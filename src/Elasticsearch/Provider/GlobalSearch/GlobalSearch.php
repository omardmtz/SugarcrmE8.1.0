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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch;

use Sugarcrm\Sugarcrm\Elasticsearch\Provider\AbstractProvider;
use Sugarcrm\Sugarcrm\Elasticsearch\Container;
use Sugarcrm\Sugarcrm\Elasticsearch\ContainerAwareInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\ContainerAwareTrait;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\Parser\TermParserHelper;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\QueryBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\HandlerCollection;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\HandlerFilterIterator;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\HandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\MultiFieldHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\AutoIncrementHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\EmailAddressHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\CrossModuleAggHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\TagsHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\FavoritesHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\HtmlHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\ErasedFieldsHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\MultiMatchQuery;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\MatchAllQuery;

/**
 *
 * GlobalSearch Provider
 *
 */
class GlobalSearch extends AbstractProvider implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var HandlerCollection
     */
    protected $handlers;

    /**
     * @var Highlighter
     */
    protected $highlighter;

    /**
     * @var ResultParser
     */
    protected $resultParser;

    /**
     * @var Booster
     */
    protected $booster;

    /**
     * List of supported sugar types
     * @var array
     */
    protected $supportedTypes = array();

    /**
     * default space operator for globalsearch
     * current default value is 'AND'
     * @var string
     */
    protected $defaultOperator = TermParserHelper::OPERATOR_AND;

    /**
     * List of supported sugar types for Studio
     * @var array
     */
    protected $studioSupportedTypes = array(
        'varchar',
        'name',
        'text',
        'int',
        'phone',
        'url',
        'longtext',
        'htmleditable_tinymce',
        'email',
    );

    /**
     * List of types which should be skipped by getBeanIndexFields
     * when being called from QueueManager.
     * TODO: cleanup
     * @var array
     */
    protected $skipTypesFromQueue = array();

    /**
     * Ctor
     */
    public function __construct()
    {
        $this->highlighter = new Highlighter();
        $this->resultParser = new ResultParser($this->highlighter);
        $this->booster = new Booster();
        $this->handlers = new HandlerCollection($this);
        $this->registerHandlers();
    }

    /**
     * to get default operator
     * @return string
     */
    public function getDefaultOperator()
    {
        // using config to override the default operator
        global $sugar_config;
        if (isset($sugar_config['default_gs_space_operator'])) {
            $operator = TermParserHelper::getOperator($sugar_config['default_gs_space_operator']);
            if ($operator) {
                $this->defaultOperator = $operator;
            }
        }
        return $this->defaultOperator;
    }

    /**
     * Register handlers
     */
    protected function registerHandlers()
    {
        $this->addHandler(new MultiFieldHandler());
        $this->addHandler(new AutoIncrementHandler());
        $this->addHandler(new EmailAddressHandler());
        $this->addHandler(new CrossModuleAggHandler());
        $this->addHandler(new TagsHandler());
        $this->addHandler(new FavoritesHandler());
        $this->addHandler(new HtmlHandler());
        $this->addHandler(new ErasedFieldsHandler());
    }

    /**
     * Add handler
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers->addHandler($handler);
    }

    /**
     * Remove existing handler
     * @param string $name
     */
    public function removeHandler($name)
    {
        $this->handlers->removeHandler($name);
    }

    /**
     * Check if given handler is registered
     * @param string $name
     * @return boolean
     */
    public function hasHandler($name)
    {
        return $this->handlers->hasHandler($name);
    }

    /**
     * Get existing handler by name
     * @param string $name
     * @return HandlerInterface
     */
    public function getHandler($name)
    {
        return $this->handlers->getHandler($name);
    }

    /**
     * Get handlers filtered by interface. If no interface is given an
     * iterator of all available handlers is returned.
     *
     * @param string $interface Filter iterator by given interface
     * @return HandlerInterface[]
     */
    public function getHandlers($interface = null)
    {
        if (empty($interface)) {
            return $this->handlers->getIterator();
        }
        return new HandlerFilterIterator($this->handlers->getIterator(), $interface);
    }

    /**
     * Add supported field types
     * @param array $types
     */
    public function addSupportedTypes(array $types)
    {
        $this->supportedTypes = array_merge(
            $this->supportedTypes,
            array_flip($types)
        );
    }

    /**
     * Check if given field type is supported
     * @param string $type Field type
     * @return boolean
     */
    public function isSupportedType($type)
    {
        return isset($this->supportedTypes[$type]);
    }

    /**
     * Add types to be skipped in queue query
     * @param array $fields
     */
    public function addSkipTypesFromQueue(array $fields)
    {
        $this->skipTypesFromQueue = array_merge(
            $this->skipTypesFromQueue,
            array_flip($fields)
        );
    }

    /**
     * Check if given field type needs to be skipped
     * @param string $type Field type
     * @return boolean
     */
    public function isSkippedType($type)
    {
        return isset($this->skipTypesFromQueue[$type]);
    }

    /**
     * Add highlighter field definitions
     * @param array $fields
     */
    public function addHighlighterFields(array $fields)
    {
        $this->highlighter->setFields($fields);
    }

    /**
     * Add highlighter field remaps
     * @param string[] $remap
     */
    public function addFieldRemap(array $remap)
    {
        $this->resultParser->addHighlightRemap($remap);
    }

    /**
     * Add _source field remap
     * @param string[] $remap
     */
    public function addSourceRemap(array $remap)
    {
        $this->resultParser->addSourceRemap($remap);
    }

    /**
     * Set the highlighter to add tags in value.
     * @param array $setting the value of the field setting
     */
    public function setWrapValueWithTags(array $setting)
    {
        $this->highlighter->setWrapValueWithTags($setting);
    }

    /**
     * Add weighted definition for booster
     * @param array $weighted
     */
    public function addWeightedBoosts(array $weighted)
    {
        $this->booster->setWeighted($weighted);
    }

    /**
     * {@inheritdoc}
     */
    public function buildAnalysis(AnalysisBuilder $analysisBuilder)
    {
        foreach ($this->getHandlers('Analysis') as $analysis) {
            $analysis->buildAnalysis($analysisBuilder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildMapping(Mapping $mapping)
    {
        foreach ($this->getFtsFields($mapping->getModule()) as $field => $defs) {
            foreach ($this->getHandlers('Mapping') as $handler) {
                $handler->buildMapping($mapping, $field, $defs);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean)
    {
        foreach ($this->getHandlers('ProcessDocument') as $handler) {
            $handler->processDocumentPreIndex($document, $bean);
        }
    }

    /**
     * Return a list of sugar types for the studio
     * @return array
     */
    public function getStudioSupportedTypes()
    {
        return $this->studioSupportedTypes;
    }


    /**
     * Get fts field defs
     * @param string $module
     * @return array
     */
    protected function getFtsFields($module)
    {
        return $this->container->metaDataHelper->getFtsFields($module);
    }

    /**
     * Get module list for user
     * @return array
     */
    protected function getUserModules()
    {
        return $this->container->metaDataHelper->getAvailableModulesForUser($this->user);
    }

    /**
     * Add the filters to the query.
     * @param QueryBuilder $builder the query builder
     */
    protected function handleSearchFilters($builder)
    {
        if (!empty($this->filters)) {
            // Apply the filters so both the query results and facets/aggregation results are filtered
            foreach ($this->filters as $filter) {
                $builder->addFilter($filter);
            }
        }
    }

    /**
     * Add search fields per module
     * @param $sf object the search field object
     * @param $module string the name of the module
     */
    protected function buildSearchFieldsPerModule($sf, $module)
    {
        foreach ($this->getFtsFields($module) as $field => $defs) {
            // skip fields which are not searchable
            if (!$this->container->metaDataHelper->isFieldSearchable($defs)) {
                continue;
            }

            // pass through handlers
            foreach ($this->getHandlers('SearchFields') as $handler) {
                $handler->buildSearchFields($sf, $module, $field, $defs);
            }
        }
    }

    /**
     * Get search field wrapper
     * @param array $modules List of modules
     * @return SearchFields
     */
    public function buildSearchFields(array $modules)
    {
        $sfs = new SearchFields($this->fieldBoost ? $this->booster : null);
        foreach ($modules as $module) {
            $this->buildSearchFieldsPerModule($sfs, $module);
        }
        return $sfs;
    }

    /**
     * Check if the field's type is valid for indexing
     * @param $type string the type of the field
     * @param $fromQueue boolean an indicator of being from queue or not
     * @return bool
     */
    public function isValidTypeField($type, $fromQueue)
    {
        // skip unsupported fields
        if (!$this->isSupportedType($type)) {
            return false;
        }

        // filter fields which need to be skipped when called from queue
        if ($fromQueue && $this->isSkippedType($type)) {
            return false;
        }

        return true;
    }

    /**
     * {inheritdoc}
     */
    public function getBeanIndexFields($module, $fromQueue = false)
    {
        $indexFields = array();

        foreach ($this->getFtsFields($module) as $field => $defs) {
            $type = $defs['type'];

            if (!$this->isValidTypeField($type, $fromQueue)) {
                continue;
            }

            $indexFields[$field] = $type;
        }

        return $indexFields;
    }

    //// Search interface

    /**
     * @var string Search term
     */
    protected $term;

    /**
     * @var array Module list
     */
    protected $modules = array();

    /**
     * @var integer
    */
    protected $limit = 20;

    /**
     * @var integer
     */
    protected $offset = 0;

    /**
     * @var boolean the flag of getting tags.
     */
    protected $getTags = array();

    /**
     * @var array the name of the "Tags" Module
     */
    protected $tagModule = 'Tags';

    /**
     * @var integer
     */
    protected $tagLimit = 5;

    /**
     * @var array the list of filters
     */
    protected $filters = array();

    /**
     * @var boolean Apply field level boosts
    */
    protected $fieldBoost = false;

    /**
     * @var boolean Apply highlighter
     */
    protected $useHighlighter = false;

    /**
     * @var array Sort fields
     */
    protected $sort = array('_score');

    /**
     * @var boolean Execute explain on query
     */
    protected $explain = false;

    /**
     * Set search term
     * @param string $term Search term
     * @return GlobalSearch
     */
    public function term($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Set modules to search for
     * @param array $modules
     * @return GlobalSearch
     */
    public function from(array $modules)
    {
        $this->modules = $modules;
        return $this;
    }

    /**
     * Set limit (query size)
     * @param integer $limit
     * @return GlobalSearch
     */
    public function limit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     * Set offset
     * @param integer $offset
     * @return GlobalSearch
     */
    public function offset($offset)
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     * Set the flag of getting tags.
     * @param boolean $getTags
     * @return GlobalSearch
     */
    public function getTags($getTags)
    {
        if (!empty($getTags)) {
            $this->getTags = $getTags;
        }
        return $this;
    }

    /**
     * Set the size of tags in the response.
     * @param integer $tagLimit
     * @return GlobalSearch
     */
    public function setTagLimit($tagLimit)
    {
        $this->tagLimit = (int) $tagLimit;
        return $this;
    }

    /**
     * Set the list of filters filtering.
     * @param array $filters
     * @return GlobalSearch
     */
    public function setFilters(array $filters)
    {
        if (!empty($filters)) {
            $this->filters = $filters;
        }
        return $this;
    }

    /**
     * Enable field boosts (disabled by default)
     * @param boolean $toggle
     * @return GlobalSearch
     */
    public function fieldBoost($toggle)
    {
        $this->fieldBoost = (bool) $toggle;
        return $this;
    }

    /**
     * Enable/disable highlighter (disabled by default)
     * @param boolean $toggle
     * @return GlobalSearch
     */
    public function useHighlighter($toggle)
    {
        $this->useHighlighter = (bool) $toggle;
        return $this;
    }

    /**
     * Set order by field
     * @param string $field
     * @return GlobalSearch
     */
    public function sort(array $fields)
    {
        if ($fields === array() || $fields === array('_score')) {
            $this->sort = array('_score');
            return $this;
        }

        $sortFields = array();
        foreach ($fields as $field => $order) {
            $sortFields[$field] = array(
                'order' => $order,
                'missing' => '_last',
            );
        }
        $this->sort = $sortFields;

        // when sorting is requested other than the default we dont need boosting
        $this->fieldBoost = false;
        return $this;
    }

    /**
     * Set query explain flag
     * @param boolean $flag
     * @return GlobalSearch
     */
    public function setExplain($flag)
    {
        $this->explain = (bool) $flag;
        return $this;
    }

    protected function handleSearchAggregations(QueryBuilder $builder)
    {
        if ($this->queryCrossModuleAggs || $this->queryModuleAggs) {
            $builder->setAggFilterDefs($this->aggFilters);
            $this->addAggregations($builder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function search()
    {
        // Make sure modules are selected
        if (empty($this->modules)) {
            $this->modules = $this->getUserModules();
        }

        if (!empty($this->term)) {
            // create a multi-match query with terms
            $query = $this->createMultiMatchQuery();
        } else {
            // If no query term is passed in we use a MatchAll and try to
            // order by the common date_modified field
            $query = new MatchAllQuery();
            $this->sort([Mapping::PREFIX_COMMON . 'date_modified.gs_datetime' => 'desc']);
            $this->useHighlighter = false;
        }

        $builder = new QueryBuilder($this->container);
        $builder
            ->setUser($this->user)
            ->setModules($this->modules)
            ->setLimit($this->limit)
            ->setOffset($this->offset)
            ->setQuery($query)
            ->setExplain($this->explain)
            ->setResultParser($this->resultParser)
        ;

        // Set highlighter
        if ($this->useHighlighter) {
            $builder->setHighLighter($this->highlighter);
        }

        // Set sorting
        if ($this->sort) {
            $builder->setSort($this->sort);
        }

        // Add aggregations
        $this->handleSearchAggregations($builder);

        // Add the filters
        $this->handleSearchFilters($builder, $this->filters);


        return $builder->executeSearch();
    }

    /**
     * {@inheritdoc}
     */
    public function searchTags()
    {
        //create a module list including the tag module only
        $this->modules = array($this->tagModule);

        $multiMatch = $this->createMultiMatchQuery();

        $builder = new QueryBuilder($this->container);
        $builder
            ->setUser($this->user)
            ->setModules($this->modules)
            ->setLimit($this->tagLimit)
            ->setQuery($multiMatch)
        ;

        // Set sorting
        if ($this->sort) {
            $builder->setSort($this->sort);
        }
        return $builder->executeSearch();
    }

    /**
     * Create Multi Match query object
     * @return MultiMatchQuery
     */
    protected function createMultiMatchQuery()
    {
        $multiMatch = new MultiMatchQuery();
        $multiMatch->setTerms($this->term);
        $multiMatch->setOperator($this->getDefaultOperator());
        $multiMatch->setVisibilityProvider($this->container->getProvider('Visibility'));

        $modules = $this->modules;
        //when searching on a specific module, include tags if necessary
        if ($this->getTags && !in_array($this->tagModule, $modules)) {
            $modules[] = $this->tagModule;
        }

        $multiMatch->setSearchFields($this->buildSearchFields($modules));
        $multiMatch->setUser($this->user);
        return $multiMatch;
    }

    //// Aggregations

    /**
     * Get cross module aggregations
     * @var boolean
     */
    protected $queryCrossModuleAggs = false;

    /**
     * List of aggregation filters
     * @var array
     */
    protected $aggFilters = array();

    /**
     * List of modules for which to get the aggregations
     * @var array
     */
    protected $queryModuleAggs = array();

    /**
     * Enable/disable cross module aggregations
     * @param boolean $toggle
     */
    public function queryCrossModuleAggs($toggle)
    {
        $this->queryCrossModuleAggs = (bool) $toggle;
    }

    /**
     * Set modules to get aggregations for
     * @param array $modules
     */
    public function queryModuleAggs(array $modules)
    {
        $this->queryModuleAggs = $modules;
    }

    /**
     * Get cross module aggregation flag
     * @return boolean
     */
    public function getQueryCrossModuleAggs()
    {
        return $this->queryCrossModuleAggs;
    }

    /**
     * Get list of modules to generate aggregations for
     * @return array
     */
    public function getQueryModuleAggs()
    {
        return $this->queryModuleAggs;
    }

    /**
     * Set aggregation filters
     * @param array $filters
     */
    public function aggFilters(array $filters)
    {
        $this->aggFilters = $filters;
    }

    /**
     * Add aggregations through available handlers
     * @param QueryBuilder $builder
     */
    protected function addAggregations(QueryBuilder $builder)
    {
        foreach ($this->getHandlers('Aggregation') as $handler) {
            $handler->addAggregations($builder);
        }
    }
}
