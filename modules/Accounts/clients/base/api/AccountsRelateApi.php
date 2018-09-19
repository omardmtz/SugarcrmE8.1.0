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


class AccountsRelateApi extends RelateApi
{
    public function registerApiRest() {
        return array(
            'filterRelatedRecords' => array(
                'reqType' => 'GET',
                'path' => array('Accounts', '?', 'link', '?', 'filter'),
                'pathVars' => array('module', 'record', '', 'link_name', ''),
                'jsonParams' => array('filter'),
                'method' => 'filterRelated',
                'shortHelp' => 'Lists related filtered records.',
                'longHelp' => 'include/api/help/module_record_link_link_name_filter_get_help.html',
            )
        );
    }

    public function filterRelated(ServiceBase $api, array $args)
    {
        if (empty($args['include_child_items']) || !in_array($args['link_name'], array('calls', 'meetings'))) {
            return parent::filterRelated($api, $args);
        }

        $api->action = 'list';

        $record = BeanFactory::retrieveBean($args['module'], $args['record']);

        if (empty($record)) {
            throw new SugarApiExceptionNotFound(
                sprintf(
                    'Could not find parent record %s in module: %s',
                    $args['record'],
                    $args['module']
                )
            );
        }

        if (!$record->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized(
                sprintf(
                    'No access to view records for module: %s',
                    $args['module']
                )
            );
        }

        $linkName = $args['link_name'];
        if (!$record->load_relationship($linkName)) {
            throw new SugarApiExceptionNotFound(
                sprintf(
                    'Could not find a relationship named: %s',
                    $args['link_name']
                )
            );
        }

        $linkModuleName = $record->$linkName->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('list')) {
            throw new SugarApiExceptionNotAuthorized(
                sprintf(
                    'No access to list records for module: %s',
                    $linkModuleName
                )
            );
        }

        $options = $this->parseArguments($api, $args, $linkSeed);
        $q = self::getQueryObject($linkSeed, $options);
        if (!isset($args['filter']) || !is_array($args['filter'])) {
            $args['filter'] = array();
        }

        self::addFilters($args['filter'], $q->where(), $q);

        // FIXME: this informations should be dynamically retrieved
        if ($linkModuleName === 'Meetings') {
            $linkToContacts = 'contacts';
            $childModuleTable = 'meetings';
            $childRelationshipTable = 'meetings_contacts';
            $childRelationshipAlias = 'mc';
            $childLhsColumn = $childModuleTable . '.id';
            $childRhsColumn = $childRelationshipAlias . '.meeting_id';

        } else {
            $linkToContacts = 'contacts';
            $childModuleTable = 'calls';
            $childRelationshipTable = 'calls_contacts';
            $childRelationshipAlias = 'cc';
            $childLhsColumn = $childModuleTable . '.id';
            $childRhsColumn = $childRelationshipAlias . '.call_id';
        }

        // Join contacts if not already requested.
        $contactJoin = $q->join($linkToContacts, array('joinType'=>'LEFT'));

        // FIXME: there should be the ability to specify from which related module
        // the child items should be loaded
        $q->joinTable('accounts_contacts', array('alias' => 'ac', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('ac.contact_id', $contactJoin->joinName() . '.id')
            ->equals('ac.deleted', 0);

        $q->joinTable('accounts', array('alias' => 'a', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('a.id', 'ac.account_id')
            ->equals('a.deleted', 0);

        $where = $q->where()->queryOr();
        $where->queryAnd()
            ->equals('ac.account_id', $record->id);
        $where->queryAnd()
            ->equals($childModuleTable . '.parent_type', 'Accounts')
            ->equals($childModuleTable . '.parent_id', $record->id);

        return $this->runQuery($api, $args, $q, $options, $linkSeed);
    }
}
