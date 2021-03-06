#Dockerfile
FROM ubuntu:18.04

LABEL MAINTAINER="Ana Arashiro"

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG=C.UTF-8 \
    DEBIAN_FRONTEND=noninteractive

#ENV LANG en_US.UTF-8
#ENV LANGUAGE en_US:en
#ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y \
        nginx \
        curl \
        zip unzip \
        git \
        software-properties-common \
        supervisor \
        sqlite3 \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y \
        php7.3-fpm \
        php7.3-cli \
        php7.3-gd \
        php7.3-mysql \
        php7.3-pgsql \
        php7.3-imap \
        php-memcached \
        php7.3-mbstring \
        php7.3-xml \
        php7.3-curl \
        php7.3-sqlite3 \
        php7.3-imagick \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && apt-get remove -y \
        --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && echo "daemon off;" >> /etc/nginx/nginx.conf \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

## RUN apt-get update
## RUN curl -sL https://deb.nodesource.com/setup_14.x | bash
## RUN apt-get install -y nodejs

COPY php-fpm.conf /etc/php/7.3/fpm/php-fpm.conf

COPY default /etc/nginx/sites-available/default

COPY local.ini /etc/php/7.3/fpm/conf.d/local.ini

COPY policy.xml /etc/ImageMagick-6/policy.xml

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#WORKDIR /var/www/html
WORKDIR /usr/share/nginx/html

CMD ["/usr/bin/supervisord"]