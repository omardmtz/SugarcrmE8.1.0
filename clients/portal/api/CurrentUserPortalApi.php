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


class CurrentUserPortalApi extends CurrentUserApi {
    /**
     * Retrieves the current portal user info
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function retrieveCurrentUser(ServiceBase $api, array $args)
    {
        global $current_user;

        // Get the basics
        $user_data = $this->getBasicUserInfo($api->platform);
        // Fill in the portal specific stuff
        $contact = $this->getPortalContact();
        $user_data['type'] = 'support_portal';
        $user_data['user_id'] = $current_user->id;
        $user_data['user_name'] = $current_user->user_name;
        $user_data['acl'] = $this->getAcls('portal');
        $user_data['id'] = $_SESSION['contact_id'];
        
        // We need to ask the visibility system for the list of account ids
        $visibility = new SupportPortalVisibility($contact);
        $user_data['account_ids'] = $visibility->getAccountIds();

        $user_data['full_name'] = $contact->full_name;
        $user_data['picture'] = $contact->picture;
        $user_data['portal_name'] = $contact->portal_name;
        if(isset($contact->preferred_language)) {
            $user_data['preferences']['language'] = $contact->preferred_language;
        }
        
        return array('current_user'=>$user_data);
    }

    /**
     * Updates current portal users info
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function updateCurrentUser(ServiceBase $api, array $args)
    {
        $bean = $this->getPortalContact();
        // setting these for the loadBean
        $args['module'] = $bean->module_name;
        $args['record'] = $bean->id;

        $id = $this->updateBean($bean, $api, $args);

        return $this->retrieveCurrentUser($api, $args);
    }

    /**
     * Gets the current portal user's Contact bean.
     * When working with Portal this contains the interesting user info
     *
     * @return Contact
     */
    protected function getPortalContact(){
        if(!isset($this->portal_contact)){
            $this->portal_contact = BeanFactory::getBean('Contacts', $_SESSION['contact_id']);
        }
        return $this->portal_contact;
    }

    /**
     * Checks a given password and sends back the contact bean if the password matches
     * 
     * @param string $passwordToVerify
     * @return Contact
     */
    protected function getUserIfPassword($passwordToVerify) {
        $contact = $this->getPortalContact();
        $currentPassword = $contact->portal_password;
        if (User::checkPassword($passwordToVerify, $currentPassword)) {
            return $contact;
        }
        
        return null;
    }

    /**
     * Changes a portal password for a contact from old to new
     * 
     * @param Contact $bean Contact bean
     * @param string $old Old password 
     * @param string $new New password
     * @return array
     */
    protected function changePassword(SugarBean $bean, $old, $new)
    {
        $bean->portal_password = User::getPasswordHash($new);
        $bean->save();
        return array(
            'valid' => true,
            'message' => 'Password updated.',
            'expiration' => null,
        );
    }

    /**
     * Gets the preference for user login expiration
     * 
     * @return null
     */
    protected function getUserLoginExpirationPreference() {
        return null;
    }

    /**
     * Manipulates the ACLs for portal
     * 
     * @param array $acls
     * @return array
     */
    protected function verifyACLs(Array $acls) {
        $acls['admin'] = 'no';
        $acls['developer'] = 'no';
        $acls['delete'] = 'no';
        $acls['import'] = 'no';
        $acls['export'] = 'no';
        $acls['massupdate'] = 'no';
        
        return $acls;
    }

    /**
     * Enforces module specific ACLs for users without accounts
     * 
     * @param array $acls
     * @return array
     */
    protected function enforceModuleACLs(Array $acls) {
        $apiPerson = $this->getPortalContact();
        // This is a change in the ACL's for users without Accounts
        $vis = new SupportPortalVisibility($apiPerson);

        $accounts = $vis->getAccountIds();
        if (count($accounts)==0) {
            // This user has no accounts, modify their ACL's so that they match up with enforcement
            $acls['Accounts']['access'] = 'no';
            $acls['Cases']['access'] = 'no';
        }
        foreach ($acls as $modName => $modAcls) {
            if ($modName === 'Contacts') continue;

            $acls[$modName]['edit'] = 'no';
        }
        
        return $acls;
    }
}
