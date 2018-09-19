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

namespace Sugarcrm\IdentityProvider\Tests\Functional;

/**
 * Class Config
 *
 * Class to work with configuration for functional tests.
 *
 * @package Sugarcrm\IdentityProvider\Tests\Functional
 */
class Config
{
    /**
     * Method to retrieve some configuration option.
     *
     * @param string $name Configuration option name
     * @param bool $default Default value
     * @return mixed
     */
    public static function get($name, $default = false)
    {
        $value = getenv($name);

        return $value ?: $default;
    }
}
