# Stops Laravel queue:work processes (Windows)
# Usage: ./scripts/stop-queue.ps1
$procs = Get-CimInstance Win32_Process | Where-Object { $_.CommandLine -like '*artisan*queue:work*' }
if (!$procs) {
    Write-Output "No queue worker processes found."
    exit 0
}
foreach ($p in $procs) {
    Write-Output "Stopping PID $($p.ProcessId)"
    Stop-Process -Id $p.ProcessId -Force
}
Write-Output "Stopped queue workers."