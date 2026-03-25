"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";

export async function createEbmAttribute(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const company_name = formData.get("company_name") as string;
  const tin_number = formData.get("tin_number") as string;
  const description = formData.get("description") as string;
  const date = new Date(formData.get("date") as string);
  const amount_rwf = parseFloat(formData.get("amount_rwf") as string);
  
  // File upload logic would go here (e.g., to S3 or a local store mapped to public)
  // For now, we'll record the metadata.
  const file_path = "ebm_vault/placeholder.pdf"; 

  await prisma.ebm.create({
    data: {
      company_name,
      tin_number,
      description,
      date,
      amount_rwf,
      file_path,
      uploaded_by: parseInt(session.user.id),
    },
  });

  revalidatePath("/sales/ebm");
}
