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
 * 1-1 Bean relationship
 * @api
 */
class One2OneBeanRelationship extends One2MBeanRelationship
{

    public function __construct($def)
    {
        parent::__construct($def);
    }
    /**
     * @param  $lhs SugarBean left side bean to add to the relationship.
     * @param  $rhs SugarBean right side bean to add to the relationship.
     * @param  $additionalFields key=>value pairs of fields to save on the relationship
     * @return boolean true if successful
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        $success = true;
        $lhsLinkName = $this->lhsLink;
        //In a one to one, any existing links from both sides must be removed first.
        //one2Many will take care of the right side, so we'll do the left.
        $lhs->load_relationship($lhsLinkName);
        if ($this->removeAll($lhs->$lhsLinkName) === false) {
            $success = false;
            LoggerManager::getLogger()->error("Warning: failed calling removeAll() on lhsLinkName: $lhsLinkName for relationship {$this->name} within One2OneBeanRelationship->add().");
        }
        if (parent::add($lhs, $rhs, $additionalFields) === false) {
            $success = false;
            LoggerManager::getLogger()->error("Warning: failed calling parent add() for relationship {$this->name} within One2OneBeanRelationship->add().");
        }
        return $success;
    }

    protected function updateLinks($lhs, $lhsLinkName, $rhs, $rhsLinkName)
    {
        //RHS and LHS only ever have one bean
        if (isset($lhs->$lhsLinkName))
            $lhs->$lhsLinkName->beans = array($rhs->id => $rhs);

        if (isset($rhs->$rhsLinkName))
            $rhs->$rhsLinkName->beans = array($lhs->id => $lhs);
    }

    public function getJoin($link, $params = array(), $return_array = false)
    {
        $linkIsLHS = $link->getSide() == REL_LHS;
        $startingTable = $link->getFocus()->table_name;
        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];
        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];
        $targetTableWithAlias = $targetTable;
        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $join_type= isset($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';

        $join = '';

        //Set up any table aliases required
        if ( ! empty($params['join_table_alias']))
        {
            $targetTableWithAlias = $targetTable . " ". $params['join_table_alias'];
            $targetTable = $params['join_table_alias'];
        }

        $deleted = !empty($params['deleted']) ? 1 : 0;

        //join the related module's table
        $join .= "$join_type $targetTableWithAlias ON $targetTable.$targetKey=$startingTable.$startingKey"
               . " AND $targetTable.deleted=$deleted\n"
        //Next add any role filters
               . $this->getRoleWhere();

        if($return_array){
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
     * Build a Join for an existing SugarQuery
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

        $startingTable = isset($options['myAlias']) ? $options['myAlias'] : '';
        if (empty($startingTable)) {
            $startingTable = $linkIsLHS ? $this->def['lhs_table'] : $this->def['rhs_table'];
        }
        $startingKey = $linkIsLHS ? $this->def['lhs_key'] : $this->def['rhs_key'];

        $targetTable = $linkIsLHS ? $this->def['rhs_table'] : $this->def['lhs_table'];

        $targetKey = $linkIsLHS ? $this->def['rhs_key'] : $this->def['lhs_key'];
        $targetModule = $linkIsLHS ? $this->def['rhs_module'] : $this->def['lhs_module'];

        $join_type= isset($options['joinType']) ? $options['joinType'] : 'INNER';

        $joinParams = array(
            'joinType' => $join_type,
            'bean' => BeanFactory::getDefinition($targetModule),
        );
        $jta = $targetTable;
        if (!empty($options['joinTableAlias'])) {
            $jta = $joinParams['alias'] = $options['joinTableAlias'];
        }

        $join = $sugar_query->joinTable($targetTable, $joinParams);
        $join->on()->equalsField("{$startingTable}.{$startingKey}", "{$jta}.{$targetKey}")
            ->equals("{$jta}.deleted", "0");


        if (empty($options['ignoreRole'])) {
            $this->buildSugarQueryRoleWhere($sugar_query, $jta);
        }

        $this->addCustomToSugarQuery($sugar_query, $options, $linkIsLHS, $jta);

        return $sugar_query->join[$jta];
    }

    /**
     * {@inheritdoc}
     */
    public function getType($side)
    {
        return REL_TYPE_ONE;
    }
}
