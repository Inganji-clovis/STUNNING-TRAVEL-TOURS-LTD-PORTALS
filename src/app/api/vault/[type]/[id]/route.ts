import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { NextResponse } from "next/server";
import { readFile } from "fs/promises";
import path from "path";
import { existsSync } from "fs";
import { getGoogleDriveClient, getFileStreamFromDrive } from "@/lib/google-drive";

const mimeMap: Record<string, string> = {
  ".pdf": "application/pdf",
  ".jpg": "image/jpeg",
  ".jpeg": "image/jpeg",
  ".png": "image/png",
  ".doc": "application/msword",
  ".docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  ".xls": "application/vnd.ms-excel",
  ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
};

async function serveFile(filePath: string, fileName: string = "document") {
  // Google Drive-backed file
  if (filePath.startsWith("gdrive://")) {
    const fileId = filePath.replace("gdrive://", "");
    const drive = await getGoogleDriveClient();
    const buffer = await getFileStreamFromDrive(drive, fileId);
    const ext = path.extname(fileName).toLowerCase();
    const mimeType = mimeMap[ext] || "application/octet-stream";
    return new NextResponse(buffer, {
      headers: {
        "Content-Type": mimeType,
        "Content-Disposition": `inline; filename="${fileName}"`,
      },
    });
  }

  // Legacy local file (fallback for pre-migration records)
  let fullPath = path.join(/*turbopackIgnore: true*/ process.cwd(), filePath);
  if (!existsSync(fullPath)) {
    fullPath = path.join(/*turbopackIgnore: true*/ process.cwd(), "public", filePath);
  }
  if (!existsSync(fullPath)) {
    return new NextResponse("File not found on server", { status: 404 });
  }
  const fileBuffer = await readFile(fullPath);
  const ext = path.extname(fullPath).toLowerCase();
  const mimeType = mimeMap[ext] || "application/octet-stream";
  return new NextResponse(fileBuffer, {
    headers: {
      "Content-Type": mimeType,
      "Content-Disposition": `inline; filename="${path.basename(fullPath)}"`,
    },
  });
}

export async function GET(
  request: Request,
  { params }: { params: Promise<{ type: string; id: string }> }
) {
  const session = await auth();
  if (!session) {
    return new NextResponse("Unauthorized", { status: 401 });
  }

  const { type, id } = await params;
  const docId = parseInt(id);

  try {
    switch (type) {
      case "passport": {
        const doc = await prisma.travelDocument.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, `passport_${docId}.pdf`);
      }
      case "ticket": {
        const doc = await prisma.ticket.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, `ticket_${doc.ticket_number}`);
      }
      case "ebm": {
        const doc = await prisma.ebm.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, `ebm_${docId}.pdf`);
      }
      case "tax-declaration": {
        const doc = await prisma.taxCompliance.findUnique({ where: { id: docId } });
        if (!doc?.declaration_file) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.declaration_file, `tax_declaration_${doc.declaration_number}.pdf`);
      }
      case "tax-receipt": {
        const doc = await prisma.taxCompliance.findUnique({ where: { id: docId } });
        if (!doc?.receipt_file) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.receipt_file, `tax_receipt_${doc.declaration_number}.pdf`);
      }
      case "corporate": {
        const doc = await prisma.corporateDocument.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, `corporate_${doc.title}`);
      }
      case "folder-file": {
        const doc = await prisma.corporateFile.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, doc.name);
      }
      case "rra": {
        const doc = await prisma.rraFile.findUnique({ where: { id: docId } });
        if (!doc?.file_path) return new NextResponse("Not found", { status: 404 });
        return serveFile(doc.file_path, `rra_${docId}.pdf`);
      }
      default:
        return new NextResponse("Invalid document type", { status: 400 });
    }
  } catch (error) {
    console.error("Vault Access Error:", error);
    return new NextResponse("Internal Server Error", { status: 500 });
  }
}
