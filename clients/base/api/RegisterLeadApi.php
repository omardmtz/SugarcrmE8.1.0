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


class RegisterLeadApi extends SugarApi {
    public function registerApiRest() {
        return array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array('Leads','register'),
                'pathVars' => array('module'),
                'method' => 'createLeadRecord',
                'shortHelp' => 'This method registers leads',
                'longHelp' => 'include/api/help/leads_register_post_help.html',
                'noLoginRequired' => true,
            ),
        );
    }

    /**
     * Fetches data from the $args array and updates the bean with that data
     * @param $bean SugarBean The bean to be updated
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return id Bean id
     */
    protected function updateBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        // Bug 54515: Set modified by and created by users to assigned to user. If not set default to admin.
        $bean->update_modified_by = false;
        $bean->set_created_by = false;
        $admin = Administration::getSettings();
        if (isset($admin->settings['supportPortal_RegCreatedBy']) && !empty($admin->settings['supportPortal_RegCreatedBy'])) {
            $bean->created_by = $admin->settings['supportPortal_RegCreatedBy'];
            $bean->modified_user_id = $admin->settings['supportPortal_RegCreatedBy'];
        } else {
            $bean->created_by = '1';
            $bean->modified_user_id = '1';
        }

        // Bug 54516 users not getting notified on new record creation
        $bean->save(true);

        return parent::updateBean($bean, $api, $args);
    }

    /**
     * Creates lead records
     * @param ServiceBase $apiServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return array properties on lead bean formatted for display
     */
    public function createLeadRecord(ServiceBase $api, array $args)
    {
        // Bug 54647 Lead registration can create empty leads
        if (!isset($args['last_name'])) {
            throw new SugarApiExceptionMissingParameter();
        }

        /**
         *
         * Bug56194: This API can be hit without logging into Sugar, but the creation of a Lead SugarBean
         * uses messages that require the use of the app strings.
         *
         **/
        global $app_list_strings;
        global $current_language;
        if(!isset($app_list_strings)){
            $app_list_strings = return_app_list_strings_language($current_language);
        }

        $bean = BeanFactory::newBean('Leads');
        // we force team and teamset because there is no current user to get them from
        $fields = array(
            'team_set_id' => '1',
            'team_id' => '1',
            'lead_source' => 'Support Portal User Registration',
        );

        $admin = Administration::getSettings();

        if (isset($admin->settings['portal_defaultUser']) && !empty($admin->settings['portal_defaultUser'])) {
            $fields['assigned_user_id'] = json_decode(html_entity_decode($admin->settings['portal_defaultUser']));
        }

        $fieldList = array('first_name', 'last_name', 'phone_work', 'email', 'primary_address_country', 'primary_address_state', 'account_name', 'title', 'preferred_language');
        foreach ($fieldList as $fieldName) {
            if (isset($args[$fieldName])) {
                $fields[$fieldName] = $args[$fieldName];
            }
        }

        $id = $this->updateBean($bean, $api, $fields);
        return $id;
    }


}
