"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { getGoogleDriveClient, uploadBufferToDrive, createFolderIfNotExist } from "./google-drive";
import path from "path";
import { existsSync } from "fs";
import { copyFile, mkdir } from "fs/promises";

const VAULT_ROOT = process.env.GOOGLE_DRIVE_FOLDER_ID!;

async function uploadFileToDriveVault(file: File, subDir: string): Promise<string> {
  const bytes = await file.arrayBuffer();
  const buffer = Buffer.from(bytes);

  const drive = await getGoogleDriveClient();

  // Ensure sub-folder exists inside the root vault folder
  const folderId = await createFolderIfNotExist(drive, `vault/${subDir}`, VAULT_ROOT);

  const safeName = `${Date.now()}_${file.name.replace(/\s+/g, '_')}`;
  const mimeType = file.type || "application/octet-stream";

  const fileId = await uploadBufferToDrive(drive, safeName, buffer, mimeType, folderId);

  // Store the Drive file ID with a prefix so the proxy knows to stream from GDrive
  return `gdrive://${fileId}`;
}

export async function archiveCorporateDocument(formData: FormData) {
  const session = await auth();
  if (!session?.user || (session.user.role !== 'admin' && session.user.role !== 'md')) {
    throw new Error("Unauthorized");
  }

  const title = formData.get("title") as string;
  const category = formData.get("category") as string;
  const description = formData.get("description") as string;
  const file = formData.get("file") as File;

  if (!file || file.size === 0) throw new Error("File is required");

  const filePath = await uploadFileToDriveVault(file, `corporate/${category}`);

  await prisma.corporateDocument.create({
    data: {
      title,
      category,
      description,
      file_path: filePath,
      uploaded_by: parseInt(session.user.id),
    }
  });

  revalidatePath("/md/vault");
}

export async function createCorporateFolder(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const name = formData.get("name") as string;
  const type = formData.get("type") as string;
  const client_id = formData.get("client_id") ? parseInt(formData.get("client_id") as string) : null;
  const organization_id = formData.get("organization_id") ? parseInt(formData.get("organization_id") as string) : null;

  await prisma.corporateFolder.create({
    data: {
      name,
      type,
      client_id,
      organization_id,
      year: new Date().getFullYear().toString(),
      created_by: parseInt(session.user.id),
    }
  });

  revalidatePath("/md/folders");
}

export async function uploadToCorporateFolder(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const folderId = parseInt(formData.get("folder_id") as string);
  const name = formData.get("name") as string;
  const file = formData.get("file") as File;

  if (!file || file.size === 0) throw new Error("File is required");

  const folder = await prisma.corporateFolder.findUnique({ where: { id: folderId } });
  if (!folder) throw new Error("Folder not found");

  const filePath = await uploadFileToDriveVault(file, `md_vault/${folder.type}/${folderId}`);

  await prisma.corporateFile.create({
    data: {
      folder_id: folderId,
      name,
      file_path: filePath,
      file_type: "uploaded",
      uploaded_by: parseInt(session.user.id),
    }
  });

  revalidatePath(`/md/folders/${folderId}`);
}

export async function importCrossPortalFiles(folderId: number, fileRefs: string[]) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const folder = await prisma.corporateFolder.findUnique({ where: { id: folderId } });
  if (!folder) throw new Error("Folder not found");

  const drive = await getGoogleDriveClient();
  const destFolderId = await createFolderIfNotExist(drive, `md_vault/${folder.type}/${folderId}`, VAULT_ROOT);

  for (const ref of fileRefs) {
    const [source, name, originalPath] = ref.split("||");

    // If the source file is already on Drive, re-reference it directly
    if (originalPath.startsWith("gdrive://")) {
      await prisma.corporateFile.create({
        data: {
          folder_id: folderId,
          name: `${name} (Imported from ${source})`,
          file_path: originalPath,
          file_type: "cross_portal_import",
          uploaded_by: parseInt(session.user.id),
        }
      });
      continue;
    }

    // Legacy local path — read and re-upload to Drive
    let fullPath = path.join(process.cwd(), originalPath);
    if (!existsSync(fullPath)) {
      fullPath = path.join(process.cwd(), "public", originalPath);
    }

    if (existsSync(fullPath)) {
      const { readFile } = await import("fs/promises");
      const buffer = await readFile(fullPath);
      const fileName = `imported_${Date.now()}_${path.basename(fullPath)}`;

      const fileId = await uploadBufferToDrive(drive, fileName, buffer, "application/octet-stream", destFolderId);

      await prisma.corporateFile.create({
        data: {
          folder_id: folderId,
          name: `${name} (Imported from ${source})`,
          file_path: `gdrive://${fileId}`,
          file_type: "cross_portal_import",
          uploaded_by: parseInt(session.user.id),
        }
      });
    }
  }

  revalidatePath(`/md/folders/${folderId}`);
}
