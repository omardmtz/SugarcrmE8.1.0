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

namespace Sugarcrm\Sugarcrm\Audit;

use JsonSerializable;

/**
 * Represents a change to a field in the Audit log
 */
interface Change extends JsonSerializable
{
    /**
     * Constructs a new Audit Field Change from an in ['before', 'after'] format
     * @param array $change
     *
     * @return Change[]
     */
    public static function getAuditFieldChanges(array $change);

    /**
     * @return array[
     *  string field_name
     *  string data_type
     *  mixed before
     *  mixed after
     * ]
     */
    public function getChangeArray();
}
