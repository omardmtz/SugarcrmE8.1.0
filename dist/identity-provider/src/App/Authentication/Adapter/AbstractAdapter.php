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

namespace Sugarcrm\IdentityProvider\App\Authentication\Adapter;

abstract class AbstractAdapter
{
    /**
     * modify idp-api configs to ipd-php
     * @param $config
     * @return array
     */
    abstract public function getConfig($config);

    /**
     * Decodes a JSON string.
     * @param string $encoded
     * @return mixed
     */
    protected function decode($encoded)
    {
        if (empty($encoded)) {
            return [];
        }
        try {
            return \GuzzleHttp\json_decode($encoded, true);
        } catch (\InvalidArgumentException $e) {
            return [];
        }
    }
}
