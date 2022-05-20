FROM php:8.1-fpm-buster

MAINTAINER Cédric Rassaert <contact@numadream.com>

# Install PHP extensions and PECL modules.
RUN buildDeps=" \
        libbz2-dev \
        libmemcached-dev \
        libsasl2-dev \
    " \
    runtimeDeps=" \
        curl \
        git \
        acl \
        libicu-dev \
        libpcre3-dev \
        libzip-dev \
        libldap2-dev \
        libxslt1-dev \
        libmemcachedutil2 \
        libpq-dev \
        libxml2-dev \
    " \
    && apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y $buildDeps $runtimeDeps \
    && docker-php-ext-install bcmath bz2 calendar opcache pdo_pgsql pgsql soap zip \
    && docker-php-ext-install ldap \
    && docker-php-ext-install xsl \
    && docker-php-ext-install exif \
    && apt-get purge -y --auto-remove $buildDeps \
    && rm -r /var/lib/apt/lists/*

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
