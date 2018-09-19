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


class UsersApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'delete' => array(
                'reqType'   => 'DELETE',
                'path'      => array('Users', '?'),
                'pathVars'  => array('module', 'record'),
                'method'    => 'deleteUser',
                'shortHelp' => 'This method deletes a User record',
                'longHelp'  => 'modules/Users/clients/base/api/help/UsersApi.html',
            ),
            'getFreeBusySchedule' => array(
                'reqType' => 'GET',
                'path' => array("Users", '?', "freebusy"),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getFreeBusySchedule',
                'shortHelp' => 'Retrieve a list of calendar event start and end times for specified person',
                'longHelp' => 'include/api/help/user_get_freebusy_help.html',
            ),
        );
    }

    /**
     * Delete the user record and set the appropriate flags. Handled in a separate api call from the base one because
     * the base api delete field doesn't set user status to 'inactive' or employee_status to 'Terminated'
     *
     * The non-api User deletion logic is handled in /modules/Users/controller.php::action_delete()
     *
     * @param  ServiceBase $api
     * @param  array       $args
     * @return array
     */
    public function deleteUser(ServiceBase $api, array $args)
    {
        // Ensure we have admin access to this module
        if (!($api->user->isAdmin() || $api->user->isAdminForModule('Users'))) {
            throw new SugarApiExceptionNotAuthorized();
        }

        // This logic is also present in /module/Users/controller.php::action_delete()
        if ($api->user->id === $args['record']) {
            throw new SugarApiExceptionInvalidParameter();
        }

        $this->requireArgs($args, array('module', 'record'));
        // loadBean() handles exceptions for bean validation
        $user = $this->loadBean($api, $args, 'delete');
        $user->deleted = 1;
        $user->status = 'Inactive';
        $user->employee_status = 'Terminated';
        $user->save();

        return array('id' => $user->id);
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
}
