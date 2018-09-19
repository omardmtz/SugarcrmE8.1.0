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

use Sugarcrm\Sugarcrm\Security\InputValidation\Superglobals;
use Symfony\Component\Validator\Constraint;

/**
 *
 * @see InputParametersValidator
 *
 */
class InputParameters extends Constraint
{
    const ERROR_REQUEST = 1;
    const ERROR_GET = 2;
    const ERROR_POST = 3;

    protected static $errorNames = array(
        self::ERROR_REQUEST => 'ERROR_REQUEST',
        self::ERROR_GET => 'ERROR_GET',
        self::ERROR_POST => 'ERROR_POST',
    );

    public $msgGeneric = 'Generic input violation for input parameter [%type%]';
    public $msgNullBytes = 'Null bytes violation for input parameter [%type%]';
    public $inputType = Superglobals::GET;
}
