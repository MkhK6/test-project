ARG NGINX_IMAGE_TAG=1.27-alpine
FROM nginx:${NGINX_IMAGE_TAG}

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY src/public /var/www/html/public
