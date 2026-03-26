const { createClient } = require('@libsql/client');

async function main() {
  const client = createClient({
    url: 'libsql://stunningdb-transcendent-sagittarius-pm.aws-ap-northeast-1.turso.io',
    authToken: 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3NzQzNDc2NzIsImlkIjoiMDE5ZDFmNWItZmIwMS03NzY5LTgzN2UtNWU4YTdlMDU2OGRlIiwicmlkIjoiZTUxMTgzMmItMjMzMy00ZDUxLWE1ZmUtZDM2M2EzYWY5ODJhIn0.v1LtiZvNmHVYaUMc54vSNakH6gqZlR0PwaLzq4XD75p9VMype1gxAHQm-m18jv4EByOQdZ3SDwjSqVVEr81ZBQ'
  });

  try {
    console.log('Adding vat_amount to ebms...');
    await client.execute("ALTER TABLE ebms ADD COLUMN vat_amount DECIMAL");
    console.log('Adding billing_document_id to ebms...');
    await client.execute("ALTER TABLE ebms ADD COLUMN billing_document_id INTEGER REFERENCES billing_documents(id) ON DELETE SET NULL");
    
    console.log('Migration complete.');
  } catch (error) {
    if (error.message.includes('duplicate column name')) {
      console.log('Columns already exist.');
    } else {
      console.error('Migration failed:', error);
    }
  } finally {
    client.close();
  }
}

main();
