"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { z } from "zod";
import { getGoogleDriveClient, uploadBufferToDrive, createFolderIfNotExist } from "./google-drive";
import { ActivityLogger } from "./activity-service";

const declareSchema = z.object({
  type: z.string().min(1),
  declaration_number: z.string().min(1),
  declared_amount: z.coerce.number().min(0),
});

const paySchema = z.object({
  id: z.coerce.number(),
  receipt_amount: z.coerce.number().min(0),
  payment_date: z.string().min(1),
});

export async function declareTax(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  const file = formData.get("declaration_file") as File;
  if (!file || file.size === 0) return { success: false, message: "Declaration file is required" };

  try {
    const validated = declareSchema.parse({
      type: formData.get("type"),
      declaration_number: formData.get("declaration_number"),
      declared_amount: formData.get("declared_amount"),
    });

    // 1. Google Drive Integration
    const drive = await getGoogleDriveClient();
    const folderId = await createFolderIfNotExist(drive, "TAX_DECLARATIONS", process.env.GOOGLE_DRIVE_FOLDER_ID);
    
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(drive, `DECL_${validated.declaration_number}_${file.name}`, buffer, file.type, folderId);

    // 2. Database Record
    await prisma.taxCompliance.create({
      data: {
        type: validated.type,
        declaration_number: validated.declaration_number,
        declared_amount: validated.declared_amount,
        declaration_file: `gdrive://${driveFileId}`,
        uploaded_by: parseInt(session.user.id),
      }
    });

    await ActivityLogger.log(
      "Sales",
      "Tax Hub",
      "Create",
      validated.declaration_number,
      `Filed ${validated.type} tax compliance (Phase 1: Declaration)`
    );

    revalidatePath("/sales/tax");
    return { success: true, message: "Declaration filed and archived on cloud" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    console.error("Tax Declaration Error:", error);
    return { success: false, message: "Failed to process declaration" };
  }
}

export async function payTax(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  const file = formData.get("receipt_file") as File;
  if (!file || file.size === 0) return { success: false, message: "Receipt proof is required" };

  try {
    const validated = paySchema.parse({
      id: formData.get("id"),
      receipt_amount: formData.get("receipt_amount"),
      payment_date: formData.get("payment_date"),
    });

    const tax = await prisma.taxCompliance.findUnique({ where: { id: validated.id } });
    if (!tax) return { success: false, message: "Record not found" };

    if (Math.abs(validated.receipt_amount - Number(tax.declared_amount)) > 0.01) {
      return { success: false, message: "Amount Mismatch: Receipt must match declared value." };
    }

    // 1. Google Drive Integration
    const drive = await getGoogleDriveClient();
    const folderId = await createFolderIfNotExist(drive, "TAX_RECEIPTS", process.env.GOOGLE_DRIVE_FOLDER_ID);
    
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(drive, `RCPT_${tax.declaration_number}_${file.name}`, buffer, file.type, folderId);

    // 2. Update Record
    await prisma.taxCompliance.update({
      where: { id: validated.id },
      data: {
        receipt_amount: validated.receipt_amount,
        payment_date: new Date(validated.payment_date),
        receipt_file: `gdrive://${driveFileId}`,
      }
    });

    await ActivityLogger.log(
      "Sales",
      "Tax Hub",
      "Update",
      tax.declaration_number,
      `Paid ${tax.type} tax compliance (Phase 2: Payment Proof)`
    );

    revalidatePath("/sales/tax");
    return { success: true, message: "Payment proof uploaded and verified" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    console.error("Tax Payment Error:", error);
    return { success: false, message: "Failed to process payment proof" };
  }
}
