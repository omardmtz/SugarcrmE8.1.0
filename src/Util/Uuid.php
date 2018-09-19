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

use Rhumsaa\Uuid\Uuid as UuidBackend;

/**
 *
 * RFC 4122 Universally unique identifier wrappers (UUID)
 *
 */
class Uuid
{
    /**
     * Generate variant 1 (time-based) UUID
     * @return string
     */
    public static function uuid1()
    {
        return UuidBackend::uuid1()->toString();
    }

    /**
     * Generate variant 4 (random) UUID
     * @return string
     */
    public static function uuid4()
    {
        return UuidBackend::uuid4()->toString();
    }

    /**
     * validate uuid
     * @param string $uuid
     * @return bool
     */
    public static function isValid(string $uuid)
    {
        return UuidBackend::isValid($uuid);
    }
}
