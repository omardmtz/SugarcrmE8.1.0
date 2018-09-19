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

use Doctrine\DBAL\Query\QueryBuilder;

global $dictionary;
//Load all relationship metadata
include_once("modules/TableDictionary.php");


define('REL_LHS', 'LHS');
define('REL_RHS', 'RHS');
define('REL_BOTH', 'BOTH_SIDES');
define('REL_MANY_MANY', 'many-to-many');
define('REL_ONE_MANY', 'one-to-many');
define('REL_ONE_ONE', 'one-to-one');
define('REL_TYPE_ONE', 'one');
define('REL_TYPE_MANY', 'many');
define('REL_TYPE_UNDEFINED', 'undefined');

/**
 * A relationship is between two modules.
 * It contains at least two links.
 * Each link represents a connection from one record to the records linked in this relationship.
 * Links have a context(focus) bean while relationships do not.
 * @api
 */
abstract class SugarRelationship
{
    protected $def;
    protected $lhsLink;
    protected $rhsLink;
    protected $ignore_role_filter = false;
    protected $self_referencing = false; //A relationship is self referencing when LHS module = RHS Module

    /**
     * Bean resave queue
     *
     * @var SugarBean[]
     */
    protected static $resaveQueue = array();

    /**
     * Index of bean resave queue
     *
     * @var array
     */
    protected static $resaveIndex = array();

    public abstract function add($lhs, $rhs, $additionalFields = array());

    /**
     * @abstract
     *
     * @param  $lhs SugarBean
     * @param  $rhs SugarBean
     *
     * @return boolean
     */
    public abstract function remove($lhs, $rhs);

    /**
     * Return type of relationship based on side.
     *
     * @abstract
     * @param string $side Relationship side.
     * return string Type of current relationship.
     */
    abstract public function getType($side);

    /**
     * @abstract
     *
     * @param $link Link2 loads the rows for this relationship that match the given link
     *
     * @return void
     */
    public abstract function load($link, $params = array());

    /**
     * Gets the query to load a link.
     * This is currently public, but should prob be made protected later.
     * See Link2->getQuery
     *
     * @deprecated Use SugarRelationship::load() instead
     * @abstract
     *
     * @param  $link Link Object to get query for.
     *
     * @return string|array query used to load this relationship
     */
    public abstract function getQuery($link, $params = array());

    /**
     * @abstract
     *
     * @param Link2 $link
     *
     * @return string|array the query to join against the related modules table for the given link.
     */
    public abstract function getJoin($link);

    /**
     * @abstract
     * @internal
     *
     * @param Link2 $link
     * @param SugarQuery $sugar_query
     *
     * @return SugarQuery
     */
    public abstract function buildJoinSugarQuery(
        Link2 $link,
        $sugar_query,
        $options
    );

    /**
     * @abstract
     *
     * @param SugarBean $lhs
     * @param SugarBean $rhs
     *
     * @return bool
     */
    public abstract function relationship_exists($lhs, $rhs);

    /**
     * @abstract
     * @return string name of the table for this relationship
     */
    public abstract function getRelationshipTable();

    /**
     * @param  $link Link2 removes all the beans associated with this link from the relationship
     *
     * @return boolean     true if all beans were successfully removed or there
     *                     were not related beans, false otherwise
     */
    public function removeAll($link)
    {
        $focus = $link->getFocus();
        $related = $link->getBeans();
        $result = true;
        foreach ($related as $relBean) {
            if (empty($relBean->id)) {
                continue;
            }

            if ($link->getSide() == REL_LHS) {
                $sub_result = $this->remove($focus, $relBean);
            } else {
                $sub_result = $this->remove($relBean, $focus);
            }

            $result = $result && $sub_result;
        }

        return $result;
    }

    /**
     * @param $rowID id of SugarBean to remove from the relationship
     *
     * @return void
     */
    public function removeById($rowID)
    {
        $this->removeRow(array("id" => $rowID));
    }

    /**
     * @return string name of right hand side module.
     */
    public function getRHSModule()
    {
        return $this->def['rhs_module'];
    }

    /**
     * @return string name of left hand side module.
     */
    public function getLHSModule()
    {
        return $this->def['lhs_module'];
    }

    /**
     * @return String left link in relationship.
     */
    public function getLHSLink()
    {
        return $this->lhsLink;
    }

    /**
     * @return String right link in relationship.
     */
    public function getRHSLink()
    {
        return $this->rhsLink;
    }

    /**
     * @return array names of fields stored on the relationship
     */
    public function getFields()
    {
        return isset($this->def['fields']) ? $this->def['fields'] : array();
    }

    /**
     * @param array $row values to be inserted into the relationship
     *
     * @return int The number of affected rows
     */
    protected function addRow(array $row)
    {
        $existing = $this->checkExisting($row);
        if (!empty($existing)) //Update the existing row, overriding the values with those passed in
        {
            return $this->updateRow(
                $existing['id'],
                array_merge($existing, $row)
            );
        }

        DBManagerFactory::getInstance()->insertParams(
            $this->getRelationshipTable(),
            $this->getFields(),
            $row
        );

        return true;
    }

    /**
     * @param string $id id of row to update
     * @param array $values values to insert into row
     *
     * @return int The number of affected rows
     */
    protected function updateRow($id, array $values)
    {
        //Unset the ID since we are using it to update the row
        if (isset($values['id'])) {
            unset($values['id']);
        }

        DBManagerFactory::getInstance()->updateParams(
            $this->getRelationshipTable(),
            $this->getFields(),
            $values,
            array(
                'id' => $id,
            )
        );

        return true;
    }

    /**
     * Removes one or more rows from the relationship table
     *
     * @param $where array of field=>value pairs to match
     *
     * @return int The number of affected rows
     */
    protected function removeRow($where)
    {
        if (empty($where)) {
            return 0;
        }

        DBManagerFactory::getInstance()->updateParams(
            $this->getRelationshipTable(),
            $this->getFields(),
            array(
                'deleted' => 1,
                'date_modified' => TimeDate::getInstance()->nowDb(),
            ),
            $where
        );

        return true;
    }

    /**
     * Checks for an existing row who's keys match the one passed in.
     *
     * @param  $row
     *
     * @return array|bool returns false if now row is found, otherwise the row is returned
     */
    protected function checkExisting($row)
    {
        $leftIDName = $this->def['join_key_lhs'];
        $rightIDName = $this->def['join_key_rhs'];
        if (empty($row[$leftIDName]) || empty($row[$rightIDName])) {
            return false;
        }

        $leftID = $row[$leftIDName];
        $rightID = $row[$rightIDName];
        //Check the relationship role as well
        $builder = DBManagerFactory::getConnection()->createQueryBuilder();
        $expr = $builder->expr();
        $table = $this->getRelationshipTable();
        $builder->select('*')
            ->from($table)
            ->where($expr->eq($leftIDName, $builder->createPositionalParameter($leftID)))
            ->andWhere($expr->eq($rightIDName, $builder->createPositionalParameter($rightID)))
            ->andWhere($expr->eq('deleted', 0));
        $this->applyQueryBuilderFilter($builder, $table, false, true);

        $stmt = $builder->execute();
        return $stmt->fetch();
    }

    /**
     * Get role columns array for the defs
     * @return array
     */
    public function getRelationshipRoleColumns($def = null)
    {
        if (!empty($this->def['relationship_role_columns']) && is_array($this->def['relationship_role_columns'])) {
            return $this->def['relationship_role_columns'];
        }
        if (!empty($this->def['relationship_role_column']) && !empty($this->def["relationship_role_column_value"])) {
            return array($this->def['relationship_role_column'] => $this->def["relationship_role_column_value"]);
        }
        return array();
    }

    /**
     * Applies relationship filter to the query
     *
     * @param QueryBuilder $builder Query builder
     * @param string $tableAlias Relationship table alias
     * @param bool $ignoreRole Whether the role filter needs to be ignored
     * @param bool $ignorePrimary Whether the filter by primary flag needs to be ignored
     */
    protected function applyQueryBuilderFilter(
        QueryBuilder $builder,
        $tableAlias,
        $ignoreRole = false,
        $ignorePrimary = false
    ) {
        if (!$ignoreRole && !$this->ignore_role_filter) {
            $this->applyQueryBuilderRoleFilter($builder, $tableAlias);
        }

        if (!$ignorePrimary && !empty($this->primaryOnly)) {
            $this->applyQueryBuilderPrimaryFilter($builder, $tableAlias);
        }
    }

    /**
     * Applies role-specific relationship filter to the query
     *
     * @param QueryBuilder $builder Query builder
     * @param string $tableAlias Relationship table alias
     */
    protected function applyQueryBuilderRoleFilter(QueryBuilder $builder, $tableAlias)
    {
        $expr = $builder->expr();
        foreach ($this->getRelationshipRoleColumns() as $column => $value) {
            $field = sprintf('%s.%s', $tableAlias, $column);
            if ($value) {
                $cond = $expr->eq($field, $builder->createPositionalParameter($value));
            } else {
                $cond = $expr->isNull($field);
            }
            $builder->andWhere($cond);
        }
    }

    /**
     * Applies primary flag relationship filter to the query
     *
     * @param QueryBuilder $builder Query builder
     * @param string $tableAlias Relationship table alias
     */
    protected function applyQueryBuilderPrimaryFilter(QueryBuilder $builder, $tableAlias)
    {
        if (!empty($this->def['primary_flag_column'])) {
            $field = sprintf('%s.%s', $tableAlias, $this->def['primary_flag_column']);
            $builder->andWhere($builder->expr()->eq($field, 1));
        }
    }

    /**
     * Gets the relationship role column check for the where clause
     *
     * @param string $table
     *
     * @return string
     */
    protected function getRoleWhere($table = "", $ignore_role_filter = false, $ignore_primary_flag = false)
    {
        $ignore_role_filter = $ignore_role_filter || $this->ignore_role_filter;
        $roleCheck = "";
        if (empty ($table)) {
            $table = $this->getRelationshipTable();
        }

        if (!$ignore_role_filter) {
            $db = DBManagerFactory::getInstance();
            foreach ($this->getRelationshipRoleColumns() as $column => $value) {
                if (empty($table)) {
                    $roleCheck = " AND $column";
                } else {
                    $roleCheck = " AND $table.$column";
                }
                //role column value.
                if (empty($value)) {
                    $roleCheck .= ' IS NULL';
                } else {
                    $roleCheck .= " = ".$db->quoted($value);
                }
            }
        }
        if (!empty($this->def['primary_flag_column'])
            && !empty($this->primaryOnly)
            && !$ignore_primary_flag) {

            $field = $table.'.'.$this->def['primary_flag_column'];

            $roleCheck .= " AND {$field} = 1 ";
        }

        return $roleCheck;
    }

    /**
     * add role where clause to query builder
     * @param QueryBuilder $query
     * @param bool $ignoreFilter
     * @param bool $ignoreFlag
     */
    protected function addRoleWhereToQuery(QueryBuilder $query, $ignoreFilter = false, $ignoreFlag = false)
    {
        $expr = $query->expr();
        if (!$ignoreFilter && !$this->ignore_role_filter) {
            foreach ($this->getRelationshipRoleColumns() as $name => $value) {
                if (empty($value)) {
                    $query->andWhere($expr->isNull($name));
                } else {
                    $query->andWhere($expr->eq($name, $query->createPositionalParameter($value)));
                }
            }
        }

        if (!empty($this->def['primary_flag_column']) && !empty($this->primaryOnly) && !$ignoreFlag) {
            $query->andWhere($expr->eq($this->def['primary_flag_column'], 1));
        }

    }


    /**
     * Build a Where object for a role in a relationship
     *
     * @param SugarQuery $sugar_query
     * @param string $table
     * @param bool $ignore_role_filter
     *
     * @return SugarQuery
     */
    protected function buildSugarQueryRoleWhere(
        $sugar_query,
        $table = "",
        $ignore_role_filter = false
    ) {
        $ignore_role_filter = $ignore_role_filter || $this->ignore_role_filter;
        $roleCheck = array();

        if (empty ($table)) {
            $table = $this->getRelationshipTable();
        }

        if (!$ignore_role_filter && !empty($table)) {
            foreach ($this->getRelationshipRoleColumns() as $column => $value) {
                //role column value.
                $field = "$table.$column";
                if (empty($value)) {
                    if (isset($sugar_query->join[$table])) {
                        $sugar_query->join[$table]->on()->isNull($field);
                    } else {
                        $sugar_query->where()->isNull($field);
                    }
                } else {
                    if (isset($sugar_query->join[$table])) { // i.e. Accounts joining Notes
                        $sugar_query->join[$table]->on()->equals($field, $value);
                    } else { // i.e. Notes joining Accounts
                        $sugar_query->where()->equals($field, $value);
                    }
                }
            }
        }

        if (!empty($this->def['primary_flag_column'])
            && !empty($this->primaryOnly)
            && !$ignore_role_filter) {

            $field = $this->def['primary_flag_column'];
            if (!empty($table)) {
                $field = "$table.{$field}";
            }
            $sugar_query->join[$table]->on()->equals($field, '1');
        }

        return $sugar_query;
    }

    /**
     * @param SugarBean $focus base bean the hooks is triggered from
     * @param SugarBean $related bean being added/removed/updated from relationship
     * @param string $link_name name of link being triggerd
     *
     * @return array base arguments to pass to relationship logic hooks
     */
    protected function getCustomLogicArguments($focus, $related, $link_name)
    {
        $custom_logic_arguments = array();
        $custom_logic_arguments['id'] = $focus->id;
        $custom_logic_arguments['related_id'] = $related->id;
        $custom_logic_arguments['name'] = $focus->name;
        $custom_logic_arguments['related_name'] = $related->name;
        $custom_logic_arguments['module'] = $focus->module_dir;
        $custom_logic_arguments['related_module'] = $related->module_dir;
        $custom_logic_arguments['link'] = $link_name;
        $custom_logic_arguments['relationship'] = $this->name;

        return $custom_logic_arguments;
    }

    /**
     * Call the before add logic hook for a given link
     *
     * @param  SugarBean $focus base bean the hooks is triggered from
     * @param  SugarBean $related bean being added/removed/updated from relationship
     * @param string $link_name name of link being triggerd
     *
     * @return void
     */
    protected function callBeforeAdd($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
            $focus,
            $related,
            $link_name
        );
        $focus->call_custom_logic(
            'before_relationship_add',
            $custom_logic_arguments
        );
    }

    /**
     * Call the after add logic hook for a given link
     *
     * @param  SugarBean $focus base bean the hooks is triggered from
     * @param  SugarBean $related bean being added/removed/updated from relationship
     * @param string $link_name name of link being triggerd
     *
     * @return void
     */
    protected function callAfterAdd($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
            $focus,
            $related,
            $link_name
        );
        $focus->call_custom_logic(
            'after_relationship_add',
            $custom_logic_arguments
        );
    }

    /**
     * Call the before update logic hook for a given link
     *
     * @param  SugarBean $focus base bean the hooks is triggered from
     * @param  SugarBean $related bean being added/removed/updated from relationship
     * @param string $link_name name of link being triggerd
     *
     * @return void
     */
    protected function callBeforeUpdate($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
            $focus,
            $related,
            $link_name
        );
        $focus->call_custom_logic(
            'before_relationship_update',
            $custom_logic_arguments
        );
    }

    /**
     * Call the after update logic hook for a given link
     *
     * @param  SugarBean $focus base bean the hooks is triggered from
     * @param  SugarBean $related bean being added/removed/updated from relationship
     * @param string $link_name name of link being triggerd
     *
     * @return void
     */
    protected function callAfterUpdate($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
                $focus,
                $related,
                $link_name
        );
        $focus->call_custom_logic(
                'after_relationship_update',
                $custom_logic_arguments
        );
    }

    /**
     * @param  SugarBean $focus
     * @param  SugarBean $related
     * @param string $link_name
     *
     * @return void
     */
    protected function callBeforeDelete($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
            $focus,
            $related,
            $link_name
        );
        $focus->call_custom_logic(
            'before_relationship_delete',
            $custom_logic_arguments
        );
    }

    /**
     * @param  SugarBean $focus
     * @param  SugarBean $related
     * @param string $link_name
     *
     * @return void
     */
    protected function callAfterDelete($focus, $related, $link_name = "")
    {
        $custom_logic_arguments = $this->getCustomLogicArguments(
            $focus,
            $related,
            $link_name
        );
        $focus->call_custom_logic(
            'after_relationship_delete',
            $custom_logic_arguments
        );
    }

    /**
     * Gets the correct table to select a custom where from
     *
     * @param string $whereTable Existing whereTable
     * @param SugarBean $relatedBean The related bean
     * @param string $fieldName The field name to check for a custom where table
     * @return string
     */
    public function getWhereTable($whereTable, $relatedBean, $fieldName)
    {
        // Its just easier to work on a shorter variable
        $defs = $relatedBean->field_defs;

        // If the field is sourced from custom fields, get the custom table it
        // belongs to
        if ($fieldName && isset($defs[$fieldName]['source']) && $defs[$fieldName]['source'] === 'custom_fields') {
            $whereTable = $relatedBean->get_custom_table_name();
        }

        return $whereTable;
    }

    /**
     * Gets an optional where clause
     *
     * @param $optional_array List of conditionals to apply to a custom where
     * @param string $whereTable The existing where table to select from
     * @param SugarBean $relatedBean
     * @return string
     */
    protected function getOptionalWhereClause($optional_array, $whereTable = '', SugarBean $relatedBean = null)
    {
        //lhs_field, operator, and rhs_value must be set in optional_array
        foreach (array("lhs_field", "operator", "rhs_value") as $required) {
            if (empty($optional_array[$required])) {
                throw new \InvalidArgumentException('lhs_field, operator and rhs_value must be set');
            }
        }

        // If there was a related bean passed, use it to get the where table
        if ($relatedBean) {
            $whereTable = $this->getWhereTable($whereTable, $relatedBean, $optional_array['lhs_field']);
        }

        // If $whereTable is not empty, add the dot
        if (!empty($whereTable)) {
            $whereTable .= '.';
        }

        return $whereTable . $optional_array['lhs_field'] . "" . $optional_array['operator'] . "'" . $optional_array['rhs_value'] . "'";
    }


    /**
     * Adds optional where clause to the sugar query
     *
     * @param SugarQuery $query
     * @param array $optional_array List of conditionals to apply to a custom where
     * @param string $whereTable The existing where table to select from
     * @param SugarBean $relatedBean
     *
     * @return bool
     */
    protected function buildOptionalQueryWhere(
        SugarQuery $query,
        $optional_array,
        $whereTable = '',
        SugarBean $relatedBean = null
    ) {
        //lhs_field, operator, and rhs_value must be set in optional_array
        foreach (array('lhs_field', 'operator', 'rhs_value') as $required) {
            if (empty($optional_array[$required])) {
                throw new \InvalidArgumentException('lhs_field, operator and rhs_value must be set');
            }
        }

        // If there was a related bean passed, use it to get the where table
        if ($relatedBean) {
            $whereTable = $this->getWhereTable($whereTable, $relatedBean, $optional_array['lhs_field']);
        }

        // If $whereTable is not empty, add the dot
        if ($whereTable) {
            $whereTable .= '.';
        }

        $query->where()->condition(
            $whereTable . $optional_array['lhs_field'],
            $optional_array['operator'],
            $optional_array['rhs_value']
        );
        return true;
    }

    /**
     * Parses string representation of ORDER BY into an array
     *
     * @param string $orderBy Order by string
     *
     * @return array
     */
    protected function getOrderByFields($orderBy)
    {
        $parts = explode(',', $orderBy);
        array_walk($parts, 'trim');
        $fields = array();
        foreach ($parts as $part) {
            $matches = array();
            if (preg_match('/^(.*)\s+(asc|desc)$/i', $part, $matches)) {
                $fields[$matches[1]] = $matches[2];
            } else {
                $fields[$part] = 'ASC';
            }
        }
        return $fields;
    }

    /**
     * Adds a realted Bean to the list to be resaved along with the current bean.
     * @static
     *
     * @param  SugarBean $bean
     *
     * @return void
     */
    public static function addToResaveList($bean)
    {
        if (isset(self::$resaveIndex[$bean->module_dir][$bean->id])) {
            return;
        }

        self::$resaveIndex[$bean->module_dir][$bean->id] = true;
        self::$resaveQueue[] = $bean;
    }

    /**
     *
     * @static
     * @param Bool $refresh using the newest version of the bean, not the one queued
     * @return void
     */
    public static function resaveRelatedBeans($refresh = true)
    {
        if (SugarBean::inOperation('updating_relationships') || !SugarBean::enterOperation('saving_related')) {
            return;
        }

        //Resave any bean not currently in the middle of a save operation
        while (count(self::$resaveQueue) > 0) {
            $bean = array_shift(self::$resaveQueue);
            if (empty($bean->deleted) && empty($bean->in_save)) {
                if ($refresh) {
                    // Make sure we're using the newest version of the bean, not the one queued
                    $latestBean = BeanFactory::retrieveBean($bean->module_name, $bean->id);
                    if ($latestBean !== null) {
                        $bean = $latestBean;
                    }
                }
                $bean->save();
            } else {
                // Bug 55942 save the in-save id which will be used to send workflow alert later
                if (isset($bean->id) && !empty($_SESSION['WORKFLOW_ALERTS'])) {
                    $_SESSION['WORKFLOW_ALERTS']['id'] = $bean->id;
                }
            }
        }

        SugarBean::leaveOperation('saving_related');

        //Reset the queue index
        self::$resaveIndex = array();

        SugarBean::clearRecursiveResave();
        return true;
    }

    /**
     * @return bool true if the relationship is a flex / parent relationship
     */
    public function isParentRelationship()
    {
        //check role fields to see if this is a parent (flex relate) relationship
        if (!empty($this->def["relationship_role_column"]) && !empty($this->def["relationship_role_column_value"])
            && $this->def["relationship_role_column"] == "parent_type" && $this->def['rhs_key'] == "parent_id"
        ) {
            return true;
        }
        return false;
    }

    public function __get($name)
    {
        if (isset($this->def[$name])) {
            return $this->def[$name];
        }

        switch ($name) {
            case 'relationship_type':
                return $this->type;
            case 'relationship_name':
                return $this->name;
            case 'lhs_module':
                return $this->getLHSModule();
            case 'rhs_module':
                return $this->getRHSModule();
            case 'lhs_table':
                return $this->def['lhs_table'];
            case 'rhs_table':
                return $this->def['rhs_table'];
            case 'list_fields':
                return array(
                    'lhs_table',
                    'lhs_key',
                    'rhs_module',
                    'rhs_table',
                    'rhs_key',
                    'relationship_type'
                );
        }

        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }
}
