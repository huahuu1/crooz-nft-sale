# Base on offical NGINX Alpine image
FROM nginx:stable-alpine

RUN apk --update add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*

RUN rm -f /etc/nginx/conf.d/default.conf

# Copy config files
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf.d/*.conf /etc/nginx/conf.d/

# Launch NGINX
CMD nginx -g 'daemon off;'