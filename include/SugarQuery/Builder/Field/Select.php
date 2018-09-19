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
 * SugarQuery_Builder_Field_Select
 * @api
 */

class SugarQuery_Builder_Field_Select extends SugarQuery_Builder_Field
{
    public function __construct($field, SugarQuery $query)
    {
        parent::__construct($field, $query);
    }

    public function expandField()
    {
        $this->checkCustomField();

        if (isset($this->def['type']) && $this->def['type'] == 'function') {
            if(!empty($this->def['function_params'])) {
                foreach($this->def['function_params'] as $param) {
                    $this->addToSelect("{$this->table}.{$param}");
                }
            }
            $this->markNonDb();
            return;
        }

        if (empty($this->alias) && !empty($this->def['name'])) {
            $this->alias = $this->def['name'];
        }

        if (!empty($this->alias)) {
            $this->alias = $this->query->getValidColumnAlias($this->alias);
        }

        if ($this->field == '*') {
            // remove *
            $bean = $this->query->getFromBean();
            if (empty($this->moduleName)) {
                $this->moduleName = $bean->module_name;
            }
            foreach ($bean->field_defs AS $field => $def) {
                if (!isset($def['source'])
                    || $def['source'] == 'db'
                    || ($def['source'] == 'custom_fields' && !in_array($def['type'], $bean::$relateFieldTypes))
                ) {
                    $this->addToSelect("{$this->table}.{$field}");
                }
            }
            $this->markNonDb();
            return;
        }

        if ($this->def['type'] == 'fullname') {
            $from = $this->query->getFromBean();
            $nameFields = Localization::getObject()->getNameFormatFields($this->moduleName);
            foreach ($nameFields as $partOfName) {
                $fqn = sprintf('%s.%s', $this->table, $partOfName);

                // check if the field belongs to the primary table
                if ($this->table === $this->query->getFromAlias()) {
                    $this->addToSelect($fqn);
                } else {
                    $fieldAlias = $this->alias ?: $this->def['name'];
                    $columnAlias = $from->getRelateAlias($fieldAlias, $partOfName);
                    $this->addToSelect([[$fqn, $columnAlias]]);
                }
            }

            $this->markNonDb();
            return;
        }

        if (!isset($this->def['source']) || $this->def['source'] == 'db') {
            return;
        }
        if (!empty($this->def['fields'])) {
            // this is a compound field
            foreach ($this->def['fields'] as $field) {
                $this->addToSelect("{$this->table}.{$field}");
            }
        }
        if ($this->def['type'] == 'parent') {
            $this->query->hasParent($this->field);
            $this->addToSelect('parent_type');
            $this->addToSelect('parent_id');
            $this->markNonDb();
        }
        if (isset($this->def['custom_type']) && $this->def['custom_type'] == 'teamset') {
            $this->addToSelect('team_set_id');
        }

        // Exists only checks
        if (!empty($this->def['rname_exists'])) {
            $this->markNonDb();
            $this->addToSelectRaw("case when {$this->jta}.{$this->def['rname']} IS NOT NULL then 1 else 0 end",$this->field);
            return;
        }

        if (!empty($this->def['rname']) && !empty($this->jta)) {
            $field = array("{$this->jta}.{$this->def['rname']}", $this->def['name']);
            $this->addToSelect(array($field));
            if (isset($this->def['module'])) {
                $rBean = BeanFactory::getDefinition($this->def['module']);
                $ownerField = $rBean->getOwnerField();
                if ($ownerField) {
                    $this->query->select->addField($this->jta . '.' . $ownerField, array(
                        'alias' => $this->def['name'] . '_owner',
                    ));
                }
            }
            $this->markNonDb();
        }
        if (!empty($this->def['rname_link']) && !empty($this->jta)) {
            $this->field = $this->def['rname_link'];
            $this->alias = $this->def['name'];
        }
        if (!empty($this->def['source']) && $this->def['source'] == 'custom_fields') {
            $this->table = strstr($this->table, '_cstm') ? $this->table : $this->table . '_cstm';
        }
        if (!empty($this->def['db_concat_fields'])) {
            $tableAlias = $this->jta ? $this->jta : $this->table;
            $expr = $GLOBALS['db']->concat($tableAlias, $this->def['db_concat_fields']);
            $this->field = $expr;
            $this->markNonDb();
            $this->addToSelectRaw($expr, $this->alias);
        }
    }

    public function addToSelect($field)
    {
        if (!is_object($this->query->select)) {
            $this->query->select($field);
        } else {
            $this->query->select->field($field);
        }
        return true;
    }

    public function addToSelectRaw($field, $alias = '')
    {
        $this->query->select->fieldRaw($field, $alias);
    }
}
