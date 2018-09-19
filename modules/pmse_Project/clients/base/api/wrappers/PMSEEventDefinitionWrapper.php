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

class PMSEEventDefinitionWrapper implements PMSEObservable
{
    private $event;
    private $eventDefinition;
    private $processDefinition;
    private $relatedDependency;
    private $relationship;
    protected $observers = array();

    /**
     * Class Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->event = BeanFactory::newBean('pmse_BpmnEvent');
        $this->relationship = BeanFactory::newBean('Relationships');
        $this->eventDefinition = BeanFactory::newBean('pmse_BpmEventDefinition');
        $this->processDefinition = BeanFactory::newBean('pmse_BpmProcessDefinition');
        $this->crmDataWrapper = ProcessManager\Factory::getPMSEObject('PMSECrmDataWrapper');
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getEventDefinition()
    {
        return $this->eventDefinition;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getProcessDefinition()
    {
        return $this->processDefinition;
    }

    /**
     *
     * @param type $event
     * @codeCoverageIgnore
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     *
     * @param type $eventDefinition
     * @codeCoverageIgnore
     */
    public function setEventDefinition($eventDefinition)
    {
        $this->eventDefinition = $eventDefinition;
    }

    /**
     *
     * @param type $processDefinition
     * @codeCoverageIgnore
     */
    public function setProcessDefinition($processDefinition)
    {
        $this->processDefinition = $processDefinition;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getCrmDataWrapper()
    {
        return $this->crmDataWrapper;
    }

    /**
     *
     * @param type $crmDataWrapper
     * @codeCoverageIgnore
     */
    public function setCrmDataWrapper($crmDataWrapper)
    {
        $this->crmDataWrapper = $crmDataWrapper;
    }

    /**
     *
     * @param array $args
     * @return type
     */
    public function _get(array $args)
    {
        $result = array();
        $this->event->retrieve_by_string_fields(array('evn_uid' => $args['record']));
        if ($this->event->fetched_row != false) {
            $this->eventDefinition->retrieve($this->event->id);
            if ($this->eventDefinition->fetched_row != false) {
                $result = array_merge($result, $this->eventDefinition->fetched_row);
                $result['evn_uid'] = $this->event->fetched_row['evn_uid'];
            }
        }
        $relatedOutput = array();
        if (isset($args['related'])) {
            $related = explode(',', $args['related']);
            $output = array();
            if (is_array($related)) {
                foreach ($related as $search) {
                    $output[$search] = $this->crmDataWrapper->getRelatedSearch($search, $args);
                }
            }
            $relatedOutput['related'] = $output;
        }
        $result = array_merge($result, $relatedOutput);
        $result = PMSEEngineUtils::sanitizeKeyFields($result);
        return $result;
    }

    /**
     *
     * @param array $args
     */
    public function _put(array $args)
    {
        if (isset($args['record']) && count($args) > 0) {

            if ($this->event->retrieve_by_string_fields(array('evn_uid' => $args['record']))) {

                if ($this->event->fetched_row != false) {

                    $args = $args['data'];

                    $this->eventDefinition->retrieve($this->event->id);
                    if (!isset($args['evn_status'])) {
                        $args['evn_status'] = 'ACTIVE';
                    }
                    if (isset($args['evn_timer_type'])) {
                        switch ($args['evn_timer_type']) {
                            case 'duration':
                                $this->eventDefinition->evn_criteria = $args['evn_duration_criteria'];
                                $this->eventDefinition->evn_params = $args['evn_duration_params'];
                                break;
                            case 'fixed date':
                                $this->eventDefinition->evn_criteria = $args['evn_criteria'];
                                $this->eventDefinition->evn_params = $args['evn_timer_type'];
                                break;
                            default:
                                break;
                        }
                    } else {
                        foreach ($args as $key => $value) {
                            $this->eventDefinition->$key = $args[$key];
                        }
                    }
                    $this->eventDefinition->save();
                    //if ($this->event->evn_behavior == 'CATCH') {
                    $this->notify();
                    //}

                }
            }
        }
    }

    /**
     *
     * @param PMSEObserver $observer
     */
    public function attach($observer)
    {
        $i = array_search($observer, $this->observers);
        if ($i === false) {
            $this->observers[] = $observer;
        }
    }

    /**
     *
     * @param PMSEObserver $observer
     */
    public function detach($observer)
    {
        if (!empty($this->observers)) {
            $i = array_search($observer, $this->observers);
            if ($i !== false) {
                unset($this->observers[$i]);
            }
        }
    }

    /**
     *
     * Notify changes to all observers.
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
