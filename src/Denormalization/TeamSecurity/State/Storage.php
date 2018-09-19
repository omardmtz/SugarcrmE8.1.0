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

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\State;

/**
 * Denormalization state storage
 */
interface Storage
{
    /**
     * Returns state variable
     *
     * @param string $var
     * @return mixed
     */
    public function get($var);

    /**
     * Updates state variable
     *
     * @param string $var
     * @param mixed $value
     * @return void
     */
    public function update($var, $value);
}
