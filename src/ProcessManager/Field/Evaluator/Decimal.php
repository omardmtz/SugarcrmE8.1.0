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
 * Decimal field evaluator object. This should not be confused with the Float
 * field type, as floats are treated as strings across the board, while decimal
 * fields are treated like Int fields, except as a floating point value.
 * @package ProcessManager
 */
class Decimal extends Base implements EvaluatorInterface
{
    /**
     * @inheritDoc
     */
    public function hasChanged()
    {
        if ($this->isCheckable()) {
            return $this->data[$this->name] !== floatval($this->bean->{$this->name});
        }

        return false;
    }
}
