#!/bin/bash
# ============================================================================
# SERVER SHUTDOWN SCRIPT (Linux/macOS)
# ============================================================================
# Stops containers but preserves database volumes.
# ============================================================================

# Ensure we are in the script's directory
cd "$(dirname "$0")"

# Define colors
YELLOW='\033[1;33m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

echo -e "${YELLOW}==========================================${NC}"
echo -e "${YELLOW}  Stopping Blog-Challenge Environment...  ${NC}"
echo -e "${YELLOW}==========================================${NC}"

# Stop and remove containers
docker compose down

echo ""
echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN}  Shutdown complete.                      ${NC}"
echo -e "${GREEN}  Containers removed. Data preserved.     ${NC}"
echo -e "${GREEN}==========================================${NC}"
echo ""