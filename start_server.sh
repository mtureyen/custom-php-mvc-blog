#!/bin/bash
# ============================================================================
# SERVER STARTUP SCRIPT (Linux/macOS)
# ============================================================================
# Spins up the Docker environment with a fresh build.
# ============================================================================

# Ensure we are in the script's directory
cd "$(dirname "$0")"

# Define colors for output
GREEN='\033[0;32m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${CYAN}==========================================${NC}"
echo -e "${CYAN}  Starting Blog-Challenge Environment...  ${NC}"
echo -e "${CYAN}==========================================${NC}"

# Start Docker
# -d: Detached, --build: Rebuild images
docker compose up -d --build

echo ""
echo -e "${GREEN}==========================================${NC}"
echo -e "${GREEN}  DONE! 🚀                                ${NC}"
echo -e "${GREEN}  Website:    http://localhost:8080       ${NC}"
echo -e "${GREEN}  Database:   http://localhost:8081       ${NC}"
echo -e "${GREEN}  User:       root                        ${NC}"
echo -e "${GREEN}  Pass:       root                        ${NC}"
echo -e "${GREEN}==========================================${NC}"
echo ""