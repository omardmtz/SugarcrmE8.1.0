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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage;

use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State\Storage;

/**
 * In-memory implementation of the state storage for behavior testing purposes
 */
final class InMemoryStorage implements Storage
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * {@inheritDoc}
     */
    public function get($var)
    {
        if (isset($this->data[$var])) {
            return $this->data[$var];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function update($var, $value)
    {
        $this->data[$var] = $value;
    }
}
