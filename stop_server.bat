@echo off
:: ============================================================================
:: SERVER SHUTDOWN SCRIPT (Windows)
:: ============================================================================
:: Stops and removes the Docker containers and networks.
:: NOTE: Database data (Volumes) is PRESERVED. Use reset_server.bat to delete data.
:: ============================================================================

:: Ensure the script runs in the correct directory
cd /d "%~dp0"

echo ==========================================
echo   Stopping Blog-Challenge Environment...
echo ==========================================

:: Stops containers and removes them, but keeps the named volumes
docker compose down

echo.
echo ==========================================
echo   Shutdown complete.
echo   Containers removed. Data preserved.
echo ==========================================
echo.

pause