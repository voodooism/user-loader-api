FROM nginx:1.19

COPY dockerfiles/nginx/nginx.conf /etc/nginx/nginx.conf
COPY dockerfiles/nginx/conf.d /etc/nginx/conf.d

RUN mkdir -p /var/www/

COPY . /var/www
