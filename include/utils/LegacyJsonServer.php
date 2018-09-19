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

/**
 * Class LegacyJsonServer
 *
 * This class is a temporary wrapper around the legacy json_server.php functions
 * that were used for BWC - Meetings & Calls specifically - for retrieving and
 * querying records via an ajax call. This functionality is needed by
 * CalendarEventsApi REST API until it can be replace with functional equivalence
 * in the UnifiedSearchApi.
 */
class LegacyJsonServer
{
    /**
     * Retrieve a simplified version of SugarBean - used in legacy/bwc code only
     * via json_server.php - wrapped to be consistent with the query function
     *
     * @param string $request_id
     * @param array $params
     * @return array
     */
    public function retrieve($request_id, $params)
    {
        $focus = BeanFactory::getBean($params[0]['module'], $params[0]['record']);

        // to get a simplified version of the sugarbean
        $module_arr = $this->populateBean($focus);

        $response = array();
        $response['id'] = $request_id;
        $response['result'] = array("status" => "success", "record" => $module_arr);
        return $response;
    }

    /**
     * Same as retrieve method, but returns the results as a JSON string
     *
     * @param string $request_id
     * @param array $params
     * @return string
     */
    public function jsonRetrieve($request_id, $params)
    {
        return $this->encodeResult($this->retrieve($request_id, $params));
    }

    /**
     * Builds and runs a query against multiple modules
     * More flexible, yet less performant than what currently exists for searching
     * in the Unified Search API (ElasticSearch & SpotSearch)
     *
     * DO NOT USE FOR NEW PURPOSES
     * This should only used as a stop-gap for Meetings & Calls invitee search until
     * Unified Search API can support related fields (like account_name)
     *
     * @param string $request_id
     * @param array $params
     * @param bool $returnFullBeans
     * @return array
     */
    public function query($request_id, $params, $returnFullBeans = false)
    {
        global $response, $sugar_config;
        $json = getJSONobj();

        // override query limits
        if ($sugar_config['list_max_entries_per_page'] < 31) {
            $sugar_config['list_max_entries_per_page'] = 31;
        }

        $args = $params[0];

        //decode condition parameter values..
        if (is_array($args['conditions'])) {
            foreach ($args['conditions'] as $key => $condition) {
                if (!empty($condition['value'])) {
                    $where = $json->decode(utf8_encode($condition['value']));
                    // cn: bug 12693 - API change due to CSRF security changes.
                    $where = empty($where) ? $condition['value'] : $where;
                    $args['conditions'][$key]['value'] = $where;
                }
            }
        }

        $list_return = array();

        if (!empty($args['module'])) {
            $args['modules'] = array($args['module']);
        }

        foreach ($args['modules'] as $module) {
            $focus = BeanFactory::newBean($module);

            $query_orderby = '';
            if (!empty($args['order'])) {
                $query_orderby = preg_replace('/[^\w_.-]+/i', '', $args['order']['by']);
                if (!empty($args['order']['desc'])) {
                    $query_orderby .= " DESC";
                } else {
                    $query_orderby .= " ASC";
                }
            }

            $query_limit = '';
            if (!empty($args['limit'])) {
                $query_limit = (int)$args['limit'];
            }
            $query_where = $this->constructWhere($args, $focus->table_name, $module);
            $list_arr = array();
            if ($focus->ACLAccess('ListView', true)) {
                $focus->ungreedy_count = false;
                $curlist = $focus->get_list($query_orderby, $query_where, 0, $query_limit, -1, 0);
                $list_return = array_merge($list_return, $curlist['list']);
            }
        }

        $app_list_strings = null;

        for ($i = 0; $i < count($list_return); $i++) {
            if (isset($list_return[$i]->emailAddress) && is_object($list_return[$i]->emailAddress)) {
                $list_return[$i]->emailAddress->handleLegacyRetrieve($list_return[$i]);
            }

            $list_arr[$i] = array();
            $list_arr[$i]['fields'] = array();
            $list_arr[$i]['module'] = $list_return[$i]->object_name;

            foreach ($args['field_list'] as $field) {

                //handle links
                if (isset($list_return[$i]->field_defs[$field])
                    && $list_return[$i]->field_defs[$field]['type'] == "relate") {
                    $linked = current(
                        $list_return[$i]->get_linked_beans(
                            $list_return[$i]->field_defs[$field]['link'],
                            get_valid_bean_name($list_return[$i]->field_defs[$field]['module'])
                        )
                    );
                    $list_return[$i]->$field = "";
                    if (is_object($linked)) {
                        $linkFieldName = $list_return[$i]->field_defs[$field]['rname'];
                        $list_return[$i]->$field = $linked->$linkFieldName;
                    }
                }

                if (!empty($list_return[$i]->field_defs[$field]['sensitive'])) {
                    continue;
                }

                // handle enums
                if ((isset($list_return[$i]->field_defs[$field]['type'])
                        && $list_return[$i]->field_defs[$field]['type'] == 'enum')
                    || (isset($list_return[$i]->field_defs[$field]['custom_type'])
                        && $list_return[$i]->field_defs[$field]['custom_type'] == 'enum'
                    )) {
                    // get fields to match enum vals
                    if (empty($app_list_strings)) {
                        if (isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '') {
                            $current_language = $_SESSION['authenticated_user_language'];
                        } else {
                            $current_language = $sugar_config['default_language'];
                        }
                        $app_list_strings = return_app_list_strings_language($current_language);
                    }

                    // match enum vals to text vals in language pack for return
                    if (!empty($app_list_strings[$list_return[$i]->field_defs[$field]['options']])) {
                        if (!empty($app_list_strings[
                            $list_return[$i]->field_defs[$field]['options']
                        ][$list_return[$i]->$field])) {
                            $list_return[$i]->$field = $app_list_strings[
                                $list_return[$i]->field_defs[$field]['options']
                            ][$list_return[$i]->$field];
                        } else {
                            $list_return[$i]->$field = '';
                        }
                    }
                }

                $list_arr[$i]['fields'][$field] = $list_return[$i]->$field;
            }

            if ($returnFullBeans) {
                $list_arr[$i]['bean'] = $list_return[$i];
            }
        }


        $response['id'] = $request_id;
        $response['result'] = array("list" => $list_arr);
        return $response;
    }

    /**
     * Same as query method, but returns the results as a json string
     *
     * @param string $request_id
     * @param array $params
     * @return string
     */
    public function jsonQuery($request_id, $params)
    {
        return $this->encodeResult($this->query($request_id, $params));
    }

    /**
     * Maps from a bean format into a simple module array format.
     * Exposed as public so it can be used by json_server.php legacy code
     *
     * @param SugarBean $focus
     * @return array
     */
    public function populateBean(&$focus)
    {
        $all_fields = $focus->column_fields;
        // MEETING SPECIFIC
        $all_fields = array_merge($all_fields,
            array('required', 'accept_status', 'name')); // need name field for contacts and users
        //$all_fields = array_merge($focus->column_fields,$focus->additional_column_fields);

        $module_arr = array();

        $module_arr['module'] = $focus->object_name;
        $module_arr['module_name'] = $focus->object_name;

        $module_arr['fields'] = array();

        foreach ($all_fields as $field) {
            if (isset($focus->$field) && !is_object($focus->$field)) {
                $focus->$field = from_html($focus->$field);
                $focus->$field = preg_replace("/\r\n/", "<BR>", $focus->$field);
                $focus->$field = preg_replace("/\n/", "<BR>", $focus->$field);
                $module_arr['fields'][$field] = $focus->$field;
            }
        }
        $GLOBALS['log']->debug("JSON_SERVER:populate bean:");
        return $module_arr;
    }

    /**
     * Build where clause for a given module - used by query function
     * Exposed as public so it can be used by json_server.php legacy code
     *
     * @param array $query_obj
     * @param string $table
     * @param string $module
     * @return string
     */
    public function constructWhere(&$query_obj, $table = '', $module = null)
    {
        if (!empty($table)) {
            $table .= ".";
        }
        $cond_arr = array();

        if (!is_array($query_obj['conditions'])) {
            $query_obj['conditions'] = array();
        }

        foreach ($query_obj['conditions'] as $condition) {
            if ($condition['name'] == 'user_hash' ||
                ($condition['name'] == 'account_name' && $module === "Users")) {
                continue;
            }
            if ($condition['name'] == 'email1' or $condition['name'] == 'email2' or $condition['name'] == 'email') {

                $email1_value = $GLOBALS['db']->quote(strtoupper($condition['value']));
                $email1_condition = " {$table}id in ( SELECT  er.bean_id AS id FROM email_addr_bean_rel er, " .
                    "email_addresses ea WHERE ea.id = er.email_address_id " .
                    "AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = '{$module}' AND email_address_caps LIKE '%{$email1_value}%' )";

                array_push($cond_arr, $email1_condition);
            } elseif ($condition['name'] == 'account_name' && $module == "Contacts") {
                $account_name = " {$table}id in ( SELECT  lnk.contact_id AS id FROM accounts ac, " .
                    "accounts_contacts lnk WHERE ac.id = lnk.account_id " .
                    "AND ac.deleted = 0 AND lnk.deleted = 0 AND ac.name LIKE '%" .
                    $GLOBALS['db']->quote($condition['value']) . "%' )";
                array_push($cond_arr, $account_name);
            } elseif ($condition['name'] === 'account_name' && $module === 'Leads') {
                $account_name = " {$table}id in ( SELECT leads.id AS id FROM accounts ac, leads " .
                    "WHERE ac.id = leads.account_id AND ac.deleted = 0 AND leads.deleted = 0 AND ac.name LIKE '%" .
                    $GLOBALS['db']->quote($condition['value']) . "%' )";
                array_push($cond_arr, $account_name);
            } elseif ($condition['name'] === 'full_name') {
                $query_parts = explode(' ', $condition['value']);
                $first_name_query = array_shift($query_parts);
                $name_query = "({$table}first_name like '" . $GLOBALS['db']->quote($first_name_query) . "%'";
                if (count($query_parts) > 0) {
                    $last_name_query = implode($query_parts);
                    $full_name_group = 'and';
                } else {
                    $last_name_query = $first_name_query;
                    $full_name_group = 'or';
                }
                $name_query .= " {$full_name_group} {$table}last_name like '" . $GLOBALS['db']->quote($last_name_query) . "%')";
                array_push($cond_arr, $name_query);
            } else {
                if ($condition['op'] == 'contains') {
                    $cond_arr[] = $table . $GLOBALS['db']->getValidDBName($condition['name']) . " like '%" . $GLOBALS['db']->quote($condition['value']) . "%'";
                }
                if ($condition['op'] == 'like_custom') {
                    $like = '';
                    if (!empty($condition['begin'])) {
                        $like .= $GLOBALS['db']->quote($condition['begin']);
                    }
                    $like .= $GLOBALS['db']->quote($condition['value']);
                    if (!empty($condition['end'])) {
                        $like .= $GLOBALS['db']->quote($condition['end']);
                    }
                    $cond_arr[] = $table . $GLOBALS['db']->getValidDBName($condition['name']) . " like '$like'";
                } else { // starts_with
                    $cond_arr[] = $table . $GLOBALS['db']->getValidDBName($condition['name']) . " like '" . $GLOBALS['db']->quote($condition['value']) . "%'";
                }
            }
        }

        $group = strtolower(trim($query_obj['group']));
        if ($group != "and" && $group != "or") {
            $group = "and";
        }
        $result = implode(" $group ", $cond_arr);

        //if filtering users table ensure status is Active
        if ($table == 'users.') {
            if (count($cond_arr) > 0) {
                $result = $result . " and ";
            }
            $result = $result . "users.status='Active'";
        }

        //add parenthesis because visibility will be added as an additional 'AND' clause
        return '(' . $result . ')';
    }

    /**
     * Helper function that converts given result into JSON string
     *
     * @param array $result
     * @return string
     */
    protected function encodeResult($result)
    {
        $json = getJSONobj();
        return $json->encode($result, true);
    }
}
