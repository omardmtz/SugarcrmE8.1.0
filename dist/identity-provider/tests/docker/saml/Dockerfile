# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

FROM php:7.1-apache

# Utilities
RUN apt-get update && \
    apt-get install -y apt-utils && \
    apt-get -y install apt-transport-https git curl wget --no-install-recommends && \
    apt-get -y install libmcrypt-dev && \
    docker-php-ext-install mcrypt


# SimpleSAMLphp
ARG SIMPLESAMLPHP_VERSION=1.14.11
RUN wget https://github.com/simplesamlphp/simplesamlphp/releases/download/v$SIMPLESAMLPHP_VERSION/simplesamlphp-$SIMPLESAMLPHP_VERSION.tar.gz -O /tmp/simplesamlphp.tar.gz && \
    tar xzf /tmp/simplesamlphp.tar.gz -C /tmp && \
    rm -f /tmp/simplesamlphp.tar.gz  && \
    ls -al /tmp && \
    mv /tmp/simplesamlphp-* /var/www/simplesamlphp
COPY config/simplesamlphp/config/config.php /var/www/simplesamlphp/config
COPY config/simplesamlphp/config/authsources.php /var/www/simplesamlphp/config
COPY config/simplesamlphp/metadata/saml20-sp-remote.php /var/www/simplesamlphp/metadata
COPY config/simplesamlphp/metadata/saml20-idp-remote.php /var/www/simplesamlphp/metadata
COPY config/simplesamlphp/metadata/saml20-idp-hosted.php /var/www/simplesamlphp/metadata
COPY config/simplesamlphp/cert/server.crt /var/www/simplesamlphp/cert/
COPY config/simplesamlphp/cert/server.pem /var/www/simplesamlphp/cert/
RUN touch /var/www/simplesamlphp/modules/exampleauth/enable

# Apache
RUN echo "ServerName samltestserver" >> /etc/apache2/apache2.conf
COPY config/apache/simplesamlphp.conf /etc/apache2/sites-available
COPY config/apache/cert.crt /etc/ssl/cert/cert.crt
COPY config/apache/private.key /etc/ssl/private/private.key
RUN a2enmod ssl
RUN a2dissite 000-default.conf default-ssl.conf
RUN a2ensite simplesamlphp.conf

# Set work dir
WORKDIR /var/www/simplesamlphp

# General setup
EXPOSE 80 443
