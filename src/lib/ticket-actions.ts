"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { revalidatePath } from "next/cache";
import { z } from "zod";
import { getGoogleDriveClient, uploadBufferToDrive, createFolderIfNotExist } from "./google-drive";
import { ActivityLogger } from "./activity-service";

const ticketSchema = z.object({
  pnr: z.string().min(1, "PNR is required"),
  ticket_number: z.string().min(1, "Ticket number is required"),
  client_names: z.string().min(1, "Client name is required"),
  departure_date: z.string().min(1, "Departure date is required"),
  return_date: z.string().optional().nullable(),
  flight_number: z.string().min(1, "Flight number is required"),
  airline_name: z.string().min(1, "Airline name is required"),
  status: z.enum(["issued", "cancelled", "void"]),
  remarks: z.string().optional(),
});

export async function createTicket(formData: FormData) {
  const session = await auth();
  if (!session?.user) return { success: false, message: "Unauthorized" };

  const file = formData.get("file") as File;
  if (!file || file.size === 0) return { success: false, message: "Ticket PDF/Scan is required" };

  try {
    const rawData = {
      pnr: formData.get("pnr"),
      ticket_number: formData.get("ticket_number"),
      client_names: formData.get("client_names"),
      departure_date: formData.get("departure_date"),
      return_date: formData.get("return_date") || null,
      flight_number: formData.get("flight_number"),
      airline_name: formData.get("airline_name"),
      status: formData.get("status") || "issued",
      remarks: formData.get("remarks"),
    };

    const validated = ticketSchema.parse(rawData);

    // 1. Google Drive Integration
    const drive = await getGoogleDriveClient();
    const folderId = await createFolderIfNotExist(drive, "TICKET_ARCHIVE", process.env.GOOGLE_DRIVE_FOLDER_ID);
    
    const buffer = Buffer.from(await file.arrayBuffer());
    const driveFileId = await uploadBufferToDrive(drive, `TKT_${validated.pnr}_${file.name}`, buffer, file.type, folderId);

    // 2. Database Record
    await prisma.ticket.create({
      data: {
        pnr: validated.pnr,
        ticket_number: validated.ticket_number,
        client_names: validated.client_names,
        departure_date: new Date(validated.departure_date),
        return_date: validated.return_date ? new Date(validated.return_date) : null,
        flight_number: validated.flight_number,
        airline_name: validated.airline_name,
        status: validated.status,
        remarks: validated.remarks,
        file_path: `gdrive://${driveFileId}`,
        created_by: parseInt(session.user.id),
      }
    });

    await ActivityLogger.log(
      "Operations",
      "Ticket Manager",
      "Create",
      validated.pnr,
      `Issued ticket ${validated.ticket_number} (PNR: ${validated.pnr}) for ${validated.client_names}`
    );

    revalidatePath("/ops/tickets");
    return { success: true, message: "Ticket issued and archived successfully on cloud" };
  } catch (error) {
    if (error instanceof z.ZodError) return { success: false, message: error.errors[0].message };
    console.error("Ticket Error:", error);
    return { success: false, message: "Failed to archive ticket" };
  }
}

export async function updateTicketStatus(id: number, status: "issued" | "cancelled" | "void") {
    const session = await auth();
    if (!session?.user) return { success: false, message: "Unauthorized" };

    const ticket = await prisma.ticket.update({
        where: { id },
        data: { status }
    });

    await ActivityLogger.log(
        "Operations",
        "Ticket Manager",
        "Update Status",
        ticket.pnr,
        `Ticket ${ticket.pnr} status changed to ${status.toUpperCase()}`
    );

    revalidatePath("/ops/tickets");
    return { success: true, message: `Ticket status updated to ${status}` };
}
