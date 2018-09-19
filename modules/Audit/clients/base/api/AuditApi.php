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


class AuditApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'view_change_log' => array(
                'reqType' => 'GET',
                'path' => array('<module>','?', 'audit'),
                'pathVars' => array('module','record','audit'),
                'method' => 'viewChangeLog',
                'shortHelp' => 'View audit log in record view',
                'longHelp' => 'include/api/help/audit_get_help.html',
            ),
        );
    }

    public function viewChangeLog(ServiceBase $api, array $args)
    {
        global $focus, $current_user;

        $this->requireArgs($args,array('module', 'record'));

        $focus = BeanFactory::getBean($args['module'], $args['record']);

        if (!$focus->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('no access to the bean');
        }

        $auditBean = BeanFactory::newBean('Audit');

        return array(
            'next_offset' => -1,
            'records' => $auditBean->getAuditLog($focus),
        );
    }
}
