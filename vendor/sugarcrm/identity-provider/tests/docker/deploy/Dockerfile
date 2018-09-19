# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

FROM php:7.1-apache

RUN apt-get update && apt-get install -y \
        git \
        libmcrypt-dev \
        libxml2-dev \
        libssl-dev \
        libldap2-dev \
        libsasl2-dev \
        libgmp-dev \
        unzip \
        libaio1 \
        --no-install-recommends \
    && ln -fs /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/ \
    && ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h \
    && docker-php-ext-install mcrypt zip mysqli pdo_mysql \
    && docker-php-ext-configure ldap --with-ldap-sasl --with-ldap \
    && docker-php-ext-install ldap \
    && docker-php-ext-install gmp \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o \
    APT::AutoRemove::SuggestsImportant=false

# Apache set up
RUN a2enmod rewrite
COPY app/deploy/apache/identity-provider.conf /etc/apache2/sites-available
COPY app/deploy/apache/php.ini /usr/local/etc/php
RUN a2dissite 000-default.conf default-ssl.conf
RUN a2ensite identity-provider.conf

# Identity Provider setup
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install
RUN tests/docker/deploy/prepare.sh
RUN chown -R www-data:www-data /var/www/html/var

EXPOSE 80
CMD ["apache2-foreground"]
