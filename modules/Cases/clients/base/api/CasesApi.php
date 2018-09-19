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


class CasesApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'create' => array(
                'reqType'   => 'POST',
                'path'      => array('Cases'),
                'pathVars'  => array('module'),
                'method'    => 'createRecord',
                'shortHelp' => 'This method creates a new Case record with option to add Contact and Account relationships',
                'longHelp'  => 'modules/Cases/api/help/CasesApi.html',
            ),
        );
    }

    /**
     * Create the case record and optionally perform post-save actions for Portal
     */
    public function createRecord(ServiceBase $api, array $args)
    {
        //create the case using the ModuleApi

        $contact = null;
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'support_portal') {
            $contact = BeanFactory::getBean('Contacts', $_SESSION['contact_id'], array('strict_retrieve' => true));

            if (!empty($contact)) {
                $args['assigned_user_id'] = $contact->assigned_user_id;
                $args['account_id']       = $contact->account_id;
                $args['team_id'] = $contact->team_id;
                $args['team_set_id'] = $contact->team_set_id;
                $args['acl_team_set_id'] = $contact->acl_team_set_id;
            }
        }

        $data = parent::createRecord($api, $args);

        $caseId = null;
        if (isset($data['id']) && !empty($data['id'])) {
            $caseId = $data['id'];
        } else {
            //case not created, can't do post-processes - bail out
            return $data;
        }

        if (!empty($caseId) && !empty($contact)) {
            $case = BeanFactory::getBean('Cases', $caseId);
            $case->load_relationship('contacts');
            $case->contacts->add($contact->id);
        }

        return $data;
    }
}
