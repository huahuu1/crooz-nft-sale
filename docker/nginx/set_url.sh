#!/bin/sh

ENV_NAME=$1

AUCTION_SERVICE=auction
MYPAGE_SERVICE=mypage

if [ "${ENV_NAME}" != "production" ]; then
    AUCTION_SERVICE=auction-${ENV_NAME}
    MYPAGE_SERVICE=mypage-${ENV_NAME}
fi

find /etc/nginx/conf.d/ -type f -print0 | xargs -0 sed -i -e "s|<AUCTION_SERVICE>|${AUCTION_SERVICE}|g"
find /etc/nginx/conf.d/ -type f -print0 | xargs -0 sed -i -e "s|<MYPAGE_SERVICE>|${MYPAGE_SERVICE}|g"