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

namespace Sugarcrm\Sugarcrm\Security\InputValidation\Sanitizer;

/**
 *
 * Ability to add additional sanitizing on validator constraints. Although this
 * option exists its encouraged to perform validation and report a violation
 * instead of sanitizing the user input.
 *
 */
interface ConstraintSanitizerInterface
{
    /**
     * Sanitize the given value. Sanitizing happens before the validation.
     *
     * @param mixed $value Raw value
     * @return mixed Sanitzed string value
     */
    public function sanitize($value);
}
