echo "Deploy script started"

# Install deps first so autoload & vendors are up to date
sudo -u www-data composer -n install --prefer-dist --no-progress --no-interaction
sudo -u www-data composer -n dump-autoload -o

# Clear all possible stale state
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan event:clear
sudo -u www-data php artisan clear-compiled

# DB changes
sudo -u www-data php artisan migrate --force

# Rebuild caches (if desired for speed)
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache

# Optional feel-good :)
sudo -u www-data php artisan inspire

echo "Deploy script finished execution"
# sudo -u www-data php artisan db:seed --class=CreditPlanSeeder
