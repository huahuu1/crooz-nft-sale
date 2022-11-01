FROM php:8.0.24-alpine

# env
ENV APP_ROOT=/var/www/backend

# install packages
RUN apk add --no-cache wget libzip libpng libjpeg-turbo libwebp freetype icu supervisor

RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev unzip && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install intl && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    docker-php-ext-install zip && \
    docker-php-ext-install bcmath && \
    apk del build-essentials && rm -rf /usr/src/php*

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

RUN mkdir /var/log/supervisor

# copy config files
COPY ./docker/worker/cron.root /var/spool/cron/crontabs/root
COPY ./docker/worker/supervisord.conf /etc/
COPY ./docker/worker/startup.sh ./
RUN chmod +x ./startup.sh

WORKDIR ${APP_ROOT}

# command
CMD /startup.sh