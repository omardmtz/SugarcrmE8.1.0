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

class SugarQuery_Builder_Condition
{
    /**
     * @var string
     */
    public $operator;
    /**
     * @var SugarQuery_Builder_Field_Condition
     */
    public $field;
    /**
     * @var array
     */
    public $values = array();
    /**
     * @var bool|SugarBean
     */
    public $bean = false;
    /**
     * @var bool
     */
    public $isNull = false;
    /**
     * @var bool
     */
    public $notNull = false;

    /**
     * @var SugarQuery
     */
    public $query;

    /**
     * @var bool
     */
    protected $isAclIgnored;

    public function __construct(SugarQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @param string $operator
     * @return SugarQuery_Builder_Condition
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param array $values
     * @return SugarQuery_Builder_Condition
     */
    public function setValues($values)
    {
        if (is_array($values) && count($values) == 1 && key($values) === '$field') {
            $values = new SugarQuery_Builder_Field(current($values), $this->query);
        } else {
            $this->field->verifyCondition($values, $this->query);
        }
        $this->values = $values;
        return $this;
    }

    /**
     * @param string $field
     * @return SugarQuery_Builder_Condition
     */
    public function setField($field)
    {
        $this->field = new SugarQuery_Builder_Field_Condition($field, $this->query);
        return $this;
    }

    /**
     * @param SugarBean $bean
     */
    public function setBean(SugarBean $bean)
    {
        $this->bean = $bean;
    }

    /**
     * @return SugarQuery_Builder_Condition
     */
    public function isNull()
    {
        $this->isNull = true;
        return $this;
    }

    /**
     * @return SugarQuery_Builder_Condition
     */
    public function notNull()
    {
        $this->notNull = true;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Marks condition as ignoring ACL
     */
    public function ignoreAcl()
    {
        $this->isAclIgnored = true;
    }

    /**
     * Checks
     */
    public function isAclIgnored()
    {
        return $this->isAclIgnored;
    }
}
