echo "Deploy script started"

# Vendors
sudo -u www-data composer -n install --prefer-dist --no-progress --no-interaction

# Fix ownership and permissions **AFTER**
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache
sudo find storage -type d -exec chmod g+s {} \;
sudo find bootstrap/cache -type d -exec chmod g+s {} \;

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

# Fix ownership and permissions **AFTER**
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache
sudo find storage -type d -exec chmod g+s {} \;
sudo find bootstrap/cache -type d -exec chmod g+s {} \;

# Restart queue workers
sudo -u www-data php artisan queue:restart

#sudo -u www-data php artisan db:seed --class=CreateUserHotSpotSeeder    # dev done / staging done /prod pending
#sudo -u www-data php artisan db:seed --class=OptimizationPlanSeeder     # dev done / staging done /prod pending

sudo rm -rf storage/framework/cache/data/*
echo "Deploy script finished execution"
exit 0
