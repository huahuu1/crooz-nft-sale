#!/bin/bash

echo "deploy start"

## Docker build
# docker-compose -f docker/docker-compose.prod.yml build --no-cache backend-app backend-web
# docker-compose -f docker/docker-compose.prod.yml up -d backend-app backend-web

echo "composer install"
docker exec backend-app composer install

## Migrate db (only run on local)
# docker exec backend-app php artisan migrate

echo "Clear cache config"
docker exec backend-app php artisan config:clear
docker exec backend-app php artisan config:cache

echo "Clear cache route"
docker exec backend-app php artisan route:clear
docker exec backend-app php artisan route:cache

echo "Optimize ..."
docker exec backend-app php artisan optimize

echo "deploy done"
