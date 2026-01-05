@echo off
:: ============================================================================
:: SERVER STARTUP SCRIPT (Windows)
:: ============================================================================
:: This script spins up the Docker environment for the Blog Application.
:: It ensures the latest changes in Dockerfiles are built before starting.
:: ============================================================================

:: Ensure the script runs in the correct directory
cd /d "%~dp0"

echo ==========================================
echo   Starting Blog-Challenge Environment...
echo ==========================================

:: Start Docker containers
:: -d      : Detached mode (runs in background)
:: --build : Forces a rebuild of images (useful if you changed the Dockerfile)
docker compose up -d --build

echo.
echo ==========================================
echo   DONE! 
echo   Website:    http://localhost:8080
echo   Database:   http://localhost:8081
echo   User:       root
echo   Pass:       root
echo ==========================================
echo.

:: Pause so the window stays open (helps you see if Docker threw an error)
pause