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

namespace Sugarcrm\Sugarcrm\ProcessManager\Field\Evaluator;

/**
 * Field evaluator interface
 * @package ProcessManager
 */
interface EvaluatorInterface
{
    /**
     * Checks to see if the field has changed
     * @return boolean
     */
    public function hasChanged();

    /**
     * Checks to see if the field contains an empty value
     * @return boolean
     */
    public function isEmpty();
}
