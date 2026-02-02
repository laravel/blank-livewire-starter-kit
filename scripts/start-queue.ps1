# Start the Laravel queue worker in background
# Usage: ./scripts/start-queue.ps1
$cwd = Split-Path -Parent $MyInvocation.MyCommand.Definition
$artisan = Join-Path $cwd '..\artisan'
Start-Process -NoNewWindow -FilePath php -ArgumentList "$artisan", 'queue:work', '--tries=3', '--sleep=3' -WorkingDirectory (Join-Path $cwd '..') -PassThru
Write-Output "Queue worker started (check process list)."