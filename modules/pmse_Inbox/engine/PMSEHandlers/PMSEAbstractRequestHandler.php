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

/**
 * Abstract class that defines the basics of a request handler in Advanced Workflow
 * @codeCoverageIgnore
 */
abstract class PMSEAbstractRequestHandler
{
    /**
     * Defined by the child classes, will load the appropriate request handler
     * @var string
     */
    protected $requestType;

    /**
     * The PMSE Preprocessor object
     * @var PMSEPreProcessor
     */
    protected $preProcessor;

    /**
     * The PMSE Logger object
     * @var PMSELogger
     */
    protected $logger;

    /**
     * The PMSE Request object
     * @var PMSERequest
     */
    protected $request;

    /**
     * Executes a request
     * @param array $args Arguments used in handling the request
     * @param boolean $createThread Whether to create a new thread for processes
     * @param SugarBean $bean Affected bean, if there is one
     * @param string $externalAction Additional action to take
     * @return void
     */
    public function executeRequest($args = array(), $createThread = false, $bean = null, $externalAction = '')
    {
        // Get the request object setup
        $request = $this->getRequest();

        // Set the necessary request properties
        $request->setArguments($args);
        $request->setCreateThread($createThread);
        $request->setBean($bean);
        $request->setExternalAction($externalAction);

        // Preprocessor doesn't actually return anything
        return $this->getPreProcessor()->processRequest($request);
    }

    /**
     * Allows for setting of the request type
     * @param string $type The PMSE Request object type
     */
    public function setRequestType($type)
    {
        $this->requestType = $type;
        $this->request->setType($this->requestType);
    }

    /**
     * Gets the request object type
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * Sets the request object
     * @param PMSERequest $request
     * @codeCoverageIgnore
     */
    public function setRequest(PMSERequest $request)
    {
        $this->request = $request;
    }

    /**
     * Gets the PMSE request object
     * @return PMSERequest
     * @codeCoverageIgnore
     */
    public function getRequest()
    {
        if (empty($this->request)) {
            $this->request = ProcessManager\Factory::getPMSEObject('PMSERequest');
            $this->request->setType($this->requestType);
        }

        return $this->request;
    }

    /**
     * Sets the PMSE PreProcessor object
     * @param PMSEPreProcessor $preProcessor
     * @codeCoverageIgnore
     */
    public function setPreProcessor(PMSEPreProcessor $preProcessor)
    {
        $this->preProcessor = $preProcessor;
    }

    /**
     * Gets the PMSE PreProcessor object
     * @return PMSEPreProcessor
     * @codeCoverageIgnore
     */
    public function getPreProcessor()
    {
        if (empty($this->preProcessor)) {
            $this->preProcessor = PMSEPreProcessor::getInstance();
        }

        return $this->preProcessor;
    }

    /**
     * Sets the PMSE logger object
     * @param PMSELogger $logger
     * @codeCoverageIgnore
     */
    public function setLogger(PMSELogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets the PMSE Logger object
     * @return PMSELogger
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        if (empty($this->logger)) {
            $this->logger = PMSELogger::getInstance();
        }

        return $this->logger;
    }
}
