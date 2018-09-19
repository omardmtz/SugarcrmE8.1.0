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

namespace Sugarcrm\Sugarcrm\Logger\Processor\Factory;

use Sugarcrm\Sugarcrm\Logger\Processor\BacktraceProcessor;
use Sugarcrm\Sugarcrm\Logger\Processor\Factory;

/**
 * Backtrace processor factory
  */
class Backtrace implements Factory
{
    /** @inheritDoc */
    public function create(array $config)
    {
        return new BacktraceProcessor();
    }
}
