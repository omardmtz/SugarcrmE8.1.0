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
 * Copy custom data from old KBDocument module to new KBContent module..
 */
class SugarUpgradeRenameCustom extends UpgradeScript
{
    /**
     * {@inheritDoc}
     */
    public $order = 1002;

    /**
     * {@inheritDoc}
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritDoc}
     */
    public $version = '7.5';

    /**
     * Map for old fields to new fields.
     * @var array
     */
    protected $fieldsViewMap = array(
        'kbdocument_name' => array(
            'name' => 'name',
            'label' => 'LBL_NAME',
        ),
        'kbdoc_approver_name' => array(
            'name' => 'kbsapprover_name',
            'label' => 'LBL_KBSAPPROVER',
        ),
        'is_external_article' => array(
            'name' => 'is_external',
            'label' => 'LBL_IS_EXTERNAL',
        ),
        'status_id' => array(
            'name' => 'status',
            'label' => 'LBL_STATUS',
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
        ),
        'exp_date' => array(
            'name' => 'exp_date',
            'label' => 'LBL_EXP_DATE',
        ),
        'body' => array(
            'name' => 'kbdocument_body',
            'label' => 'LBL_TEXT_BODY',
        ),
        'kbdocument_revision_number' => array(
            'name' => 'revision',
            'label' => 'LBL_REVISION',
        ),
        'active_date' => array(
            'name' => 'active_date',
            'label' => 'LBL_PUBLISH_DATE',
        ),
        'views_number' => array(
            'name' => 'viewcount',
            'label' => 'LBL_VIEWED_COUNT',
        ),
        'case_name' => array(
            'name' => 'kbscase_name',
            'label' => 'LBL_KBSCASE',
        ),

    );

    /**
     * Array of mod_strings for new module.
     * @var array
     */
    protected $language = array();

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7.0', '<')) {

            sugar_mkdir('custom/modules/KBContents/language', null, true);
            foreach (glob('custom/modules/KBDocuments/language/*.php') as $file) {
                $dest = str_replace('/KBDocuments/', '/KBContents/', $file);
                copy($file, $dest);
                $this->upgrader->fileToDelete(array($file), $this);
            }

            sugar_mkdir('custom/Extension/modules/KBContents/Ext/Language', null, true);
            foreach (glob('custom/Extension/modules/KBDocuments/Ext/Language/*.php') as $file) {
                $dest = str_replace('/KBDocuments/', '/KBContents/', $file);
                copy($file, $dest);
                $this->upgrader->fileToDelete(array($file), $this);
            }

            foreach (glob('custom/include/language/*.php') as $file) {
                $this->updateLanguageFile($file);
            }

            foreach (glob('custom/Extension/application/Ext/Language/*.php') as $file) {
                $this->updateLanguageFile($file);
            }

            $langs = SugarConfig::getInstance()->get('languages');
            $lang = SugarConfig::getInstance()->get('default_language');
            $mi = new ModuleInstaller();
            $mi->rebuild_languages($langs, array('KBContents'));
            $this->language = LanguageManager::loadModuleLanguage('KBContents', $lang);

            if (file_exists('custom/modules/KBDocuments/metadata/listviewdefs.php')) {
                $this->copyListView('custom/modules/KBDocuments/metadata/listviewdefs.php');
            }

            if (file_exists('custom/modules/KBDocuments/metadata/popupdefs.php')) {
                $this->copyPopupView('custom/modules/KBDocuments/metadata/popupdefs.php');
            }

            if (file_exists('custom/modules/KBDocuments/clients/base/views/list/list.php')) {
                sugar_mkdir('custom/modules/KBContents/clients/base/views/list', null, true);
                $this->copySViews(
                    'custom/modules/KBDocuments/clients/base/views/list/list.php',
                    'custom/modules/KBContents/clients/base/views/list/list.php'
                );
            }

            if (file_exists('custom/modules/KBDocuments/clients/base/views/selection-list/selection-list.php')) {
                sugar_mkdir('custom/modules/KBContents/clients/base/views/selection-list', null, true);
                $this->copySViews(
                    'custom/modules/KBDocuments/clients/base/views/selection-list/selection-list.php',
                    'custom/modules/KBContents/clients/base/views/selection-list/selection-list.php'
                );
            }

            sugar_mkdir('custom/Extension/modules/KBContents/Ext/Vardefs', null, true);
            foreach (glob('custom/Extension/modules/KBDocuments/Ext/Vardefs/*.php') as $file) {
                $this->copyDefs($file, str_replace('/KBDocuments/', '/KBContents/', $file));
            }

            $query = "UPDATE fields_meta_data set custom_module = 'KBContents' where custom_module = 'KBDocuments'";
            $this->db->query($query);
        }
    }

    /**
     * Copy definition for list view.
     * @param String $file File with view definition.
     */
    protected function copyListView($file)
    {
        $listViewDefs = array();
        sugar_mkdir('custom/modules/KBContents/metadata', null, true);
        include $file;
        $listViewDefs['KBContents'] = $this->checkDefs($listViewDefs['KBDocuments']);
        write_array_to_file(
            "listViewDefs['KBContents']",
            $listViewDefs['KBContents'],
            'custom/modules/KBContents/metadata/listviewdefs.php'
        );
    }

    /**
     * Copy definition for popup view.
     * @param String $file File with view definition.
     */
    protected function copyPopupView($file)
    {
        $popupMeta = array();
        sugar_mkdir('custom/modules/KBContents/metadata', null, true);
        include $file;
        $popupMeta['moduleMain'] = 'KBContents';
        $popupMeta['varName'] = 'KBContents';
        $popupMeta['orderBy'] = 'kbcontents.name';
        $popupMeta['whereClauses'] = array('name' => 'kbcontents.name');
        $popupMeta['listviewdefs'] = $this->checkDefs($popupMeta['listviewdefs']);
        if (!empty($popupMeta['searchdefs'])) {
            $popupMeta['searchdefs'] = $this->checkDefs($popupMeta['searchdefs']);
        }
        if (!empty($popupMeta['searchInputs'])) {
            $popupMeta['searchInputs'] = $this->checkDefs($popupMeta['searchInputs']);
        }
        write_array_to_file(
            "popupMeta",
            $popupMeta,
            'custom/modules/KBContents/metadata/popupdefs.php'
        );
    }

    /**
     * @param String $file File with view definition.
     * @param String $dest New file destination.
     */
    protected function copySViews($file, $dest)
    {
        $viewdefs = array();
        $cname = str_replace('custom/', '', $dest);
        list($modules, $module_name, $clients, $platform, $views, $viewname) = explode('/', $cname);
        include $file;
        foreach ($viewdefs['KBDocuments'][$platform]['view'][$viewname]['panels'] as &$panel) {
            $panel['fields'] = $this->checkDefs($panel['fields']);
        }
        write_array_to_file(
            "viewdefs['KBContents']",
            $viewdefs['KBDocuments'],
            $dest
        );
        //need to merge
        $viewdefs['KBContents'] = $viewdefs['KBDocuments'];
        unset($viewdefs['KBDocuments']);
        $this->upgrader->state['for_merge'][$cname] = $viewdefs;
        $cname = str_replace('custom/', '', $file);
        if (!empty($this->upgrader->state['for_merge'][$cname])) {
            unset($this->upgrader->state['for_merge'][$cname]);
        }
    }

    /**
     * Copy fields definitions.
     * @param String $file File with variable definition.
     * @param String $dest New destination.
     */
    protected function copyDefs($file, $dest)
    {
        $content = file_get_contents($file);
        $content = str_replace(
            array("'KBDocument'", '"KBDocument"'),
            array("'KBContent'", '"KBContent"'),
            $content
        );
        file_put_contents($dest, $content);
    }

    /**
     * Replace old field definitions with new one.
     * @param array $defs View definition.
     * @return array
     */
    protected function checkDefs($defs)
    {
        $result = array();
        foreach ($defs as $key => $def) {
            $name = strtolower(!empty($def['name']) ? $def['name'] : $key);
            if (!empty($this->fieldsViewMap[$name])) {
                if (!empty($def['label']) && !empty($this->language[$def['label']])) {
                    $this->fieldsViewMap[$name]['label'] = $def['label'];
                }
                $def = array_merge($def, $this->fieldsViewMap[$name]);
                $name = strtolower($this->fieldsViewMap[$name]['name']);
            }
            $key = strtoupper($name);
            $result[$key] = $def;
        }
        return $result;
    }

    /**
     * Move `app_list_strings` for KB in custom language file.
     * @param $file
     */
    protected function updateLanguageFile($file)
    {
        global $app_list_strings;
        // backup global $app_list_strings
        $g_app_list_strings = $app_list_strings;
        $app_list_strings = array();
        $lang = explode('.', basename($file, '.php'));
        $lang = array_shift($lang);
        include $file;

        $mod_app_list_strings = false;
        $app_list_strings = $this->moveKBDocumentsToKBContents($app_list_strings, $mod_app_list_strings);
        $app_list_strings = $this->updateDropdown($app_list_strings, $lang, $mod_app_list_strings);
        if ($mod_app_list_strings === true) {
            $this->upgrader->backupFile($file);
            $this->log("Updating language file {$file} with new KB module");
            $out = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
            foreach (array_keys($app_list_strings) as $key) {
                $out .= override_value_to_string_recursive2('app_list_strings', $key, $app_list_strings[$key]);
            }
            file_put_contents($file, $out);
        }
        // restore global $app_list_strings
        $app_list_strings = $g_app_list_strings;

    }

    /**
     * Recursive move `app_list_strings` from KBDocuments to KBContents.
     * @param mixed $array Array to work with.
     * @param        $array_modified_flag set to true when $array is changed
     * @return array Updated array.
     */
    protected function moveKBDocumentsToKBContents($array, &$array_modified_flag)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->moveKBDocumentsToKBContents($value, $array_modified_flag);
                if ($key === 'KBDocuments') {
                    $array['KBContents'] = $array['KBDocuments'];
                    unset($array['KBDocuments']);
                    $array_modified_flag = true;
                }
            }
        }
        return $array;
    }

    /**
     * Remove customization for deleted dropdowns migrate labels for changed dropdowns.
     * @param mixed $app_list_strings Strings to work with.
     * @param string $lang Language to work with.
     * @param boolean $changed Flag indicates if there any changes in dropdowns.
     * @return mixed Updated `app_list_strings`.
     */
    protected function updateDropdown($app_list_strings, $lang, &$changed)
    {

        //Dropdowns to remove.
        $ddToDelete = array(
            'kbolddocument_status_dom',
            'kbolddocument_attachment_option_dom',
            'kbolddocument_viewing_frequency_dom',
            'kbolddocument_canned_search',
            'kbolddocument_date_filter_options',
        );
        //Dropdown to migrate labels.
        $ddMappings = array(
            'kbdocument_status_dom' => array(
                'Draft' => 'draft',
                'Expired' => 'expired',
                'In Review' => 'in-review',
                'Published' => 'published',
            )
        );

        foreach ($ddToDelete as $dd) {
            if (isset($app_list_strings[$dd])) {
                unset($app_list_strings[$dd]);
                $this->log("Dropdown {$dd} removed");
                $changed = true;
            }
        }

        foreach ($ddMappings as $dd => $map) {
            $arrays = array(
                &$app_list_strings,
            );
            if (!empty($this->upgrader->state['dropdowns_to_merge'][$lang]['custom'])) {
                $arrays[] = &$this->upgrader->state['dropdowns_to_merge'][$lang]['custom'];
            }
            foreach ($arrays as &$arr) {
                if (empty($arr[$dd])) {
                    continue;
                }
                $result = array();
                foreach ($arr[$dd] as $key => $value) {
                    if (!empty($map[$key])) {
                        $key = $map[$key];
                        $changed = true;
                    }
                    $result[$key] = $value;
                }
                $arr[$dd] = $result;
            }
            $this->log("Dropdown {$dd} updated for language {$lang}");
        }
        return $app_list_strings;
    }
}
