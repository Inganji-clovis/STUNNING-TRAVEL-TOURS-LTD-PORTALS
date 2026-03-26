"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { z } from "zod";
import { getGoogleDriveClient, uploadBufferToDrive, createFolderIfNotExist } from "./google-drive";
import { ActivityLogger } from "./activity-service";

const passportSchema = z.object({
  client_id: z.coerce.number(),
  passport_number: z.string().min(1, "Passport number is required"),
  country_code: z.string().optional(),
  nationality: z.string().optional(),
  dob: z.string().optional(),
  place_of_birth: z.string().optional(),
  issue_date: z.string().optional(),
  place_of_issue: z.string().optional(),
  expiry_date: z.string().optional(),
  gender: z.enum(["M", "F", "O"]).optional(),
});

export async function uploadPassport(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  const file = formData.get("file") as File;
  if (!file || file.size === 0) return { success: false, message: "Passport scan is required" };

  try {
    const rawData = {
      client_id: formData.get("client_id"),
      passport_number: formData.get("passport_number"),
      country_code: formData.get("country_code"),
      nationality: formData.get("nationality"),
      dob: formData.get("dob"),
      place_of_birth: formData.get("place_of_birth"),
      issue_date: formData.get("issue_date"),
      place_of_issue: formData.get("place_of_issue"),
      expiry_date: formData.get("expiry_date"),
      gender: formData.get("gender"),
    };

    const validated = passportSchema.parse(rawData);

    // 1. Google Drive Integration
    const drive = await getGoogleDriveClient();
    const folderId = await createFolderIfNotExist(drive, "PASSPORT_VAULT", process.env.GOOGLE_DRIVE_FOLDER_ID);
    
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(drive, `${validated.passport_number}_${file.name}`, buffer, file.type, folderId);

    // 2. Database Record
    await prisma.travelDocument.create({
      data: {
        client_id: validated.client_id,
        type: "passport",
        passport_number: validated.passport_number,
        country_code: validated.country_code,
        nationality: validated.nationality,
        dob: validated.dob ? new Date(validated.dob) : null,
        place_of_birth: validated.place_of_birth,
        issue_date: validated.issue_date ? new Date(validated.issue_date) : null,
        place_of_issue: validated.place_of_issue,
        expiry_date: validated.expiry_date ? new Date(validated.expiry_date) : null,
        gender: validated.gender,
        file_path: `gdrive://${driveFileId}`,
      }
    });

    await ActivityLogger.log(
      "Operations",
      "Passport Vault",
      "Upload",
      validated.passport_number,
      `Vaulted passport ${validated.passport_number} for client ID ${validated.client_id}`
    );

    revalidatePath("/ops/passports");
    return { success: true, message: "Passport safely archived in cloud vault" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    console.error("Passport Error:", error);
    return { success: false, message: "Failed to archive passport" };
  }
}
