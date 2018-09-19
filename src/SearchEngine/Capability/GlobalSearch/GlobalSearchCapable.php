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

namespace Sugarcrm\Sugarcrm\SearchEngine\Capability\GlobalSearch;

use Sugarcrm\Sugarcrm\SearchEngine\Engine\EngineInterface;

/**
 *
 * GlobalSearch capability
 *
 */
interface GlobalSearchCapable extends EngineInterface
{
    /**
     * Execute search
     * @return ResultSetInterface
     */
    public function search();

    /**
     * Execute search tags
     * @return ResultSetInterface
     */
    public function searchTags();

    /**
     * Set search term string
     * @param string $term Search term
     * @return GlobalSearchInterface
     */
    public function term($term);

    /**
     * Set modules to search for
     * @param array $modules
     * @return GlobalSearchInterface
     */
    public function from(array $modules = array());

    /**
     * Set the flag of getting tags
     * @param boolean $getTags
     * @return GlobalSearchInterface
     */
    public function getTags($getTags);

    /**
     * Set the size of tags in the response
     * @param integer $tagLimit
     * @return GlobalSearchInterface
     */
    public function setTagLimit($tagLimit);

    /**
     * Set the list of filters for filtering.
     * @param array $filters
     * @return GlobalSearchInterface
     */
    public function setFilters($filters);

    /**
     * Set limit (query size)
     * @param integer $limit
     * @return GlobalSearchInterface
     */
    public function limit($limit);

    /**
     * Set offset
     * @param integer $offset
     * @return GlobalSearchInterface
     */
    public function offset($offset);

    /**
     * Enable/disable highlighter (disabled by default)
     * @param boolean $toggle
     * @return GlobalSearchInterface
     */
    public function highlighter($toggle);

    /**
     * Enable/disable field boost (disabled by default)
     * @param boolean $toggle
     * @return GlobalSearchInterface
     */
    public function fieldBoost($toggle);

    /**
     * Set field sorting (default to relevance)
     * @param array $fields List of fields and order
     * @return GlobalSearchInterface
     */
    public function sort(array $fields);

    /**
     * Return a list of supported sugar types for the studio
     * @return array
     */
    public function getStudioSupportedTypes();
}
