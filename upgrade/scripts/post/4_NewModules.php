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
 * Update moduleList and moduleListSingular with new modules
 */
class SugarUpgradeNewModules extends UpgradeScript
{
    public $order = 4100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if(empty($this->upgrader->state['old_moduleList'])) {
            $this->log("Did not find old modules?");
            return;
        }
        include 'include/language/en_us.lang.php';
        $en_strings = $app_list_strings;

        $newModules = array_diff($en_strings['moduleList'],  $this->upgrader->state['old_moduleList']);
        if(empty($newModules)) {
            return;
        }
        $this->log("New modules to add: ".var_export($newModules, true));

        $keyList = array('moduleList', 'moduleListSingular');

        foreach (get_languages() as $langKey => $langName) {
            if(!file_exists("custom/include/language/$langKey.lang.php")) {
                // no custom file, nothing to do
                continue;
            }
            $app_list_strings = array();
            if (file_exists("include/language/$langKey.lang.php")) {
                include "include/language/$langKey.lang.php";
            }
            $orig_lang_strings = $app_list_strings;
            $all_strings = return_app_list_strings_language($langKey);
            $addModStrings = array();
            foreach($newModules as $modKey => $modName) {
                foreach($keyList as $appKey) {
                    if(empty($all_strings[$appKey][$modKey])) {
                        if(!empty($orig_lang_strings[$appKey][$modKey])) {
                            $addModStrings[$appKey][$modKey] = $orig_lang_strings[$appKey][$modKey];
                        } elseif (!empty($en_strings[$appKey][$modKey])) {
                            $addModStrings[$appKey][$modKey] = $en_strings[$appKey][$modKey];
                        } else {
                            $this->log("Weird, did not find name in $appKey for $modKey in $langKey");
                            $addModStrings[$appKey][$modKey] = $modKey;
                        }
                    }
                }
            }
            if(!empty($addModStrings)) {
                $this->updateCustomFile($langKey, $addModStrings);
            }
        }
    }

    /**
     * Update custom language file
     * @param unknown $lang Language
     * @param unknown $data Updated data
     */
    protected function updateCustomFile($lang, $data)
    {
        if(empty($data)) {
            return;
        }
        $file_data = trim(file_get_contents("custom/include/language/$lang.lang.php"));
        if(substr($file_data, -2) == "?>") {
            // strip closing tag
            $file_data = substr($file_data, 0, -2);
        }
        $file_data .= "\n/* This file was modified by Sugar Upgrade */\n";
        foreach($data as $key => $array) {
            foreach($array as $akey => $aval) {
                $file_data .= "\$app_list_strings['$key']['$akey'] = ".var_export($aval, true).";\n";
            }
        }

        $this->putFile("custom/include/language/$lang.lang.php", $file_data);
        $this->log("Updated custom/include/language/$lang.lang.php");
    }
}
