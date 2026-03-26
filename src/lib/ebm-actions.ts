"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { z } from "zod";
import { uploadBufferToDrive } from "./google-drive";
import { ActivityLogger } from "./activity-service";

const ebmSchema = z.object({
  company_name: z.string().min(1, "Company name is required"),
  tin_number: z.string().min(1, "TIN number is required"),
  description: z.string().optional(),
  date: z.string().min(1, "Date is required"),
  amount_rwf: z.coerce.number().min(0, "Amount must be positive"),
  vat_amount: z.coerce.number().min(0, "VAT must be positive").optional(),
  billing_document_id: z.coerce.number().optional(),
});

export async function createEbmRecord(formData: FormData) {
  const session = await auth();
  if (!session?.user) {
    return { success: false, message: "Unauthorized" };
  }

  // RBAC: Sales and Admin only
  const role = (session.user as any).role;
  if (role !== "admin" && role !== "sales") {
    return { success: false, message: "Insufficient permissions to register EBMs" };
  }

  const file = formData.get("file") as File;
  if (!file || file.size === 0) {
    return { success: false, message: "EBM file is required" };
  }

  try {
    const rawData = {
      company_name: formData.get("company_name"),
      tin_number: formData.get("tin_number"),
      description: formData.get("description"),
      date: formData.get("date"),
      amount_rwf: formData.get("amount_rwf"),
      vat_amount: formData.get("vat_amount") || 0,
      billing_document_id: formData.get("billing_document_id") || undefined,
    };

    const validated = ebmSchema.parse(rawData);

    // 1. Upload to Google Drive
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(
      buffer,
      file.name,
      file.type,
      process.env.GOOGLE_DRIVE_FOLDER_ID // Assuming a central vault folder
    );

    const file_path = `gdrive://${driveFileId}`;

    // 2. Create Database Record
    const ebm = await prisma.ebm.create({
      data: {
        company_name: validated.company_name,
        tin_number: validated.tin_number,
        description: validated.description,
        date: new Date(validated.date),
        amount_rwf: validated.amount_rwf,
        vat_amount: validated.vat_amount,
        billing_document_id: validated.billing_document_id,
        file_path,
        uploaded_by: parseInt(session.user.id),
      },
    });

    // 3. Log Activity
    await ActivityLogger.log(
      "Sales",
      "EBM Registry",
      "Create",
      validated.tin_number,
      `Archived EBM for ${validated.company_name} (Amount: ${validated.amount_rwf} RWF)`
    );

    revalidatePath("/sales/ebm");
    return { success: true, message: "EBM record archived successfully on Google Drive" };
  } catch (error) {
    if (error instanceof z.ZodError) {
      return { success: false, message: error.errors[0].message };
    }
    console.error("EBM Error:", error);
    return { success: false, message: "Internal server error while archiving EBM" };
  }
}
