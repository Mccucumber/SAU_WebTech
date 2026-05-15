@echo off
set "POWERSHELL_EXE=%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe"

if not exist "%POWERSHELL_EXE%" (
    set "POWERSHELL_EXE=%SystemRoot%\Sysnative\WindowsPowerShell\v1.0\powershell.exe"
)

if not exist "%POWERSHELL_EXE%" (
    echo PowerShell bulunamadi. start_server.ps1 dosyasini elle calistirin veya Windows PowerShell kurulumunu kontrol edin.
    pause
    exit /b 1
)

"%POWERSHELL_EXE%" -NoProfile -ExecutionPolicy Bypass -File "%~dp0start_server.ps1"
pause
