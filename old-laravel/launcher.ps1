# Stunning Portal Standalone Launcher
# This script ensures the Docker stack is running and opens the app in a dedicated window.

$AppUrl = "http://localhost:8080" # Update this to your production domain once SSL is set
$ChromePath = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$EdgePath = "C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

Write-Host "--- Initializing Stunning Portal Standalone ---" -ForegroundColor Green

# Check if Docker is running
if (!(docker info 2>$null)) {
    Write-Error "Docker Desktop is not running. Please start Docker and try again."
    Pause
    exit
}

# Start the stack
Write-Host "Verifying containers..." -ForegroundColor Cyan
docker compose -f docker-compose.prod.yml up -d

Write-Host "Launching portal..." -ForegroundColor Green

# Launch in App Mode (No tabs, no address bar)
if (Test-Path $ChromePath) {
    Start-Process $ChromePath -ArgumentList "--app=$AppUrl"
} elseif (Test-Path $EdgePath) {
    Start-Process $EdgePath -ArgumentList "--app=$AppUrl"
} else {
    # Fallback to default browser if neither is found at standard paths
    Start-Process $AppUrl
}

Write-Host "✅ Portal launched successfully!" -ForegroundColor Cyan

# --- Branding: Create/Refresh Desktop Shortcut ---
$ShortcutName = "Stunning Portal"
$DesktopPath = [Environment]::GetFolderPath("Desktop")
$ShortcutPath = Join-Path $DesktopPath "$ShortcutName.lnk"

# Icon Configuration
# We use shell32.dll, index 13 (Globe/World) which fits the "Travel" theme
# This is much more reliable than PNGs which Windows shortcuts usually reject
$SystemIconPath = "C:\Windows\System32\shell32.dll"
$IconIndex = 13 

# Delete old shortcut to force Windows to refresh
if (Test-Path $ShortcutPath) {
    Remove-Item $ShortcutPath -Force
}

Write-Host "`nRefreshing branded Desktop shortcut..." -ForegroundColor Yellow
$WshShell = New-Object -ComObject WScript.Shell
$Shortcut = $WshShell.CreateShortcut($ShortcutPath)
$Shortcut.TargetPath = "powershell.exe"
$Shortcut.Arguments = "-ExecutionPolicy Bypass -File `"$PSCommandPath`""
$Shortcut.WorkingDirectory = $PSScriptRoot
$Shortcut.Description = "Launch Stunning Travel & Tours Portal"
$Shortcut.IconLocation = "$SystemIconPath, $IconIndex"

$Shortcut.Save()

# Force icon cache refresh
$shell = New-Object -ComObject Shell.Application
$shell.Namespace($DesktopPath).Self.InvokeVerb("refresh") | Out-Null

Write-Host "✅ Desktop shortcut successfully refreshed with a professional icon!" -ForegroundColor Green
