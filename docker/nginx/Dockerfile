# Base on offical NGINX Alpine image
FROM nginx:stable-alpine

RUN apk --update add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*

RUN rm -f /etc/nginx/conf.d/default.conf

# Copy config files
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

COPY ./docker/nginx/set_url.sh /set_url.sh
RUN chmod +x /set_url.sh

# Copy static files
COPY ./build /usr/share/nginx/html

# Launch NGINX
CMD /set_url.sh ${ENV_NAME} ${API_HOST} && nginx -g 'daemon off;'