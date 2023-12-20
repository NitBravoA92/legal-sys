#! /bin/bash

echo "Laravel Project setup script started"

php "artisan down" # put the site down
php /usr/local/bin/composer install --no-interaction #install dependencies
php /usr/local/bin/composer update --no-interaction #update dependencies

php artisan migrate:fresh --seed # migrate the database
php artisan optimize:clear # clear the cache
php artisan view:clear # clear the view cache
php artisan route:clear # clear the route cache
php artisan config:clear # clear the config cache
php artisan view:cache # cache the views
php artisan route:cache # cache the routes
php artisan storage:link # create the symbolic link

php artisan up # put the site up

source ~/.nvm/nvm.sh
npm install
echo "Installation script finished execution"
