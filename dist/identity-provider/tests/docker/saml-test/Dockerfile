# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

FROM registry.sugarcrm.net/identity-provider/samlserver:latest

COPY config/simplesamlphp/config/config.php /var/www/simplesamlphp/config
COPY config/simplesamlphp/metadata/saml20-sp-remote.php /var/www/simplesamlphp/metadata
COPY config/simplesamlphp/metadata/endpoints.php /var/www/simplesamlphp/metadata
