#!/bin/bash
echo "Starting..."
echo "Running docker-compose up -d ..."
docker-compose up -d

echo "Extracting vendor folder..."
docker exec -it 7.4.x-webserver tar xvzf /var/www/html/lumen-sample/vendor.tar.gz -C /var/www/html/lumen-sample/

echo "Create .evn"
docker exec -it 7.4.x-webserver cp /var/www/html/lumen-sample/.env.example /var/www/html/lumen-sample/.env

echo "Add execute mode to phpnunit"
docker exec -it 7.4.x-webserver chmod u+x /var/www/html/lumen-sample/vendor/bin/phpunit


echo "Verify PHP version and MySQL connection..."
docker exec -it 7.4.x-webserver php /var/www/html/lumen-sample/verify.php

echo "Run migration..."
docker exec -it 7.4.x-webserver php /var/www/html/lumen-sample/artisan migrate --force

echo "Done :)";
echo "";
echo "Base URL: http://localhost:8080"
echo "Place Wager - POST: http://localhost:8080/wagers"
echo "Buy Wager  - POST: http://localhost:8080/buy/{:wadger_id}"
echo "List of Wagers - GET: http://localhost:8080/wagers?page=:page&limit=:limit"
echo "";