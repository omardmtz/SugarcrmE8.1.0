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

class UsersRelateRecordApi extends RelateRecordApi
{
    public function registerApiRest() {
        return array(
            'createRelatedLink' => array(
                'reqType'   => 'POST',
                'path'      => array('Users','?',     'link','?'        ,'?'),
                'pathVars'  => array('module',  'record','',    'link_name','remote_id'),
                'method'    => 'createRelatedLink',
                'shortHelp' => 'Relates an existing record to this module',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_post_help.html',
            ),
            'createRelatedLinks' => array(
                'reqType' => 'POST',
                'path' => array('Users', '?', 'link'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'createRelatedLinks',
                'shortHelp' => 'Relates existing records to this module.',
                'longHelp' => 'include/api/help/module_record_link_post_help.html',
            ),
            'deleteRelatedLink' => array(
                'reqType'   => 'DELETE',
                'path'      => array('Users','?'     ,'link','?'        ,'?'),
                'pathVars'  => array('module'  ,'record',''    ,'link_name','remote_id'),
                'method'    => 'deleteRelatedLink',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html',
            ),
        );
    }

    protected function checkRelatedSecurity(
        ServiceBase $api,
        array $args,
        SugarBean $primaryBean,
        $securityTypeLocal = 'view',
        $securityTypeRemote = 'view'
    ) {
        global $current_user;

        $this->requireArgs($args, array('link_name'));
        if ($args['link_name'] == 'teams' && !$current_user->isAdmin()) {
            throw new SugarApiExceptionNotAuthorized('No access to modify link "teams"');
        }

        return parent::checkRelatedSecurity($api, $args, $primaryBean, $securityTypeLocal, $securityTypeRemote);

    }
}
