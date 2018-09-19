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
 * @internal
 */
class SugarQuery_Builder_Join
{
    /**
     * @var array
     */
    public $options = array();

    /**
     * @var null|string|SugarQuery
     */
    public $table;

    /**
     * @var null|SugarQuery_Builder_Where
     */
    public $on;

    /**
     * @var bool|string
     */
    public $raw = false;

    /**
     * @var bool|string
     */
    public $linkName = false;

    /**
     * @var bool|SugarQuery
     */
    public $query = false;

    /**
     * @var bool|SugarBean
     */
    public $bean = false;

    public $relatedJoin = false;

    /**
     * @var string
     */
    public $relationshipTableAlias;

    /**
     * Create the JOIN Object
     * @param string $table
     * @param array $options
     * @throws SugarQueryException
     */
    public function __construct($table, array $options = array())
    {
        if (!is_string($table) && !isset($options['alias'])) {
            throw new SugarQueryException('Joined sub-query must have alias');
        }

        // Set the table to JOIN on
        $this->table = $table;
        $this->bean = !empty($options['bean']) ? $options['bean'] : false;
        unset($options['bean']);
        $this->relatedJoin = !empty($options['relatedJoin']) ? $options['relatedJoin'] : false;
        unset($options['relatedJoin']);
        $this->options = array_merge(array(
            'joinType' => 'INNER',
        ), $options);
    }

    /**
     * Sets and returns the ON criteria
     *
     * @return SugarQuery_Builder_Andwhere
     * @throws SugarQueryException
     */
    public function on()
    {
        if (isset($this->on)) {
            if (!$this->on instanceof SugarQuery_Builder_Andwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level ON operator from %s to %s',
                    $this->on->operator(),
                    'AND'
                ));
            }
        } else {
            $this->on = new SugarQuery_Builder_Andwhere($this->query, $this->bean);
        }

        return $this->on;
    }

    /**
     * Sets and returns the ON criteria
     *
     * @return object this
     * @throws SugarQueryException
     */
    public function onOr()
    {
        if (isset($this->on)) {
            if (!$this->on instanceof SugarQuery_Builder_Orwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level ON operator from %s to %s',
                    $this->on->operator(),
                    'OR'
                ));
            }
        } else {
            $this->on = new SugarQuery_Builder_Orwhere($this->query, $this->bean);
        }

        return $this->on;
    }

    /**
     * Add a string of Raw SQL
     * @param string $sql
     * @return SugarQuery_Builder_Join
     */
    public function addRaw($sql)
    {
        $this->raw = $sql;
        return $this;
    }

    /**
     * Add a string that is a link name from vardefs
     * @param string $linkName
     * @return SugarQuery_Builder_Join
     */
    public function addLinkName($linkName)
    {
        $this->linkName = $linkName;
        return $this;
    }

    /**
     * Return name of the join table
     * @return string
     */
    public function joinName()
    {
        if (!empty($this->options['alias'])) {
            return $this->options['alias'];
        }
        return $this->table;
    }

    public function join($link, $options = array())
    {
        $options['relatedJoin'] = $this->options['alias'];
        return $this->query->join($link, $options);
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
