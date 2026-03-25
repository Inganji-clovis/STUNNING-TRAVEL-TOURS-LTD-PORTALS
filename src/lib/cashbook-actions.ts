"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";

export async function getOpenCashDay() {
  let day = await prisma.cashDay.findFirst({
    where: { status: 'open' },
    orderBy: { date: 'desc' },
  });

  if (!day) {
    const lastDay = await prisma.cashDay.findFirst({
      where: { status: 'closed' },
      orderBy: { date: 'desc' },
    });

    const now = new Date();
    now.setHours(0, 0, 0, 0);

    day = await prisma.cashDay.create({
      data: {
        date: now,
        opening_balance: lastDay ? lastDay.closing_balance : 0,
        closing_balance: lastDay ? lastDay.closing_balance : 0,
        opening_petty_balance: lastDay ? lastDay.closing_petty_balance : 0,
        closing_petty_balance: lastDay ? lastDay.closing_petty_balance : 0,
        opening_balance_usd: lastDay ? lastDay.closing_balance_usd : 0,
        closing_balance_usd: lastDay ? lastDay.closing_balance_usd : 0,
        opening_petty_balance_usd: lastDay ? lastDay.closing_petty_balance_usd : 0,
        closing_petty_balance_usd: lastDay ? lastDay.closing_petty_balance_usd : 0,
        status: 'open',
      }
    });
  }
  return day;
}

export async function recordTransaction(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const amount = parseFloat(formData.get("amount") as string);
  const currency = formData.get("currency") as string;
  const type = formData.get("type") as string; // income, expense
  const source = formData.get("source") as string; // main, petty
  const category_id = parseInt(formData.get("category_id") as string);
  const payment_method = formData.get("payment_method") as string;
  const description = formData.get("description") as string;

  // Ticket fields
  const ttc = formData.get("ttc") ? parseFloat(formData.get("ttc") as string) : null;
  const service_fee = formData.get("service_fee") ? parseFloat(formData.get("service_fee") as string) : null;
  const supplier_name = formData.get("supplier_name") as string;

  const day = await getOpenCashDay();

  await prisma.$transaction(async (tx) => {
    // 1. Create Transaction
    await tx.cashTransaction.create({
      data: {
        cash_day_id: day.id,
        category_id,
        type,
        amount,
        currency,
        source,
        payment_method,
        description,
        ttc,
        service_fee,
        supplier_name,
        created_by: parseInt(session.user.id),
      }
    });

    // 2. Update Day Balances
    const balanceField = source === 'main' 
      ? (currency === 'RWF' ? 'closing_balance' : 'closing_balance_usd')
      : (currency === 'RWF' ? 'closing_petty_balance' : 'closing_petty_balance_usd');

    const updateAmount = type === 'income' ? amount : -amount;

    await tx.cashDay.update({
      where: { id: day.id },
      data: {
        [balanceField]: { increment: updateAmount }
      }
    });
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Sales",
      module: "Cashbook",
      action: "Create",
      target: amount.toString(),
      description: `Recorded ${type} of ${amount} ${currency} via ${source} cash`
    }
  });

  revalidatePath("/sales/cashbook");
}

export async function closeBusinessDay() {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const day = await prisma.cashDay.findFirst({
    where: { status: 'open' },
    orderBy: { date: 'desc' },
  });

  if (!day) throw new Error("No open business day found.");

  await prisma.cashDay.update({
    where: { id: day.id },
    data: {
      status: 'closed',
      closed_by: parseInt(session.user.id),
      closed_at: new Date(),
    }
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Sales",
      module: "Cashbook",
      action: "Close",
      target: day.id.toString(),
      description: `Closed business day for ${day.date.toLocaleDateString()}`
    }
  });

  revalidatePath("/sales/cashbook");
}
