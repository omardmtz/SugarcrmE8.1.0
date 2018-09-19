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

class LeadsApi extends ModuleApi {
    public function registerApiRest() {
        return array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array('Leads'),
                'pathVars' => array('module'),
                'method' => 'createRecord',
                'shortHelp' => 'This method creates a new Lead record with option to add Target & Email relationships',
                'longHelp' => 'modules/Leads/clients/base/api/help/LeadsApi.html',
            ),
            'getFreeBusySchedule' => array(
                'reqType' => 'GET',
                'path' => array("Leads", '?', "freebusy"),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getFreeBusySchedule',
                'shortHelp' => 'Retrieve a list of calendar event start and end times for specified person',
                'longHelp' => 'include/api/help/lead_get_freebusy_help.html',
            ),
        );
    }

    /**
     * Create the lead record and optionally perform post-save actions for Convert Target & Lead from Email cases
     */
    public function createRecord(ServiceBase $api, array $args)
    {
        //create the lead using the ModuleApi
        $data = parent::createRecord($api, $args);

        $leadId = null;
        if (isset($data['id']) && !empty($data['id'])) {
            $leadId = $data['id'];
        } else {
            //lead not created, can't do post-processes - bail out
            return $data;
        }

        // Handle Lead-Prospect post processing
        if (!empty($args['relate_to']) && $args['relate_to'] === 'Prospects' && !empty($args['relate_id'])) {
            // Save lead_id for display purposes
            $prospectId = $args['relate_id'];
            $prospect = BeanFactory::getBean('Prospects', $prospectId);
            $prospect->lead_id = $leadId;
            $prospect->save();
            // Handle Campaign Log entry creation
            if (!empty($data['campaign_id'])) {
                $lead = BeanFactory::getBean('Leads', $leadId);
                campaign_log_lead_or_contact_entry($data['campaign_id'], $prospect, $lead, 'lead');
            }
        }

        //handle Create Lead from Email use case
        if (isset($args['inbound_email_id']) && !empty($args['inbound_email_id'])) {
            $this->linkLeadToEmail($args['inbound_email_id'], $leadId);
        }

        return $data;
    }

    /**
     * Retrieve a list of calendar event start and end times for specified person
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function getFreeBusySchedule(ServiceBase $api, array $args)
    {
        $bean = $this->loadBean($api, $args, 'view');
        return array(
            "module" => $bean->module_name,
            "id" => $bean->id,
            "freebusy" => $bean->getFreeBusySchedule($args),
        );
    }

    /**
     * Link the Lead to the Email from which the lead was created
     * Also set the assigned user to current user and mark email as read.
     * TODO: This logic is brought over from LeadFormBase->handleSave() - need refactoring to use Link2?
     *
     * @param $emailId
     * @param $leadId
     */
    protected function linkLeadToEmail($emailId, $leadId) {
        global $current_user;

        $email = new Email();
        $email->retrieve($emailId);
        $email->parent_type = 'Leads';
        $email->parent_id = $leadId;
        $email->assigned_user_id = $current_user->id;
        $email->status = 'read';
        $email->save();

        $email->load_relationship('leads');
        $email->leads->add($leadId);
    }

    protected function getAccountBean(ServiceBase $api, array $args, $record)
    {
        // Load up the relationship
        if (!$record->load_relationship('accounts')) {
            throw new SugarApiExceptionNotFound('Could not find a relationship name accounts');
        }

        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $record->accounts->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$linkModuleName);
        }

        $accounts = $record->accounts->query(array());
        foreach ($accounts['rows'] as $accountId => $value) {
            $account = BeanFactory::getBean('Accounts', $accountId);
            if (empty($account)) {
                throw new SugarApiExceptionNotFound('Could not find parent record '.$accountId.' in module Accounts');
            }
            if (!$account->ACLAccess('view')) {
                throw new SugarApiExceptionNotAuthorized('No access to view records for module: Accounts');
            }

            // Only one account, so we can return inside the loop.
            return $account;
        }
    }

    protected function getAccountRelationship(ServiceBase $api, array $args, $account, $relationship, $limit = 5, $query = array())
    {
        // Load up the relationship
        if (!$account->load_relationship($relationship)) {
            // The relationship did not load, I'm guessing it doesn't exist
            throw new SugarApiExceptionNotFound('Could not find a relationship name ' . $relationship);
        }
        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $account->$relationship->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$linkModuleName);
        }

        $relationshipData = $account->$relationship->query($query);
        $rowCount = 1;

        $data = array();
        foreach ($relationshipData['rows'] as $id => $value) {
            $rowCount++;
            $bean = BeanFactory::getBean(ucfirst($relationship), $id);
            $data[] = $this->formatBean($api, $args, $bean);
            if (!is_null($limit) && $rowCount == $limit) {
                // We have hit our limit.
                break;
            }
        }
        return $data;
    }

}
