const { createClient } = require('@libsql/client');
const bcrypt = require('bcryptjs');

async function main() {
  const client = createClient({
    url: 'libsql://stunningdb-transcendent-sagittarius-pm.aws-ap-northeast-1.turso.io',
    authToken: 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3NzQzNDc2NzIsImlkIjoiMDE5ZDFmNWItZmIwMS03NzY5LTgzN2UtNWU4YTdlMDU2OGRlIiwicmlkIjoiZTUxMTgzMmItMjMzMy00ZDUxLWE1ZmUtZDM2M2EzYWY5ODJhIn0.v1LtiZvNmHVYaUMc54vSNakH6gqZlR0PwaLzq4XD75p9VMype1gxAHQm-m18jv4EByOQdZ3SDwjSqVVEr81ZBQ'
  });

  const username = 'admin';
  const plainPassword = 'admin@stunning';
  const hashedPassword = await bcrypt.hash(plainPassword, 10);

  console.log('Connecting to Turso...');

  try {
    // 1. Find the admin user (table name is lowercase 'users' as per prisma mapping)
    const result = await client.execute({
      sql: "SELECT id FROM users WHERE role = 'admin' LIMIT 1",
      args: []
    });

    if (result.rows.length > 0) {
      const adminId = result.rows[0].id;
      console.log(`Found admin user (ID: ${adminId}). Updating...`);
      
      await client.execute({
        sql: "UPDATE users SET username = ?, password = ?, is_active = 1 WHERE id = ?",
        args: [username, hashedPassword, adminId]
      });
      console.log('Admin account updated successfully.');
    } else {
      console.log('No admin user found with role="admin". Creating one...');
      
      // We need a unique email since it's a unique constraint in Prisma
      const email = 'admin@stunning.local';
      
      await client.execute({
        sql: "INSERT INTO users (name, username, email, password, role, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        args: ['Super Admin', username, email, hashedPassword, 'admin', 1, new Date().toISOString(), new Date().toISOString()]
      });
      console.log('New admin account created successfully.');
    }
  } catch (error) {
    console.error('Error updating database:', error);
  } finally {
    client.close();
  }
}

main().catch(console.error);
