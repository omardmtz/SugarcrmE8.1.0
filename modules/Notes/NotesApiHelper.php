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



class NotesApiHelper extends SugarBeanApiHelper
{
    /**
     * This function sets the team & assigned user and sets up the contact & account relationship
     * for new Notes submitted via portal users.
     *
     * @param SugarBean $bean
     * @param array $submittedData
     * @param array $options
     * @return array
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        //TODO: need a more generic way to deal with file types
        if (isset($submittedData['file_mime_type'])) {
            unset($submittedData['file_mime_type']);
        }

        $data = parent::populateFromApi($bean, $submittedData, $options);

        //Only needed for Portal sessions
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'support_portal') {
            if (empty($bean->id)) {
                $bean->id = create_guid();
                $bean->new_with_id = true;
            }

            $contact = BeanFactory::getBean('Contacts',$_SESSION['contact_id']);
            $account = $contact->account_id;

            $bean->assigned_user_id = $contact->assigned_user_id;

            $bean->team_id = $contact->fetched_row['team_id'];
            $bean->team_set_id = $contact->fetched_row['team_set_id'];
            $bean->acl_team_set_id = $contact->fetched_row['acl_team_set_id'];

            $bean->account_id = $account;
            $bean->contact_id= $contact->id;
        }

        return $data;
    }
}
