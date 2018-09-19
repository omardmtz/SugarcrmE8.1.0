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
 * Multi select evaluator object. Handles evaluation of multienum fields.
 * @package ProcessManager
 */
class Multienum extends Base implements EvaluatorInterface
{
    /**
     * @inheritDoc
     */
    public function hasChanged()
    {
        if ($this->isCheckable()) {
            // Get what is on the bean as an array
            $beanValues = unencodeMultienum($this->bean->{$this->name});

            // The data sent should already be an array, but cast it for safety's
            // sake
            $dataValues = (array) $this->data[$this->name];

            // Lets calculate some diffs, shall we?
            $diff1 = array_diff($beanValues, $dataValues);
            $diff2 = array_diff($dataValues, $beanValues);

            // If either of the diffs are not empty then there was a change
            return !empty($diff1) || !empty($diff2);
        }

        return false;
    }
}
