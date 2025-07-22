echo "Deploy script started"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan clear-compiled
php artisan event:clear
php artisan optimize:clear
php artisan optimize
php artisan view:cache
php artisan event:cache
php artisan config:cache
composer -n dump-autoload -o
php artisan inspire
php artisan migrate
composer -n install
php artisan route:clear
echo "Deploy script finished execution"
php artisan db:seed --class=CreditPlanSeeder
