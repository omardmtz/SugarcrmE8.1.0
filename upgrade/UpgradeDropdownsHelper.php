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

class UpgradeDropdownsHelper
{
    /**
     * Returns a hash of all dropdown lists extracted from the file specified.
     *
     * Any {@link DropDownBrowser::$restrictedDropdowns restricted dropdown lists} are skipped.
     *
     * @param string $file Load the dropdown lists for the specified language.
     * @return array The keys are the dropdown list names and the values are the dropdown list options. An empty array
     * is returned if the file does not exist.
     */
    public function getDropdowns($file)
    {
        if (!file_exists($file)) {
            return array();
        }

        //TODO: this list needs to be kept in sync with DropDownBrowser::$restrictedDropdowns
        $restrictedDropdowns = array(
            'eapm_list',
            'eapm_list_documents',
            'eapm_list_import',
            'extapi_meeting_password',
            'Elastic_boost_options',
            'commit_stage_dom',
            'commit_stage_custom_dom',
            'commit_stage_binary_dom',
            'forecasts_config_ranges_options_dom',
            'forecasts_timeperiod_types_dom',
            'forecasts_chart_options_group',
            'forecasts_config_worksheet_layout_forecast_by_options_dom',
            'forecasts_timeperiod_options_dom',
            'generic_timeperiod_options',
            //'moduleList', // We may want to put this in at a later date
            //'moduleListSingular', // Same with this
            'sweetspot_theme_options',
        );

        $dropdowns = array();
        $appListStrings = $this->getAppListStringsFromFile($file);

        // checking that it's an array just in case the included file changes the type
        if (is_array($appListStrings)) {
            foreach ($appListStrings as $key => $value) {
                if (!is_array($value) || array_filter($value, 'is_array')) {
                    // it's only a dropdown list if the value is an array
                    continue;
                }

                if (!in_array($key, $restrictedDropdowns)) {
                    $dropdowns[$key] = $value;
                }
            }
        }

        return $dropdowns;
    }

    /**
     * Returns array of app_list_strings keys that need to be used with use_push parameter in ParserDropDown::saveDropDown
     * @see ParserDropDown::saveDropDown for more details
     * @return array
     */
    public function getDropdownsToPush()
    {
        return array(
            'moduleList',
            'moduleListSingular',
            'record_type_display',
            'parent_type_display',
            'record_type_display_notes',
        );
    }

    /**
     * Returns a copy of the $app_list_strings from the specified file.
     *
     * It is assumed that the file exists. Use of the global variable $app_list_strings is required in order to load
     * the strings into a variable that can be returned. The global variable is returned to its previous state before
     * returning.
     *
     * @param string $file Path to the file.
     * @return array
     */
    protected function getAppListStringsFromFile($file)
    {
        global $app_list_strings;

        // back up the $app_list_strings so that it's safe to manipulate the global variable
        $appListStringsBackup = $app_list_strings;

        // clear $app_list_strings so that only the strings found in the file are loaded into the variable
        $app_list_strings = array();

        include $file;

        // capture $app_list_strings into a local variable so it can be returned
        $appListStringsFromFile = $app_list_strings;

        // restore $app_list_strings
        $app_list_strings = $appListStringsBackup;

        return $appListStringsFromFile;
    }
}
