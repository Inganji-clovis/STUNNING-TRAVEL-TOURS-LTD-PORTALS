"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import bcrypt from "bcryptjs";

export async function registerStaff(formData: FormData) {
  const session = await auth();
  if (!session?.user || session.user.role !== 'admin') {
    throw new Error("Unauthorized: Administrative privileges required.");
  }

  const name = formData.get("name") as string;
  const username = formData.get("username") as string;
  const email = formData.get("email") as string;
  const phone = formData.get("phone") as string;
  const role = formData.get("role") as string;
  const password = formData.get("password") as string;

  // 1. Hash Password
  const hashedPassword = await bcrypt.hash(password, 10);

  // 2. Create User
  const user = await prisma.user.create({
    data: {
      name,
      username,
      email,
      phone,
      role,
      password: hashedPassword,
      is_active: true,
    }
  });

  // 3. Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Admin",
      module: "Personnel Provisioning",
      action: "Create",
      target: user.username || user.email,
      description: `Provisioned new staff ${user.name} with role ${user.role}`
    }
  });

  revalidatePath("/admin/users");
  revalidatePath("/md/employees");
  redirect("/admin/users");
}

export async function toggleStaffStatus(id: number) {
  const session = await auth();
  if (!session?.user || session.user.role !== 'admin') {
    throw new Error("Unauthorized");
  }

  const user = await prisma.user.findUnique({ where: { id } });
  if (!user) throw new Error("User not found");

  const updatedUser = await prisma.user.update({
    where: { id },
    data: { is_active: !user.is_active }
  });

  // Log Activity
  await prisma.activityLog.create({
    data: {
      user_id: parseInt(session.user.id),
      portal: "Admin",
      module: "Personnel Management",
      action: "Update",
      target: updatedUser.username || updatedUser.email,
      description: `Toggled status for ${updatedUser.name} to ${updatedUser.is_active ? 'Active' : 'Suspended'}`
    }
  });

  revalidatePath("/admin/users");
  revalidatePath("/md/employees");
}
