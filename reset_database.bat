@echo off
:: ============================================================================
:: HARD RESET UTILITY SCRIPT (Windows)
:: ============================================================================
:: This script performs a complete cleanup of the development environment.
:: WARNING: DATA LOSS!
:: 1. Stops all Docker containers.
:: 2. Deletes Docker Volumes (Database data is permanently lost).
:: 3. Deletes all uploaded images in 'public/uploads'.
:: ============================================================================

:: Ensure the script runs in the correct directory
cd /d "%~dp0"

:: Change console color to RED on BLACK (0C) to indicate warning
color 0C

echo ========================================================
echo   WARNING: HARD RESET (Delete Database & Images)
echo ========================================================
echo.
echo   This command will:
echo   1. Stop Docker and delete all database volumes.
echo   2. DELETE ALL uploaded images in 'public/uploads'.
echo.
echo   Everything will be reset to the initial state.
echo.
echo ========================================================

:: User Confirmation Prompt
set /p confirm="Are you sure? Type 'yes' to proceed: "

:: Logic Check: If input is NOT "yes" (case-insensitive /i), abort.
if /i "%confirm%" NEQ "yes" (
    echo.
    echo Aborted. Nothing was deleted.
    color 07
    pause
    exit
)

echo.
echo ========================================================
echo   Step 1: Removing Docker containers and volumes...
echo ========================================================

:: -v removes the named volumes defined in docker-compose.yml
docker compose down -v

echo.
echo ========================================================
echo   Step 2: Deleting local images...
echo ========================================================

if exist "public\uploads" (
    :: /s = subdirectories, /q = quiet
    rmdir /s /q "public\uploads"
    echo   Folder 'public\uploads' deleted.
)

:: Recreate the empty directory
mkdir "public\uploads"
echo   Empty folder 'public\uploads' created.

echo.
echo   DONE. The environment is now clean.
echo   You can rebuild it using 'start_server.bat' (or docker compose up).

:: Reset color to default
color 07
pause