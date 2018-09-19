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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Adapter;

use Elastica\Index as BaseIndex;
use Elastica\Type;

/**
 *
 * Adapter class for \Elastica\Index
 *
 */
class Index extends BaseIndex
{
    /**
     * The base name of the index without normalization applied. The actual
     * index name is based on prefixes and other logic which can be applied
     * by the IndexPool.
     *
     * @var string
     */
    protected $baseName = '';

    /**
     * List of available types
     * @var array
     */
    protected $types = array();

    /**
     *
     * @param string $name Index name
     */
    public function __construct(Client $client, $name)
    {
        parent::__construct($client, $name);
    }

    /**
     * Set base name
     * @param string $baseName
     */
    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;
    }

    /**
     * Get base name
     * @return string
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * Add type to the stack
     * @param array $types
     */
    public function addType($type)
    {
        $this->types[$type] = new Type($this, $type);
    }

    /**
     * Get available types
     * @return \Elastica\Type[]
     */
    public function getTypes()
    {
        return $this->types;
    }
}
