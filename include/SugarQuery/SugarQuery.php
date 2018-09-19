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


class SugarQuery
{

    /**
     * This is the Select Object
     * @var null|SugarQuery_Builder_Select
     */
    public $select = null;

    /**
     * This is the Union Object
     * @var null|SugarQuery_Builder_Union
     */
    public $union = null;

    /**
     * @var SugarQuery_Builder_Groupby[]
     */
    public $group_by = array();

    /**
     * @var null|SugarQuery_Builder_Where
     */
    public $having;

    /**
     * @var SugarQuery_Builder_Orderby[]
     */
    public $order_by = array();

    /**
     * @var null|integer
     */
    public $limit = null;

    /**
     * @var null|integer
     */
    public $offset = null;

    /**
     * @var bool
     */
    public $distinct = false;

    /**
     * @var null|SugarBean
     */
    public $from = false;

    /**
     * @var null|SugarQuery_Builder_Where
     */
    public $where;

    /**
     * @var SugarQuery_Builder_Join[]
     */
    public $join = array();

    protected $joined_tables = array();

    protected $jt_index = 0;

    /**
     * @var DBManager
     */
    protected $db;

    /**
     * Stores joins corresponding to links
     * @var array
     */
    protected $links = array();

    /**
     * Stores parent field for this query
     * @var array
     */
    protected $has_parent;

    /**
     * Bean templates for used tables
     * @var array
     */
    protected $table_beans = array();

    /**
     * If an rname_link field is used, this is the join alias
     * @var bool|string
     */
    public $rname_link = false;

    public $joinTableToKey = array();

    public $joinLinkToKey = array();

    public $fields = array();

    /**
     * @var bool True when the custom table for the current bean has already been added to the query
     */
    public $customJoined = false;

    /**
     * Whether the query should skip deleted records
     *
     * @var bool
     */
    protected $shouldSkipDeletedRecords = true;

    /**
     * Whether the query should skip deleted records
     *
     * @var bool
     */
    protected $shouldFetchErasedFields = false;

    /**
     * Mapping of original column aliases to their compact versions
     *
     * @var array
     */
    private $columnAliasMap = [];

    /**
     * This is used in Order By statements and in general is always true
     * @var boolean
     */
    protected $orderByStability = true;

    /**
     * @param DBManager $db
     */
    public function __construct(DBManager $db = null)
    {
        $this->select = new SugarQuery_Builder_Select($this, array());
        $this->setDBManager($db ?: DBManagerFactory::getInstance());
    }

    /**
     * Set DBManager
     * @param DBManager $db
     */
    public function setDBManager(DBManager $db)
    {
        $this->db = $db;
    }

    /**
     * Get DBManager
     * @return DBManager
     */
    public function getDBManager()
    {
        return $this->db;
    }

    /**
     * Sets the order by stability property
     * @param boolean $val The toggle value
     */
    public function setOrderByStability($val)
    {
        $this->orderByStability = (bool) $val;
    }

    /**
     * Gets the current value of the orderByStability property
     * @return boolean
     */
    public function getOrderByStability()
    {
        return $this->orderByStability;
    }

    /**
     * Build the select object
     *
     * @param array $fields
     *
     * @return null|SugarQuery_Builder_Select
     */
    public function select($fields = array())
    {
        if (!is_array($fields)) {
            $fields = func_get_args();
        }

        $this->select->field($fields);
        return $this->select;
    }

    /**
     * Build the union object.
     *
     * @param SugarQuery $select Query object.
     * @param bool $all (optional) Indicates if 'UNION ALL' should be used or not. Default is `true`.
     * @return SugarQuery_Builder_Union instance of union object.
     */
    public function union(SugarQuery $select, $all = true)
    {
        if (!is_object($this->union)) {
            $this->union = new SugarQuery_Builder_Union($this);
        }

        if (!empty($select)) {
            $this->union->addQuery($select, $all);
        }

        return $this->union;
    }

    /**
     * Set the from bean
     *
     * @param SugarBean $bean
     * @param array     $options
     *
     * @return SugarQuery
     */
    public function from(SugarBean $bean, $options = array())
    {
        if (is_string($options)){
            $options = array('alias' => $options);
        }

        $alias = (isset($options['alias'])) ? $options['alias'] : false;

        if (!empty($alias)) {
            $newAlias = $this->db->getValidDBName($alias, false, 'alias');
            if (strtolower($alias) != $newAlias) {
                throw new SugarQueryException("From alias is more than the max allowed length for an alias");
            }
        }

        $team_security = (isset($options['team_security'])) ? $options['team_security'] : true;
        $this->from = $bean;
        if (!empty($alias)) {
            $this->from = array($bean, $alias);
        }

        if ($team_security === true) {
            if (!empty($alias)) {
                $options['table_alias'] = $alias;
            }
            if (!isset($options['action'])) {
                $options['action'] = 'list';
            }
            $bean->addVisibilityQuery($this, $options);
        }

        if (isset($options['add_deleted']) && !$options['add_deleted']) {
            $this->shouldSkipDeletedRecords = false;
        }

        if (!empty($options['erased_fields'])) {
            $this->shouldFetchErasedFields = true;
        }

        $this->rebuildFields();

        return $this;
    }

    /**
     * Add an AND Where Object to this query
     *
     * @param array $conditions
     *
     * @return SugarQuery_Builder_Where
     */
    public function where($conditions = array())
    {
        if (isset($this->where)) {
            if (!$this->where instanceof SugarQuery_Builder_Andwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level WHERE operator from %s to %s',
                    $this->where->operator(),
                    'AND'
                ));
            }
        } else {
            $this->where = new SugarQuery_Builder_Andwhere($this);
        }

        if (!empty($conditions)) {
            $this->where->add($conditions);
        }

        return $this->where;
    }

    /**
     * Build a raw where statement
     *
     * @param string $sql
     *
     * @return SugarQuery_Builder_Andwhere
     */
    public function whereRaw($sql)
    {
        $where = new SugarQuery_Builder_Andwhere($this);
        $where->addRaw($sql);
        if (!isset($this->where)) {
            $this->where = new SugarQuery_Builder_Andwhere($this);
        }
        $this->where->add($where);
        return $this->where;
    }

    /**
     * Add an Or Where Object to this query
     *
     * @param array $conditions
     *
     * @return SugarQuery_Builder_Orwhere
     */
    public function orWhere($conditions = array())
    {
        if (isset($this->where)) {
            if (!$this->where instanceof SugarQuery_Builder_Orwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level WHERE operator from %s to %s',
                    $this->where->operator(),
                    'OR'
                ));
            }
        } else {
            $this->where = new SugarQuery_Builder_Orwhere($this);
        }

        if (!empty($conditions)) {
            $this->where->add($conditions);
        }

        return $this->where;
    }

    /**
     * Add a traditional query builder join object to this query
     *
     * @param string $table
     * @param array $options
     *
     * @return SugarQuery_Builder_Join
     */
    public function joinTable($table, $options = array())
    {
        if (!isset($options['linkingTable']) && !isset($options['bean'])) {
            $options['linkingTable'] = true;
        }
        $join = new SugarQuery_Builder_Join($table, $options);
        $join->query = $this;
        if (isset($options['alias'])) {
            $key = $options['alias'];
        } else {
            $key = $table;
        }

        $this->join[$key] = $join;

        if (is_string($table)) {
            $this->joinTableToKey[$table] = $key;
        }

        return $join;
    }

    /**
     * Add a join based on a link with the from bean
     *
     * @param string $link_name
     * @param array $options
     *
     * @return SugarQuery_Builder_Join
     */
    public function join($link_name, $options = array())
    {
        $relatedJoin = empty($options['relatedJoin']) ? false : $options['relatedJoin'];
        if (!isset($options['alias'])) {
            $options['alias'] = $this->getJoinTableAlias($link_name, $relatedJoin);
        }

        if (!isset($options['action'])) {
            $options['action'] = 'list';
        }

        if (!empty($this->links[$options['alias']])) {
            return $this->links[$options['alias']];
        }

        // FIXME: it's really not good we have a special case here
        if (!empty($options['favorites']) || $link_name == 'favorites') {
            $sfOptions = $options;
            $sf = BeanFactory::getDefinition('SugarFavorites');
            $options['alias'] = $sf->addToSugarQuery($this, $sfOptions);
        } else {
            $this->loadBeans($link_name, $options);
        }
        $this->join[$options['alias']]->addLinkName($link_name);
        $this->links[$link_name] = $this->join[$options['alias']];
        $link_name_key = $relatedJoin ? $relatedJoin . '_' . $link_name : $link_name;
        $this->joinLinkToKey[$link_name_key] = $options['alias'];

        return $this->join[$options['alias']];
    }

    /**
     *
     * Used to get a unique join table alias to prevent conflicts when joining the same table multiple times
     * or joining a table against itself
     *
     * @param string $table_name (optional)
     * @param bool $relatedJoin (optional)
     * @param bool isLink (optional)
     *
     * @return string
     */
    public function getJoinTableAlias($table_name = "", $relatedJoin = false, $isLink = true)
    {
        $table_name = $relatedJoin ? $relatedJoin . '_' . $table_name : $table_name;
        if ($alias = $this->getJoinAlias($table_name, $isLink)) {
            return $alias;
        }

        $alias = "jt" . $this->jt_index++;
        if (!empty($table_name)) {
            $alias .= "_" . $table_name;
        }

        return $this->db->getValidDBName($alias, true, 'alias');
    }

    /**
     * Add a join based on a link from the target bean
     *
     * @param SugarBean $bean
     * @param string $link_name
     * @param array $options
     *
     * @return SugarQuery
     */
    public function joinSubpanel($bean, $link_name, $options = array())
    {
        //Force a unique join table alias for self referencing relationships and multiple joins against the same table
        $alias = !empty($options['joinTableAlias']) ? $options['joinTableAlias'] : $this->getJoinTableAlias(
            $link_name
        );
        $joinType = (!empty($options['joinType'])) ? $options['joinType'] : 'INNER';
        $ignoreRole = (!empty($options['ignoreRole'])) ? $options['ignoreRole'] : false;

        if (!$bean->load_relationship($link_name)) {
            throw new SugarApiExceptionInvalidParameter("Unable to load link $link_name");
        }

        $joinParams = array(
            'joinTableAlias' => $alias,
            'joinType' => $joinType,
            'ignoreRole' => $ignoreRole,
            'reverse' => true,
            'includeCustom' => true,
        );
        if (!empty($options['myAlias'])) {
            $joinParams['myAlias'] = $options['myAlias'];
        }

        $bean->$link_name->buildJoinSugarQuery($this, $joinParams);

        $this->join[$alias]->addLinkName($link_name);
        $this->join[$alias]->on()->equals($alias . '.id', $bean->id);
        $this->links[$link_name] = $this->join[$alias];

        return $this->join[$alias];
    }


    /**
     * Returns the correct join_key for the relationship that is being used in JoinOn, if no field is found
     * NULL is returned.
     *
     * @param string $linkName
     * @return null|string
     */
    protected function getJoinOnField($linkName) {
        $bean = $this->from;

        if (is_array($bean)) {
            // the bean is the first element of the array
            $bean = reset($bean);
        }

        $field = null;

        if ($bean->load_relationship($linkName)) {
            $rel_def = $bean->$linkName->getRelationshipObject()->def;
            if ($bean->$linkName->getSide() == REL_LHS) {
                $join_key = 'join_key_rhs';
            } else {
                $join_key = 'join_key_lhs';
            }

            if (!empty($rel_def[$join_key])) {
                $field = $rel_def[$join_key];
            }
        }

        return $field;
    }

    /**
     * Add a join-on if there is an rname_link in use
     *
     * @param array $options
     * @throws SugarQueryException
     */
    public function setJoinOn($options = array())
    {
        if ($this->rname_link !== false && !empty($options['baseBeanId'])) {
            // get the field name from the relationship instead of just assuming it's something
            $field = $this->getJoinOnField($this->join[$this->rname_link]->linkName);
            if ($field) {
                $targetTableJoin = $this->join[$this->rname_link];
                $relationshipTableAlias = $targetTableJoin->relationshipTableAlias;
                $relateTableJoinKey = $this->joinTableToKey[$relationshipTableAlias];
                $this->join[$relateTableJoinKey]->on()->equals(
                    $relationshipTableAlias . '.' . $field,
                    $options['baseBeanId']
                );
            } else {
                throw new SugarQueryException('Relationship Field Not Found');
            }
        }
    }

    /**
     * If group by is not empty, then add rest of fields in select statement
     */
    public function ensureGroupByFields()
    {
        // check if short list of fields in GROUP BY is supported
        if ($this->db->supports('short_group_by')) {
            return;
        }

        //make sure 'group by' is not empty, if 'group by' is empty then we don't need to modify 'group by'
        if (!empty($this->group_by)) {
            $groupByCols = array();
            //grab the defined cols so we don't add them twice
            foreach ($this->group_by AS $groupBy) {
                $groupByCols[$groupBy->column->table . '.' . $groupBy->column->field] = $groupBy->column->field;
            }
            //make sure all the fields in the select statement are in the group by
            foreach ($this->select->select as $selectFieldKey => $selectField) {
                //add cols not already defined
                if (empty($groupByCols[$selectField->table . '.' . $selectField->field])) {
                    //if field class is raw, then we need to do some special processing
                    if (get_class($selectField) == 'SugarQuery_Builder_Field_Raw') {
                        //check to see if this is a concatenated field:
                        if (!empty($selectField->alias)
                            && !empty($this->from->field_defs[$selectField->alias]['db_concat_fields'])) {
                            //we need to get the concatenated fields
                            $concatFields = $this->from->field_defs[$selectField->alias]['db_concat_fields'];
                            //check to see if join exists, otherwise use table name
                            $table = $this->from->field_defs[$selectField->alias]['table'];
                            $linkName = $this->from->field_defs[$selectField->alias]['link'];
                            //check for join name only if we have a table and link name
                            if (!empty($table) && !empty($linkName)) {
                                foreach ($this->join as $joinName => $joinDef) {
                                    if ($joinDef->table == $table && $joinDef->linkName == $linkName) {
                                        //table and link match this join, use the join name in the 'group by'
                                        $table = $joinName;
                                        break;
                                    }
                                }
                            }
                            //add each concat field to the group by
                            foreach ($concatFields as $fieldToAdd) {
                                if (empty($fieldToAdd) || !is_string($fieldToAdd)) {
                                    //skip empty or array fields (should never happen unless metadata is bad)
                                    continue;
                                }
                                $this->groupBy($table . '.' . $fieldToAdd);
                            }
                        } else {
                            //not a concatenated field, so let's parse the string and attempt to grab the table and field name
                            $fieldToAdd = $selectField->field;
                            $fieldStringArray = explode(' ', $fieldToAdd);

                            foreach ($fieldStringArray as $fieldStr) {
                                if (strpos($fieldStr, '.' ) !== false) {
                                    $fieldToAdd = $fieldStr;
                                    break;
                                }
                            }

                            //add either the newly saved table and field name or the entire string
                            $this->groupBy($fieldToAdd);
                        }
                    } else {
                        $type = $this->db->getFieldType($selectField->def);
                        if ($type && $this->db->isTextType($type)) {
                            $castedField = $this->db->convert(
                                $selectField->table . '.' . $selectField->field,
                                'text2char'
                            );
                            $this->groupByRaw($castedField);
                            $selectField->addToSelectRaw($castedField, $selectFieldKey);
                            continue;
                        }
                        //Field class is not of type raw, add the table and field name from the selectField array
                        $this->groupBy($selectField->table . '.' . $selectField->field);
                    }
                } //end if (empty($groupByCols[$selectField->field]))
            } //end foreach ($this->select->select as $selectField)
        }//end if(!empty($this->group_by)){
    }

    /**
     * Converts SugarQuery into Doctrine DBAL query
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function compile()
    {
        $compiler = new SugarQuery_Compiler_Doctrine($this->db);
        return $compiler->compile($this);
    }

    /**
     * Execute this query and return the resulting data set as aarray
     *
     * @return array
     */
    public function execute()
    {
        $result = array();
        $stmt = $this->runQuery();
        while ($row = $stmt->fetch()) {
            //Apply any post data cleanup/db abstraction
            $result[] = $this->formatRow($row);
        }
        return $result;
    }

    /**
     * Get one value result from the query
     * @return false|string
     */
    public function getOne()
    {
       if(empty($this->limit)) {
           $this->offset(0)->limit(1);
       }

        $stmt = $this->runQuery();
        $result = $stmt->fetchColumn();
        $stmt->closeCursor();

        return $result;
    }

    /**
     * Run the query and return the db result object
     *
     * @return Doctrine\DBAL\Statement
     */
    protected function runQuery()
    {
        return $this->compile()->execute();
    }

    /**
     * Applies any cleanup or formatting required on the raw DB data result.
     *
     * @param array $row
     *
     * @return array updated $row
     */
    protected function formatRow(array $row)
    {
        //remap long aliases to thier correct output key
        foreach ($this->columnAliasMap as $orignalAlias => $compactAlias) {
            if (array_key_exists($compactAlias, $row)) {
                $row[$orignalAlias] = $row[$compactAlias];
                unset($row[$compactAlias]);
            }
        }

        return $row;
    }


    /**
     * This will eventually determine the type of query [select, update, delete, insert] and return the specific type
     * @return string
     */
    public static function getType()
    {
        return 'select';
    }

    /**
     * Set this Query as Distinct
     *
     * @param bool $value
     *
     * @return SugarQuery
     */
    public function distinct($value)
    {
        $this->distinct = (bool)$value;
        return $this;
    }


    /**
     * Set the offset of this query
     *
     * @param int $number
     *
     * @return SugarQuery
     */
    public function offset($number)
    {
        $this->offset = $number;

        return $this;
    }

    /**
     * Add a group by statement to this query
     *
     * @param array $array
     *
     * @return SugarQuery
     */
    public function groupBy($column)
    {
        $groupBy = new SugarQuery_Builder_Groupby($this);
        $groupBy->addField($column);
        $this->group_by[] = $groupBy;
        return $this;
    }

    public function groupByRaw($expression)
    {
        $groupBy = new SugarQuery_Builder_Groupby($this);
        $groupBy->addRaw($expression);
        $this->group_by[] = $groupBy;
        return $this;
    }

    /**
     * Add a having statement to this query
     *
     * @param array $conditions
     *
     * @return SugarQuery
     * @throws SugarQueryException
     */
    public function having($conditions)
    {
        if (isset($this->having)) {
            if (!$this->having instanceof SugarQuery_Builder_Andwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level HAVING operator from %s to %s',
                    $this->where->operator(),
                    'AND'
                ));
            }
        } else {
            $this->having = new SugarQuery_Builder_Andwhere($this);
        }

        if (!empty($conditions)) {
            $this->having->add($conditions);
        }

        return $this->having;
    }

    /**
     * Add a having statement to this query
     *
     * @param array $conditions
     *
     * @return SugarQuery
     * @throws SugarQueryException
     */
    public function orHaving($conditions)
    {
        if (isset($this->having)) {
            if (!$this->having instanceof SugarQuery_Builder_Orwhere) {
                throw new SugarQueryException(sprintf(
                    'Cannot change the top level HAVING operator from %s to %s',
                    $this->where->operator(),
                    'OR'
                ));
            }
        } else {
            $this->having = new SugarQuery_Builder_Orwhere($this);
        }

        if (!empty($conditions)) {
            $this->having->add($conditions);
        }

        return $this->having;
    }

    public function havingRaw($expression)
    {
        $having = new SugarQuery_Builder_Andwhere($this);
        $having->addRaw($expression);
        $this->having = $having;
        return $this->having;
    }

    /**
     * Add an order by statement for this query
     *
     * @param string $column
     * @param string $direction
     *
     * @return SugarQuery
     */
    public function orderBy($column, $direction = 'DESC')
    {
        $orderBy = new SugarQuery_Builder_Orderby($this, $direction);
        $orderBy->addField($column);
        $this->order_by[] = $orderBy;

        return $this;
    }


    /**
     * Add an order by raw expression for this query
     *
     * @param string $expression Raw expression to sort.
     * @param string $direction Values ASC or DESC.
     *
     * @return SugarQuery
     */
    public function orderByRaw($expression, $direction = 'DESC')
    {
        $orderBy = new SugarQuery_Builder_Orderby($this, $direction);
        $orderBy->addRaw($expression);
        $this->order_by[] = $orderBy;
        return $this;
    }

    /**
     * Reset ORDER BY of query
     *
     * @return SugarQuery
     */
    public function orderByReset()
    {
        $this->order_by = array();
        return $this;
    }

    /**
     * Set the limit of this query
     *
     * @param int $number
     *
     * @return SugarQuery
     */
    public function limit($number)
    {
        $this->limit = $number;

        return $this;
    }

    /**
     * After a from is changed rebuild all the fields to check the vardefs
     */
    protected function rebuildFields()
    {
        if (!empty($this->select)) {
            foreach ($this->select->select as $field) {
                if ($field instanceof SugarQuery_Builder_Field) {
                    $field->setupField($this);
                }
            }
        }

        if (!empty($this->join)) {
            foreach ($this->join as $joinObj) {
                if (!empty($joinObj->on)) {
                    foreach ($joinObj->on as $whereObj) {
                        if (empty($whereObj->conditions)) {
                            continue;
                        }
                        foreach ($whereObj->conditions as $conditionObj) {
                            if ($conditionObj->field instanceof SugarQuery_Builder_Field) {
                                $conditionObj->field->setupField($this);
                            }
                        }
                    }
                }
            }
        }

        if (!empty($this->where)) {
            foreach ($this->where as $whereObj) {
                if (empty($whereObj->conditions)) {
                    continue;
                }
                foreach ($whereObj->conditions as $conditionObj) {
                    if ($conditionObj->field instanceof SugarQuery_Builder_Field) {
                        $conditionObj->field->setupField($this);
                    }
                }
            }
        }

        if (!empty($this->order_by)) {
            foreach ($this->order_by as $orderObj) {
                if ($orderObj->column instanceof SugarQuery_Builder_Field) {
                    $orderObj->column->setupField($this);
                }
            }
        }

        if (!empty($this->group_by)) {
            foreach ($this->group_by as $groupByObj) {
                if ($groupByObj->column instanceof SugarQuery_Builder_Field) {
                    $groupByObj->column->setupField($this);
                }
            }
        }

        if (!empty($this->having)) {
            foreach ($this->having as $whereObj) {
                if (empty($whereObj->conditions)) {
                    continue;
                }
                foreach ($whereObj->conditions as $conditionObj) {
                    if ($conditionObj->field instanceof SugarQuery_Builder_Field) {
                        $conditionObj->field->setupField($this);
                    }
                }
            }
        }
    }

    /**
     * Load Beans uses Link2 to take a SugarQuery object and add the joins needed to take a link and make the connection
     *
     * @param Linkname $join
     * @param $alias
     */
    protected function loadBeans($join, $options)
    {
        $alias = (!empty($options['alias'])) ? $options['alias'] : $this->getJoinTableAlias($join);
        $joinType = (!empty($options['joinType'])) ? $options['joinType'] : 'INNER';
        $team_security = (isset($options['team_security'])) ? $options['team_security'] : true;
        $ignoreRole = (!empty($options['ignoreRole'])) ? $options['ignoreRole'] : false;

        $bean = !empty($options['relatedJoin']) ? $this->join[$options['relatedJoin']]->bean : $this->from;

        if (is_array($bean)) {
            // the bean is the first element of the array
            $bean = reset($bean);
        }

        $bean->load_relationship($join);
        if (empty($bean->$join)) {
            throw new SugarApiExceptionInvalidParameter("Invalid link $join");
        }

        $bean->$join->buildJoinSugarQuery(
            $this,
            array(
                'joinTableAlias' => $alias,
                'joinType' => $joinType,
                'ignoreRole' => $ignoreRole,
            )
        );
        $joined = BeanFactory::getDefinition($bean->$join->getRelatedModuleName());
        if ($team_security === true) {
            $options['table_alias'] = $alias;
            $options['as_condition'] = true;
            $joined->addVisibilityQuery($this, $options);
        }

        if ($joined->hasCustomFields()) {
            $table_cstm = $joined->get_custom_table_name();
            $alias_cstm = $this->db->getValidDBName($alias . '_cstm', false, 'alias');
            $this->joinTable($table_cstm, array('alias' => $alias_cstm, 'joinType' => "LEFT", "linkingTable" => true))
                ->on()->equalsField("$alias_cstm.id_c", "{$alias}.id");
        }

    }

    /**
     * Set/get parent field for the query
     * @param array|null $has
     * @return array
     */
    public function hasParent($has = null)
    {
        if ($has !== null) {
            $this->has_parent = $has;
        }
        return $this->has_parent;
    }

    /**
     * Get bean that corresponds to this table name
     *
     * @param string $table_name
     *
     * @return SugarBean
     */
    public function getTableBean($table_name)
    {
        if (substr($table_name, -5) == '_cstm') {
            // if we've got _cstm name, it's the same bean as non-custom one
            $table_name = substr($table_name, 0, -5);
        }
        if (!isset($this->table_beans[$table_name])) {
            if (empty($this->join[$table_name])) {
                return null;
            }
            $link_name = $this->join[$table_name]->linkName;
            if ($link_name == 'favorites') {
                // FIXME: special case, should eliminate it
                $bean = BeanFactory::getDefinition('SugarFavorites');
            } elseif ($link_name == 'tracker') {
                $bean = BeanFactory::getDefinition('Trackers');
            } else {
                $bean = $this->join[$table_name]->bean;
            }

            $this->table_beans[$table_name] = $bean;
        }
        return $this->table_beans[$table_name];
    }

    public function getTableMetadata($alias)
    {
        global $dictionary;

        if (!isset($this->join[$alias])) {
            return array();
        }

        $table = $this->join[$alias]->table;
        if (!is_string($table) || !isset($dictionary[$table])) {
            return array();
        }

        return $dictionary[$table];
    }

    public function getJoinAlias($name, $isLink = true)
    {
        if ($isLink) {
            return isset($this->joinLinkToKey[$name]) ? $this->joinLinkToKey[$name] : false;
        }
        if (isset($this->joinLinkToKey[$name])) {
            return $this->joinLinkToKey[$name];
        } elseif (isset($this->joinTableToKey[$name])) {
            return $this->joinTableToKey[$name];
        }
        return false;
    }

    /**
     * Returns the SugarBean Object that is the subject of this query.
     * @return null|SugarBean
     */
    public function getFromBean()
    {
        if (is_array($this->from)) {
            return $this->from[0];
        }

        return $this->from;
    }

    /**
     * Returns the alias of the from bean, or the bean table if no alias exists
     * @return String
     */
    public function getFromAlias()
    {
        if (is_array($this->from)) {
            return $this->from[1];
        }
        return $this->from->getTableName();
    }

    /**
     * @param $link_name name of link field to check for an existing join against.
     *
     * @return null|SugarQuery_Builder_Join
     */
    public function getJoinForLink($link_name)
    {
        if (!empty($this->links[$link_name])) {
            return $this->links[$link_name];
        }

        return null;
    }

    /**
     * Joins the custom table to the current query (if possible)
     * @param SugarBean $bean
     * @param string $alias
     */
    public function joinCustomTable($bean, $alias = "") {
        if ($bean->hasCustomFields() && !$this->customJoined) {
            $table = $bean->getTableName();
            $table_cstm = $bean->get_custom_table_name();
            if (!empty($table_cstm)) {
                $options = array(
                    'joinType' => 'left',
                );
                $joinAlias = $this->getCustomTableAlias($bean, $alias);
                if (!empty($alias)) {
                    $fromAlias = $alias;
                    $options['alias'] = $joinAlias;
                } else {
                    $fromAlias = $table;
                }
                $this->joinTable($table_cstm, $options)
                    ->on()->equalsField($joinAlias . '.id_c', $fromAlias . '.id');
            }
        }
    }

    /**
     * Returns whether the query should skip deleted records
     *
     * @return mixed
     */
    public function shouldSkipDeletedRecords()
    {
        return $this->shouldSkipDeletedRecords;
    }

    /**
     * Returns whether the query should fetch erased fields for selected records
     *
     * @return mixed
     */
    public function shouldFetchErasedFields()
    {
        return $this->shouldFetchErasedFields;
    }

    /**
     * Returns a SQL-valid version of a given column alias and registers a mapping between the two
     *
     * @param string $alias Original column alias
     * @return string
     */
    public function getValidColumnAlias(string $alias) : string
    {
        $validAlias = $this->db->getValidDBName($alias, true);

        if (strcasecmp($alias, $validAlias) == 0) {
            return $alias;
        }

        $this->columnAliasMap[$alias] = $validAlias;

        return $validAlias;
    }

    /**
     * @param SugarBean $bean
     * @param null|string $alias
     * @return string
     */
    public function getCustomTableAlias(SugarBean $bean, ?string $alias) : string
    {
        if (!empty($alias)) {
            return $alias . '_cstm';
        }
        return $bean->get_custom_table_name();
    }
}
