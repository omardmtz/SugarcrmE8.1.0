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

class jsLanguage
{
    public static function createAppStringsCache($lang = 'en_us')
    {
        // cn: bug 8242 - non-US langpack chokes
        $app_strings = return_application_language($lang);
        $app_list_strings = return_app_list_strings_language($lang);

        $json = getJSONobj();
        $app_list_strings_encoded = $json->encode($app_list_strings);
        $app_strings_encoded = $json->encode($app_strings);

        $str = <<<EOQ
SUGAR.language.setLanguage('app_strings', $app_strings_encoded);
SUGAR.language.setLanguage('app_list_strings', $app_list_strings_encoded);
EOQ;

        $cacheDir = create_cache_directory('jsLanguage/');
        if ($fh = @sugar_fopen($cacheDir . $lang . '.js', "w")) {
            fputs($fh, $str);
            fclose($fh);
        }
    }

    public static function createModuleStringsCache($moduleDir, $lang = 'en_us', $return = false)
    {
        global $mod_strings;
        $json = getJSONobj();

        // cn: bug 8242 - non-US langpack chokes
        // Allows for modification of mod_strings by individual modules prior to
        // sending down to JS
        if (empty($mod_strings)) {
            $mod_strings = return_module_language($lang, $moduleDir);
        }

        $mod_strings_encoded = $json->encode($mod_strings);
        $str = "SUGAR.language.setLanguage('" . $moduleDir . "', " . $mod_strings_encoded . ");";

        $cacheDir = create_cache_directory('jsLanguage/' . $moduleDir . '/');

        if ($fh = @fopen($cacheDir . $lang . '.js', "w")) {
            fputs($fh, $str);
            fclose($fh);
        }

        if ($return) {
            return $str;
        }
    }
}
