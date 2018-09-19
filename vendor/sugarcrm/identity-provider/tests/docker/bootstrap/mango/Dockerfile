# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

FROM php:7.1-apache-jessie

# install common packages
RUN apt-get update && apt-get install -y \
        apt-utils \
        apt-transport-https \
        lsb-release \
        curl \
        wget \
        ca-certificates \
        libssl-dev \
        unzip \
        libaio1 \
        --no-install-recommends


# Install PHP dependencies
RUN apt-get update && \
    apt-get install -y g++ \
                      openssl \
                      libc-client-dev \
                      libkrb5-dev \
                      libxml2-dev \
                      libfreetype6-dev \
                      libgd-dev \
                      libldap2-dev \
                      libsasl2-dev \
                      libmcrypt-dev \
                      libcurl4-openssl-dev \
                      libgmp-dev \
                      --no-install-recommends && \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false && \
    ln -fs /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
        docker-php-ext-configure gd --with-freetype-dir=/usr && \
        docker-php-ext-configure bcmath && \
        docker-php-ext-configure ldap --with-ldap-sasl --with-ldap

RUN docker-php-ext-install mysqli pdo_mysql imap gd exif mcrypt dom gettext mbstring soap zip bcmath ldap gmp


# MySQL set up
RUN echo 'mysql-server mysql-server/root_password password Sugar123' | debconf-set-selections && \
    echo 'mysql-server mysql-server/root_password_again password Sugar123' | debconf-set-selections && \
    apt-get -y install mysql-server

# Apache set up
RUN mkdir /var/www/sugarcrm
RUN a2enmod rewrite
COPY apache/mango.conf /etc/apache2/sites-available
COPY apache/apache2.conf /etc/apache2
COPY apache/php.ini /usr/local/etc/php/conf.d/custom.ini
RUN a2dissite 000-default.conf default-ssl.conf
RUN a2ensite mango.conf

# LDAP setup
COPY ldap/ldap.conf /etc/ldap/ldap.conf

#prepare mango
COPY sugarcrm /var/www/sugarcrm
COPY config_override.php /var/www/sugarcrm/config_override.php
COPY config_si.php /var/www/sugarcrm/config_si.php
RUN chown -R www-data:www-data /var/www/sugarcrm

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod a+x /usr/local/bin/entrypoint.sh

COPY refreshCache.php /var/www/sugarcrm/refreshCache.php

WORKDIR /var/www/sugarcrm

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 80