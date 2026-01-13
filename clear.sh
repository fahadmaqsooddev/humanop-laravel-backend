echo "Deploy script started"

# Fix permissions
sudo chown -R www-data:www-data .
sudo chmod -R ug+rw storage bootstrap/cache

# Vendors
sudo -u www-data composer -n install --prefer-dist --no-progress --no-interaction

# Clear stale state
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan event:clear
sudo -u www-data php artisan clear-compiled

# DB changes
sudo -u www-data php artisan migrate --force

# Warm up caches
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache

# Restart queue workers
sudo -u www-data php artisan queue:restart

echo "Deploy script finished execution"

