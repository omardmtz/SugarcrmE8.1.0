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

namespace Sugarcrm\Sugarcrm\Security\Password;

use Sugarcrm\Sugarcrm\Security\Crypto\CSPRNG;
use Sugarcrm\Sugarcrm\Security\Password\Exception\RuntimeException;

/**
 *
 * Password salt generator
 *
 * This class makes use of the CSPRNG to generate base64 character based
 * salt values. It has the ability to perform per character substitution
 * against the base64 character set to compensate for different encoding
 * schemes.
 *
 */
class Salt
{
    const BASE64_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    /**
     * Crypto Secure PRNG
     * @var CSPRNG
     */
    protected $csprng;

    /**
     * Substitution characters
     * @var string
     */
    protected $substitution;

    /**
     * Ctor
     * @param CSPRNG $csprng
     */
    public function __construct(CSPRNG $csprng = null)
    {
        $this->csprng = $csprng ?: CSPRNG::getInstance();
    }

    /**
     * Generate salt value for given size
     * @param integer $size Byte size
     * @return string
     * @throws RuntimeException
     */
    public function generate($size)
    {
        $salt = $this->csprng->generate($size, true);

        if (!$salt) {
            throw RuntimeException("Error generating salt");
        }

        return $this->substitution ? $this->substitute($salt) : $salt;
    }

    /**
     * Set substitution characters
     * @param string $chars
     */
    public function setSubstitution($chars)
    {
        $this->substitution = $chars;
    }

    /**
     * Perform a character by character substitution based on the the
     * configured substitution string against the base64 char set.
     *
     * @param string $salt
     * @return string
     */
    protected function substitute($salt)
    {
        return strtr($salt, self::BASE64_CHARS, $this->substitution);
    }
}
