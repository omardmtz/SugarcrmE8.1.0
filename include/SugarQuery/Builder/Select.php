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

class SugarQuery_Builder_Select
{
    /**
     * @var SugarQuery_Builder_Field_Select[]
     */
    public $select = array();

    protected $query;

    protected $countQuery = false;

    /**
     * Selected field names indexed by table
     *
     * @var true[][]
     */
    private $fieldsByTable = [];

    /**
     * Create Select Object
     *
     * @param SugarQuery $query
     */
    public function __construct(SugarQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Select method
     * Add select elements
     * @param string $columns
     * @return object this
     */
    public function field($columns)
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }
        foreach ($columns as $column) {
            $field = new SugarQuery_Builder_Field_Select($column, $this->query);
            $key = empty($field->alias) ? $field->field : $field->alias;
            if(!$field->isNonDb()) {
                $this->select[$key] = $field;
                $this->fieldsByTable[$field->table][$field->field] = true;
            }
        }
        return $this;
    }

    /**
     * Adds a raw piece of SQL to the select
     * @param string $columns The raw SQL to execute
     * @param string $alias What to alias the sql as (optional)
     * @return object this
     */
    public function fieldRaw($columns, $alias = '')
    {
        $field = new SugarQuery_Builder_Field_Raw($columns, $this->query);
        if (!empty($alias)) {
            $field->alias = $alias;
        } else {
            $alias = md5($columns);
        }
        $this->select[$alias] = $field;
        return $this;
    }

    public function addField($column, $options = array())
    {
        if (!empty($options['alias'])) {
            $column = array(array($column, $options['alias']));
        }
        $this->field($column);
    }

    /**
     * Check if a field is already being selected
     * @param string $field
     * @param string $table
     * @return bool
     */
    public function checkField($field, $table)
    {
        return isset($this->fieldsByTable[$table][$field]);
    }

    /**
     * Clear out the objects select array
     *
     * @return static
     */
    public function selectReset()
    {
        $this->select = $this->fieldsByTable = [];

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Make the query COUNT query
     *
     * @return static
     */
    public function setCountQuery()
    {
        $this->countQuery = true;
        return $this;
    }

    /**
     * Check if the the query is COUNT query
     *
     * @return bool
     */
    public function getCountQuery()
    {
        return $this->countQuery;
    }

    /**
     * Returns names of the fields selected from the given table
     *
     * @param string $table
     * @return string[]
     */
    public function getSelectedFieldsByTable(string $table) : array
    {
        if (!isset($this->fieldsByTable[$table])) {
            return [];
        }

        return array_keys($this->fieldsByTable[$table]);
    }
}
