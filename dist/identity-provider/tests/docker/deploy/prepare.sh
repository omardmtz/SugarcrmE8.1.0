# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

cp app/config/parameters.php.test app/config/parameters.php

# ToDo: replace with actual production keys.
cp -rf app/config/certs/travis.crt.dist app/config/certs/travis.crt
cp -rf app/config/certs/travis.key.dist app/config/certs/travis.key
