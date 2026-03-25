"use server";

import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { getGoogleDriveClient, createFolderIfNotExist, uploadFileToDrive } from "./google-drive";
import { revalidatePath } from "next/cache";

export async function startCloudSync(formData: FormData) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const year = parseInt(formData.get("year") as string);
  const month = parseInt(formData.get("month") as string);

  // Check limits (max 3 per day as per legacy)
  const todayStart = new Date();
  todayStart.setHours(0, 0, 0, 0);
  
  const dailyCount = await prisma.cloudSync.count({
    where: {
      status: 'completed',
      created_at: { gte: todayStart }
    }
  });

  if (dailyCount >= 3) {
    throw new Error("Daily synchronization limit reached (3). Please wait until tomorrow.");
  }

  // Initial record
  const syncRecord = await prisma.cloudSync.create({
    data: {
      year,
      month,
      status: 'processing',
      synced_by: parseInt(session.user.id),
    }
  });

  try {
    const drive = await getGoogleDriveClient();
    const rootFolderId = process.env.GOOGLE_DRIVE_FOLDER_ID;
    
    // Create Archive Folder
    const archiveName = `stunning_portal_${year}_${month}_Archive_${syncRecord.id}`;
    const archiveFolderId = await createFolderIfNotExist(drive, archiveName, rootFolderId);

    // 1. Sync Invoices (Placeholder for PDF generation)
    // 2. Sync Tickets
    const tickets = await prisma.ticket.findMany({
      where: {
        created_at: {
          gte: new Date(year, month - 1, 1),
          lt: new Date(year, month, 1)
        }
      }
    });

    if (tickets.length > 0) {
        const ticketFolderId = await createFolderIfNotExist(drive, "Tickets", archiveFolderId);
        for (const ticket of tickets) {
            // In a real scenario, we'd read the local file content
            // For now, we'll sync metadata or a small placeholder
            await uploadFileToDrive(
                drive, 
                `Ticket_${ticket.pnr}.txt`, 
                `Ticket PNR: ${ticket.pnr}\nClient: ${ticket.client_names}`, 
                'text/plain', 
                ticketFolderId
            );
        }
    }

    // 3. Sync EBMs
    // 4. Sync Passports

    // Finalize
    await prisma.cloudSync.update({
      where: { id: syncRecord.id },
      data: { status: 'completed' }
    });

  } catch (error: any) {
    await prisma.cloudSync.update({
      where: { id: syncRecord.id },
      data: { 
        status: 'failed',
        error_log: error.message 
      }
    });
    console.error("Cloud Sync Error:", error);
  }

  revalidatePath("/admin/cloud-sync");
}
