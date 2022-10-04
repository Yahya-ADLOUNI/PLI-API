FROM php:8.0-fpm

# install system dependencies
RUN apt-get -y update
RUN apt-get install -y --no-install-recommends \
    libmcrypt-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    libmagickwand-dev \
    openssl \
    zip unzip \
    git \
    nodejs \
    libldap2-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install php dependencies
RUN pecl install mcrypt
RUN docker-php-ext-enable mcrypt

RUN docker-php-ext-configure zip;
RUN docker-php-ext-install pdo;
RUN docker-php-ext-install pdo_mysql;
RUN docker-php-ext-install gd;
RUN docker-php-ext-install zip;
RUN docker-php-ext-install xml;
RUN docker-php-ext-install ldap;

# add crontab file
RUN apt-get update && apt-get install -y cron

#RUN  \
#    pecl install imagick \
#    && docker-php-ext-enable imagick \
#    docker-php-ext-install mcrypt pdo_mysql \
#    && docker-php-ext-install gd
COPY api-infrastructure/docker/php/app.ini /etc/php7/conf.d/app.ini
WORKDIR /usr/local/apache2/htdocs

COPY . ./
# RUN composer install --no-interaction

# Install fake user pliuser
ARG pliuser_UID=1000
RUN addgroup --gid 1000 pliuser && \
    adduser --system --uid $pliuser_UID --home /home/pliuser --shell /bin/bash pliuser
RUN chown -R pliuser.pliuser /usr/local/apache2/htdocs

USER pliuser

# RUN php artisan key:generate

EXPOSE 9000
CMD ["php-fpm"]
