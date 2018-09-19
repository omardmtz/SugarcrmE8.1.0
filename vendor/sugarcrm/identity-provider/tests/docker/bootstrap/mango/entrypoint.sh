#!/bin/bash

# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

# chown to get rid of "Can't open and lock privilege tables: Table 'mysql.host' doesn't exist" fatal error on mysql start
chown -R mysql:mysql /var/lib/mysql && chown -R mysql:mysql /var/log/mysql && service mysql start


# copy config override
CONFIG_FROM_VOLUME="/var/www/sugarcrm-config/config_override.php"
SUGAR_CONFIG="/var/www/sugarcrm/config_override.php"

if [ -f $CONFIG_FROM_VOLUME ]; then
	cp $CONFIG_FROM_VOLUME $SUGAR_CONFIG
	cd /var/www/sugarcrm/
	php refreshCache.php
	chown -R www-data:www-data /var/www/sugarcrm/cache/*
fi


exec /usr/sbin/apache2ctl -D FOREGROUND
