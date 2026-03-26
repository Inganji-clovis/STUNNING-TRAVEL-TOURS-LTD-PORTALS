const { PrismaClient } = require('@prisma/client');
const { PrismaLibSql } = require('@prisma/adapter-libsql');
const { createClient } = require('@libsql/client');
const bcrypt = require('bcryptjs');

async function main() {
  const client = createClient({
    url: 'libsql://stunningdb-transcendent-sagittarius-pm.aws-ap-northeast-1.turso.io',
    authToken: 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3NzQzNDc2NzIsImlkIjoiMDE5ZDFmNWItZmIwMS03NzY5LTgzN2UtNWU4YTdlMDU2OGRlIiwicmlkIjoiZTUxMTgzMmItMjMzMy00ZDUxLWE1ZmUtZDM2M2EzYWY5ODJhIn0.v1LtiZvNmHVYaUMc54vSNakH6gqZlR0PwaLzq4XD75p9VMype1gxAHQm-m18jv4EByOQdZ3SDwjSqVVEr81ZBQ'
  });
  const adapter = new PrismaLibSql(client);
  const prisma = new PrismaClient({ adapter });

  const plainPassword = 'admin@stunning';
  const hashedPassword = await bcrypt.hash(plainPassword, 10);

  let admin = await prisma.user.findFirst({
    where: { role: 'admin' }
  });

  if (admin) {
    await prisma.user.update({
      where: { id: admin.id },
      data: {
        username: 'admin',
        password: hashedPassword,
        is_active: true
      }
    });
    console.log('Successfully updated existing admin credentials.');
  } else {
    await prisma.user.create({
      data: {
        name: 'Super Admin',
        username: 'admin',
        email: 'admin@stunning.local',
        password: hashedPassword,
        role: 'admin',
        is_active: true
      }
    });
    console.log('Successfully created new admin user.');
  }

  await prisma.$disconnect();
}

main().catch(console.error);
