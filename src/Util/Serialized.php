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

namespace Sugarcrm\Sugarcrm\Util;

use Sugarcrm\Sugarcrm\Security\Validator\Constraints\PhpSerialized;
use Sugarcrm\Sugarcrm\Security\Validator\Validator;

/**
 * Class Serialized
 *
 * This class represents a serialization/unserialization of untrusted data. If data comes directly from user input please
 * use InputValidation.
 */
class Serialized
{
    /**
     * Performs unserialization of untrusted php serialized data. In case of violations a default value is returned.
     *
     * @param string $value Serialized value of any type except Object
     * @param mixed $default Default value
     * @param bool|false $base64 Requires base64 decoding
     * @param bool|false $html Requires html decoding
     * @return mixed
     */
    public static function unserialize($value, $default = null, $base64 = false, $html = false)
    {
        $constraint = new PhpSerialized();
        $constraint->base64Encoded = $base64;
        $constraint->htmlEncoded = $html;

        $violations = Validator::getService()->validate($value, $constraint);
        if (count($violations) === 0) {
            return $constraint->getFormattedReturnValue();
        } else {
            return $default;
        }
    }
}
