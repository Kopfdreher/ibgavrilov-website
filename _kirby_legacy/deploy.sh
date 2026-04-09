#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "🚀 Starting deployment..."

# 1. Build the static site
echo "📦 Building static site..."
export KIRBY_URL="/ibgavrilov-website"
php static-builder.php

# 2. Stage the static folder
echo "Git -> Staging static folder..."
git add -f static

# 3. Commit temporarily
echo "📸 Committing static files..."
msg="Deploy to gh-pages $(date)"
git commit -m "$msg"

# 4. Push subtree to gh-pages (Force push to overwrite history)
echo "🚀 Pushing to gh-pages branch..."
# Use split+push --force to handle non-fast-forward errors
git push origin `git subtree split --prefix static`:gh-pages --force

# 5. Reset main branch to keep it clean
echo "🧹 Cleaning up main branch..."
git reset HEAD~1

echo "✅ Deployment complete! Check your repository settings to ensure GitHub Pages is enabled for the 'gh-pages' branch."
