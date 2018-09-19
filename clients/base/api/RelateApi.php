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


class RelateApi extends FilterApi {
    public function registerApiRest() {
        return array(
            'filterRelatedRecords' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', '?', 'filter'),
                'pathVars' => array('module', 'record', '', 'link_name', ''),
                'jsonParams' => array('filter'),
                'method' => 'filterRelated',
                'shortHelp' => 'Lists related filtered records.',
                'longHelp' => 'include/api/help/module_record_link_link_name_filter_get_help.html',
            ),
            'filterRelatedRecordsCount' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', '?', 'filter', 'count'),
                'pathVars' => array('module', 'record', '', 'link_name', '', ''),
                'jsonParams' => array('filter'),
                'method' => 'filterRelatedCount',
                'shortHelp' => 'Lists related filtered records.',
                'longHelp' => 'include/api/help/module_record_link_link_name_filter_get_help.html',

            ),
            'listRelatedRecords' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', '?'),
                'pathVars' => array('module', 'record', '', 'link_name'),
                'jsonParams' => array('filter'),
                'method' => 'filterRelated',
                'shortHelp' => 'Lists related records.',
                'longHelp' => 'include/api/help/module_record_link_link_name_filter_get_help.html',
            ),
            'listRelatedRecordsCount' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'link', '?', 'count'),
                'pathVars' => array('module', 'record', '', 'link_name', ''),
                'jsonParams' => array('filter'),
                'method' => 'filterRelatedCount',
                'shortHelp' => 'Counts all filtered related records.',
                'longHelp' => 'include/api/help/module_record_link_link_name_filter_get_help.html',
            ),
        );
    }

    public function filterRelatedSetup(ServiceBase $api, array $args)
    {
        // Load the parent bean.
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

        // Load the relationship.
        $linkName = $args['link_name'];
        if (!$record->load_relationship($linkName)) {
            // The relationship did not load.
            throw new SugarApiExceptionNotFound('Could not find a relationship named: ' . $args['link_name']);
        }
        $linkModuleName = $record->$linkName->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('list')) {
            throw new SugarApiExceptionNotAuthorized('No access to list records for module: ' . $linkModuleName);
        }

        $options = $this->parseArguments($api, $args, $linkSeed);

        // If they don't have fields selected we need to include any link fields
        // for this relationship
        if (empty($args['fields']) && is_array($linkSeed->field_defs)) {
            $relatedLinkName = $record->$linkName->getRelatedModuleLinkName();
            $options['linkDataFields'] = array();
            foreach ($linkSeed->field_defs as $field => $def) {
                if (empty($def['rname_link']) || empty($def['link'])) {
                    continue;
                }
                if ($def['link'] != $relatedLinkName) {
                    continue;
                }
                // It's a match
                $options['linkDataFields'][] = $field;
                $options['select'][] = $field;
            }
        }

        // In case the view parameter is set, reflect those fields in the
        // fields argument as well so formatBean only takes those fields
        // into account instead of every bean property.
        if (!empty($args['view'])) {
            $args['fields'] = $options['select'];
        } elseif (!empty($args['fields'])) {
            $args['fields'] = $this->normalizeFields($args['fields'], $options['displayParams']);
        }


        $q = self::getQueryObject($linkSeed, $options);

        // Some relationships want the role column ignored
        if (!empty($args['ignore_role'])) {
            $ignoreRole = true;
        } else {
            $ignoreRole = false;
        }

        $q->joinSubpanel($record, $linkName, array('joinType' => 'INNER', 'ignoreRole' => $ignoreRole));
        
        $q->setJoinOn(array('baseBeanId' => $record->id));

        if (!isset($args['filter']) || !is_array($args['filter'])) {
            $args['filter'] = array();
        }
        self::addFilters($args['filter'], $q->where(), $q);

        if (!sizeof($q->order_by)) {
            self::addOrderBy($q, $this->defaultOrderBy);
        }
        return array($args, $q, $options, $linkSeed);
    }

    public function filterRelated(ServiceBase $api, array $args)
    {

        $api->action = 'list';

        list($args, $q, $options, $linkSeed) = $this->filterRelatedSetup($api, $args);

        return $this->runQuery($api, $args, $q, $options, $linkSeed);
    }

    public function filterRelatedCount(ServiceBase $api, array $args)
    {
        $api->action = 'list';

        /** @var SugarQuery $q */
        list(, $q) = $this->filterRelatedSetup($api, $args);

        $q->select->selectReset()->setCountQuery();
        $q->limit = null;
        $q->orderByReset();

        $stmt = $q->compile()->execute();
        $count = (int) $stmt->fetchColumn();

        return array(
            'record_count' => $count,
        );
    }

}
