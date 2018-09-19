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
 * Collection definition for Collection API
 */
interface CollectionDefinitionInterface
{
    /**
     * Returns available collection source names
     *
     * @return string[] Source names
     */
    public function getSources();

    /**
     * Checks if there is a field map specified for the given source
     *
     * @param string $source Source name
     * @return boolean
     */
    public function hasFieldMap($source);

    /**
     * Returns the field map specified for the given source
     *
     * @param string $source Source name
     * @return array
     */
    public function getFieldMap($source);

    /**
     * Checks if there is a filter for the given source
     *
     * @param string $source Source name
     * @return boolean
     */
    public function hasSourceFilter($source);

    /**
     * Returns the filter specified for the given source
     *
     * @param string $source Source name
     * @return array
     */
    public function getSourceFilter($source);

    /**
     * Checks if there is a stored filter with the given name
     *
     * @param string $id Filter ID
     * @return boolean
     */
    public function hasStoredFilter($id);

    /**
     * Returns the stored filter definition
     *
     * @param string $id Filter ID
     * @return array
     */
    public function getStoredFilter($id);

    /**
     * Returns default ORDER BY expression for the collection, or NULL if not specified
     *
     * @return string|null ORDER BY expression
     */
    public function getOrderBy();

    /**
     * Returns the module name corresponding to the given collection source
     *
     * @param string $source Source name
     * @return string Module name
     */
    public function getSourceModuleName($source);
}
