#!/bin/bash
# -------------------------------------------------------------
# Database Seeder Launcher (Linux / macOS)
#
# This script triggers the PHP seeding logic inside the running
# Docker container. It requires the stack to be up and running.
# -------------------------------------------------------------

echo "Starting seeding process in container 'app'..."

# Execute the seed.php script within the 'app' service container
# -T: Disables pseudo-tty allocation (recommended for automation scripts)
docker-compose exec -T app php tests/seed.php

# Optional: Check if the command was successful
if [ $? -eq 0 ]; then
    echo "Process finished."
else
    echo "Error: Could not execute script. Is the server running?"
fi