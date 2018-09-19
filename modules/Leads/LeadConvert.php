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

require_once('modules/Campaigns/utils.php');

class LeadConvert
{
    const TRANSFERACTION_NONE = 'donothing';
    const TRANSFERACTION_COPY = 'copy';
    const TRANSFERACTION_MOVE = 'move';

    const STATUS_CONVERTED = 'Converted';
    protected $fileName = "modules/Leads/clients/base/layouts/convert-main/convert-main.php";
    protected $modules;
    protected $lead;
    protected $contact;
    protected $defs;

    public function __construct($leadId)
    {
        $this->initialize($leadId);
    }

    public function initialize($leadId)
    {
        $this->defs = $this->getVarDefs();

        if (empty($this->defs)) {
            throw new Exception('Could not retrieve lead convert metadata.');
        }

        $this->lead = BeanFactory::getBean('Leads', $leadId, array('strict_retrieve' => true));

        if (empty($this->lead)) {
            $errorMessage = string_format('Could not find record: {0} in module: Leads', $leadId);
            throw new Exception($errorMessage);
        }
    }

    /**
     * Returns the list of available modules that can be used during the lead convert process.
     * @return array modules
     */
    public function getAvailableModules()
    {
        $modules = array();
        foreach ($this->defs as $moduleDef) {
            $modules[] = $moduleDef['module'];
        }
        return $modules;
    }

    /**
     * Converts the Lead to a Contact and associates other modules to both lead and contact.
     * @param $modules Array of SugarBeans
     * @param $transferActivitiesAction The type of transfer to perform on lead activities (e.g. copy, move ...)
     * @param $transferActivitiesModules Array of modules to transfer lead activities to
     * @return array modules
     */
    public function convertLead($modules, $transferActivitiesAction = '', $transferActivitiesModules = array())
    {
        $calcFieldBeans = array();
        $this->modules = $modules;
        if (isset($this->modules['Contacts'])) {
            $this->contact = $this->modules['Contacts'];
        }

        foreach ($this->defs as $moduleDef) {
            $moduleName = $moduleDef['module'];
            if (!isset($this->modules[$moduleName])) {
                continue;
            }

            if ($moduleName != "Contacts" && $this->contact !=null && $this->contact instanceof Contact) {
                $this->setRelationshipsForModulesToContacts($moduleDef);
            }

            if ($this->modules[$moduleName]->object_name == 'Opportunity' && empty($this->modules[$moduleName]->account_id)) {
                $this->updateOpportunityWithAccountInformation($moduleDef);
            }

            if ($moduleName == "Accounts" && $this->lead->account_name != $modules['Accounts']->name) {
                $this->lead->account_name = $modules['Accounts']->name;
            }

            $this->setAssignedForModulesToLeads($moduleDef);
            $this->setRelationshipForModulesToLeads($moduleDef);

            $this->modules[$moduleName]->save();

            //iterate through each field in field map and check meta for calculated fields
            foreach ($this->modules[$moduleName]->field_defs as $calcFieldDefs) {
                if (!empty($calcFieldDefs['calculated'])) {
                    //bean has a calculated field, lets add it to the array for later processing
                    $calcFieldBeans[$moduleName] = $this->modules[$moduleName];
                    break;
                }
            }
        }

        if($this->contact != null && $this->contact instanceof Contact) {
            $this->contact->save();
            $this->addLogForContactInCampaign();
        }

        $this->performLeadActivitiesTransfer($transferActivitiesAction, $transferActivitiesModules);
        $this->performDataPrivacyTransfer();

        $this->lead->status = LeadConvert::STATUS_CONVERTED;
        $this->lead->converted = 1;
        $this->lead->in_workflow = true;
        $this->lead->save();

        //IF beans have calculated fields, re-save now  so calculated values can be updated
        foreach ($calcFieldBeans as $calcFieldBean) {
            //refetch bean and save to update Calculated Fields.
            $calcFieldBean->retrieve($calcFieldBean->id);
            $calcFieldBean->save();
        }

        return $this->modules;
    }

    /**
     * Links DP records to Contact and/or other modules related to DP module
     * and copy dp-related fields (business purpose, consent date etc) to Contact.
     * Only out-of-box DP relationships are handled.
     */
    public function performDataPrivacyTransfer()
    {
        $dprs = $this->lead->get_linked_beans('dataprivacy', 'DataPrivacy');
        if (!empty($dprs)) {
            foreach ($dprs as $dpr) {
                foreach ($this->modules as $module => $bean) {
                    if ($module !== 'Leads') {
                        if ($bean->load_relationship('dataprivacy')) {
                            $bean->dataprivacy->add($dpr);
                        } elseif ($rel = $this->findRelationship($bean, $dpr)) {
                            // custom module may have different link name
                            if ($bean->load_relationship($rel)) {
                                // left side rel
                                $bean->$rel->add($dpr);
                            } elseif ($dpr->load_relationship($rel)) {
                                // right side rel
                                $dpr->$rel->add($bean);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($this->lead->dp_business_puspose)) {
            $this->contact->dp_business_puspose = $this->lead->dp_business_puspose;
        }
        if (!empty($this->lead->dp_consent_last_updated)) {
            $this->contact->dp_consent_last_updated = $this->lead->dp_consent_last_updated;
        }
        if (isset($this->contact->dp_business_puspose) || isset($this->contact->dp_consent_last_updated)) {
            $this->contact->save();
        }
    }

    /**
     * If a Transfer Action is provided that agrees with the allowed actions define in the System Settings, then
     * perform the appropriate transfer of any Activities found for the Lead to the target transfer modules specified.
     * @param $transferActivitiesAction The type of transfer to perform on lead activities (e.g. copy, move ...)
     * @param $transferActivitiesModules Array of modules to transfer lead activities to
     */
    public function performLeadActivitiesTransfer($transferActivitiesAction, $transferActivitiesModules)
    {
        // Check to see if there is an action to take on the Transfer of Lead Activities
        $activitySetting = $this->getActivitySetting(); // From Configuration Setting

         if (!empty($activitySetting) &&
            !empty($transferActivitiesAction)
        ) {
            if ($activitySetting === static::TRANSFERACTION_COPY &&
                $transferActivitiesAction === static::TRANSFERACTION_COPY &&
                !empty($transferActivitiesModules)
            ) {
                Activity::disable();
                $this->copyActivities(
                    $this->lead,
                    $this->modules,
                    $transferActivitiesModules
                );
                Activity::enable();
            } elseif ($activitySetting === static::TRANSFERACTION_MOVE &&
                $transferActivitiesAction === static::TRANSFERACTION_MOVE &&
                !empty($this->contact)
            ) {
                Activity::disable();
                // Activity Move is currently limited to the Contact that the Lead is being converted to.
                $this->moveActivities(
                    $this->lead,
                    $this->contact
                );
                Activity::restoreToPreviousState();
            }
        }
    }

    /**
     * Update the opportunity with account id and name
     *
     * @param $moduleDef
     */
    public function updateOpportunityWithAccountInformation($moduleDef)
    {
        $moduleName = $moduleDef['module'];
        if (isset($this->modules['Accounts'])) {
            $this->modules[$moduleName]->account_id = $this->modules['Accounts']->id;
            $this->modules[$moduleName]->account_name = $this->modules['Accounts']->name;
        }
    }

    /**
     * Sets the relationships for modules to the Contacts module
     * @return null
     */
    public function setRelationshipsForModulesToContacts($moduleDef)
    {
        $moduleName = $moduleDef['module'];
        $contactRel = "";
        $relate = "";

        if (isset($moduleDef['contactRelateField']) && !empty($moduleDef['contactRelateField'])) {
            $relate = $moduleDef['contactRelateField'];
            $fieldDef = $this->contact->field_defs[$relate];
            if (!empty($fieldDef['id_name'])) {
                $this->contact->{$fieldDef['id_name']} = $this->modules[$moduleName]->id;
                if ($fieldDef['id_name'] != $relate) {
                    $rname = isset($fieldDef['rname']) ? $fieldDef['rname'] : "";
                    if (!empty($rname) && isset($this->modules[$moduleName]->$rname))
                        $this->contact->$relate = $this->modules[$moduleName]->$rname;
                    else
                        $this->contact->$relate = $this->modules[$moduleName]->name;
                }
            }
        }
        else {
            $contactRel = $this->findRelationship($this->contact, $this->modules[$moduleName]);
            if (!empty($contactRel)) {
                $this->contact->load_relationship($contactRel);
                $relObject = $this->contact->$contactRel->getRelationshipObject();
                if ($relObject->relationship_type == "one-to-many" && $this->contact->$contactRel->_get_bean_position()) {
                    $id_field = $relObject->rhs_key;
                    $this->modules[$moduleName]->$id_field = $this->contact->id;
                } else {
                    $this->contact->$contactRel->add($this->modules[$moduleName]);
                }
            }
        }
    }

    /**
     * Sets the assigned team and user based on the leads module
     * @return null
     */
    protected function setAssignedForModulesToLeads($moduleDef)
    {
        $moduleName = $moduleDef['module'];
        if (!empty($this->lead)) {
            if (empty($this->modules[$moduleName]->team_name)) {
                $this->modules[$moduleName]->team_id = $this->lead->team_id;
                $this->modules[$moduleName]->team_set_id = $this->lead->team_set_id;
            }
            if (empty($this->modules[$moduleName]->assigned_user_id)) {
                $this->modules[$moduleName]->assigned_user_id = $this->lead->assigned_user_id;
            }
        }
    }

    /**
     * Sets the relationships for modules to the Lead module
     * @return null
     */
    public function setRelationshipForModulesToLeads($moduleDef)
    {
        $moduleName = $moduleDef['module'];
        if (!empty($this->lead)) {
            $leadsRel = $this->findRelationship($this->modules[$moduleName], $this->lead);
            if (!empty($leadsRel)) {
                $this->modules[$moduleName]->load_relationship($leadsRel);
                $relObject = $this->modules[$moduleName]->$leadsRel->getRelationshipObject();

                if ($relObject->relationship_type == "one-to-many" && $this->modules[$moduleName]->$leadsRel->_get_bean_position()) {
                    $id_field = $relObject->rhs_key;
                    $this->lead->$id_field = $this->modules[$moduleName]->id;
                }
                else {
                    $this->modules[$moduleName]->$leadsRel->add($this->lead->id);
                }
            }
        }
    }

    /**
     * If campaign id exists then there should be an entry in campaign_log table for the newly created contact: bug 44522
     * @return null
     */
    public function addLogForContactInCampaign()
    {
        if (isset($this->lead->campaign_id) && $this->lead->campaign_id != null && isset($this->contact)) {
            $this->addCampaignLog($this->lead->campaign_id, $this->lead, $this->contact, 'contact');
        }
    }

    protected function addCampaignLog($campaignId, $lead, $contact, $moduleName)
    {
        return campaign_log_lead_or_contact_entry($campaignId, $lead, $contact, $moduleName);
    }

    /**
     * Loads the var def for the convert lead
     * @return null
     */
    protected function getVarDefs()
    {
        $viewdefs = array();
        $metaDataFile = SugarAutoLoader::existingCustomOne($this->fileName);
        require_once($metaDataFile);
        return $viewdefs['Leads']['base']['layout']['convert-main']['modules'];
    }

    /**
     * Copy Lead's Related Activities to Specified Modules
     * @param $lead
     * @param $beans
     * @param $transferModules
     */
    public function copyActivities($lead, $beans, $transferModules)
    {
        global $app_list_strings;
        $parent_types = $app_list_strings['record_type_display'];
        $activities = $this->getActivitiesFromLead($lead);

        // If an account is present, we will specify the account as the parent bean
        $accountTarget = null;
        if (!empty($beans['Accounts'])) {
            // Process this after any other copy-to targets are processed
            $accountTarget = $beans['Accounts'];
            unset($beans['Accounts']);
        }

        foreach ($beans as $module => $bean) {
            if (isset($parent_types[$module])) {
                foreach ($activities as $activity) {
                    if (in_array($module, $transferModules)) {
                        $this->copyActivityAndRelateToBean($activity, $bean, array());
                    }
                }
            }
        }

        if (!empty($accountTarget) && isset($parent_types['Accounts']) && in_array('Accounts', $transferModules)) {
            $accountParentInfo = array('id' => $accountTarget->id, 'type' => 'Accounts');
            foreach ($activities as $activity) {
                $this->copyActivityAndRelateToBean($activity, $accountTarget, $accountParentInfo);
            }
        }
    }

    /**
     * Move Lead's Related Activities to Target Bean
     * @param $lead
     * @param $bean  target Bean
     */
    public function moveActivities($lead, $bean)
    {
        if (!empty($bean)) {
            $activities = $this->getActivitiesFromLead($lead);
            foreach ($activities as $activity) {
                if ($rel = $this->findRelationship($activity, $lead)) {
                    $activity->load_relationship($rel);

                    if ($activity->parent_id && $activity->id) {
                        $activity->$rel->delete($activity->id, $activity->parent_id);
                    }

                    if ($rel = $this->findRelationship($activity, $bean)) {
                        $activity->load_relationship($rel);
                        $relObj = $activity->$rel->getRelationshipObject();
                        if ($relObj->relationship_type == 'one-to-one' || $relObj->relationship_type == 'one-to-many') {
                            $key = $relObj->rhs_key;
                            $activity->$key = $bean->id;
                        }
                        $activity->$rel->add($bean);
                    }

                    // set the new parent id and type
                    $activity->parent_id = $bean->id;
                    $activity->parent_type = $bean->module_dir;

                    $activity->save();
                }
            }
        }
    }

    /**
     * Gets the list of activities related to the lead
     * @param Lead $lead Lead to get activities from
     * @return Array of Activity SugarBeans.
     */
    protected function getActivitiesFromLead($lead)
    {
        global $beanList, $db;
        $activitiesList = array("Calls", "Tasks", "Meetings", "Emails", "Notes");
        $activities = array();

        if (!empty($lead)) {
            foreach ($activitiesList as $module) {
                $beanName = $beanList[$module];
                $activity = new $beanName();
                $query = "SELECT id FROM {$activity->table_name} WHERE parent_id = '{$lead->id}' AND parent_type = 'Leads'";
                $result = $db->query($query, true);
                while ($row = $db->fetchByAssoc($result)) {
                    $activity = new $beanName();
                    $activity->retrieve($row['id']);
                    $activity->fixUpFormatting();
                    $activities[] = $activity;
                }
            }
        }

        return $activities;
    }

    /**
     * Clone the supplied Activity and relate it to the supplied bean. Set parent_id and parent_type on the cloned
     * Activity if these values are supplied to this function.
     * @param $activity The Activity that you wish to Clone and assign to the supplied Bean
     * @param $bean The target Bean that you wish to copy the new Cloned Activity to.
     * @param array $parentArr The parent_id and parent_type values to set on the cloned Activity (optional)
     */
    protected function copyActivityAndRelateToBean($activity, $bean, $parentArr = array())
    {
        $newActivity = clone $activity;
        $newActivity->id = create_guid();
        $newActivity->new_with_id = true;

        //set the parent id and type if it was passed in, otherwise use blank to wipe it out
        $parentID = '';
        $parentType = '';
        if (!empty($parentArr)) {
            if (!empty($parentArr['id'])) {
                $parentID = $parentArr['id'];
            }

            if (!empty($parentArr['type'])) {
                $parentType = $parentArr['type'];
            }

        }

        //Special case to prevent duplicated tasks from appearing under Contacts multiple times
        if ($newActivity->module_dir == "Tasks" && $bean->module_dir != "Contacts") {
            $newActivity->contact_id = $newActivity->contact_name = "";
        }

        if ($rel = $this->findRelationship($newActivity, $bean)) {
            if (isset($newActivity->$rel)) {
                // this comes form $activity, get rid of it and load our own
                $newActivity->$rel = '';
            }

            $newActivity->load_relationship($rel);
            $relObj = $newActivity->$rel->getRelationshipObject();
            if ($relObj->relationship_type == 'one-to-one' || $relObj->relationship_type == 'one-to-many') {
                $key = $relObj->rhs_key;
                $newActivity->$key = $bean->id;
            }

            //parent (related to field) should be blank unless it is explicitly sent in
            $newActivity->parent_id = $parentID;
            $newActivity->parent_type = $parentType;

            $newActivity->update_date_modified = false; //bug 41747
            $newActivity->save();
            $newActivity->$rel->add($bean);
            if ($newActivity->module_dir == "Notes" && $newActivity->filename) {
                UploadFile::duplicate_file($activity->id, $newActivity->id, $newActivity->filename);
            }
        }
    }

    /**
     * Finds the relationship between two modules and returns the relationship key
     * @return string
     */
    public function findRelationship($from, $to)
    {
        $dictionary = $this->getMetaTableDictionary();

        foreach ($from->field_defs as $field => $def) {
            if (isset($def['type']) && $def['type'] == "link" && isset($def['relationship'])) {
                $rel_name = $def['relationship'];
                $rel_def = "";
                if (isset($dictionary[$from->object_name]['relationships']) && isset($dictionary[$from->object_name]['relationships'][$rel_name])) {
                    $rel_def = $dictionary[$from->object_name]['relationships'][$rel_name];
                }
                else if (isset($dictionary[$to->object_name]['relationships']) && isset($dictionary[$to->object_name]['relationships'][$rel_name])) {
                    $rel_def = $dictionary[$to->object_name]['relationships'][$rel_name];
                }
                else if (isset($dictionary[$rel_name]) && isset($dictionary[$rel_name]['relationships'])
                    && isset($dictionary[$rel_name]['relationships'][$rel_name])
                ) {
                    $rel_def = $dictionary[$rel_name]['relationships'][$rel_name];
                }
                if (!empty($rel_def)) {
                    if ($rel_def['lhs_module'] == $from->module_dir && $rel_def['rhs_module'] == $to->module_dir) {
                        return $field;
                    }
                    else if ($rel_def['rhs_module'] == $from->module_dir && $rel_def['lhs_module'] == $to->module_dir) {
                        return $field;
                    }
                }
            }
        }
        return false;
    }

    public function getActivitySetting()
    {
        global $sugar_config;
        $activitySetting = static::TRANSFERACTION_NONE;
        if (isset($sugar_config['lead_conv_activity_opt'])) {
            $activitySetting = $sugar_config['lead_conv_activity_opt'];
        }
        return $activitySetting;
    }

    public function getMetaTableDictionary()
    {
        global $dictionary;
        require_once("modules/TableDictionary.php");
        return $dictionary;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function setModules($modules)
    {
        $this->modules = $modules;
    }

    public function getModules()
    {
        return $this->modules;
    }
}
