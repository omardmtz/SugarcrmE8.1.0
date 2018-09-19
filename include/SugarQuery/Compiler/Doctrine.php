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

use Sugarcrm\Sugarcrm\Dbal\Query\QueryBuilder;
use Doctrine\DBAL\Platforms\SQLServerPlatform;

/**
 * Compiler of SugarQuery to Doctrine query builder
 */
class SugarQuery_Compiler_Doctrine
{
    /**
     * @var DBManager
     */
    protected $db;

    /**
     * Constructor
     *
     * @param DBManager $db Database connection
     */
    public function __construct(DBManager $db)
    {
        $this->db = $db;
    }

    /**
     * Build out the Query in SQL
     *
     * @param SugarQuery $query The query being compiled
     *
     * @return QueryBuilder
     * @throws SugarQueryException
     */
    public function compile(SugarQuery $query)
    {
        $builder = $this->db->getConnection()
            ->createQueryBuilder();

        if ($query->union instanceof SugarQuery_Builder_Union) {
            return $this->compileUnionQuery($builder, $query);
        }

        return $this->compileSelectQuery($builder, $query);
    }

    /**
     * Build out the Query in SQL
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     * @return QueryBuilder
     * @throws SugarQueryException
     */
    protected function compileUnionQuery(QueryBuilder $builder, SugarQuery $query)
    {
        $unions = $query->union->getQueries();

        $sql = '';
        foreach ($unions as $i => $union) {
            if ($i > 0) {
                $sql .= ' UNION ';
                if ($union['all']) {
                    $sql .= 'ALL ';
                }
            }

            $sql .= $this->compileSubQuery($builder, $union['query']);
        }

        $hasLimit = $query->limit !== null || $query->offset !== null;
        $hasOrderBy = count($query->order_by) > 0;
        $platform = $this->db->getConnection()->getDatabasePlatform();

        // in case of a UNION query with LIMIT and ORDER BY, wrap the UNIONs in a sub-query
        // in order to let Doctrine DBAL apply the LIMIT
        // @link https://github.com/doctrine/dbal/issues/2374
        if ($hasLimit && $hasOrderBy && $platform instanceof SQLServerPlatform) {
            $sql = 'SELECT * FROM (' . $sql . ') union_tmp';
        }

        $this->compileOrderBy($builder, $query, false);

        // combine manually built SELECT with the ORDER BY built by builder
        $sql = str_replace('SELECT ', $sql, $builder->getSQL());

        // manually apply LIMIT to the resulting SQL
        if ($hasLimit) {
            $sql = $platform->modifyLimitQuery($sql, $query->limit, $query->offset);
        }

        // below is a very dirty hack: Doctrine QueryBuilder doesn't support UNION's,
        // so we inject pre-built SQL into builder.
        // another dirty thing is that we're using our own wrapper for QueryBuilder,
        // so we use parent class reflection here in order to set private properties of
        // the parent class
        $re = new ReflectionProperty(get_parent_class($builder), 'sql');
        $re->setAccessible(true);
        $re->setValue($builder, $sql);

        $re = new ReflectionProperty(get_parent_class($builder), 'state');
        $re->setAccessible(true);
        $re->setValue($builder, QueryBuilder::STATE_CLEAN);

        return $builder;
    }

    /**
     * Build out the Query in SQL
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     * @return QueryBuilder
     * @throws SugarQueryException
     */
    protected function compileSelectQuery(QueryBuilder $builder, SugarQuery $query)
    {
        $query->ensureGroupByFields();
        $this->compileSelect($builder, $query);
        $this->compileFrom($builder, $query);
        $this->compileJoins($builder, $query);
        $this->compileWhere($builder, $query);
        $this->compileGroupBy($builder, $query);
        $this->compileHaving($builder, $query);
        $this->compileOrderBy($builder, $query, $query->getOrderByStability());
        $this->compileLimit($builder, $query);

        return $builder;
    }

    /**
     * Create a select statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     */
    protected function compileSelect(QueryBuilder $builder, SugarQuery $query)
    {
        // if there aren't any selected fields, add them all
        if (empty($query->select->select) && $query->select->getCountQuery() === false) {
            $query->select('*');
        }

        $select = $query->select;

        $columns = array();

        foreach ($select->select as $field) {
            if ($field->isNonDb()) {
                continue;
            }

            $columns[] = $this->compileField($field);
            if ($select->getCountQuery()) {
                $query->groupBy("{$field->table}.{$field->field}");
            }
        }

        if ($query->distinct && count($columns) > 0) {
            $columns[0] = 'DISTINCT ' . $columns[0];
        }

        if ($select->getCountQuery()) {
            $columns[] = 'COUNT(0) AS record_count';
        }

        $builder->select($columns);
    }

    /**
     * Create a from statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     *
     * @throws SugarQueryException
     */
    protected function compileFrom(QueryBuilder $builder, SugarQuery $query)
    {
        $bean = $query->getFromBean();
        if (!$bean) {
            throw new SugarQueryException('The primary bean is not specified');
        }

        $alias = $query->getFromAlias();
        $table = $bean->getTableName();

        if ($alias == $table) {
            $alias = null;
        }

        $builder->from($table, $alias);

        // SugarQuery will determine if we actually need to add the table or not.
        $query->joinCustomTable($bean, $alias);

        if ($query->shouldFetchErasedFields()) {
            $this->joinErasedFields($builder, $query, $bean, $alias ?: $table, 'erased_fields');
        }
    }

    /**
     * Compile JOIN statements
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     *
     * @throws SugarQueryException
     */
    protected function compileJoins(QueryBuilder $builder, SugarQuery $query)
    {
        foreach ($query->join as $join) {
            $this->compileJoin($builder, $join);
        }
    }

    /**
     * Compile single JOIN expression
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery_Builder_Join $join Join specification
     *
     * @throws SugarQueryException
     */
    protected function compileJoin(QueryBuilder $builder, SugarQuery_Builder_Join $join)
    {
        if ($join->table instanceof SugarQuery
            || $join->table instanceof QueryBuilder
        ) {
            $table = '(' . $this->compileSubQuery($builder, $join->table) . ')';
        } else {
            $table = $join->table;
        }

        if ($join->on) {
            $condition = $this->compileExpression($builder, $join->on);
        } else {
            $condition = null;
        }

        $fromAlias = $join->query->getFromAlias();
        $alias = $join->joinName();

        switch (strtolower($join->options['joinType'])) {
            case 'left':
                $builder->leftJoin($fromAlias, $table, $alias, $condition);
                break;
            default:
                $builder->join($fromAlias, $table, $alias, $condition);
                break;
        }

        if ($join->bean && $join->query->shouldFetchErasedFields()) {
            $columnAlias = $join->query->getValidColumnAlias($join->linkName . '_erased_fields');
            $this->joinErasedFields($builder, $join->query, $join->bean, $alias, $columnAlias);
        }
    }

    /**
     * Compiles additional SELECTed fields and JOINed tables which represent erased bean fields
     *
     * @param QueryBuilder $builder
     * @param SugarQuery $query
     * @param SugarBean $bean The bean whose erased fields need to be retrieved
     * @param string $tableAlias The alias of the table which the erased fields need to be joined to
     * @param string $columnAlias The alias for the column containing the erased fields data
     */
    protected function joinErasedFields(
        QueryBuilder $builder,
        SugarQuery $query,
        SugarBean $bean,
        string $tableAlias,
        string $columnAlias
    ) {
        if (!$this->isPiiFieldsSelected($bean, $query, $tableAlias)) {
            return false;
        }

        $erasedAlias = $bean->db->getValidDBName($tableAlias . '_erased', true, 'alias');

        $builder->leftJoin(
            $query->getFromAlias(),
            'erased_fields',
            $erasedAlias,
            sprintf(
                '%1$s.bean_id = %2$s.id AND %1$s.table_name = ' . $builder->createPositionalParameter(
                    $bean->getTableName()
                ),
                $erasedAlias,
                $tableAlias
            )
        )->addSelect(sprintf('%s.data %s', $erasedAlias, $columnAlias));
    }

    /**
     * check if any Pii field is selected
     * @param SugarBean $bean
     * @param SugarQuery $query
     * @param string $tableAlias
     * @return bool
     */
    protected function isPiiFieldsSelected(SugarBean $bean, SugarQuery $query, string $tableAlias) : bool
    {
        if (!$bean->hasPiiFields()) {
            return false;
        }

        $selectedFields = $this->getSelectFieldsByTable($bean, $query, $tableAlias);

        if (!count($selectedFields)) {
            return false;
        }

        $piiFields = $bean->getFieldDefinitions('pii', [true]);

        if (!array_intersect($selectedFields, array_keys($piiFields))) {
            return false;
        }

        return true;
    }

    /**
     * get selected fields
     * @param SugarBean $bean
     * @param SugarQuery $query
     * @param string $tableAlias
     * @return array
     */
    protected function getSelectFieldsByTable(SugarBean $bean, SugarQuery $query, string $tableAlias) : array
    {
        return array_merge(
            $query->select->getSelectedFieldsByTable($tableAlias),
            $query->select->getSelectedFieldsByTable($query->getCustomTableAlias($bean, $tableAlias))
        );
    }

    /**
     * Compile WHERE statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     *
     * @throws SugarQueryException
     */
    protected function compileWhere(QueryBuilder $builder, SugarQuery $query)
    {
        if ($query->shouldSkipDeletedRecords()) {
            $where = new SugarQuery_Builder_Andwhere($query);
            if ($query->where) {
                $where->add($query->where);
            }
            $where->equals('deleted', 0);
        } else {
            $where = $query->where;
        }

        if ($where) {
            $builder->where(
                $this->compileExpression($builder, $where)
            );
        }
    }

    /**
     * Compile GROUP BY statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     */
    protected function compileGroupBy(QueryBuilder $builder, SugarQuery $query)
    {
        foreach ($query->group_by as $column) {
            if ($column->column->isNonDb()) {
                continue;
            }

            $builder->addGroupBy(
                $this->compileField($column->column)
            );
        }
    }

    /**
     * Compile HAVING statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     */
    protected function compileHaving(QueryBuilder $builder, SugarQuery $query)
    {
        if ($query->having) {
            $builder->having(
                $this->compileExpression($builder, $query->having)
            );
        }
    }

    /**
     * Compile ORDER BY statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     * @param bool $applyOrderStability Whether order stability should be applied the the SQL
     */
    protected function compileOrderBy(QueryBuilder $builder, SugarQuery $query, $applyOrderStability)
    {
        $orderBy = $query->order_by;
        if ($applyOrderStability && !$this->db->supports('order_stability')) {
            $orderBy = $this->applyOrderByStability($query, $orderBy);
        }

        foreach ($orderBy as $column) {
            if ($column->column->isNonDb()) {
                continue;
            }

            $builder->addOrderBy(
                $this->compileField($column->column),
                $column->direction
            );
        }
    }

    /**
     * Add additional column to `ORDER BY` clause for order stability, defaults
     * to using the `id` column.
     *
     * @param SugarQuery $query The query being compiled
     * @param SugarQuery_Builder_Orderby[] $orderBy List of already existing `ORDER BY` defs
     * @return SugarQuery_Builder_Orderby[]
     */
    protected function applyOrderByStability(SugarQuery $query, array $orderBy)
    {
        if (count($orderBy) == 0) {
            return $orderBy;
        }

        foreach ($orderBy as $column) {
            if ($column->column->field == 'id') {
                return $orderBy;
            }
        }

        $uniqueCol = new SugarQuery_Builder_Orderby($query, end($orderBy)->direction);
        $uniqueCol->addField('id');
        $orderBy[] = $uniqueCol;

        return $orderBy;
    }

    /**
     * Compile LIMIT statement
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery $query The query being compiled
     */
    protected function compileLimit(QueryBuilder $builder, SugarQuery $query)
    {
        if ($query->select->getCountQuery()) {
            return;
        }

        $builder->setFirstResult($query->offset);
        $builder->setMaxResults($query->limit);
    }

    /**
     * Compile field expression
     *
     * @param SugarQuery_Builder_Field $field Field specification
     * @return string
     */
    protected function compileField(SugarQuery_Builder_Field $field)
    {
        if ($field instanceof SugarQuery_Builder_Field_Raw) {
            $sql = $field->field;
        } else {
            if ($field->isNonDb()) {
                return '';
            }

            if ($field->table && !strstr($field->field, '.')) {
                $sql = $field->table . '.' . $field->field;
            } else {
                $sql = $field->field;
            }
        }

        if ($field->alias && strcmp($field->alias, $field->field) != 0) {
            $sql .= ' ' . $field->alias;
        }

        return  $sql;
    }

    /**
     * Build the Where Statement using arrays, to keep it nice and clean
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery_Builder_Where $expression
     * @return string|\Doctrine\DBAL\Query\Expression\CompositeExpression
     */
    protected function compileExpression(QueryBuilder $builder, SugarQuery_Builder_Where $expression)
    {
        $expressions = array();

        if (!empty($expression->raw)) {
            $compiledField = $this->compileField($expression->raw);
            if (!empty($compiledField)) {
                $expressions[] = $compiledField;
            }
        }

        foreach ($expression->conditions as $condition) {
            if ($condition instanceof SugarQuery_Builder_Where) {
                $compiledField = $this->compileExpression($builder, $condition);
                if (count($compiledField) > 0) {
                    $expressions[] = $compiledField;
                }
            } elseif ($condition instanceof SugarQuery_Builder_Condition) {
                $compiledField = $this->compileCondition($builder, $condition);
                if (!empty($compiledField)) {
                    $expressions[] = $compiledField;
                }
            }
        }

        if (count($expressions) == 1) {
            return current($expressions);
        }

        $method = strtolower($expression->operator()) . 'X';
        return call_user_func_array(array($builder->expr(), $method), $expressions);
    }

    /**
     * Compile condition
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery_Builder_Condition $condition Condition
     * @return string|null
     *
     * @throws SugarQueryException
     */
    protected function compileCondition(QueryBuilder $builder, SugarQuery_Builder_Condition $condition)
    {
        global $current_user;

        $field = $this->compileField($condition->field);

        if (empty($field)) {
            return null;
        }

        if (!empty($condition->field->def['type']) && $this->db->isTextType($condition->field->def['type'])) {
            $castField = $this->db->convert($field, 'text2char');
        } else {
            $castField = $field;
        }

        $expr = $builder->expr();

        if ($condition->isNull) {
            $sql = $expr->isNull($field);
        } elseif ($condition->notNull) {
            $sql = $expr->isNotNull($field);
        } else {
            $fieldDef = $condition->field->def;
            switch ($condition->operator) {
                case 'IN':
                case 'NOT IN':
                    $sql =  $this->compileIn($builder, $castField, $condition->operator, $condition->values, $fieldDef);
                    break;
                case 'BETWEEN':
                    $min = $this->bindValue($builder, $condition->values['min'], $fieldDef);
                    $max = $this->bindValue($builder, $condition->values['max'], $fieldDef);
                    $sql = "{$field} BETWEEN {$min} AND {$max}";
                    break;
                case 'STARTS':
                case 'DOES NOT START':
                case 'CONTAINS':
                case 'DOES NOT CONTAIN':
                case 'ENDS':
                case 'DOES NOT END':
                case 'LIKE':
                case 'NOT LIKE':
                    $sql = $this->compileLike(
                        $builder,
                        $field,
                        $condition->operator,
                        $condition->values,
                        $fieldDef
                    );
                    break;
                default:
                    $sql = $castField . ' ' . $condition->operator . ' ';
                    if ($condition->values instanceof SugarQuery
                        || $condition->values instanceof QueryBuilder) {
                        $sql .= '(' . $this->compileSubQuery($builder, $condition->values) . ')';
                    } elseif ($condition->values instanceof SugarQuery_Builder_Field) {
                        $sql .= $this->compileField($condition->values);
                    } else {
                        $sql .= $this->bindValue($builder, $condition->values, $fieldDef);
                    }
                    break;
            }
        }

        if (!$condition->isAclIgnored()) {
            $isFieldAccessible = ACLField::generateAclCondition($condition, $current_user);
            if ($isFieldAccessible) {
                $sql = '(' . $sql . ' AND (' . $this->compileExpression($builder, $isFieldAccessible) . '))';
            }
        }

        return $sql;
    }

    /**
     * Compile (NOT)? IN expression
     *
     * @param QueryBuilder $builder Query builder
     * @param string $field Field expression
     * @param string $operator Internal SugarQuery operator
     * @param SugarQuery|QueryBuilder|array|string $set
     * @param array $fieldDef Field definition
     * @return string
     *
     * @throws SugarQueryException
     */
    protected function compileIn(QueryBuilder $builder, $field, $operator, $set, array $fieldDef)
    {
        $sql = $field . ' ' . $operator . ' (' . $this->compileSet($builder, $set, $fieldDef) . ')';

        $isNegative = strpos($operator, 'NOT') !== false;
        if ($isNegative) {
            $sql = $this->isNullOr($field, $sql);
        }

        return $sql;
    }

    /**
     * Compile set of values
     *
     * @param QueryBuilder $builder Query builder
     * @param SugarQuery|QueryBuilder|array|string $set
     * @param array $fieldDef Field definition
     * @return string
     *
     * @throws SugarQueryException
     */
    protected function compileSet(QueryBuilder $builder, $set, array $fieldDef)
    {
        if ($set instanceof SugarQuery || $set instanceof QueryBuilder) {
            return $this->compileSubQuery($builder, $set);
        }

        if (empty($set)) {
            return 'NULL';
        }

        $values = array();
        foreach ($set as $value) {
            $values[] = $this->bindValue($builder, $value, $fieldDef);
        }

        return implode(',', $values);
    }

    /**
     * Compile subquery and return it as SQL
     *
     * @param QueryBuilder $builder Primary query builder
     * @param SugarQuery|QueryBuilder $subQuery Subquery
     * @return string
     * @throws SugarQueryException
     */
    protected function compileSubQuery(QueryBuilder $builder, $subQuery)
    {
        if ($subQuery instanceof SugarQuery) {
            $subQuery = $this->compile($subQuery);
        }

        return $builder->importSubQuery($subQuery);
    }

    /**
     * Compile "contains" expression
     *
     * @param QueryBuilder $builder Query builder
     * @param string $field Field expression
     * @param string $operator Internal SugarQuery operator
     * @param string|array $values Value or values to look for
     * @param array $fieldDef Field definition
     * @return string
     */
    protected function compileLike(QueryBuilder $builder, $field, $operator, $values, array $fieldDef)
    {
        $format = null;
        $isPattern = false;
        switch ($operator) {
            case 'STARTS':
            case 'DOES NOT START':
                $format = '%s%%';
                break;
            case 'CONTAINS':
            case 'DOES NOT CONTAIN':
                $format = '%%%s%%';
                break;
            case 'ENDS':
            case 'DOES NOT END':
                $format = '%%%s';
                break;
            case 'LIKE':
            case 'NOT LIKE':
                $isPattern = true;
                break;
        }

        $isNegation = strpos($operator, 'NOT') !== false;
        if ($isNegation) {
            $chainWith = 'AND';
        } else {
            $chainWith = 'OR';
        }

        if (!is_array($values)) {
            $values = array($values);
        }

        if (!$this->isCollationCaseSensitive()) {
            $expr = $field;
        } else {
            $expr = 'UPPER(' . $field . ')';
            $values = array_map('strtoupper', $values);
        }

        $conditions = array();
        foreach ($values as $value) {
            $condition = $expr . ($isNegation ? ' NOT' : '') . ' LIKE ';

            if (!$isPattern) {
                $condition .= $this->compileSubstringPattern($builder, $format, $value, $fieldDef);
            } else {
                $condition .= $this->bindValue($builder, $value, $fieldDef);
            }

            $conditions[] = $condition;
        }

        $sql = implode(' ' . $chainWith . ' ', $conditions);

        if ($isNegation) {
            if (count($conditions) > 0) {
                $sql = '(' . $sql . ')';
            }
            $sql = $this->isNullOr($field, $sql);
        }

        return $sql;
    }

    /**
     * Compiles substring expression
     *
     * @param QueryBuilder $builder Query builder
     * @param string $format Wildcard placement format
     * @param string $substring Value to compare with
     * @param array $fieldDef Field definition
     * @return string
     */
    protected function compileSubstringPattern(
        QueryBuilder $builder,
        $format,
        $substring,
        array $fieldDef
    ) {
        $esc = '!';
        // temporarily disable escaping of wildcards in order to support their usage in starts(), ends() and contains()
        // and avoid backward compatibility breakage
        $shouldEscape = /*strpbrk($substring, '%_') !==*/ false;
        if ($shouldEscape) {
            $pattern = str_replace(
                array($esc,        '_',        '%'),
                array($esc . $esc, $esc . '_', $esc . '%'),
                $substring
            );
        } else {
            $pattern = $substring;
        }

        $value = sprintf($format, $pattern);
        $sql = $this->bindValue($builder, $value, $fieldDef);

        if ($shouldEscape) {
            $sql .= ' ESCAPE \'' . $esc . '\'';
        }

        return $sql;
    }

    protected function isNullOr($field, $sql)
    {
        return $field . ' IS NULL OR ' . $sql;
    }

    /**
     * Binds value to the query and returns the query fragment representing the placeholder
     *
     * @param QueryBuilder $builder Query builder
     * @param mixed $value The value to be bound
     * @param array $fieldDef Field definition
     * @return string
     */
    protected function bindValue(QueryBuilder $builder, $value, array $fieldDef)
    {
        if ($value === null || $value === false || $value === '') {
            $value = $this->db->emptyValue(
                $this->db->getFieldType($fieldDef),
                true
            );
        }

        return $this->db->bindValue($builder, $value, $fieldDef);
    }

    /**
     * Checks whether the DB collation is case sensitive, assuming all tables and fields use the same collation
     *
     * @return boolean
     */
    protected function isCollationCaseSensitive()
    {
        return $this->db->supports('case_insensitive');
    }
}
