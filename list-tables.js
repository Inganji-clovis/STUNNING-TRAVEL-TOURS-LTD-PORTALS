const { createClient } = require('@libsql/client');

async function main() {
  const client = createClient({
    url: 'libsql://stunningdb-transcendent-sagittarius-pm.aws-ap-northeast-1.turso.io',
    authToken: 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3NzQzNDc2NzIsImlkIjoiMDE5ZDFmNWItZmIwMS03NzY5LTgzN2UtNWU4YTdlMDU2OGRlIiwicmlkIjoiZTUxMTgzMmItMjMzMy00ZDUxLWE1ZmUtZDM2M2EzYWY5ODJhIn0.v1LtiZvNmHVYaUMc54vSNakH6gqZlR0PwaLzq4XD75p9VMype1gxAHQm-m18jv4EByOQdZ3SDwjSqVVEr81ZBQ'
  });

  try {
    const result = await client.execute("SELECT name FROM sqlite_master WHERE type='table'");
    console.log(result.rows.map(row => row.name));
  } catch (error) {
    console.error(error);
  } finally {
    client.close();
  }
}

main();
