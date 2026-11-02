
FROM php:7.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

RUN printf '<Directory /var/www/html>\n\
    Options FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/codeigniter.conf \
    && a2enconf codeigniter

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;
