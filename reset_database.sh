#!/bin/bash
# ============================================================================
# HARD RESET UTILITY SCRIPT (Linux/macOS)
# ============================================================================
# 1. Stops Docker containers & removes volumes.
# 2. Cleans up the 'public/uploads' directory.
# ============================================================================

# Ensure we are in the script's directory
cd "$(dirname "$0")"

# Define colors for output
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${RED}========================================================${NC}"
echo -e "${RED}  WARNING: HARD RESET (Delete Database & Images)        ${NC}"
echo -e "${RED}========================================================${NC}"
echo ""
echo "  This command will:"
echo "  1. Stop Docker and delete all database volumes."
echo "  2. DELETE ALL uploaded images in 'public/uploads'."
echo ""
echo "  Everything will be reset to the initial state."
echo ""

# User Confirmation
read -p "Are you sure? Type 'yes' to proceed: " confirm

# Check input (case-insensitive)
if [[ ! "$confirm" =~ ^[Yy][Ee][Ss]$ ]]; then
    echo ""
    echo "Aborted. Nothing was deleted."
    exit 1
fi

echo ""
echo "--------------------------------------------------------"
echo "  Step 1: Removing Docker containers and volumes..."
echo "--------------------------------------------------------"

docker compose down -v

echo ""
echo "--------------------------------------------------------"
echo "  Step 2: Deleting local images..."
echo "--------------------------------------------------------"

# Remove the folder and its contents
rm -rf public/uploads

# Recreate the folder
mkdir -p public/uploads

# Set permissions so the Docker container (www-data user) can write to it
chmod 777 public/uploads

echo "  Folder 'public/uploads' recreated and permissions set."

echo ""
echo "  DONE. The environment is now clean."