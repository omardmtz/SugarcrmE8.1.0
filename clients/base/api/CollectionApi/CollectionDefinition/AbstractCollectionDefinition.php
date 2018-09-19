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
 * Base implementation of collection definition
 */
abstract class AbstractCollectionDefinition implements CollectionDefinitionInterface
{
    /**
     * The key in collection definition that identifies sources
     *
     * @var string
     */
    protected static $sourcesKey = 'sources';

    /**
     * Collection name
     *
     * @var string
     */
    protected $name;

    /**
     * Source definitions
     *
     * @var array
     */
    protected $sources;

    /**
     * Stored filter definitions
     *
     * @return array
     */
    protected $storedFilters = array();

    /**
     * ORDER BY definition
     *
     * @return string|null
     */
    protected $orderBy;

    /**
     * Constructor
     *
     * @param string $name Collection name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $definition = $this->loadDefinition();
        $this->setDefinition($definition);
    }

    /** {@inheritDoc} */
    public function getSources()
    {
        return array_keys($this->sources);
    }

    /** {@inheritDoc} */
    public function hasFieldMap($source)
    {
        return isset($this->sources[$source]['field_map']);
    }

    /** {@inheritDoc} */
    public function getFieldMap($source)
    {
        if ($this->hasFieldMap($source)) {
            return $this->sources[$source]['field_map'];
        }

        return null;
    }

    /** {@inheritDoc} */
    public function hasSourceFilter($source)
    {
        return isset($this->sources[$source]['filter']);
    }

    /** {@inheritDoc} */
    public function getSourceFilter($source)
    {
        if ($this->hasSourceFilter($source)) {
            return $this->sources[$source]['filter'];
        }

        return null;
    }

    /** {@inheritDoc} */
    public function hasStoredFilter($id)
    {
        return isset($this->storedFilters[$id]);
    }

    /** {@inheritDoc} */
    public function getStoredFilter($id)
    {
        if ($this->hasStoredFilter($id)) {
            return $this->storedFilters[$id];
        }

        return null;
    }

    /** {@inheritDoc} */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Tries to load collection definition from metadata
     *
     * @return array Collection definition
     * @throws SugarApiException
     */
    abstract protected function loadDefinition();

    /**
     * Sets loaded collection definition
     *
     * @param array $definition Collection definition
     * @throws SugarApiException
     */
    protected function setDefinition(array $definition)
    {
        if (!isset($definition[static::$sourcesKey])) {
            throw new SugarApiExceptionError(
                sprintf('Sources are not defined for collection %s', $this->name)
            );
        }

        $this->sources = $this->normalizeSources($definition[static::$sourcesKey]);

        if (isset($definition['order_by'])) {
            $this->orderBy = $definition['order_by'];
        }

        if (isset($definition['filters'])) {
            foreach ($definition['filters'] as $i => $filter) {
                if (!isset($filter['id'], $filter['filter_definition'])) {
                    throw new SugarApiExceptionError(
                        sprintf('Incorrect filter #%d definition are collection %s', $i, $this->name)
                    );
                }
                $this->storedFilters[$filter['id']] = $filter['filter_definition'];
            }
        }
    }

    /**
     * Normalizes and validates source definitions
     *
     * @param array $sources
     *
     * @return array Normalized definitions
     * @throws SugarApiExceptionError
     */
    protected function normalizeSources($sources)
    {
        if (!is_array($sources)) {
            throw new SugarApiExceptionError(
                $this->getErrorMessage('Source definition must be array, %s is given for collection %s', array(
                    gettype($sources),
                    $this->name,
                ))
            );
        }

        $normalized = array();
        foreach ($sources as $i => $definition) {
            if (is_string($definition)) {
                $name = $definition;
                $definition = array();
            } elseif (is_array($definition)) {
                if (!isset($definition['name']) || !is_string($definition['name'])) {
                    throw new SugarApiExceptionError(
                        $this->getErrorMessage('Source #%d name is not defined for collection %s', array(
                            $i,
                            $this->name,
                        ))
                    );
                }
                $name = $definition['name'];
                unset($definition['name']);
            } else {
                throw new SugarApiExceptionError(
                    $this->getErrorMessage(
                        'Source definition must be string or array, %s is given for source #%d, collection %s',
                        array(
                            gettype($definition),
                            $i,
                            $this->name,
                        )
                    )
                );
            }

            $normalized[$name] = $definition;
        }

        return $normalized;
    }

    /**
     * Creates error message
     *
     * @param string $format
     * @param array $arguments
     *
     * @return string
     */
    protected function getErrorMessage($format, array $arguments)
    {
        return vsprintf($format, $arguments);
    }
}
