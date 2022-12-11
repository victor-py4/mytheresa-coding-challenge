#!/bin/sh
## Setup Environment
echo "Initializing Environment"
cp .env.example .env
## Setup services
echo "Initializing services"
./vendor/bin/sail up -d
## Install Dependencies
echo "Installing dependencies"
docker exec my_theresa_apache composer install
## Install Dependencies
echo "Running migrations"
docker exec my_theresa_apache php artisan migrate

