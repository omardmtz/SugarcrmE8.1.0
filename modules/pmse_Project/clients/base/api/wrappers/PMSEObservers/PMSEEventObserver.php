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


use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEEventObserver implements PMSEObserver
{

    /**
     *
     * @var type
     */
    protected $relatedDependency;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->relatedDependency = ProcessManager\Factory::getPMSEObject('PMSERelatedDependencyWrapper');
        $this->logger = PMSELogger::getInstance();
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getRelatedDependency()
    {
        return $this->relatedDependency;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     *
     * @param type $relatedDependency
     * @codeCoverageIgnore
     */
    public function setRelatedDependency($relatedDependency)
    {
        $this->relatedDependency = $relatedDependency;
    }

    /**
     *
     * @param PMSELogger $logger
     * @codeCoverageIgnore
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param PMSEObservable $subject
     */
    public function update($subject)
    {
        if (method_exists($subject, 'getEventDefinition')) {
            $this->logger->debug("Trigger update of a Related Relationship for a Event Definition update");
            $event = $subject->getEvent();
            $eventData = $event->fetched_row;
            $eventDefinition = $subject->getEventDefinition();
            $eventDefinitionData = $eventDefinition->fetched_row;
            $processDefinition = $subject->getProcessDefinition();
            $processDefinitionData = ($processDefinition->fetched_row) ? $processDefinition->fetched_row : array();
            $completeData = $eventData + $eventDefinitionData + $processDefinitionData;
            $this->relatedDependency->processRelatedDependencies($completeData);
        }
    }

}
