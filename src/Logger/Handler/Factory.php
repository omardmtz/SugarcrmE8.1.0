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

namespace Sugarcrm\Sugarcrm\Logger\Handler;

use Monolog\Handler\HandlerInterface;

/**
 * Interface for handler factories
 */
interface Factory
{
    /**
     * Creates handler
     *
     * @param int $level Logging level
     * @param array $config Handler-specific configuration
     *
     * @return HandlerInterface
     */
    public function create($level, array $config);
}
