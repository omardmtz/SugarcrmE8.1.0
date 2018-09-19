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

use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Repository;

/**
 * Global search
 * @api
 */
class SugarSpot
{
    protected $module = "";

    /**
     * @param string $current_module
     */
    public function __construct($current_module = "")
    {
        $this->module = $current_module;
    }

    /**
     * searchAndDisplay
     *
     * Performs the search and returns the HTML widget containing the results
     *
     * @param  $query string what we are searching for
     * @param  $modules array modules we are searching in
     * @param  $offset int search result offset
     * @return string HTML code containing results
     *
     * @deprecated deprecated since 6.5
     */
    public function searchAndDisplay($query, $modules, $offset = -1)
    {
        $query_encoded = urlencode($query);
        $formattedResults = $this->formatSearchResultsToDisplay($query, $modules, $offset);
        $displayMoreForModule = $formattedResults['displayMoreForModule'];
        $displayResults = $formattedResults['displayResults'];

        $ss = new Sugar_Smarty();
        $ss->assign('displayResults', $displayResults);
        $ss->assign('displayMoreForModule', $displayMoreForModule);
        $ss->assign('appStrings', $GLOBALS['app_strings']);
        $ss->assign('appListStrings', $GLOBALS['app_list_strings']);
        $ss->assign('queryEncoded', $query_encoded);
        return $ss->fetch(SugarAutoLoader::existingCustomOne('include/SearchForm/tpls/SugarSpot.tpl'));
	}


    protected function formatSearchResultsToDisplay($query, $modules, $offset = -1)
    {
        $results = $this->_performSearch($query, $modules, $offset);
        $displayResults = array();
        $displayMoreForModule = array();
        //$actions=0;
        foreach ($results as $m => $data) {
            if (empty($data['data'])) {
                continue;
            }

            $countRemaining = $data['pageData']['offsets']['total'] - count($data['data']);
            if ($offset > 0) {
                $countRemaining -= $offset;
            }

            if ($countRemaining > 0) {
                $displayMoreForModule[$m] = array('query' => $query,
                    'offset' => $data['pageData']['offsets']['next']++,
                    'countRemaining' => $countRemaining);
            }

            foreach ($data['data'] as $row) {
                $name = '';

                //Determine a name to use
                if (!empty($row['NAME'])) {
                    $name = $row['NAME'];
                }
                else if (!empty($row['DOCUMENT_NAME'])) {
                    $name = $row['DOCUMENT_NAME'];
                }
                else {
                    $foundName = '';
                    foreach ($row as $k => $v) {
                        if (strpos($k, 'NAME') !== false) {
                            if (!empty($row[$k])) {
                                $name = $v;
                                break;
                            }
                            else if (empty($foundName)) {
                                $foundName = $v;
                            }
                        }
                    }

                    if (empty($name)) {
                        $name = $foundName;
                    }
                }

                $displayResults[$m][$row['ID']] = $name;
            }
        }

        return array('displayResults' => $displayResults, 'displayMoreForModule' => $displayMoreForModule);
    }
	/**
	 * Returns the array containing the $searchFields for a module.  This function
	 * first checks the default installation directories for the SearchFields.php file and then
	 * loads any custom definition (if found)
	 *
	 * @param  $moduleName String name of module to retrieve SearchFields entries for
	 * @return array of SearchFields
	 */
	protected static function getSearchFields( $moduleName )
	{
	    return SugarAutoLoader::loadSearchFields($moduleName);
	}


    /**
     * Get count from query
     * @param SugarBean $seed
     * @param string $main_query
     */
    protected function _getCount($seed, $main_query)
    {
        $result = $seed->db->query("SELECT COUNT(*) as c FROM ($main_query) main");
        $row = $seed->db->fetchByAssoc($result);
        return isset($row['c']) ? $row['c'] : 0;
    }

    /**
     * Determine which modules should be searched against.
     *
     * @return array
     */
    protected function getSearchModules()
    {
        $usa = new UnifiedSearchAdvanced();
        $unified_search_modules_display = $usa->getUnifiedSearchModulesDisplay();

        // load the list of unified search enabled modules
        $modules = array();

        //check to see if the user has  customized the list of modules available to search
        $users_modules = $GLOBALS['current_user']->getPreference('globalSearch', 'search');

        if (!empty($users_modules)) {
            // use user's previous selections
            foreach ($users_modules as $key => $value) {
                if (isset($unified_search_modules_display[$key]) && !empty($unified_search_modules_display[$key]['visible'])) {
                    $modules[$key] = $key;
                }
            }
        }
        else {
            foreach ($unified_search_modules_display as $key => $data) {
                if (!empty($data['visible'])) {
                    $modules[$key] = $key;
                }
            }
        }
        // make sure the current module appears first in the list
        if (isset($modules[$this->module])) {
            unset($modules[$this->module]);
            $modules = array_merge(array($this->module => $this->module), $modules);
        }

        return $modules;
    }

    /**
     * Perform a search
     *
     * @param $query string what we are searching for
     * @param $offset int search result offset
     * @param  $limit  int    search limit
     * @param  $options array  An array of options to better control how the result set is generated
     * @return array
     */
    public function search($query, $offset = -1, $limit = 20, $options = array())
    {
        if (isset($options['modules']) && !empty($options['modules']))
            $modules = $options['modules'];
        else
            $modules = $this->getSearchModules();

        return $this->_performSearch($query, $modules, $offset, $limit, $options);

    }

    /**
     * _performSearch
     *
     * Performs the search from the global search field.
     *
     * @param  $query   string what we are searching for
     * @param  $modules array  modules we are searching in
     * @param  $offset  int   search result offset
     * @param  $limit  int    search limit
     * @param  $options array  An array of options to better control how the result set is generated
     * @return array
     */
    protected function _performSearch($query, $modules, $offset = -1, $limit = 20, $options = array())
    {
        if (empty($query)) {
            if (!((isset($options['my_items']) && $options['my_items'] == true)
                || (isset($options['favorites']) && $options['favorites'] == 2)
                || (isset($options['allowEmptySearch']) && $options['allowEmptySearch'] == true))
            ) {
                // Make sure we aren't just searching for my items or favorites
                return array();
            }
        }
        $primary_module = '';
        $results = array();
        require_once 'include/SearchForm/SearchForm2.php';
        $where = '';
        $searchEmail = preg_match('/^([^%]|%)*@([^%]|%)*$/', $query);

        // bug49650 - strip out asterisks from query in case
        // user thinks asterisk is a wildcard value
        $query = str_replace( '*' , '' , $query );

        $limit = !empty($GLOBALS['sugar_config']['max_spotresults_initial']) ? $GLOBALS['sugar_config']['max_spotresults_initial'] : 5;
        if ($offset !== -1) {
            $limit = !empty($GLOBALS['sugar_config']['max_spotresults_more']) ? $GLOBALS['sugar_config']['max_spotresults_more'] : 20;
        }
        $totalCounted = empty($GLOBALS['sugar_config']['disable_count_query']);

        if (empty($options['orderBy'])) {
            $orderBy = "date_modified DESC";
        } else {
            $orderBy = $options['orderBy'];
        }

        foreach ($modules as $moduleName) {
            if (empty($primary_module)) {
                $primary_module = $moduleName;
            }

            $searchFields = SugarSpot::getSearchFields($moduleName);

            if (empty($searchFields[$moduleName])) {
                continue;
            }

            $seed = BeanFactory::newBean($moduleName);
            if(!$seed->ACLAccess('ListView')) continue;

            foreach ($searchFields[$moduleName] as $k => $v) 
            {
                /*
                 * Restrict Bool searches from free-form text searches.
                 * Reasoning: cases.status is unified_search = true
                 * searching for New will all open_only cases due to open_only being set as well
                 * see: modules/Cases/metadata/Searchform.php
                 * This would cause incorrect search results due to the open only returning more than only status like New%
                 */

                if (isset($v['type']) && !empty($v['type']) && $v['type'] == 'bool') {
                    unset($searchFields[$moduleName][$k]);
                    continue;
                }

                $keep = false;
                $searchFields[$moduleName][$k]['value'] = $query;
                if (!empty($searchFields[$moduleName][$k]['force_unifiedsearch'])) {
                    continue;
                }

                if(!empty($GLOBALS['dictionary'][$seed->object_name]['unified_search']))
                {
                    if(empty($GLOBALS['dictionary'][$seed->object_name]['fields'][$k]['unified_search']))
                    {
                       
                        if(isset($searchFields[$moduleName][$k]['db_field']))
                        {
                            foreach($searchFields[$moduleName][$k]['db_field'] as $field)
                            {
                                if(!empty($GLOBALS['dictionary'][$seed->object_name]['fields'][$field]['unified_search']))
                                {
                                    if(isset($GLOBALS['dictionary'][$seed->object_name]['fields'][$field]['type']))
                                    {
                                        if(!$this->filterSearchType($GLOBALS['dictionary'][$seed->object_name]['fields'][$field]['type'], $query))
                                        {
                                            unset($searchFields[$moduleName][$k]);
                                            continue;
                                        }
                                    }

                                    $keep = true;
                                }
                            } //foreach
                        }
                        # Bug 42961 Spot search for custom fields
                        if (!$keep && (isset($v['force_unifiedsearch']) == false || $v['force_unifiedsearch'] != true)) 
                        {
                            if (strpos($k, 'email') === false || !$searchEmail) {
                                unset($searchFields[$moduleName][$k]);
                            }
                        }
                    } 
                    else 
                    {
                        if ($GLOBALS['dictionary'][$seed->object_name]['fields'][$k]['type'] == 'int' && !is_numeric($query)) {
                            unset($searchFields[$moduleName][$k]);
                        }
                    }
                } 
                else if (empty($GLOBALS['dictionary'][$seed->object_name]['fields'][$k])) 
                {
                    //If module did not have unified_search defined, then check the exception for an email search before we unset
                    if (strpos($k, 'email') === false || !$searchEmail) 
                    {
                        unset($searchFields[$moduleName][$k]);
                    }
                } else if (!$this->filterSearchType($GLOBALS['dictionary'][$seed->object_name]['fields'][$k]['type'], $query)) 
                {
                    unset($searchFields[$moduleName][$k]);
                }
            } //foreach
            // setup the custom query options
            // reset these each time so we don't append my_items calls for tables not in the query
            // this would happen in global search
            $custom_select = $this->getOption($options, 'custom_select', $moduleName);
            $custom_from   = $this->getOption($options, 'custom_from', $moduleName);
            $custom_where  = $this->getOption($options, 'custom_where', $moduleName);
            if (isset($options['custom_where_module'][$moduleName])) {
                if (!empty($custom_where)) {
                    $custom_where .= " AND {$options['custom_where_module'][$moduleName]}";
                } else {
                    $custom_where = $options['custom_where_module'][$moduleName];
                }
            }
            $allowBlankSearch = false;
            // Add an extra search filter for my items
            // Verify the bean has assigned_user_id before we blindly assume it does
            if (!empty($options['my_items']) && $options['my_items'] == true && isset($seed->field_defs['assigned_user_id'])) {
                if(!empty($custom_where)) {
                    $custom_where .= " AND ";
                }
                $custom_where .= "{$seed->table_name}.assigned_user_id = '{$GLOBALS['current_user']->id}'";
                $allowBlankSearch = true;
            }

            if (!empty($options['untouched']) && $options['untouched'] !== false && isset($GLOBALS['dictionary'][$class]['fields']['last_activity_date'])) {
                if (!empty($custom_where)) {
                    $custom_where .= " AND ";
                }
                $days = (int)$options['untouched'];
                $lastActivityDate = gmdate('Y-m-d',time() - ($days * 24 * 60 * 60));
                $custom_where .= "{$seed->table_name}.last_activity_date <= '$lastActivityDate'";
                if(isset($GLOBALS['dictionary'][$class]['fields']['sales_stage'])){
                    $custom_where .= " AND {$seed->table_name}.sales_stage != 'Closed Won' AND {$seed->table_name}.sales_stage != 'Closed Lost'";
                }
                if(isset($GLOBALS['dictionary'][$class]['fields']['date_closed'])){
                                   $next30days = gmdate('Y-m-d',time() + (30 * 24 * 60 * 60));
                                   $custom_where .= "AND {$seed->table_name}.date_closed <= '$next30days'";
                }
                $allowBlankSearch = true;

            }
            // If we are just searching by favorites, add a no-op query parameter so we still search
            if (!empty($options['favorites']) && $options['favorites'] == 2) {
                $allowBlankSearch = true;
            }
            if (!empty($options['allowEmptySearch']) && $options['allowEmptySearch'] == true) {
                $allowBlankSearch = true;
            }

            //If no search field criteria matched then continue to next module
            if (empty($searchFields[$moduleName]) && !$allowBlankSearch) {
                continue;
            }

            $return_fields = array();
            if (isset($seed->field_defs['name'])) {
                $return_fields['name'] = $seed->field_defs['name'];
            }

            foreach ($seed->field_defs as $k => $v) {
                if (isset($seed->field_defs[$k]['type']) && ($seed->field_defs[$k]['type'] == 'name') && !isset($return_fields[$k])) {
                    $return_fields[$k] = $seed->field_defs[$k];
                }
            }

            if (!isset($return_fields['name'])) {
                // if we couldn't find any name fields, try search fields that have name in it
                foreach ($searchFields[$moduleName] as $k => $v) {
                    if (strpos($k, 'name') != -1 && isset($seed->field_defs[$k]) && !isset($seed->field_defs[$k]['source'])) {
                        $return_fields[$k] = $seed->field_defs[$k];
                        break;
                    }
                }
            }

            if (!isset($return_fields['name'])) {
                // last resort - any fields that have 'name' in their name
                foreach ($seed->field_defs as $k => $v) {
                    if (strpos($k, 'name') != -1 && isset($seed->field_defs[$k])
                        && !isset($seed->field_defs[$k]['source'])
                    ) {
                        $return_fields[$k] = $seed->field_defs[$k];
                        break;
                    }
                }
            }

            if (!isset($return_fields['name'])) {
                // FAIL: couldn't find id & name for the module
                $GLOBALS['log']->error("Unable to find name for module $moduleName");
                continue;
            }

            if (isset($return_fields['name']['fields'])) {
                // some names are composite name fields (e.g. last_name, first_name), add these to return list
                foreach ($return_fields['name']['fields'] as $field) {
                    $return_fields[$field] = $seed->field_defs[$field];
                }
            }
            if (!empty($options['fields'])) {
                $extraFields = array();
                if (!empty($options['fields'][$moduleName])) {
                    $extraFields = $options['fields'][$moduleName];
                } else if (!empty($options['fields']['_default'])) {
                    $extraFields = $options['fields']['_default'];
                }
                if (empty($extraFields)) {
                    // We set the 'fields' parameter, but left it blank, we should fetch all fields
                    $return_fields = array();
                } else {

                    foreach ( $extraFields as $extraField ) {
                        if ( $extraField == 'id' ) {
                            // Already in the list of fields it will return
                            continue;
                        }
                        if (isset($seed->field_defs[$extraField]) && !isset($return_fields[$extraField])) {
                            $return_fields[$extraField] = $seed->field_defs[$extraField];
                        }
                    }
                }
            }
            SugarAutoLoader::requireWithCustom('include/SearchForm/SearchForm2.php');
            $searchFormClass = SugarAutoLoader::customClass('SearchForm');
            $searchForm = new $searchFormClass($seed, $moduleName);

            $searchForm->setup(array($moduleName => array()), $searchFields, '', 'saved_views' /* hack to avoid setup doing further unwanted processing */);
            $where_clauses = $searchForm->generateSearchWhere();

            $orderBy = '';
            if (isset($options['orderBy'])) {
                $orderBy = $options['orderBy'];
            }

            $selectFields = '';
            if (!empty($options['selectFields']) ) {
                foreach ( $options['selectFields'] as $selectField ) {
                    $selectFields .= $seed->table_name.".".$selectField." ".$selectField.", ";
                }
                $selectFields = rtrim($selectFields,', ');
            }

            $showDeleted = (isset($options['deleted']))
                        ? $options['deleted']
                            : 0;

            if (empty($where_clauses)) {
                if ($allowBlankSearch) {
                    $ret_array = $seed->create_new_list_query(
                        $orderBy,
                        '',
                        $return_fields,
                        $options,
                        $showDeleted,
                        '',
                        true,
                        $seed,
                        true
                    );

                    if (!empty($selectFields)) {
                        $ret_array['select'] = "SELECT DISTINCT ".$selectFields;
                    }

                    if(!empty($custom_select)) {
                        $ret_array['select'] .= $custom_select;
                    }
                    if (!empty($custom_from)) {
                        $ret_array['from'] .= $custom_from;
                    }
                    if (!empty($custom_where)) {
                        if (!empty($ret_array['where'])) {
                            $ret_array['where'] .= " AND ";
                        }

                        // If there are no where clauses but there is a custom
                        // where but there is no return array where clause add
                        // an AND, otherwise you will get a situation where there
                        // is a where condition without an adjoining clause. This
                        // happens in Unified Search where there is team security
                        // added to the query.
                        // - rgonzalez
                        if (stripos($ret_array['where'], 'where') === false) {
                            $ret_array['where'] .= ' AND ';
                        }

                        $ret_array['where'] .= $custom_where;
                    }

                   $main_query = $ret_array['select'] . $ret_array['from'] . $ret_array['where'] . $ret_array['order_by'];
                } else {
                    continue;
                }
            }
            else if (count($where_clauses) > 1) {
                $query_parts = array();

                $ret_array_start = $seed->create_new_list_query(
                    $orderBy,
                    '',
                    $return_fields,
                    $options,
                    $showDeleted,
                    '',
                    true,
                    $seed,
                    true
                );

                $search_keys = array_keys($searchFields[$moduleName]);

                foreach ($where_clauses as $n => $clause) {
                    $allfields = $return_fields;
                    if (!empty($return_fields)) {
                        // We don't have any specific return_fields, so leaving this blank will include everything in the query
                        $skey = $search_keys[$n];
                        if (isset($seed->field_defs[$skey])) {
                            // Joins for foreign fields aren't produced unless the field is in result, hence the merge
                            $allfields[$skey] = $seed->field_defs[$skey];
                        }
                    }
                    // Individual UNION's don't allow order by
                    $ret_array = $seed->create_new_list_query(
                        '',
                        $clause,
                        $allfields,
                        $options,
                        $showDeleted,
                        '',
                        true,
                        $seed,
                        true
                    );

                    if (!empty($selectFields)) {
                        $ret_array_start['select'] = "SELECT DISTINCT ".$selectFields;
                    }

                    if(!empty($custom_select)) {
                        $ret_array_start['select'] .= $custom_select;
                    }
                    if (!empty($custom_from)) {
                        $ret_array['from'] .= $custom_from;
                    }
                    if (!empty($custom_where)) {
                        if (!empty($ret_array['where'])) {
                            $ret_array['where'] .= " AND ";
                        }
                        $ret_array['where'] .= $custom_where;
                    }

                    $query_parts[] = $ret_array_start['select'] . $ret_array['from'] . $ret_array['where'];
                }
                // So we add it to the output of all of the unions
                $main_query = "(".join(")\n UNION (", $query_parts).")";
                if ( !empty($orderBy) ) {
                    $main_query .= " ORDER BY ".$orderBy;
                }
            }
            else {
                foreach ($searchFields[$moduleName] as $k => $v) {
                    if (isset($seed->field_defs[$k])) {
                        $return_fields[$k] = $seed->field_defs[$k];
                    }
                }

                $ret_array = $seed->create_new_list_query(
                    $orderBy,
                    $where_clauses[0],
                    $return_fields,
                    $options,
                    $showDeleted,
                    '',
                    true,
                    $seed,
                    true
                );

                if (!empty($selectFields)) {
                    $ret_array['select'] = "SELECT DISTINCT ".$selectFields;
                }
                if(!empty($custom_select)) {
                    $ret_array['select'] .= $custom_select;
                }
                if (!empty($custom_from)) {
                    $ret_array['from'] .= $custom_from;
                }
                if (!empty($custom_where)) {
                    if (!empty($ret_array['where'])) {
                        $ret_array['where'] .= " AND ";
                    }
                    $ret_array['where'] .= $custom_where;
                }

                $main_query = $ret_array['select'] . $ret_array['from'] . $ret_array['where'] . $ret_array['order_by'];
            }

            $totalCount = null;
            if ($limit < -1) {
                $result = $seed->db->query($main_query);
            }
            else {
                if ($limit == -1) {
                    $limit = $GLOBALS['sugar_config']['list_max_entries_per_page'];
                }

                if ($offset === 'end') {
                    $totalCount = $this->_getCount($seed, $main_query);
                    if ($totalCount) {
                        $offset = (floor(($totalCount - 1) / $limit)) * $limit;
                    } else {
                        $offset = 0;
                    }
                }

                if (isset($options['limitPerModule'])) {
                    $limit = $options['limitPerModule'] - 1;
                }
                $result = $seed->db->limitQuery($main_query, $offset, $limit + 1);
            }

            $data = array();
            $count = 0;
            $ids = [];
            while ($count < $limit && ($row = $seed->db->fetchByAssoc($result))) {
                $temp = $seed->getCleanCopy();
                $temp->setupCustomFields($temp->module_dir);
                $temp->loadFromRow($row);
                // need to reload the seed because not all the fields will be filled in, for instance in bugs, all fields are wanted but the query does
                // not contain description, so the loadFromRow will not load it
                $temp->retrieve($temp->id, true, false); // this may be a deleted record
                if ( isset($options['return_beans']) && $options['return_beans'] ) {
                    $data[] = $temp;
                } else {
                    $data[] = $temp->get_list_view_data($return_fields);
                }
                $ids[] = $temp->id;
                if (isset($options['limitPerModule'])) {
                    // Don't keep track of the counted records if we are already applying per-module limits
                    $count = 0;
                } else {
                    $count++;
                }
            }

            $nextOffset = -1;
            $prevOffset = -1;
            $endOffset = -1;

            if (!isset($options['limitPerModule']) || !$options['limitPerModule']) {
                // Don't worry about the offsets if we are running a per-module limit
                if ($count >= $limit) {
                    $nextOffset = $offset + $limit;
                }

                if($offset > 0) {
                    $prevOffset = $offset - $limit;
                    if ($prevOffset < 0) {
                        $prevOffset = 0;
                    }
                }

                if( $count >= $limit && $totalCounted) {
                    if(!isset($totalCount)) {
                        $totalCount  = $this->_getCount($seed, $main_query);
                    }
                } else {
                    $totalCount = $count + $offset;
                }
            }

            // add erased_fields
            if (!empty($options['erased_fields'])) {
                $erasedFields = $this->getModuleErasedFields($moduleName, $ids);
                foreach ($data as $bean) {
                    $bean->erased_fields = $erasedFields[$bean->id]?? null;
                }
            }

            $pageData['offsets'] = array('current' => $offset, 'next' => $nextOffset, 'prev' => $prevOffset, 'end' => $endOffset, 'total' => $totalCount, 'totalCounted' => $totalCounted);
            $pageData['bean'] = array('objectName' => $seed->object_name, 'moduleDir' => $seed->module_dir);

            $results[$moduleName] = array("data" => $data, "pageData" => $pageData);
        }
        return $results;
    }

    /**
     * Returns the value of specified option in the context of module
     *
     * @param array  $options Search options
     * @param string $name    Option name
     * @param string $module  Module name
     *
     * @return mixed
     */
    protected function getOption(array $options, $name, $module = null)
    {
        if ($module !== null && isset($options['modules'][$module][$name])) {
            return $options['modules'][$module][$name];
        }

        if (isset($options[$name])) {
            return $options[$name];
        }

        return null;
    }


    /**
     * Function used to walk the array and find keys that map the queried string.
     * if both the pattern and module name is found the promote the string to thet top.
     */
    protected function _searchKeys($item1, $key, $patterns)
    {
        //make the module name singular....
        if ($patterns[1][strlen($patterns[1]) - 1] == 's') {
            $patterns[1] = substr($patterns[1], 0, (strlen($patterns[1]) - 1));
        }

        $module_exists = stripos($key, $patterns[1]); //primary module name.
        $pattern_exists = stripos($key, $patterns[0]); //pattern provided by the user.
        if ($module_exists !== false and $pattern_exists !== false) {
            $GLOBALS['matching_keys'] = array_merge(array(array('NAME' => $key, 'ID' => $key, 'VALUE' => $item1)), $GLOBALS['matching_keys']);
        }
        else {
            if ($pattern_exists !== false) {
                $GLOBALS['matching_keys'][] = array('NAME' => $key, 'ID' => $key, 'VALUE' => $item1);
            }
        }
    }


    /**
     * filterSearchType
     *
     * This is a private function to determine if the search type field should be filtered out based on the query string value
     *
     * @param String $type The string value of the field type (e.g. phone, date, datetime, int, etc.)
     * @param String $query The search string value sent from the global search
     * @return boolean True if the search type fits the query string value; false otherwise
     */
    protected function filterSearchType($type, $query)
    {
        switch ($type) {
            case 'id':
            case 'date':
            case 'datetime':
            case 'bool':
                return false;
                break;
            case 'int':
                if (!is_numeric($query)) {
                    return false;
                }
                break;
            case 'phone':
                //For a phone search we require at least three digits
                if (!preg_match('/[0-9]{3,}/', $query)) {
                    return false;
                }
            case 'decimal':
            case 'float':
                if (!preg_match('/[0-9]/', $query)) {
                    return false;
                }
                break;
        }
        return true;
    }

    /**
     * to get erased_fields for list of ids for a module
     * @param string $module
     * @param array $ids
     * @return array
     */
    protected function getModuleErasedFields(string $module, array $ids) : array
    {
        $erasedFields = [];
        if (count($ids) === 0) {
            return $erasedFields;
        }

        $seed = BeanFactory::newBean($module);
        if (!$seed->hasPiiFields()) {
            return $erasedFields;
        }
        $tableName = $seed->db->quoted($seed->getTableName());
        array_walk($ids, function (&$val, $key, $seed) {
            $val = $seed->db->quoted($val);
        }, $seed);
        $query = "SELECT data, bean_id FROM " .  Repository::DB_TABLE .
            " WHERE table_name = $tableName AND bean_id IN (" . implode(", ", $ids) . ")";

        $result = $seed->db->query($query);

        while ($row = $seed->db->fetchByAssoc($result, false)) {
            $erasedFields[$row['bean_id']] = json_decode($row['data'], true);
        }

        return $erasedFields;
    }
}
