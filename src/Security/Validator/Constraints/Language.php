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
 * @see LanguageValidator
 *
 */
class Language extends Constraint
{
    const ERROR_LANGUAGE_NOT_FOUND = 1;

    protected static $errorNames = array(
        self::ERROR_LANGUAGE_NOT_FOUND => 'ERROR_LANGUAGE_NOT_FOUND',
    );

    public $message = 'Language name violation: %msg%';
}
