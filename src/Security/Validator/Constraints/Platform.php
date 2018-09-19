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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 *
 * @see PlatformValidator
 *
 */
class Platform extends Constraint
{
    const ERROR_INVALID_PLATFORM_FORMAT = 0;
    const ERROR_INVALID_PLATFORM = 1;

    /**
     * {@inheritdoc}
     */
    protected static $errorNames = array(
        self::ERROR_INVALID_PLATFORM_FORMAT => 'ERROR_INVALID_PLATFORM_FORMAT',
        self::ERROR_INVALID_PLATFORM => 'ERROR_INVALID_PLATFORM',
    );

    /**
     * Message template
     * @var string
     */
    public $message = 'Platform name violation: %reason% (%platform%)';
}
