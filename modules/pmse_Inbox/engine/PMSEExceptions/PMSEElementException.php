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
 * Define a custom exception class
 */
class PMSEElementException extends Exception
{
    protected $flowData;
    protected $element;

    // Redefine the exception so message isn't optional
    public function __construct($message, $flowData, $element, $code = 0, Exception $previous = null)
    {
        // some code
        $flowData['cas_flow_status'] = 'ERROR';
        $this->flowData = $flowData;
        $this->element = $element;
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
        $this->logMessage();
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getFlowData()
    {
        return $this->flowData;
    }

    public function getElement()
    {
        return $this->element;
    }

    public function logMessage()
    {
        // Since we need to log our exceptions, let's get the logger

        $logMessage = get_class($this) . ' : ' . $this->message;

        // Log it
        PMSELogger::getInstance()->alert($logMessage);
    }
}
