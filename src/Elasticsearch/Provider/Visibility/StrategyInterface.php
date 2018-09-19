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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\Visibility;

use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use SugarBean;
use User;

/**
 *
 * Visibility strategy interface
 *
 */
interface StrategyInterface
{
    /**
     * Build Elasticsearch analysis settings
     * @param AnalysisBuilder $analysisBuilder
     * @param Visibility $provider
     */
    public function elasticBuildAnalysis(AnalysisBuilder $analysisBuilder, Visibility $provider);

    /**
     * Build Elasticsearch mapping
     * @param Mapping $mapping
     * @param Visibility $provider
     */
    public function elasticBuildMapping(Mapping $mapping, Visibility $provider);

    /**
     * Process document before its being indexed
     * @param Document $document
     * @param SugarBean $bean
     * @param Visibility $provider
     */
    public function elasticProcessDocumentPreIndex(Document $document, SugarBean $bean, Visibility $provider);

    /**
     * Bean index fields to be indexed
     * @param string $module
     * @param Visibility $provider
     * @return array
     */
    public function elasticGetBeanIndexFields($module, Visibility $provider);

    /**
     * Add visibility filters
     * @param User $user
     * @param \Elastica\Query\BoolQuery $filter
     * @param Visibility $provider
     */
    public function elasticAddFilters(User $user, \Elastica\Query\BoolQuery $filter, Visibility $provider);
}
