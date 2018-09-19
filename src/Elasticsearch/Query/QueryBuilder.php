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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Query;

use Sugarcrm\Sugarcrm\Elasticsearch\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Highlighter\HighlighterInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Aggregation\AggregationInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\ResultSet;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Client;
use Sugarcrm\Sugarcrm\Elasticsearch\Exception\QueryBuilderException;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Aggregation\AggregationStack;
use User;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Result\ParserInterface;

/**
 *
 * Query Builder
 *
 */
class QueryBuilder
{
    /**
     * Field separator
     * @deprecated
     */
    const FIELD_SEP = '.';

    /**
     * Module name prefix separator
     * @deprecated Use Mapping::PREFIX_SEP
     */
    const PREFIX_SEP = '__';

    /**
     * Boost value separator
     */
    const BOOST_SEP = '^';

    /**
     * @var Container
     */
    protected $container;

    /**
     * User context
     * @var User
     */
    protected $user;

    /**
     * Apply visibility filters
     * @var boolean
     */
    protected $applyVisibility = true;

    /**
     * @var QueryInterface
     */
    protected $query;

    /**
     * Modules being queried
     * @var array
     */
    protected $modules = array();

    /**
     * @var AggregationStack
     */
    protected $aggregationStack = array();

    /**
     * Aggregation filter definitions
     * @var array
     */
    protected $aggFilterDefs = array();

    /**
     * List of query filters
     * @var \Elastica\Query\AbstractQuery[]
     */
    protected $filters = array();

    /**
     * List of post filters
     * @var \Elastica\Query\AbstractQuery[]
     */
    protected $postFilters = array();

    /**
     * @var HighlighterInterface
     */
    protected $highlighter;

    /**
     * @var ParserInterface
     */
    protected $resultParser;

    /**
     * @var integer
     */
    protected $limit;

    /**
     * @var integer
     */
    protected $offset;

    /**
     * @var array
     */
    protected $sort = array('_score');

    /**
     * Set explain flag
     * @var boolean
     */
    protected $explain = false;

    /**
     * Ctor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Set user context
     * @param User $user
     * @return QueryBuilder
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user context
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Disable visibility filter, not recommended !
     */
    public function disableVisibility()
    {
        $this->applyVisibility = false;
    }

    /**
     * Set query
     * @param QueryInterface $query
     * @return QueryBuilder
     */
    public function setQuery(QueryInterface $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Set modules
     * @param array $modules
     * @return QueryBuilder
     */
    public function setModules(array $modules)
    {
        if ($this->applyVisibility) {
            $modules = $this->getAllowedModules($modules);
        }
        $this->modules = $modules;
        return $this;
    }

    /**
     * Get selected modules
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }


    /**
     * Set highlighter
     * @param HighlighterInterface $highLighter
     * @return QueryBuilder
     */
    public function setHighLighter(HighlighterInterface $highlighter)
    {
        $this->highlighter = $highlighter;
        return $this;
    }

    /**
     * Set result parser
     * @param ParserInterface $parser
     * @return QueryBuilder
     */
    public function setResultParser(ParserInterface $parser)
    {
        $this->resultParser = $parser;
        return $this;
    }

    /**
     * Add aggregation
     * @param string $id
     * @param AggregationInterface
     * @return QueryBuilder
     */
    public function addAggregation($id, AggregationInterface $agg)
    {
        // initialize stack
        if (empty($this->aggregationStack)) {
            $this->aggregationStack = new AggregationStack();
        }

        $this->aggregationStack->addAggregation($id, $agg);
        return $this;
    }

    /**
     * Add query filter
     * @param \Elastica\Query\AbstractQuery $filter
     * @return QueryBuilder
     */
    public function addFilter(\Elastica\Query\AbstractQuery $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Add query filter
     * @param \Elastica\Query\AbstractQuery $postFilter
     * @return QueryBuilder
     */
    public function addPostFilter(\Elastica\Query\AbstractQuery $postFilter)
    {
        $this->postFilters[] = $postFilter;
        return $this;
    }

    /**
     * Set limit
     * @param integer $limit
     * @return QueryBuilder
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     * Set offset
     * @param integer $offset
     * @return QueryBuilder
     */
    public function setOffset($offset)
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     * Set sort
     * @param array $fields
     * @return QueryBuilder
     */
    public function setSort(array $fields)
    {
        $this->sort = $fields;
        return $this;
    }

    /**
     * Set explain flag
     * @param boolean $flag
     * @return QueryBuilder
     */
    public function setExplain($flag)
    {
        $this->explain = (bool) $flag;
        return $this;
    }

    /**
     * Set aggregation filter definitions
     * @param array $aggFilterDefs
     */
    public function setAggFilterDefs(array $aggFilterDefs)
    {
        $this->aggFilterDefs = $aggFilterDefs;
    }

    /**
     * Add settings after building query.
     * @param \Elastica\Query $query object the query object
     */
    protected function addSettingsAfterBuild(\Elastica\Query $query)
    {
        // Set limit
        if (isset($this->limit)) {
            $query->setSize($this->limit);
        }

        // Set offset
        if (isset($this->offset)) {
            $query->setFrom($this->offset);
        }

        // Add highlighter
        if ($this->highlighter) {
            $query->setHighlight($this->highlighter->build());
        }

        // Set sort order
        if ($this->sort) {
            $query->setSort($this->sort);
        }

        // Add aggregations, needs to happen before post filter
        if (!empty($this->aggregationStack)) {
            $this->buildAggregations($query, $this->aggregationStack, $this->aggFilterDefs);
        }

        // Set post filter
        if (!empty($this->postFilters)) {
            $query->setPostFilter($this->buildPostFilters($this->postFilters));
        }
    }

    /**
     * Build query
     * @return \Elastica\Query
     */
    public function build()
    {
        // Wrap query in a filtered query
        $query = new \Elastica\Query\BoolQuery();
        $query->addMust($this->query->build());

        // Apply visibility filtering
        if ($this->applyVisibility) {
            $this->buildVisibilityFilters($this->modules);
        }

        // Add all filters to query
        $query->addFilter($this->buildFilters($this->filters));

        // Wrap again in our main query object
        $query = $this->buildQuery($query);

        // Apply settings afterwards
        $this->addSettingsAfterBuild($query);

        return $query;
    }

    /**
     * Execute query against search API
     * @return ResultSet
     */
    public function executeSearch()
    {
        if (empty($this->user)) {
            throw new QueryBuilderException('QueryBuilder executeSearch failed - no user context');
        }

        if (empty($this->modules)) {
            throw new QueryBuilderException('QueryBuilder executeSearch failed - no modules selected');
        }

        // Build query
        $query = $this->build();
        $query->setExplain($this->explain);

        // Wrap query in search API object
        $search = $this->newSearchObject();
        $search->setQuery($query);
        $search->addIndices($this->getReadIndices($this->modules, $this->user));
        $search->addTypes($this->modules);

        return $this->createResultSet($search->search());
    }

    /**
     * Prepare result set
     * @param \Elastica\ResultSet $resultSet
     * @return ResultSet
     */
    protected function createResultSet(\Elastica\ResultSet $resultSet)
    {
        $resultSet = new ResultSet($resultSet);

        // attach result parser
        if ($this->resultParser) {
            $resultSet->setResultParser($this->resultParser);
        }

        // attach aggregation stack
        if ($this->aggregationStack) {
            $resultSet->setAggregationStack($this->aggregationStack);
        }

        return $resultSet;
    }

    /**
     * Create search object
     * @param Client $client Optional client
     * @return \Elastica\Search
     */
    protected function newSearchObject(Client $client = null)
    {
        $client = $client ?: $this->container->client;
        return new \Elastica\Search($client);
    }

    /**
     * Build filters
     * @return \Elastica\Query\BoolQuery
     */
    protected function buildFilters(array $filters)
    {
        $result = new \Elastica\Query\BoolQuery();
        foreach ($filters as $filter) {
            $result->addMust($filter);
        }
        return $result;
    }

    /**
     * Build post filters
     * @return \Elastica\Query\BoolQuery
     */
    protected function buildPostFilters(array $postFilters)
    {
        $result = new \Elastica\Query\BoolQuery();
        foreach ($postFilters as $postFilter) {
            $result->addMust($postFilter);
        }
        return $result;
    }

    /**
     * Build aggregations
     * @param \Elastica\Query $query
     * @param AggregationStack $stack
     * @param array $filterDefs
     */
    protected function buildAggregations(\Elastica\Query $query, AggregationStack $stack, array $filterDefs)
    {
        // build the aggregations from the stack
        foreach ($stack->buildAggregations($filterDefs) as $agg) {
            $query->addAggregation($agg);
        }

        // attach all filters as post filter
        foreach ($stack->getFilters() as $filter) {
            $this->addPostFilter($filter);
        }
    }

    /**
     * Build main query object
     * @param \Elastica\Query\AbstractQuery $query
     * @return \Elastica\Query
     */
    protected function buildQuery(\Elastica\Query\AbstractQuery $query)
    {
        return new \Elastica\Query($query);
    }

    /**
     * Return list of indices to read from. Currently only the user context is
     * supported but might be extended with date ranges too for rolling
     * indices depending on the index pool strategies.
     *
     * @param array $modules
     * @param User $user
     * @return array
     */
    protected function getReadIndices(array $modules, User $user = null)
    {
        $context = empty($user) ? array() : array('user' => $user);
        $collection = $this->container->indexPool->getReadIndices($modules, $context);
        return iterator_to_array($collection);
    }

    /**
     * Filter list of modules based on user context ACL
     * @param array $modules
     * @return array
     */
    protected function getAllowedModules(array $modules)
    {
        $userModules = $this->container->metaDataHelper->getAvailableModulesForUser($this->user);
        return array_intersect($modules, $userModules);
    }

    /**
     * Build visibility filter
     * @param array $modules
     */
    protected function buildVisibilityFilters(array $modules)
    {
        $visibility = $this->container->getProvider('Visibility');
        $visibility->buildVisibilityFilters($this, $modules);
    }
}
