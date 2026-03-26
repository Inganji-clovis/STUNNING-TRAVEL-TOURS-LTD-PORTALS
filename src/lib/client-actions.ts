"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { z } from "zod";
import { ActivityLogger } from "./activity-service";

const clientSchema = z.object({
  id: z.coerce.number().optional(),
  name: z.string().min(1, "Name is required"),
  email: z.string().email("Invalid email").optional().or(z.literal("")),
  phone: z.string().optional(),
  membership_type: z.enum(["standard", "gold", "platinum", "corporate"]),
  tin: z.string().optional(),
  organization_id: z.coerce.number().optional().nullable(),
});

const organizationSchema = z.object({
  name: z.string().min(1, "Organization name is required"),
  tin: z.string().optional(),
  email: z.string().email().optional().or(z.literal("")),
  phone: z.string().optional(),
  address: z.string().optional(),
});

export async function upsertClient(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  try {
    const rawData = {
      id: formData.get("id"),
      name: formData.get("name"),
      email: formData.get("email"),
      phone: formData.get("phone"),
      membership_type: formData.get("membership_type"),
      tin: formData.get("tin"),
      organization_id: formData.get("organization_id") || null,
    };

    const validated = clientSchema.parse(rawData);

    if (validated.id) {
      // Update
      await prisma.client.update({
        where: { id: validated.id },
        data: {
          name: validated.name,
          email: validated.email,
          phone: validated.phone,
          membership_type: validated.membership_type,
          tin: validated.tin,
          organization_id: validated.organization_id,
        }
      });

      await ActivityLogger.log(
        "Operations",
        "Client Registry",
        "Update",
        validated.name,
        `Updated details for client ${validated.name}`
      );

      revalidatePath("/ops/clients");
      return { success: true, message: "Client profile updated" };
    } else {
      // Create
      const client = await prisma.client.create({
        data: {
          name: validated.name,
          email: validated.email,
          phone: validated.phone,
          membership_type: validated.membership_type,
          tin: validated.tin,
          organization_id: validated.organization_id,
          registered_by: parseInt(session.user.id),
        }
      });

      await ActivityLogger.log(
        "Operations",
        "Client Registry",
        "Create",
        validated.name,
        `Registered new client: ${validated.name}`
      );

      revalidatePath("/ops/clients");
      return { success: true, message: "New client registered successfully" };
    }
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    return { success: false, message: "Operation failed" };
  }
}

export async function upsertOrganization(formData: FormData) {
    const session = await auth();
    if (!session?.user) return { success: false, message: "Unauthorized" };

    try {
        const validated = organizationSchema.parse({
            name: formData.get("name"),
            tin: formData.get("tin"),
            email: formData.get("email"),
            phone: formData.get("phone"),
            address: formData.get("address"),
        });

        const org = await prisma.organization.create({
            data: validated
        });

        await ActivityLogger.log(
            "Operations",
            "Organization Registry",
            "Create",
            validated.name,
            `Registered new corporate partner: ${validated.name}`
        );

        revalidatePath("/ops/clients");
        return { success: true, message: "Organization created", id: org.id };
    } catch (error) {
        if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
        return { success: false, message: "Failed to create organization" };
    }
}
