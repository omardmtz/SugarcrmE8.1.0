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


class CalendarController extends SugarController
{

    /**
     * Bean that is being handled by the Calendar's current action.
     * @var SugarBean $currentBean
     */
    protected $currentBean = null;


    /**
     * Action SaveActivity
     */
    protected function action_saveactivity()
    {
        $this->view = 'json';

        if (!$this->retrieveCurrentBean('Save')) {
            return;
        }

        $module = $this->currentBean->module_dir;
        $bean = $this->currentBean;
        
        $path = "modules/{$bean->module_dir}/{$bean->object_name}FormBase.php";
        if (!file_exists($path)) {
            $GLOBALS['log']->fatal("File {$bean->object_name}FormBase.php doesn't exist");
            sugar_cleanup(true);
        }

        require_once($path);

        $FBObjectName = "{$bean->object_name}FormBase";

        if (!class_exists($FBObjectName)) {
            $GLOBALS['log']->fatal("Class {$bean->object_name}FormBase doesn't exist");
            sugar_cleanup(true);
        }

        $formBase = new $FBObjectName();
        
        $isRecurring = false;
        if ($formBase->prepareRecurring()) {
            if ($limit = $formBase->checkRecurringLimitExceeded()) {
                $this->view_object_map['jsonData'] = $jsonData = array(
                    'access' => 'yes',
                    'limit_error' => 'true',
                    'limit' => $limit,
                );
                return;
            }
            $isRecurring = true;
        }
        
        $bean = $formBase->handleSave('', false, false);

        if ($record = $bean->id) {
            $bean->retrieve($record);
            $jsonData = CalendarUtils::getBeanDataArray($bean);    
            
            if ($isRecurring) {
                $jsonData = array_merge($jsonData, array('repeat' => $formBase->getRecurringCreated()));
            }

            if (!empty($_REQUEST['edit_all_recurrences'])) {
                $jsonData['edit_all_recurrences'] = 'true';
            }
        } else {
            $jsonData = array(
                'access' => 'no',
            );
        }

        $this->view_object_map['jsonData'] = $jsonData;
    }


    /**
     * Action QuickEdit
     */
    protected function action_quickedit()
    {
        $this->view = 'quickedit';

        if (!$this->retrieveCurrentBean('Detail')) {
            return;
        }

        $this->view_object_map['currentModule'] = $this->currentBean->module_dir;
        $this->view_object_map['currentBean'] = $this->currentBean;

        $editAllRecurrences = isset($_REQUEST['edit_all_recurrences']) ? $_REQUEST['edit_all_recurrences'] : false;
        $dateStart = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : false;
        $this->view_object_map['repeatData'] = CalendarUtils::getRepeatData($this->currentBean, $editAllRecurrences, $dateStart);
    }

    /**
     * Action Reschedule
     * Used for drag & drop
     */
    protected function action_reschedule()
    {
        $this->view = 'json';

        $commit = true;

        if (!$this->retrieveCurrentBean('Save')) {
            return;
        }

        $_REQUEST['parent_name'] = $this->currentBean->parent_name;

        $dateField = "date_start";
        if ($this->currentBean->module_dir == "Tasks") {
            $dateField = "date_due";
        }

        if (!empty($_REQUEST['calendar_style']) && $_REQUEST['calendar_style'] == "basic") {
            global $timedate;
            $date = $timedate->asUserDate($timedate->fromUser($_REQUEST['datetime']));
            $time = $timedate->asUserTime($timedate->fromUser($this->currentBean->$dateField));
            $_REQUEST['datetime'] = $date . ' ' . $time;
        }
        $_POST[$dateField] = $_REQUEST['datetime'];

        if ($this->currentBean->module_dir == "Tasks" && !empty($this->currentBean->date_start)) {
            if ($GLOBALS['timedate']->fromUser($_POST['date_due'])->ts < $GLOBALS['timedate']->fromUser($this->currentBean->date_start)->ts) {
                $this->view_object_map['jsonData'] = array(
                    'access' => 'no',
                    'errorMessage' => $GLOBALS['mod_strings']['LBL_DATE_END_ERROR'],
                );
                $commit = false;
            }
        }

        if ($commit) {
            require_once('include/formbase.php');
            $this->currentBean = populateFromPost("", $this->currentBean);                
            $this->currentBean->save();        
            $this->currentBean->retrieve($_REQUEST['record']);        
                
            $this->view_object_map['jsonData'] = CalendarUtils::getBeanDataArray($this->currentBean);
        }    
    }

    /**
     * Action Remove
     */
    protected function action_remove()
    {
        $this->view = 'json';

        if (!$this->retrieveCurrentBean('Delete')) {
            return;
        }

        if ($this->currentBean->module_dir == "Meetings" || $this->currentBean->module_dir == "Calls") {
            if (!empty($_REQUEST['remove_all_recurrences']) && $_REQUEST['remove_all_recurrences']) {
                CalendarUtils::markRepeatDeleted($this->currentBean);
            }
        }

        $this->currentBean->mark_deleted($_REQUEST['record']);

        $this->view_object_map['jsonData'] = array(
            'access' => 'yes',
        );

    }

    /**
     * Action Resize
     * Used for drag & drop resizing
     */
    protected function action_resize()
    {
        $this->view = 'json';

        if (!$this->retrieveCurrentBean('Save')) {
            return;
        }

        require_once('include/formbase.php');
        $this->currentBean = populateFromPost("", $this->currentBean);
        $this->currentBean->save();

        $this->view_object_map['jsonData'] = array(
            'access' => 'yes',
        );
    }


    /**
     * Retrieves current activity bean and checks access to action
     *
     * @param string $actionToCheck
     * @return bool Result of check
     */
    protected function retrieveCurrentBean($actionToCheck = false)
    {
        $module = $_REQUEST['current_module'];
        $record = null;
        if (!empty($_REQUEST['record'])) {
            $record = $_REQUEST['record'];
        }

        $this->currentBean = BeanFactory::getBean($module, $record);

        if (!empty($actionToCheck)) {
            if (!$this->currentBean->ACLAccess($actionToCheck)) {
                $this->view = 'json';
                $jsonData = array(
                    'access' => 'no',
                );
                $this->view_object_map['jsonData'] = $jsonData;
                return false;
            }
        }

        return true;
    }

    protected function action_getActivities()
    {
        $this->view = 'json';

        if(!ACLController::checkAccess('Calendar', 'list', true)){
            ACLController::displayNoAccess(true);
        }

        $cal = new Calendar($_REQUEST['view']);

        if (in_array($cal->view, array('day', 'week', 'month'))){
            $cal->add_activities($GLOBALS['current_user']);

        } else if ($cal->view == 'shared') {
            $cal->init_shared();
            $sharedUser = BeanFactory::newBean('Users');    
            foreach ($cal->shared_ids as $member) {
                $sharedUser->retrieve($member);
                $cal->add_activities($sharedUser);
            }
        }
        $cal->load_activities();
        $this->view_object_map['jsonData'] = $cal->items;
    }

}
