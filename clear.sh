echo "Deploy script started"

# 1) Vendors first
sudo -u www-data composer -n install --prefer-dist --no-progress --no-interaction

# 2) Clear any stale state
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan route:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan event:clear
sudo -u www-data php artisan clear-compiled

# 3) DB changes (non-interactive)
sudo -u www-data php artisan migrate --force

# 4) Warm caches to mirror production behavior
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache

# 5) Optional: restart workers if you use queues
sudo -u www-data php artisan queue:restart

echo "Deploy script finished execution"
