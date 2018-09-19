<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */


class OAuth2MobileApi extends OAuth2Api
{

    /**
     * This function checks to make sure that if a client version is supplied it is up to date.
     *
     * @param ServiceBase $api The service api
     * @param array $args The arguments passed in to the function
     * @return bool True if the version was good, false if it wasn't
     */
    public function isSupportedClientVersion(ServiceBase $api, array $args)
    {
        if (!empty($args['client_info']['app']['name'])
            && !empty($args['client_info']['app']['version'])) {

            $name = $args['client_info']['app']['name'];

            //Non-native mobile clients are bunbled with the sugar build and are therefore exempt from version checks
            if ($name == 'nomad' &&
                isset($args['client_info']['app']['isNative']) &&
                !isTruthy($args['client_info']['app']['isNative'])
            ) {
                return true;
            }
        }

        return parent::isSupportedClientVersion($api, $args);
    }
}
