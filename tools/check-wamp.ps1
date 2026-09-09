# Run from PowerShell. Uses local WAMP PHP; no downloads or database changes.
param([switch]$Database)
$phpCandidates = Get-ChildItem 'C:\wamp64\bin\php' -Filter php.exe -Recurse -ErrorAction SilentlyContinue |
    Where-Object { $_.Directory.Name -match '^php\d+(\.\d+)+$' } |
    Sort-Object { [version]($_.Directory.Name -replace '^php', '') } -Descending
$phpExecutable = $phpCandidates | Select-Object -First 1
if (-not $phpExecutable) { Write-Error 'WAMP PHP was not found. Run tools/check-project.php with your installed PHP CLI.'; exit 1 }
Write-Host ('Using ' + $phpExecutable.FullName)
if ($Database) { & $phpExecutable.FullName "$PSScriptRoot\check-project.php" --database }
else { & $phpExecutable.FullName "$PSScriptRoot\check-project.php" }
exit $LASTEXITCODE
