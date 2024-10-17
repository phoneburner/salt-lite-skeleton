# syntax=docker/dockerfile:1
FROM php:8.3-apache as base
ENV PATH "/var/www/vendor/bin:/home/dev/composer/bin:$PATH"
ENV COMPOSER_HOME "/home/dev/composer"
EXPOSE 80

RUN <<-EOF
  groupadd --gid 1000 dev;
  useradd --system --create-home --uid 1000 --gid 1000 --shell /bin/bash dev;
  apt-get update;
  apt-get install -y -q \
    apt-transport-https \
    autoconf  \
    build-essential \
    curl \
    git \
    less \
    lynx \
    nano \
    libgmp-dev \
    libicu-dev \
    libzip-dev \
    libsodium-dev \
    pkg-config \
    unzip \
    vim-tiny \
    zip \
    zlib1g-dev;
  apt-get clean;
EOF

# Install PHP Extensions
RUN <<-EOF
  docker-php-ext-install -j$(nproc) bcmath exif gmp intl opcache pdo_mysql zip;
  MAKEFLAGS="-j $(nproc)" pecl install igbinary redis;
  docker-php-ext-enable igbinary redis;
EOF

# Apache Webserver Configuration
RUN <<-EOF
  a2enmod rewrite;
  a2enmod deflate;
  a2enmod env;
  a2enmod ssl;
  a2enmod expires;
  a2enmod headers;
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini";
EOF

COPY --link php-production.ini /usr/local/etc/php/conf.d/settings.ini
COPY --link --from=composer/composer:latest-bin /composer /usr/bin/composer

FROM base as development
ENV SALT_BUILD_STAGE "development"
ENV XDEBUG_MODE "off"
WORKDIR /var/www
COPY --link php-development.ini /usr/local/etc/php/conf.d/settings.ini
RUN <<-EOF
  MAKEFLAGS="-j $(nproc)" pecl install xdebug-3.4.0beta1;
  docker-php-ext-enable xdebug;
EOF
USER dev

FROM base as production
ENV SALT_BUILD_STAGE "production"
WORKDIR /var/www
COPY --link . /var/www
RUN composer install --optimize-autoloader --no-dev
