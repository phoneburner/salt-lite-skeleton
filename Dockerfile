# syntax=docker/dockerfile:1
FROM php:8.4-fpm AS base
ENV PATH "/var/www/bin:/var/www/vendor/bin:/home/dev/composer/bin:$PATH"
ENV COMPOSER_HOME "/home/dev/composer"
ENV PHP_PEAR_PHP_BIN="php -d error_reporting=E_ALL&~E_DEPRECATED"

RUN <<-EOF
  set -eux
  groupadd --gid 1000 dev
  useradd --system --create-home --uid 1000 --gid 1000 --groups www-data --shell /bin/bash dev
  apt-get update
  apt-get upgrade -y
  apt-get install -y -q \
    apt-transport-https \
    autoconf  \
    build-essential \
    curl \
    git \
    jq \
    less \
    libgmp-dev \
    libicu-dev \
    libzip-dev \
    librabbitmq-dev \
    libsodium-dev \
    pkg-config \
    unzip \
    vim-tiny \
    zip \
    zlib1g-dev
  apt-get clean
  ln -s /usr/bin/vim.tiny /usr/bin/vim
EOF

# Install PHP Extensions
RUN <<-EOF
  set -eux
  docker-php-ext-install -j$(nproc) bcmath exif gmp intl opcache pcntl pdo_mysql zip
  MAKEFLAGS="-j $(nproc)" pecl install amqp igbinary redis timezonedb
  docker-php-ext-enable amqp igbinary redis timezonedb
  find "$(php-config --extension-dir)" -name '*.so' -type f -exec strip --strip-all {} \;
  rm -rf /tmp/pear
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
EOF

COPY --link php-production.ini /usr/local/etc/php/conf.d/settings.ini
COPY --link --from=composer/composer:latest-bin /composer /usr/local/bin/composer

FROM base AS development-php
ENV SALT_BUILD_STAGE "development"
ENV XDEBUG_MODE "off"
WORKDIR /var/www
COPY --link php-development.ini /usr/local/etc/php/conf.d/settings.ini
RUN <<-EOF
  set -eux
  MAKEFLAGS="-j $(nproc)" pecl install xdebug
  docker-php-ext-enable xdebug
  rm -rf /tmp/pear
EOF
USER dev

FROM redocly/cli AS production-redocly
COPY --link openapi.yaml redocly.yaml /spec/
RUN <<-EOF
    set -eux;
    redocly bundle openapi.yaml --output=openapi.json
    redocly build-docs openapi.yaml --output=openapi.html
EOF

FROM base AS production-php
ENV SALT_BUILD_STAGE "production"
WORKDIR /var/www
COPY --link --chown=1000:1000 . /var/www
COPY --link --chown=1000:1000 --from=production-redocly /spec/openapi.yaml /var/www/resources/views/openapi.yaml
COPY --link --chown=1000:1000 --from=production-redocly /spec/openapi.html /var/www/resources/views/openapi.html
RUN <<-EOF
    set -eux;
    mkdir -p /home/dev/composer;
    chown -R dev:dev /var/www /home/dev/
    find /var/www/storage -type d -exec chmod 0777 {} \;
    find /var/www/storage -type f -exec chmod 0666 {} \;
EOF

USER dev
RUN --mount=type=cache,mode=0777,uid=1000,gid=1000,target=/home/dev/composer/cache \
    --mount=type=secret,id=GITHUB_TOKEN,uid=1000,gid=1000,required=true <<-EOF
    set -eux
    composer config --global github-oauth.github.com $(cat /run/secrets/GITHUB_TOKEN)
    composer install --classmap-authoritative --no-dev
    php salt orm:generate-proxies
    php salt routing:cache
    rm -f /var/www/storage/bootstrap/config.cache.php
EOF

FROM caddy:latest AS development-web
COPY --link Caddyfile /etc/caddy/Caddyfile

FROM development-web AS production-web
COPY --link ./public /var/www/public
