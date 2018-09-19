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

use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Query\QueryBuilder;

/**
 *
 * SearchField object
 *
 */
class SearchField
{
    /**
     * Module name
     * @var string
     */
    private $module;

    /**
     * Original bean field name
     * @var string
     */
    private $field;

    /**
     * Field definitions
     * @var array
     */
    private $defs = [];

    /**
     * Search path
     * @var array
     */
    private $path = [];

    /**
     * Boost value
     * @var float
     */
    private $boost;

    /**
     * Ctor
     * @param string $module
     * @param string $field
     * @param array $defs
     */
    public function __construct($module, $field, array $defs)
    {
        $this->module = $module;
        $this->field = $field;
        $this->defs = $defs;
    }

    /**
     * Compile search field value
     * @return string
     */
    public function compile()
    {
        $path = empty($this->path) ? [$this->field] : $this->path;
        $field = $this->module . Mapping::PREFIX_SEP . implode('.', $path);
        if ($this->boost !== null) {
            $field .= QueryBuilder::BOOST_SEP . $this->boost;
        }
        return $field;
    }

    /**
     * Get module
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Get field name
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get field defs
     * @return array
     */
    public function getDefs()
    {
        return $this->defs;
    }

    /**
     * Set search path
     * @param array $path
     * @return SearchField
     */
    public function setPath(array $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set boost value
     * @param float $boost
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }
}
