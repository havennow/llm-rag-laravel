FROM --platform=linux/amd64 php:8.5-cli-alpine

ARG USER=lc
ARG UID=1000
ARG GID=1000

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="1"
ENV NODE_VERSION 14.19.0

RUN ln -fs /usr/share/zoneinfo/UTC /etc/localtime

RUN addgroup ${USER}

RUN adduser -D -G ${USER} -u ${UID} ${USER}

RUN apk update && apk add \
      iputils \
      libzip-dev \
      gd-dev \
      geoip-dev \
      git \
      gnupg \
      imagemagick \
      jpegoptim \
      libffi-dev \
      libgit2 \
      libxslt-dev \
      make \
      openssh \
      openssl-dev \
      optipng \
      pcre-dev \
      pngquant \
      libpq \
      unzip \
      wget \
      zip \
      zlib-dev \
      curl \
      curl-dev \
      libpq \
      bash

# Swoole

RUN  apk add --no-cache --virtual .build-deps \
      autoconf \
      $PHPIZE_DEPS \
      automake \
      g++ \
      gcc \
      gettext \
      libc-dev \
      libtool \
      linux-headers \
      musl-dev \
      perl-dev \
      zlib-dev \
      icu-dev \
      c-ares \
      c-ares-dev

# Pcntl
RUN docker-php-ext-install pcntl

# intl
RUN docker-php-ext-configure intl && docker-php-ext-install intl \
    && docker-php-ext-enable intl


RUN docker-php-ext-install zip


#imagick
RUN apk add --update imagemagick imagemagick-libs imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# php exif
RUN docker-php-ext-configure exif \
&& docker-php-ext-install exif \
&& docker-php-ext-enable exif


# php mysql (PDO)
RUN apk add --no-cache \
        mysql-dev \
        libpq \
        libpq-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql


# redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# php gd
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      # --with-png=/usr/include/ \ # No longer necessary as of 7.4; https://github.com/docker-library/php/pull/910#issuecomment-559383597
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*

#RUN apk add --no-cache nodejs=${NODE_VERSION}-r0 npm yarn
RUN apk add --no-cache nodejs npm yarn
RUN apk update && apk add yarn

# Install chokidar
RUN yarn global add chokidar

WORKDIR /var/www/

# Get latest Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN chown -R ${USER}:${USER} /usr/bin/composer
RUN chown -R ${USER}:${USER} /var/www/

USER $USER
