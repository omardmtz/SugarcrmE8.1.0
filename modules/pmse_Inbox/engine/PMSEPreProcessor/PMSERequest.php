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
 * Request data is passed along from the pre-processor to the engine layers
 * @codeCoverageIgnore
 */
class PMSERequest
{
    /**
     *
     * @var SugarBean
     */
    protected $bean;

    /**
     *
     * @var boolean
     */
    protected $createThread;

    /**
     *
     * @var string
     */
    protected $externalAction;

    /**
     *
     * @var array
     */
    protected $arguments;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var array
     */
    protected $validTypes = array('hook', 'direct', 'cron', 'queue', 'engine');

    /**
     *
     * @var boolean
     */
    protected $validated;

    /**
     *
     * @var string
     */
    protected $status;

    /**
     *
     * @var array
     */
    protected $flowData;

    /**
     *
     * @var array
     */
    protected $result;

    /**
     * Class constructor
     */
    public function __construct()
    {
        // start clean
        $this->reset();
    }

    /**
     * Sets the request object to clean validation state
     */
    public function reset()
    {
        $this->status = 'VALID';
        $this->validated = true;
        $this->result = '';
    }

    /**
     *
     * @return type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @return type
     */
    public function getCreateThread()
    {
        return $this->createThread;
    }

    /**
     *
     * @return SugarBean
     */
    public function getBean()
    {
        return $this->bean;
    }

    /**
     *
     * @return type
     */
    public function getExternalAction()
    {
        return $this->externalAction;
    }

    /**
     *
     * @return type
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     *
     * @return type
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @return type
     */
    function getResult()
    {
        return $this->result;
    }

    /**
     *
     * @param type $result
     */
    function setResult($result)
    {
        $this->result = $result;
    }

    /**
     *
     * @param type $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     *
     * @param type $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     *
     * @return type
     */
    public function getFlowData()
    {
        return $this->flowData;
    }

    /**
     *
     * @param type $flowData
     */
    public function setFlowData($flowData)
    {
        $this->flowData = $flowData;
    }

    /**
     *
     * @param type $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     *
     * @param type $createThread
     */
    public function setCreateThread($createThread)
    {
        $this->createThread = $createThread;
    }

    /**
     *
     * @param type $bean
     */
    public function setBean($bean)
    {
        $this->bean = $bean;
    }

    /**
     *
     * @param type $externalAction
     */
    public function setExternalAction($externalAction)
    {
        $this->externalAction = $externalAction;
    }

    /**
     * Validate request
     */
    public function validate()
    {
        $this->status = 'VALID';
        $this->validated = true;
    }

    /**
     * Invalidate request
     */
    public function invalidate()
    {
        $this->status = 'INVALID';
        $this->validated = false;
    }

    /**
     * Check if request is valid or not
     * @return type
     */
    public function isValid()
    {
        return $this->validated;
    }
}
