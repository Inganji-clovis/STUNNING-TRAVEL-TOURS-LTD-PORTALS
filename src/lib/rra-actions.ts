"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { z } from "zod";
import { getGoogleDriveClient, uploadBufferToDrive, createFolderIfNotExist } from "./google-drive";
import { ActivityLogger } from "./activity-service";

const rraSchema = z.object({
  type: z.string().min(1),
  month: z.string().min(1),
  year: z.coerce.number(),
  description: z.string().optional(),
});

export async function archiveRraFile(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  const file = formData.get("file") as File;
  if (!file || file.size === 0) return { success: false, message: "Document file is required" };

  try {
    const validated = rraSchema.parse({
      type: formData.get("type"),
      month: formData.get("month"),
      year: formData.get("year"),
      description: formData.get("description"),
    });

    // 1. Google Drive Integration
    const drive = await getGoogleDriveClient();
    const folderId = await createFolderIfNotExist(drive, "RRA_VAULT", process.env.GOOGLE_DRIVE_FOLDER_ID);
    
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(drive, `RRA_${validated.type}_${validated.month}_${validated.year}_${file.name}`, buffer, file.type, folderId);

    // 2. Database Record
    await prisma.rraFile.create({
      data: {
        type: validated.type,
        month: validated.month,
        year: validated.year,
        description: validated.description,
        file_path: `gdrive://${driveFileId}`,
        uploaded_by: parseInt(session.user.id),
      }
    });

    await ActivityLogger.log(
      "Sales",
      "RRA Vault",
      "Create",
      `${validated.type} ${validated.month}/${validated.year}`,
      `Archived ${validated.type} doc for ${validated.month}/${validated.year}`
    );

    revalidatePath("/sales/rra");
    return { success: true, message: "Document safely vaulted in cloud" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    console.error("RRA Vault Error:", error);
    return { success: false, message: "Failed to archive document" };
  }
}
