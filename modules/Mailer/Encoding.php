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

/**
 * This class defines valid encodings to aid in maintaining a list of valid encodings that can be referenced from a
 * single source.
 */
class Encoding
{
    const EightBit        = "8bit";
    const SevenBit        = "7bit";
    const Binary          = "binary";
    const Base64          = "base64";
    const QuotedPrintable = "quoted-printable";

    /**
     * Returns true/false indicating whether or not $encoding is a valid, known encoding for the context of a Mailer.
     *
     * @static
     * @access public
     * @param string $encoding required
     * @return bool
     */
    public static function isValid($encoding) {
        switch ($encoding) {
            case self::EightBit:
            case self::SevenBit:
            case self::Binary:
            case self::Base64:
            case self::QuotedPrintable:
                return true;
                break;
            default:
                return false;
                break;
        }
    }
}
