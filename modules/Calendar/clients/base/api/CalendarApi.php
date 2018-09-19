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


class CalendarApi extends ModuleApi {
    public function registerApiRest() {
        return array(
            'invitee_search' => array(
                'reqType' => 'GET',
                'path' => array('Calendar', 'invitee_search'),
                'pathVars' => array('', ''),
                'method' => 'inviteeSearch',
                'shortHelp' => 'This method searches for people to invite to an event',
                'longHelp' => 'modules/Calendar/clients/base/api/help/calendar_invitee_search_get_help.html',
            ),
        );
    }

    /**
     * Run a search for possible invitees to invite to a calendar event.
     *
     * TODO: currently uses legacy code - either replace this backend
     *   implementation with global search when it supports searching across
     *   linked fields like account_name or remove the endpoint altogether.
     *   Either way - remember to update api docs.
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionMissingParameter
     */
    public function inviteeSearch(ServiceBase $api, array $args)
    {
        $api->action = 'list';
        $this->requireArgs($args, array('q', 'module_list', 'search_fields', 'fields'));

        //make legacy search request
        $params = $this->buildSearchParams($args);
        $searchResults = $this->runInviteeQuery($params);

        return $this->transformInvitees($api, $args, $searchResults);
    }

    /**
     * Map from global search api arguments to search params expected by
     * legacy invitee search code
     *
     * @param array $args
     * @return array
     */
    protected function buildSearchParams(array $args)
    {
        $modules = explode(',', $args['module_list']);
        $searchFields = explode(',', $args['search_fields']);
        $fieldList = explode(',', $args['fields']);
        $fieldList = array_merge($fieldList, $searchFields);

        $conditions = array();
        foreach ($searchFields as $searchField) {
            $conditions[] = array(
                'name' => $searchField,
                'op' => 'starts_with',
                'value' => $args['q'],
            );
        }

        return array(
            array(
                'modules' => $modules,
                'group' => 'or',
                'field_list' => $fieldList,
                'conditions' => $conditions,
            ),
        );
    }

    /**
     * Run the the legacy invitee query
     *
     * @param $params
     * @return array
     */
    protected function runInviteeQuery($params)
    {
        $requestId = '1'; //not really used
        $jsonServer = new LegacyJsonServer();
        return $jsonServer->query($requestId, $params, true);
    }

    /**
     * Map from legacy invitee search code's result format to a format
     * that is closer to what global search returns
     *
     * Pagination is not supported
     *
     * @param ServiceBase $api
     * @param array $args
     * @param $searchResults
     * @return array
     */
    protected function transformInvitees(ServiceBase $api, array $args, $searchResults)
    {
        $resultList = $searchResults['result']['list'];
        $records = array();
        foreach ($resultList as $result) {
            if (!empty($args['erased_fields'])) {
                $options = ['erased_fields' => true, 'use_cache' => false, 'encode' => false];
                $result['bean'] = BeanFactory::retrieveBean($result['bean']->module_dir, $result['bean']->id, $options);
            }
            $record = $this->formatBean($api, $args, $result['bean']);
            $highlighted = $this->getMatchedFields($args, $record, 1);
            $record['_search'] = array(
                'highlighted' => $highlighted,
            );
            $records[] = $record;
        }

        return array(
            'next_offset' => -1,
            'records' => $records,
        );
    }

    /**
     * Returns an array of fields that matched search query
     *
     * @param array $args Api arguments
     * @param array $record Search result formatted from bean into array form
     * @param int $maxFields Number of highlighted fields to return, 0 = all
     *
     * @return array matched fields key value pairs
     */
    protected function getMatchedFields(array $args, $record, $maxFields = 0)
    {
        $query = $args['q'];
        $searchFields = explode(',', $args['search_fields']);

        $matchedFields = array();
        foreach ($searchFields as $searchField) {
            if (!isset($record[$searchField])) {
                continue;
            }

            $fieldValues = array();
            if ($searchField === 'email') {
                //can be multiple email addresses
                foreach ($record[$searchField] as $email) {
                    $fieldValues[] = $email['email_address'];
                }
            } elseif (is_string($record[$searchField])) {
                $fieldValues = array($record[$searchField]);
            }

            foreach ($fieldValues as $fieldValue) {
                if (stripos($fieldValue, $query) !== false) {
                    $matchedFields[$searchField] = array($fieldValue);
                }
            }
        }

        $ret = array();
        if (!empty($matchedFields) && is_array($matchedFields)) {
            $highlighter = new SugarSearchEngineHighlighter();
            $highlighter->setModule($record['_module']);
            $ret = $highlighter->processHighlightText($matchedFields);
            if ($maxFields > 0) {
                $ret = array_slice($ret, 0, $maxFields);
            }
        }

        return $ret;
    }
}
