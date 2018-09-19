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

namespace Sugarcrm\IdentityProvider\Srn;

class SrnRules
{
    const SCHEME = 'srn';

    const MAX_LENGTH = 255;

    const MIN_COMPONENTS = 6;

    const ALLOWED_CHARS = '/^[a-zA-Z0-9_\-.;\/]*$/';

    const SEPARATOR = ':';

    const TENANT_LENGTH = 10;

    const TENANT_REGEX = '/^\d{1,' . self::TENANT_LENGTH . '}$/';
}
