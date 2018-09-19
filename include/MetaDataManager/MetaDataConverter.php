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
 * Assists in backporting 6.6 Metadata formats to legacy style in order to
 * maintain backward compatibility with old clients consuming the V3 and V4 apis.
 */
class MetaDataConverter
{
    /**
     * An instantiated object of MetaDataConverter type
     *
     * @var MetaDataConverter
     */
    protected static $converter = null;

    /**
     * Actions associated to their ACLAction type
     *
     * @var array
     */
    protected $aclActionList = array(
        'EditView' => 'edit',
        '' => 'list',
        'index' => 'list',
        'Import' => 'import',
        'Reports' => 'list',
        'DetailView' => 'view',
        'Administration' => 'admin',
    );

    /**
     * Converts edit and detail view defs that contain fieldsets to a compatible
     * defs that does not contain fieldsets. In essence, it splits up any fieldsets
     * and moves them out of their grouping into individual fields within the panel.
     *
     * This method assumes that the defs have already been converted to a legacy
     * format.
     *
     * @param array $defs
     * @return array
     */
    public static function fromGridFieldsets(array $defs)
    {
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            $newpanels = array();
            $offset = 0;
            foreach ($defs['panels'] as $row) {
                if (is_array($row[0]) && isset($row[0]['type'])
                    && $row[0]['type'] == 'fieldset' && isset($row[0]['related_fields'])
                ) {
                    // Fieldset.... convert
                    foreach ($row[0]['related_fields'] as $fName) {
                        $newpanels[$offset] = array($fName);
                        $offset++;
                    }
                } else {
                    // do nothing
                    $newpanels[$offset] = $row;
                    $offset++;
                }
            }

            $defs['panels'] = $newpanels;
        }

        return $defs;
    }

    /**
     * Static entry point, will instantiate an object of itself to run the process.
     * Will convert $defs to legacy format $viewtype if there is a converter for
     * it, otherwise will return the defs as-is with no modification.
     *
     * @static
     * @param string $viewtype One of list|edit|detail
     * @param array $defs The defs to convert
     * @return array Converted defs if there is a converter, else the passed in defs
     */
    public static function toLegacy($viewtype, $defs)
    {
        if (null === self::$converter) {
            self::$converter = new self;
        }

        $method = 'toLegacy' . ucfirst(strtolower($viewtype));
        if (method_exists(self::$converter, $method)) {
            return self::$converter->$method($defs);
        }

        return $defs;
    }

    /**
     * Takes in a 6.6+ version of mobile|portal|sidecar list view metadata and
     * converts it to pre-6.6 format for legacy clients. The formats of the defs
     * are pretty dissimilar so the steps are going to be:
     *  - Take in all defs
     *  - Clip everything but the fields portion of the panels section of the defs
     *  - Modify the fields array to be keyed on UPPERCASE field name
     *
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyList(array $defs)
    {
        $return = array();

        // Check our panels first
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            foreach ($defs['panels'] as $panels) {
                // Handle fields if there are any (there should be)
                if (isset($panels['fields']) && is_array($panels['fields'])) {
                    // Logic here is simple... pull the name index value out, UPPERCASE it and
                    // set that as the new index name
                    foreach ($panels['fields'] as $field) {
                        if (isset($field['name'])) {
                            $name = strtoupper($field['name']);
                            unset($field['name']);
                            $return[$name] = $field;
                        }
                    }
                }
            }
        }


        return $return;
    }

    /**
     * Takes a Sidecar Subpanel view def and returns a BWC compatibile Subpanel view def
     *
     * @param array $oldDefs Field defs to convert
     * @param string $moduleName, the module we are converting
     * @return array BWC defs
     */
    public function toLegacySubpanelsViewDefs(array $defs, $moduleName)
    {
        if (!isset($defs['panels'])) {
            return array();
        }

        $oldDefs = array();

        // for BWC, we need to have some top buttons.  Sidecar doesn't have buttons in the def
        $oldDefs['top_buttons'] = array(
            array(
                'widget_class' => 'SubPanelTopCreateButton'
            ),
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'popup_module' => $moduleName,
            ),
        );

        $oldDefs['list_fields'] = $this->toLegacyList($defs);
        return $oldDefs;
    }

    protected $subpanelNameTranslation = array(
        'email1' => 'email',
    );

    /**
     * Convert legacy subpanels view defs to sidecar subpanel view defs
     * @param array $defs
     * @param string module
     * @return array
     */
    public function fromLegacySubpanelsViewDefs(array $defs, $module)
    {
        if (!isset($defs['list_fields'])) {
            throw new \RuntimeException("Subpanel is defined without fields");
        }

        $viewdefs = array('panels' => array(), 'type' => 'subpanel-list');

        $viewdefs['panels'][0]['name'] = 'panel_header';
        $viewdefs['panels'][0]['label'] = 'LBL_PANEL_1';

        $viewdefs['panels'][0]['fields'] = array();
        $bean = BeanFactory::newBean($module);

        foreach ($defs['list_fields'] as $fieldName => $details) {
            if (isset($details['vname'])) {
                $details['label'] = $details['vname'];
            }
            // disregard buttons
            if ((isset($details['label']) && stripos($details['label'], 'button') !== false) ||
                stripos($fieldName, 'button') !== false
            ) {
                continue;
            }

            if (isset($details['usage'])) {
                continue;
            }

            if (!isset($details['default'])) {
                $details['default'] = true;
            }

            if (!isset($details['enabled'])) {
                $details['enabled'] = true;
            }
            if(!empty($this->subpanelNameTranslation[$fieldName])) {
                $details['name'] = $this->subpanelNameTranslation[$fieldName];
            } else {
                $details['name'] = $fieldName;
            }

            if(!empty($details['widget_class'])) {
                if($details['widget_class'] == 'SubPanelDetailViewLink') {
                    $details['link'] = true;
                } elseif($details['widget_class'] == 'SubPanelEmailLink') {
                    $details['type'] = 'email';
                } else {
                    // See BR-1436: we will drop unknown widgets for now since Sidecar
                    // can not properly display them
                    continue;
                }

            }

            if ($bean && !empty($bean->field_defs[$details['name']])) {
                $newDefs = $bean->field_defs[$details['name']];
                if (!empty($newDefs['fields'])) {
                    $details['fields'] = $newDefs['fields'];
                }
                if (empty($details['type']) && !empty($newDefs['type']) && $newDefs['type'] != 'varchar') {
                    $details['type'] = $newDefs['type'];
                }
                // special handling for teamsets, since they have changed from 6
                if(!empty($newDefs['custom_type']) && $newDefs['custom_type'] == 'teamset') {
                    $details['sortable'] = false;
                    $details['link'] = false;
                    unset($details['type']);
                    unset($details['target_module']);
                    unset($details['target_record_key']);
                }
            }

            $viewdefs['panels'][0]['fields'][] = $this->fromLegacySubpanelField($details);
        }
        return $viewdefs;
    }

    /**
     * Convert a single field from the old subpanel fielddef
     * to the new sidecar def.
     *
     * This will return an array that contains any of the following:
     * label - the field label, will use vname if label doesn't exist
     * width - the width of the field
     * type - the field type [varchar, etc]
     * target module - for link fields the target module
     * target record key - for link fields the target key for the target_module
     *
     * @param array $details
     * @return array
     */
    public function fromLegacySubpanelField(array $fieldDefs)
    {
        static $fieldMap = array(
            'name' => true,
            'label' => true,
            'type' => true,
            'target_module' => true,
            'target_record_key' => true,
            'default' => true,
            'enabled' => true,
            'link' => true,
            'fields' => true,
            'sortable' => true,
        );

        return array_intersect_key($fieldDefs, $fieldMap);
    }

    /**
     * This converts the sidecar subpanel name to the legacy subpanel name
     * @param array $def - Sidecar Subpanel Definition
     * @return string - the Legacy subpanel name
     */
    public function toLegacySubpanelName(array $def)
    {
        if (isset($def['override_subpanel_list_view'])) {
            if (is_array($def['override_subpanel_list_view']) && isset($def['override_subpanel_list_view']['view'])) {
                $legacyName = $def['override_subpanel_list_view']['view'];
            } else {
                $legacyName = $def['override_subpanel_list_view'];
            }
            $legacyName = str_replace('subpanel-', '', $legacyName);
            $legacyName = str_replace(' ', '', ucwords(str_replace('-', ' ', $legacyName)));
            // special awesome condition so aSubpanel doesn't blow up because the bwc is ProspectLists
            if ($legacyName == 'ForProspectlists') {
                $legacyName = 'ForProspectLists';
            }
        } else {
            $legacyName = 'default';
        }
        return $legacyName;
    }

    /**
     * @param array $layoutDefs
     * @param SugarBean $bean
     * @return array legacy LayoutDef
     */
    public function toLegacySubpanelLayoutDefs(array $layoutDefs, SugarBean $bean)
    {
        $return = array();

        foreach ($layoutDefs as $order => $def) {
            // no link can't move on
            if (empty($def['context']['link'])) {
                continue;
            }

            // In most cases we can safely expect a Link2 object. But in cases
            // where a vardef defines it's own link_class and link_file, we need
            // to honor that. For example, archived_emails in Accounts.
            $linkClass = 'Link2';
            $linkName = $def['context']['link'];
            if (isset($bean->field_defs[$linkName])) {
                $linkClass = load_link_class($bean->field_defs[$linkName]);
            }
            $link = new $linkClass($linkName, $bean);
            $linkModule = $link->getRelatedModuleName();

            $legacySubpanelName = $this->toLegacySubpanelName($def);

            // if we don't have a label at least set the module name as the label
            // similar to configure shortcut bar
            $label = isset($def['label']) ? $def['label'] : translate($linkModule);
            $return[$linkName] = array(
                'order' => $order,
                'module' => $linkModule,
                'subpanel_name' => $legacySubpanelName,
                'sort_order' => 'asc',
                'sort_by' => 'id',
                'title_key' => $label,
                'get_subpanel_data' => $linkName,
                'top_buttons' => array(
                    array(
                        'widget_class' => 'SubPanelTopButtonQuickCreate',
                    ),
                    array(
                        'widget_class' => 'SubPanelTopSelectButton',
                        'mode' => 'MultiSelect',
                    ),
                ),
            );
            if (!empty($def['override_subpanel_list_view'])) {
                $return[$linkName]['override_subpanel_name'] = $def['override_subpanel_list_view'];
            }
        }
        return array('subpanel_setup' => $return);
    }

    /**
     * Simple accessor into the grid legacy converter
     *
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyEdit(array $defs)
    {
        return $this->toLegacyGrid($defs);
    }

    /**
     * Simple accessor into the grid legacy converter
     *
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyDetail(array $defs)
    {
        return $this->toLegacyGrid($defs);
    }

    /**
     * Takes in a 6.6+ version of mobile|portal|sidecar edit|detail view metadata and
     * converts it to pre-6.6 format for legacy clients.
     *
     * NOTE: This will only work for layouts that have only one field per row. For
     * the 6.6 upgrade that is sufficient since we were only converting portal
     * and mobile viewdefs. As is, this method will NOT convert grid layout view
     * defs that have more than one field per row.
     *
     * @param array $defs
     * @return array
     */
    protected function toLegacyGrid(array $defs)
    {
        // Check our panels first
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            // For our new panels
            $newpanels = array();
            foreach ($defs['panels'] as $panels) {
                // Handle fields if there are any (there should be)
                if (isset($panels['fields']) && is_array($panels['fields'])) {
                    // Logic is fairly straight forward... take each member of
                    // the fields array and make it an array of its own
                    foreach ($panels['fields'] as $field) {
                        $newpanels[] = array($field);
                    }
                }
            }

            unset($defs['panels']);
            $defs['panels'] = $newpanels;
        }

        return $defs;
    }

    /**
     * Convert a legacy subpanel name to the new sidecar name
     * Examples:
     * ForAccounts becomes subpanel-for-accounts
     * default becomes subpanel-list
     *
     * @param string $subpanelName the legacy subpanel
     * @return string the new subpanel name
     */
    public function fromLegacySubpanelName($subpanelName)
    {
        if (substr($subpanelName, 0, 9) == 'subpanel-') {
            return $subpanelName;
        }

        $newName = ($subpanelName === 'default') ? 'list' : strtolower($subpanelName);
        if (substr($newName, 0, 3) === "for") {
            $newName = "for-" . substr($newName, 3);
        }
        return 'subpanel-' . $newName;
    }

    /**
     * Convert a legacy subpanel path to the new sidecar path
     * @param string $filename the path to a legacy subpanel
     * @param string client the client
     * @return string the new sidecar subpanel path
     */
    public function fromLegacySubpanelPath($fileName, $client = 'base')
    {
        $pathInfo = pathinfo($fileName);
        $dirParts = preg_split('/[\/\\\]+/', $pathInfo['dirname'], -1, PREG_SPLIT_NO_EMPTY);

        if (count($dirParts) < 3) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Directory '%s' is an incorrect path for a subpanel",
                    $fileName
                )
            );
        }

        $subpanelFileName = $pathInfo['filename'];
        if (substr_count($pathInfo['filename'], '_') > 1 && stristr($pathInfo['filename'], 'subpanel')) {
            $parts = explode('_subpanel_', $pathInfo['filename']);
            $beanNameParts = explode('_', $parts[0]);
            $subPanelBeanName = '';
            foreach ($beanNameParts as $part) {
                $subPanelBeanName .= ucwords($part);
            }

            // case is not the actually object name, it's aCase
            if ($subPanelBeanName == 'Case') {
                $subPanelBeanName = 'aCase';
            }

            $focus = BeanFactory::newBeanByName($subPanelBeanName);
            if ($focus) {
                $subpanelFileName = $this->getLegacySubpanelFileName($focus, $parts[1]);
            }
        }

        $newSubpanelName = $this->fromLegacySubpanelName($subpanelFileName);

        $newPath = str_replace(
            "metadata/subpanels/{$pathInfo['filename']}.php",
            "clients/{$client}/views/{$newSubpanelName}/{$newSubpanelName}.php",
            $fileName
        );

        return $newPath;
    }

    /**
     * Returns name of the file containing legacy subpanel metadata
     *
     * @param SugarBean $bean Parent bean
     * @param string $linkName Subpanel link name
     * @return string|null
     */
    public function getLegacySubpanelFileName(SugarBean $bean, $linkName)
    {

        $field = $bean->getFieldDefinition($linkName);
        if ($field && $field['type'] == 'link') {
            if (!empty($bean->field_defs[$linkName]['relationship'])) {
                $relName = $bean->field_defs[$linkName]['relationship'];
            } else {
                $relName = $linkName;
            }
            // since we have a valid link, we need to test the relationship to see if it's custom relationship
            $relationships = new DeployedRelationships($bean->module_name);
            $relationship = $relationships->get($relName);
            if ($relationship) {
                $relDef = $relationship->getDefinition();
                if (!empty($relDef['is_custom']) && !empty($relDef['from_studio'])) {
                    return 'For' . $relDef['relationship_name'];
                }
            }
        }

        return 'For' . $bean->module_name;
    }

    /**
     * Convert a piece of a subpanel layoutdef to the new style
     * @param array $layoutdef old style layout
     * @return array new style layout for this piece
     */
    public function fromLegacySubpanelLayout(array $layoutdef)
    {
        $viewdefs = array(
            'layout' => 'subpanel',
        );

        // we aren't upgrading collections
        if (!empty($layoutdef['collection_list'])) {
            return $viewdefs;
        }

        foreach ($layoutdef as $key => $value) {
            if ($key == 'override_subpanel_name') {
                $subpanelFileName = $value;
                if (substr_count($value, '_') > 1 && stristr($value, 'subpanel')) {
                    $parts = explode('_subpanel_', $value);
                    $beanNameParts = explode('_', $parts[0]);
                    $subPanelBeanName = '';
                    foreach ($beanNameParts as $part) {
                        $subPanelBeanName .= ucwords($part);
                    }

                    // case is not the actually object name, it's aCase
                    if ($subPanelBeanName == 'Case') {
                        $subPanelBeanName = 'aCase';
                    }

                    $focus = BeanFactory::newBeanByName($subPanelBeanName);
                    if ($focus) {
                        $field = $focus->getFieldDefinition($parts[1]);
                        if ($field && $field['type'] == 'link') {
                            // since we have a valid link, we need to test the relationship to see if it's custom relationship
                            $relationships = new DeployedRelationships($focus->module_name);
                            $relationship = $relationships->get($parts[1]);
                            $relDef = array();
                            if ($relationship) {
                                $relDef = $relationship->getDefinition();
                            }
                            if (!empty($relDef['is_custom']) && !empty($relDef['from_studio']) &&
                                (!empty($relDef['name']) || !empty($relDef['relationship_name']))
                            ) {
                                $name = !empty($relDef['name']) ? $relDef['name'] : $relDef['relationship_name'];
                                $subpanelFileName = "For{$name}";
                            } else {
                                $subpanelFileName = "For{$focus->module_name}";
                            }
                        } else {
                            $subpanelFileName = "For{$focus->module_name}";
                        }
                    }
                }
                $viewdefs['override_subpanel_list_view'] = array(
                    'view' => $this->fromLegacySubpanelName($subpanelFileName),
                    'link' => (isset($layoutdef['get_subpanel_data']) ? $layoutdef['get_subpanel_data'] : ''),
                );
            }
            elseif ($key == 'title_key') {
                $viewdefs['label'] = $value;
            }
            elseif ($key == 'get_subpanel_data') {
                $viewdefs['context']['link'] = $value;
            }
        }

        return $viewdefs;
    }

    /**
     * Converts a legacy menu to the new style menu
     *
     * @param $module module converting
     * @param array $menu menu contents
     * @param bool $ext is this an Extension
     * @return string new menu layout
     */
    public function fromLegacyMenu($moduleName, array $menu)
    {
        $arrayName = "viewdefs['{$moduleName}']['base']['menu']['header']";

        $dataItems = array();

        foreach ($menu as $option) {
            $data = array();
            // get the menu manip done
            $url = parse_url($option[0]);
            parse_str($url['query'], $menuOptions);
            $data['label'] = trim($option[1]);
            if (isset($this->aclActionList[$menuOptions['module']])) {
                $data['acl_action'] = trim($this->aclActionList[$menuOptions['module']]);
                $data['acl_module'] = $moduleName;
            } elseif (isset($this->aclActionList[$menuOptions['action']])) {
                $data['acl_action'] = trim($this->aclActionList[$menuOptions['action']]);
                $data['acl_module'] = trim($menuOptions['module']);
            }

            if ($menuOptions['action'] == 'EditView' && empty($menuOptions['record'])) {
                $data['icon'] = "fa fa-plus";
            } else if($menuOptions['module'] == 'Import') {
                $data['icon'] = 'fa fa-upload';
            } else if($menuOptions['module'] == 'Reports' && $moduleName != 'Reports') {
                $data['icon'] = 'fa fa-bar-chart-o';
            }

            $data['route'] = $this->buildMenuRoute($menuOptions, $option[0]);
            $dataItems[] = $data;
        }

        return array('name' => $arrayName, 'data' => $dataItems);
    }

    /**
     * @param array $menuOptions the request variables
     * @param string $link the legacy link
     * @return string the correct route for the menu option
     */
    protected function buildMenuRoute(array $menuOptions, $link)
    {
        global $bwcModules;

        $url = parse_url($link);
        $currSiteUrl = parse_url($GLOBALS['sugar_config']['site_url']);

        // most likely another server, return the URL provided
        if (!empty($url['host']) && $url['host'] != $currSiteUrl['host']) {
            return $link;
        }

        if (in_array($menuOptions['module'], $bwcModules)) {
            return "#bwc/index.php?" . http_build_query($menuOptions);
        }

        $route = null;

        if ($menuOptions['action'] == 'EditView' && empty($menuOptions['record'])) {
            $route = "#{$menuOptions['module']}/create";
        } elseif (($menuOptions['action'] == 'EditView' || $menuOptions['action'] == 'DetailView') &&
            !empty($menuOptions['record'])
        ) {
            $route = "#{$menuOptions['module']}/{$menuOptions['record']}";
        } elseif (empty($menuOptions['action']) || $menuOptions['action'] == 'index') {
            $route = "#{$menuOptions['module']}";
        } else {
            $route = "#bwc/index.php?" . http_build_query($menuOptions);
        }

        return $route;
    }

    /**
     * Converts the old profileactions metadata to the new sidecar metadata
     *
     * @param array $global_control_links globalcontrollink format
     * @return array new sidecar profileactions view metadata
     */
    public function fromLegacyProfileActions(array $global_control_links)
    {
        $menu = $this->processFromGlobalControlLinkFormat($global_control_links);
        global $app_strings;
        include("clients/base/views/profileactions/profileactions.php");
        $baseMeta = $viewdefs['base']['view']['profileactions'];
        $arrayName = "viewdefs['base']['view']['profileactions']";

        $dataItems = array();
        if(!empty($menu)){
            // Convert custom items to sidecar format
            foreach ($menu as $option) {
                foreach($baseMeta as $baseOption){
                    if($option['LABEL'] === $app_strings[$baseOption["label"]]){
                        $dataItems[] = $baseOption;
                        continue 2;
                    }
                }
                $data = $this->convertCustomMenu($option);
                // Handles submenu conversions and performs several
                // checks to push the converted item and items if
                // they are not empty
                if(!empty($option['SUBMENU'])){
                    $submenuData = array();
                    foreach($option['SUBMENU'] as $submenu){
                        $converted = $this->convertCustomMenu($submenu);
                        if(!empty($converted)){
                            $submenuData[] = $converted;
                        }
                    }
                    if(!empty($submenuData)){
                        $data['submenu'] = $submenuData;
                    }
                }
                if(!empty($data)){
                    $dataItems[] = $data;
                }

            }
        }
        return array('name' => $arrayName, 'data' => $dataItems);
    }
    /**
     * Process the global_control_links format and extract labels, urls
     * and submenu links from globalcontrollinks
     *
     * @param array $global_control_links globalcontrollink format
     * @return array of items contain label, url and submenu
     */
    public function processFromGlobalControlLinkFormat($global_control_links){
        $gcls = array();
        foreach($global_control_links as $key => $value) {
            foreach ($value as $linkattribute => $attributevalue) {
                // get the main link info
                if ( $linkattribute == 'linkinfo' ) {
                    $gcls[$key] = array(
                        "LABEL" => key($attributevalue),
                        "URL"   => current($attributevalue),
                        "SUBMENU" => array(),
                    );
                    if(substr($gcls[$key]["URL"], 0, 11) == "javascript:") {
                        $gcls[$key]["OPENWINDOW"] = true;
                        $url = explode("'",$gcls[$key]["URL"]);
                        $gcls[$key]["URL"] = $url[1];
                    }
                }
                // and now the sublinks
                if ( $linkattribute == 'submenu' && is_array($attributevalue) ) {
                    foreach ($attributevalue as $submenulinkkey => $submenulinkinfo)
                        $gcls[$key]['SUBMENU'][$submenulinkkey] = array(
                            "LABEL" => key($submenulinkinfo),
                            "URL"   => current($submenulinkinfo),
                        );
                    if(substr($gcls[$key]['SUBMENU'][$submenulinkkey]["URL"], 0, 11) == "javascript:") {
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["OPENWINDOW"] = true;
                        $url = explode("'",$gcls[$key]['SUBMENU'][$submenulinkkey]["URL"]);
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["URL"] = $url[1];
                    }
                }
            }
        }
        return $gcls;
    }
    /**
     * Convert a single item array into sidecar meta format
     *
     * @param array $option
     * @return array sidecar profileactions item meta
     */
    public function convertCustomMenu($option){
        $data = array();
        $data['label'] = $option['LABEL'];
        $url = parse_url($option['URL']);
        if(isset($url['query'])){
            parse_str($url['query'], $menuOptions);
            if (isset($this->aclActionList[$menuOptions['module']])) {
                $data['acl_action'] = trim($this->aclActionList[$menuOptions['module']]);
            } elseif (isset($this->aclActionList[$menuOptions['action']])) {
                $data['acl_action'] = trim($this->aclActionList[$menuOptions['action']]);
            }
            $data['route'] = $this->buildMenuRoute($menuOptions, $option['URL']);
        }
        // This condition cover cases that Routes are:
        // External link (eg. https://www.google.com, Host is 'www.google.com' Scheme is 'https'),
        // Sidecar list view (eg. #Accounts, #Contacts, Fragment is 'Accounts', 'Contacts')
        // Opening new window link (eg.javascript:void(0), Scheme is 'javascript')
        elseif(isset($url['host']) || isset($url['fragment']) || isset($url['scheme'])){
            $data['route'] = $option['URL'];
            $data['acl_action'] = '';
        }
        // This condition is for urls that does not met any above conditions, which might be
        // a path to our internal php file (eg.client/base/views/attachments/attachments.php
        // In this case only 'Path' will be in the parsed url) Since we don't want user to access
        // our internal files, so we return nothing and not push this item to the menu list
        else{
            $GLOBALS['log']->error("Invalid URL {$option['URL']}");
            return;
        }
        if(isset($option['OPENWINDOW'])){
            $data['openwindow'] = $option['OPENWINDOW'];
        }
        if(isset($option['ICON'])){
            $data['icon'] = $this->{$option['ICON']};
        }
        return $data;
    }


    /**
     * Convert BWC searchdefs to sidecar filter metadata files.
     * @param array  $defs       : the defs for search fields
     * @param string $moduleName : name of the module
     * @param array $fieldDefs   : the defintion of the field
     * @param string $viewType   : the type of the view
     * @param string $viewClient : the name of the client
     * @return array
     */
    public function convertLegacyViewDefsToSidecar($defs, $moduleName, $fieldDefs, $viewType, $viewClient, $defaultSidecarFilter)
    {
        // load SearchFields.php
        $searchFields = $this->loadSearchFields($moduleName);
        $fields = array();
        if (!empty($defs['layout']['basic_search'])) {
            $old_fields = $defs['layout']['basic_search'];
        } else {
            $old_fields = array();
        }
        if (!empty($defs['layout']['advanced_search'])) {
            $old_fields = array_merge($old_fields, $defs['layout']['advanced_search']);
        }
        foreach ($old_fields as $name => $data) {
            if (!empty($data['name'])) {
                $name = $data['name'];
            }
            //BR-1680
            if ($name == 'assigned_user_id') {
                $name = 'assigned_user_name';
            }
            // We'll add those later
            if ($name == 'favorites_only' || $name == 'current_user_only') {
                continue;
            }
            // Also try range_* for date fields, see BR-1409
            if (!empty($searchFields["range_".$name])) {
                $searchFields[$name] = $searchFields["range_".$name];
            }
            // We don't know this field
            if (empty($searchFields[$name])) {
                // may be a custom field
                if (substr($name, -2) == '_c') {
                    $fields[$name] = $data;
                    if (isset($fields[$name]['label']) && !isset($fields[$name]['vname'])) {
                        $fields[$name]['vname'] = $fields[$name]['label'];
                        unset($fields[$name]['label']);
                    }
                    continue;
                }
            }
            // Subqueries not supported yet
            if (!empty($searchFields[$name]['operator']) && $searchFields[$name]['operator'] == 'subquery') {
                continue;
            }
            if (!empty($searchFields[$name]['db_field'])) {
                $label = '';
                if (isset($data['label'])) {
                    $label = $data['label'];
                }
                if (isset($searchFields[$name]['vname'])) {
                    $label = $searchFields[$name]['vname'];
                }
                $fields[$name] = $this->filterDbField(
                    $searchFields[$name]['db_field'],
                    $fieldDefs,
                    $viewType,
                    $viewClient
                );
                if (!empty($searchFields[$name]['type'])) {
                    $fields[$name]['type'] = $searchFields[$name]['type'];
                } else if (!empty($defaultSidecarFilter[$name]['type'])) {
                    $fields[$name]['type'] = $defaultSidecarFilter[$name]['type'];
                }
                if (empty($fields[$name]['dbFields']) &&
                    !$this->isValidField($name, $fieldDefs, $viewType, $viewClient)) {
                    unset($fields[$name]);
                    continue;
                }
                if (!empty($label)) {
                    $fields[$name]['vname'] = $label;
                }
            } else {
                if (!$this->isValidField($name, $fieldDefs, $viewType, $viewClient)) {
                    continue;
                }
                $fields[$name] = array();
            }
        }
        $fields['$owner'] = array(
            'predefined_filter' => true,
            'vname' => 'LBL_CURRENT_USER_FILTER',
        );
        $fields['$favorite'] = array(
            'predefined_filter' => true,
            'vname' => 'LBL_FAVORITES_FILTER',
        );
        $sidecarViewDefs = array(
            'default_filter' => 'all_records',
        );
        $sidecarViewDefs['fields'] = $fields;
        return $sidecarViewDefs;
    }
    /**
     * Determines if a field exists on the bean.
     * @param array $dbFields    : the db fields
     * @param array $fieldDefs   : the defintion of the field
     * @param string $viewType   : the type of the view
     * @param string $viewClient : the name of the client
     * @return bool
     */
    public function filterDbField($dbFields, $fieldDefs, $viewType, $viewClient)
    {
        $filterArray = array();
        foreach ($dbFields as $field) {
            if ($this->isValidField($field, $fieldDefs, $viewType, $viewClient) == true) {
                $filterArray[] = $field;
            }
        }
        return array('dbFields' => $filterArray);
    }
    /**
     * Determines if a field exists on the bean.
     * @param string $field      : the name of the field
     * @param array $fieldDefs   : the defintion of the field
     * @param string $viewType   : the type of the view
     * @param string $viewClient : the name of the client
     * @return bool
     */
    public function isValidField($field, $fieldDefs, $viewType, $viewClient)
    {
        $field = strtolower($field);
        if (empty($fieldDefs)) {
            // no idea where it came from, let it pass.no reason to remove fields that may be necessary
            return true;
        }
        if (empty($fieldDefs[$field])) {
            return false;
        }
        return AbstractMetaDataParser::validField($fieldDefs[$field], $viewType, $viewClient);
    }
    /**
     * Load search fields defs from SearchFields.php.
     * @param string $moduleName: name of the module
     * @return array
     */
    protected function loadSearchFields($moduleName)
    {
        $filename = SugarAutoLoader::existingCustomOne("modules/{$moduleName}/metadata/SearchFields.php");
        if (!$filename) {
            // try template now
            $sm = StudioModuleFactory::getStudioModule($moduleName);
            $moduleType = $sm->getType();
            $filename = 'include/SugarObjects/templates/' . $moduleType . '/metadata/SearchFields.php';
            if (!file_exists($filename)) {
                // OK, I give up, no way I can find it, let's use basic ones
                $filename = 'include/SugarObjects/templates/basic/metadata/SearchFields.php';
            }
        }
        $searchFields = array();
        include $filename;
        return (isset($searchFields[$moduleName]) ? $searchFields[$moduleName] : array());
    }

}
