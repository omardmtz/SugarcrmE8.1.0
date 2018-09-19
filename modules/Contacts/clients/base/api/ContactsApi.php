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

class ContactsApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'opportunity_stats' => array(
                'reqType' => 'GET',
                'path' => array('Contacts','?', 'opportunity_stats'),
                'pathVars' => array('module', 'record'),
                'method' => 'opportunityStats',
                'shortHelp' => 'Get opportunity statistics for current record',
                'longHelp' => '',
            ),
            'influencers' => array(
                'reqType' => 'GET',
                'path' => array('Contacts','?', 'influencers'),
                'pathVars' => array('module', 'record'),
                'method' => 'influencers',
                'shortHelp' => '',
                'longHelp' => '',
            ),
            'getFreeBusySchedule' => array(
                'reqType' => 'GET',
                'path' => array("Contacts", '?', "freebusy"),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getFreeBusySchedule',
                'shortHelp' => 'Retrieve a list of calendar event start and end times for specified person',
                'longHelp' => 'include/api/help/contact_get_freebusy_help.html',
            ),
        );
    }

    public function influencers(ServiceBase $api, array $args)
    {
        $account = $this->getAccountBean($api, $args);
        $relationships = array('calls' => 0, 'meetings' => 0);
        $data = array();
        foreach($relationships as $relationship => $ignore) {
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

            $relationshipData = $account->$relationship->query(array());

            foreach ($relationshipData['rows'] as $id => $value) {
                $bean = BeanFactory::getBean(ucfirst($relationship), $id);
                $bean->load_relationship('users');
                $userModuleName = $bean->users->getRelatedModuleName();
                $userSeed = BeanFactory::newBean($userModuleName);
                $userData = $bean->users->query(array());

                foreach($userData['rows'] as $userId => $user) {
                    if(empty($data[$userId])) {
                        $userBean = BeanFactory::getBean('Users', $userId);
                        if($userBean) {
                            $data[$userId] = array_merge($this->formatBean($api, $args, $userBean), $relationships);
                            $data[$userId][$relationship]++;
                        }
                    } else {
                        $data[$userId][$relationship]++;
                    }
                }
            }
        }
        return array_values($data);
    }

    public function opportunityStats(ServiceBase $api, array $args)
    {
        $account = $this->getAccountBean($api, $args);
        $data = $this->getAccountRelationship($api, $args, $account, 'opportunities', null);
        $return = array(
            'won' => array('amount_usdollar' => 0, 'count' => 0),
            'lost' => array('amount_usdollar' => 0, 'count' => 0),
            'active' => array('amount_usdollar' => 0, 'count' => 0)
        );
        foreach ($data as $record) {
            switch($record['sales_stage']) {
                case "Closed Lost":
                    $status = 'lost';
                    break;
                case "Closed Won":
                    $status = 'won';
                    break;
                default:
                    $status = 'active';
                    break;
            }
            $return[$status]['amount_usdollar'] += $record['amount_usdollar'];
            $return[$status]['count']++;
        }
        return $return;
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

    protected function getBean(ServiceBase $api, array $args)
    {
        // Load up the bean
        $record = BeanFactory::getBean($args['module'], $args['record']);

        if (empty($record)) {
            throw new SugarApiExceptionNotFound('Could not find parent record '.$args['record'].' in module '.$args['module']);
        }
        if (!$record->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }
        return $record;
    }

    protected function getAccountBean(ServiceBase $api, array $args)
    {
        $record = $this->getBean($api, $args);
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
