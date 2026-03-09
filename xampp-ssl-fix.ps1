# XAMPP SSL Helper Script
# Usage: Run with PowerShell as Administrator.

$Domain = "stunning.test"
$OpenSSLPath = "C:\xampp\apache\bin\openssl.exe"
$ApacheConfigPath = "C:\xampp\apache\conf\ssl.crt"
$ApacheKeyPath = "C:\xampp\apache\conf\ssl.key"

if (-not (Test-Path $OpenSSLPath)) {
    Write-Error "XAMPP OpenSSL not found at $OpenSSLPath. Please update the path in the script."
    exit
}

# Create directories if they don't exist
New-Item -ItemType Directory -Force -Path "C:\xampp\apache\conf"

# Generate Certificate
Write-Host "--- Generating SSL Certificate for $Domain ---" -ForegroundColor Green
& $OpenSSLPath req -x509 -nodes -days 365 -newkey rsa:2048 `
    -keyout "$ApacheKeyPath\server.key" `
    -out "$ApacheConfigPath\server.crt" `
    -subj "/C=RW/ST=Kigali/L=Kigali/O=StunningTravel/CN=$Domain"

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Files generated: server.crt and server.key in C:\xampp\apache\conf\" -ForegroundColor Cyan
}

# Trust the Certificate in Windows
Write-Host "--- Adding Certificate to Windows Trusted Root ---" -ForegroundColor Green
Import-Certificate -FilePath "$ApacheConfigPath\server.crt" -CertStoreLocation Cert:\LocalMachine\Root

Write-Host "✅ Windows now trusts certificates for $Domain!" -ForegroundColor Cyan

Write-Host "`n--- Next Steps ---" -ForegroundColor Yellow
Write-Host "1. Update your C:\Windows\System32\drivers\etc\hosts file:"
Write-Host "   127.0.0.1 $Domain"
Write-Host "2. Update your XAMPP Apache config (httpd-vhosts.conf):"
Write-Host "   <VirtualHost *:443>"
Write-Host "       DocumentRoot 'c:/xampp/htdocs/STUNNING TRAVEL & TOURS LTD/public'"
Write-Host "       ServerName $Domain"
Write-Host "       SSLEngine on"
Write-Host "       SSLCertificateFile 'conf/ssl.crt/server.crt'"
Write-Host "       SSLCertificateKeyFile 'conf/ssl.key/server.key'"
Write-Host "   </VirtualHost>"
