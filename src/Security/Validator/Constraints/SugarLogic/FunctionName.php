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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints\SugarLogic;

use Symfony\Component\Validator\Constraint;

/**
 *
 * @see FunctionNameValidator
 *
 */
class FunctionName extends Constraint
{
    const ERROR_INVALID_FUNCTION_NAME = 1;

    protected static $errorNames = array(
        self::ERROR_INVALID_FUNCTION_NAME => 'ERROR_INVALID_FUNCTION_NAME',
    );

    public $message = 'Function name violation: %msg%';
}
