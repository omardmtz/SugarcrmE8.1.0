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
 * Represents a one to many relationship that is table based.
 * @api
 */
class One2MBeanRelationship extends One2MRelationship
{
    //Type is read in sugarbean to determine query construction
    var $type = "one-to-many";

    public function __construct($def)
    {
        parent::__construct($def);
    }

    /**
     * @param  $lhs SugarBean left side bean to add to the relationship.
     * @param  $rhs SugarBean right side bean to add to the relationship.
     * @param  $additionalFields key=>value pairs of fields to save on the relationship
     *
     * @return boolean true if successful
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        // test to see if the relationship exist if the relationship between the two beans
        // exist then we just fail out with false as we don't want to re-trigger this
        // the save and such as it causes problems with the related() in sugarlogic
        if ($this->relationship_exists($lhs,$rhs) && $lhs->inOperation('saving_related')) {
            return false;
        }

        $lhsLinkName = $this->lhsLink;
        $rhsLinkName = $this->rhsLink;

        //Since this is bean based, we know updating the RHS's field will overwrite any old value,
        //But we need to use delete to make sure custom logic is called correctly
        if ($rhs->load_relationship($rhsLinkName)) {
            $oldLink = $rhs->$rhsLinkName;
            $prevRelated = $oldLink->getBeans(null);
            foreach($prevRelated as $oldLHS) {
                if ($oldLHS->id != $lhs->id) {
                    $this->remove($oldLHS, $rhs, false);
                }
            }
        }
        //Make sure we load the current relationship state to the LHS link
        if ((isset($lhs->$lhsLinkName) && is_a(
                    $lhs->$lhsLinkName,
                    "Link2"
                )) || $lhs->load_relationship($lhsLinkName)
        ) {
            $lhs->$lhsLinkName->load();
        }

        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes") {
            $this->callBeforeAdd($lhs, $rhs, $lhsLinkName);
            $this->callBeforeAdd($rhs, $lhs, $rhsLinkName);
        }

        $this->updateFields($lhs, $rhs, $additionalFields);

        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes") {
            //Need to call save to update the bean as the relationship is saved on the main table
            //We don't want to create a save loop though, so make sure we aren't already in the middle of saving this bean
            SugarRelationship::addToResaveList($rhs);

            $this->updateLinks($lhs, $lhsLinkName, $rhs, $rhsLinkName);

            $this->callAfterAdd($lhs, $rhs, $lhsLinkName);
            $this->callAfterAdd($rhs, $lhs, $rhsLinkName);
        }

        //One2MBean relationships require that the RHS bean be saved or else the relationship will not be saved.
        //If we aren't already in a relationship save, intitiate a save now.
        if (!$lhs->inOperation('saving_related')) {
            SugarRelationship::resaveRelatedBeans(false);
        }

        return true;
    }

    protected function updateLinks($lhs, $lhsLinkName, $rhs, $rhsLinkName)
    {
        if (isset($lhs->$lhsLinkName)) {
            $lhs->$lhsLinkName->resetLoaded();
        }

        if (isset($rhs->$rhsLinkName)) {
            $rhs->$rhsLinkName->resetLoaded();
        }
    }

    protected function updateFields($lhs, $rhs, $additionalFields)
    {
        //Now update the RHS bean's key field
        $lhsID = $this->def['lhs_key'];
        $rhsID = $this->def['rhs_key'];
        $rhs->$rhsID = $lhs->$lhsID;
        foreach ($additionalFields as $field => $val) {
            $rhs->$field = $val;
        }
        //Update role fields
        foreach ($this->getRelationshipRoleColumns() as $column => $value) {
            if (!empty($value)) {
                $rhs->$column = $value;
            }
        }
    }

    public function remove($lhs, $rhs, $save = true)
    {
        $rhsID = $this->def['rhs_key'];

        // If this relationship has already been removed, we can just return.
        // Check both current value of related ID field and the one from fetched row.
        // The latter is valid in case, if relation was removed by changing bean's related ID field to another value.
        // We still need to track this and call logic hooks.
        if ($rhs->$rhsID != $lhs->id && isset($rhs->fetched_row[$rhsID]) && $rhs->fetched_row[$rhsID] != $lhs->id) {
            return false;
        }

        $rhs->$rhsID = null;

        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes") {
            $this->callBeforeDelete($lhs, $rhs, $this->getLHSLink());
            $this->callBeforeDelete($rhs, $lhs, $this->getRHSLink());
        }

        if ($save && !$rhs->deleted) {
            $rhs->in_relationship_update = true;
            // Rather than calling full save on the related bean just update the
            // parent id field to empty it. This saves a significant amount of
            // in mass updating and mass deleting
            $nullValue = $rhs->db->massageValue(null, $rhs->field_defs[$rhsID], true);

            $rhs->db->updateParams(
                $rhs->getTableName(),
                $rhs->getFieldDefinitions(),
                array($rhsID => $nullValue),
                array('id' => $rhs->id)
            );
        }

        $link = $this->lhsLink;
        if ($lhs->load_relationship($link)) {
            $lhs->$link->resetLoaded();
        }
        if (empty($_SESSION['disable_workflow']) || $_SESSION['disable_workflow'] != "Yes") {
            $this->callAfterDelete($lhs, $rhs, $this->getLHSLink());
            $this->callAfterDelete($rhs, $lhs, $this->getRHSLink());
        }

        return true;
    }

    /**
     * @param  $link Link2 loads the relationship for this link.
     *
     * @return void
     */
    public function load($link, $params = array())
    {
        $rows = array();
        //The related bean ID is stored on the RHS table.
        //If the link is RHS, just grab it from the focus.
        if ($link->getSide() == REL_RHS) {
            $rhsID = $this->def['rhs_key'];
            if (isset($link->getFocus()->$rhsID)) {
                $id = $link->getFocus()->$rhsID;
                if (!empty($id)) {
                    $rows[$id] = array('id' => $id);
                }
            }
        } elseif ($link->getSide() == REL_LHS) {
            //If the link is LHS, we need to query to get the full list and load all the beans.
            $result = $this->getSugarQuery($link, $params)->execute();
            $rows = array();
            foreach ($result as $row) {
                $id = $row['id'];
                $rows[$id] = $row;
            }
        }

        return array("rows" => $rows);
    }

    /**
     * @deprecated Use One2MBeanRelationship::load() instead
     */
    public function getQuery($link, $params = array())
    {
        //There was an old signature with $return_as_array as the second parameter. We should respect this if $params is true
        if ($params === true) {
            $params = array("return_as_array" => true);
        }

        if ($link->getSide() != REL_LHS) {
            return false;
        } else {
            $lhsKey = $this->def['lhs_key'];
            $rhsTable = $this->def['rhs_table'];
            $rhsTableKey = "{$rhsTable}.{$this->def['rhs_key']}";
            $deleted = !empty($params['deleted']) ? 1 : 0;
            $where = "WHERE $rhsTableKey = '{$link->getFocus(
            )->$lhsKey}' AND {$rhsTable}.deleted=$deleted";

            //Check for role column
            $db = DBManagerFactory::getInstance();
            foreach ($this->getRelationshipRoleColumns() as $column => $value) {
                if (!empty($value)) {
                    $where .= " AND $rhsTable.$column = ".$db->quoted($value);
                }
            }

            //Add any optional where clause
            if (!empty($params['where'])) {
                $relatedSeed = BeanFactory::getDefinition($this->getRHSModule());
                $add_where = is_string($params['where']) ?
                             $params['where'] :
                             $this->getOptionalWhereClause($params['where'], $rhsTable, $relatedSeed);

                if (!empty($add_where)) {
                    $where .= " AND $add_where";
                }
            }

            $from = $this->def['rhs_table'];
            if (!empty($params['enforce_teams'])) {
                // This may have been set already
                if (empty($relatedSeed)) {
                    $relatedSeed = BeanFactory::getDefinition($this->getRHSModule());
                }
                if ($this->def['rhs_table'] != $relatedSeed->table_name) {
                    $from .= ", $relatedSeed->table_name";
                }
                $relatedSeed->add_team_security_where_clause($from);
            }

            if (empty($params['return_as_array'])) {
                //Limit is not compatible with return_as_array
                $orderby = !empty($params['orderby']) ? "ORDER BY $rhsTable.{$params['orderby']}" : "";
                $query = "SELECT {$this->def['rhs_table']}.id FROM $from $where $orderby";
                if (!empty($params['limit']) && $params['limit'] > 0) {
                    $offset = isset($params['offset']) ? $params['offset'] : 0;
                    $query = DBManagerFactory::getInstance()->limitQuery(
                        $query,
                        $offset,
                        $params['limit'],
                        false,
                        "",
                        false
                    );
                }
                return $query;
            } else {
                return array(
                    'select' => "SELECT {$this->def['rhs_table']}.id",
                    'from' => "FROM {$this->def['rhs_table']}",
                    'where' => $where,
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getSugarQuery(Link2 $link, array $params = array())
    {
        if ($link->getSide() != REL_LHS) {
            throw new \Exception('Wrong side of the link, we shouldn\'t query DB for this case');
        }
        $lhsKey = $this->def['lhs_key'];
        $rhsTable = $this->def['rhs_table'];
        $rhsTableKey = "{$rhsTable}.{$this->def['rhs_key']}";
        $deleted = !empty($params['deleted']) ? 1 : 0;
        $relatedSeed = BeanFactory::getDefinition($this->getRHSModule());

        $query = new SugarQuery();
        $query->from(
            $relatedSeed,
            array('team_security' => !empty($params['enforce_teams']), 'add_deleted' => false)
        );
        $query->select('id');
        $query->where()->equals($rhsTableKey, $link->getFocus()->$lhsKey)
            ->equals("$rhsTable.deleted", $deleted);

        //Check for role column
        foreach ($this->getRelationshipRoleColumns() as $column => $value) {
            if (!empty($value)) {
                $query->where()->equals("$rhsTable.$column", $value);
            }
        }

        //Add any optional where clause
        if (!empty($params['where'])) {
            if (is_string($params['where'])) {
                $query->where()->addRaw($params['where']);
            } else {
                $this->buildOptionalQueryWhere($query, $params['where'], $rhsTable, $relatedSeed);
            }
        }

        if (!empty($params['orderby'])) {
            $orderByFields = $this->getOrderByFields($params['orderby']);
            foreach ($orderByFields as $field => $direction) {
                $query->orderBy("$rhsTable.$field", $direction);
            }
        }
        if (!empty($params['limit']) && ($params['limit'] > 0)) {
            $query->limit($params['limit']);
        }
        if (isset($params['offset'])) {
            $query->offset($params['offset']);
        }
        return $query;
    }

    public function getJoin($link, $params = array(), $return_array = false)
    {
        $linkIsLHS = $link->getSide() == REL_LHS;
        $startingTable = (empty($params['left_join_table_alias']) ? $this->def['lhs_table'] : $params['left_join_table_alias']);
        if (!$linkIsLHS) {
            $startingTable = (empty($params['right_join_table_alias']) ? $this->def['rhs_table'] : $params['right_join_table_alias']);
        }
        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];
        $targetTableWithAlias = $targetTable;
        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type = isset($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';
        $join = '';

        //Set up any table aliases required
        if (!empty($params['join_table_alias'])) {
            $targetTableWithAlias = $targetTable . " " . $params['join_table_alias'];
            $targetTable = $params['join_table_alias'];
        }

        //First join the relationship table
        $join .= "$join_type $targetTableWithAlias ON $startingTable.$startingKey=$targetTable.$targetKey AND $targetTable.deleted=0\n"
            //Next add any role filters
            . $this->getRoleWhere(
                ($linkIsLHS) ? $targetTable : $startingTable
            ) . "\n";

        if ($return_array) {
            return array(
                'join' => $join,
                'type' => $this->type,
                'rel_key' => $targetKey,
                'join_tables' => array($targetTable),
                'where' => "",
                'select' => "$targetTable.id",
            );
        }
        return $join;
    }

    /**
     * Build a Join Query with a SugarQuery Object
     *
     * @param Link2 $link
     * @param SugarQuery $sugar_query
     * @param Array $options array of additional paramters. Possible parameters include
     *  - 'myAlias' String name of starting table alias
     *  - 'joinTableAlias' String alias to use for the related table in the final result
     *  - 'reverse' Boolean true if this join should be built in reverse for subpanel style queries where the select is
     *              on the related table
     *  - 'ignoreRole' Boolean true if the role column of the relationship should be ignored for this join .
     *
     * @return SugarQuery
     */
    public function buildJoinSugarQuery(Link2 $link, $sugar_query, $options)
    {
        $linkIsLHS = $link->getSide() == REL_LHS;
        if (!empty($options['reverse'])) {
            $linkIsLHS = !$linkIsLHS;
        }

        $startingTable = $linkIsLHS ? $this->def['lhs_table'] : $this->def['rhs_table'];
        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];
        $targetModule = $linkIsLHS ? $this->def['rhs_module'] : $this->def['lhs_module'];

        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type = isset($options['joinType']) ? $options['joinType'] : 'INNER';

        $joinParams = array(
            'joinType' => $join_type,
            'bean' => BeanFactory::getDefinition($targetModule),
        );
        $jta = $targetTable;
        if (!empty($options['joinTableAlias'])) {
            $jta = $joinParams['alias'] = $options['joinTableAlias'];
        }

        $joinTable = $sugar_query->joinTable($targetTable, $joinParams);
        $joinTable->on()->equalsField(
            "{$startingTable}.{$startingKey}",
            "{$jta}.{$targetKey}")
            ->equals("{$jta}.deleted", "0");

        $relTable =  $linkIsLHS ? $jta : $startingTable;

        if (empty($options['ignoreRole'])) {
            foreach ($this->getRelationshipRoleColumns() as $column => $value) {
                $joinTable->on()->equals("{$relTable}.$column", $value);
            }
        }

        $this->addCustomToSugarQuery($sugar_query, $options, $linkIsLHS, $jta);

        return $sugar_query->join[$jta];
    }


    public function getSubpanelQuery(
        $link,
        $params = array(),
        $return_array = false
    ) {

        $linkIsLHS = $link->getSide() == REL_RHS;
        $startingTable = (empty($params['left_join_table_alias']) ? $this->def['lhs_table'] : $params['left_join_table_alias']);
        if (!$linkIsLHS) {
            $startingTable = (empty($params['right_join_table_alias']) ? $this->def['rhs_table'] : $params['right_join_table_alias']);
        }
        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];
        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type = isset($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';
        $query = '';

        $alias = empty($params['join_table_alias']) ? "{$link->name}_rel" : $params['join_table_alias'];
        $alias = $GLOBALS['db']->getValidDBName($alias, false, 'alias');

        $tableInRoleFilter = "";
        if (
            (
                $startingTable == "meetings"
                || $startingTable == "notes"
                || $startingTable == "tasks"
                || $startingTable == "calls"
                || $startingTable == "emails"
            )
            &&
            (
                $targetTable == "meetings"
                || $targetTable == "notes"
                || $targetTable == "tasks"
                || $targetTable == "calls"
            )
            && substr(
                $alias,
                0,
                12 + strlen($targetTable)
            ) == $targetTable . "_activities_"
        ) {
            $tableInRoleFilter = $linkIsLHS ? $alias : $startingTable;
        }

        //Set up any table aliases required
        $targetTableWithAlias = "$targetTable $alias";
        $targetTable = $alias;

        $query .= "$join_type $targetTableWithAlias ON ($startingTable.$startingKey=$targetTable.$targetKey AND $targetTable.deleted=0)\n"
            //Next add any role filters
            . $this->getRoleWhere($tableInRoleFilter) . "\n";

        if (!empty($params['return_as_array'])) {
            $return_array = true;
        }

        if (!empty($params['return_as_array'])) {
            $return_array = true;
        }

        if ($return_array) {
            return array(
                'join' => $query,
                'type' => $this->type,
                'rel_key' => $targetKey,
                'join_tables' => array($targetTable),
                'where' => "WHERE $startingTable.$startingKey='{$link->focus->id}'",
                'select' => " ",
            );
        }
        return $query;

    }

    /**
     * Check to see if the relationship already exist.
     *
     * If it does return true otherwise return false
     *
     * @param SugarBean $lhs        Left hand side of the relationship
     * @param SugarBean $rhs        Right hand side of the relationship
     * @param bool      $return_row Ignored
     *
     * @return boolean
     */
    public function relationship_exists($lhs, $rhs, $return_row = false)
    {
        $lhsIDName = $this->def['rhs_key'];
        $rhsIDName = $this->def['lhs_key'];

        return (isset($rhs->fetched_row[$lhsIDName]) && $rhs->$lhsIDName == $rhs->fetched_row[$lhsIDName] && $rhs->$lhsIDName == $lhs->$rhsIDName);
    }

    public function getRelationshipTable()
    {
        if (isset($this->def['table'])) {
            return $this->def['table'];
        } else {
            return $this->def['rhs_table'];
        }
    }
}
