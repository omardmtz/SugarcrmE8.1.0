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

/**
 * View iterator
 */
class ViewIterator
{
    /**
     * @var string The name of the module whose view is expected to be iterated over
     */
    protected $module;

    /**
     * @var array Field definitions of the module
     */
    protected $fieldDefs = array();

    /**
     * Constructor
     *
     * @param string $module Module name
     * @param array $fieldDefs Field definitions
     */
    public function __construct($module, array $fieldDefs)
    {
        $this->module = $module;
        $this->fieldDefs = $fieldDefs;
    }

    /**
     * Applies the given callback to every field of the view
     *
     * @param array $fieldSet Field set definition
     * @param callable $callback Callback to be applied
     */
    public function apply(array $fieldSet, /* callable */ $callback)
    {
        foreach ($fieldSet as $field) {
            if (is_string($field)) {
                $field = array('name' => $field);
            }

            if (is_array($field)) {
                $type = 'base';
                if (isset($field['name'])) {
                    if (isset($this->fieldDefs[$field['name']]['type'])) {
                        $type = $this->fieldDefs[$field['name']]['type'];
                    }
                }
                $this->getSugarField($type)->iterateViewField($this, $field, $callback);
            }
        }
    }

    /**
     * Returns implementation of Sugar Field for the given type
     *
     * @param string $type Field type
     * @return SugarFieldBase
     */
    protected function getSugarField($type)
    {
        $sf = SugarFieldHandler::getSugarField($type);
        $sf->setModule($this->module);

        return $sf;
    }
}
