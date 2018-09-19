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
 * Sanitizer interface
 *
 * Input sanitization which is applied on every superglobal value before
 * constraint specific sanitizing and validation occurs.
 *
 * Note that sanitizing is a bad habit. It is better to teach your users how to
 * properly format input data by having full whitelist validation reject badly
 * crafted values.
 *
 */
interface SanitizerInterface
{
    /**
     * @param mixed $value Value to be sanitized
     * @return mixed Sanitized value
     */
    public function sanitize($value);
}
