#!/bin/bash
echo "Starting..."
echo "Running docker-compose up -d ..."
docker-compose up -d

echo "Intalling Lumen via composer...";
echo "Create database lumen_sample";
docker exec -it mysql mysql --login-path=/etc/mysql/my.cnf --execute="create database if not exists lumen_sample CHARACTER SET utf8 COLLATE utf8_general_ci;"

echo "Verify PHP version and MySQL connection..."
docker exec -it 7.4.x-webserver php /var/www/html/lumen-sample/verify.php

echo "Extracting vendor folder..."
docker exec -it 7.4.x-webserver tar xvzf /var/www/html/lumen-sample/vendor.tar.gz -C /var/www/html/lumen-sample/

#docker exec -it 7.4.x-webserver /root/.composer/vendor/bin/lumen new /var/www/lumen-sample