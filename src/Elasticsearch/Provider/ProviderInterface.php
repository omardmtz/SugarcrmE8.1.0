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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider;

use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document;

/**
 *
 * Provider interface
 *
 */
interface ProviderInterface
{
    /**
     * Set provider name.
     *
     * Providers are instantiated by the service container. Each provider
     * has an identifier know to the container which is set through this
     * method.
     *
     * @param string $identifier
     */
    public function setIdentifier($identifier);

    /**
     * Get provider identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Set user context
     *
     * @param \User $user
     */
    public function setUser(\User $user);

    /**
     * Create analysis settings.
     *
     * Each provider can register custom analyzers which can be used when
     * building the mapping for a given provider. This method will be called
     * by the IndexManager passing in the AnalysisBuilder object on top
     * of which the provider can register its required analyzers.
     *
     * @param \Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder $analysisBuilder
     */
    public function buildAnalysis(AnalysisBuilder $analysisBuilder);

    /**
     * Create mapping for given mapping context.
     *
     * This method is responsible to register the field mappings as needed
     * by the provider. The mapping object itself represents a specific
     * module (Elasticsearch type). The MappingManager will call this
     * method for every full text search enabled module.
     *
     * @param \Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping\Mapping $mapping
     */
    public function buildMapping(Mapping $mapping);

    /**
     * Process document before indexing.
     *
     * When a bean is indexed into Elasticsearch an Elastica Document is
     * created containing by default all enabled fields for the given bean.
     * Before sending the Document to the Elasticsearch backend, the provider
     * has the ability to add additional properties on the it. The original
     * bean is also passed in as a reference but only changes on the Document
     * object should be made within the provider. This method is called by
     * the Indexer implicitly.
     *
     * @param \Sugarcrm\Sugarcrm\Elasticsearch\Adapter\Document $document
     * @param \SugarBean $bean
     */
    public function processDocumentPreIndex(Document $document, \SugarBean $bean);

    /**
     * Returns the list of fields to be indexed associated with its sugar type.
     * The optional $fromQueue parameter is set by QueueManager when creating
     * the query to retrieve bean values from the database. I certain cases we
     * do not want to include specific fields (like emails) as they will get
     * populated in a later stage. This flag gives us the opportunity to work
     * around corner cases avoiding heavy bulk queries retrieving data we
     * don't really need at this point.
     *
     * @param string  $module    Module name
     * @param boolean $fromQueue Set if coming from QueueManager
     * @return array
     */
    public function getBeanIndexFields($module, $fromQueue = false);
}
