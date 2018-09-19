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

/**
 *
 * Result interface for GlobalSearch capability
 *
 */
interface ResultInterface
{
    /**
     * Get module name
     * @return string
     */
    public function getModule();

    /**
     * Get record id
     * @return string
     */
    public function getId();

    /**
     * Get raw key/value pair data
     * @return array
     */
    public function getData();

    /**
     * Get list of available result fields
     * @return array
     */
    public function getDataFields();

    /**
     * Get score
     * @return float
     */
    public function getScore();

    /**
     * Get highlights
     * @return array
     */
    public function getHighlights();

    /**
     * Get SugarBean
     * @param boolean $retrieve When true, perform a database retrieve
     *      disregarding the data collected from the search engine backend.
     *      For best performance do not use retrieve mode.
     * @return \SugarBean
     */
    public function getBean($retrieve = false);
}
