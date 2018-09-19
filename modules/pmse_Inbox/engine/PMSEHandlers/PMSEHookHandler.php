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
use Sugarcrm\Sugarcrm\ProcessManager\Registry;

class PMSEHookHandler extends PMSEAbstractRequestHandler
{
    /**
     * @inheritDoc
     */
    protected $requestType = 'hook';

    /**
     * Sugar logger object
     * @var LoggerManager
     */
    protected $sugarLogger;

    /**
     * The ProcessManager Registry object
     * @var Registry
     */
    protected $registry;

    /**
     * List of reasons that processes could be disabled
     * @var array
     */
    protected $disablers = [
        'setup' => 'Perform Setup',
        'upgrade' => 'Upgrade',
    ];

    /**
     * Sets the Sugar logger object
     * @param LoggerManager $logger
     * @codeCoverageIgnore
     */
    public function setSugarLogger(LoggerManager $logger)
    {
        $this->sugarLogger = $logger;
    }

    /**
     * Gets the Sugar Logger object
     * @return LoggerManager
     * @codeCoverageIgnore
     */
    public function getSugarLogger()
    {
        if (empty($this->sugarLogger)) {
            $this->sugarLogger = LoggerManager::getLogger();
        }

        return $this->sugarLogger;
    }

    /**
     * @inheritDoc
     */
    public function executeRequest($args = array(), $createThread = false, $bean = null, $externalAction = '')
    {
        // If we are disabled we need to bail immediately
        if (!$this->isEnabled()) {
            return;
        }

        // Otherwise, pass through
        return parent::executeRequest($args, $createThread, $bean, $externalAction);
    }

    /**
     *
     * @global type $db
     * @global type $redirectBeforeSave
     * @param type $bean
     * @param type $event
     * @param type $arguments
     * @param type $startEvents
     * @param type $isNewRecord
     * @return boolean
     */
    public function runStartEventAfterSave($bean, $event, $arguments)
    {
        // If we are disabled we need to bail immediately
        if (!$this->isEnabled()) {
            return;
        }

        $this->executeRequest($arguments, false, $bean, '');
    }


    public function terminateCaseAfterDelete($bean, $event, $arguments)
    {
        // If we are disabled we need to bail immediately
        if (!$this->isEnabled()) {
            return;
        }

        $this->executeRequest($arguments, false, $bean, 'TERMINATE_CASE');
    }

    /**
     * Execute the cron tasks.
     */
    public function executeCron()
    {
        // If we are disabled we need to bail immediately
        if (!$this->isEnabled()) {
            return;
        }

        $this->wakeUpSleepingFlows();
    }

    /**
     * Execute all the flows marked as SLEEPING
     */
    protected function wakeUpSleepingFlows()
    {
        // Needed for the query
        $today = TimeDate::getInstance()->nowDb();

        // We will need this for quoting strings
        $db = DBManagerFactory::getInstance();

        // Used in the get full list process
        $addedSQL = 'bpmn_type = ' . $db->quoted('bpmnEvent') .
                    ' AND cas_flow_status = ' . $db->quoted('SLEEPING') .
                    ' AND cas_due_date <= ' . $db->quoted($today);

        $bean = BeanFactory::newBean('pmse_BpmFlow');
        $flows = $bean->get_full_list('', $addedSQL);

        // If there were flows to process, handle that
        if ($flows !== null && ($c = count($flows)) > 0) {
            foreach ($flows as $flow) {
                $this->newFollowFlow($flow->fetched_row, false, null, 'WAKE_UP');
            }

            $this->getLogger()->info("Processed $c flows with status sleeping");
        } else {
            $this->getLogger()->info("No flows processed with status sleeping");
        }
    }

    protected function newFollowFlow($flowData, $createThread = false, $bean = null, $externalAction = '')
    {
        Registry\Registry::getInstance()->drop('triggered_starts');
        $fr = ProcessManager\Factory::getPMSEObject('PMSEExecuter');
        return $fr->runEngine($flowData, $createThread, $bean, $externalAction);
    }

    /**
     * Writes a log message to the sugar logger and the PMSE logger. Used primarily
     * on installation and on upgrade.
     * @param string $msg The message to log
     */
    protected function writeLog($msg)
    {
        $this->getLogger()->alert($msg);
        $this->getSugarLogger()->error($msg);
    }

    /**
     * Checks to see if processes are enabled
     * @return boolean
     */
    protected function isEnabled()
    {
        // Get the registry object
        $registry = Registry\Registry::getInstance();

        foreach ($this->disablers as $type => $by) {
            $key = "$type:disable_processes";
            $d = $registry->get($key);
            if ($d !== null && $d !== false) {
                $this->writeLog("Process workflows are currently disabled by $by.");
                return false;
            }
        }

        return true;
    }
}
