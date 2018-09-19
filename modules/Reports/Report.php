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
require_once('modules/Reports/config.php');

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

class Report
{
    var $result;
    var $summary_result;
    var $total_result;
    var $row_start = 0;
    var $row_end = 0;
    var $row_count = 0;
    var $summary_row_start = 0;
    var $summary_row_end = 0;
    var $summary_row_count = 0;
    var $current_summary_row_count = 0;
    var $requested_fields_map = array();
    var $group_fields_map = array();
    var $summary_fields_map = array();
    var $full_table_list = array();

    /**
     * @var SugarBean[]
     */
    public $full_bean_list = array();

    /**
     * @var string
     */
    public $from;

    var $where;
    var $order_by;
    var $order_by_arr = array();
    var $summary_order_by_arr = array();
    var $order_by_special;
    var $group_by;
    var $group_order_by = '';
    var $module = 'Accounts';

    /**
     * @var SugarBean
     */
    var $focus;

    var $currency_symbol;

    /** @var Currency */
    var $currency_obj;
    var $name;
    var $select_fields = array();
    var $summary_select_fields = array();
    var $total_select_fields = array();
    var $report_def = array();
    var $group_defs_Info = array();
    var $addedColumns = 0;
    var $do_export = false;
    var $report_type = 'tabular';
    var $show_columns = false;
    var $enable_paging = true;

    var $query_list = array();
    var $query = '';
    var $summary_query = '';
    var $total_query = '';

    var $module_dir = 'Reports';
    var $time_date_obj = null;
    var $is_saved_report = false;
    var $saved_report_id = '';
    var $saved_report = null;
    var $alias_lookup = array();
    var $upgrade_lookup = array();

    var $all_fields = array();
    var $relationships = array();
    var $loaded_links = array();
    var $selected_loaded_links = array();
    var $selected_loaded_custom_links = array();
    var $layout_manager = null;
    var $plain_text_output = false;
    var $obj_array = array(); //array to store the object reference in get_next_row()
    var $default_report_def_str = '{"report_type":"tabular","display_columns":[],"summary_columns":[],"order_by":[{"name":"","sort_dir":""}],"filters_def":[],"group_defs":[],"links_def":[],"module":"Accounts","chart_type":"hBarF","chart_description":""}';

    var $select_already_defined_hash = array();

    var $do_chart = true;
    var $chart_header_row = array();
    var $chart_rows = array();
    var $chart_type = 'vBarF';
    var $table_name = 'saved_reports';
    var $chart_description = '';
    var $chart_group_position = array();
    var $chart_numerical_position = 0;
    var $group_header;
    var $group_column_is_invisible = 0;
    var $chart_total_header_row = array();
    var $jtcount = 0;

    // this is a var not in metadata that is set to bypass the sugar_die calls used throughout this class
    public $fromApi = false;

    // an empty bean
    protected $moduleBean;

    /**
     *
     * Default visibility options
     * @var array
     */
    protected $visibilityOpts = array(
        // notify visibility strategies we are running from reports
        'report_query' => true
    );

    /**
     * Array of invalid report fields. Populated during is_definition_valid() call.
     *
     * @var array
     */
    var $invalid_fields = array();

    /**
     * List of relationships/links that are no longer valid/deleted
     * @var array
     */
    public $invalid_links = array();

    /**
     * Array, which reflects whether or not to consider the currency in the query.
     *
     * @var array
     */
    protected $currency_join = array();

    /**
     * @var DBManager
     */
    public $db;

    /**
     * @var array
     */
    protected $group_by_arr = array();

    /**
     * @var array
     */
    protected $group_order_by_arr = array();

    /**
     *
     * SugarBeans in JOIN
     * @var array
     */
    public $extModules = array();

    /**
     * @var \Sugarcrm\Sugarcrm\Security\InputValidation\Request
     */
    protected $request;

    public function __construct($report_def_str = '', $filters_def_str = '', $panels_def_str = '')
    {
        global $current_user, $current_language, $app_list_strings;
        if (!isset($current_user) || empty($current_user)) {

            $current_user = BeanFactory::getBean('Users', '1');
        }

        $this->request = InputValidation::getService();

        //Scheduled reports don't have $_REQUEST.
        if ((!isset($_REQUEST['module']) || $_REQUEST['module'] == 'Reports') && !defined('SUGAR_PHPUNIT_RUNNER')) {
            Report::cache_modules_def_js();
        }

        $mod_strings = return_module_language($current_language, 'Reports');

        $this->report_max = (!empty($GLOBALS['sugar_config']['list_report_max_per_page']))
                ? $GLOBALS['sugar_config']['list_report_max_per_page'] : 100;
        $this->report_offset = (int) $this->request->getValidInputRequest('report_offset', null, 0);

        if ($this->report_offset < 0) $this->report_offset = 0;
        $this->time_date_obj = new TimeDate();
        $this->name = $mod_strings['LBL_UNTITLED'];
        $this->db = DBManagerFactory::getInstance('reports');

        $json = getJSONobj();
        if (empty($report_def_str)) {
            $this->report_def_str = $this->default_report_def_str;
            $this->report_def = $json->decode($this->report_def_str);
        } elseif (is_array($report_def_str)) {
            $this->report_def = $report_def_str;
            $this->report_def_str = $json->encode($report_def_str);
        } else {
            if (Report::is_old_content($report_def_str)) {
                $this->handleException('this report was created with an older version of reports. please upgrade');
            }
            $this->report_def_str = $report_def_str;
            $this->report_def = $json->decode($this->report_def_str);
        }

        // 5.1 Report Format - only called by the Wizard.
        if (!empty($filters_def_str)) {
            $this->parseUIFiltersDef($json->decode($filters_def_str), $json->decode($panels_def_str));
        }

        if (!empty($this->report_def['full_table_list']))
        {
            $this->fixReportDefs();
        }
        $this->cleanLabels();

        if (!empty($this->report_def['report_name'])) {
            $this->name = $this->report_def['report_name'];
        }
        if (!empty($this->report_def['module'])) {
            $this->module = $this->report_def['module'];
            $this->moduleBean = BeanFactory::newBean($this->module);
        }
        if (!empty($this->report_def['report_type'])) {
            $this->report_type = $this->report_def['report_type'];
        }
        if (!empty($this->report_def['display_columns']) && count($this->report_def['display_columns']) > 0 && $this->report_type == 'summary') {
            $this->show_columns = true;
        }
        if (!empty($this->report_def['chart_type'])) {
            $this->chart_type = $this->report_def['chart_type'];
        } else
        {
            $this->report_def['chart_type'] = 'none';
            $this->chart_type = $this->report_def['chart_type'];
        }
        if (!empty($this->report_def['chart_description'])) {
            $this->chart_description = $this->report_def['chart_description'];
        }

        //Upgrade the pre-5.1 reports that had a summary column field that wasn't in the group by or an aggregate field.
        if (!empty ($this->report_def['summary_columns'])) {
            foreach ($this->report_def['summary_columns'] as $summary_column) {
                if (!isset($summary_column['group_function']) && !isset($summary_column['is_group_by']) && !isset($summary_column['column_function'])) {
                    $isInGroupBy = false;
                    foreach ($this->report_def['group_defs'] as $group_by_col) {
                        if ($summary_column['table_key'] == $group_by_col['table_key'] && $summary_column['name'] == $group_by_col['name']) {
                            $isInGroupBy = true;
                            break;
                        }
                    }
                    if (!$isInGroupBy)
                        $this->report_def['group_defs'][count($this->report_def['group_defs'])] = $summary_column;
                }
            }
        }

        if (!empty($this->report_def['full_table_list'])) {
            $this->full_table_list = $this->report_def['full_table_list'];
        }
        else
        {
            $this->full_table_list['self']['value'] = $this->module;
            $this->full_table_list['self']['module'] = $this->module;
            $this->full_table_list['self']['label'] = isset($app_list_strings['moduleList'][$this->module])
                    ? $app_list_strings['moduleList'][$this->module] : $this->module;
            $this->full_table_list['self']['parent'] = '';
            $this->full_table_list['self']['children'] = array();
        }

        $tempFullTableList = array();
        $tempFullTableList['self'] = $this->full_table_list['self'];
        unset($tempFullTableList['self']['children']);
        unset($tempFullTableList['self']['parent']);

        global $beanList;
        global $beanFiles;
        // START: Dynamically convert ancient versions to 5.1 version of content string.
        if (!empty($this->report_def['links_def'])) {
            $tmpBean = BeanFactory::newBean($this->full_table_list['self']['module']);
            $linked_fields = $tmpBean->get_linked_fields();

            foreach ($this->report_def['links_def'] as $old_link)
            {
                $tmpBean->load_relationship($old_link);
                $linkObject = $tmpBean->$old_link;
                $relationship = $tmpBean->$old_link->_relationship;
                $newIndex = $tempFullTableList['self']['module'] . ':' . $linked_fields[$old_link]['name'];
                $upgrade_lookup[$old_link] = $newIndex;
                $tempFullTableList[$newIndex]['label'] = translate($linked_fields[$old_link]['vname']);
                $tempFullTableList[$newIndex]['link_def']['relationship_name'] = $linked_fields[$old_link]['relationship'];
                $tempFullTableList[$newIndex]['link_def']['name'] = $linked_fields[$old_link]['name'];
                $tempFullTableList[$newIndex]['link_def']['link_type'] = $linkObject->getType();
                $tempFullTableList[$newIndex]['link_def']['label'] = $tempFullTableList[$newIndex]['label'];
                $tempFullTableList[$newIndex]['link_def']['table_key'] = $newIndex;

                $tempFullTableList[$newIndex]['parent'] = 'self';

                if (isset($this->report_def['link_joins']) && is_array($this->report_def['link_joins']) && in_array($old_link, $this->report_def['link_joins']) || $relationship->relationship_type == 'one-to-many') {
                    $tempFullTableList[$newIndex]['optional'] = 1;
                }
                else
                {
                    $tempFullTableList[$newIndex]['optional'] = 0;
                }

                //Update the parent with one of us children
                $tempFullTableList['self']['children'][$old_link] = $old_link;
                $tempFullTableList[$newIndex]['module'] = $linkObject->getRelatedModuleName();
                $tempFullTableList[$newIndex]['link_def']['bean_is_lhs'] = $linkObject->_get_bean_position() ? 1 : 0;
                $tempFullTableList[$newIndex]['name'] = $tempFullTableList['self']['module'] . " > " . $tempFullTableList[$newIndex]['module'];

            }

            unset($this->report_def['links_def']);
            unset($this->report_def['link_joins']);
            unset($tmpBean);
        }
        // END: Dynamically convert ancient versions to 5.1 version of content string.
        // START: Dynamically convert previous versions to 5.1 version of content string.
        foreach ($this->full_table_list as $table_key => $table_data)
        {
            if (preg_match('/self_/', $table_key) == 1) {
                $currLinkIndex = strripos($table_key, '_link_');
                $parentLink = substr($table_key, 0, $currLinkIndex);
                if ($parentLink == 'self') {
                    $newIndex = $tempFullTableList['self']['module'] . ":" . $table_data['link_def']['name'];
                    $tempFullTableList[$newIndex] = $table_data;
                    $tempFullTableList[$newIndex]['link_def']['table_key'] = $newIndex;
                    $tempFullTableList[$newIndex]['parent'] = 'self';
                    $tempFullTableList[$newIndex]['name'] = $tempFullTableList['self']['module'] . " > " . $table_data['module'];
                    if (isset($table_data['optional']) && $table_data['optional'] == 1)
                        $tempFullTableList[$newIndex]['optional'] = 1;
                    unset($tempFullTableList[$newIndex]['children']);
                    unset($tempFullTableList[$newIndex]['value']);
                    $upgrade_lookup[$table_key] = $newIndex;
                }
                else {
                    $newIndex = $tempFullTableList[$upgrade_lookup[$parentLink]]['link_def']['table_key'] . ":" .
                                $table_data['link_def']['name'];
                    $tempFullTableList[$newIndex] = $table_data;
                    $tempFullTableList[$newIndex]['link_def']['table_key'] = $newIndex;
                    $tempFullTableList[$newIndex]['parent'] = $upgrade_lookup[$parentLink];
                    $tempFullTableList[$newIndex]['name'] = $tempFullTableList[$upgrade_lookup[$parentLink]]['name'] . " > " . $table_data['module'];
                    unset($tempFullTableList[$newIndex]['children']);
                    unset($tempFullTableList[$newIndex]['value']);
                    if (isset($table_data['optional']) && $table_data['optional'] == 1)
                        $tempFullTableList[$newIndex]['optional'] = 1;
                    $upgrade_lookup[$table_key] = $newIndex;
                }
            }
            else if ($table_key != 'self' && preg_match('/:/', $table_key) == 0) {
                $newIndex = $tempFullTableList['self']['module'] . ":" . $table_data['link_def']['name'];
                $tempFullTableList[$newIndex] = $table_data;
                $tempFullTableList[$newIndex]['link_def']['table_key'] = $newIndex;
                $tempFullTableList[$newIndex]['parent'] = 'self';
                $tempFullTableList[$newIndex]['name'] = $tempFullTableList['self']['module'] . " > " . $table_data['module'];
                if (isset($table_data['optional']) && $table_data['optional'] == 1)
                    $tempFullTableList[$newIndex]['optional'] = 1;
                unset($tempFullTableList[$newIndex]['children']);
                unset($tempFullTableList[$newIndex]['value']);
                $upgrade_lookup[$table_key] = $newIndex;
            }
        }
        if (isset($upgrade_lookup) && count($upgrade_lookup) > 0) {
            $this->full_table_list = $tempFullTableList;
            $this->report_def['full_table_list'] = $tempFullTableList;
            for ($i = 0; $i < count($this->report_def['display_columns']); $i++) {
                if ($this->report_def['display_columns'][$i]['table_key'] != 'self') {
                    $this->report_def['display_columns'][$i]['table_key'] = $upgrade_lookup[$this->report_def['display_columns'][$i]['table_key']];
                }
            }
            for ($i = 0; $i < count($this->report_def['summary_columns']); $i++) {
                if ($this->report_def['summary_columns'][$i]['table_key'] != 'self') {
                    $this->report_def['summary_columns'][$i]['table_key'] = $upgrade_lookup[$this->report_def['summary_columns'][$i]['table_key']];
                }
            }

            for ($i = 0; $i < count($this->report_def['group_defs']); $i++) {
                if ($this->report_def['group_defs'][$i]['table_key'] != 'self') {
                    $this->report_def['group_defs'][$i]['table_key'] = $upgrade_lookup[$this->report_def['group_defs'][$i]['table_key']];
                }
            }
            if (isset($this->report_def['order_by'])) {
                for ($i = 0; $i < count($this->report_def['order_by']); $i++) {
                    if ($this->report_def['order_by'][$i]['table_key'] != 'self') {
                        $this->report_def['order_by'][$i]['table_key'] = $upgrade_lookup[$this->report_def['order_by'][$i]['table_key']];
                    }
                }
            }

            if (empty($this->report_def['filters_def']) || !isset($this->report_def['filters_def']['Filter_1'])) {
                $filters = array();
                $filters['Filter_1'] = array();
                if (isset($this->report_def['filters_combiner'])) {
                    $filters['Filter_1']['operator'] = $this->report_def['filters_combiner'];
                } else {
                    $filters['Filter_1']['operator'] = 'AND';
                }
                for ($i = 0; $i < count($this->report_def['filters_def']); $i++) {
                    if ($this->report_def['filters_def'][$i]['table_key'] != 'self') {
                        $this->report_def['filters_def'][$i]['table_key'] = $upgrade_lookup[$this->report_def['filters_def'][$i]['table_key']];
                    }
                    array_push($filters['Filter_1'], $this->report_def['filters_def'][$i]);
                }
                $this->report_def['filters_def'] = $filters;
            }

            // Re-encode the report definition
            $this->report_def_str = $json->encode($this->report_def);

        }

        // Still need to update older formats that only have self in the full_table_list
        if (!isset($this->report_def['filters_def']['Filter_1'])) {
            $filters = array();
            $filters['Filter_1'] = array();
            if (isset($this->report_def['filters_combiner']))
                $filters['Filter_1']['operator'] = $this->report_def['filters_combiner'];
            else
                $filters['Filter_1']['operator'] = 'AND';

            for ($i = 0; $i < count($this->report_def['filters_def']); $i++) {
                array_push($filters['Filter_1'], $this->report_def['filters_def'][$i]);
            }
            $this->report_def['filters_def'] = $filters;
            // Re-encode the report definition
            $this->report_def_str = $json->encode($this->report_def);
        }

        if (isset($this->report_def['numerical_chart_column']) && $this->report_def['numerical_chart_column'] == 'count')
            $this->report_def['numerical_chart_column'] = 'self:count';
        // END: Dynamically convert previous versions to 5.1 version of content string.

        // Load all the necessary beans, and populate the full_bean_list array
        foreach ($this->full_table_list as $table_key => $table_data)
        {

            // Set this to a reasonable default
            $beanLabel = 'Accounts';
            if (isset($table_data['module'])) {
                // We will take this value, because 'self' doesn't typically pass the label through
                $beanLabel = $table_data['module'];
            }

            $bean = BeanFactory::newBean($beanLabel);

            if (empty($bean)) {
                $GLOBALS['log']->warn("$beanLabel doesn't exist.");
                continue;
            }
            // Store this for later, in case we want it.
            $this->full_table_list[$table_key]['bean_label'] = $beanLabel;
            $this->full_table_list[$table_key]['bean_name'] = $bean->object_name;
            $this->full_table_list[$table_key]['bean_module'] = $beanLabel;
            $this->full_bean_list[$table_key] = $bean;
            if ($table_key == 'self') {
                // Alias that to $this->focus, because it is very commonly used
                $this->focus = $this->full_bean_list[$table_key];
            }
            $this->alias_lookup[$table_key] = 'l' . count($this->alias_lookup);
        }

        $this->_load_all_fields();
        $this->_load_currency();


        if ($this->layout_manager == null) {
            $this->layout_manager = new LayoutManager();
            $this->layout_manager->default_widget_name = 'ReportField';
            $this->layout_manager->setAttributePtr('reporter', $this);
        }
        // Re-encode the report definition
        $this->report_def_str = $json->encode($this->report_def);


    }

    /**
     * Bug #52757
     * Tries to find missed relations and removes them from full_table_list
     */
    public function fixReportDefs()
    {
        $validTableKeys = array();
        // Collecting table_keys from display_columns
        foreach ($this->report_def['display_columns'] as $column) {
            if (in_array($column['table_key'], $validTableKeys) == false) {
                $validTableKeys[] = $column['table_key'];
            }
        }
        // Collecting table_keys from summary_columns
        foreach ($this->report_def['summary_columns'] as $column) {
            if (in_array($column['table_key'], $validTableKeys) == false) {
                $validTableKeys[] = $column['table_key'];
            }
        }
        // Collecting table_keys from group_defs
        foreach ($this->report_def['group_defs'] as $column) {
            if (in_array($column['table_key'], $validTableKeys) == false) {
                $validTableKeys[] = $column['table_key'];
            }
        }
        // Collecting table_keys from filter_defs
        $filters_def = array();
        $recursiveArrayIterator = new RecursiveArrayIterator($this->report_def['filters_def']);
        $recursiveIteratorIterator = new RecursiveIteratorIterator($recursiveArrayIterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($recursiveIteratorIterator as $k => $v) {
            if (is_array($v) && !empty($v['table_key'])) {
                $filters_def[] = $v;
            }
        }
        foreach ($filters_def as $column) {
            if (in_array($column['table_key'], $validTableKeys) == false) {
                $validTableKeys[] = $column['table_key'];
            }
        }

        // Filling dependencies from validTableKeys
        $requiredTableKeys = array(
            'self' => $this->module
        );
        foreach ($this->report_def['full_table_list'] as $k => $v) {
            if (in_array($k, $validTableKeys) == true) {
                $offset = -1;
                while (($offset = strpos($k, ':', $offset + 1)) !== false) {
                    $requiredTableKeys[substr($k, 0, $offset)] = $k;
                }
                $requiredTableKeys[$k] = $k;
            }
        }

        // Removing incorrect dependencies
        foreach ($this->report_def['full_table_list'] as $k => $v) {
            if (in_array($k, $validTableKeys) == true) {
                continue;
            }
            if (!empty($requiredTableKeys[$k])) {
                continue;
            }
            unset($this->report_def['full_table_list'][$k]);
        }
    }

    /**
     * Ensure that report labels do not have HTML inside
     */
    protected function cleanLabels()
    {
        foreach(array('summary_columns', 'display_columns', 'group_defs', 'full_table_list') as $def) {
            if (!empty ($this->report_def[$def])) {
                foreach ($this->report_def[$def] as &$column) {
                    if(!empty($column['label'])) {
                        // clean up the label
                        $column['label'] = strip_tags($column['label']);
                    }
                }
            }
        }
        if(!empty($this->report_def['report_name'])) {
            $this->report_def['report_name'] = strip_tags($this->report_def['report_name']);
        }
        if(!empty($this->report_def['chart_description'])) {
            $this->report_def['chart_description'] = strip_tags($this->report_def['chart_description']);
        }
    }

    // gets rid of fields that user shouldn't see
    // assumes all_fields only contains viewable fields
    function clean_report_def()
    {
        $fields = array('display_columns', 'summary_columns', 'order_by', 'filters_def', 'group_defs');
        foreach ($fields as $field)
        {
            if (count($this->report_def[$field])) {
                continue;
            }
            for ($i = 0; $i < count($this->report_def[$field]); $i++)
            {
                $def = $this->report_def[$field][$i];
                if (empty($def['table_key']) && empty($def['name'])) {
                    continue;
                }

                $key = $this->_get_full_key($def);
                if (empty($this->all_fields[$key])) {
                    unset($def);
                }
            }
        }

        global $report_modules;
        if (empty($report_modules[$this->module])) {
            $this->handleException("you are not allowed to report on this module:" . $this->module);
        }
    }

// make sure this user can report on this module

    function _check_user_permissions()
    {
        global $current_user;
        if (isset($current_user)) {
            $tabs = new TabController();
            $tabArray = $tabs->get_user_tabs($current_user);

            $moduleMap = array();

            foreach ($tabArray as $user_module) {

                $moduleMap[$user_module] = 1;

            }

            if (isset($moduleMap["Calendar"]) || isset($moduleMap["Activities"])) {
                //also include reports that are in the following modules
                $moduleMap['Tasks'] = 1;
                $moduleMap['Calls'] = 1;
                $moduleMap['Meetings'] = 1;
                $moduleMap['Notes'] = 1;
            }
            if (isset($moduleMap["Project"])) {
                $moduleMap['ProjectTask'] = 1;
            }
            if (empty($moduleMap[$this->module_dir])) {
                die ("you do not have access to report this module");
            }

        } else
        {
            die ("you shouldn't be here");
        }
    }

    function isVisibleModule($related_module)
    {
        global $report_modules;
        if (empty($report_modules[$related_module])) {
            return false;
        }
        return true;
    }

    function _load_all_fields()
    {
        $tmp = array();
        foreach ($this->full_table_list as $table_key => $table_data) {
            if(!isset($table_data['module'])) continue;

            if(!isset($tmp[$table_data['module']]))
            {
                $tmp[$table_data['module']] = array();
            }

            if (!isset($this->full_bean_list[$table_key])) {
                continue;
            }

            foreach ($this->full_bean_list[$table_key]->field_defs as $field_def)
            {
                $tmp[$table_data['module']][$field_def['name']] = 0;
                $field_def['module'] = $this->full_table_list[$table_key]['bean_label'];
                $field_def['real_table'] = $this->full_bean_list[$table_key]->table_name;
                if (!empty($field_def['source']) && ($field_def['source'] == 'custom_fields' || ($field_def['source'] == 'non-db'
                                                                                                 && !empty($field_def['ext2']) && !empty($field_def['id']))) && !empty($field_def['real_table'])
                ) {
                    $field_def['real_table'] .= '_cstm';
                }
                if ($field_def['type'] == 'relate' && !empty($field_def['ext2'])) {
                    $joinFocus = BeanFactory::newBean($field_def['ext2']);
                    $field_def['secondary_table'] = $joinFocus->table_name;
                    if(isset($table_data['link_def']) && isset($table_data['link_def']['module']) && isset($table_data['module'])
                        && $table_data['link_def']['module'] == $table_data['module'])
                    {
                        $tmp[$table_data['module']][$field_def['name']]++;
                    }
                }
                $field_def['rep_rel_name'] = $field_def['name'] . '_' . $tmp[$table_data['module']][$field_def['name']];
                $this->all_fields[$table_key . ':' . $field_def['name']] = $field_def;
            }
        }
	unset($tmp);
    }


    function _load_currency()
    {
        $this->currency_obj = BeanFactory::newBean('Currencies')->getUserCurrency();
        $this->currency_symbol = $this->currency_obj->symbol;
    }

    function clear_results()
    {
        $this->from = null;
        $this->where = null;
        $this->order_by = null;
        $this->group_by = null;
        $this->select_fields = array();
        $this->query = null;
        $this->result = null;
        $this->summary_result = null;
        $this->total_result = null;
        $this->row_count = 0;
        $this->row_end = 0;
        $this->summary_row_count = 0;
        $this->summary_row_end = 0;
    }

    function run_summary_combo_query($run_main_query = true)
    {
        if ($run_main_query) $this->run_query();

        $this->run_summary_query();
        if ($this->has_summary_columns()) {
            $this->run_total_query();
        }
    }

    function run_summary_child_query()
    {
        $this->clear_group_by();
        $this->create_order_by();
        $this->create_select();
        // false means don't
        $this->create_where();
        $this->create_group_by(false);
        $this->create_from();
        $this->create_query();

        if (empty($this->child_filter_by)) {
            return false;
        }

        $query = $this->query;
        if (!empty($this->group_order_by)) {
            $queries = explode("ORDER BY", $query);
            $query = $queries[0] . " AND  $this->child_filter= '$this->child_filter_by'";
        }
        $query_name = $this->child_filter;
        $this->$query_name = $query;
        $this->create_order_by();
        if (!empty($this->order_by_arr)) {
            $this->$query_name .= " ORDER BY " . implode(',', $this->order_by_arr);
        }
        $this->execute_query($query_name, 'child_result', '', '', '');

        return 'child_result';
    }


    function create_summary_select()
    {
        $this->create_select('summary_columns', 'summary_select_fields');
    }

    function create_total_select()
    {
        $this->create_select('summary_columns', 'total_select_fields');
    }

    function create_total_query()
    {
        $this->create_query('total_query', 'total_select_fields');
    }

    function create_summary_query()
    {
        $this->create_query('summary_query', 'summary_select_fields');
    }


    function run_summary_query()
    {
        $this->create_group_by();
        $this->create_order_by();
        $this->create_summary_select();
        $this->create_where();
        $this->create_from('summary_columns');
        $this->create_summary_query();
        $this->execute_summary_query();

    }

    function run_total_query()
    {
        $this->create_order_by();
        $this->create_total_select();
        $this->create_where();
        $this->create_from('summary_columns');
        $this->create_total_query();
        $this->execute_total_query();
    }

    function run_query($do_group_by = false)
    {
        $this->clear_group_by();
        $this->create_order_by();
        $this->create_select();
        // false means don't
        $this->create_where();
        $this->create_group_by(false);
        $this->create_from();
        $this->create_query();
        $limit = false;
        if ($this->report_type == 'tabular' && $this->enable_paging) {
            $this->total_count = $this->execute_count_query();
            $limit = true;
        }
        $this->execute_query('query', 'result', 'row_count', 'row_start', 'row_end', $limit);

    }

    function execute_count_query($query_name = 'query')
    {

        $query = $this->$query_name;
        $queries = explode('FROM', $query, 2);
        if (count($queries) == 1) {
            $queries = explode('from', $query, 2);
        }
        if (count($queries) == 2) {
            $queries = explode('ORDER BY', $queries[1]);
            $result = $this->db->query("SELECT count(*) as total_count FROM " . $queries[0]);

            if ($row = $this->db->fetchByAssoc($result)) {
                return $row['total_count'];
            }

        }
        return 0;
    }

    function execute_total_query()
    {
        $this->execute_query('total_query',
                             'total_result',
                             '',
                             '',
                             '');
    }


    function execute_summary_query()
    {
        $this->execute_query('summary_query',
                             'summary_result',
                             'summary_row_count',
                             'summary_row_start',
                             'summary_row_end');
    }

    function execute_query($query_name = 'query', $result_name = 'result', $row_count_name = 'row_count',
        $row_start_name = 'row_start', $row_end_name = 'row_end', $limit = false)
    {
        // FIXME: needs DB-independent code here
        if ($limit) {
            $this->$result_name = $this->db->limitQuery($this->$query_name, $this->report_offset, $this->report_max, true,
                                                        "Error executing query ");
        } else {
            $this->$result_name = $this->db->query($this->$query_name, true, "Error executing query ");
        }
        if (!empty($row_count_name) && empty($this->$row_count_name)) {
                $this->$row_count_name = $this->report_offset;
                $this->$row_end_name = $this->report_max;

                if ($limit && $this->total_count < $this->$row_end_name + $this->$row_count_name) {
                    $this->$row_end_name = $this->total_count - $this->$row_count_name;
                }
            if ($this->$row_count_name > 0) {
                $this->$row_start_name = 1;
            }
        }
    }


    function getTableFromField(&$layout_def)
    {

        $field = $this->getFieldDefFromLayoutDef($layout_def);

        $custom_table = '';
        if (!empty($field['table'])) {
            // truncate because oracle doesn't like long alias names
            $custom_table = substr($field['table'], 0, 8);
            $custom_table .= '_';
        }

        if (empty($layout_def['table_key'])) {
            $linked_field = 'self';
        } else {
            $linked_field = $layout_def['table_key'];
        }

        if ($linked_field != 'self') {
            $field_table = $custom_table . $this->getRelatedAliasName($linked_field);
        } else {
            $field_table = $custom_table . $this->focus->table_name;
        }
        return $field_table;
    }

    /**
     * Whether the report definition is valid (currently only column definitions
     * are considered).
     *
     * @return bool
     */
    function is_definition_valid()
    {
        $column_defs = array(
            'display_columns',
            'summary_columns',
        );

        $this->invalid_fields = array();

        foreach ($column_defs as $def)
        {
            if (isset($this->report_def[$def]) && is_array($this->report_def[$def]))
            {
                foreach ($this->report_def[$def] as $layout_def)
                {
                    if (!$this->is_layout_def_valid($layout_def))
                    {
                        $this->invalid_fields[] = $layout_def['name'];
                    }
                }
            }
        }

        $this->invalid_links = $this->getInvalidTableDefs();

        $this->invalid_fields = array_unique(array_merge($this->invalid_fields, $this->invalid_links));

        return 0 == count($this->invalid_fields);
    }

    /**
     * Removes filter entries that are using invalid links
     */
    public function removeInvalidFilters()
    {
        $entries = array_keys($this->getInvalidTableDefs());
        if (!empty($entries)) {
            foreach ($entries as $k) {
                unset($this->report_def['full_table_list'][$k]);
            }
            foreach ($this->report_def['display_columns'] as $i => $v) {
                if (!empty($v['table_key']) && in_array($v['table_key'], $entries)) {
                    unset($this->report_def['display_columns'][$i]);
                }
            }
            foreach ($this->report_def['filters_def'] as $fId => $group) {
                foreach ($group as $k => $v) {
                    if (!empty($v['table_key']) && in_array($v['table_key'], $entries)) {
                        unset($this->report_def['filters_def'][$fId][$k]);
                    }
                }
            }
            $this->report_def_str = getJSONobj()->encode($this->report_def);
        }
    }

    /**
     * Retrieves a list table joins that are using invalid links
     */
    public function getInvalidTableDefs()
    {
        $ret = array();
        if (!empty($this->report_def['full_table_list'])) {
            foreach ($this->report_def['full_table_list'] as $k => $v) {
                if (!empty($v['link_def']['name'])) {
                    $relModule = $v['parent'] == 'self' ? $this->module : $v['parent'];
                    $bean = BeanFactory::newBean($relModule);
                    if (!empty($bean) && empty($bean->field_defs[$v['link_def']['name']])) {
                        //Invalid link found
                        $ret[$k] = $v['name'];
                        continue;
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Verifies the report definition and displays an error if any issues are found.
     */
    public function validateBeforeDisplay()
    {
        global $current_language;
        if (!$this->is_definition_valid()) {
            $mod_strings = return_module_language($current_language, "Reports");
            $this->handleException($mod_strings['LBL_DELETED_FIELD_IN_REPORT1'] . ' <b>'
                    . implode(array_merge($this->invalid_links, $this->invalid_fields), ",")
                    . '</b>. ' . $mod_strings['LBL_DELETED_FIELD_IN_REPORT2']
            );
        }
    }

    /**
     * Whether specified layout definition is valid.
     *
     * @param array $layout_def
     * @return bool
     */
    function is_layout_def_valid($layout_def)
    {
        $layout_def['table_alias'] = $this->getTableFromField($layout_def);
        $full_key = $this->_get_full_key($layout_def);

        if (!empty($this->all_fields[$full_key]))
        {
            return true;
        }

        if (isset($layout_def['group_function']))
        {
            switch ($layout_def['group_function'])
            {
                case 'count':           // fall through
                case 'weighted_sum':    // fall through
                case 'weighted_amount':
                    return true;
            }
        }

        return false;
    }

    /**
     * Get report invalid fields.
     *
     * @return array
     */
    function get_invalid_fields()
    {
        return $this->invalid_fields;
    }

    // used mainly to register the join if this column needs it
    function register_field_for_query(&$layout_def)
    {
        if (!$this->is_layout_def_valid($layout_def)) {
            global $current_language;
            $mod_strings = return_module_language($current_language, $this->module_dir);
            $this->handleException($mod_strings['LBL_DELETED_FIELD_IN_REPORT1'] . ' <b>' . $layout_def['name'] . '</b>. ' . $mod_strings['LBL_DELETED_FIELD_IN_REPORT2']);
        }

        $layout_def['table_alias'] = $this->getTableFromField($layout_def);
        $field_def = $this->getFieldDefFromLayoutDef($layout_def);
        if (!empty($field_def['source']) && ($field_def['source'] == 'custom_fields' || ($field_def['source'] == 'non-db'
                                                                                         && !empty($field_def['ext2']) && !empty($field_def['id']))) && !empty($field_def['real_table'])
        ) {
            $layout_def['table_alias'] .= '_cstm';
            $params = array('join_table_alias' => $layout_def['table_alias'], 'base_table' => $this->focus->table_name, 'real_table' => $field_def['real_table'],);

            if ($layout_def['table_key'] != 'self') {
                $params['base_table'] = $this->getRelatedAliasName($layout_def['table_key']);
            }
            $this->selected_loaded_custom_links[$layout_def['table_alias']] = $params;

        }
        $layout_def['column_key'] = $this->_get_full_key($layout_def);
        if (!empty($field_def['ext2']) && !empty($field_def['id_name'])) {
            $layout_def['name'] = $field_def['id_name'];
            //#27662  , if the table was not in reristed cutom links, we will regist it
            $kk = $field_def['secondary_table'] . '_' . $field_def['rep_rel_name'];
            if (!isset($this->selected_loaded_custom_links[$kk])) {
                $this->jtcount++;
                $params = array(
                    'join_table_alias' => $field_def['secondary_table'] . $this->jtcount,
                    'base_table' => $field_def['secondary_table'],
                    'join_id' => $layout_def['table_alias'] . '.' . $field_def['id_name']);
                $this->selected_loaded_custom_links[$kk] = $params;
            }
        }

        if (!empty($layout_def['name']))
        {
	        if ($layout_def['name'] == 'weighted_amount' || $layout_def['name'] == 'weighted_sum')
	        {
	            $field_def['type'] = 'currency';
	        }

	        // In case of DOCUMENTS table must set 'document_name' field type of to 'name' manually, because _load_all_fields() function sets field type to 'name' only if the field name is 'name' also
	        if (strtolower($layout_def['name']) == 'document_name')
	        {
	            $field_def['type'] = 'name';
	        }
        }

        $layout_def['type'] = $field_def['type'];

        if (isset($field_def['precision'])) {
            $layout_def['precision'] = $field_def['precision'];
        }

        if (isset($field_def['rel_field'])) {
            $layout_def['rel_field'] = $field_def['rel_field'];
        }
    }

    function parseUIFiltersDef($filters_def_str, $panels_def_str)
    {
        $filters = array();
        $panelParents = array();
        foreach ($panels_def_str as $index => $key) {
            $panelParents[$key['id']] = $key['parentId'];
            foreach ($filters_def_str as $filter_key => $filter_def) {
                if ($filter_def['panelId'] == $key['id']) {
                    if (!isset ($filters[$filter_def['panelId']])) {
                        $filters[$filter_def['panelId']] = array();
                        $filters[$filter_def['panelId']]['operator'] = $key['operator'];
                    }
                    // Remove the panelId from the filter definition as it's no longer needed.
                    array_splice($filter_def, 0, 1);
                    array_push($filters[$key['id']], $filter_def);
                }
            }
            if (!isset($filters[$key['id']])) {
                $filters[$key['id']] = array();
                $filters[$key['id']]['operator'] = $key['operator'];
            }
        }
        krsort($panelParents);

        foreach ($panelParents as $panel => $parent) {
            if (isset($filters[$parent])) {
                array_unshift($filters[$parent], $filters[$panel]);
            }
        }
        array_splice($filters, 1);
        global $current_language;
        $mod_strings = return_module_language($current_language, 'Reports');
        $filterString = $mod_strings['LBL_FILTER'] . '.1';
        if (isset($filters[$filterString])) {
            $filters['Filter_1'] = $filters[$filterString];
            unset($filters[$filterString]);
        }
        $this->report_def['filters_def'] = $filters;
    }

    function filtersIterate($filters, &$where_clause)
    {
        $where_clause .= '(';
        $operator = $filters['operator'];
        $isSubCondition = 0;
        if (count($filters) < 2) { // We only have an operator and an empty Filter Box.
            $where_clause .= "1=1";
        }
        for ($i = 0; $i < count($filters) - 1; $i++) {
            $current_filter = $filters[$i];
            if (isset($current_filter['operator'])) {
                $where_clause .= "(";
                $isSubCondition = 1;
                Report::filtersIterate($current_filter, $where_clause);
            }
            else {
                if (!empty($current_filter['type']) && ($current_filter['type'] == 'datetimecombo' || $current_filter['type'] == 'datetime')) {
                    if (!empty($current_filter['input_name0'])) {
                        $current_filter['input_name0'] = $GLOBALS['timedate']->asDbType(new DateTime($current_filter['input_name0']), $current_filter['type']);
                    }
                    if (!empty($current_filter['input_name2'])) {
                        $current_filter['input_name2'] = $GLOBALS['timedate']->asDbType(new DateTime($current_filter['input_name2']), $current_filter['type']);
                    }
                }
                $this->register_field_for_query($current_filter);
                $select_piece = "(" . $this->layout_manager->widgetQuery($current_filter) . ")";
                $where_clause .= $select_piece;
            }
            if ($isSubCondition == 1) {
                $where_clause .= ")";
                // reset the subCondition
                $isSubCondition = 0;
            }
            if ($i != count($filters) - 2) {
                $where_clause .= " $operator ";
            }

        }
        $where_clause .= ')';
    }

    /**
     * Applies visibility filtering defined by the bean to the data selected from the specified table
     * when building the FROM part of the query
     *
     * @param SugarBean $bean
     * @param string $from
     * @param string $tableAlias
     *
     * @return string
     */
    private function addVisibilityFrom(SugarBean $bean, $from, $tableAlias)
    {
        $options = $this->getVisibilityOptions();
        $options['table_alias'] = $tableAlias;

        $bean->addVisibilityFrom($from, $options);

        return $from;
    }

    /**
     * Applies visibility filtering defined by the bean to the data selected from the specified table
     * when building the WHERE part of the query
     *
     * @param SugarBean $bean
     * @param string $where
     * @param string $tableAlias
     *
     * @return string
     */
    private function addVisibilityWhere(SugarBean $bean, $where, $tableAlias)
    {
        $options = $this->getVisibilityOptions();
        $options['table_alias'] = $tableAlias;

        $bean->addVisibilityWhere($where, $options);

        return $where;
    }

    function create_where()
    {
        $this->layout_manager->setAttribute('context', 'Filter');
        $filters = $this->report_def['filters_def'];
        $where_clause = "";
        if (isset($filters['Filter_1']))
            Report::filtersIterate($filters['Filter_1'], $where_clause);
        $this->where = $where_clause;
    }

    function filtersIterateForUI($filters, &$verdef_arr_for_filters)
    {
        $operator = $filters['operator'];
        for ($i = 0; $i < count($filters) - 1; $i++) {
            $current_filter = $filters[$i];
            if (isset($current_filter['operator'])) {
                Report::filtersIterateForUI($current_filter, $verdef_arr_for_filters);
            }
            else {
                $fieldDef = $this->getFieldDefFromLayoutDef($current_filter);
                $verdef_arr_for_filters[$fieldDef['name']] = $fieldDef;
            }
        }
    }

    private function getVisibilityOptions()
    {
        $options = $this->visibilityOpts;

        // Here we have a hack because MySQL hates subqueries in joins, see bug #60288 for details
        if ($this->focus->db->supports('fix:report_as_condition')) {
            $options['as_condition'] = true;
        }

        return $options;
    }

    function createFilterStringForUI()
    {
        global $app_list_strings;
        $verdef_arr_for_filters = array();
        $filters = $this->report_def['filters_def'];
        $originalWhereClause = $this->where;
        if (isset($filters['Filter_1'])) {
            Report::filtersIterateForUI($filters['Filter_1'], $verdef_arr_for_filters);
        } // if
        $where_clause = $this->where;
        global $reportAlias;
        if (empty($reportAlias) || empty($where_clause)) {
            return "";
        }
        // reportalias is a table.cllumn key to filter object
        foreach ($reportAlias as $key => $value) {
            $columnKey = $value['column_key'];
            $tableKey = $value['table_key'];
            $tableArray = $this->report_def['full_table_list'][$tableKey];
            //This is used for old data. the 'label' in old data is not translated at all.
            $reportDisplayTableName = ($tableKey == "self")
                    ? (isset($app_list_strings['moduleList'][$tableArray['label']])
                            ? $app_list_strings['moduleList'][$tableArray['label']] : $tableArray['label'])
                    : $tableArray['name'];
            $columnKeyArray = explode(":", $columnKey);
            if (isset($verdef_arr_for_filters[$columnKeyArray[sizeof($columnKeyArray) - 1]])) {
                $varDefLabel = $verdef_arr_for_filters[$columnKeyArray[sizeof($columnKeyArray) - 1]]['vname'];
                $varDefLabel = translate($varDefLabel, $verdef_arr_for_filters[$columnKeyArray[sizeof($columnKeyArray) - 1]]['module']);
                $finalDisplayName = $reportDisplayTableName . " > " . $varDefLabel;
                // Wrap the search and replace terms in spaces to ensure exact match
                // and replace
                $where_clause = str_replace(" $key ", " $finalDisplayName ", $where_clause);
            }
        } // foreach
        return $where_clause;
    } // fn

    function getFieldDefFromLayoutDef(&$layout_def)
    {
        $field = null;
        $relModules = explode('_', $layout_def['table_key']);
        $module = $relModules[count($relModules) - 1];
        if (!empty($this->all_fields[$this->_get_full_key($layout_def)])) {
            $field = $this->all_fields[$this->_get_full_key($layout_def)];
        }
        return $field;
    }

    function _get_full_key(&$layout_def)
    {
        if (empty($layout_def['table_key'])) {
            $table_key = 'self';
        } else {
            $table_key = $layout_def['table_key'];
        }
        if (empty($layout_def['name']))
            return $table_key;

        return $table_key . ":" . $layout_def['name'];
    }

    function parseLinkedField($fieldname)
    {
        preg_match('/^(\w+):/', $fieldname, $match);
        return $match[1];
    }

    function getRelatedAliasName($linked_field)
    {
        if (!empty($this->alias_lookup[$linked_field])) {
            return $this->alias_lookup[$linked_field];
        }
        return $linked_field;
    }

    function getRelatedLinkAliasName($linked_field)
    {
        return $this->alias_lookup[$linked_field] . '_1';
    }

    function has_summary_columns()
    {
        $key = 'summary_columns';


        $got_summary = 0;
        foreach ($this->report_def[$key] as $index => $display_column)
        {

            if ($display_column['name'] == 'count') {
                $got_summary = 1;
            } else if (!empty($display_column['group_function'])) {
                $got_summary = 1;
            }
        }
        return $got_summary;
    }

    function is_group_column(&$display_column)
    {
        $qualifier = '';

        if (!empty($display_column['column_function'])) {
            $qualifier = $display_column['column_function'];
        }


        for ($i = 0; $i < count($this->report_def['group_defs']); $i++)
        {
            $def_qualifier = '';
            if (!empty($this->report_def['group_defs'][$i]['qualifier'])) {
                $def_qualifier = $this->report_def['group_defs'][$i]['qualifier'];
            }

            if ($this->report_def['group_defs'][$i]['table_key'] . "_" . $this->report_def['group_defs'][$i]['name'] . "_" . $def_qualifier ==
                $display_column['table_key'] . "_" . $display_column['name'] . "_" . $qualifier
            ) {
                return 1;

            }

        }
        return 0;
    }

    /**
     * Create a list of field defs for fullname
     * @param string $table Table alias for which fullname is created
     * @return array
     */
    public function createNameList($table)
    {
        $defs_list = array();
        if(empty($this->full_bean_list[$table])) {
            return array();
        }
        $bean = $this->full_bean_list[$table];
        if(empty($bean->name_format_map)) {
            return array();
        }
        foreach(array_unique(array_values($bean->name_format_map)) as $column) {
            $def = array();
            if(empty($bean->field_defs[$column])) {
                // we don't know this field, skip it
                continue;
            }
            $field_def = $bean->field_defs[$column];
            $def['name'] = $column;
            $def['type'] = $field_def['type'];
            $def['table_key'] = $table;
            $def['column_key'] = $def['table_key'] . ':' . $def['name'];
            $def['table_alias'] = $this->getTableFromField($def);
            if (!empty($field_def['source']) && ($field_def['source'] == 'custom_fields' || ($field_def['source'] == 'non-db'
                    && !empty($field_def['ext2']) && !empty($field_def['id'])))
            ) {
                $def['table_alias'] .= '_cstm';
            }
            $defs_list[] = $def;
        }
        return $defs_list;
    }

    function create_select($key = 'display_columns', $field_list_name = 'select_fields')
    {
        $this->layout_manager->setAttribute('context', 'Select');
        $got_count = 0;
        $got_join = array();
        foreach ($this->report_def[$key] as $index => $display_column) {
            if ($display_column['name'] == 'count') {
                if ('self' != $display_column['table_key'])
                {
                    // use table name itself, not it's alias
                    $table_name = $this->alias_lookup[$display_column['table_key']];
                }
                else
                {
                    // use table alias
                    if(isset($this->full_table_list['self']['params']['join_table_alias'])) {
                        $table_name = $this->full_table_list['self']['params']['join_table_alias'];
                    } else {
                        $table_name = $this->full_bean_list['self']->table_name;
                    }
                }
                $select_piece = "COUNT($table_name.id) {$table_name}__allcount, COUNT(DISTINCT  $table_name.id) {$table_name}__count ";
                $got_count = 1;
            }
            else {
                if ($field_list_name == 'total_select_fields' && empty($display_column['group_function'])) {
                    continue;
                }
                $this->register_field_for_query($display_column);

                // this hack is so that the id field for every table is always selected
                if (empty($display_column['table_key'])) {
                    $this->handleException('table_key doesnt exist for ' . $display_column['name']);
                }

                if($display_column['type'] == 'fullname') {
                    $name = "{$key}_{$display_column['name']}";
                    $this->report_def[$name] = $this->createNameList($display_column['table_key']);
                    $this->create_select($name, $field_list_name);
                    continue;
                }

                if (empty($got_join[$display_column['table_key']])) {
                    $id_column = array();
                    $got_join[$display_column['table_key']] = 1;

                    if (!empty($display_column['column_key']) && !empty($this->all_fields[$display_column['column_key']]) && !empty($this->all_fields[$display_column['column_key']]['custom_type'])) {
                        $do_id = 0;
                    }
                    else {
                        $do_id = 1;
                    }
                    // Bug 45019: don't add ID column if this column is the ID column
                    if (($field_list_name != 'total_select_fields' && $field_list_name != 'summary_select_fields') && $do_id) {
                        $id_column['name'] = 'id';
                        $id_column['type'] = 'id';
                        $id_column['table_key'] = $display_column['table_key'];
                        if (preg_match('/_cstm/', $display_column['table_alias']) > 0) {
                            // bug #49475
                            $id_column['table_alias'] = $this->focus->table_name;
                        } else {
                            $id_column['table_alias'] = $display_column['table_alias'];
                        }
                        $id_column['column_key'] = $id_column['table_key'] . ':' . $id_column['name'];
                        $select_piece = $this->layout_manager->widgetQuery($id_column);
                        if (!$this->select_already_defined($select_piece, $field_list_name)) {
                            array_push($this->$field_list_name, $select_piece);
                        }
                    }
                }
                // specify "currency_alias" parameter for fields of currency type
                if (!empty($display_column['column_key']) && !empty($this->all_fields[$display_column['column_key']])
                    && $display_column['type'] == 'currency') {
                    $field_def = $this->all_fields[$display_column['column_key']];
                    if (strpos($field_def['name'], '_usdoll') === false) {
                        $display_column['currency_alias'] = $display_column['table_alias'] . '_currencies';
                    }
                }
                $select_piece = $this->layout_manager->widgetQuery($display_column);
            }
            if (!$this->select_already_defined($select_piece, $field_list_name)) {
                array_push($this->$field_list_name, $select_piece);
            }

            if (!empty($display_column['column_key']) && !empty($this->all_fields[$display_column['column_key']])) {
                $field_def = $this->all_fields[$display_column['column_key']];
                if (!empty($field_def['ext2'])) {
                    $select_piece = $this->getExt2FieldDefSelectPiece($field_def);
                    array_push($this->$field_list_name, $select_piece);
                }
            }

            // for SUM currency fields add params to join 'currencies' table
            if (!empty($display_column['column_key'])
                && !empty($this->all_fields[$display_column['column_key']])
                && !empty($display_column['group_function'])
                && isset($display_column['field_type'])
                && $display_column['field_type'] == 'currency'
                && strpos($display_column['name'], '_usdoll') === false
                && isset($display_column['currency_alias'])
                && !isset($this->currency_join[$key][$display_column['currency_alias']])
            ) {
                $table_key = $this->full_bean_list[$display_column['table_key']]->table_name;

                $bean_table_alias = $display_column['table_key'] === 'self'
                    ? $table_key : $this->getRelatedAliasName($display_column['table_key']);

                // by default, currency table is joined to the alias of primary table
                $table_alias = $bean_table_alias;

                // but if the field is contained in a custom table, use it's alias for join
                $field_def = $this->all_fields[$display_column['column_key']];
                if ($field_def['real_table'] != $table_key) {
                    $columns = $this->db->get_columns($field_def['real_table']);
                    if (isset($columns['currency_id'])) {
                        $table_alias = $display_column['table_alias'];
                    }
                }

                // create additional join of currency table for each module containing currency fields
                $this->currency_join[$key][$display_column['currency_alias']] = $table_alias;
            }
        }

        // 'register' the joins for the other column defs since we need to join all for summary to work.. else the count and maybe other group functions won't work.
        if ($key == 'display_columns') {
            $key = 'summary_columns';
        }
        else {
            $key = 'display_columns';
        }

        if ($got_count == 0 && $field_list_name == 'summary_select_fields') {
            array_push($this->$field_list_name, 'count(*) count');
        }
    } // end create_select

    function clear_group_by()
    {
        $this->group_by = '';
    }

    function create_order_by()
    {
        $generated_order_by = '';
        $this->layout_manager->setAttribute('context', 'OrderBy');
        $this->order_by = '';
        $this->order_by_arr = array();
        $this->summary_order_by_arr = array();
        if (!empty($this->report_def['order_by'][0])) {
            $order_by = $this->report_def['order_by'][0];

            $this->register_field_for_query($order_by);

            $generated_order_by = $this->layout_manager->widgetQuery($order_by);
            if($generated_order_by != "") {
                array_push($this->order_by_arr, $generated_order_by);
            }
        }
        $this->summary_order_by = '';

        // Only do this for Summation reports.
        if ($this->report_def['report_type'] == 'summary' && empty($this->report_def['display_columns'])) {
            if (!empty($this->report_def['summary_order_by'][0])) {
                $summary_order_by = $this->report_def['summary_order_by'][0];
                $this->register_field_for_query($summary_order_by);
                array_push($this->summary_order_by_arr, $this->layout_manager->widgetQuery($summary_order_by));
            }
        }

    }


    function select_already_defined($select, $which = 'select_fields')
    {
        if (empty($this->select_already_defined_hash[$which])) {
            $this->select_already_defined_hash[$which] = array();
        }

        if (empty($this->select_already_defined_hash[$which][$select])) {
            $this->select_already_defined_hash[$which][$select] = 1;
            return false;
        }
        return true;

    }

    function create_group_by($register_group_by = true)
    {

        if (!empty($this->report_def['group_defs']) && is_array($this->report_def['group_defs'])) {
            $this->group_by_arr = array();
            $this->group_order_by_arr = array();
            foreach ($this->report_def['group_defs'] as $group_column)
            {
                $this->layout_manager->setAttribute('context', 'GroupBy');
                $this->register_field_for_query($group_column);
                $group_by = $this->layout_manager->widgetQuery($group_column);
                $this->layout_manager->setAttribute('context', 'OrderBy');
                $order_by = $this->layout_manager->widgetQuery($group_column);
                $this->layout_manager->setAttribute('context', 'Select');

                if (!empty($group_column['qualifier'])) {
                    $group_column['column_function'] = $group_column['qualifier'];
                }

                $select = $this->layout_manager->widgetQuery($group_column);

                if (!$this->select_already_defined($select, 'select_fields')) {
                    $this->select_fields[] = $select;
                }

                if (!$this->select_already_defined($select, 'summary_select_fields')) {
                    $this->summary_select_fields[] = $select;
                }

                if (!empty($register_group_by)) {
                    $this->group_by_arr[] = $group_by;
                    if (!empty($group_column['column_key']) && !empty($this->all_fields[$group_column['column_key']])) {
                        $field_def = $this->all_fields[$group_column['column_key']];

                        if (!empty($field_def['ext2'])) {
                            $select_piece = $this->getExt2FieldDefSelectPiece($field_def, false);
                            $this->group_by_arr[] = $select_piece;
                        }
                    }
                }
                // Changed the sort order, so it would sort by the initial options first
                if (!empty($order_by)) {
                    array_unshift($this->group_order_by_arr, $order_by);
                }
            }
        }
    }

    protected function create_from($key = null)
    {
        global $beanFiles;
        foreach ($this->full_table_list as $linkKey => $def) {
            if ($linkKey != 'self' && isset($def['link_def'])) {
                $params = array('join_table_alias' => $this->getRelatedAliasName($def['link_def']['table_key']),
                                'join_table_link_alias' => $this->getRelatedLinkAliasName($def['link_def']['table_key']));

                $this->full_table_list[$def['link_def']['table_key']]['params'] = $params;
                // Be sure to load in the parents

                $curr_parent = $this->full_table_list[$def['link_def']['table_key']]['parent'];

                while ($curr_parent != '' && $curr_parent != 'self' && !isset($this->full_table_list[$curr_parent]['params'])) {
                    // The parent is not loaded.
                    $params = array('join_table_alias' => $this->getRelatedAliasName($curr_parent),
                                    'join_table_link_alias' => $this->getRelatedLinkAliasName($curr_parent));
                    $this->full_table_list[$curr_parent]['params'] = $params;
                    // Find out if my parent's parent is loaded or not
                    $curr_parent = $this->full_table_list[$curr_parent]['parent'];
                }
            }
        }

        // Setup some data structures so they can be used later
        $this->full_table_list['self']['params']['join_table_alias'] = $this->focus->table_name;
        $this->full_table_list['self']['params']['join_table_link_alias'] = $this->focus->table_name . "_l";

        $from = "\nFROM " . $this->focus->table_name . "\n";
        $this->from = $this->addVisibilityFrom($this->focus, $from, $this->focus->table_name);

        $this->jtcount = 0;
        foreach ($this->full_table_list as $table_key => $table_def)
        {

            // Increment the join table count
            $this->jtcount++;

            if ($table_key == 'self') continue; // self table, no join required
            if (empty($table_def['params'])) { // always join on tables!
                $table_def['params'] = array(
                    'join_table_alias' => $this->getRelatedAliasName($table_key),
                    'join_table_link_alias' => $this->getRelatedLinkAliasName($table_key),
                );
            }
            $params = $table_def['params'];
            if (!isset($params['join_table_alias'])) {
                $params['join_table_alias'] = "jt" . $this->jtcount;
            }

            if (!isset($params['join_table_link_alias'])) {
                $params['join_table_link_alias'] = "jtl" . $this->jtcount;
            }
            $team_join_type = 'INNER';
            if (isset($table_def['optional']) && $table_def['optional'] == 1) {
                $params['join_type'] = 'LEFT JOIN ';
                $team_join_type = 'LEFT';
            }

            if (is_object($this->full_bean_list[$table_def['parent']])) {
                // We need to find the exact link name that it is expecting.
                $link_name = $table_def['link_def']['name'];
                $rel_name = $table_def['link_def']['relationship_name'];
                $linked_fields = $this->full_bean_list[$table_def['parent']]->get_linked_fields();
                foreach ($linked_fields as $tmp_link_name => $link)
                {
                    if ($link['name'] == $link_name) {
                        $link_name = $tmp_link_name;
                    }
                    if ($link['relationship'] == $rel_name) {
                        $rel_name = $tmp_link_name;
                    }

                }
                if ($link_name != '') {
                    $focus = BeanFactory::newBean($table_def['module']);
                    if (!isset($params['bean_is_lhs']) || $params['bean_is_lhs'] != 1) {
                        $params['right_join_table_alias'] = $this->full_table_list[$table_def['parent']]['params']['join_table_alias'];
                        $params['right_join_table_link_alias'] = $this->full_table_list[$table_def['parent']]['params']['join_table_link_alias'];
                    }
                    $params['left_join_table_alias'] = $this->full_table_list[$table_def['parent']]['params']['join_table_alias'];
                    $params['left_join_table_link_alias'] = $this->full_table_list[$table_def['parent']]['params']['join_table_link_alias'];

                    $this->full_bean_list[$table_def['parent']]->load_relationships();
                    $params['primary_table_name'] = $this->full_table_list[$table_def['parent']]['params']['join_table_alias'];

                    if (isset($this->full_bean_list[$table_def['parent']]->$link_name)) {
                        /** @var Link2 $link */
                        $link = $this->full_bean_list[$table_def['parent']]->$link_name;

                        if (!$link->loadedSuccesfully()) {
                            $this->handleException("Unable to load link: $link_name for bean {$table_def['parent']}");
                        }

                        // Start ACL check
                        global $current_user, $mod_strings;
                        $linkModName = $link->getRelatedModuleName();
                        $list_action = ACLAction::getUserAccessLevel($current_user->id, $linkModName, 'list', $type = 'module');
                        $view_action = ACLAction::getUserAccessLevel($current_user->id, $linkModName, 'view', $type = 'module');

                        if ($list_action == ACL_ALLOW_NONE || $view_action == ACL_ALLOW_NONE) {
                            if ((isset($_REQUEST['DynamicAction']) && $_REQUEST['DynamicAction'] == 'retrievePage') || (isset($_REQUEST['module']) && $_REQUEST['module'] == 'Home')) {
                                throw new Exception($mod_strings['LBL_NO_ACCESS'] . "----" . $linkModName);
                            } else {
                                $this->handleException($mod_strings['LBL_NO_ACCESS'] . "----" . $linkModName);
                            }
                        }
                        // End ACL check
                    }
                    else {
                        // Start ACL check
                        global $current_user, $mod_strings;

                        /** @var Link2 $link */
                        $link = $this->full_bean_list[$table_def['parent']]->$rel_name;

                        $linkModName = $link->getRelatedModuleName();
                        $list_action = ACLAction::getUserAccessLevel($current_user->id, $linkModName, 'list', $type = 'module');
                        $view_action = ACLAction::getUserAccessLevel($current_user->id, $linkModName, 'view', $type = 'module');

                        if (!$link->loadedSuccesfully()) {
                            $this->handleException("Unable to load link: $rel_name");
                        }
                        if ($list_action == ACL_ALLOW_NONE || $view_action == ACL_ALLOW_NONE)
                            $this->handleException($mod_strings['LBL_NO_ACCESS'] . "----" . $linkModName);
                        // End ACL check
                    }

                    $this->from .= $link->getJoin($params);

                    $this->from = $this->addVisibilityFrom($focus, $this->from, $params['join_table_alias']);
                    $this->where = $this->addVisibilityWhere($focus, $this->where, $params['join_table_alias']);
                }
                else
                {
                    die("Could not find link name, searching through for: " . $link_name);
                }
            }
            else
            {
                die("table_def[parent] is not an object! (" . $table_def['parent'] . ")<br>");
            }
        }
        foreach ($this->selected_loaded_custom_links as $custom_table => $params)
        {
            if (!empty($params['join_id'])) {
                $this->from .= "LEFT JOIN " . $params['base_table'] . " " . $params['join_table_alias'] . " ON " . $params['join_table_alias'] . ".id = ";
                $this->from .= $params['join_id'] . "\n";
            }
            else {
                $tablename = (empty($params['real_table']) ? $params['base_table'] : $params['real_table']);
                $this->from .= "LEFT JOIN " . $tablename . " " . $params['join_table_alias'] . " ON " . $params['base_table'] . ".id = ";
                $this->from .= $params['join_table_alias'] . ".id_c\n";
            }
        }

        if ($key && isset($this->currency_join[$key])) {
            $join = array();
            $currency_table = $this->currency_obj->table_name;
            foreach ($this->currency_join[$key] as $currency_alias => $table_alias) {
                $join[] = 'LEFT JOIN ' . $currency_table . ' ' . $currency_alias
                    . ' ON ' . $table_alias . '.currency_id=' . $currency_alias . '.id'
                    . ' AND ' . $currency_alias . '.deleted=0';
            }
            $this->from .= implode(' ', $join);
        }
    }

    protected function wrapIfNull($field)
    {
        $has_space = strrpos($field, " ");
        // Check if the field has space - i.e. it's "table.field alias"
        if ($has_space && !stristr("' '", $field)) {
            $aggregate_func = strtolower(substr($field, 0, 4));
            if ($aggregate_func == 'max(' || $aggregate_func == 'min(' || $aggregate_func == 'avg(' || $aggregate_func == 'sum(') {
                return $field;
            }
            if (strtolower(substr($field, 0, 6)) == 'count(') {
                return $field;
            }
            // This is field name as table.field
            $field_name = substr($field, 0, $has_space);
            $field_data = explode(".", $field_name);
            if (!isset($field_data[1])) {
                return $field;
            }
            $field_type = null;
            foreach ($this->full_table_list as $k => $v) {
                if (!empty($v['params']) && !empty($v['params']['join_table_alias'])) {
                    if ($v['params']['join_table_alias'] == $field_data[0]) {
                        $key = $k;
                        break;
                    }
                }
            }

            if (!empty($key)) {
                $fieldName = $key . ':' . $field_data[1];
                $field_type = DBManagerFactory::getInstance()->getFieldType($this->all_fields[$fieldName]);
            }
            if (empty($field_type)) {
                // Not a field or unknown field type - don't touch it
                return $field;
            }

            if (!in_array($field_type, array('currency','double','float','decimal','int','date','datetime'))) {
                if ($field_type === 'bool') {
                    $default = '0';
                } else {
                    $default = "''";
                }

                // add IFNULL to the field and then re-add alias back
                return $this->db->convert($field_name, "IFNULL", array($default))
                    . " " . substr($field, $has_space + 1) . "\n";
            }
        }
        return $field;
    }

    function create_query($query_name = 'query', $field_list_name = 'select_fields')
    {

        $query = "SELECT ";
        $field_list_name_array = $this->$field_list_name;
        foreach ($field_list_name_array as $field) {
            $field = trim($field);
            if (strstr($field, ',')) {
                $fields = explode(',', $field);
                foreach ($fields as $field_in_field) {
                    $field_not_null[] = $this->wrapIfNull($field_in_field);
                }
            } else {
                $field_not_null[] = $this->wrapIfNull($field);
            }
        }
        $this->$field_list_name = $field_not_null;
        $query .= implode(",", $this->$field_list_name);
        $query .= $this->from . "\n";

        $where_auto = " " . $this->focus->table_name . ".deleted=0 \n";

        // related custom fields could have record in the {MODULE}_cstm table and at the same time could not have
        // record in the related module table. So we need to check for NULL value.
        foreach($this->extModules as $tableAlias => $extModule) {
            if (isset($extModule->deleted)) {
                $where_auto .= ' AND ' . $this->db->convert($tableAlias . '.deleted', 'IFNULL', array(0)) . "=0 \n";
            }
        }

        // Start ACL check
        global $current_user, $mod_strings;
        if (!is_admin($current_user)) {
            $list_action = ACLAction::getUserAccessLevel($current_user->id, $this->focus->module_dir, 'list', $type = 'module');
            $view_action = ACLAction::getUserAccessLevel($current_user->id, $this->focus->module_dir, 'view', $type = 'module');

            if ($list_action == ACL_ALLOW_NONE || $view_action == ACL_ALLOW_NONE) {
                $this->handleException($mod_strings['LBL_NO_ACCESS']);
            }
            $aclVisibility = new ACLVisibility($this->focus);
            $aclVisibility->setOptions(array('action' => 'view'));
            $aclVisibility->addVisibilityWhere($where_auto);
            $aclVisibility->setOptions(array('action' => 'list'));
            $aclVisibility->addVisibilityWhere($where_auto);
        }
        // End ACL check

        $options = $this->getVisibilityOptions();
        $options['action'] = 'list';
        $where_auto = $this->focus->addVisibilityWhere($where_auto, $options);

        if (!empty($this->where)) {
            $query .= " WHERE ($this->where) \nAND " . $where_auto;
        } else {
            $query .= " WHERE " . $where_auto;
        }

        if (!empty($this->group_order_by_arr) && is_array($this->group_order_by_arr) && $query_name != 'summary_query') {
            foreach ($this->group_order_by_arr as $group_order_by) {
                array_unshift($this->order_by_arr, $group_order_by);
            }
        } else if (!empty($this->group_order_by_arr) && is_array($this->group_order_by_arr) &&
                   $query_name == 'summary_query' && empty($this->summary_order_by_arr)
        ) {
            foreach ($this->group_order_by_arr as $group_order_by) {
                array_unshift($this->summary_order_by_arr, $group_order_by);
            }
        }

        // if we are doing the details part of a summary query.. we need the details
        // to be sorted by the group by

        if (!empty($this->group_by_arr) && is_array($this->group_by_arr) && $query_name != 'total_query') {
            $query .= " GROUP BY " . join(",", $this->group_by_arr);
        }

        if ($query_name == 'summary_query') {
            if (!empty($this->summary_order_by_arr)) {
                $this->summary_order_by_arr = array_unique($this->summary_order_by_arr);
                $query .= " ORDER BY " . implode(',', $this->summary_order_by_arr);
            }
        } else if ($query_name == 'query') {
            if (!empty($this->order_by_arr)) {
                $this->order_by_arr = array_unique($this->order_by_arr);
                $query .= " ORDER BY " . implode(',', $this->order_by_arr);
            }
        }

        $this->$query_name = $query;

        array_push($this->query_list, $this->$query_name);
    }


    function get_summary_header_row()
    {
        $this->layout_manager->setAttribute('list_type', 'summary');
        $header_row = $this->get_header_row_generic('summary_columns');
        return $header_row;
    }

    function get_total_header_row($exporting = false)
    {
        $this->layout_manager->setAttribute('list_type', 'summary');
        $this->layout_manager->setAttribute('no_sort', '1');
        $header_row = $this->get_header_row_generic('summary_columns', true, $exporting);
        return $header_row;
    }

    function get_header_row($column_field_name = 'display_columns', $skip_non_group = false, $exporting = false, $force_distinct = false)
    {
        $this->layout_manager->setAttribute('list_type', 'columns');

        $header_row = $this->get_header_row_generic($column_field_name, $skip_non_group, $exporting, $force_distinct);
        return $header_row;
    }

    function get_header_row_generic($column_field_name = 'display_columns', $skip_non_group = false, $exporting = false, $force_distinct = false)
    {
        if ($this->plain_text_output == true) {
            $this->layout_manager->setAttribute('context', 'HeaderCellPlain');
        }
        else {
            $this->layout_manager->setAttribute('context', 'HeaderCell');
        }

        $header_row = array();
        $summary_count = 0;

        foreach ($this->report_def[$column_field_name] as $display_column) {
            if ($skip_non_group && empty($display_column['group_function'])) {
                if ($exporting || $this->plain_text_output)
                    array_push($header_row, ' ');
                else
                    array_push($header_row, '&nbsp;');
                continue;
            }

            $group_by_key = '';
            if (!empty($this->report_def['group_defs'][0])) {
                $group_by_key = $this->report_def['group_defs'][0]['table_key'] . ":" . $this->report_def['group_defs'][0]['name'];
            }
            if (!empty($this->report_def['order_by'][0])) {
                $order_by_key = $this->report_def['order_by'][0]['table_key'] . ":" . $this->report_def['order_by'][0]['name'];
                $column_key = $this->_get_full_key($display_column);

                if (!empty($display_column['group_function'])) {
                    $column_key .= ':' . $display_column['group_function'];
                }
                elseif (!empty($display_column['column_function'])) {
                    $column_key .= ':' . $display_column['column_function'];
                }

                if ($group_by_key == $column_key) {
                    $display_column['no_sort'] = 1;
                }

                if ($order_by_key == $column_key) {
                    if (empty($this->report_def['order_by'][0]['sort_dir']) || $this->report_def['order_by'][0]['sort_dir'] == 'a') {
                        $display_column['sort'] = '_down';
                    }
                    else {
                        $display_column['sort'] = '_up';
                    }
                }
            }

            $column_key = $this->_get_full_key($display_column);

            if (!empty($display_column['group_function']) && $display_column['group_function'] != 'count') {
                $column_key .= ":" . $display_column['group_function'];
            }
            elseif (!empty($display_column['column_function']) && $display_column['column_function'] != 'count') {
                $column_key .= ":" . $display_column['column_function'];
            }

            if (!empty($this->report_def['summary_order_by'][0])) {
                if (!empty($this->report_def['summary_order_by'][0]['group_function']) && $this->report_def['summary_order_by'][0]['group_function'] == 'count') {
                    $order_by_key = $this->report_def['summary_order_by'][0]['table_key'] . ":" . 'count';
                }
                else {
                    $order_by_key = $this->report_def['summary_order_by'][0]['table_key'] . ":" . $this->report_def['summary_order_by'][0]['name'];

                    if (!empty($this->report_def['summary_order_by'][0]['group_function'])) {
                        $order_by_key .= ":" . $this->report_def['summary_order_by'][0]['group_function'];
                    }
                    elseif (!empty($this->report_def['summary_order_by'][0]['column_function'])) {
                        $order_by_key .= ":" . $this->report_def['summary_order_by'][0]['column_function'];
                    }
                }

                if ($order_by_key == $column_key) {
                    if (empty($this->report_def['summary_order_by'][0]['sort_dir']) || $this->report_def['summary_order_by'][0]['sort_dir'] == 'a') {
                        $display_column['sort'] = '_down';
                    }
                    else {
                        $display_column['sort'] = '_up';
                    }
                }
            }
            $display = $this->layout_manager->widgetDisplay($display_column);

            if ($column_field_name == 'summary_columns' && !empty($display_column['is_group_by'])) {
                if ($display_column['is_group_by'] != 'hidden') {
                    $this->group_column_is_invisible = 0;
                    array_push($header_row, $display);
                }
                else {
                    $this->group_column_is_invisible = 1;
                }
                $this->group_header = $display;
                $this->chart_group_position[] = $summary_count;
            }
            else {
                array_push($header_row, $display);
            }
            $summary_count++;

            //if summary, but not the total summary, and doing the chart
            if ($skip_non_group == false && $column_field_name == 'summary_columns' && $this->do_chart == true) {
                //              $this->layout_manager->setAttribute('context', 'HeaderCellPlain');
                $chart_header = array();
                $chart_header['label'] = $this->layout_manager->widgetDisplay($display_column);
                $chart_header['column_key'] = $column_key;
                array_push($this->chart_header_row, $chart_header);
            }
            elseif ($skip_non_group == true && $column_field_name == 'summary_columns' && $this->do_chart == true) {
                $this->layout_manager->setAttribute('context', 'HeaderCellPlain');
                $chart_header = array();
                $chart_header['label'] = $this->layout_manager->widgetDisplay($display_column);
                $chart_header['column_key'] = $column_key;
                array_push($this->chart_total_header_row, $chart_header);
            }

        } // END foreach

        // Bug 29829 Make sure the header names are distinct labels for sugarpdf writeCellTable()
        if ($force_distinct) {
            $distinct_labels = array();
            for ($i = 0; $i < sizeof($header_row); $i++) {
                $label = $header_row[$i];
                if (!in_array($label, $distinct_labels)) {
                    $distinct_labels[] = $label;
                } else {
                    while (in_array($label, $distinct_labels)) {
                        $label .= ' ';
                    }
                    $distinct_labels[] = $label;
                }
            }
            $header_row = $distinct_labels;
        }

        return $header_row;
    }

    function get_summary_total_row($exporting = false)
    {
        $this->_load_currency();
        $get_next_row = $this->get_next_row('total_result', 'summary_columns', true, $exporting);
        return $get_next_row;
    }

    function get_summary_next_row()
    {
        $this->_load_currency();
        $get_next_row = $this->get_next_row('summary_result', 'summary_columns');
        if(isset($get_next_row['count'])) {
            $this->current_summary_row_count = $get_next_row['count'];
        } else {
            $this->current_summary_row_count = null;
        }

        return $get_next_row;
    }

    function get_next_child_row($result_name)
    {
        if (empty($this->child_filter)) return false;
        $db_row = $this->db->fetchByAssoc($this->$result_name);
        if (!$db_row) return false;
        $fields = array();
        foreach ($db_row as $key => $value)
        {
            $fields[strtoupper($key)] = $value;
        }
        $this->_load_currency();
        // here we want to make copies, so use foreach
        $cells = array();
        foreach ($this->report_def['display_columns'] as $display_column)
        {
            $display_column['table_alias'] = $this->getTableFromField($display_column);
            $display_column['fields'] = $fields;

            $this->register_field_for_query($display_column);

            if ($this->plain_text_output == true) {
                $this->layout_manager->setAttribute('context', 'ListPlain');
            } else {
                $this->layout_manager->setAttribute('context', 'List');
            }
            $field_name = $this->getColumnFieldName($display_column);
            if (!empty($field_name) && isset($display_column['fields'][$field_name])) {
                $display_column['fields'][$field_name] = $this->db->fromConvert($display_column['fields'][$field_name], $display_column['type']);
            }
            $display = $this->layout_manager->widgetDisplay($display_column);
            $cells[] = $display;
        }

        return $cells;
    }

    function getDataTypeForColumnsForMatrix($column_field_name = 'summary_columns')
    {
        $labelToDataTypeArray = array();
        foreach ($this->report_def[$column_field_name] as $display_column) {
            $display_column['table_alias'] = $this->getTableFromField($display_column);
            $this->register_field_for_query($display_column);
            $display_column['varname'] = $display_column['label'];
            $labelToDataTypeArray[$display_column['label']] = $display_column;
        } // foreach
        return $labelToDataTypeArray;
    } // fn



    /**
     * Get data field name for the display column
     * @param array $display_column
     * @return string
     */
    protected function getColumnFieldName($display_column)
    {
        if (isset($display_column['group_function'])) {
            $field_name = $this->getTruncatedColumnAlias(strtoupper($display_column['table_alias']) . "_" . strtoupper($display_column['group_function']) . "_" . strtoupper($display_column['name']));
        }

        if (!isset($field_name) || !isset($display_column['fields'][$field_name])) {
            $field_name = $this->getTruncatedColumnAlias(strtoupper($display_column['table_alias']) . "_" . strtoupper($display_column['name']));
        }

        return $field_name;
    }

    function get_next_row($result_field_name = 'result', $column_field_name = 'display_columns', $skip_non_summary_columns = false, $exporting = false)
    {
        global $current_user;
        $chart_cells = array();

        if ($this->do_export) {
            $db_row = $this->db->fetchByAssoc($this->$result_field_name, false);
        }
        else {
            $db_row = $this->db->fetchByAssoc($this->$result_field_name);
        }

        if ($db_row == 0 || sizeof($db_row) == 0) {
            return 0;
        }

        // Call custom hooks
        if (isset($this->full_bean_list) && is_array($this->full_bean_list)
            && array_key_exists('self', $this->full_bean_list) && is_object($this->full_bean_list['self'])
            && method_exists($this->full_bean_list['self'], 'call_custom_logic')) {
            $this->full_bean_list['self']->call_custom_logic(
                'process_report_row',
                array('row' => &$db_row, 'reporter' => $this)
            );
        }

        if ($result_field_name == 'summary_result') {
            if (!empty($this->child_filter) && !empty($db_row[$this->child_filter_name])) {
                $this->child_filter_by = $db_row[$this->child_filter_name];
            }
            else {
                $this->child_filter = '';
                $this->child_filter_by = '';
                $this->child_filter_name = '';
            }
        }

        $row = array();
        $cells = array();
        $fields = array();

        foreach ($db_row as $key => $value) {
            //if value is null or not set, then change to empty string.  This prevents array index errors while processing
            $fields[strtoupper($key)] = is_null($value) ? '' : $value;
        }

        // here we want to make copies, so use foreach

        foreach ($this->report_def[$column_field_name] as $display_column) {
            $display_column['table_alias'] = $this->getTableFromField($display_column);

            $this->register_field_for_query($display_column);

            if ($skip_non_summary_columns && empty($display_column['group_function'])) {
                if ($exporting || $this->plain_text_output)
                    array_push($cells, ' ');
                else
                    array_push($cells, '&nbsp;');
                continue;
            }
            $display_column['fields'] = $fields;

            if ($this->plain_text_output == true) {
                /*nsingh: bug 13554- date and time fields must be displayed using user's locale settings.
                * Since to_pdf uses plain_text_output=true, we handle the date and time case here by using the 'List' context of the layout_manager
                */
                if ($display_column['type'] == 'date' || $display_column['type'] == 'time' || $display_column['type'] == 'datetimecombo')
                    $this->layout_manager->setAttribute('context', 'List');
                else
                    $this->layout_manager->setAttribute('context', 'ListPlain');
            }
            else {
                $this->layout_manager->setAttribute('context', 'List');
            }

            // Make sure 'AVG' aggregate is shown as float, regardless of the original field type
            if (!empty($display_column['group_function']) && strtolower($display_column['group_function']) === 'avg'
                && $display_column['type'] != 'currency') {
                $display_column['type'] = 'float';
            }

            if ($display_column['type'] != 'currency' || (substr_count($display_column['name'], '_usdoll') == 0 &&
                    (isset($display_column['group_function']) ?
                        ($display_column['group_function'] != 'weighted_amount' && $display_column['group_function'] != 'weighted_sum') :
                        true)
                )
            ) {
                $pos = $display_column['table_key'];
                $module_name = '';
                if ($pos) {
                    $module_name = substr($pos, strrpos($pos, ':') + 1);
                }

                $field_name = $this->getColumnFieldName($display_column);

                if ($module_name == 'currencies' && empty($display_column['fields'][$field_name])) {
                    switch ($display_column['name']) {
                        case 'iso4217':
                            $display = $this->currency_obj->getDefaultISO4217();
                            break;
                        case 'symbol':
                            $display = $this->currency_obj->getDefaultCurrencySymbol();
                            break;
                        case 'name':
                            $display = $this->currency_obj->getDefaultCurrencyName();
                            break;
                        default:
                            $display = $this->layout_manager->widgetDisplay($display_column);
                    }
                    $display_column['fields'][$field_name] = $display;
                } else {
                    if (!empty($field_name) && isset($display_column['fields'][$field_name])) {
                        $display_column['fields'][$field_name] = $this->db->fromConvert($display_column['fields'][$field_name], $display_column['type']);
                    }
                    $display = $this->layout_manager->widgetDisplay($display_column);
                }

            } else {

                if (isset($display_column['group_function'])) {
                    $field_name = $this->getTruncatedColumnAlias(strtoupper($display_column['table_alias']) . "_" . strtoupper($display_column['group_function']) . "_" . strtoupper($display_column['name']));
                } else {
                    unset($field_name);
                }

                if (!isset($field_name) || !isset($display_column['fields'][$field_name])) {
                    $field_name = $this->getTruncatedColumnAlias(strtoupper($display_column['table_alias']) . "_" . strtoupper($display_column['name']));
                }

                if (isset($display_column['fields'][$field_name])) {
                    $display = $display_column['fields'][$field_name];
                }
            }

            if ($display_column['type'] == 'currency' && (strpos($display_column['name'], '_usdoll') !== false || !empty($display_column['group_function']))) {
                // convert base to user preferred if set in user prefs
                if($current_user->getPreference('currency_show_preferred')) {
                    $userCurrency = SugarCurrency::getUserLocaleCurrency();
                    $raw_display = SugarCurrency::convertWithRate($display_column['fields'][$field_name], 1.0, $userCurrency->conversion_rate);
                    $display = SugarCurrency::formatAmountUserLocale($raw_display, $userCurrency->id);
                } else {
                    $raw_display = $display_column['fields'][$field_name];
                    $display = SugarCurrency::formatAmountUserLocale($raw_display, SugarCurrency::getBaseCurrency()->id);
                }
            } else {
                $raw_display = $display;
            }

            if (isset($display_column['type']) && $display_column['type'] == 'float') {
                $display = $this->layout_manager->widgetDisplay($display_column);
            }

            if (isset($display_column['type'])) {

                $alias = $this->alias_lookup[$display_column['table_key']];
                $array_key = strtoupper($alias . '__count');

                if (array_key_exists($array_key, $display_column['fields'])) {
                    $displayData = $display_column['fields'][$array_key];
                    if (empty($displayData) && $display_column['type'] != 'bool' && ($display_column['type'] != 'enum' || $display_column['type'] == 'enum' && $displayData != '0')) {
                        $display = "";
                    }
                } // if


                if (is_array($this->moduleBean->field_defs)) {
                    if (isset($this->moduleBean->field_defs[$display_column['type']])) {
                        if (isset($this->moduleBean->field_defs[$display_column['type']]['options'])) {
                            $trans_options = translate($this->moduleBean->field_defs[$display_column['type']]['options']);

                            if (isset($trans_options[$display_column['fields'][$field_name]])) {
                                $display = $trans_options[$display_column['fields'][$field_name]];
                            }
                        }
                    }
                }
            } // if

            //  for charts
            if ($column_field_name == 'summary_columns' && $this->do_chart) {
                $raw_value = "";
                $keys = array_keys($fields);
                foreach ($this->report_def['summary_columns'] as $index => $column) {
                    if ($column['name'] == $display_column['name'] && isset($keys[$index])
                    && isset($fields[$keys[$index]])) {
                        $raw_value = $fields[$keys[$index]];
                        break;
                    }
                }
                $cell_arr = array(
                    'val' => $raw_display,
                    'key' => $display_column['column_key'],
                    'raw_value' => $raw_value
                );
                array_push($chart_cells, $cell_arr);
            }

            if ($exporting) {
                global $app_list_strings;
                // parse out checkboxes
                // TODO: wp this should be done in the widget
                if (preg_match('/type.*=.*checkbox/Uis', $display)) {
                    if (preg_match('/checked/i', $display)) $display = $app_list_strings['dom_switch_bool']['on'];
                    else $display = $app_list_strings['dom_switch_bool']['off'];
                }
            }
            array_push($cells, $display);
        } // END foreach

        $row['cells'] = $cells;

        // calculate summary rows count by returning the highest number of 'count' fields for the given db row
        $count = 1;
        $count_exists = false;
        foreach ($db_row as $count_column => $count_value)
        {
            if (substr($count_column, -10) == "__allcount" || $count_column == 'count') {
                $count = max(array($count,$count_value, 1));
                $count_exists = true;
            }
        }

        if ($count_exists) {
            $row['count'] = $count;
        }

        // for charts
        if ($column_field_name == 'summary_columns' && $this->do_chart) {
            $chart_row = 0;
            if (!empty($db_row['count'])) {
                $chart_row = array('cells' => $chart_cells, 'count' => $db_row['count']);
            }
            else {
                $chart_row = array('cells' => $chart_cells);
            }
            array_push($this->chart_rows, $chart_row);
        }

        if ($column_field_name == 'summary_columns' && isset($this->chart_group_position) && isset($this->group_header)) {
            $row['group_pos'] = $this->chart_group_position;
            $row['group_header'] = $this->group_header;
            $row['group_column_is_invisible'] = $this->group_column_is_invisible;
        }

        return $row;
    }

    function save($report_name)
    {
        global $current_user;
        $saved_vars = array();

        $saved_report = BeanFactory::newBean('Reports');
        $report_type = 'tabular';
        $chart_type = 'none';

        if (isset($this->report_def['chart_type'])) {
            $chart_type = $this->report_def['chart_type'];
        }
        if ($this->report_def['report_type'] == 'summary') {
            $report_type = 'summary';
            if (!empty($this->report_def['display_columns'])) {
                $report_type = 'detailed_summary';
            } else {
                if (!empty($this->report_def['group_defs'])) {
                    $group_def_array = $this->report_def['group_defs'];
                    if (isset($this->report_def['layout_options']) &&
                        ((count($group_def_array) == 2) || (count($group_def_array) == 3))
                    ) {
                        $report_type = 'Matrix';
                    } // if
                } // if
            } // else
        }

        $record = $this->request->getValidInputRequest('record', 'Assert\Guid', -1) ?: -1;
        $assignedUserId = $this->request->getValidInputRequest('assigned_user_id', 'Assert\Guid');

        require_once('include/formbase.php');
        populateFromPost('', $saved_report);

        $result = $saved_report->save_report(
            $record,
            $assignedUserId,
            $report_name,
            $this->module,
            $report_type,
            $this->report_def_str,
            0,
            $saved_report->team_id,
            $chart_type,
            $saved_report->acl_team_set_id
        );
        $this->saved_report = $saved_report;

        if (!empty($this->saved_report)) {
            $_REQUEST['record'] = $this->saved_report->id;
        }
        return $result;
    }

    /**
     * Delete files cached by cache_modules_js()
     * @param $user all if null
     */
    public static function clearCaches($user = null)
    {
        $md5 = !empty($user) ? '_'.md5($user->id) : '';

        foreach (glob(sugar_cached('modules').'/modules_def_*'.$md5.'.js') as $file) {
            unlink($file);
        }
    }

    function cache_modules_def_js()
    {
        global $current_language, $current_user;
        $cacheDefsJs = sugar_cached('modules/modules_def_' . $current_language . '_' . md5($current_user->id) . '.js');
        $cacheFiscalJs = sugar_cached('modules/modules_def_fiscal_' . $current_language . '_' . md5($current_user->id) . '.js');

        $files = array(
            array($cacheDefsJs, 'template_module_defs_js'),
            array($cacheFiscalJs, 'template_module_defs_fiscal_js'),
        );

        foreach ($files as $file) {
            $fileName = $file[0];
            $function = $file[1];

            if (!file_exists($fileName)) {
                require_once('modules/Reports/templates/templates_modules_def_js.php');

                ob_start();
                $function();
                $data = ob_get_clean();

                if (is_writable(sugar_cached('modules/'))) {
                    file_put_contents($fileName, $data);
                }
            }
        }
    }

    function is_old_content($content)
    {

        if (preg_match('/report_type\=/', $content)) {
            return true;
        }
        return false;

    }

    function run_chart_queries()
    {

        $this->run_summary_query();

        $this->get_summary_header_row();

        while (($row = $this->get_summary_next_row()) != 0)
        {
        }
        if ($this->has_summary_columns()) {
            $this->run_total_query();
        }
        $this->get_summary_total_row();
    }

    // static function to return the modules associated to a report definition
    function getModules(&$report_def)
    {
        $modules = array();
        $modules_hash[$report_def['module']] = 1;
        $focus = BeanFactory::newBean($report_def['module']);
        $linked_fields = $focus->get_linked_fields();

        foreach ($report_def['links_def'] as $name) {
            $properties = $linked_fields[$name];
            $class = load_link_class($properties);

            $link = new $class($properties['relationship'], $focus, $properties);
            $module = $link->getRelatedModuleName();
            $modules_hash[$module] = 1;
        }

        $modules = array_keys($modules_hash);
        return $modules;
    }


    /**
     * getTruncatedColumnAlias
     * This function ensures that a column alias is no more than 28 characters.  Shoulud the column_name
     * argument exceed 28 charcters, it creates an alias using the first 22 characters of the column_name
     * plus an md5 of the first 6 characters of the lowercased column_name value.
     *
     */
    private function getTruncatedColumnAlias($column_name)
    {
        if (empty($column_name) || !is_string($column_name) || strlen($column_name) < 28) {
            return $column_name;
        }

        return strtoupper(substr($column_name, 0, 22) . substr(md5(strtolower($column_name)), 0, 6));
    }


    /**
     * getExt2FieldDefSelectPiece
     *
     * This is a private helper function to separate a piece of code that creates the select statement for a field where
     * there is an aggregation of columns
     *
     * @param $field_def Array representing the field definition to build the select piece for
     * @param $add_alias boolean true to add the column alias, false otherwise (you would want false for group by)
     */
    private function getExt2FieldDefSelectPiece($field_def, $add_alias = true)
    {
        $extModule = BeanFactory::newBean($field_def['ext2']);
        $secondaryTableAlias = $field_def['secondary_table'];
        if (!empty($this->selected_loaded_custom_links) && !empty($this->selected_loaded_custom_links[$field_def['secondary_table'] . '_' . $field_def['rep_rel_name']])) {
            $secondaryTableAlias = $this->selected_loaded_custom_links[$field_def['secondary_table'] . '_' . $field_def['rep_rel_name']]['join_table_alias'];
        } else if (!empty($this->selected_loaded_custom_links) && !empty($this->selected_loaded_custom_links[$field_def['secondary_table']])) {
            $secondaryTableAlias = $this->selected_loaded_custom_links[$field_def['secondary_table']]['join_table_alias'];
        }

        $this->extModules[$secondaryTableAlias] = $extModule;

        if (isset($extModule->field_defs['name']['db_concat_fields'])) {
            $select_piece = db_concat($secondaryTableAlias, $extModule->field_defs['name']['db_concat_fields']);
        } else if(isset($field_def['rname']) && isset($extModule->field_defs[$field_def['rname']])) {
         	$select_piece = $secondaryTableAlias . ".{$field_def['rname']}";
        } else {
            $select_piece = $secondaryTableAlias . '.name';
        }

        $select_piece .= $add_alias ? " {$secondaryTableAlias}_name" : ' ';

        return $select_piece;
    }

    protected function handleException($msg, $exit_code=1) {
        if($this->fromApi === false) {
            sugar_die($msg, $exit_code);
        } else {
            throw new SugarApiExceptionNotAuthorized($msg);
        }
    }

    /**
     * Returns the report data as a flat list (no groups or aggs)
     * @return array
     */
    public function getData()
    {
        global $alias_map;

        $result = [];

        if (!isset($this->result)) {
            $this->run_query();
        }

        while ($row = $this->db->fetchByAssoc($this->result)) {
            $row = array_combine(
                array_map(
                    function ($key) use ($alias_map) {
                        return isset($alias_map[$key]) ? $alias_map[$key] : $key;
                    },
                    array_keys($row)
                ),
                array_values($row)
            );
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Returns the record ids of this report
     * @param array $reportDef
     * @param integer $offset
     * @param integer $limit
     * @return array Array of record ids
     */
    public function getRecordIds($offset = 0, $limit = -1)
    {
        $recordIds = array();
        $this->create_where();
        $this->create_from();
        $id = $this->full_bean_list['self']->table_name . '.id';
        $query = "SELECT DISTINCT $id {$this->from} WHERE {$this->where} AND {$this->focus->table_name}.deleted=0";
        $query .= " ORDER BY $id ASC";
        $query = str_replace("\n", " ", $query);
        if ($offset > 0 || $limit > 0) {
            $result = $this->db->limitQuery($query, $offset, $limit, true, "Error executing query");
        } else {
            $result = $this->db->query($query, true, "Error executing query");
        }
        if ($result) {
            while ($row = $this->db->fetchByAssoc($result)) {
                $recordIds[] = $row['id'];
            }
        }
        return $recordIds;
    }

    /**
     * Returns record count of this report
     * @return integer
     */
    public function getRecordCount()
    {
        $recordCount = 0;
        $this->create_where();
        $this->create_from();
        $id = $this->full_bean_list['self']->table_name . '.id';
        $query = "SELECT COUNT(DISTINCT $id) AS record_count {$this->from} WHERE {$this->where} AND {$this->focus->table_name}.deleted=0";
        $result = $this->db->query($query, true, "Error executing query");
        if ($result) {
            $row = $this->db->fetchByAssoc($result);
            $recordCount = (int) $row['record_count'];
        }
        return $recordCount;
    }
}
