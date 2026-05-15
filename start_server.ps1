$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$port = 8000

$phpCommand = Get-Command php -ErrorAction SilentlyContinue
$phpPath = if ($phpCommand) { $phpCommand.Source } else { $null }

if (-not $phpPath -and (Test-Path -LiteralPath "C:\wamp\bin\php\php8.4.15\php.exe")) {
    $phpPath = "C:\wamp\bin\php\php8.4.15\php.exe"
}

if (-not $phpPath) {
    $phpCandidate = Get-ChildItem -Path "C:\wamp\bin\php" -Filter php.exe -Recurse -ErrorAction SilentlyContinue |
        Sort-Object FullName -Descending |
        Select-Object -First 1

    if ($phpCandidate) {
        $phpPath = $phpCandidate.FullName
    }
}

if (-not $phpPath) {
    Write-Host "PHP bulunamadi. PHP'yi PATH'e ekleyin veya WAMP kurulumunu kontrol edin." -ForegroundColor Red
    exit 1
}

Write-Host "PHP: $phpPath" -ForegroundColor Cyan
Write-Host "Proje: $projectRoot" -ForegroundColor Cyan
Write-Host "Site adresi: http://127.0.0.1:$port/index.html" -ForegroundColor Green
Write-Host "Durdurmak icin bu pencerede Ctrl+C kullanin." -ForegroundColor Yellow

Set-Location -LiteralPath $projectRoot
& $phpPath -S "127.0.0.1:$port" -t $projectRoot
