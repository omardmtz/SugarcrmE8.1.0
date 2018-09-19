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
 * Currency evaluator object.
 * @package ProcessManager
 */
class Currency extends Base implements EvaluatorInterface
{
    /**
     * The currency ID field that is on the bean and in the data
     * @var string
     */
    protected $idField = 'currency_id';

    /**
     * @inheritDoc
     */
    public function hasChanged()
    {
        if ($this->isCheckable()) {
            // To check change on a Currency field we check both the values of
            // the bean and data AND the currency_id on the bean and in the data
            return $this->valueHasChanged() || $this->idHasChanged();
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function isCheckable()
    {
        return parent::isCheckable()
               && isset($this->data[$this->idField]) && isset($this->bean->{$this->idField});
    }

    /**
     * Checks if the value of the currency field has changed
     * @return boolean
     */
    protected function valueHasChanged()
    {
        return $this->data[$this->name] !== $this->bean->{$this->name};
    }

    /**
     * Checks if the currency_id has changed
     * @return boolean
     */
    protected function idHasChanged()
    {
        return $this->data[$this->idField] !== $this->bean->{$this->idField};
    }
}
