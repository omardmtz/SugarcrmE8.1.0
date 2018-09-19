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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

require_once 'modules/Administration/Common.php';

class ParserDropDown extends ModuleBuilderParser
{
    /**
     * Takes in the request params from a save request and processes
     * them for the save.
     *
     * @param $params $params
     * @param bool $finalize if false, the changes are not yet deployed.
     *  Useful when making multiple changes in a single request. finalize() should then be called externally.
     */
    public function saveDropDown($params, $finalize = true)
    {
        global $locale;

        $emptyMarker = translate('LBL_BLANK');

        if (!empty($_REQUEST['dropdown_lang'])) {
            $selected_lang = $_REQUEST['dropdown_lang'];
        } else {
            $selected_lang = $locale->getAuthenticatedUserLanguage();
        }

        $type = $_REQUEST['view_package'];
        $dropdown_name = $params['dropdown_name'];
        $json = getJSONobj();

        $list_value = str_replace('&quot;&quot;:&quot;&quot;', '&quot;__empty__&quot;:&quot;&quot;', $params['list_value']);
        //Bug 21362 ENT_QUOTES- convert single quotes to escaped single quotes.
        $temp = $json->decode(html_entity_decode(rawurldecode($list_value), ENT_QUOTES));
        $dropdown = array();
        // dropdown is received as an array of (name,value) pairs - now extract to name=>value format preserving order
        // we rely here on PHP to preserve the order of the received name=>value pairs - associative arrays in PHP are ordered
        if (is_array($temp)) {
            foreach ($temp as $item) {
                $key = SugarCleaner::stripTags(from_html($item[0]), false);
                $dropdown[$key] = empty($key) ? '' : SugarCleaner::stripTags(from_html($item[1]), false);
            }
        }
        if (array_key_exists($emptyMarker, $dropdown)) {
            $output=array();
            foreach ($dropdown as $key => $value) {
                if ($emptyMarker===$key) {
                    $output['']='';
                } else {
                    $output[$key]=$value;
                }
            }
            $dropdown=$output;
        }

        if ($type != 'studio') {
            $mb = new ModuleBuilder();
            $module = $mb->getPackageModule($params['view_package'], $params['view_module']);
            $this->synchMBDropDown($dropdown_name, $dropdown, $selected_lang, $module);
            //Can't use synch on selected lang as we want to overwrite values, not just keys
            $module->mblanguage->appListStrings[$selected_lang.'.lang.php'][$dropdown_name] = $dropdown;
            $module->mblanguage->save($module->key_name, false, true); // tyoung - key is required parameter as of
        } else {
            $contents = return_custom_app_list_strings_file_contents($selected_lang);
            $my_list_strings = return_app_list_strings_language($selected_lang);
            if ($selected_lang == $GLOBALS['current_language']){
               $GLOBALS['app_list_strings'][$dropdown_name] = $dropdown;
            }
            //write to contents
            $contents = str_replace("?>", '', $contents);
            if (empty($contents)) $contents = "<?php";

            // Skip saveExemptDropdowns on upgrades
            if (empty($params['skipSaveExemptDropdowns'])) {
                $dropdown = $this->saveExemptDropdowns($dropdown, $dropdown_name, $my_list_strings, $selected_lang);
            }

            //add new drop down to the bottom
            if (!empty($params['use_push'])) {
                //this is for handling moduleList and such where nothing should be deleted or anything but they can be renamed
                $app_list_strings = array();
                $filePath = $this->getExtensionFilePath($dropdown_name, $selected_lang);
                //Include the original extension to ensure any values sourced from it are kept.
                if (sugar_is_file($filePath)) {
                    include FileLoader::validateFilePath($filePath);
                }

                foreach ($dropdown as $key => $value) {
                    //only if the value has changed or does not exist do we want to add it this way
                    if (!isset($my_list_strings[$dropdown_name][$key])
                        || strcmp($my_list_strings[$dropdown_name][$key], $value) != 0) {
                        $app_list_strings[$dropdown_name][$key] = $value;
                    }
                }
                //Now that we have all the values, save the overrides to the extension
                if (!empty($app_list_strings[$dropdown_name])) {
                    $contents = "<?php\n //created: " . date('Y-m-d H:i:s') . "\n";
                    foreach($app_list_strings[$dropdown_name] as $key => $value) {
                        $contents .= "\n\$app_list_strings['$dropdown_name']['$key']=" . var_export_helper($value) . ";";
                    }
                    $this->saveContents($dropdown_name, $contents, $selected_lang);
                }
            } else {
                if (empty($params['skip_sync'])) {
                    // Now synch up the keys in other languages to ensure that removed/added
                    // Drop down values work properly under all langs.
                    // If skip_sync, we don't want to sync ALL languages
                    $this->synchDropDown($dropdown_name, $dropdown, $selected_lang);
                }

                $contents = $this->getExtensionContents($dropdown_name, $dropdown);
                $this->saveContents($dropdown_name, $contents, $selected_lang);
            }
        }
        //If more than one language is being updated this request, allow the caller to finalize
        if ($finalize) {
            $this->finalize($selected_lang);
        }
    }

    /**
     * Saves the dropdown as an Extension, and rebuilds the extensions for given language
     *
     * @param string $dropdownName - dropdown name, used for file name
     * @param string $contents - the edited dropdown contents
     * @param string $lang - the edited dropdown language
     * @return bool Success
     */
    protected function saveContents($dropdownName, $contents, $lang)
    {
        $fileName = $this->getExtensionFilePath($dropdownName, $lang);
        if ($fileName) {
            if (file_put_contents($fileName, $contents) !== false) {
                return true;
            }
            $GLOBALS['log']->fatal("Unable to write edited dropdown language to file: $fileName");
        }
        return false;
    }

    protected function getExtensionFilePath($dropdownName, $lang)
    {
        $dirName = 'custom/Extension/application/Ext/Language';
        if (SugarAutoLoader::ensureDir($dirName)) {
            $fileName = "$dirName/$lang.sugar_$dropdownName.php";

            return $fileName;
        } else {
            $GLOBALS['log']->fatal("Unable to create dir: $dirName");
        }

        return false;
    }


    /**
     * Clears the js cache and rebuilds the language files
     *
     * @param string $lang - language to be rebuilt, and cache cleared
     */
    public function finalize($lang)
    {
        if (!is_array($lang)) {
            $lang = [$lang => $lang];
        }
        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $mi = new $moduleInstallerClass();
        $mi->silent = true;
        $mi->rebuild_languages($lang);

        sugar_cache_reset();
        sugar_cache_reset_full();
        clearAllJsAndJsLangFilesWithoutOutput();

        // Clear out the api metadata languages cache for selected language
        LanguageManager::invalidateJsLanguageCache();
        MetaDataManager::refreshLanguagesCache($lang);
    }

    /**
     * function synchDropDown
     * 	Ensures that the set of dropdown keys is consistant accross all languages.
     *
     * @param string $dropdown_name The name of the dropdown to be synched
     * @param array $dropdown The dropdown currently being saved
     * @param string $selected_lang the language currently selected in Studio/MB
     */
    public function synchDropDown($dropdown_name, $dropdown, $selected_lang)
    {
        $allLanguages =  get_languages();
        foreach ($allLanguages as $lang => $langName) {
            if ($lang != $selected_lang) {
                $listStrings = return_app_list_strings_language($lang, false);
                $langDropDown = array();
                if (isset($listStrings[$dropdown_name]) && is_array($listStrings[$dropdown_name])) {
                     $langDropDown = $this->synchDDKeys($dropdown, $listStrings[$dropdown_name]);
                } else {
                    //if the dropdown does not exist in the language, justt use what we have.
                    $langDropDown = $dropdown;
                }
                $contents = $this->getExtensionContents($dropdown_name, $langDropDown);
                $this->saveContents($dropdown_name, $contents, $lang);
            }
        }
    }

    /**
     * function synchMBDropDown
     * 	Ensures that the set of dropdown keys is consistant accross all languages in a ModuleBuilder Module
     *
     * @param $dropdown_name The name of the dropdown to be synched
     * @param $dropdown array The dropdown currently being saved
     * @param $selected_lang String the language currently selected in Studio/MB
     * @param $module MBModule the module to update the languages in
     */
    public function synchMBDropDown($dropdown_name, $dropdown, $selected_lang, $module)
    {
        $selected_lang = $selected_lang . '.lang.php';
        foreach ($module->mblanguage->appListStrings as $lang => $listStrings) {
            if ($lang != $selected_lang) {
                $langDropDown = array();
                if (isset($listStrings[$dropdown_name]) && is_array($listStrings[$dropdown_name])) {
                    $langDropDown = $this->synchDDKeys($dropdown, $listStrings[$dropdown_name]);
                } else {
                    $langDropDown = $dropdown;
                }
                $module->mblanguage->appListStrings[$lang][$dropdown_name] = $langDropDown;
                $module->mblanguage->save($module->key_name);
            }
        }
    }

    private function synchDDKeys($dom, $sub)
    {
        //check for extra keys
        foreach ($sub as $key=>$value) {
            if (!isset($dom[$key])) {
                unset ($sub[$key]);
            }
        }
        //check for missing keys
        foreach ($dom as $key=>$value) {
            if (!isset($sub[$key])) {
                $sub[$key] = $value;
            }
        }
        return $sub;
    }

    public function getPatternMatch($dropdown_name)
    {
        // Change the regex to NOT look for GLOBALS anymore
        return '/\s*\$app_list_strings\s*\[\s*\''
             . $dropdown_name.'\'\s*\]\s*=\s*array\s*\([^\)]*\)\s*;\s*/ism';
    }

    /**
     * Gets the new custom dropdown list file contents after replacement
     *
     * @param string $dropdown_name
     * @param array $dropdown
     * @param string $lang
     * @return string
     */
    public function getNewCustomContents($dropdown_name, $dropdown, $lang)
    {
        $contents = return_custom_app_list_strings_file_contents($lang);
        $contents = str_replace("?>", '', $contents);
        if (empty($contents)) $contents = "<?php";
        $contents = preg_replace($this->getPatternMatch($dropdown_name), "\n\n", $contents);
        $contents .= "\n\n\$app_list_strings['$dropdown_name']=" . var_export_helper($dropdown) . ";";
        return $contents;
    }

    /**
     * Retrieves the contents for a language extension that includes only the dropdown modified in the contents
     * @param string $dropdown_name
     * @param array $dropdown
     *
     * @return string
     */
    protected function getExtensionContents($dropdown_name, $dropdown)
    {
        $contents = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
        $contents .= "\n\$app_list_strings['$dropdown_name']=" . var_export_helper($dropdown) . ";";

        return $contents;
    }

    /**
     * Save dropdowns in which we use 'null' to remove a value
     *
     * @param $dropdown - Dropdown values
     * @param $dropdownName - Dropdown name
     * @param $myListStrings - Current app_list_strings
     * @param $selectedLang - Selected language
     *
     * @see getExemptDropdowns()
     */
    public function saveExemptDropdowns($dropdown, $dropdownName, $myListStrings, $selectedLang)
    {
        // Handle special dropdown item removal
        if (in_array($dropdownName, getExemptDropdowns())) {
            foreach ($myListStrings[$dropdownName] as $key => $value) {
                // If the value is present in the old app_list_strings but not in the new, null it
                if (!empty($key) && !isset($dropdown[$key])) {
                    $dropdown[$key] = null;
                }
            }
            // We need to copy the NULLs if they are not set in the new dropdown
            // because return_app_list_strings_language() removes them from the array
            $files = SugarAutoLoader::existing(
                "custom/include/language/$selectedLang.lang.php",
                "custom/application/Ext/Language/$selectedLang.lang.ext.php"
            );

            foreach ($files as $customLanguage) {
                include FileLoader::validateFilePath($customLanguage);
                if (isset($app_list_strings[$dropdownName])) {
                    foreach ($app_list_strings[$dropdownName] as $key => $value) {
                        if ($value === null && !isset($dropdown[$key])) {
                            $dropdown[$key] = null;
                        }
                    }
                }
            }
        }

        return $dropdown;
    }
}
