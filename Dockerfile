FROM php:7.4-apache

ENV DEBIAN_FRONTEND=noninteractive

# Gunakan repository Debian archive untuk base image lama
RUN set -eux; \
    sed -i \
      -e 's/deb.debian.org/archive.debian.org/g' \
      -e 's/security.debian.org/archive.debian.org/g' \
      -e '/buster-updates/d' \
      /etc/apt/sources.list; \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    zlib1g-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        mysqli \
        pdo \
        pdo_mysql \
        gd \
        zip \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html