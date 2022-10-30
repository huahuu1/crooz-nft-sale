#!/bin/sh

ENV_NAME=$1
API_HOST=$2

ADMIN_SERVICE=admin

if [ "${ENV_NAME}" != "production" ]; then
    ADMIN_SERVICE=admin-${ENV_NAME}
fi

find /etc/nginx/conf.d/ -type f -print0 | xargs -0 sed -i -e "s|<ADMIN_SERVICE>|${ADMIN_SERVICE}|g"
find /etc/nginx/conf.d/ -type f -print0 | xargs -0 sed -i -e "s|<API_HOST>|${API_HOST}|g"