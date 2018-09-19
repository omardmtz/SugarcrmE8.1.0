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
 * @see DropdownListValidator
 *
 */
class DropdownList extends Constraint
{
    const ERROR_INVALID_DROPDOWN_KEY = 1;
    protected static $errorNames = array(
        self::ERROR_INVALID_DROPDOWN_KEY => 'ML_LANGUAGE_FILE_KEYS_INVALID',
    );
    public $message = 'Dropdown list violation: %msg%';
}
