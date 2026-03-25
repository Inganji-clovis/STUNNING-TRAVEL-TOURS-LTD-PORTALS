param (
    [string]$Message = "chore: auto-commit updates"
)

Write-Host "Staging all changes..." -ForegroundColor Cyan
git add .

Write-Host "Committing changes with message: '$Message'" -ForegroundColor Cyan
git commit -m $Message

Write-Host "Pushing to origin main..." -ForegroundColor Cyan
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Auto-commit and push successful!" -ForegroundColor Green
} else {
    Write-Host "❌ An error occurred during the process." -ForegroundColor Red
}
