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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Query\Result;

/**
 *
 * Result parser interface which can be attached to the
 * Result(Set) objects to perform additional parsing on
 * the results coming back from Elasticsearch queries.
 *
 */
interface ParserInterface
{
    /**
     * Parse the _source valuse from Result and
     * return the parsed _source array
     *
     * @param \Elastica\Result $result
     * @return array
     */
    public function parseSource(\Elastica\Result $result);

    /**
     * Parse the _highlights from Result and
     * return the parsed _highlight array
     *
     * @param Result $result
     * @return array
     */
    public function parseHighlights(\Elastica\Result $result);
}
