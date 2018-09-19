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

namespace Sugarcrm\IdentityProvider\CSPRNG;

/**
 * @inheritDoc
 */
class Generator implements GeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate($size, $prefix = '')
    {
        $length = $size - strlen($prefix);
        if ($length < 1) {
            throw new \RuntimeException('The size must be at least one point longer than the prefix length');
        }

        $bytes = random_bytes($length);
        $random = strtr(substr(base64_encode($bytes), 0, $length), '+/', '-_');
        $result = $prefix . $random;
        return $result;
    }
}
