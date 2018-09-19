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
 * @see ComponentNameValidator
 *
 */
class ComponentName extends Constraint
{
    const ERROR_INVALID_COMPONENT_NAME = 1;
    const ERROR_RESERVED_KEYWORD = 2;

    protected static $errorNames = array(
        self::ERROR_INVALID_COMPONENT_NAME => 'ERROR_INVALID_COMPONENT_NAME',
        self::ERROR_RESERVED_KEYWORD => 'ERROR_RESERVED_KEYWORD',
    );

    public $message = 'Component name violation: %msg%';

    /**
     * Are sql reserved words allowed?
     * @var bool
     */
    public $allowReservedSqlKeywords = true;
}
