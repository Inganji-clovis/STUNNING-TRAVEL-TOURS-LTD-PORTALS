"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import fs from "fs/promises";
import path from "path";

export async function declareTax(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const type = formData.get("type") as string;
  const declaration_number = formData.get("declaration_number") as string;
  const declared_amount = parseFloat(formData.get("declared_amount") as string);
  const file = formData.get("declaration_file") as File;

  if (!file || file.size === 0) throw new Error("Declaration file is required");

  // Save File
  const buffer = Buffer.from(await file.arrayBuffer());
  const fileName = `${Date.now()}_${file.name}`;
  const uploadDir = path.join(process.cwd(), "public", "vault", "tax", type, "declarations");
  await fs.mkdir(uploadDir, { recursive: true });
  const filePath = path.join(uploadDir, fileName);
  await fs.writeFile(filePath, buffer);

  const relativePath = `/vault/tax/${type}/declarations/${fileName}`;

  const record = await prisma.taxCompliance.create({
    data: {
      type,
      declaration_number,
      declared_amount,
      declaration_file: relativePath,
      uploaded_by: parseInt(session.user.id),
    }
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Sales",
      module: "Tax Hub",
      action: "Create",
      target: declaration_number,
      description: `Filed ${type} tax compliance (Phase 1)`
    }
  });

  revalidatePath("/sales/tax");
  redirect(`/sales/tax?type=${type}`);
}

export async function payTax(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const id = parseInt(formData.get("id") as string);
  const receipt_amount = parseFloat(formData.get("receipt_amount") as string);
  const payment_date = new Date(formData.get("payment_date") as string);
  const file = formData.get("receipt_file") as File;

  const tax = await prisma.taxCompliance.findUnique({ where: { id } });
  if (!tax) throw new Error("Record not found");

  if (receipt_amount !== Number(tax.declared_amount)) {
    throw new Error("Inconsistent amounts: Receipt amount must precisely match declared amount.");
  }

  // Save File
  const buffer = Buffer.from(await file.arrayBuffer());
  const fileName = `${Date.now()}_${file.name}`;
  const uploadDir = path.join(process.cwd(), "public", "vault", "tax", tax.type, "receipts");
  await fs.mkdir(uploadDir, { recursive: true });
  const filePath = path.join(uploadDir, fileName);
  await fs.writeFile(filePath, buffer);

  const relativePath = `/vault/tax/${tax.type}/receipts/${fileName}`;

  await prisma.taxCompliance.update({
    where: { id },
    data: {
      receipt_amount,
      payment_date,
      receipt_file: relativePath,
    }
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Sales",
      module: "Tax Hub",
      action: "Update",
      target: tax.declaration_number,
      description: `Paid ${tax.type} tax compliance (Phase 2)`
    }
  });

  revalidatePath("/sales/tax");
  redirect(`/sales/tax?type=${tax.type}`);
}
