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

use Sugarcrm\Sugarcrm\ProcessManager\Registry;

class PMSEStartEvent extends PMSEEvent
{
    /**
     *
     * @param type $flowData
     * @param type $bean
     * @param type $externalAction
     * @param type $arguments
     * @return type
     */
    public function run($flowData, $bean = null, $externalAction = '', $arguments = array())
    {
        // Needed for checking on triggered starts
        $regKey = 'triggered_starts';

        // Used to read from and write to if necessary
        $registry = Registry\Registry::getInstance();

        // Get our list of triggered starts
        $triggered = $registry->get($regKey, array());

        // See if this start event has already been triggered in this request
        if (isset($flowData['bpmn_id'])) {
            // Will need this for writing
            $startEventID = $flowData['bpmn_id'];

            // If this start event has been triggered already, stop now to prevent
            // infinite triggers
            if (!empty($triggered[$startEventID])) {
                // Log a message for this event
                $msg = "Start Event ID $startEventID has already been triggered" .
                       " in this request and cannot be triggered again.";
                $this->logger->alert($msg);

                // We need to call this method to ensure what is needed later is there
                return $this->prepareResponse(array(), '', '');
            }
        }

        $relatedBean = $this->retrieveRelatedBean($flowData, $bean);
        if (!empty($relatedBean)) {
            $flowData = $this->createNewCase($relatedBean, $flowData);
        } else {
            $flowData = $this->createNewCase($bean, $flowData);
        }
        // Set the triggered ID into registry now
        if (isset($startEventID)) {
            $triggered[$startEventID] = true;
            $registry->set($regKey, $triggered, true);
        }

        return parent::run($flowData, $bean, $externalAction, $arguments);
    }

    /**
     *
     * @param type $flowData
     * @param type $bean
     * @return type
     */
    public function retrieveRelatedBean($flowData, $bean)
    {
        $processDefinitionBean = $this->caseFlowHandler->retrieveBean('pmse_BpmProcessDefinition', $flowData['pro_id']);
        $relatedBean = '';
        if ($processDefinitionBean->pro_module != $bean->module_name) {
            foreach ($bean as $key => $attribute) {
                if ($bean->$key instanceof Link2) {
                    if (
                        ($bean->$key->relationship->def['lhs_module'] == $processDefinitionBean->pro_module
                            && $bean->$key->relationship->def['rhs_module'] == $bean->module_name) ||
                        ($bean->$key->relationship->def['lhs_module'] == $bean->module_name
                            && $bean->$key->relationship->def['rhs_module'] == $processDefinitionBean->pro_module)
                    ) {
                        $relatedBeanList = $bean->$key->getBeans();
                        if ($relatedBean = array_pop($relatedBeanList)) {
                            $relatedBean->load_relationships();
                        }
                    }
                }
            }
        }
        return $relatedBean;
    }

    /**
     * Creates a new case based on a determined module and start Event
     * @param SugarBean $bean
     * @param type $event
     * @param type $flowData
     * @return type
     * @codeCoverageIgnore
     */
    private function createNewCase($bean, $elementData)
    {
        //set fields
        $moduleName = $bean->module_name;
        $objectId = $bean->id;

        $today = TimeDate::getInstance()->nowDb();
        $_date = TimeDate::getInstance()->getNow()->add(new DateInterval('P2D'));
        $dueDate = $_date->asDb();

        //todo: generate a pin
        $cas_pin = rand(0, 10000);

        //execute queries to get the correct process Id and process title
        $pro_id = $elementData['pro_id'];
        $processBean = BeanFactory::getBean('pmse_BpmnProcess', $pro_id); //new BpmnProcess();

        if (!$processBean->fetched_row) {
            $this->logger->error("[0][1] process name not found using Process Number: $pro_id");
            //$this->bpmLog('ERROR', "[$cas_id][1] process name not found using Process Id: $pro_id");
            $pro_title = 'unknown';
        } else {
            $pro_title = $processBean->name;
        }

        if (isset($bean->assigned_user_id)) {
            $assigned_user_id = $bean->assigned_user_id;
        } else {
            if (isset($bean->created_by)) {
                $assigned_user_id = $bean->created_by;
            } else {
                $assigned_user_id = '';
            }
        }

        // Ensures that the assigned_user_id field is trimmed, since on some
        // DBs this field is a padded char field and on others it is a varchar.
        // This is done here because $flowData can be sent throughout the chain
        // of method calls and could pose a problem in comparison beyond this point.
        $assigned_user_id = $bean->db->fromConvert($assigned_user_id, 'id');

        //create a ProcessMaker row
        $case = BeanFactory::newBean('pmse_Inbox'); //new BpmInbox();
        $case->cas_parent = 0;
        $case->cas_status = 'IN PROGRESS';
        $case->pro_id = $pro_id;
        $case->pro_title = $pro_title;
        $case->cas_custom_status = '';
        $case->cas_init_user = $assigned_user_id;
        $case->assigned_user_id = $assigned_user_id;
        $case->cas_create_date = $today;
        $case->cas_update_date = $today;
        $case->cas_finish_date = '';
        $case->cas_pin = $cas_pin;
        $case->cas_module = $moduleName;

        $case->save();

        $flowData = array();

        $flowData['cas_id'] = $case->cas_id;
        $flowData['cas_index'] = 1;
        $flowData['cas_previous'] = 0;
        $flowData['pro_id'] = $pro_id;
        $flowData['bpmn_id'] = $elementData['bpmn_id'];
        $flowData['bpmn_type'] = 'bpmnEvent';
        $flowData['cas_user_id'] = $assigned_user_id;
        $flowData['cas_thread'] = 1;
        $flowData['cas_flow_status'] = 'NEW';
        $flowData['cas_sugar_module'] = $moduleName;
        $flowData['cas_sugar_object_id'] = $objectId;
        $flowData['cas_sugar_action'] = 'None';
        $flowData['cas_delegate_date'] = $today;
        $flowData['cas_start_date'] = $today; //all start events are started inmediately
        $flowData['cas_finish_date'] = '';
        $flowData['cas_due_date'] = $dueDate;
        $flowData['cas_queue_duration'] = 0;
        $flowData['cas_duration'] = 0;
        $flowData['cas_delay_duration'] = 0;
        $flowData['cas_started'] = 1; //all start events are started inmediately
        $flowData['cas_finished'] = 0;
        $flowData['cas_delayed'] = 0;

        return $flowData;
    }
}
