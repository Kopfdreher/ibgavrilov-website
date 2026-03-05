#!/bin/bash

# Define colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Starting Kirby Local Development Server...${NC}"

# 1. Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ Error: PHP is not installed or not in your PATH.${NC}"
    echo "Please install PHP 8.0 or higher to run Kirby."
    exit 1
fi

# 2. Check PHP version (Kirby 4 requires PHP 8.1+)
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "${GREEN}✅ PHP version $PHP_VERSION detected.${NC}"

# 3. Check if the Panel is installed (it's part of the 'kirby' folder)
if [ ! -d "kirby" ]; then
    echo -e "${RED}❌ Error: Kirby core folder not found.${NC}"
    echo "Please ensure you are running this script from the project root."
    exit 1
fi

# 4. Check for 'media' folder permissions (crucial for Panel)
if [ ! -d "media" ]; then
    mkdir media
fi
if [ ! -w "media" ]; then
    echo -e "${RED}⚠️ Warning: 'media' folder is not writable.${NC}"
    echo "Attempting to fix permissions..."
    chmod -R 755 media
fi

# 5. Define Host and Port
HOST="localhost"
PORT="8000"
URL="http://$HOST:$PORT"
PANEL_URL="$URL/panel"

echo -e "${BLUE}🌍 Server starting at: ${GREEN}$URL${NC}"
echo -e "${BLUE}🔧 Panel access at:   ${GREEN}$PANEL_URL${NC}"
echo -e "${BLUE}👉 Press Ctrl+C to stop the server.${NC}"

# 6. Open the browser (Cross-platform support)
# We sleep for 2 seconds to give the server a moment to start
(sleep 2 && open "$PANEL_URL" 2>/dev/null || xdg-open "$PANEL_URL" 2>/dev/null || start "$PANEL_URL" 2>/dev/null) &

# 7. Start the built-in PHP server with Kirby's router
# This command blocks until the user stops it
php -S $HOST:$PORT kirby/router.php
