@echo off
echo === WebNewBiz Build Script ===
echo.

echo [1/3] Installing backend dependencies...
cd /d "%~dp0backend"
call composer install --no-interaction 2>&1
echo.

echo [2/3] Installing frontend dependencies...
cd /d "%~dp0frontend-react"
call npm install 2>&1
echo.

echo [3/3] Building frontend for production...
call npm run build 2>&1
echo.

if %ERRORLEVEL% EQU 0 (
    echo Build completed successfully!
) else (
    echo Build failed with exit code %ERRORLEVEL%
)
pause
