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

 // $Id: DropDownHelper.php 19013 2007-01-04 02:15:22Z majed $
require_once('modules/Administration/Common.php');
class DropDownHelper
{
    public $modules = array();
    public function getDropDownModules()
    {
        $dir = dir('modules');
        while ($entry = $dir->read()) {
            if (file_exists('modules/'. $entry . '/EditView.php')) {
                $this->scanForDropDowns('modules/'. $entry . '/EditView.php', $entry);
            }
        }

    }

    public function scanForDropDowns($filepath, $module)
    {
        $contents = file_get_contents($filepath);
        $matches = array();
        preg_match_all('/app_list_strings\s*\[\s*[\'\"]([^\]]*)[\'\"]\s*]/', $contents, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $this->modules[$module][$match] = $match;
            }
        }
    }

    /**
     * Allow for certain dropdowns to be filtered when edited by pre 5.0 studio (eg. Rename Tabs)
     *
     * @param string name
     * @param array dropdown
     * @return array Filtered dropdown list
     */
    public function filterDropDown($name,$dropdown)
    {
        $results = array();
        switch ($name) {
            //When renaming tabs ensure that the modList dropdown is filtered properly.
            case 'moduleList':
                $hiddenModList = array_flip($GLOBALS['modInvisList']);
                $moduleList = array_flip($GLOBALS['moduleList']);

                foreach ($dropdown as $k => $v) {
                    if (isset($moduleList[$k])) {
                        $results[$k] = $v;
                    }
                }
                break;
            default: //By default perform no filtering
                $results = $dropdown;
        }

        return $results;
    }

    /**
     * Takes in the request params from a save request and processes
     * them for the save.
     *
     * @param array $params Request parameters
     */
    public static function saveDropDown($params)
    {
        global $locale;
        $count = 0;
        $dropdown = array();
        $dropdown_name = $params['dropdown_name'];

        if (!empty($params['dropdown_lang'])) {
            $selected_lang = $params['dropdown_lang'];
        } else {
            $selected_lang = $locale->getAuthenticatedUserLanguage();
        }

        $my_list_strings = return_app_list_strings_language($selected_lang);
        while (isset($params['slot_' . $count])) {
            $index = $params['slot_' . $count];
            $key = (isset($params['key_' . $index])) ? SugarCleaner::stripTags($params['key_' . $index]) : 'BLANK';
            $value = (isset($params['value_' . $index])) ? SugarCleaner::stripTags($params['value_' . $index]) : '';
            if ($key == 'BLANK') {
                $key = '';
            }
            
            $key = trim($key);
            $value = trim($value);
            if (empty($params['delete_' . $index])) {
                $dropdown[$key] = $value;
            }
            $count++;
        }

        return save_custom_dropdown_strings(array($dropdown_name => $dropdown));
    }
}
