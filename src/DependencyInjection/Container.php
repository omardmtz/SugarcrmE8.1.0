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

namespace Sugarcrm\Sugarcrm\DependencyInjection;

use Psr\Container\ContainerInterface;

class Container
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * Get a container instance
     *
     * @return ContainerInterface
     */
    public static function getInstance()
    {
        if (!self::$container) {
            self::$container = require SUGAR_BASE_DIR . '/etc/container.php';
        }

        return self::$container;
    }

    /**
     * Welcome to the world of singletons!
     */
    public static function resetInstance()
    {
        self::$container = null;
    }
}
