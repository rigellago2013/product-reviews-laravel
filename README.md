## Setup

composer install
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ProductsSeeder
php artisan db:seed --class=ReviewsSeeder

## Testing
php artisan test

## Db located at
{yourpath}/app/database/db.amazon