#!/bin/bash

echo "deploy start"

## Docker build
# docker-compose -f docker/docker-compose.prod.yml build --no-cache backend-worker
# docker-compose -f docker/docker-compose.prod.yml up -d backend-app backend-worker

echo "composer install"
docker exec backend-worker composer install

echo "Clear cache config"
docker exec backend-worker php artisan config:clear
docker exec backend-worker php artisan config:cache

echo "Optimize ..."
docker exec backend-worker php artisan optimize

echo "deploy done"
