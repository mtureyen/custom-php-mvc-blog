@echo off
REM -------------------------------------------------------------
REM Database Seeder Launcher (Windows)
REM
REM This script triggers the PHP seeding logic inside the running
REM Docker container. It requires the stack to be up and running.
REM -------------------------------------------------------------

echo Starting seeding process in container 'app'...

REM Execute the seed.php script within the 'app' service container
REM -T: Disables pseudo-tty allocation (recommended for automation scripts)
docker-compose exec -T app php tests/seed.php

REM Keep the window open so the user can read the output
pause