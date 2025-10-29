echo "Deploy script started"
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan clear-compiled
sudo -u www-data php artisan event:clear
sudo -u www-data php artisan optimize:clear
sudo -u www-data php artisan optimize
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan event:cache
sudo -u www-data php artisan config:cache
sudo -u www-data composer -n dump-autoload -o
sudo -u www-data php artisan inspire
sudo -u www-data php artisan migrate
sudo -u www-data composer -n install
sudo -u www-data php artisan route:clear
echo "Deploy script finished execution"
#php artisan db:seed --class=CreditPlanSeeder
