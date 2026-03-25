import { google } from 'googleapis';

export async function getGoogleDriveClient() {
  const oauth2Client = new google.auth.OAuth2(
    process.env.GOOGLE_DRIVE_CLIENT_ID,
    process.env.GOOGLE_DRIVE_CLIENT_SECRET,
    process.env.GOOGLE_DRIVE_REDIRECT_URI || 'https://developers.google.com/oauthplayground'
  );

  oauth2Client.setCredentials({
    refresh_token: process.env.GOOGLE_DRIVE_REFRESH_TOKEN,
  });

  return google.drive({ version: 'v3', auth: oauth2Client });
}

export async function uploadFileToDrive(
  drive: any, 
  fileName: string, 
  content: Buffer | string, 
  mimeType: string, 
  parentFolderId?: string
) {
  const fileMetadata = {
    name: fileName,
    parents: parentFolderId ? [parentFolderId] : [],
  };

  const media = {
    mimeType: mimeType,
    body: content,
  };

  const response = await drive.files.create({
    requestBody: fileMetadata,
    media: media,
    fields: 'id',
  });

  return response.data.id as string;
}

export async function uploadBufferToDrive(
  drive: any,
  fileName: string,
  buffer: Buffer,
  mimeType: string,
  parentFolderId?: string
): Promise<string> {
  const { Readable } = await import('stream');
  const stream = Readable.from(buffer);

  const fileMetadata = {
    name: fileName,
    parents: parentFolderId ? [parentFolderId] : [],
  };

  const response = await drive.files.create({
    requestBody: fileMetadata,
    media: { mimeType, body: stream },
    fields: 'id',
  });

  return response.data.id as string;
}

export async function getFileStreamFromDrive(drive: any, fileId: string) {
  const response = await drive.files.get(
    { fileId, alt: 'media' },
    { responseType: 'arraybuffer' }
  );
  return Buffer.from(response.data as ArrayBuffer);
}

export async function createFolderIfNotExist(drive: any, folderName: string, parentFolderId?: string) {
    // Search for existing folder
    const query = `name = '${folderName}' and mimeType = 'application/vnd.google-apps.folder' and trashed = false ${parentFolderId ? `and '${parentFolderId}' in parents` : ''}`;
    const res = await drive.files.list({
        q: query,
        fields: 'files(id, name)',
        spaces: 'drive',
    });

    if (res.data.files && res.data.files.length > 0) {
        return res.data.files[0].id;
    }

    // Create new folder
    const fileMetadata = {
        name: folderName,
        mimeType: 'application/vnd.google-apps.folder',
        parents: parentFolderId ? [parentFolderId] : [],
    };

    const folder = await drive.files.create({
        requestBody: fileMetadata,
        fields: 'id',
    });

    return folder.data.id;
}
