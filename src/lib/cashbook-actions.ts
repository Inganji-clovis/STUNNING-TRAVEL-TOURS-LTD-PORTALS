"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { z } from "zod";
import { ActivityLogger } from "./activity-service";

const transactionSchema = z.object({
  cash_day_id: z.coerce.number(),
  category_id: z.coerce.number(),
  amount: z.coerce.number().min(0, "Amount must be positive"),
  currency: z.enum(["RWF", "USD"]),
  source: z.enum(["main", "petty"]),
  type: z.enum(["income", "expense"]),
  payment_method: z.string().optional(),
  description: z.string().optional(),
  ttc: z.coerce.number().optional().nullable(),
  service_fee: z.coerce.number().optional().nullable(),
  supplier_name: z.string().optional().nullable(),
});

const openDaySchema = z.object({
  date: z.string(),
  opening_balance: z.coerce.number().min(0),
  opening_petty_balance: z.coerce.number().min(0),
  opening_balance_usd: z.coerce.number().min(0),
  opening_petty_balance_usd: z.coerce.number().min(0),
});

export async function openBusinessDay(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  try {
    const validated = openDaySchema.parse({
      date: formData.get("date"),
      opening_balance: formData.get("opening_balance"),
      opening_petty_balance: formData.get("opening_petty_balance"),
      opening_balance_usd: formData.get("opening_balance_usd"),
      opening_petty_balance_usd: formData.get("opening_petty_balance_usd"),
    });

    const day = await prisma.cashDay.create({
      data: {
        date: new Date(validated.date),
        opening_balance: validated.opening_balance,
        closing_balance: validated.opening_balance,
        opening_petty_balance: validated.opening_petty_balance,
        closing_petty_balance: validated.opening_petty_balance,
        opening_balance_usd: validated.opening_balance_usd,
        closing_balance_usd: validated.opening_balance_usd,
        opening_petty_balance_usd: validated.opening_petty_balance_usd,
        closing_petty_balance_usd: validated.opening_petty_balance_usd,
        status: 'open',
      }
    });

    await ActivityLogger.log(
      "Sales",
      "Cashbook",
      "Open Day",
      day.id.toString(),
      `Opened financial day for ${validated.date}`
    );

    revalidatePath("/sales/cashbook");
    return { success: true, message: "Financial day initiated successfully" };
  } catch (error) {
    return { success: false, message: "Failed to open financial day" };
  }
}

export async function recordTransaction(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  try {
    const rawData = {
      cash_day_id: formData.get("cash_day_id"),
      category_id: formData.get("category_id"),
      amount: formData.get("amount"),
      currency: formData.get("currency"),
      source: formData.get("source"),
      type: formData.get("type"),
      payment_method: formData.get("payment_method"),
      description: formData.get("description"),
      ttc: formData.get("ttc"),
      service_fee: formData.get("service_fee"),
      supplier_name: formData.get("supplier_name"),
    };

    const validated = transactionSchema.parse(rawData);

    const day = await prisma.cashDay.findUnique({ where: { id: validated.cash_day_id } });
    if (!day || day.status === 'closed') {
      return { success: false, message: "Cannot record transaction on a closed day" };
    }

    const category = await prisma.cashCategory.findUnique({ where: { id: validated.category_id } });
    if (!category) return { success: false, message: "Invalid category" };

    // Ticketing Logic: RC = TTC + Service Fee
    if (category.name.toLowerCase().includes('ticket')) {
      const rc = validated.amount;
      const ttc = validated.ttc || 0;
      const fee = validated.service_fee || 0;
      
      if (Math.abs(rc - (ttc + fee)) > 0.01) {
        return { success: false, message: "Ticketing Error: Amount (RC) must exactly equal TTC + Service Fee" };
      }
    }

    await prisma.$transaction(async (tx) => {
      await tx.cashTransaction.create({
        data: {
          cash_day_id: day.id,
          category_id: validated.category_id,
          type: validated.type,
          amount: validated.amount,
          currency: validated.currency,
          source: validated.source,
          payment_method: validated.payment_method,
          description: validated.description,
          ttc: validated.ttc,
          service_fee: validated.service_fee,
          supplier_name: validated.supplier_name,
          created_by: parseInt(session.user.id),
        }
      });

      const balanceField = validated.source === 'main' 
        ? (validated.currency === 'RWF' ? 'closing_balance' : 'closing_balance_usd')
        : (validated.currency === 'RWF' ? 'closing_petty_balance' : 'closing_petty_balance_usd');

      const updateAmount = validated.type === 'income' ? validated.amount : -validated.amount;

      await tx.cashDay.update({
        where: { id: day.id },
        data: { [balanceField]: { increment: updateAmount } }
      });
    });

    await ActivityLogger.log(
      "Sales",
      "Cashbook",
      "Record",
      validated.amount.toString(),
      `Recorded ${validated.type} of ${validated.amount} ${validated.currency} (${category.name})`
    );

    revalidatePath("/sales/cashbook");
    return { success: true, message: "Transaction committed to ledger" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    return { success: false, message: "Internal server error" };
  }
}

export async function closeBusinessDay(formData: FormData) {
  const session = await auth();
  if (!session?.user || (session.user as any).role !== 'admin') {
    throw new Error("Only administrators can close the financial day.");
  }

  const dayId = parseInt(formData.get("day_id") as string);

  await prisma.cashDay.update({
    where: { id: dayId },
    data: {
      status: 'closed',
      closed_by: parseInt(session.user.id),
      closed_at: new Date(),
    }
  });

  await ActivityLogger.log(
    "Sales",
    "Cashbook",
    "Close Day",
    dayId.toString(),
    `Closed business day and locked transactions`
  );

  revalidatePath("/sales/cashbook");
}
