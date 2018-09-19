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

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\StrategyInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility\Visibility;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;

/**
 * Class TeamBasedACLVisibility
 * For internal use, should not be used explicitly in vardefs.php.
 * Grant access to users who belong to one of the Selected Teams.
 */
class TeamBasedACLVisibility extends SugarVisibility implements StrategyInterface
{
    /**
     * Apply TBA in from clause.
     * {@inheritdoc}
     */
    public function addVisibilityFrom(&$query)
    {
        if ($this->getOption('where_condition') || !$this->isApplicable()) {
            return $query;
        }
        $query .= $this->getWhereClause();
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function addVisibilityFromQuery(SugarQuery $query)
    {
        $join = '';
        $this->addVisibilityFrom($join);
        if (!empty($join)) {
            // The "addVisibilityFrom()" is just a cover for the WHERE part.
            $query->whereRaw($join);
        }
        return $query;
    }

    /**
     * Apply TBA in where clause.
     * {@inheritdoc}
     */
    public function addVisibilityWhere(&$query)
    {
        if (!$this->getOption('where_condition') || !$this->isApplicable()) {
            return $query;
        }
        $where = $this->getWhereClause();
        if (!empty($query)) {
            $query .= " AND $where";
        } else {
            $query = $where;
        }
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function addVisibilityWhereQuery(SugarQuery $query)
    {
        $condition = '';
        $this->addVisibilityWhere($condition);
        if (!empty($condition)) {
            $query->whereRaw($condition);
        }
        return $query;
    }

    /**
     * Get a TBA where clause.
     * @return string Where clause
     */
    protected function getWhereClause()
    {
        global $current_user;

        list($teamTableAlias, $tableAlias) = $this->getAliases();
        $inClause = "SELECT tst.team_id
            FROM team_sets_teams tst
            INNER JOIN team_memberships {$teamTableAlias} ON {$teamTableAlias}.team_id = tst.team_id
                AND {$teamTableAlias}.user_id = '{$current_user->id}' AND {$teamTableAlias}.deleted = 0
            WHERE tst.team_set_id = {$tableAlias}.acl_team_set_id";

        $ow = new OwnerVisibility($this->bean, $this->params);
        if (!empty($this->getOption('table_alias'))) {
            $ow->setOptions(array('table_alias' => $this->getOption('table_alias')));
        }
        $ownerVisibilityRaw = '';
        $ow->addVisibilityWhere($ownerVisibilityRaw);

        return "({$ownerVisibilityRaw} OR EXISTS ({$inClause})) ";
    }

    /**
     * Verifies if Team Based ACL needs to be applied.
     * @return bool
     */
    protected function isApplicable()
    {
        global $current_user;

        if (empty($current_user)) {
            return false;
        }
        return true;
    }

    /**
     * Get table aliases for raw queries.
     * @return array [Team Membership, Table]
     */
    protected function getAliases()
    {
        $teamTableAlias = 'team_memberships';
        $tableAlias = $this->getOption('table_alias');
        if (!empty($tableAlias)) {
            $teamTableAlias = $this->bean->db->getValidDBName($teamTableAlias . $tableAlias, true, 'table');
        } else {
            $tableAlias = $this->bean->table_name;
        }
        return array($teamTableAlias, $tableAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticBuildAnalysis(AnalysisBuilder $analysisBuilder, Visibility $provider)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function elasticBuildMapping(Mapping $mapping, Visibility $provider)
    {
        $property = new MultiFieldProperty();
        $property->setType('keyword');
        $mapping->addCommonField('acl_team_set_id', 'set', $property);
    }

    /**
     * {@inheritdoc}
     */
    public function elasticProcessDocumentPreIndex(Document $document, SugarBean $bean, Visibility $provider)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function elasticGetBeanIndexFields($module, Visibility $provider)
    {
        return array('acl_team_set_id' => 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function elasticAddFilters(User $user, \Elastica\Query\BoolQuery $filter, Visibility $provider)
    {
        if ($this->isApplicable()) {
            $combo = new \Elastica\Query\BoolQuery();
            $combo->addFilter($provider->createFilter('TeamSet', [
                'user' => $user,
                'module' => $this->bean->module_name,
                'field' => 'acl_team_set_id.set',
            ]));
            $combo->addFilter($provider->createFilter('Owner', [
                'user_id' => $user->id,
            ]));
            $filter->addMust($combo);
        }
    }
}
