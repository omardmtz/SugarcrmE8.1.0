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

require_once 'modules/ModuleBuilder/parsers/constants.php';

class StudioModule
{
    /**
     * modules which are not supported by mobile app
     * @var array
     */
    static $mobileNotSupportedModules = array(
        'Bugs', // Bug Tracker
        'Campaigns',
        'Contracts',
        'KBContents', // Knowledge Base
        'ProductTemplates', // Product Catalog
        'Prospects', // Targets
        'pmse_Business_Rules', // Process Business Rules
        'pmse_Project', // Process Definitions
        'pmse_Emails_Templates', // Process Emails Templates
        'pmse_Inbox', // Processes
    );

    /**
     * BWC modules that do not have a quick create layout
     * @var array
     */
    static $quickCreateNotSupportedModules = array(
        'projecttask',
        'campaigns',
        'quotes',
        'producttemplates'
    );

    public $name;
    private $popups = array();
    public $module;
    public $fields;
    public $seed;

    /**
     * Backwards compatibility check, set here in the event that a bean is not
     * found for the requested module
     *
     * @var bool
     */
    public $bwc = false;

    /**
     * The indicator to use in the tree and menus to indicate a backward compatible
     * module
     *
     * @var string
     */
    public static $bwcIndicator = '*';

    /**
     * Class constructor
     * 
     * @param string $module The name of the module to base this object on
     */
    public function __construct($module, $seed = null)
    {
        $moduleList = $GLOBALS['app_list_strings']['moduleList'];
        if (empty($moduleList) && !is_array($moduleList)) {
            $moduleList = array();
        }

        $moduleNames = array_change_key_case($moduleList);
        $this->name = isset($moduleNames[strtolower($module)]) ? $moduleNames[strtolower($module)] : strtolower($module);
        $this->module = $module;
        if (!$seed) {
            $this->seed = BeanFactory::newBean($this->module);
        } else {
            $this->seed = $seed;
        }
        if ($this->seed) {
            $this->fields = $this->seed->field_defs;
        }

        // Set BWC since this is needed for sources
        $this->bwc = isModuleBWC($module);

        $this->setSources();
    }

    /**
     * Sets the viewdef file sources for use in studio
     */
    protected function setSources()
    {
        // Backward Compatible modules need the old way of doing things
        if ($this->bwc) {
            // Sources can be used to override the file name mapping for a specific
            // view or the parser for a view.
            $this->sources = array(
                array(
                    'name'  => translate('LBL_EDITVIEW'),
                    'type'  => MB_EDITVIEW,
                    'image' => 'EditView',
                    'path'  => "modules/{$this->module}/metadata/editviewdefs.php",
                ),
                array(
                    'name'  => translate('LBL_DETAILVIEW'),
                    'type'  => MB_DETAILVIEW,
                    'image' => 'DetailView',
                    'path'  => "modules/{$this->module}/metadata/detailviewdefs.php",
                ),
                array(
                    'name'  => translate('LBL_LISTVIEW'),
                    'type'  => MB_LISTVIEW,
                    'image' => 'ListView',
                    'path'  => "modules/{$this->module}/metadata/listviewdefs.php",
                ),
            );
             // Some modules should not have a QuickCreate form at all, so do not add them to the list
             if (!in_array(strtolower($this->module), self::$quickCreateNotSupportedModules)) {
                 $this->sources[] = array(
                     'name' => translate('LBL_QUICKCREATE'),
                     'type' => MB_QUICKCREATE,
                     'image' => 'QuickCreate',
                     'path'  => "modules/{$this->module}/metadata/quickcreatedefs.php",
                 );
             }
        } else {
            $this->sources = array(
                array(
                    'name'  => translate('LBL_RECORDVIEW'),
                    'type'  => MB_RECORDVIEW,
                    'image' => 'RecordView',
                    'path'  => "modules/{$this->module}/clients/base/views/record/record.php",
                ),
                array(
                    'name'  => translate('LBL_LISTVIEW'),
                    'type'  => MB_LISTVIEW,
                    'image' => 'ListView',
                    'path'  => "modules/{$this->module}/clients/base/views/list/list.php",
                ),
            );
        }
    }

    /**
     * Gets the name of this module. Some modules have naming inconsistencies 
     * such as Bug Tracker and Bugs which causes warnings in Relationships
     * Added to resolve bug #20257
     * 
     * @return string
     */
    public function getModuleName()
    {
        $modules_with_odd_names = array(
            'Bug Tracker'=>'Bugs'
        );
        
        if (isset($modules_with_odd_names[$this->name])) {
            return $modules_with_odd_names[$this->name];
        }

        return $this->name;
    }

    /**
     * Attempt to determine the type of a module, for example 'basic' or 'company'
     * These types are defined by the SugarObject Templates in /include/SugarObjects/templates
     * Custom modules extend one of these standard SugarObject types, so the type can be determined from their parent
     * Standard module types can be determined simply from the module name - 'bugs' for example is of type 'issue'
     * If all else fails, fall back on type 'basic'...
     * 
     * @return string Module's type
     */
    public function getType()
    {
        // first, get a list of a possible parent types
        $templates = array();
        $d = dir('include/SugarObjects/templates');
        while ($filename = $d->read()) {
            if (substr($filename, 0, 1) != '.') {
                $templates[strtolower($filename)] = strtolower($filename);
            }
        }

        // If a custom module, then its type is determined by the parent SugarObject that it extends
        if (!$this->seed)
        {
            $seed = BeanFactory::newBean($this->module);
        } else {
            $seed = $this->seed;
        }
        if (empty($seed)) {
            //If there is no bean at all for this module, use the basic template for base files
            return "basic";
        }
        $type = get_class($seed);
        do {
            $type = get_parent_class($type);
        } while (!in_array(strtolower($type), $templates) && $type != 'SugarBean');

        if ($type != 'SugarBean') {
            return strtolower($type);
        }

        // If a standard module then just look up its type - type is implicit 
        // for standard modules. Perhaps one day we will make it explicit, just 
        // as we have done for custom modules...
        $types = array(
            'Accounts' => 'company' ,
            'Bugs' => 'issue' ,
            'Cases' => 'issue' ,
            'Contacts' => 'person' ,
            'Documents' => 'file' ,
            'Leads' => 'person' ,
            'Opportunities' => 'sale'
        );
        if (isset($types[$this->module])) {
            return $types[$this->module];
        }

        return "basic";
    }

    /**
     * Return the fields for this module as sourced from the SugarBean
     * 
     * @return Array of fields
     */

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Gets all nodes for this module for use in rendering studio
     * 
     * @return array
     */
    public function getNodes()
    {
        $bwc = $this->bwc ? ' ' . self::$bwcIndicator : '';

        return array(
            'name' => $this->name . $bwc,
            'module' => $this->module,
            'type' => 'StudioModule',
            'action' => "module=ModuleBuilder&action=wizard&view_module={$this->module}",
            'children' => $this->getModule(),
            'bwc' => $this->bwc,
        );
    }

    /**
     * Gets specific nodes and actions related to this module
     * 
     * @return array
     */
    public function getModule()
    {
        $sources = array(
            translate('LBL_LABELS') => array(
                'action' => "module=ModuleBuilder&action=editLabels&view_module={$this->module}",
                'imageTitle' => 'Labels', 
                'help' => 'labelsBtn',
            ),
            translate('LBL_FIELDS') => array(
                'action' => "module=ModuleBuilder&action=modulefields&view_package=studio&view_module={$this->module}",
                'imageTitle' => 'Fields', 
                'help' => 'fieldsBtn',
            ),
            translate('LBL_RELATIONSHIPS') => array(
                'action' => "get_tpl=true&module=ModuleBuilder&action=relationships&view_module={$this->module}",
                'imageTitle' => 'Relationships',
                'help' => 'relationshipsBtn',
            ),
            translate('LBL_LAYOUTS') => array(
                'children' => 'getLayouts', 
                'action' => "module=ModuleBuilder&action=wizard&view=layouts&view_module={$this->module}", 
                'imageTitle' => 'Layouts', 
                'help' => 'layoutsBtn',
            ),
            translate('LBL_SUBPANELS') => array(
                'children' => 'getSubpanels',
                'action' => "module=ModuleBuilder&action=wizard&view=subpanels&view_module={$this->module}",
                'imageTitle' => 'Subpanels',
                'help' => 'subpanelsBtn',
            ), 
        );
        if (self::isMobileLayoutsSupported($this->module)) {
            $sources[translate('LBL_WIRELESSLAYOUTS')] = array(
                'children' => 'getWirelessLayouts',
                'action' => "module=ModuleBuilder&action=wizard&view=wirelesslayouts&view_module={$this->module}",
                'imageTitle' => 'MobileLayouts',
                'help' => 'wirelesslayoutsBtn',
            );
        }
        $sources[translate('LBL_PORTAL_LAYOUTS')] = array(
            'children' => 'getPortal',
            'action' => "module=ModuleBuilder&action=wizard&portal=1&view_module={$this->module}",
            'imageTitle' => 'Portal',
            'help' => 'portalBtn',
        );

        $nodes = array();
        foreach ($sources as $source => $def) {
            $nodes[$source] = $def;
            $nodes[$source]['name'] = translate($source);
            if (isset($def['children'])) {
                $childNodes = $this->{$def['children']}();
                if (!empty($childNodes)) {
                    $nodes[$source]['type'] = 'Folder';
                    $nodes[$source]['children'] = $childNodes;
                } else {
                    unset($nodes[$source]);
                }
            }
        }

        return $nodes ;
    }

    /**
     * Gets views for this module
     * 
     * @return array
     */
    public function getViews()
    {
        $views = array () ;

        foreach ($this->sources as $def) {
            // Remove path from the defs as it's not needed in the views array
            $path = $def['path'];
            unset($def['path']);
            if (file_exists($path) || file_exists("custom/$path")) {
                $views[basename($path, '.php')] = $def;
            }
        }

        return $views;
    }

    /**
     * Gets layouts for this module
     * 
     * @return array
     */
    public function getLayouts()
    {
        $views = $this->getViews();

        $layouts = array();
        foreach ($views as $def) {
            $view = !empty($def['view']) ? $def['view'] : $def['type'];
            $layouts[$def['name']] = array(
                'name' => $def['name'],
                'action' => "module=ModuleBuilder&action=editLayout&view={$view}&view_module={$this->module}",
                'imageTitle' => $def['image'],
                'help' => "viewBtn{$def['type']}",
                'size' => '48',
            );
        }

        //For popup tree node
        $popups = array();
        $popups[] = array(
            'name' => translate('LBL_POPUPLISTVIEW'),
            'type' => 'popuplistview',
            'action' => 'module=ModuleBuilder&action=editLayout&view=selection-list&view_module=' . $this->module,
        );
        $popups[] = array(
            'name' => translate('LBL_POPUPSEARCH'),
            'type' => 'popupsearch',
            'action' => 'module=ModuleBuilder&action=editLayout&view=popupsearch&view_module=' . $this->module,
        );
        $layouts[translate('LBL_POPUP')] = array(
            'name' => translate('LBL_POPUP'),
            'type' => 'Folder',
            'children' => $popups,
            'imageTitle' => 'Popup',
            'action' => 'module=ModuleBuilder&action=wizard&view=popup&view_module=' . $this->module,
        );

        $nodes = $this->getSearch();
        if (!empty($nodes)) {
            $layouts[translate('LBL_SEARCH')] = array(
                'name' => translate('LBL_SEARCH'),
                'type' => 'Folder',
                'children' => $nodes,
                'action' => "module=ModuleBuilder&action=wizard&view=search&view_module={$this->module}",
                'imageTitle' => 'BasicSearch',
                'help' => 'searchBtn',
                'size' => '48',
            );
        }

        return $layouts ;

    }

    /**
     * Gets wiresless layouts for this module
     * 
     * @return array
     */
    public function getWirelessLayouts()
    {
        $layouts[translate('LBL_WIRELESSEDITVIEW')] = array(
            'name' => translate('LBL_WIRELESSEDITVIEW'),
            'type' => MB_WIRELESSEDITVIEW,
            'action' => "module=ModuleBuilder&action=editLayout&view=".MB_WIRELESSEDITVIEW."&view_module={$this->module}",
            'imageTitle' => 'EditView',
            'help' => "viewBtn".MB_WIRELESSEDITVIEW,
            'size' => '48',
        );
        $layouts[translate('LBL_WIRELESSDETAILVIEW')] = array(
            'name' => translate('LBL_WIRELESSDETAILVIEW'),
            'type' => MB_WIRELESSDETAILVIEW,
            'action' => "module=ModuleBuilder&action=editLayout&view=".MB_WIRELESSDETAILVIEW."&view_module={$this->module}",
            'imageTitle' => 'DetailView',
            'help' => "viewBtn".MB_WIRELESSDETAILVIEW,
            'size' => '48',
        );
        $layouts[translate('LBL_WIRELESSLISTVIEW')] = array(
            'name' => translate('LBL_WIRELESSLISTVIEW'),
            'type' => MB_WIRELESSLISTVIEW,
            'action' => "module=ModuleBuilder&action=editLayout&view=".MB_WIRELESSLISTVIEW."&view_module={$this->module}",
            'imageTitle' => 'ListView',
            'help' => "viewBtn".MB_WIRELESSLISTVIEW,
            'size' => '48',
        );

        return $layouts ;
    }

    /**
     * Gets appropriate search layouts for the module
     * 
     * @return array
     */
    public function getSearch()
    {
        $nodes = array();
        $options =  $this->bwc ? array(MB_BASICSEARCH => 'LBL_BASIC_SEARCH', MB_ADVANCEDSEARCH => 'LBL_ADVANCED_SEARCH') : array(MB_BASICSEARCH => 'LBL_FILTER_SEARCH',);
        foreach ($options as $view => $label) {
            try {
                $title = translate($label);
                if ($label == 'LBL_BASIC_SEARCH') {
                    $name = 'BasicSearch';
                } elseif ($label == 'LBL_ADVANCED_SEARCH') {
                    $name = 'AdvancedSearch';
                } elseif ($label == 'LBL_FILTER_SEARCH') {
                    $name = "FilterSearch";
                } else {
                    $name = str_replace(' ', '', $title);
                }
                $nodes[$title] = array(
                    'name' => $title, 
                    'action' => "module=ModuleBuilder&action=editLayout&view={$view}&view_module={$this->module}", 
                    'imageTitle' => $title, 
                    'imageName' => $name, 
                    'help' => "{$name}Btn", 
                    'size' => '48',
                );
            } catch (Exception $e) {
                $GLOBALS['log']->info('No search layout : '. $e->getMessage());
            }
        }

        return $nodes;
    }

    /**
     * Return an object containing all the relationships participated in by this
     * module
     * 
     * @return AbstractRelationships Set of relationships
     */
    public function getRelationships($relationshipName = "")
    {
        return new DeployedRelationships($this->module, $relationshipName);
    }

    /**
     * Gets the collection of portal layouts for this module, if they exist
     * 
     * @return array
     */
    public function getPortal()
    {
        $nodes = array();
        foreach ($this->sources as $file => $def) {
            $dir = str_replace('viewdefs.php', '', $file);
            $file = str_replace('viewdefs', '', $file);
            if (file_exists("modules/{$this->module}/clients/portal/views/$dir/$file")) {
                $nodes[] = array(
                   'name' => $def['name'],
                   'action' => 'module=ModuleBuilder&action=editPortal&view=' . ucfirst($def['type']) . '&view_module=' . $this->module,
                );
            }
        }

        return $nodes;
    }

    /**
     * Gets a list of subpanels used by the current module
     * 
     * @return array
     */
    public function getSubpanels()
    {
        if(!empty($GLOBALS['current_user']) && empty($GLOBALS['modListHeader'])) {
            $GLOBALS['modListHeader'] = query_module_access_list($GLOBALS['current_user']);
        }


        $nodes = array();

        $GLOBALS['log']->debug("StudioModule->getSubpanels(): getting subpanels for " . $this->module);

        // counter to add a unique key to assoc array below
        $ct = 0;
        foreach (SubPanel::getModuleSubpanels($this->module) as $name => $label) {
            if ($name == 'users') {
                continue;
            }
            $subname = sugar_ucfirst((!empty($label)) ? translate($label, $this->module) : $name);
            $action = "module=ModuleBuilder&action=editLayout&view=ListView&view_module={$this->module}&subpanel={$name}&subpanelLabel=" . urlencode($subname);

            //  bug47452 - adding a unique number to the $nodes[ key ] so if you have 2+ panels
            //  with the same subname they will not cancel each other out
            $nodes[$subname . $ct++] = array(
                'name' => $name,
                'label' => $subname,
                'action' =>  $action,
                'imageTitle' => $subname,
                'imageName' => 'icon_' . ucfirst($name) . '_32',
                'altImageName' => 'Subpanels',
                'size' => '48',
            );
        }

        return $nodes;
    }

    /**
     * Sets and gets a list of subpanels provided to other modules
     *
     * @return array
     */
    public function getProvidedSubpanels()
    {
        if (isModuleBWC($this->module)) {
            return $this->getBWCProvidedSubpanels();
        }

        return $this->getSidecarProvidedSubpanels();
    }

    public function getBWCProvidedSubpanels()
    {
        $this->providedSubpanels = array();
        $subpanelDir = 'modules/' . $this->module . '/metadata/subpanels/';
        foreach (array($subpanelDir, "custom/$subpanelDir") as $dir) {
            if (is_dir($dir)) {
                foreach (scandir($dir) as $fileName) {
                    // sanity check to confirm that this is a usable subpanel...
                    if (substr($fileName, 0, 1) != '.'
                        && substr(strtolower($fileName), -4) == ".php"
                        && AbstractRelationships::validSubpanel("$dir/$fileName")
                    ) {
                        $subname = str_replace('.php', '', $fileName);
                        $this->providedSubpanels[$subname] = $subname;
                    }
                }
            }
        }

        return $this->providedSubpanels;
    }


    public function getSidecarProvidedSubpanels()
    {
        $this->providedSubpanels = array();
        $subpanelDir = 'modules/' . $this->module . '/clients/base/views/';
        foreach (array($subpanelDir, "custom/$subpanelDir") as $dir) {
            if (is_dir($dir)) {
                foreach (scandir($dir) as $fileName) {
                    // sanity check to confirm that this is a usable subpanel...
                    if (stristr($fileName, 'subpanel-')) {
                        $subpanelName = str_replace('subpanel-', '', $fileName);
                        if ($subpanelName != 'list') {
                            $subpanelName = str_replace('-', ' ', $subpanelName);
                            $subpanelName = ucwords($subpanelName);
                            $subpanelName = str_replace(' ', '', $subpanelName);
                        } else {
                            $subpanelName = 'default';
                        }
                        $this->providedSubpanels[$subpanelName] = $subpanelName;
                    }
                }
            }
        }

        return $this->providedSubpanels;
    }

    /**
     * Gets modules and subpanels related the given one
     * 
     * @param string $sourceModule The name of the module
     * @return array
     */
    public function getModulesWithSubpanels($sourceModule)
    {
        global $moduleList, $beanFiles, $beanList, $module;

        //use tab controller function to get module list with named keys
        $modules_to_check = TabController::get_key_array($moduleList);

        //change case to match subpanel processing later on
        $modules_to_check = array_change_key_case($modules_to_check);

        $spd_arr = array();
        //iterate through modules and build subpanel array
        foreach ($modules_to_check as $mod_name) {
            $bean = BeanFactory::newBean($mod_name);
            if(empty($bean)) continue;

            //create new subpanel definition instance and get list of tabs
            $spd = new SubPanelDefinitions($bean);
            if (isset($spd->layout_defs['subpanel_setup'])) {
                $subpanels = $this->getModuleSubpanels($spd->layout_defs['subpanel_setup'], $sourceModule);
                if (count($subpanels) > 0) {
                    $spd_arr[$mod_name] = $subpanels;
                }
            }
        }

        return  $spd_arr;
    }

    /**
     * Returns array of subpanel names related to the given module
     * @param array $defs the definition of subpanel layout.
     * @param string $sourceModule the name of the source module in subpanel
     * @return array
     */
    protected function getModuleSubpanels(array $defs, $sourceModule)
    {
        $subpanels = array();
        foreach ($defs as $name => $def) {
            //Example:
            //subpanel link name: accounts_meetings_1
            //related module: Meetings
            //source module: Accounts (should be equal to $sourceModule)
            if (isset($def['module']) && $def['module'] == $sourceModule) {
                $subpanels[] = $name;
            }
        }
        return $subpanels;
    }

    /**
     * Removes a field from the layouts that it is on
     * 
     * @param string $fieldName The name of the field to remove
     */
    public function removeFieldFromLayouts($fieldName)
    {
        $GLOBALS ['log']->info(get_class($this) . "->removeFieldFromLayouts($fieldName)");
        $sources = $this->getViewMetadataSources();
        $sources[] = array('type'  => MB_BASICSEARCH);
        $sources[] = array('type'  => MB_ADVANCEDSEARCH);
        $sources[] = array('type'  => MB_POPUPSEARCH);
        $sources = array_merge($sources, $this->getWirelessLayouts());
        $sources = array_merge($sources, $this->getPortalLayoutSources());

        $GLOBALS['log']->debug(print_r($sources, true));

        $roles = MBHelper::getRoles();
        foreach ($sources as $name => $defs) {
            $this->removeFieldFromLayout($this->module, $defs['type'], null, $fieldName);
            foreach ($roles as $role) {
                $this->removeFieldFromLayout($this->module, $defs['type'], null, $fieldName, array(
                    'role' => $role->id,
                ));
            }
        }

        //Remove the field from subpanels
        $data = $this->getModulesWithSubpanels($this->module);
        foreach ($data as $module => $subpanels) {
            foreach ($subpanels as $subpanel) {
                $this->removeFieldFromLayout($module, MB_LISTVIEW, $subpanel, $fieldName);
            }
        }
    }

    /**
     * Removes a field from layout
     *
     * @param string $module Module name
     * @param string $layout Layout type
     * @param string $subpanelName Subpanel name
     * @param string $fieldName Field name
     * @param array $params Layout parameters
     */
    protected function removeFieldFromLayout($module, $layout, $subpanelName, $fieldName, array $params = array())
    {
        // If this module type doesn't support a given metadata type, we will
        // get an exception from getParser()
        try {
            $parser = ParserFactory::getParser($layout, $module, null, $subpanelName, null, $params);
            if ($parser && method_exists($parser, 'removeField') && $parser->removeField($fieldName)) {
                // don't populate from $_REQUEST, just save as is...
                $parser->handleSave(false);
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Gets a list of source metadata view types. Used in resetting a module and
     * for the field removal process.
     *
     * @return array
     */
    public function getViewMetadataSources()
    {
        $sources = $this->getViews();
        $sources[] = array('type'  => MB_BASICSEARCH);
        $sources[] = array('type'  => MB_ADVANCEDSEARCH);
        $sources[] = array('type'  => MB_POPUPLIST);
        $sources = array_merge($sources, $this->getWirelessLayouts());
        $sources = array_merge($sources, $this->getPortalLayoutSources());
        return $sources;
    }

    /**
     * Gets the type for a view
     * 
     * @param string $view The view to get the type from
     * @return string
     */
    public function getViewType($view)
    {
        foreach ($this->sources as $file => $def) {
            if (!empty($def['view']) && $def['view'] == $view && !empty($def['type'])) {
                return $def['type'];
            }
        }

        return $view;
    }

    /**
     * Gets a simple array of source layout types for field deletion
     *
     * @return array
     */
    public function getPortalLayoutSources()
    {
        return array(
            array('type' => MB_PORTALRECORDVIEW),
            array('type' => MB_PORTALLISTVIEW),
        );
    }

    public static function isMobileLayoutsSupported ($module) {
        return !in_array($module, self::$mobileNotSupportedModules);
    }

    /**
     * Removes all custom fields created in Studio
     *
     * @return array Names of removed fields
     */
    public function removeCustomFields()
    {
        $seed = BeanFactory::newBean($this->module);
        $df = new DynamicField($this->module) ;
        $df->setup($seed) ;

        $module = StudioModuleFactory::getStudioModule($this->module) ;
        $removedFields = array();
        foreach ($seed->field_defs as $def) {
            if (isset($def['custom_module']) && $def['custom_module'] === $this->module) {
                $field = $df->getFieldWidget($this->module, $def['name']);
                // the field may have already been deleted
                if ($field) {
                    $field->delete($df);
                }

                $module->removeFieldFromLayouts($def['name']);
                $removedFields[] = $def['name'];
            }
        }

        return $removedFields;
    }
}
