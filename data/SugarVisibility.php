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

use Sugarcrm\Sugarcrm\Bean\Visibility\Strategy;

/**
 * Base class for visibility implementations
 * @api
 */
abstract class SugarVisibility implements Strategy
{
    /**
     * Bean context
     * @var SugarBean
     */
    protected $bean;

    /**
     * Strategy params
     * @var mixed
     */
    protected $params;

    /**
     * @var LoggerManager
     */
    protected $log;

    /**
     * Options for this run
     * @var array|null
     */
    protected $options;

    /**
     * @param SugarBean $bean
     * @param mixed $params Strategy params
     */
    public function __construct(SugarBean $bean, $params = null)
    {
        $this->bean = $bean;
        $this->params = $params;
        $this->log = LoggerManager::getLogger();
    }

    /**
     * Add visibility clauses to the FROM part of the query
     * @param string $query
     * @return string
     *
     * @deprecated Use SugarQuery and SugarVisibility::addVisibilityQuery() instead
     */
    public function addVisibilityFrom(&$query)
    {
        return $query;
    }

    /**
     * Add visibility clauses to the WHERE part of the query
     * @param string $query
     * @return string
     *
     * @deprecated Use SugarQuery and SugarVisibility::addVisibilityQuery() instead
     */
    public function addVisibilityWhere(&$query)
    {
        return $query;
    }

    /**
     * Add visibility clauses to the FROM part of SugarQuery
     * @param SugarQuery $query
     * @return SugarQuery
     *
     * @deprecated Implement SugarVisibility::addVisibilityQuery() instead
     */
    public function addVisibilityFromQuery(SugarQuery $query)
    {
        return $query;
    }

    /**
     * Add visibility clauses to the WHERE part of SugarQuery
     * @param SugarQuery $query
     * @return SugarQuery
     *
     * @deprecated Implement SugarVisibility::addVisibilityQuery() instead
     */
    public function addVisibilityWhereQuery(SugarQuery $query)
    {
        return $query;
    }

    /**
     * Add visibility to SugarQuery
     *
     * @param SugarQuery $query
     */
    public function addVisibilityQuery(SugarQuery $query)
    {
        $this->addVisibilityFromQuery($query);
        $this->addVisibilityWhereQuery($query);
    }

    /**
     * {@inheritDoc}
     */
    public function applyToFrom(DBManager $db, $query, $table)
    {
        $this->options['table_alias'] = $table;
        return $this->addVisibilityFrom($query);
    }

    /**
     * {@inheritDoc}
     */
    public function applyToWhere(DBManager $db, $query, $table)
    {
        $this->options['table_alias'] = $table;
        return $this->addVisibilityWhere($query);
    }

    /**
     * {@inheritDoc}
     */
    public function applyToQuery(SugarQuery $query, $table)
    {
        $this->options['table_alias'] = $table;
        $this->addVisibilityQuery($query);
    }

    /**
     * Get visibility option
     * @param string $name
     * @param mixed $default Default value if option not set
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        if ($name === 'action' && $default !== null) {
            $GLOBALS['log']->warn('Relying on the default action in SugarVisibility is discouraged');
        }

        return $default;
    }

    /**
     * Set visibility options
     * @param array|null $options
     * @return SugarVisibility
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Called before the bean is indexed so that any calculated attributes can
     * updated. Override to implement visibility related attribute updates
     * before the bean is indexed.
     * @return void
     * @deprecated
     */
    public function beforeSseIndexing()
    {
        $GLOBALS['log']->deprecated("SugarVisibility::beforeSseIndexing is deprecated !");
    }

    /**
     * Apply visibility filters for SugarSearchEngine
     * @param SugarSearchEngineInterface $engine Sugar search engine objects
     * @param mixed $filter
     * @return mixed
     * @deprecated
     */
    public function addSseVisibilityFilter(SugarSearchEngineInterface $engine, $filter)
    {
        $GLOBALS['log']->deprecated("SugarVisibility::addSseVisibilityFilter is deprecated !");
        return $filter;
    }
}
