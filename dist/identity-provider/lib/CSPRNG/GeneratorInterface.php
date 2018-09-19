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
 * Generator random string.
 *
 * Interface GeneratorInterface
 * @package Sugarcrm\IdentityProvider\CSPRNG
 */
interface GeneratorInterface
{
    /**
     * Generates cryptographically secure pseudo-random string.
     *
     * @param int $size the length of the random string that should be returned in bytes.
     * @param string $prefix
     * @return string
     * @throws \RuntimeException
     */
    public function generate($size, $prefix = '');
}
