<?php declare(strict_types=1);
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

namespace Sugarcrm\Sugarcrm\DataPrivacy\Erasure;

use JsonSerializable;
use SugarBean;

/**
 * Represents a field marked for erasure
 */
interface Field extends JsonSerializable
{
    /**
     * Erases data from the given bean
     *
     * @param SugarBean $bean
     */
    public function erase(SugarBean $bean) : void;
}
