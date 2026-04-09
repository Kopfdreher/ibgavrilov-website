#!/bin/bash

# ==========================================================
# Deployment Script for Hetzner Managed Webhosting
# ==========================================================

# 1. Fill in your Hetzner SSH details below
HETZNER_USER="yourusername"        # e.g., ftp123456
HETZNER_HOST="your-server.de"      # e.g., www01.your-server.de
HETZNER_PORT="22"                  # default SSH port (sometimes 222 on managed hosts)

# 2. Define where the files should live on Hetzner
# We will upload the app to a folder called "statamic-app" next to your public_html folder
REMOTE_PATH="/usr/home/$HETZNER_USER/statamic-app"

echo "🚀 Starting deployment to Hetzner..."

# Sync files, but exclude development and cache files
rsync -avz --delete \
    -e "ssh -p $HETZNER_PORT" \
    --exclude '.git' \
    --exclude '.github' \
    --exclude 'node_modules' \
    --exclude 'tests' \
    --exclude '.env' \
    --exclude 'storage/framework/cache/data/*' \
    --exclude 'storage/framework/sessions/*' \
    --exclude 'storage/framework/views/*' \
    --exclude 'storage/logs/*' \
    --exclude 'deploy.sh' \
    --exclude 'composer.phar' \
    ./ $HETZNER_USER@$HETZNER_HOST:$REMOTE_PATH

echo "✅ File transfer complete!"
echo "🔄 Setting up secure public folder via SSH..."

# Run commands on the server to install dependencies and link the public folder
ssh -p $HETZNER_PORT $HETZNER_USER@$HETZNER_HOST << EOF
    cd $REMOTE_PATH
    
    # Install production PHP dependencies
    composer install --optimize-autoloader --no-dev
    
    # Back up the original public_html if it's a real directory (not a symlink)
    if [ ! -L "/usr/home/$HETZNER_USER/public_html" ] && [ -d "/usr/home/$HETZNER_USER/public_html" ]; then
        mv /usr/home/$HETZNER_USER/public_html /usr/home/$HETZNER_USER/public_html_backup
    fi
    
    # Create a symlink from our public folder to public_html
    ln -sfn $REMOTE_PATH/public /usr/home/$HETZNER_USER/public_html

    # Clear caches
    php artisan config:cache
    php artisan view:cache
EOF

echo "✅ Deployment successful!"
echo ""
echo "=========================================================="
echo "⚠️ FINAL STEP: ENVIRONMENT VARIABLES ⚠️"
echo "=========================================================="
echo "1. SSH into your server: ssh $HETZNER_USER@$HETZNER_HOST"
echo "2. Go to the app folder: cd statamic-app"
echo "3. Create a .env file: cp .env.example .env"
echo "4. Edit the .env file (e.g. with nano .env) and set:"
echo "   APP_ENV=production"
echo "   APP_DEBUG=false"
echo "   APP_URL=https://www.your-domain.de"
echo "5. Generate an app key: php artisan key:generate"
echo "=========================================================="
