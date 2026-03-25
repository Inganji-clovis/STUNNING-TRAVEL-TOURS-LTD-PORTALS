"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import fs from "fs/promises";
import path from "path";

export async function archiveRraFile(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const type = formData.get("type") as string;
  const month = formData.get("month") as string;
  const year = parseInt(formData.get("year") as string);
  const description = formData.get("description") as string;
  const file = formData.get("file") as File;

  if (!file || file.size === 0) throw new Error("File is required");

  // Save File
  const buffer = Buffer.from(await file.arrayBuffer());
  const fileName = `${Date.now()}_${file.name}`;
  const uploadDir = path.join(process.cwd(), "public", "vault", "rra", year.toString(), type);
  await fs.mkdir(uploadDir, { recursive: true });
  const filePath = path.join(uploadDir, fileName);
  await fs.writeFile(filePath, buffer);

  const relativePath = `/vault/rra/${year}/${type}/${fileName}`;

  await prisma.rraFile.create({
    data: {
      type,
      month,
      year,
      description,
      file_path: relativePath,
      uploaded_by: parseInt(session.user.id),
    }
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Sales",
      module: "RRA Vault",
      action: "Create",
      target: `${type} ${month}/${year}`,
      description: `Archived ${type} for ${month}/${year}`
    }
  });

  revalidatePath("/sales/rra");
  redirect("/sales/rra");
}
