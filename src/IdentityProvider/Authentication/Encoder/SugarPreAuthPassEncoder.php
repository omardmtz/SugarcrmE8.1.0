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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class SugarPreAuthPassEncoder implements PasswordEncoderInterface
{
    /**
     * encode form login raw password before check
     * @param string $raw
     * @param string $salt
     * @param bool $isPasswordEncrypted
     * @return string
     */
    public function encodePassword($raw, $salt, $isPasswordEncrypted = false)
    {
        if (!$isPasswordEncrypted) {
            $raw = strtolower(md5($raw));
        }
        return $raw;
    }

    /**
     * Will be implemented in Phase 2
     * @param string $encoded
     * @param string $raw
     * @param string $salt
     * @return bool
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return false;
    }
}
