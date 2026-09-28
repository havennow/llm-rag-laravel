FROM --platform=linux/amd64 appx

# Change OPCache settings
ADD ini/opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"

USER $USER

WORKDIR /var/www/
COPY docker-php-ext-* docker-php-entrypoint /usr/local/bin/
ENTRYPOINT ["docker-php-entrypoint"]

EXPOSE 8080
EXPOSE 9000
