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
 * Class SugarQuery_Builder_Union.
 */
class SugarQuery_Builder_Union
{
    /**
     * @var SugarQuery
     */
    protected $query;

    /**
     * Array of union queries.
     * @var array
     */
    protected $queries = array();

    /**
     * Create Union Object.
     * @param SugarQuery $query
     */
    public function __construct(SugarQuery $query)
    {
        $this->query = $query;
    }

    /**
     * Add new query for union.
     * @param SugarQuery $query Query object to add.
     * @param bool $all (optional) Indicates should 'UNION ALL' be used or not. Default is `true`.
     */
    public function addQuery(SugarQuery $query, $all = true)
    {
        $this->queries[] = array('query' => $query, 'all' => (boolean) $all);
    }

    /**
     * Return queries for union.
     * @return array Set of query objects.
     */
    public function getQueries()
    {
        return $this->queries;
    }
}
