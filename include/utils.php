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
/*********************************************************************************

 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Psr\Log\LoggerInterface;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\Context;

function make_sugar_config(&$sugar_config)
{
    /* used to convert non-array config.php file to array format */
    global $admin_export_only;
    global $cache_dir;
    global $calculate_response_time;
    global $create_default_user;
    global $dateFormats;
    global $dbconfig;
    global $dbconfigoption;
    global $default_action;
    global $default_charset;
    global $default_currency_name;
    global $default_currency_symbol;
    global $default_currency_iso4217;
    global $defaultDateFormat;
    global $default_language;
    global $default_module;
    global $default_password;
    global $default_permission_mode;
    global $default_theme;
    global $defaultTimeFormat;
    global $default_user_is_admin;
    global $default_user_name;
    global $disable_export;
    global $disable_persistent_connections;
    global $display_email_template_variable_chooser;
    global $display_inbound_email_buttons;
    global $history_max_viewed;
    global $host_name;
    global $import_dir;
    global $languages;
    global $list_max_entries_per_page;
    global $lock_default_user_name;
    global $log_memory_usage;
    global $nameFormats;
    global $requireAccounts;
    global $RSS_CACHE_TIME;
    global $session_dir;
    global $site_URL;
    global $site_url;
    global $sugar_version;
    global $timeFormats;
    global $tmp_dir;
    global $translation_string_prefix;
    global $unique_key;
    global $upload_badext;
    global $upload_dir;
    global $upload_maxsize;
    global $import_max_execution_time;
    global $list_max_entries_per_subpanel;
    global $passwordsetting;

    // assumes the following variables must be set:
    // $dbconfig, $dbconfigoption, $cache_dir,  $session_dir, $site_URL, $upload_dir

    $sugar_config = array (
    'admin_export_only' => empty($admin_export_only) ? false : $admin_export_only,
    'export_delimiter' => empty($export_delimiter) ? ',' : $export_delimiter,
    'cache_dir' => empty($cache_dir) ? 'cache/' : $cache_dir,
    'calculate_response_time' => empty($calculate_response_time) ? true : $calculate_response_time,
    'create_default_user' => empty($create_default_user) ? false : $create_default_user,
    'chartEngine' => 'sucrose',
    'date_formats' => empty($dateFormats) ? array(
    'Y-m-d'=>'2010-12-23',
    'd-m-Y' => '23-12-2010',
    'm-d-Y'=>'12-23-2010',
    'Y/m/d'=>'2010/12/23',
    'd/m/Y' => '23/12/2010',
    'm/d/Y'=>'12/23/2010',
    'Y.m.d' => '2010.12.23',
    'd.m.Y' => '23.12.2010',
    'm.d.Y' => '12.23.2010'
        ) : $dateFormats,
    'dbconfig' => $dbconfig,  // this must be set!!
    'dbconfigoption' => $dbconfigoption,  // this must be set!!
    'default_action' => empty($default_action) ? 'index' : $default_action,
    'default_charset' => empty($default_charset) ? 'UTF-8' : $default_charset,
    'default_currency_name' => empty($default_currency_name) ? 'US Dollar' : $default_currency_name,
    'default_currency_symbol' => empty($default_currency_symbol) ? '$' : $default_currency_symbol,
    'default_currency_iso4217' => empty($default_currency_iso4217) ? '$' : $default_currency_iso4217,
    'default_date_format' => empty($defaultDateFormat) ? 'm/d/Y' : $defaultDateFormat,
    'default_locale_name_format' => empty($defaultNameFormat) ? 's f l' : $defaultNameFormat,
    'default_export_charset' => 'UTF-8',
    'default_language' => empty($default_language) ? 'en_us' : $default_language,
    'default_module' => empty($default_module) ? 'Home' : $default_module,
    'default_password' => empty($default_password) ? '' : $default_password,
    'default_permissions' => array (
        'dir_mode' => 02770,
        'file_mode' => 0660,
        'chown' => '',
        'chgrp' => '',
    ),
    'default_theme' => empty($default_theme) ? 'RacerX' : $default_theme,
    'default_time_format' => empty($defaultTimeFormat) ? 'h:ia' : $defaultTimeFormat,
    'default_user_is_admin' => empty($default_user_is_admin) ? false : $default_user_is_admin,
    'default_user_name' => empty($default_user_name) ? '' : $default_user_name,
    'disable_export' => empty($disable_export) ? false : $disable_export,
    'display_email_template_variable_chooser' => empty($display_email_template_variable_chooser) ? false : $display_email_template_variable_chooser,
    'display_inbound_email_buttons' => empty($display_inbound_email_buttons) ? false : $display_inbound_email_buttons,
    'history_max_viewed' => empty($history_max_viewed) ? 50 : $history_max_viewed,
    'host_name' => empty($host_name) ? 'localhost' : $host_name,
    'import_dir' => $import_dir,  // this must be set!!
    'import_max_records_per_file' => 100,
    'import_max_records_total_limit' => '',
    'languages' => empty($languages) ? array('en_us' => 'English (US)') : $languages,
    'list_max_entries_per_page' => empty($list_max_entries_per_page) ? 20 : $list_max_entries_per_page,
    'list_max_entries_per_subpanel' => empty($list_max_entries_per_subpanel) ? 5 : $list_max_entries_per_subpanel,
    'lock_default_user_name' => empty($lock_default_user_name) ? false : $lock_default_user_name,
    'log_memory_usage' => empty($log_memory_usage) ? false : $log_memory_usage,
    'name_formats' => empty($nameFormats) ? array(
        's f l' => 's f l', 'f l' => 'f l', 's l' => 's l', 'l, s f' => 'l, s f',
        'l, f' => 'l, f', 's l, f' => 's l, f', 'l s f' => 'l s f', 'l f s' => 'l f s'
    ) : $nameFormats,
    'portal_view' => 'single_user',
    'resource_management' => array (
        'special_query_limit' => 50000,
        'special_query_modules' => array('Reports', 'Export', 'Import', 'Administration', 'Sync'),
        'default_limit' => 1000,
    ),
    'require_accounts' => empty($requireAccounts) ? true : $requireAccounts,
    'rss_cache_time' => empty($RSS_CACHE_TIME) ? '10800' : $RSS_CACHE_TIME,
    'session_dir' => $session_dir,  // this must be set!!
    'site_url' => empty($site_URL) ? $site_url : $site_URL,  // this must be set!!
    'showDetailData' => true, // if true, read-only ACL fields will still appear on EditViews as non-editable
    'showThemePicker' => true,
    'sugar_version' => empty($sugar_version) ? 'unknown' : $sugar_version,
    'time_formats' => empty($timeFormats) ? array (
    'H:i'=>'23:00', 'h:ia'=>'11:00 pm', 'h:iA'=>'11:00PM',
    'H.i'=>'23.00', 'h.ia'=>'11.00 pm', 'h.iA'=>'11.00PM' ) : $timeFormats,
    'tmp_dir' => $tmp_dir,  // this must be set!!
    'translation_string_prefix' => empty($translation_string_prefix) ? false : $translation_string_prefix,
    'unique_key' => empty($unique_key) ? get_unique_key() : $unique_key,
    'upload_badext' => empty($upload_badext) ? array (
    'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
    'asp', 'cfm', 'js', 'vbs', 'html', 'htm' ) : $upload_badext,
    'upload_dir' => $upload_dir,  // this must be set!!
    'upload_maxsize' => empty($upload_maxsize) ? 30000000 : $upload_maxsize,
    'import_max_execution_time' => empty($import_max_execution_time) ? 3600 : $import_max_execution_time,
    'lock_homepage' => false,
    'lock_subpanels' => false,
    'collapse_subpanels' => false,
    'max_dashlets_homepage' => 15,
    'dashlet_display_row_options' => array('1','3','5','10'),
    'default_max_tabs' => empty($max_tabs) ? '7' : $max_tabs,
    'default_subpanel_tabs' =>  empty($subpanel_tabs) ? true : $subpanel_tabs,
    'default_subpanel_links' => empty($subpanel_links) ? false : $subpanel_links,
    'default_swap_last_viewed' => empty($swap_last_viewed) ? false : $swap_last_viewed,
    'default_swap_shortcuts' => empty($swap_shortcuts) ? false : $swap_shortcuts,
    'default_navigation_paradigm' => empty($navigation_paradigm) ? 'gm' : $navigation_paradigm,
    'default_call_status' => 'Planned',
    'js_lang_version' => 1,
        'passwordsetting' => empty($passwordsetting) ? array (
        'minpwdlength' => '',
        'maxpwdlength' => '',
        'oneupper' => '',
        'onelower' => '',
        'onenumber' => '',
        'onespecial' => '',
        'SystemGeneratedPasswordON' => '',
        'generatepasswordtmpl' => '',
        'lostpasswordtmpl' => '',
        'customregex' => '',
        'regexcomment' => '',
        'forgotpasswordON' => false,
        'linkexpiration' => '1',
        'linkexpirationtime' => '30',
        'linkexpirationtype' => '1',
        'userexpiration' => '0',
        'userexpirationtime' => '',
        'userexpirationtype' => '1',
        'userexpirationlogin' => '',
        'systexpiration' => '0',
        'systexpirationtime' => '',
        'systexpirationtype' => '0',
        'systexpirationlogin' => '',
        'lockoutexpiration' => '0',
        'lockoutexpirationtime' => '',
        'lockoutexpirationtype' => '1',
        'lockoutexpirationlogin' => '',
        ) : $passwordsetting,
        'use_sprites' => function_exists('imagecreatetruecolor'),
        'search_wildcard_infront' => false,
        'search_wildcard_char' => '%',
        'jobs' => array(
            'min_retry_interval' => 60, // minimal job retry delay
            'max_retries' => 5, // how many times to retry the job
            'timeout' => 3600, // how long a job may spend as running before being force-failed
            'soft_lifetime' => 7, // how many days until job record will be soft deleted after completion
            'hard_lifetime' => 21, // how many days until job record will be purged from DB
        ),
        "cron" => array(
            'max_cron_jobs' => 25, // max jobs per cron schedule run
            'max_cron_runtime' => 1800, // max runtime for cron jobs
            'min_cron_interval' => 30, // minimal interval between cron jobs
        ),
        'max_record_fetch_size' => 1000,
        'max_record_link_fetch_size' => 5000,
        'mass_actions' => array(
        ),
        'merge_duplicates' => array(
            'merge_relate_fetch_concurrency' => 2,
            'merge_relate_fetch_timeout' => 90000,
            'merge_relate_fetch_limit' => 20,
            'merge_relate_update_concurrency' => 4,
            'merge_relate_update_timeout' => 90000,
            'merge_relate_max_attempt' => 3,
        ),
    );
}

function get_sugar_config_defaults()
{
    global $locale;
    /**
     * used for getting base values for array style config.php.  used by the
     * installer and to fill in new entries on upgrades.  see also:
     * sugar_config_union
     */

    $sugar_config_defaults = array (
    'oauth_token_life' => 86400, // 60*60*24
    'oauth_token_expiry' => 0,
    'admin_export_only' => false,
    'export_delimiter' => ',',
    'export_excel_compatible' => false,
    'cache_dir' => 'cache/',
    'calculate_response_time' => true,
    'create_default_user' => false,
    'chartEngine' => 'sucrose',
    'date_formats' => array (
    'Y-m-d' => '2010-12-23', 'm-d-Y' => '12-23-2010', 'd-m-Y' => '23-12-2010',
    'Y/m/d' => '2010/12/23', 'm/d/Y' => '12/23/2010', 'd/m/Y' => '23/12/2010',
    'Y.m.d' => '2010.12.23', 'd.m.Y' => '23.12.2010', 'm.d.Y' => '12.23.2010',),
    'name_formats' => array (
        's f l' => 's f l', 'f l' => 'f l', 's l' => 's l', 'l, s f' => 'l, s f',
        'l, f' => 'l, f', 's l, f' => 's l, f', 'l s f' => 'l s f', 'l f s' => 'l f s'
    ),
    'dbconfigoption' => array (
    'persistent' => false,
    'autofree' => false,
    'debug' => 0,
    'ssl' => false ),
    'default_action' => 'index',
    'default_charset' => return_session_value_or_default('default_charset',
    'UTF-8'),
    'default_currency_name' => return_session_value_or_default('default_currency_name', 'US Dollar'),
    'default_currency_symbol' => return_session_value_or_default('default_currency_symbol', '$'),
    'default_currency_iso4217' => return_session_value_or_default('default_currency_iso4217', 'USD'),
    'default_currency_significant_digits' => return_session_value_or_default('default_currency_significant_digits', 2),
    'default_currency_show_preferred' => false,
    'default_number_grouping_seperator' => return_session_value_or_default('default_number_grouping_seperator', ','),
    'default_decimal_seperator' => return_session_value_or_default('default_decimal_seperator', '.'),
    'default_date_format' => 'm/d/Y',
    'default_locale_name_format' => 's f l',
    'default_export_charset' => 'UTF-8',
    'default_language' => return_session_value_or_default('default_language',
    'en_us'),
    'default_module' => 'Home',
    'default_password' => '',
    'default_permissions' => array (
        'dir_mode' => 02770,
        'file_mode' => 0660,
        'user' => '',
        'group' => '',
    ),
    'default_theme' => return_session_value_or_default('site_default_theme', 'Sugar'),
    'default_time_format' => 'h:ia',
    'default_user_is_admin' => false,
    'default_user_name' => '',
    'disable_export' => false,
    'disable_persistent_connections' =>
    return_session_value_or_default('disable_persistent_connections',
    'false'),
    'display_email_template_variable_chooser' => false,
    'display_inbound_email_buttons' => false,
    'dump_slow_queries' => false,
    'email_address_separator' => ',', // use RFC2368 spec unless we have a noncompliant email client
    'email_default_editor' => 'html',
    'email_default_client' => 'sugar',
    'email_mailer_timeout' => 10,
    'smtp_mailer_debug' => 0,
    'history_max_viewed' => 50,
    'installer_locked' => true,
    'import_max_records_per_file' => 100,
    'import_max_records_total_limit' => '',
    'languages' => array('en_us' => 'English (US)'),
    'large_scale_test' => false,
    'list_max_entries_per_page' => 20,
    'list_max_entries_per_subpanel' => 5,
    'wl_list_max_entries_per_page' => 10,
    'wl_list_max_entries_per_subpanel' => 3,
    'lock_default_user_name' => false,
    'log_memory_usage' => false,
    'portal_view' => 'single_user',
    'preview_edit' => false,
    'resource_management' => array (
        'special_query_limit' => 50000,
        'special_query_modules' => array('Reports', 'Export', 'Import', 'Administration', 'Sync'),
        'default_limit' => 1000,
    ),
    'require_accounts' => true,
    'rss_cache_time' => return_session_value_or_default('rss_cache_time',
    '10800'),
    'save_query' => 'all',
    'showDetailData' => true, // if true, read-only ACL fields will still appear on EditViews as non-editable
    'showThemePicker' => true,
    'slow_query_time_msec' => '100',
    'time_formats' => array (
        'H:i'=>'23:00', 'h:ia'=>'11:00pm', 'h:iA'=>'11:00PM', 'h:i a'=>'11:00 pm', 'h:i A'=>'11:00 PM',
        'H.i'=>'23.00', 'h.ia'=>'11.00pm', 'h.iA'=>'11.00PM', 'h.i a'=>'11.00 pm', 'h.i A'=>'11.00 PM' ),
    'tracker_max_display_length' => 30,
    'translation_string_prefix' =>
    return_session_value_or_default('translation_string_prefix', false),
    'upload_badext' => array (
    'php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py',
    'asp', 'cfm', 'js', 'vbs', 'html', 'htm' ),
    'upload_maxsize' => 30000000,
    'import_max_execution_time' => 3600,
    'verify_client_ip' => true,
    'js_custom_version' => '',
    'js_lang_version' => 1,
        'lead_conv_activity_opt' => 'donothing',
    'default_number_grouping_seperator' => ',',
    'default_decimal_seperator' => '.',
    'lock_homepage' => false,
    'lock_subpanels' => false,
    'collapse_subpanels' => false,
    'max_dashlets_homepage' => '15',
    'default_max_tabs' => '7',
    'dashlet_display_row_options' => array('1','3','5','10'),
    'default_subpanel_tabs' =>  false,
    'default_subpanel_links' => false,
    'default_swap_last_viewed' => false,
    'default_swap_shortcuts' => false,
    'default_navigation_paradigm' => 'gm',
    'admin_access_control' => false,
      'use_common_ml_dir'	=> false,
      'common_ml_dir' => '',
    'vcal_time' => '2',
    'calendar' => array(
      'default_view' => 'week',
      'show_calls_by_default' => true,
      'show_tasks_by_default' => true,
      'editview_width' => 990,
      'editview_height' => 465,
      'day_timestep' => 15,
      'week_timestep' => 30,
      'items_draggable' => true,
      'items_resizable' => true,
      'enable_repeat' => true,
      'max_repeat_count' => 1000,
    ),
    'snip_url' => 'https://ease.sugarcrm.com/',
    'passwordsetting' => array (
        'minpwdlength' => '',
        'maxpwdlength' => '',
        'oneupper' => '',
        'onelower' => '',
        'onenumber' => '',
        'onespecial' => '',
        'SystemGeneratedPasswordON' => '',
        'generatepasswordtmpl' => '',
        'lostpasswordtmpl' => '',
        'customregex' => '',
        'regexcomment' => '',
        'forgotpasswordON' => false,
        'linkexpiration' => '1',
        'linkexpirationtime' => '30',
        'linkexpirationtype' => '1',
        'userexpiration' => '0',
        'userexpirationtime' => '',
        'userexpirationtype' => '1',
        'userexpirationlogin' => '',
        'systexpiration' => '0',
        'systexpirationtime' => '',
        'systexpirationtype' => '0',
        'systexpirationlogin' => '',
        'lockoutexpiration' => '0',
        'lockoutexpirationtime' => '',
        'lockoutexpirationtype' => '1',
        'lockoutexpirationlogin' => '',
        ),
        'use_sprites' => function_exists('imagecreatetruecolor'),
        'use_real_names' => true,

        'search_wildcard_infront' => false,
        'search_wildcard_char' => '%',
        'jobs' => array(
            'min_retry_interval' => 30, // 30 seconds minimal job retry
            'max_retries' => 5, // how many times to retry the job
            'timeout' => 3600, // how long a job may spend as running before being force-failed
        ),
        "cron" => array(
            'max_cron_jobs' => 25, // max jobs per cron schedule run
            'max_cron_runtime' => 1800, // max runtime for cron jobs
            'min_cron_interval' => 30, // minimal interval between cron jobs
        ),
        'max_record_fetch_size' => 1000,
        'max_record_link_fetch_size' => 5000,
        'mass_actions' => array(
        ),
        'merge_duplicates' => array(
            'merge_relate_fetch_concurrency' => 2,
            'merge_relate_fetch_timeout' => 90000,
            'merge_relate_fetch_limit' => 20,
            'merge_relate_update_concurrency' => 4,
            'merge_relate_update_timeout' => 90000,
            'merge_relate_max_attempt' => 3,
        ),
        'hide_history_contacts_emails' => array(
            'Cases' => false,
            'Accounts' => false,
            'Opportunities' => true
        ),
        'enable_mobile_redirect' => true,
        'additional_js_config' => array(),
        'tmp_file_max_lifetime' => 86400,
        'diagnostic_file_max_lifetime' => 604800,
        'pdf_file_max_lifetime' => 86400,
        'roleBasedViews' => true,
        'pmse_settings_default' => array(
            'logger_level' => 'critical',
            'error_number_of_cycles' =>  '10',
            'error_timeout' => '40',
        ),
        'sugar_min_int' => -2147483648,
        'sugar_max_int' => 2147483647,
        'max_aggregate_email_attachments_bytes' => 10000000,
        'new_email_addresses_opted_out' => false,
        'activity_streams_enabled' => true,
    );

    if (empty($locale)) {
        $locale = Localization::getObject();
	}

    $sugar_config_defaults['default_currencies'] = $locale->getDefaultCurrencies();

    $sugar_config_defaults = sugarArrayMerge($locale->getLocaleConfigDefaults(), $sugar_config_defaults);

    $sugar_config_defaults[TeamBasedACLConfigurator::CONFIG_KEY] = TeamBasedACLConfigurator::getDefaultConfig();
    return( $sugar_config_defaults );
}

/**
 * @deprecated use SugarView::getMenu() instead
 */
function load_menu($path)
{
    global $module_menu;

    foreach(SugarAutoLoader::existing(
        $path . 'Menu.php',
        'custom/' . $path . 'Ext/Menus/menu.ext.php',
        'custom/application/Ext/Menus/menu.ext.php'
    ) as $file) {
        require $file;
    }

    return $module_menu;
}

/**
 * get_notify_template_file
 * This function will return the location of the email notifications template to use
 *
 * @return string relative file path to email notifications template file
 */
function get_notify_template_file($language)
{
    /*
     * Order of operation:
     * 1) custom version of specified language
     * 2) stock version of specified language
     * 3) custom version of en_us template
     * 4) stock en_us template
     */

    // set $file to the base code template so it's set if none of the conditions pass
    $file = SugarAutoLoader::existingCustomOne("include/language/en_us.notify_template.html",
        "include/language/{$language}.notify_template.html");

    return $file;
}

function sugar_config_union( $default, $override )
{
    // a little different then array_merge and array_merge_recursive.  we want
    // the second array to override the first array if the same value exists,
    // otherwise merge the unique keys.  it handles arrays of arrays recursively
    // might be suitable for a generic array_union
    if ( !is_array( $override ) ) {
        $override = array();
    }
    foreach ($default as $key => $value) {
        if ( !array_key_exists($key, $override) ) {
            $override[$key] = $value;
        } elseif ( is_array( $key ) ) {
            $override[$key] = sugar_config_union( $value, $override[$key] );
        }
    }

    return( $override );
}

function make_not_writable( $file )
{
    // Returns true if the given file/dir has been made not writable
    $ret_val = false;
    if ( is_file($file) || is_dir($file) ) {
        if ( !is_writable($file) ) {
            $ret_val = true;
        } else {
            $original_fileperms = fileperms($file);

            // take away writable permissions
            $new_fileperms = $original_fileperms & ~0x0092;
            @sugar_chmod($file, $new_fileperms);

            if ( !is_writable($file) ) {
                $ret_val = true;
            }
        }
    }

    return $ret_val;
}


/** This function returns the name of the person.
 * It currently returns "first last".  It should not put the space if either name is not available.
 * It should not return errors if either name is not available.
 * If no names are present, it will return ""
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_name($row, $first_column, $last_column)
{
    $first_name = "";
    $last_name = "";
    $full_name = "";

    if (isset($row[$first_column])) {
        $first_name = stripslashes($row[$first_column]);
    }

    if (isset($row[$last_column])) {
        $last_name = stripslashes($row[$last_column]);
    }

    $full_name = $first_name;

    // If we have a first name and we have a last name
    if ($full_name != "" && $last_name != "") {
        // append a space, then the last name
        $full_name .= " ".$last_name;
    }
    // If we have no first name, but we have a last name
    else if ($last_name != "") {
        // append the last name without the space.
        $full_name .= $last_name;
    }

    return $full_name;
}


function get_languages()
{
    global $sugar_config;
    $lang = isset($sugar_config['languages']) ? $sugar_config['languages'] : array();
    if (!empty($sugar_config['disabled_languages'])) {
        $disabledLanguages = explode(',', $sugar_config['disabled_languages']);

        // Make sure we don't disable the default language
        if (($key = array_search($sugar_config['default_language'], $disabledLanguages)) !== false) {
            unset($disabledLanguages[$key]);
        }

        foreach ($disabledLanguages as $disable) {
            unset($lang[$disable]);
        }
    }

    return $lang;
}

function get_all_languages()
{
    global $sugar_config;

    return $sugar_config['languages'];
}


function get_language_display($key)
{
    global $sugar_config;

    return $sugar_config['languages'][$key];
}

/**
 * @deprecated
 */
function get_assigned_user_name($assigned_user_id, $is_group = '')
{
	// Declare static early so PSR-2 parser doesn't choke
	static $saved_user_list = null;

    if (!empty($GLOBALS['sugar_config']['disable_user_cache'])) {
        $user = BeanFactory::getBean("Users", $assigned_user_id);
        if (!empty($user->id)) {
            return $user->full_name;
        } else {
            return '';
        }
    }

    if (empty($saved_user_list)) {
        $saved_user_list = get_user_array(false, '', '', false, null, $is_group);
    }

    if (isset($saved_user_list[$assigned_user_id])) {
        return $saved_user_list[$assigned_user_id];
    }

    return '';
}
function get_assigned_team_name($assigned_team_id)
{
    if (!empty($GLOBALS['sugar_config']['disable_user_cache'])) {
        return Team::getTeamName($assigned_team_id);
    }

    static $team_list = null;

    if(empty($team_list))
    $team_list =get_team_array(false,"");

    if (isset($team_list[$assigned_team_id])) {
        return $team_list[$assigned_team_id];
    }

    return "";
}

/**
 * Bug 13271 - Sometimes we just want to know the name of a team for display purposes when the team_id may not be one of the users assigned teams.
 * For example when they have an admin Role for a module we may need to display the teams for other users
 * Unfortunately this means we can't just use the team_array value that may be stored in the register as unless we pass is_admin(), it won't contain all the necessary team names
 *
 * @param unknown_type $team_id
 */
function get_team_name($team_id)
{
     $db = DBManagerFactory::getInstance();

    $query = 'SELECT t1.id, t1.name FROM teams t1 where t1.deleted = 0 ORDER BY t1.private,t1.name ASC';

    $GLOBALS['log']->debug("utils.php->get_team_name() query: $query");
    $result = $db->query($query, true, "Error filling in team names: ");

    while ($row = $db->fetchByAssoc($result)) {
        if ($row['id'] == $team_id) {
            return $row['name'];
        }
    }

    return '';

}

/**
 * retrieve the list of teams that this user has access to.
 * add_blank -- If you would like to have a blank entry in the list (to allow no selection) pass true.
 */
function get_team_array($add_blank = false)
{
    global $current_user;
    if (empty($current_user) || empty($current_user->id)) {
        return array();
    }

    $db = DBManagerFactory::getInstance();

    if ($current_user->isAdminForModule('Users')) {
        $query = 'SELECT t1.id, t1.name, t1.name_2 FROM teams t1';
        $where = 't1.deleted = 0';
    } else {
        $query = 'SELECT t1.id, t1.name, t1.name_2 FROM teams t1, team_memberships t2 ';
        $where = 't1.deleted = 0 and t2.deleted = 0 and t1.id=t2.team_id and t2.user_id = '
            . "'" . $current_user->id . "'";
    }

    $team = BeanFactory::newBean('Teams');
    $team->addVisibilityFrom($query);
    $query .= " WHERE $where ";
    $team->addVisibilityWhere($query);
    $query .= ' ORDER BY t1.private, t1.name ASC';

    $key = md5($query . $current_user->id);
    $team_array = get_register_value('team_array', $key);

    if (empty($team_array)) {
        $GLOBALS['log']->debug("get_team_array query: $query");
        $result = $db->query($query, true, "Error filling in team array: ");

        while ($row = $db->fetchByAssoc($result)) {

            if ($current_user->showLastNameFirst()) {
              $team_array[$row['id']] = trim($row['name_2'] . ' ' . $row['name']);
            } else {
              $team_array[$row['id']] = trim($row['name'] . ' ' . $row['name_2']);
            }
        }

        set_register_value('team_array', $key, $team_array);
    }

    if ($add_blank) {
        $team_array[''] = '';
    }

    return $team_array;
}

/**
 * retrieves the user_name column value (login)
 * @param string id GUID of user
 * @return string
 */
function get_user_name($id)
{
    global $db;

    if(empty($db))
    $db = DBManagerFactory::getInstance();

    $q = "SELECT user_name FROM users WHERE id='{$id}'";
    $a = $db->fetchOne($q);

    return (empty($a)) ? '' : $a['user_name'];
}

/**
 * @deprecated
 * @see User::getUserArray()
 */
function get_user_array($add_blank=true, $status="Active", $user_id='', $use_real_name=false, $user_name_filter='', $portal_filter=' AND portal_only=0 ', $from_cache = true, $order_by = array())
{
    $GLOBALS['log']->deprecated('get_user_array() is deprecated');
    return BeanFactory::newBean('Users')->getUserArray(
        $add_blank,
        $status,
        $user_id,
        $use_real_name,
        $user_name_filter,
        $portal_filter,
        $from_cache,
        $order_by
    );
}


/**
 * uses a different query to return a list of users than get_user_array()
 * Used from QuickSearch.php
 * @param args string where clause entry
 * @return array Array of Users' details that match passed criteria
 */
function getUserArrayFromFullName($args, $hide_portal_users = false)
{
    global $locale;
    $db = DBManagerFactory::getInstance();

    // jmorais@dri - Bug #51411
    //
    // Refactor the code responsible for parsing supplied $args, this way we
    // ensure that if $args has at least one space (after trim), the $inClause
    // will be composed by several clauses ($inClauses) inside parenthesis.
    //
    // Ensuring that operator precedence is respected, and avoiding
    // inactive/deleted users to be retrieved.
    //
    $args = trim($args);
    if (strpos($args, ' ')) {
        $inClauses = array();

        $argArray = explode(' ', $args);
        foreach ($argArray as $arg) {
            $arg = $db->quote($arg);
            $inClauses[] = "(first_name LIKE '{$arg}%' OR last_name LIKE '{$arg}%')";
        }

        $inClause = '(' . implode('OR ', $inClauses) . ')';

    } else {
        $args = $db->quote($args);
        $inClause = "(first_name LIKE '{$args}%' OR last_name LIKE '{$args}%')";
    }
    // ~jmorais@dri

    $query  = "SELECT id, first_name, last_name, user_name FROM users WHERE status='Active' AND deleted=0 AND ";
    if ($hide_portal_users) {
        $query .= " portal_only=0 AND ";
    }
    $query .= $inClause;
    $query .= " ORDER BY last_name ASC";

	$r = $db->query($query);
	$ret = array();
	while($a = $db->fetchByAssoc($r)) {
        $ret[$a['id']] = $locale->formatName('Users', $a);
	}

    return $ret;
}

/**
 *
 * based on user pref then system pref
 */
function showFullName()
{
    global $sugar_config;
    global $current_user;
    static $showFullName = null;

    if (is_null($showFullName)) {
        $sysPref = !empty($sugar_config['use_real_names']);
        $userPref = (is_object($current_user)) ? $current_user->getPreference('use_real_names') : null;

        if ($userPref != null) {
            $showFullName = ($userPref == 'on');
        } else {
            $showFullName = $sysPref;
        }
    }

    return $showFullName;
}

function clean($string, $maxLength)
{
    $string = substr($string, 0, $maxLength);

    return escapeshellcmd($string);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map($request_var, & $focus, $always_copy = false)
{
    safe_map_named($request_var, $focus, $request_var, $always_copy);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map_named($request_var, & $focus, $member_var, $always_copy)
{
    if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
        $GLOBALS['log']->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
        $focus->$member_var = $_REQUEST[$request_var];
    }
}

/**
 * This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 *
 * @param string $language specific language to load
 * @param bool $useCache optional use cache for store language data array
 * @return array lang strings
 */
function return_app_list_strings_language($language, $useCache = true)
{
    global $app_list_strings;
    global $sugar_config;

    //$useCache added to fix CRYS-459 memory limit
    if ($useCache) {
        $cache_key = 'app_list_strings.' . $language;
        // Check for cached value
        $cache_entry = sugar_cache_retrieve($cache_key);
        if (!empty($cache_entry)) {
            return $cache_entry;
        }
    }

    $default_language = !empty($sugar_config['default_language']) ? $sugar_config['default_language'] : $language;
    $temp_app_list_strings = $app_list_strings;

    $langs = array('en_us');
    if ($default_language != 'en_us') {
        $langs[] = $default_language;
    }
    if(!in_array($language, $langs) || ($default_language != 'en_us' && $language == 'en_us')) {
        $langs[] = $language;
    }

    $app_list_strings_array = array();

    //Merge language files together
    foreach ($langs as $key => $lang) {

        $app_list_strings_state = $app_list_strings;
            foreach(SugarAutoLoader::existing(
                "include/language/$lang.lang.php",
                "include/language/$lang.lang.override.php",
                "include/language/$lang.lang.php.override"
            ) as $file) {
                include $file;
                $GLOBALS['log']->info("Found language file: $file");
            }

        $app_list_strings_array[$lang] = $app_list_strings;
        //Return to previous state unless we are on first iteration and do an intersect merge
        if ($key > 0) {
            $app_list_strings = $app_list_strings_state;
            //In case a custom file doesn't exist for the expected language, we want the custom lists from the default language
            //(if there are key additions/deletions) but we want strings in our expected language (as much as possible).
            $app_list_strings = sugarArrayIntersectMerge($app_list_strings, $app_list_strings_array[$lang]);
        }

        $files = SugarAutoLoader::existing(
            "custom/include/language/$lang.lang.php",
            "custom/application/Ext/Language/$lang.lang.ext.php"
        );

        foreach ($files as $file) {
            $app_list_strings = _mergeCustomAppListStrings($file, $app_list_strings);
            $GLOBALS['log']->info("Found language file: $file");
        }
    }

    if (!isset($app_list_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language file for the selected language ($language) or the default language ($default_language) or the en_us language");

        return null;
    }

    $return_value = $app_list_strings;
    $app_list_strings = $temp_app_list_strings;

    //Add to the app_list_strings the list of language available in the application.
    $return_value['available_language_dom'] = get_languages();

    if ($useCache) {
        sugar_cache_put($cache_key, $return_value);
    }

    return $return_value;
}

/**
* The dropdown items in custom language files is $app_list_strings['$key']['$second_key'] = $value not
* $GLOBALS['app_list_strings']['$key'] = $value, so we have to delete the original ones in app_list_strings and relace it with the custom ones.
 * @param string $file The language file that you want include,
 * @param array  $appListStrings Provided app_list_strings, renamed to not conflict with GLOBALS
 * @return array
 */
 //jchi 25347
function _mergeCustomAppListStrings($file , $appListStrings)
{
    // Bug 60008 + Bugs 60607, 60608, 60609, 60612 + 60393
    // There is a chance that some app_list_strings were in fact in $GLOBALS['app_list_strings'].
    // In case of that, the customizations end up being blown away because they
    // are written to the global app_list_strings, not the function scope $app_list_strings.
    // This fixes that. rgonzalez, jbartek
    global $app_list_strings;
    $app_list_strings_original = $appListStrings;

    // Include the language file. With $app_list_strings globalized above it should
    // make no difference if the content of the file is $app_list_strings or
    // $GLOBALS['app_list_strings'] or a mixture of the two, even if indexes overlap.
    // Accordingly, the latest additions to the file will trump older additions.
    include($file);

    if (empty($app_list_strings) || !is_array($app_list_strings)) {
        return $app_list_strings_original;
    }
    //Bug 25347: We should not merge custom dropdown fields unless they relate to parent fields or the module list.
    // FG - bug 45525 - Specific codelists must NOT be overwritten
    $exemptDropdowns = getExemptDropdowns();

    foreach ($app_list_strings as $key=>$value) {
        if (!in_array($key, $exemptDropdowns) && array_key_exists($key, $app_list_strings_original)) {
            unset($app_list_strings_original[$key]);
        }
    }
    $app_list_strings = sugarArrayMergeRecursive($app_list_strings_original , $app_list_strings);

    foreach ($exemptDropdowns as $exemptDropdown) {
        if (isset($app_list_strings[$exemptDropdown])) {
            $app_list_strings[$exemptDropdown] = array_filter(
                $app_list_strings[$exemptDropdown],
                function ($value) {
                    return $value !== null;
                }
            );
        }
    }

    return $app_list_strings;
}

/**
 * This function retrieves an application language file and returns the array of strings included.
 *
 * @param string $language specific language to load
 * @return array lang strings
 */
function return_application_language($language)
{
    global $app_strings, $app_list_strings, $sugar_config;

    $cache_key = 'app_strings.'.$language;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $temp_app_strings = $app_strings;
    $temp_app_list_strings = $app_list_strings;
    $default_language = !empty($sugar_config['default_language']) ? $sugar_config['default_language'] : $language;

    $langs = array();
    if ($language != 'en_us') {
        $langs[] = 'en_us';
    }
    if ($default_language != 'en_us' && $language != $default_language) {
        $langs[] = $default_language;
    }

    $langs[] = $language;

    $app_strings_array = array();
    foreach ($langs as $lang) {
        $app_list_strings = array();
        foreach(SugarAutoLoader::existing(
                "include/language/$lang.lang.php",
                "include/language/$lang.lang.override.php",
                "include/language/$lang.lang.php.override",
                "custom/include/language/$lang.lang.php",
                "custom/application/Ext/Language/$lang.lang.ext.php"
        ) as $file) {
            include $file;
            $GLOBALS['log']->info("Found language file: $file");
        }

        $app_strings_array[] = $app_strings;
    }

    $app_strings = array();
    foreach ($app_strings_array as $app_strings_item) {
        $app_strings = sugarLangArrayMerge($app_strings, $app_strings_item);
    }

    if (!isset($app_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language strings");

        return null;
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if (!empty($sugar_config['translation_string_prefix'])) {
        foreach ($app_strings as $entry_key=>$entry_value) {
            $app_strings[$entry_key] = $language.' '.$entry_value;
        }
    }
    if (isset($_SESSION['show_deleted'])) {
        $app_strings['LBL_DELETE_BUTTON'] = $app_strings['LBL_UNDELETE_BUTTON'];
        $app_strings['LBL_DELETE_BUTTON_LABEL'] = $app_strings['LBL_UNDELETE_BUTTON_LABEL'];
        $app_strings['LBL_DELETE_BUTTON_TITLE'] = $app_strings['LBL_UNDELETE_BUTTON_TITLE'];
        $app_strings['LBL_DELETE'] = $app_strings['LBL_UNDELETE'];
    }

    $app_strings['LBL_ALT_HOT_KEY'] = get_alt_hot_key();

    $return_value = $app_strings;
    $app_strings = $temp_app_strings;
    $app_list_strings = $temp_app_list_strings;

    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}

/**
 * This function retrieves a module's language file and returns the array of strings included.
 *
 * @param string $language specific language to load
 * @param string $module module name to load strings for
 * @param bool $refresh optional, true if you want to rebuild the language strings
 * @return array lang strings
 */
function return_module_language($language, $module, $refresh=false)
{
    global $mod_strings;
    global $sugar_config;
    global $currentModule;

    // Jenny - Bug 8119: Need to check if $module is not empty
    if (empty($module)) {
        $message = "Variable module is not in return_module_language ";
        //If any object in the stack contains a circular reference,
        //debug_backtrace will cause a fatal recursive dependency error without DEBUG_BACKTRACE_IGNORE_ARGS
        $message .= var_export(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
        $GLOBALS['log']->warn($message);

        return array();
    }

    if (!$refresh) {
        $cache_key = "return_mod_lang_{$module}_{$language}";
        // Check for cached value
        $cache_entry = sugar_cache_retrieve($cache_key);
        if (!empty($cache_entry) && is_array($cache_entry)) {
            return $cache_entry;
        }
    }
    // Store the current mod strings for later
    $temp_mod_strings = $mod_strings;
    $loaded_mod_strings = array();
    $language_used = $language;
    $default_language = !empty($sugar_config['default_language']) ? $sugar_config['default_language'] : $language;
    $bean = BeanFactory::newBean($module);

    if (empty($language)) {
        $language = $default_language;
    }

    // Bug 21559 - So we can get all the strings defined in the template, refresh
    // the vardefs file if the cached language file doesn't exist.
    if(!file_exists(sugar_cached('modules/'). $module . '/language/'.$language.'.lang.php')
            && !empty($GLOBALS['beanList'][$module])){
        $object = BeanFactory::getObjectName($module);
        VardefManager::loadVardef($module,$object);
    }

    $loaded_mod_strings = LanguageManager::loadModuleLanguage($module, $language,$refresh);
    // BR-24 & BR-25, some modules, like Dynamic Fields, don't have a module_dir and this throws a notice
    if ($bean && !empty($bean->module_dir) && $bean->module_dir !== $module) {
        $loaded_mod_strings = sugarLangArrayMerge(
            LanguageManager::loadModuleLanguage($bean->module_dir, $language, $refresh),
            $loaded_mod_strings
        );
    }

    // cn: bug 6048 - merge en_us with requested language
    if ($language != $default_language) {
        $loaded_mod_strings = sugarLangArrayMerge(
            LanguageManager::loadModuleLanguage($module, $default_language, $refresh),
                $loaded_mod_strings
            );
        if ($bean && !empty($bean->module_dir) && $bean->module_dir !== $module) {
            $loaded_mod_strings = sugarLangArrayMerge(
                LanguageManager::loadModuleLanguage($bean->module_dir, $default_language, $refresh),
                $loaded_mod_strings
            );
        }
    }

    // Load in en_us strings by default
    if ($language != 'en_us' && $default_language != 'en_us') {
        $loaded_mod_strings = sugarLangArrayMerge(
            LanguageManager::loadModuleLanguage($module, 'en_us', $refresh),
                $loaded_mod_strings
            );
        if ($bean && $bean->module_dir !== $module) {
            $loaded_mod_strings = sugarLangArrayMerge(
                LanguageManager::loadModuleLanguage($bean->module_dir, 'en_us', $refresh),
                $loaded_mod_strings
            );
        }
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if (!empty($sugar_config['translation_string_prefix']) && isset($loaded_mod_strings)) {
        foreach ($loaded_mod_strings as $entry_key=>$entry_value) {
            $loaded_mod_strings[$entry_key] = $language_used.' '.$entry_value;
        }
    }

    $return_value = $loaded_mod_strings;
    if (!isset($mod_strings)) {
        $mod_strings = $return_value;
    } else
        $mod_strings = $temp_mod_strings;

    $cache_key = "return_mod_lang_{$module}_{$language}";
    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language,$module)
{
    global $mod_list_strings;
    global $sugar_config;
    global $currentModule;

    $cache_key = "mod_list_str_lang.".$language.$module;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $language_used = $language;
    $temp_mod_list_strings = $mod_list_strings;
    $default_language = !empty($sugar_config['default_language']) ? $sugar_config['default_language'] : $language;

    if ($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null) {
        return $mod_list_strings;
    }

    // cn: bug 6351 - include en_us if file langpack not available
    // cn: bug 6048 - merge en_us with requested language
    include("modules/$module/language/en_us.lang.php");
    $en_mod_list_strings = array();
    if($language_used != $default_language)
    $en_mod_list_strings = $mod_list_strings;

    foreach(SugarAutoLoader::existing(
        "modules/$module/language/$language.lang.php",
        "modules/$module/language/$language.lang.override.php",
        "modules/$module/language/$language.lang.php.override") as $file) {
        include $file;
    }

    // cn: bug 6048 - merge en_us with requested language
    $mod_list_strings = sugarLangArrayMerge($en_mod_list_strings, $mod_list_strings);

    // if we still don't have a language pack, then log an error
    if (!isset($mod_list_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language) for module({$module})");

        return null;
    }

    $return_value = $mod_list_strings;
    $mod_list_strings = $temp_mod_list_strings;

    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}


/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme)
{
    global $mod_strings, $sugar_config, $current_language;

    $language_used = $language;
    $default_language = !empty($sugar_config['default_language']) ? $sugar_config['default_language'] : $language;
    $path = SugarThemeRegistry::get($theme)->getFilePath();

    foreach(SugarAutoLoader::existing(
        $path."/language/$current_language.lang.php",
        $path."/language/$current_language.lang.override.php",
        $path."/language/$current_language.lang.php.override"
    ) as $file) {
        include $file;
    }
    if (!isset($theme_strings)) {
        $GLOBALS['log']->warn("Unable to find the theme file for language: ".$language." and theme: ".$theme);
        require(SugarThemeRegistry::get($theme)->getFilePath()."/language/$default_language.lang.php");
        $language_used = $default_language;
    }

    if (!isset($theme_strings)) {
        $GLOBALS['log']->fatal("Unable to load the theme($theme) language file for the selected language($language) or the default language($default_language)");

        return null;
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if ($sugar_config['translation_string_prefix']) {
        foreach ($theme_strings as $entry_key=>$entry_value) {
            $theme_strings[$entry_key] = $language_used.' '.$entry_value;
        }
    }

    return $theme_strings;
}

/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_session_value_or_default($varname, $default)
{
    if (isset($_SESSION[$varname]) && $_SESSION[$varname] != "") {
        return $_SESSION[$varname];
    }

    return $default;
}

/**
 * Creates an array of where restrictions.  These are used to construct a where SQL statement on the query
 * It looks for the variable in the $_REQUEST array.  If it is set and is not "" it will create a where clause out of it.
 * @param &$where_clauses - The array to append the clause to
 * @param $variable_name - The name of the variable to look for an add to the where clause if found
 * @param $SQL_name - [Optional] If specified, this is the SQL column name that is used.  If not specified, the $variable_name is used as the SQL_name.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function append_where_clause(&$where_clauses, $variable_name, $SQL_name = null)
{
    if ($SQL_name == null) {
        $SQL_name = $variable_name;
    }

    if (isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "") {
        array_push($where_clauses, "$SQL_name like '".$GLOBALS['db']->quote($_REQUEST[$variable_name])."%'");
    }
}

/**
 * Generate the appropriate SQL based on the where clauses.
 * @param $where_clauses - An Array of individual where clauses stored as strings
 * @returns string where_clause - The final SQL where clause to be executed.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function generate_where_statement($where_clauses)
{
    $where = "";
    foreach ($where_clauses as $clause) {
        if($where != "")
        $where .= " and ";
        $where .= $clause;
    }

    $GLOBALS['log']->info("Here is the where clause for the list view: $where");

    return $where;
}

/**
 * determines if a passed string matches the criteria for a Sugar GUID
 * @param string $guid
 * @return bool False on failure
 */
function is_guid($guid)
{
    return strlen($guid) == 36 && preg_match("/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i", $guid);

}

/**
 * Create a unique key and keep it in globals so it can be used by make_sugar_config()
 * and other code during install process
 * @return string unique_key
 */
function get_unique_key()
{
    global $unique_key;

    if (empty($unique_key)) {
        $unique_key = md5(create_guid());
    }

    return $unique_key;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 * @deprecated Use Sugarcrm\Sugarcrm\Util\Uuid
 */
function create_guid()
{
    return Sugarcrm\Sugarcrm\Util\Uuid::uuid1();
}

/**
 * @deprecated see create_guid()
 */
function create_guid_section($characters)
{
    $return = "";
    for ($i=0; $i<$characters; $i++) {
        $return .= dechex(mt_rand(0,15));
    }

    return $return;
}

/**
 * @deprecated see create_guid()
 */
function ensure_length(&$string, $length)
{
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string,$length,"0");
    } elseif ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}

function microtime_diff($a, $b)
{
    list($a_dec, $a_sec) = explode(" ", $a);
    list($b_dec, $b_sec) = explode(" ", $b);

    return $b_sec - $a_sec + $b_dec - $a_dec;
}

// check if Studio is displayed.
function displayStudioForCurrentUser()
{
    global $current_user;
    if ( $current_user->isAdmin() ) {
        return true;
    }
    if (isset($_SESSION['display_studio_for_user'])) {
        return $_SESSION['display_studio_for_user'];
    }
    $access = $current_user->getDeveloperModules();
    foreach ($access as $key=>$mod) {
        if (SugarAutoLoader::existingCustomOne("modules/{$mod}/metadata/studio.php")) {
            $_SESSION['display_studio_for_user'] = true;

            return true;
        }
    }
    $_SESSION['display_studio_for_user'] = false;

    return false;


}

function displayWorkflowForCurrentUser()
{
    if ( is_admin($GLOBALS['current_user']) ) {
        return true;
    }
    if (isset($_SESSION['display_workflow_for_user'])) {
        return $_SESSION['display_workflow_for_user'];
    }

    global $moduleList;
    $workflow_mod_list = array();
    foreach ($moduleList as $module) {
        $workflow_mod_list[$module] = $module;
    }
    // This list is taken from the previous version of workflow_utils.php
    $workflow_mod_list['Tasks'] = "Tasks";
    $workflow_mod_list['Calls'] = "Calls";
    $workflow_mod_list['Meetings'] = "Meetings";
    $workflow_mod_list['Notes'] = "Notes";
    $workflow_mod_list['ProjectTask'] = "Project Tasks";
    $workflow_mod_list['Leads'] = "Leads";
    $workflow_mod_list['Opportunities'] = "Opportunities";
    // End of list

    $access = get_workflow_admin_modules_for_user($GLOBALS['current_user']);
    foreach ($access as $key=>$val) {
        if (in_array($val, $workflow_mod_list)) {
            $_SESSION['display_workflow_for_user'] = true;

            return true;
        }
    }
    $_SESSION['display_workflow_for_user'] = false;

    return false;
}

// return an array with all modules where the user is an admin.
function get_admin_modules_for_user($user)
{
    $GLOBALS['log']->deprecated("get_admin_modules_for_user() is deprecated as of 6.2.2 and may disappear in the future, use Users->getDeveloperModules() instead");

    if (!isset($user)) {
        $modules = array();

        return $modules;
    }

    return($user->getDeveloperModules());

}

 function get_workflow_admin_modules_for_user($user)
 {
    global $moduleList;

    /* Workflow modules blacklist */
    $blacklist = array(
        'iFrames',
        'Feeds',
        'Home',
        'Dashboard',
        'Calendar',
        'Activities',
        'Reports',
        'pmse_Business_Rules', // Process Business Rules
        'pmse_Project', // Process Definitions
        'pmse_Emails_Templates', // Process Emails Templates
        'pmse_Inbox', // Processes
        'DataPrivacy',
    );

    $workflow_mod_list = array();
    foreach ($moduleList as $module) {
        $workflow_mod_list[$module] = $module;
    }

    // This list is taken from the previous version of workflow_utils.php
    $workflow_mod_list['Tasks'] = "Tasks";
    $workflow_mod_list['Calls'] = "Calls";
    $workflow_mod_list['Meetings'] = "Meetings";
    $workflow_mod_list['Notes'] = "Notes";
    $workflow_mod_list['ProjectTask'] = "Project Tasks";
    $workflow_mod_list['Leads'] = "Leads";
    $workflow_mod_list['Opportunities'] = "Opportunities";
    // End of list

    $wfModules = array();
    if (empty($user)) {
        return $wfModules;
    }

    foreach ($workflow_mod_list as $key => $val) {
        if (!in_array($val, $wfModules) && !in_array($val, $blacklist) && $user->isDeveloperForModule($key)) {
            $wfModules[$key] = $val;
        }
    }

    return $wfModules;
}

// Check if user is admin for at least one module.
function is_admin_for_any_module($user)
{
    if (!isset($user)) {
        return false;
    }
    if ($user->isAdmin()) {
        return true;
    }
    $GLOBALS['log']->deprecated("is_admin_for_any_module() is deprecated as of 6.2.2 and may disappear in the future, use Users->isDeveloperForAnyModule() instead");

    return $user->isDeveloperForAnyModule();
    return false;
}


// Check if user is admin for a specific module.
function is_admin_for_module($user,$module)
{
    if (!isset($user)) {
        return false;
    }
    if ($user->isAdmin()) {
        return true;
    }
    $GLOBALS['log']->deprecated("is_admin_for_module() is deprecated as of 6.2.2 and may disappear in the future, use Users->isDeveloperForModule() instead");

    return $user->isDeveloperForModule($module);
    return false;
}


/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user)
{
    if (empty($user)) {
        return false;
    }

    return $user->isAdmin();
}

/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 *
 * @deprecated use SugarThemeRegistry::get($theme)->name instead
 */
function get_theme_display($theme)
{
    return SugarThemeRegistry::get($theme)->name;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 *
 * @deprecated use SugarThemeRegistry::availableThemes() instead.
 */
function get_themes()
{
    return SugarThemeRegistry::availableThemes();
}

/**
 * THIS FUNCTION IS DEPRECATED AND SHOULD NOT BE USED; USE get_select_options_with_id()
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options ($option_list, $selected)
{
    return get_select_options_with_id($option_list, $selected);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id ($option_list, $selected_key)
{
    return get_select_options_with_id_separate_key($option_list, $option_list, $selected_key);
}


/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id_separate_key ($label_list, $key_list, $selected_key, $massupdate=false)
{
    global $app_strings;
    $select_options = "";

    //for setting null selection values to human readable --None--
    $pattern = "/'0?'></";
    $replacement = "''>".$app_strings['LBL_NONE']."<";
    if ($massupdate) {
        $replacement .= "/OPTION>\n<OPTION value='__SugarMassUpdateClearField__'><"; // Giving the user the option to unset a drop down list. I.e. none means that it won't get updated
    }

    if (empty($key_list)) $key_list = array();
    //create the type dropdown domain and set the selected value if $opp value already exists
    foreach ($key_list as $option_key=>$option_value) {

        $selected_string = '';
        // the system is evaluating $selected_key == 0 || '' to true.  Be very careful when changing this.  Test all cases.
        // The bug was only happening with one of the users in the drop down.  It was being replaced by none.
        if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '' && !$massupdate) || (is_array($selected_key) &&  in_array($option_key, $selected_key))) {
            $selected_string = 'selected ';
        }

        $html_value = $option_key;

        $select_options .= "\n<OPTION ".$selected_string."value='$html_value'>$label_list[$option_key]</OPTION>";
    }
    $select_options = preg_replace($pattern, $replacement, $select_options);

    return $select_options;
}

/**
 * Call this method instead of die().
 * We print the error message and then die with an appropriate
 * exit code.
 */
function sugar_die($error_message, $exit_code = 1)
{
    @header("HTTP/1.0 500 Server Error");
    @header("Status: 500 Server Error");
    sugar_cleanup();
    echo $error_message;
    die($exit_code);
}

/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js ()
{
    $the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
function clear_form(form)
{
    var newLoc = 'index.php?action=' + form.action.value + '&module=' + form.module.value + '&query=true&clear_query=true';
    if (typeof(form.advanced) != 'undefined') {
        newLoc += '&advanced=' + form.advanced.value;
    }
    document.location.href= newLoc;
}
</script>
EOQ;

    return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific field in a form
 * when the screen is rendered.  The field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js ()
{
    //TODO Clint 5/20 - Make this function more generic so that it can take in the target form and field names as variables
    $the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!--
function set_focus()
{
    if (document.forms.length > 0) {
        for (i = 0; i < document.forms.length; i++) {
            for (j = 0; j < document.forms[i].elements.length; j++) {
                var field = document.forms[i].elements[j];
                if ((field.type == "text" || field.type == "textarea" || field.type == "password") &&
                        !field.disabled && (field.name == "first_name" || field.name == "name" || field.name == "user_name" || field.name=="document_name")) {
                    field.focus();
                    if (field.type == "text") {
                        field.select();
                    }
                    break;
                }
            }
          }
       }
}
-->
</script>
EOQ;

    return $the_script;
}

/**
 * Very cool algorithm for sorting multi-dimensional arrays.  Found at http://us2.php.net/manual/en/function.array-multisort.php
 * Syntax: $new_array = array_csort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
 * Explanation: $array is the array you want to sort, 'col1' is the name of the column
 * you want to sort, SORT_FLAGS are : SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING
 * you can repeat the 'col',FLAG,FLAG, as often you want, the highest prioritiy is given to
 * the first - so the array is sorted by the last given column first, then the one before ...
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function array_csort()
{
    $args = func_get_args();
    $marray = array_shift($args);
    $i = 0;

    $msortline = "return(array_multisort(";
    foreach ($args as $arg) {
        $i++;
        if (is_string($arg)) {
            foreach ($marray as $row) {
                $sortarr[$i][] = $row[$arg];
            }
        } else {
            $sortarr[$i] = $arg;
        }
        $msortline .= "\$sortarr[".$i."],";
    }
    $msortline .= "\$marray));";

    eval($msortline);

    return $marray;
}

/**
 * Converts localized date format string to jscalendar format
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function parse_calendardate($local_format)
{
    preg_match('/\(?([^-]{1})[^-]*-([^-]{1})[^-]*-([^-]{1})[^-]*\)/', $local_format, $matches);
    $calendar_format = "%" . $matches[1] . "-%" . $matches[2] . "-%" . $matches[3];

    return str_replace(array("y", "ￄ1�7", "a", "j"), array("Y", "Y", "Y", "d"), $calendar_format);
}

function translate($string, $mod='', $selectedValue='')
{
    // Bug 60664
    global $mod_strings;

    if (!empty($mod)) {
        global $current_language;
        //Bug 31275
        $request = InputValidation::getService();
        $current_language = $request->getValidInputRequest('login_language', 'Assert\Language', $current_language);
        $lang = return_module_language($current_language, $mod);

        // Bug 60664 - If module language isn't found, just use mod_strings
        if (empty($lang)) {
            $lang = $mod_strings;
        }
        if ($mod == "")
        echo "Language is <pre>" . $mod_strings . "</pre>";

    } else {
        $lang =  $mod_strings;
    }

    $returnValue = '';
    global $app_strings, $app_list_strings;

    if (isset($lang[$string])) {
        $returnValue = $lang[$string];
    } elseif (isset($mod_strings[$string])) {
        $returnValue = $mod_strings[$string];
    } elseif (isset($app_strings[$string])) {
        $returnValue = $app_strings[$string];
    } elseif (isset($app_list_strings[$string])) {
        $returnValue = $app_list_strings[$string];
    } elseif (isset($app_list_strings['moduleList']) && isset($app_list_strings['moduleList'][$string])) {
        $returnValue = $app_list_strings['moduleList'][$string];
    }

    if (empty($returnValue)) {
        return $string;
    }

    // Bug 48996 - Custom enums with '0' value were not returning because of empty check
    // Added a numeric 0 checker to the conditional to allow 0 value indexed to pass
    if (is_array($returnValue) && (!empty($selectedValue) || (is_numeric($selectedValue) && $selectedValue == 0))  && isset($returnValue[$selectedValue]) ) {
        return $returnValue[$selectedValue];
    }

    return $returnValue;
}

function unTranslateNum($num)
{
        static $dec_sep;
        static $num_grp_sep;
        global $current_user, $sugar_config;

        if ($dec_sep == null) {
            $user_dec_sep = $current_user->getPreference('dec_sep');
            $dec_sep = (empty($user_dec_sep) ? $sugar_config['default_decimal_seperator'] : $user_dec_sep);
        }
        if ($num_grp_sep == null) {
            $user_num_grp_sep = $current_user->getPreference('num_grp_sep');
            $num_grp_sep = (empty($user_num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $user_num_grp_sep);
        }

        $num = preg_replace("'" . preg_quote($num_grp_sep) . "'", '', $num);
        $num = preg_replace("'" . preg_quote($dec_sep) . "'", '.', $num);

        return $num;

}

function add_http($url)
{
    if (!preg_match("@://@i", $url)) {
        $scheme = "http";
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $scheme = 'https';
        }

        return "{$scheme}://{$url}";
    }

    return $url;
}

/**
 * returns a default array of XSS tags to clean
 * @return array
 */
function getDefaultXssTags()
{
    $tmp = array(
    "applet" => "applet",
    "base" => "base",
    "embed" => "embed",
    "form" => "form",
    "frame" => "frame",
    "frameset" => "frameset",
    "iframe" => "iframe",
    "import" => "\?import",
    "layer" => "layer",
    "link" => "link",
    "object" => "object",
    "script" => "script",
    "xmp" => "xmp",
    );

    $ret = base64_encode(serialize($tmp));

    return $ret;
}

/**
 * Remove potential xss vectors from strings
 * @param string str String to search for XSS attack vectors
 * @deprecated
 * @return string
 */
function remove_xss($str)
{
    return SugarCleaner::cleanHtml($str, false);
}

/**
 * Detects typical XSS attack patterns
 * @deprecated
 * @param string str String to search for XSS attack vectors
 * @param bool cleanImg Flag to allow <img> tags to survive - only used by InboundEmail for inline images.
 * @return array Array of matches, empty on clean string
 */
function clean_xss($str, $cleanImg=true)
{
    global $sugar_config;

    if(empty($sugar_config['email_xss']))
    $sugar_config['email_xss'] = getDefaultXssTags();

    $xsstags = unserialize(base64_decode($sugar_config['email_xss']));

    // cn: bug 13079 - "on\w" matched too many non-events (cONTact, strONG, etc.)
    $jsEvents  = "onblur|onfocus|oncontextmenu|onresize|onscroll|onunload|ondblclick|onclick|";
    $jsEvents .= "onmouseup|onmouseover|onmousedown|onmouseenter|onmouseleave|onmousemove|onload|onchange|";
    $jsEvents .= "onreset|onselect|onsubmit|onkeydown|onkeypress|onkeyup|onabort|onerror|ondragdrop";

    $attribute_regex	= "#\b({$jsEvents})\s*=\s*(?|(?!['\"])\S+|['\"].+?['\"])#sim";
    $javascript_regex	= '@<[^/>][^>]+(expression\(|j\W*a\W*v\W*a|v\W*b\W*s\W*c\W*r|&#|/\*|\*/)[^>]*>@sim';
    $imgsrc_regex		= '#<[^>]+src[^=]*=([^>]*?http(s)?://[^>]*)>#sim';
    $css_url			= '#url\(.*\.\w+\)#';

    $tagsrex = '#<\/?(\w+)((?:\s+(?:\w|\w[\w-]*\w)(?:\s*=\s*(?:\".*?\"|\'.*?\'|[^\'\">\s]+))?)+\s*|\s*)\/?>#im';

    $tagmatches = array();
    $matches = array();
    preg_match_all($tagsrex, $str, $tagmatches, PREG_PATTERN_ORDER);
    foreach ($tagmatches[1] as $no => $tag) {
        if (in_array($tag, $xsstags)) {
            // dangerous tag - take out whole
            $matches[] = $tagmatches[0][$no];
            continue;
        }
        $attrmatch = array();
        preg_match_all($attribute_regex, $tagmatches[2][$no], $attrmatch, PREG_PATTERN_ORDER);
        if (!empty($attrmatch[0])) {
            $matches = array_merge($matches, $attrmatch[0]);
        }
    }

    $matches = array_merge($matches, xss_check_pattern($javascript_regex, $str));

    if ($cleanImg) {
        $matches = array_merge($matches,
        xss_check_pattern($imgsrc_regex, $str)
        );
    }

    // cn: bug 13498 - custom white-list of allowed domains that vet remote images
    preg_match_all($css_url, $str, $cssUrlMatches, PREG_PATTERN_ORDER);

    if (isset($sugar_config['security_trusted_domains']) && !empty($sugar_config['security_trusted_domains']) && is_array($sugar_config['security_trusted_domains'])) {
        if (is_array($cssUrlMatches) && count($cssUrlMatches) > 0) {
            // normalize whitelist
            foreach ($sugar_config['security_trusted_domains'] as $k => $v) {
                $sugar_config['security_trusted_domains'][$k] = strtolower($v);
            }

            foreach ($cssUrlMatches[0] as $match) {
                $domain = strtolower(substr(strstr($match, "://"), 3));
                $baseUrl = substr($domain, 0, strpos($domain, "/"));

                if (!in_array($baseUrl, $sugar_config['security_trusted_domains'])) {
                    $matches[] = $match;
                }
            }
        }
    } else {
        $matches = array_merge($matches, $cssUrlMatches[0]);
    }

    return $matches;
}

/**
 * Helper function used by clean_xss() to parse for known-bad vectors
 * @param string pattern Regex pattern to use
 * @param string str String to parse for badness
 * @return array
 */
function xss_check_pattern($pattern, $str)
{
    preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);

    return $matches[1];
}

/**
 * Designed to take a string passed in the URL as a parameter and clean all "bad" data from it
 *
 * @param string $value
 * @param string $filter which corresponds to a regular expression to use; choices are:
 * 		"STANDARD" ( default )
 * 		"STANDARDSPACE"
 * 		"FILE"
 * 		"NUMBER"
 * 		"SQL_COLUMN_LIST"
 * 		"PATH_NO_URL"
 * 		"SAFED_GET"
 * 		"UNIFIED_SEARCH"
 * 		"AUTO_INCREMENT"
 * 		"ALPHANUM"
 * @param boolean $dieOnBadData true (default) if you want to die if bad data if found, false if not
 *
 * @deprecated This is now an integral part of the new Validator service and is implemented
 * in \Sugarcrm\Sugarcrm\Security\Validator\Constraints\LegacyCleanStringValidator.
 */
function clean_string($value, $filter = "STANDARD", $dieOnBadData = true)
{
    global  $sugar_config;

    $filters = Array(
    "STANDARD"        => '#[^A-Z0-9\-_\.\@]#i',
    "STANDARDSPACE"   => '#[^A-Z0-9\-_\.\@\ ]#i',
    "FILE"            => '#[^A-Z0-9\-_\.]#i',
    "NUMBER"          => '#[^0-9\-]#i',
    "SQL_COLUMN_LIST" => '#[^A-Z0-9\(\),_\.]#i',
    "PATH_NO_URL"     => '#://#i',
    "SAFED_GET"		  => '#[^A-Z0-9\@\=\&\?\.\/\-_~+]#i', /* range of allowed characters in a GET string */
    "UNIFIED_SEARCH"	=> "#[\\x00]#", /* cn: bug 3356 & 9236 - MBCS search strings */
    "AUTO_INCREMENT"	=> '#[^0-9\-,\ ]#i',
    "ALPHANUM"        => '#[^A-Z0-9\-]#i',
    );

    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = clean_string($v, $filter, $dieOnBadData);
        }

        return $value;
    }

    if (!is_string($value)) {
        return $value;
    }

    if (preg_match($filters[$filter], $value)) {
        if (isset($GLOBALS['log']) && is_object($GLOBALS['log'])) {
            $GLOBALS['log']->fatal("SECURITY[$filter]: bad data passed in; string: {$value}");
        }
        if ($dieOnBadData) {
            die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
        }

        return false;
    } else {
        return $value;
    }
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation.
 */
function clean_special_arguments()
{
    if (isset($_SERVER['PHP_SELF']) && 'cli' !== PHP_SAPI) {
        if (!empty($_SERVER['PHP_SELF'])) clean_string($_SERVER['PHP_SELF'], 'SAFED_GET');
    }
    if (!empty($_REQUEST) && !empty($_REQUEST['login_theme'])) clean_string($_REQUEST['login_theme'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['login_module'])) clean_string($_REQUEST['login_module'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['login_action'])) clean_string($_REQUEST['login_action'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['authenticated_user_theme'])) clean_string($_SESSION['authenticated_user_theme'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['module_name'])) clean_string($_REQUEST['module_name'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['module'])) clean_string($_REQUEST['module'], "STANDARD");
    if (!empty($_POST) && !empty($_POST['parent_type'])) clean_string($_POST['parent_type'], "STANDARD");
    if (!empty($_REQUEST) && !empty($_REQUEST['mod_lang'])) clean_string($_REQUEST['mod_lang'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['authenticated_user_language'])) clean_string($_SESSION['authenticated_user_language'], "STANDARD");
    if (!empty($_SESSION) && !empty($_SESSION['dyn_layout_file'])) clean_string($_SESSION['dyn_layout_file'], "PATH_NO_URL");
    if (!empty($_GET) && !empty($_GET['from'])) clean_string($_GET['from']);
    if (!empty($_GET) && !empty($_GET['gmto'])) clean_string($_GET['gmto'], "NUMBER");
    if (!empty($_GET) && !empty($_GET['case_number'])) clean_string($_GET['case_number'], "AUTO_INCREMENT");
    if (!empty($_GET) && !empty($_GET['bug_number'])) clean_string($_GET['bug_number'], "AUTO_INCREMENT");
    if (!empty($_GET) && !empty($_GET['quote_num'])) clean_string($_GET['quote_num'], "AUTO_INCREMENT");
    clean_superglobals('stamp', 'ALPHANUM'); // for vcr controls
    clean_superglobals('offset', 'ALPHANUM');
    clean_superglobals('return_action');
    clean_superglobals('return_module');

    return true;
}

/**
 * cleans the given key in superglobals $_GET, $_POST, $_REQUEST
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation.
 */
function clean_superglobals($key, $filter = 'STANDARD')
{
    if(isset($_GET[$key])) clean_string($_GET[$key], $filter);
    if(isset($_POST[$key])) clean_string($_POST[$key], $filter);
    if(isset($_REQUEST[$key])) clean_string($_REQUEST[$key], $filter);
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation.
 */
function set_superglobals($key, $val)
{
    $_GET[$key] = $val;
    $_POST[$key] = $val;
    $_REQUEST[$key] = $val;
}

/**
 * Works in conjunction with clean_string() to defeat SQL injection, file inclusion attacks, and XSS
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation.
 */
function clean_incoming_data()
{
    global $sugar_config;
    global $RAW_REQUEST;

    if (get_magic_quotes_gpc()) {
        // magic quotes screw up data, we'd have to clean up
        $RAW_REQUEST = array_map("cleanup_slashes", $_REQUEST);
    } else {
        $RAW_REQUEST = $_REQUEST;
    }

    if (get_magic_quotes_gpc() == 1) {
        $req  = array_map("preprocess_param", $_REQUEST);
        $post = array_map("preprocess_param", $_POST);
        $get  = array_map("preprocess_param", $_GET);
    } else {

        $req  = array_map("securexss", $_REQUEST);
        $post = array_map("securexss", $_POST);
        $get  = array_map("securexss", $_GET);
    }

    // PHP cannot stomp out superglobals reliably
    foreach ($post as $k => $v) { $_POST[$k] = $v; }
    foreach ($get  as $k => $v) { $_GET[$k] = $v; }
    foreach ($req  as $k => $v) {
         $_REQUEST[$k] = $v;

        //ensure the keys are safe as well.  If mbstring encoding translation is on, the post keys don't
        //get translated, so scrub the data but don't die
        if (ini_get('mbstring.encoding_translation')==='1') {
            securexsskey($k,false);
        } else {
            securexsskey($k,true);
        }

    }
    // Any additional variables that need to be cleaned should be added here
    if (isset($_REQUEST['login_theme'])) clean_string($_REQUEST['login_theme']);
    if (isset($_REQUEST['login_module'])) clean_string($_REQUEST['login_module']);
    if (isset($_REQUEST['login_action'])) clean_string($_REQUEST['login_action']);
    if (isset($_REQUEST['login_language'])) clean_string($_REQUEST['login_language']);
    if (isset($_REQUEST['action'])) clean_string($_REQUEST['action']);
    if (isset($_REQUEST['module'])) clean_string($_REQUEST['module']);
    if (isset($_REQUEST['record'])) clean_string($_REQUEST['record'], 'STANDARDSPACE');
    if (isset($_SESSION['authenticated_user_theme'])) clean_string($_SESSION['authenticated_user_theme']);
    if (isset($_SESSION['authenticated_user_language'])) clean_string($_SESSION['authenticated_user_language']);
    if (isset($_REQUEST['language'])) clean_string($_REQUEST['language']);
    if (isset($sugar_config['default_theme'])) clean_string($sugar_config['default_theme']);
    if (isset($_REQUEST['offset'])) clean_string($_REQUEST['offset']);
    if (isset($_REQUEST['stamp'])) clean_string($_REQUEST['stamp']);

    if (isset($_REQUEST['lvso'])) {
            set_superglobals('lvso', (strtolower($_REQUEST['lvso']) === 'desc')?'desc':'asc');
    }
    // Clean "offset" and "order_by" parameters in URL
    foreach ($_REQUEST as $key => $val) {
        if (str_end($key, "_offset")) {
            clean_string($_REQUEST[$key], "ALPHANUM"); // keep this ALPHANUM for disable_count_query
            set_superglobals($key, $_REQUEST[$key]);
        } elseif (str_end($key, "_ORDER_BY")) {
            clean_string($_REQUEST[$key], "SQL_COLUMN_LIST");
            set_superglobals($key, $_REQUEST[$key]);
        }
    }

    return 0;
}

// Returns TRUE if $str begins with $begin
function str_begin($str, $begin)
{
    return (substr($str, 0, strlen($begin)) == $begin);
}

// Returns TRUE if $str ends with $end
function str_end($str, $end)
{
    return (substr($str, strlen($str) - strlen($end)) == $end);
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation. XSS prevention needs
 * to be performed on the output layer in the form of escaping.
 */
function securexss($value)
{
    if (defined('ENTRY_POINT_TYPE') && constant('ENTRY_POINT_TYPE') == 'api') {
        return $value;
    }
    if (is_array($value)) {
        $new = array();
        foreach ($value as $key=>$val) {
               $new[$key] = securexss($val);
        }

        return $new;
    }
    $value = preg_replace(array('/javascript:/i', '/\0/'), array('java script:', ''), $value);
    $value = preg_replace('/javascript:/i', 'java script:', $value);

    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation. XSS prevention needs
 * to be performed on the output layer in the form of escaping.
 */
function securexsskey($value, $die=true)
{
    global $sugar_config;
    $matches = array();
    preg_match('/[\'"<>]/', $value, $matches);
    if (!empty($matches)) {
        if ($die) {
            die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
        } else {
            unset($_REQUEST[$value]);
            unset($_POST[$value]);
            unset($_GET[$value]);
        }
    }
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation. XSS prevention needs
 * to be performed on the output layer in the form of escaping.
 */
function preprocess_param($value)
{
    if (is_string($value)) {
        if (get_magic_quotes_gpc() == 1) {
            $value = stripslashes($value);
        }

        $value = securexss($value);
    }

    return $value;
}

/**
 * @deprecated Superglobal sanitizing will be completely abandonned. Use the new InputValidation
 * framework in combination with Validator constraints for input validation. XSS prevention needs
 * to be performed on the output layer in the form of escaping.
 */
function cleanup_slashes($value)
{
    if(is_string($value)) return stripslashes($value);

    return $value;
}

function set_register_value($category, $name, $value)
{
    return sugar_cache_put("{$category}:{$name}", $value);
}

function get_register_value($category,$name)
{
    return sugar_cache_retrieve("{$category}:{$name}");
}

function clear_register_value($category,$name)
{
    return sugar_cache_clear("{$category}:{$name}");
}
// this function cleans id's when being imported
function convert_id($string)
{
    return preg_replace_callback( '|[^A-Za-z0-9\-]|',
    create_function(
    // single quotes are essential here,
    // or alternative escape all $ as \$
    '$matches',
    'return ord($matches[0]);'
         ) ,$string);
}

/**
 * @deprecated use SugarTheme::getImage()
 */
function get_image($image,$other_attributes,$width="",$height="",$ext='.gif',$alt="")
{
    return SugarThemeRegistry::current()->getImage(basename($image), $other_attributes, empty($width) ? null : $width, empty($height) ? null : $height, $ext, $alt );
}
/**
 * @deprecated use SugarTheme::getImageURL()
 */
function getImagePath($image_name)
{
    return SugarThemeRegistry::current()->getImageURL($image_name);
}

function getWebPath($relative_path)
{
    //if it has  a :// then it isn't a relative path
    if(substr_count($relative_path, '://') > 0) return $relative_path;
    if(defined('TEMPLATE_URL'))$relative_path = SugarTemplateUtilities::getWebPath($relative_path);

    return $relative_path;
}

function getVersionedPath($path, $additional_attrs='', $time_modified=false)
{
    if(empty($GLOBALS['sugar_config']['js_custom_version'])) $GLOBALS['sugar_config']['js_custom_version'] = 1;

    if (!isset($GLOBALS['js_version_key'])) {
        $GLOBALS['js_version_key'] = get_js_version_key();
    }

    if (!is_scalar($additional_attrs)) {
        $additional_attrs = serialize($additional_attrs);
    }

    if ((inDeveloperMode() || $time_modified) && file_exists($path)) {
        $additional_attrs .= filemtime($path);
    }

    // cutting 2 last chars here because since md5 is 32 chars, it's always ==
    $str = substr(base64_encode(md5("{$GLOBALS['js_version_key']}|{$GLOBALS['sugar_config']['js_custom_version']}|$additional_attrs", true)), 0, -2);
    // remove / - it confuses some parsers
    $str = strtr($str, '/+', '-_');
    if(empty($path)) return $str;

    return $path . "?v=$str";
}

function getVersionedScript($path, $additional_attrs='')
{
    return '<script type="text/javascript" src="'.getVersionedPath($path, $additional_attrs).'"></script>';
}

function getJSPath($relative_path, $additional_attrs='', $time_modified=false)
{
    if(defined('TEMPLATE_URL'))$relative_path = SugarTemplateUtilities::getWebPath($relative_path);

    return getVersionedPath($relative_path, '', $time_modified).(!empty($additional_attrs)?"&$additional_attrs":"");
}

function getSWFPath($relative_path, $additional_params='')
{
    $path = $relative_path;
    if (!empty($additional_params)) {
        $path .= '?' . $additional_params;
    }
    if (defined('TEMPLATE_URL')) {
        $path = TEMPLATE_URL . '/' . $path;
    }

    return $path;
}





function getSQLDate($date_str)
{
    if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/',$date_str,$match)) {
        if ( strlen($match[2]) == 1) {
            $match[2] = "0".$match[2];
        }
        if ( strlen($match[1]) == 1) {
            $match[1] = "0".$match[1];
        }

        return "{$match[3]}-{$match[1]}-{$match[2]}";
    } elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/',$date_str,$match)) {
        if ( strlen($match[2]) == 1) {
            $match[2] = "0".$match[2];
        }
        if ( strlen($match[1]) == 1) {
            $match[1] = "0".$match[1];
        }

        return "{$match[3]}-{$match[1]}-{$match[2]}";
    } else {
        return "";
    }
}

function clone_history(&$db, $from_id,$to_id, $to_type)
{
    global $timedate;
    $old_note_id=null;
    $old_filename=null;
    $tables = array('calls'=>'Call', 'meetings'=>'Meeting', 'notes'=>'Note', 'tasks'=>'Task');

    $location=array('Email'=>"modules/Emails/Email.php",
    'Call'=>"modules/Calls/Call.php",
    'Meeting'=>"modules/Meetings/Meeting.php",
    'Note'=>"modules/Notes/Note.php",
    'Tasks'=>"modules/Tasks/Task.php",
    );


    foreach ($tables as $table=>$bean_class) {

        if (!class_exists($bean_class)) {
            require_once($location[$bean_class]);
        }

        $bProcessingNotes=false;
        if ($table=='notes') {
            $bProcessingNotes=true;
        }
        $query = "SELECT id FROM $table WHERE parent_id='$from_id'";
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            //retrieve existing record.
            $bean = BeanFactory::newBeanByName($bean_class);
            $bean->retrieve($row['id']);
            //process for new instance.
            if ($bProcessingNotes) {
                $old_note_id=$row['id'];
                $old_filename=$bean->filename;
            }
            $bean->id=null;
            $bean->parent_id=$to_id;
            $bean->parent_type=$to_type;
            if ($to_type=='Contacts' and in_array('contact_id',$bean->column_fields)) {
                $bean->contact_id=$to_id;
            }
            $bean->update_date_modified = false;
            $bean->update_modified_by = false;
            if(isset($bean->date_modified))
                $bean->date_modified = $timedate->to_db($bean->date_modified);
            if(isset($bean->date_entered))
                $bean->date_entered = $timedate->to_db($bean->date_entered);
            //save
            $new_id=$bean->save();

            //duplicate the file now. for notes.
            if ($bProcessingNotes && !empty($old_filename)) {
                UploadFile::duplicate_file($old_note_id,$new_id,$old_filename);
            }
            //reset the values needed for attachment duplication.
            $old_note_id=null;
            $old_filename=null;
        }
    }
}

function values_to_keys($array)
{
    $new_array = array();
    if (!is_array($array)) {
        return $new_array;
    }
    foreach ($array as $arr) {
        $new_array[$arr] = $arr;
    }

    return $new_array;
}

function clone_relationship(&$db, $tables = array(), $from_column, $from_id, $to_id)
{
    global $dictionary;
    foreach ($tables as $table) {

        if ($table == 'emails_beans') {
            $query = "SELECT * FROM $table WHERE $from_column=" . $db->quoted($from_id) . " and bean_module='Leads'";
        } else {
            $query = "SELECT * FROM $table WHERE $from_column=" . $db->quoted($from_id);
        }
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            $row[$from_column] = $to_id;
            $row['id'] = create_guid();
            if ($table == 'emails_beans') {
                $row['bean_module'] = 'Contacts';
            }
            $db->insertParams($table, $dictionary[$table]['fields'], $row);
        }
    }
}

function get_unlinked_email_query($type, $bean)
{
    global $current_user;

    $return_array['select']='SELECT emails.id ';
    $return_array['from']='FROM emails ';
    $return_array['where']="";
    $return_array['join'] = " JOIN (select DISTINCT email_id from emails_email_addr_rel eear

    join email_addr_bean_rel eabr on eabr.bean_id ='$bean->id' and eabr.bean_module = '$bean->module_dir' and
    eabr.email_address_id = eear.email_address_id and eabr.deleted=0
    where eear.deleted=0 and eear.email_id not in
    (select eb.email_id from emails_beans eb where eb.bean_module ='$bean->module_dir' and eb.bean_id = '$bean->id')
    ) derivedemails on derivedemails.email_id = emails.id";
    $return_array['join_tables'][0] = '';

    if (isset($type) and !empty($type['return_as_array'])) {
        return $return_array;
    }

    return $return_array['select'] . $return_array['from'] . $return_array['where'] . $return_array['join'] ;
} // fn

function get_emails_by_assign_or_link($params)
{
    $relation = $params['link'];
    $bean = $GLOBALS['app']->controller->bean;
    if (empty($bean->$relation)) {
        $bean->load_relationship($relation);
    }
    if (empty($bean->$relation)) {
        $GLOBALS['log']->error("Bad relation '$relation' for bean '{$bean->object_name}' id '{$bean->id}'");

        return array();
    }
    $rel_module = $bean->$relation->getRelatedModuleName();
    $rel_join = $bean->$relation->getJoin(array(
        'join_table_alias' => 'link_bean',
        'join_table_link_alias' => 'linkt',
    ));
    $rel_join = str_replace("{$bean->table_name}.id", "'{$bean->id}'", $rel_join);
    $return_array['select']='SELECT DISTINCT emails.id ';
    $return_array['from'] = "FROM emails ";
    $return_array['join'] = array();

    // directly assigned emails
    $return_array['join'][] = "
        SELECT
            eb.email_id,
            'direct' source
        FROM
            emails_beans eb
        WHERE
            eb.bean_module = '{$bean->module_dir}'
            AND eb.bean_id = '{$bean->id}'
            AND eb.deleted=0
    ";

    // Related by directly by email
    $return_array['join'][] = "
        SELECT DISTINCT
            eear.email_id,
            'relate' source
        FROM
            emails_email_addr_rel eear
        INNER JOIN
            email_addr_bean_rel eabr
        ON
            eabr.bean_id ='{$bean->id}'
            AND eabr.bean_module = '{$bean->module_dir}'
            AND eabr.email_address_id = eear.email_address_id
            AND eabr.deleted=0
        WHERE
            eear.deleted=0
    ";

    $showEmailsOfRelatedContacts = empty($bean->field_defs[$relation]['hide_history_contacts_emails']);
    if (!empty($GLOBALS['sugar_config']['hide_history_contacts_emails']) && isset($GLOBALS['sugar_config']['hide_history_contacts_emails'][$bean->module_name])) {
        $showEmailsOfRelatedContacts = empty($GLOBALS['sugar_config']['hide_history_contacts_emails'][$bean->module_name]);
    }
    if ($showEmailsOfRelatedContacts) {
        // Assigned to contacts
        $return_array['join'][] = "
            SELECT DISTINCT
                eb.email_id,
                'contact' source
            FROM
                emails_beans eb
            $rel_join AND link_bean.id = eb.bean_id
            WHERE
                eb.bean_module = '$rel_module'
                AND eb.deleted=0
        ";
        // Related by email to linked contact
        $return_array['join'][] = "
            SELECT DISTINCT
                eear.email_id,
                'relate_contact' source
            FROM
                emails_email_addr_rel eear
            INNER JOIN
                email_addr_bean_rel eabr
            ON
                eabr.email_address_id=eear.email_address_id
                AND eabr.bean_module = '$rel_module'
                AND eabr.deleted=0
            $rel_join AND link_bean.id = eabr.bean_id
            WHERE
                eear.deleted=0
        ";
    }

    $return_array['join'] = " INNER JOIN (" . implode(" UNION ", $return_array['join']). ") email_ids ON emails.id=email_ids.email_id ";

    $return_array['where']=" WHERE emails.deleted=0 ";

        $return_array['join_tables'][0] = '';

        if ($bean->object_name == "Case" && !empty($bean->case_number)) {
            $where = str_replace("%1", $bean->case_number, 	$bean->getEmailSubjectMacro());
            $return_array["where"] .= "\n AND (email_ids.source = 'direct' OR emails.name LIKE '%$where%')";
        }

        return $return_array;
}

/**
 * Check to see if the number is empty or non-zero
 * @param $value
 * @return boolean
 **/
function number_empty($value)
{
    return empty($value) && $value != '0';
}

function get_bean_select_array($add_blank=true, $bean_name, $display_columns, $where='', $order_by='', $blank_is_none=false)
{
    $focus = BeanFactory::newBeanByName($bean_name);
    $user_array = array();

    $key = ($bean_name == 'EmailTemplate') ?  $bean_name : $bean_name . $display_columns. $where . $order_by;
    $user_array = get_register_value('select_array', $key );
    if (!$user_array) {

        $db = DBManagerFactory::getInstance();

        $temp_result = Array();
        $query = "SELECT {$focus->table_name}.id, {$display_columns} as display from {$focus->table_name} ";
        // Bug 36162 - We need to confirm that the user is a member of the team of the item.
        $focus->add_team_security_where_clause($query);
        $query .= "where ";
        if ($where != '') {
            $query .= $where." AND ";
        }

        $query .=  " {$focus->table_name}.deleted=0";

        if ($order_by != '') {
            $query .= " order by {$focus->table_name}.{$order_by}";
        }

        $GLOBALS['log']->debug("get_user_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        if ($add_blank==true) {
            // Add in a blank row
            if ($blank_is_none == true) { // set 'blank row' to "--None--"
                global $app_strings;
                $temp_result[''] = $app_strings['LBL_NONE'];
            } else {
                $temp_result[''] = '';
            }
        }

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            $temp_result[$row['id']] = $row['display'];
        }

        $user_array = $temp_result;
        set_register_value('select_array', $key ,$temp_result);
    }

    return $user_array;

}
/**
 *
 *
 * @param unknown_type $listArray
 */
// function parse_list_modules
// searches a list for items in a user's allowed tabs and returns an array that removes unallowed tabs from list
function parse_list_modules(&$listArray)
{
    global $modListHeader;
    $returnArray = array();

    foreach ($listArray as $optionName => $optionVal) {
        if (array_key_exists($optionName, $modListHeader)) {
            $returnArray[$optionName] = $optionVal;
        }
        // special case for products
        if (array_key_exists('Products', $modListHeader)) {
            $returnArray['ProductTemplates'] = $listArray['ProductTemplates'];
        }

        // special case for projects
        if (array_key_exists('Project', $modListHeader)) {
            $returnArray['ProjectTask'] = $listArray['ProjectTask'];
        }
    }
    $returnArray = SugarACL::filterModuleList($listArray, 'access', true);
    asort($returnArray);

    return $returnArray;
}

function display_notice($msg = false)
{
    global $error_notice;
    //no error notice - lets just display the error to the user
    if (!isset($error_notice)) {
        echo '<br>'.$msg . '<br>';
    } else {
        $error_notice .= $msg . '<br>';
    }
}

/* checks if it is a number that at least has the plus at the beginning.
 */
function skype_formatted($number)
{
    //kbrill - BUG #15375
    if (isset($_REQUEST['action']) && $_REQUEST['action']=="Popup") {
        return false;
    } else {
        return substr($number, 0, 1) == '+' || substr($number, 0, 2) == '00' || substr($number, 0, 3) == '011';
    }
}

function format_skype($number)
{
    return preg_replace('/[^\+0-9]/','',$number);
}

function insert_charset_header()
{
    header('Content-Type: text/html; charset=UTF-8');
}

function getCurrentURL()
{
    $href = "http:";
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $href = 'https:';
    }

    $href.= "//".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];

    return $href;
}

function javascript_escape($str)
{
    $new_str = '';

    for ($i = 0; $i < strlen($str); $i++) {

        if (ord(substr($str, $i, 1))==10) {
            $new_str .= '\n';
        } elseif (ord(substr($str, $i, 1))==13) {
            $new_str .= '\r';
        } else {
            $new_str .= $str{$i};
        }
    }

    $new_str = str_replace("'", "\\'", $new_str);

    return $new_str;
}

function js_escape($str, $keep=true)
{
    $str = html_entity_decode(str_replace("\\", "", $str), ENT_QUOTES);

    if ($keep) {
        $str = javascript_escape($str);
    } else {
        $str = str_replace("'", " ", $str);
        $str = str_replace('"', " ", $str);
    }

    return $str;

    //end function js_escape
}

function br2nl($str)
{
    $regex = "#<[^>]+br.+?>#i";
    preg_match_all($regex, $str, $matches);

    foreach ($matches[0] as $match) {
        $str = str_replace($match, "<br>", $str);
    }

    $brs = array('<br>','<br/>', '<br />');
    $str = str_replace("\r\n", "\n", $str); // make from windows-returns, *nix-returns
    $str = str_replace("\n\r", "\n", $str); // make from windows-returns, *nix-returns
    $str = str_replace("\r", "\n", $str); // make from windows-returns, *nix-returns
    $str = str_ireplace($brs, "\n", $str); // to retrieve it

    return $str;
}

/**
 * Convert New Lines to Html
 * This works slightly different than PHP's builtin nl2br in that it replaces
 * the New Lines with the <br /> tags rather than simply inserting a <br /> tag.
 * Since the <br /> characters are a replacement for the existing New line character(s),
 * the function can be safely called multiple times while processing a source string
 * without inadvertently adding additional blank lines.
 */
function nl2html($str)
{
    return str_replace(["\r\n", "\n\r", "\n", "\r"], '<br />', $str);
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The ppd stands for Pre Print Die.
 */
function _ppd($mixed)
{
}

/**
 * Private helper function for displaying the contents of a given variable in
 * the Logger. This function is only intended to be used for SugarCRM internal
 * development. The pp stands for Pre Print.
 * @param $mixed var to print_r()
 * @param $die boolean end script flow
 * @param $displayStackTrace also show stack trace
 */
function _ppl($mixed, $die=false, $displayStackTrace=false, $loglevel="fatal")
{
    if (!isset($GLOBALS['log']) || empty($GLOBALS['log'])) {

        $GLOBALS['log'] = LoggerManager :: getLogger('SugarCRM');
    }

    $mix	= print_r($mixed, true); // send print_r() output to $mix
    $stack	= debug_backtrace();

    $GLOBALS['log']->$loglevel('------------------------------ _ppLogger() output start -----------------------------');
    $GLOBALS['log']->$loglevel($mix);
    if ($displayStackTrace) {
        foreach ($stack as $position) {
            if (isset($position['file']) && isset($position['line'])) {
                $GLOBALS['log']->$loglevel($position['file']."({$position['line']})");
            }
        }
    }

    $GLOBALS['log']->$loglevel('------------------------------ _ppLogger() output end -----------------------------');
    $GLOBALS['log']->$loglevel('------------------------------ _ppLogger() file: '.$stack[0]['file'].' line#: '.$stack[0]['line'].'-----------------------------');

    if ($die) {
        die();
    }
}

/**
 * private helper function to quickly show the major, direct, field attributes of a given bean.
 * The ppf stands for Pre[formatted] Print Focus [object]
 * @param object bean The focus bean
 */
function _ppf($bean, $die=false)
{
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print.
 */
function _pp($mixed)
{
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print.
 */
function _pstack_trace($mixed=null)
{
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print Trace.
 */
function _ppt($mixed, $textOnly=false)
{
}

/**
 * Private helper function for displaying the contents of a given variable.
 * This function is only intended to be used for SugarCRM internal development.
 * The pp stands for Pre Print Trace Die.
 */
function _pptd($mixed)
{
}

/**
 * Private helper function for decoding javascript UTF8
 * This function is only intended to be used for SugarCRM internal development.
 */
function decodeJavascriptUTF8($str)
{
}

/**
 * Will check if a given PHP version string is supported (tested on this ver),
 * unsupported (results unknown), or invalid (something will break on this
 * ver).  Do not pass in any pararameter to default to a check against the
 * current environment's PHP version.
 *
 * @return 1 implies supported, -1 implies unsupported
 */
function check_php_version($sys_php_version = '')
{
    $sys_php_version = empty($sys_php_version) ? constant('PHP_VERSION') : $sys_php_version;

    if (version_compare($sys_php_version, '7.1.0', '<')) {
        return -1;
    }

    if (version_compare($sys_php_version, '7.2.0-dev', '>=')) {
        return -1;
    }

    return 1;
}

/**
 * Will check if a given IIS version string is supported (tested on this ver),
 * unsupported (results unknown), or invalid (something will break on this
 * ver).
 *
 * @return 1 implies supported, 0 implies unsupported, -1 implies invalid
 */
function check_iis_version($sys_iis_version = '')
{
    $server_software = $_SERVER["SERVER_SOFTWARE"];
    $iis_version = '';
    if(strpos($server_software,'Microsoft-IIS') !== false && preg_match_all("/^.*\/(\d+\.?\d*)$/",  $server_software, $out))
        $iis_version = $out[1][0];

    $sys_iis_version = empty($sys_iis_version) ? $iis_version : $sys_iis_version;

    // versions below $min_considered_iis_version considered invalid by default,
    // versions equal to or above this ver will be considered depending
    // on the rules that follow
    $min_considered_iis_version = '6.0';

    // only the supported versions,
    // should be mutually exclusive with $invalid_iis_versions
    $supported_iis_versions = array ('6.0', '7.0',);
    $unsupported_iis_versions = array();
    $invalid_iis_versions = array('5.0',);

    // default unsupported
    $retval = 0;

    // versions below $min_considered_iis_version are invalid
    if (1 == version_compare($sys_iis_version, $min_considered_iis_version, '<')) {
        $retval = -1;
    }

    // supported version check overrides default unsupported
    foreach ($supported_iis_versions as $ver) {
        if (1 == version_compare($sys_iis_version, $ver, 'eq') || strpos($sys_iis_version,$ver) !== false) {
            $retval = 1;
            break;
        }
    }

    // unsupported version check overrides default unsupported
    foreach ($unsupported_iis_versions as $ver) {
        if (1 == version_compare($sys_iis_version, $ver, 'eq') && strpos($sys_iis_version,$ver) !== false) {
            $retval = 0;
            break;
        }
    }

    // invalid version check overrides default unsupported
    foreach ($invalid_iis_versions as $ver) {
        if (1 == version_compare($sys_iis_version, $ver, 'eq') && strpos($sys_iis_version,$ver) !== false) {
            $retval = -1;
            break;
        }
    }

    return $retval;
}

/**
 * Get Sugar root directory
 * @return string
 */
function sugar_root_dir()
{
    return realpath(dirname(__FILE__) . '/..');
}

/**
 * Clean up Sugar environment
 * @param bool $exit Should we exit() after we're done?
 */
function sugar_cleanup($exit = false)
{
    static $called = false;
    if($called)return;
    $called = true;
    $root_path = sugar_root_dir();
    $paths = explode(PATH_SEPARATOR, get_include_path());
    if (in_array($root_path, $paths) == false) {
        set_include_path($root_path . PATH_SEPARATOR . get_include_path());
    }
    chdir($root_path);

    $container = Container::getInstance();
    $context = $container->get(Context::class);

    if ($context->hasActiveSubject()) {
        $logger = $container->get(LoggerInterface::class . '-security');
        $logger->warning('Security context has active subject during shutdown: ' . json_encode($context));
    }

    global $sugar_config;
    LogicHook::initialize();
    $GLOBALS['logic_hook']->call_custom_logic('', 'server_round_trip');

    //added this check to avoid errors during install.
    if (empty($sugar_config['dbconfig'])) {
        if ($exit) exit; else return;
    }

    Tracker::logPage();
    // Now write the cached tracker_queries
    if (class_exists("TrackerManager")) {
        $trackerManager = TrackerManager::getInstance();
        if ($monitor = $trackerManager->getMonitor('tracker_queries')) {
            $trackerManager->saveMonitor($monitor, true);
        }
    }
    if (!empty($GLOBALS['savePreferencesToDB']) && $GLOBALS['savePreferencesToDB']) {
        if ( isset($GLOBALS['current_user']) && $GLOBALS['current_user'] instanceOf User )
            $GLOBALS['current_user']->savePreferencesToDB();
    }

    //check to see if this is not an `ajax call AND the user preference error flag is set
    if(
        (isset($_SESSION['USER_PREFRENCE_ERRORS']) && $_SESSION['USER_PREFRENCE_ERRORS'])
        && ($_REQUEST['action']!='modulelistmenu' && $_REQUEST['action']!='DynamicAction')
        && ($_REQUEST['action']!='favorites' && $_REQUEST['action']!='DynamicAction')
        && (empty($_REQUEST['to_pdf']) || !$_REQUEST['to_pdf'] )
        && (empty($_REQUEST['sugar_body_only']) || !$_REQUEST['sugar_body_only'] )

    ){
        global $app_strings;
        //this is not an ajax call and the user preference error flag is set, so reset the flag and print js to flash message
        $err_mess = $app_strings['ERROR_USER_PREFS'];
        $_SESSION['USER_PREFRENCE_ERRORS'] = false;
        echo "
        <script>
            ajaxStatus.flashStatus('$err_mess',7000);
        </script>";

    }
    SugarAutoLoader::saveClassMap();

    if (class_exists('DBManagerFactory', false)) {
        DBManagerFactory::disconnectAll();
    }
    if ($exit) {
        exit;
    }
}

/*
 check_logic_hook - checks to see if your custom logic is in the logic file
 if not, it will add it. If the file isn't built yet, it will create the file

 */
function check_logic_hook_file($module_name, $event, $action_array)
{
    $add_logic = false;

    if (file_exists("custom/modules/$module_name/logic_hooks.php")) {

        $hook_array = get_hook_array($module_name);

        if (check_existing_element($hook_array, $event, $action_array)==true) {
            //the hook at hand is present, so do nothing
        } else {
            $add_logic = true;

            $logic_count = 0;
            if (!empty($hook_array[$event])) {
                $logic_count = count($hook_array[$event]);
            }

            if ($action_array[0]=="") {
                $action_array[0] = $logic_count  + 1;
            }
            $hook_array[$event][] = $action_array;

        }
        //end if the file exists already
    } else {
        $add_logic = true;
        if ($action_array[0]=="") {
            $action_array[0] = 1;
        }
        $hook_array = array();
        $hook_array[$event][] = $action_array;
        //end if else file exists already
    }
    if ($add_logic == true) {

        //reorder array by element[0]
        //!!!Finish this above TODO

        $new_contents = replace_or_add_logic_type($hook_array);
        write_logic_file($module_name, $new_contents);
        //end if add_element is true
    }

    //end function check_logic_hook_file
}

function remove_logic_hook($module_name, $event, $action_array)
{
    $add_logic = false;

    if (file_exists("custom/modules/".$module_name."/logic_hooks.php")) {
        // The file exists, let's make sure the hook is there
        $hook_array = get_hook_array($module_name);

        if (check_existing_element($hook_array, $event, $action_array)==true) {
            // The hook is there, time to take it out.

            foreach ($hook_array[$event] as $i => $hook) {
                // We don't do a full comparison below just in case the filename changes
                if ($hook[0] == $action_array[0]
                     && $hook[1] == $action_array[1]
                     && $hook[3] == $action_array[3]
                     && $hook[4] == $action_array[4]) {
                    unset($hook_array[$event][$i]);
                }
            }

            $new_contents = replace_or_add_logic_type($hook_array);
            write_logic_file($module_name, $new_contents);

            LogicHook::refreshHooks();
        }
    }
}

function display_stack_trace($textOnly=false)
{
    $stack  = debug_backtrace();

    echo "\n\n display_stack_trace caller, file: " . $stack[0]['file']. ' line#: ' .$stack[0]['line'];

    if(!$textOnly)
    echo '<br>';

    $first = true;
    $out = '';

    foreach ($stack as $item) {
        $file  = '';
        $class = '';
        $line  = '';
        $function  = '';

        if(isset($item['file']))
        $file = $item['file'];
        if(isset($item['class']))
        $class = $item['class'];
        if(isset($item['line']))
        $line = $item['line'];
        if(isset($item['function']))
        $function = $item['function'];

        if (!$first) {
            if (!$textOnly) {
                $out .= '<font color="black"><b>';
            }

            $out .= $file;

            if (!$textOnly) {
                $out .= '</b></font><font color="blue">';
            }

            $out .= "[L:{$line}]";

            if (!$textOnly) {
                $out .= '</font><font color="red">';
            }

            $out .= "({$class}:{$function})";

            if (!$textOnly) {
                $out .= '</font><br>';
            } else {
                $out .= "\n";
            }
        } else {
            $first = false;
        }
    }

    echo $out;
}

function StackTraceErrorHandler($errno, $errstr, $errfile,$errline, $errcontext)
{
    // prevent handling errors suppressed by @-operator
    // @link http://php.net/set_error_handler#example-470
    if (!($errno & error_reporting())) {
        return;
    }

    $error_msg = " $errstr occurred in <b>$errfile</b> on line $errline [" . date("Y-m-d H:i:s") . ']';
    $halt_script = true;
    switch ($errno) {
        case 2048: return; //depricated we have lots of these ignore them
        case E_USER_NOTICE:
        case E_NOTICE:
            $halt_script = false;
            $type = 'Notice';
            break;
        case E_USER_WARNING:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_WARNING:

            $halt_script = false;
            $type = "Warning";
            break;

        case E_USER_ERROR:
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_ERROR:

            $type = "Fatal Error";
            break;

        case E_PARSE:

            $type = "Parse Error";
            break;

        default:
            //don't know what it is might not be so bad
            $halt_script = false;
            $type = "Unknown Error ($errno)";
            break;
    }
    $error_msg = '<b>'.$type.'</b>:' . $error_msg;
    echo $error_msg;
    display_stack_trace();
    if ($halt_script) {
        exit -1;
    }

}

if (isset($sugar_config['stack_trace_errors']) && $sugar_config['stack_trace_errors']) {

    set_error_handler('StackTraceErrorHandler');
}
function get_sub_cookies($name)
{
    $cookies = array();
    if (isset($_COOKIE[$name])) {
        $subs = explode('#', $_COOKIE[$name]);
        foreach ($subs as $cookie) {
            if (!empty($cookie)) {
                $cookie = explode('=', $cookie);

                $cookies[$cookie[0]] = $cookie[1];
            }
        }
    }

    return $cookies;

}

function mark_delete_components($sub_object_array, $run_second_level=false, $sub_sub_array="")
{
    if (!empty($sub_object_array)) {

        foreach ($sub_object_array as $sub_object) {

            //run_second level is set to true if you need to remove sub-sub components
            if ($run_second_level==true) {

                mark_delete_components($sub_object->get_linked_beans($sub_sub_array['rel_field'],$sub_sub_array['rel_module']));

                //end if run_second_level is true
            }
            $sub_object->mark_deleted($sub_object->id);
            //end foreach sub component
        }
        //end if this is not empty
    }

    //end function mark_delete_components
}

/**
 * For translating the php.ini memory values into bytes.  e.g. input value of '8M' will return 8388608.
 */
function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    $multiplers = array('k' => 1024, 'm' => 1048576, 'g' => 1073741824);
    if (isset($multiplers[$last])) {
        $val = substr($val, 0, -1);
        return $val*$multiplers[$last];
    }

    return $val;
}

/**
 * Adds the href HTML tags around any URL in the $string
 */
function url2html($string)
{
    //
    $return_string = preg_replace('/(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" target="_new"  style="font-weight: normal;">\\1\\2</a>', $string);

    return $return_string;
}
// End customization by Julian

/**
 * tries to determine whether the Host machine is a Windows machine
 */
function is_windows()
{
    static $is_windows = null;
    if (!isset($is_windows)) {
        $is_windows = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN';
    }

    return $is_windows;
}

/**
 * equivalent for windows filesystem for PHP's is_writable()
 * @param string file Full path to the file/dir
 * @return bool true if writable
 */
function is_writable_windows($file)
{
    if ($file{strlen($file)-1}=='/') {
        return is_writable_windows($file.uniqid(mt_rand()).'.tmp');
    }

    // the assumption here is that Windows has an inherited permissions scheme
    // any file that is a descendant of an unwritable directory will inherit
    // that property and will trigger a failure below.
    if (is_dir($file)) {
        return true;
    }

    $file = str_replace("/", '\\', $file);

    if (file_exists($file)) {
        if (!($f = @sugar_fopen($file, 'r+')))
        return false;
        fclose($f);

        return true;
    }

    if(!($f = @sugar_fopen($file, 'w')))

    return false;
    fclose($f);
    unlink($file);

    return true;
}


/**
 * best guesses Timezone based on webserver's TZ settings
 */
function lookupTimezone($userOffset = 0)
{
    return TimeDate::guessTimezone($userOffset);
}

function convert_module_to_singular($module_array)
{
    global $beanList;

    foreach ($module_array as $key => $value) {
        if(!empty($beanList[$value])) $module_array[$key] = $beanList[$value];

        if ($value=="Cases") {
            $module_array[$key] = "Case";
        }
        if ($key=="projecttask") {
            $module_array['ProjectTask'] = "Project Task";
            unset($module_array[$key]);
        }
    }

    return $module_array;

    //end function convert_module_to_singular
}

/*
 * Given the bean_name which may be plural or singular return the singular
 * bean_name. This is important when you need to include files.
 */
function get_singular_bean_name($bean_name)
{
    global $beanFiles, $beanList;
    if (array_key_exists($bean_name, $beanList)) {
        return $beanList[$bean_name];
    } else {
        return $bean_name;
    }
}

/*
 * Given the potential module name (singular name, renamed module name)
 * Return the real internal module name.
 */
function get_module_from_singular($singular)
{
    // find the internal module name for a singular name
    if (isset($GLOBALS['app_list_strings']['moduleListSingular'])) {

        $singular_modules = $GLOBALS['app_list_strings']['moduleListSingular'];

        foreach ($singular_modules as $mod_name=>$sin_name) {
            if ($singular == $sin_name and $mod_name != $sin_name) {
                return $mod_name;
            }
        }
    }

    // find the internal module name for a renamed module
    if (isset($GLOBALS['app_list_strings']['moduleList'])) {

        $moduleList = $GLOBALS['app_list_strings']['moduleList'];

        foreach ($moduleList as $mod_name=>$name) {
            if ($singular == $name and $mod_name != $name) {
                return $mod_name;
            }
        }
    }

    // if it's not a singular name, nor a renamed name, return the original value
    return $singular;
}

function get_label($label_tag, $temp_module_strings)
{
    global $app_strings;
    if (!empty($temp_module_strings[$label_tag])) {

        $label_name = $temp_module_strings[$label_tag];
    } else {
        if (!empty($app_strings[$label_tag])) {
            $label_name = $app_strings[$label_tag];
        } else {
            $label_name = $label_tag;
        }
    }

    return $label_name;

    //end function get_label
}


function search_filter_rel_info(& $focus, $tar_rel_module, $relationship_name)
{
    $rel_list = array();

    foreach ($focus->relationship_fields as $rel_key => $rel_value) {
        if ($rel_value == $relationship_name) {
            $temp_bean = BeanFactory::newBean($tar_rel_module);
            $temp_bean->disable_row_level_security = true;
            $temp_bean->retrieve($focus->$rel_key);
            if ($temp_bean->id!="") {

                $rel_list[] = $temp_bean;

                return $rel_list;
            }
        }
    }

    foreach ($focus->field_defs as $field_name => $field_def) {
        //Check if the relationship_name matches a "relate" field
        if(!empty($field_def['type']) && $field_def['type'] == 'relate'
        && !empty($field_def['id_name']) && !empty($focus->field_defs[$field_def['id_name']])
        && !empty($focus->field_defs[$field_def['id_name']]['relationship'])
        && $focus->field_defs[$field_def['id_name']]['relationship'] == $relationship_name)
        {
            $temp_bean = BeanFactory::newBean($tar_rel_module);
            $temp_bean->disable_row_level_security = true;
            $temp_bean->retrieve($focus->{$field_def['id_name']});
            if ($temp_bean->id!="") {

                $rel_list[] = $temp_bean;

                return $rel_list;
            }
        //Check if the relationship_name matches a "link" in a relate field
        } elseif (!empty($rel_value['link']) && !empty($rel_value['id_name']) && $rel_value['link'] == $relationship_name) {
            $temp_bean = BeanFactory::newBean($tar_rel_module);
            $temp_bean->disable_row_level_security = true;
            $temp_bean->retrieve($focus->{$rel_value['id_name']});
            if ($temp_bean->id!="") {

                $rel_list[] = $temp_bean;

                return $rel_list;
            }
        }
    }

    if ($focus->module_name == "Emails") {
        $focus->fillPrimaryParentFields($tar_rel_module);
    }
    // special case for unlisted parent-type relationships
    if ( !empty($focus->parent_type) && $focus->parent_type == $tar_rel_module && !empty($focus->parent_id)) {
        $temp_bean = BeanFactory::newBean($tar_rel_module);
        $temp_bean->disable_row_level_security = true;
        $temp_bean->retrieve($focus->parent_id);
        if ($temp_bean->id!="") {
            $rel_list[] = $temp_bean;

            return $rel_list;
        }
    }

    return $rel_list;

    //end function search_filter_rel_info
}

/**
 * Get new Bean by module name
 * @deprecated use BeanFactory::getBean
 * @param string $module_name
 * @return SugarBean
 */
function get_module_info($module_name)
{
    return BeanFactory::newBean($module_name);
}

/**
 * In order to have one place to obtain the proper object name. aCase for example causes issues throughout the application.
 * @deprecated use BeanFactory::getObjectName
 * @param string $moduleName
 */
function get_valid_bean_name($module_name)
{
    return BeanFactory::getObjectName($module_name);
}

/**
 * This function returns an array of phpinfo() results that can be parsed and
 * used to figure out what version we run, what modules are compiled in, etc.
 * @param	$level			int		info level constant (1,2,4,8...64);
 * @return	$returnInfo		array	array of info about the PHP environment
 * @author	original by "code at adspeed dot com" Fron php.net
 * @author	customized for Sugar by Chris N.
 */
function getPhpInfo($level=-1)
{
    /**	Name (constant)		Value	Description
        INFO_GENERAL		1		The configuration line, php.ini location, build date, Web Server, System and more.
        INFO_CREDITS		2		PHP Credits. See also phpcredits().
        INFO_CONFIGURATION	4		Current Local and Master values for PHP directives. See also ini_get().
        INFO_MODULES		8		Loaded modules and their respective settings. See also get_loaded_extensions().
        INFO_ENVIRONMENT	16		Environment Variable information that's also available in $_ENV.
        INFO_VARIABLES		32		Shows all predefined variables from EGPCS (Environment, GET, POST, Cookie, Server).
        INFO_LICENSE		64		PHP License information. See also the license FAQ.
        INFO_ALL			-1		Shows all of the above. This is the default value.
     */
    ob_start();
    phpinfo($level);
    $phpinfo = ob_get_contents();
    ob_end_clean();

    $phpinfo	= strip_tags($phpinfo,'<h1><h2><th><td>');
    $phpinfo	= preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$phpinfo);
    $phpinfo	= preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$phpinfo);
    $parsedInfo	= preg_split('/(<h.?>[^<]+<\/h.>)/', $phpinfo, -1, PREG_SPLIT_DELIM_CAPTURE);
    $match		= '';
    $version	= '';
    $returnInfo	= array();

    if (preg_match('/<h1 class\=\"p\">PHP Version ([^<]+)<\/h1>/', $phpinfo, $version)) {
        $returnInfo['PHP Version'] = $version[1];
    }

    for ($i=1; $i<count($parsedInfo); $i++) {
        if (preg_match('/<h.>([^<]+)<\/h.>/', $parsedInfo[$i], $match)) {
            $vName = trim($match[1]);
            $parsedInfo2 = explode("\n",$parsedInfo[$i+1]);

            foreach ($parsedInfo2 AS $vOne) {
                $vPat	= '<info>([^<]+)<\/info>';
                $vPat3	= "/$vPat\s*$vPat\s*$vPat/";
                $vPat2	= "/$vPat\s*$vPat/";

                if (preg_match($vPat3,$vOne,$match)) { // 3cols
                    $returnInfo[$vName][trim($match[1])] = array(trim($match[2]),trim($match[3]));
                } elseif (preg_match($vPat2,$vOne,$match)) { // 2cols
                    $returnInfo[$vName][trim($match[1])] = trim($match[2]);
                }
            }
        } elseif (true) {

        }
    }

    return $returnInfo;
}

/**
 * This function will take a string that has tokens like {0}, {1} and will replace
 * those tokens with the args provided
 *
 * @param string $format String to format
 * @param array  $args   Arguments to replace
 *
 * @return string        Formatted string
 */
function string_format($format, $args)
{
    $result = $format;

    if ( !is_array($args) ) {
        return;
    }

    /** Bug47277 fix.
     * If args array has only one argument, and it's empty, so empty single quotes are used '' . That's because
     * IN () fails and IN ('') works.
     */
    if (count($args) == 1) {
        reset($args);
        $singleArgument = current($args);
        if (empty($singleArgument)) {
            return str_replace("{0}", "''", $result);
        }
    }
    /* End of fix */

    $replaceArray = array();
    foreach ($args as $search => $replace) {
        $replaceArray['{'. $search .'}'] = $replace;
    }

    $result = str_replace(array_keys($replaceArray),array_values($replaceArray),$format);

    return $result;
}

/**
 * Generate a string for displaying a unique bean identifier
 *
 * @param string Bean number
 * @return A formatted string
 * @deprecated
 */
function format_number_display($num)
{
    global $sugar_config;

    if (!$num) {
        return $num;
    }

    return sprintf('%d', $num);
}

function checkLoginUserStatus()
{
}

/**
 * @param	$url URL containing host to append port
 * @param	$port the port number - if '' is passed, no change to url
 * @return	$resulturl the new URL with the port appended to the host
 */
function appendPortToHost($url, $port)
{
    $resulturl = $url;

    // if no port, don't change the url
    if ($port != '') {
        $split = explode("/", $url);
        //check if it starts with http, in case they didn't include that in url
        if (str_begin($url, 'http')) {
            //third index ($split[2]) will be the host
            $split[2] .= ":".$port;
        } else { // otherwise assumed to start with host name
            //first index ($split[0]) will be the host
            $split[0] .= ":".$port;
        }

        $resulturl = implode("/", $split);
    }

    return $resulturl;
}

/**
 * Singleton to return JSON object
 * @return	JSON object
 * @deprecated JSON.php is composed of static methods only and can be called
 * directly as `JSON::encode`, ... JSON class is autoloaded.
 */
function getJSONobj()
{
    static $json = null;
    if (!isset($json)) {
        $json = new JSON();
    }

    return $json;
}

/**
 * Set default php.ini settings for entry points
 */
function setPhpIniSettings()
{
    // http://us3.php.net/manual/en/ref.pcre.php#ini.pcre.backtrack-limit
    // starting with 5.2.0, backtrack_limit breaks JSON decoding
    $backtrack_limit = ini_get('pcre.backtrack_limit');
    if (!empty($backtrack_limit)) {
        ini_set('pcre.backtrack_limit', '-1');
    }
}

/**
 * Replace values from $gimp with values from $dom, but only the keys that are in $gimp are replaced.
 *
 * @param array $gimp the array whose values will be overloaded
 * @param array $dom the array whose values will pwn the gimp's
 * @return array beaten $gimp
 */
function sugarArrayIntersectMerge($gimp, $dom)
{
    if (is_array($gimp) && is_array($dom)) {
        foreach ($gimp as $domKey => $domVal) {
            if (isset($dom[$domKey])) {
                if (is_array($dom[$domKey])) {
                    foreach ($gimp[$domKey] as $key => $value) {
                        if (!empty($dom[$domKey][$key])) { // SP-1818: should not overwrite if string is empty
                            $gimp[$domKey][$key] = $dom[$domKey][$key];
                        }
                    }
                } else {
                    $gimp[$domKey] = $dom[$domKey];
                }
            }
        }
    }

    return $gimp;
}

/**
 * Identical to sugarArrayMerge but with some speed improvements and used specifically to merge
 * language files.  Language file merges do not need to account for null values so we can get some
 * performance increases by using this specialized function. Note this merge function does not properly
 * handle null values.
 *
 * @param $gimp
 * @param $dom
 * @return array
 */
function sugarLangArrayMerge($gimp, $dom)
{
    if (empty($gimp) && is_array($dom))
        return $dom;

    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (isset($gimp[$domKey])) {
                if (is_array($domVal)) {
                    $tempArr = array();
                    foreach ( $domVal as $domArrKey => $domArrVal )
                        if (!empty($domArrVal)) // SP-1818: should not overwrite if string is empty
                            $tempArr[$domArrKey] = $domArrVal;
                    foreach ( $gimp[$domKey] as $gimpArrKey => $gimpArrVal )
                        if ( !isset($tempArr[$gimpArrKey]) )
                            $tempArr[$gimpArrKey] = $gimpArrVal;
                    $domVal = $tempArr;
                }
                if (!empty($domVal)) { // SP-1818: should not overwrite if string is empty
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif (is_array($dom)) {
        return $dom;
    }

    return $gimp;
}
/**
 * like array_merge() but will handle array elements that are themselves arrays;
 * PHP's version just overwrites the element with the new one.
 *
 * @internal Note that this function deviates from the internal array_merge()
 *           functions in that it does does not treat numeric keys differently
 *           than string keys.  Additionally, it deviates from
 *           array_merge_recursive() by not creating an array when like values
 *           found.
 *
 * @param array gimp the array whose values will be overloaded
 * @param array dom the array whose values will pwn the gimp's
 * @return array beaten gimp
 */
function sugarArrayMerge($gimp, $dom)
{
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (array_key_exists($domKey, $gimp)) {
                if (is_array($domVal)) {
                    $tempArr = array();
                    foreach ( $domVal as $domArrKey => $domArrVal )
                        $tempArr[$domArrKey] = $domArrVal;
                    foreach ( $gimp[$domKey] as $gimpArrKey => $gimpArrVal )
                        if ( !array_key_exists($gimpArrKey, $tempArr) )
                            $tempArr[$gimpArrKey] = $gimpArrVal;
                    $gimp[$domKey] = $tempArr;
                } else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif(is_array($dom))
        return $dom;

    return $gimp;
}

/**
 * Similiar to sugarArrayMerge except arrays of N depth are merged.
 *
 * @param array gimp the array whose values will be overloaded
 * @param array dom the array whose values will pwn the gimp's
 * @return array beaten gimp
 */
function sugarArrayMergeRecursive($gimp, $dom)
{
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (array_key_exists($domKey, $gimp)) {
                if (is_array($domVal) && is_array($gimp[$domKey])) {
                    $gimp[$domKey] = sugarArrayMergeRecursive($gimp[$domKey], $domVal);
                } else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif(is_array($dom))
        return $dom;

    return $gimp;
}

/**
 * finds the correctly working versions of PHP-JSON
 * @return bool True if NOT found or WRONG version
 */
function returnPhpJsonStatus()
{
    if (function_exists('json_encode')) {
        $phpInfo = getPhpInfo(8);

        return version_compare($phpInfo['json']['json version'], '1.1.1', '<');
    }

    return true; // not found
}


/**
 * getTrackerSubstring
 *
 * Returns a [number]-char or less string for the Tracker to display in the header
 * based on the tracker_max_display_length setting in config.php.  If not set,
 * or invalid length, then defaults to 15 for COM editions, 30 for others.
 *
 * @param string name field for a given Object
 * @return string [number]-char formatted string if length of string exceeds the max allowed
 */
function getTrackerSubstring($name)
{
    static $max_tracker_item_length;

    //Trim the name
    $name = html_entity_decode($name, ENT_QUOTES, 'UTF-8');
    $strlen = function_exists('mb_strlen') ? mb_strlen($name) : strlen($name);

    global $sugar_config;

    if (!isset($max_tracker_item_length)) {
        if (isset($sugar_config['tracker_max_display_length'])) {
          $max_tracker_item_length = (is_int($sugar_config['tracker_max_display_length']) && $sugar_config['tracker_max_display_length'] > 0 && $sugar_config['tracker_max_display_length'] < 50) ? $sugar_config['tracker_max_display_length'] : 30;
        } else {
          $max_tracker_item_length = 30;
        }
    }

    if ($strlen > $max_tracker_item_length) {
        $chopped = function_exists('mb_substr') ? mb_substr($name, 0, $max_tracker_item_length-3, "UTF-8") : substr($name, 0, $max_tracker_item_length-3);
        $chopped .= "...";
    } else {
        $chopped = $name;
    }

    return $chopped;
}
function generate_search_where ($field_list=array(),$values=array(),&$bean,$add_custom_fields=false,$module='')
{
    $where_clauses= array();
    $like_char='%';
    $table_name=$bean->object_name;
    foreach ($field_list[$module] as $field=>$parms) {
        if (isset($values[$field]) && $values[$field] != "") {
            $operator='like';
            if (!empty($parms['operator'])) {
                $operator=$parms['operator'];
            }
            if (is_array($values[$field])) {
                $operator='in';
                $field_value='';
                foreach ($values[$field] as $key => $val) {
                    if ($val != ' ' and $val != '') {
                        if (!empty($field_value)) {
                            $field_value.=',';
                        }
                        $field_value .= "'".$GLOBALS['db']->quote($val)."'";
                    }
                }
            } else {
                $field_value=$GLOBALS['db']->quote($values[$field]);
            }
            //set db_fields array.
            if (!isset($parms['db_field']) ) {
                $parms['db_field'] = array($field);
            }
            if (isset($parms['my_items']) and $parms['my_items'] == true) {
                global $current_user;
                $field_value = $GLOBALS['db']->quote($current_user->id);
                $operator='=';
            }

            $where='';
            $itr=0;
            if ($field_value != '') {

                foreach ($parms['db_field'] as $db_field) {
                    if (strstr($db_field,'.')===false) {
                        $db_field=$bean->table_name.".".$db_field;
                    }
                    if ($GLOBALS['db']->supports('case_sensitive') &&  isset($parms['query_type']) && $parms['query_type']=='case_insensitive') {
                        $db_field='upper('.$db_field.")";
                        $field_value=strtoupper($field_value);
                    }

                    $itr++;
                    if (!empty($where)) {
                        $where .= " OR ";
                    }
                    switch (strtolower($operator)) {
                        case 'like' :
                            $where .=  $db_field . " like '".$field_value.$like_char."'";
                            break;
                        case 'in':
                            $where .=  $db_field . " in (".$field_value.')';
                            break;
                        case '=':
                            $where .=  $db_field . " = '".$field_value ."'";
                            break;
                    }
                }
            }
            if (!empty($where)) {
                if ($itr>1) {
                    array_push($where_clauses, '( '.$where.' )');
                } else {
                    array_push($where_clauses, $where);
                }
            }
        }
    }
    if ($add_custom_fields) {
        $bean->setupCustomFields($module);
        $bean->custom_fields->setWhereClauses($where_clauses);
    }

    return $where_clauses;
}

function add_quotes($str)
{
    return "'{$str}'";
}

/**
 * This function will rebuild the config file
 * @param	$sugar_config
 * @param	$sugar_version
 * @return	bool true if successful
 */
function rebuildConfigFile($sugar_config, $sugar_version)
{
    // add defaults to missing values of in-memory sugar_config
    $sugar_config = sugarArrayMerge(get_sugar_config_defaults(), $sugar_config );
    if (isset($sugar_config['sugarbeet'])) {
        //$sugar_config['sugarbeet'] is only set in COMM
        unset($sugar_config['sugarbeet']);
    }
    if (isset($sugar_config['disable_team_access_check'])) {
        //$sugar_config['disable_team_access_check'] is a runtime configration,
        //no need to write to config.php
        unset($sugar_config['disable_team_access_check']);
    }
    if (isset($sugar_config['team_based_acl']['enabled_modules'])) {
        // [team_based_acl][enabled_modules] param is designed to be managed by config_override.php
        // see TeamBasedACLConfigurator::saveConfig
        // so empty this current value to use actual value from config_override.php after configs merge
        $sugar_config['team_based_acl']['enabled_modules'] = array();
    }
    // need to override version with default no matter what
    $sugar_config['sugar_version'] = $sugar_version;

    ksort( $sugar_config );

    if ( write_array_to_file( "sugar_config", $sugar_config, "config.php" ) ) {
        return true;
    } else {
        return false;
    }
}

/**
 * Loads clean configuration, not overridden by config_override.php
 *
 * @return array
 */
function loadCleanConfig()
{
    $sugar_config = array();
    require 'config.php';

    return $sugar_config;
}

/**
 * getJavascriptSiteURL
 * This function returns a URL for the client javascript calls to access
 * the site.  It uses $_SERVER['HTTP_REFERER'] in the event that Proxy servers
 * are used to access the site.  Thus, the hostname in the URL returned may
 * not always match that of $sugar_config['site_url'].  Basically, the
 * assumption is that however the user accessed the website is how they
 * will continue to with subsequent javascript requests.  If the variable
 * $_SERVER['HTTP_REFERER'] is not found then we default to old algorithm.
 * @return $site_url The url used to refer to the website
 */
function getJavascriptSiteURL()
{
    global $sugar_config;
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $replacement_url = $url['scheme']."://".$url['host'];
        if(!empty($url['port']))
        $replacement_url .= ':'.$url['port'];
        $site_url = preg_replace('/^http[s]?\:\/\/[^\/]+/',$replacement_url,$sugar_config['site_url']);
    } else {
        $site_url = preg_replace('/^http(s)?\:\/\/[^\/]+/',"http$1://".$_SERVER['HTTP_HOST'],$sugar_config['site_url']);
        if (!empty($_SERVER['SERVER_PORT']) &&$_SERVER['SERVER_PORT'] == '443') {
            $site_url = preg_replace('/^http\:/','https:',$site_url);
        }
    }
    $GLOBALS['log']->debug("getJavascriptSiteURL(), site_url=".  $site_url);

    return $site_url;
}

// works nicely with array_map() -- can be used to wrap single quotes around each element in an array
function add_squotes($str)
{
    return "'" . $str . "'";
}


// recursive function to count the number of levels within an array
function array_depth($array, $depth_count=-1, $depth_array=array())
{
    $depth_count++;
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $depth_array[] = array_depth($value, $depth_count);
        }
    } else {
        return $depth_count;
    }
    foreach ($depth_array as $value) {
        $depth_count = $value > $depth_count ? $value : $depth_count;
    }

    return $depth_count;
}

/**
 * Creates a new Group User
 * @param string $name Name of Group User
 * @return string GUID of new Group User
 */
function createGroupUser($name)
{
    $group = BeanFactory::newBean('Users');
    $group->user_name	= $name;
    $group->last_name	= $name;
    $group->is_group	= 1;
    $group->deleted		= 0;
    $group->status		= 'Active'; // cn: bug 6711
    $group->setPreference('timezone', TimeDate::userTimezone());
    $group->save();

    return $group->id;
}

/*
 * Helper function to locate an icon file given only a name
 * Searches through the various paths for the file
 * @param string iconFileName   The filename of the icon
 * @return string Relative pathname of the located icon, or '' if not found
 */

function _getIcon($iconFileName)
{

    $iconName = "icon_{$iconFileName}.gif";
    $iconFound = SugarThemeRegistry::current()->getImageURL($iconName,false);

    //First try un-ucfirst-ing the icon name
    if ( empty($iconFound) )
        $iconName = "icon_" . strtolower(substr($iconFileName,0,1)).substr($iconFileName,1) . ".gif";
        $iconFound = SugarThemeRegistry::current()->getImageURL($iconName,false);

    //Next try removing the icon prefix
    if ( empty($iconFound) )
        $iconName = "{$iconFileName}.gif";
        $iconFound = SugarThemeRegistry::current()->getImageURL($iconName,false);

    if ( empty($iconFound) )
        $iconName = '';

      return $iconName;
}
/**
 * Function to grab the correct icon image for Studio
 * @param string $iconFileName Name of the icon file
 * @param string $altfilename Name of a fallback icon file (displayed if the imagefilename doesn't exist)
 * @param string $width Width of image
 * @param string $height Height of image
 * @param string $align Alignment of image
 * @param string $alt Alt tag of image
 * @return string $string <img> tag with corresponding image
 */

function getStudioIcon($iconFileName='', $altFileName='', $width='48', $height='48', $align='baseline', $alt='' )
{
    global $app_strings, $theme;

    $iconName = _getIcon($iconFileName);
     if (empty($iconName)) {
         $iconName = _getIcon($altFileName);
         if (empty($iconName)) {
            return $app_strings['LBL_NO_IMAGE'];
         }
     }

    return SugarThemeRegistry::current()->getImage($iconName, "align=\"$align\" border=\"0\"", $width, $height);
}

/**
 * Function to grab the correct icon image for Dashlets Dialog
 * @param string $filename Location of the icon file
 * @param string $module Name of the module to fall back onto if file does not exist
 * @param string $width Width of image
 * @param string $height Height of image
 * @param string $align Alignment of image
 * @param string $alt Alt tag of image
 * @return string $string <img> tag with corresponding image
 */

function get_dashlets_dialog_icon($module='', $width='32', $height='32', $align='absmiddle',$alt='')
{
    global $app_strings, $theme;
     $iconName = _getIcon($module . "_32");
     if (empty($iconName)) {
         $iconName = _getIcon($module);
     }
     if (empty($iconName)) {
         return $app_strings['LBL_NO_IMAGE'];
     }

    return SugarThemeRegistry::current()->getImage($iconName, "align=\"$align\" border=\"0\"", $width, $height);
}

// works nicely to change UTF8 strings that are html entities - good for PDF conversions
function html_entity_decode_utf8($string)
{
    static $trans_tbl;
    // replace numeric entities
    //php will have issues with numbers with leading zeros, so do not include them in what we send to code2utf.
    $string = preg_replace_callback('~&#x0*([0-9a-f]+);~i', function ($matches) {
        return code2utf(hexdec($matches[1]));
    }, $string);
    $string = preg_replace_callback('~&#0*([0-9]+);~', function ($matches) {
        return code2utf($matches[1]);
    }, $string);
    // replace literal entities
    if (!isset($trans_tbl)) {
        $trans_tbl = array();
        foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
            $trans_tbl[$key] = utf8_encode($val);
    }

    return strtr($string, $trans_tbl);
}

// Returns the utf string corresponding to the unicode value
function code2utf($num)
{
    if ($num < 128) return chr($num);
    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    return '';
}


/**
 * Chart dashlet helper function that returns the correct CSS file, dependent on the current theme.
 *
 * @todo this won't work completely right until we impliment css compression and combination
 *       for now, we'll just include the last css file found.
 *
 * @return chart.css file to use
 */
function chartStyle()
{
    return SugarThemeRegistry::current()->getCSSURL('chart.css');
}

/**
 * Chart dashlet helper functions that returns the correct XML color file for charts,
 * dependent on the current theme.
 *
 * @return sugarColors.xml to use
 */
function chartColors()
{
    if (SugarThemeRegistry::current()->getCSSURL('sugarColors.xml')=='')
    return SugarThemeRegistry::current()->getImageURL('sugarColors.xml');
    return SugarThemeRegistry::current()->getCSSURL('sugarColors.xml');
}
/* End Chart Dashlet helper functions */

/**
 * This function is designed to set up the php enviroment
 * for AJAX requests.
 */

function ajaxInit()
{
    ini_set('display_errors', 'false');
}

/**
 * Returns an absolute path from the given path, determining if it is relative or absolute
 *
 * @param  string $path
 * @return string
 */
function getAbsolutePath(
    $path,
    $currentServer = false
    )
{
    $path = trim($path);

    // try to match absolute paths like \\server\share, /directory or c:\
    if ( ( substr($path,0,2) == '\\\\' )
            || ( $path[0] == '/' )
            || preg_match('/^[A-z]:/i',$path)
            || $currentServer )

        return $path;

    return getcwd().'/'.$path;
}

/**
 * Returns the bean object of the given module
 *
 * @deprecated use BeanFactory::getBean
 * @param  string $module
 * @return object
 */
function loadBean($module)
{
    return BeanFactory::newBean($module);
}

/**
 * Checks for hit from a mobile browser
 *
 * @return bool
 */
function checkForMobile()
{
    $ua = empty($_SERVER['HTTP_USER_AGENT']) ? "undefined" : strtolower($_SERVER['HTTP_USER_AGENT']);
    $ac = empty($_SERVER['HTTP_ACCEPT'])? "undefined" : strtolower($_SERVER['HTTP_ACCEPT']);

    $isMobile = false;
    if (strpos($ua, 'blackberry') !== false
        || strpos($ua, 'iphone') !== false
        || strpos($ua, 'ipod') !== false
        || strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
        || strpos($ua, 'sony') !== false
        || strpos($ua, 'symbian') !== false
        || strpos($ua, 'nokia') !== false
        || strpos($ua, 'samsung') !== false
        || strpos($ua, 'mobile') !== false
        || strpos($ua, 'windows ce') !== false
        || strpos($ua, 'epoc') !== false
        || strpos($ua, 'opera mini') !== false
        || strpos($ua, 'nitro') !== false
        || strpos($ua, 'j2me') !== false
        || strpos($ua, 'midp-') !== false
        || strpos($ua, 'cldc-') !== false
        || strpos($ua, 'netfront') !== false
        || strpos($ua, 'mot') !== false
        || strpos($ua, 'up.browser') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'audiovox') !== false
        || strpos($ua, 'ericsson,') !== false
        || strpos($ua, 'panasonic') !== false
        || strpos($ua, 'philips') !== false
        || strpos($ua, 'sanyo') !== false
        || strpos($ua, 'sharp') !== false
        || strpos($ua, 'sie-') !== false
        || strpos($ua, 'portalmmm') !== false
        || strpos($ua, 'blazer') !== false
        || strpos($ua, 'avantgo') !== false
        || strpos($ua, 'danger') !== false
        || strpos($ua, 'palm') !== false
        || strpos($ua, 'series60') !== false
        || strpos($ua, 'palmsource') !== false
        || strpos($ua, 'pocketpc') !== false
        || strpos($ua, 'smartphone') !== false
        || strpos($ua, 'rover') !== false
        || strpos($ua, 'ipaq') !== false
        || strpos($ua, 'au-mic,') !== false
        || strpos($ua, 'alcatel') !== false
        || strpos($ua, 'ericy') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'vodafone/') !== false
        || strpos($ua, 'wap1.') !== false
        || strpos($ua, 'wap2.') !== false
        || strpos($ua, 'iphone') !== false){
        $isMobile = true;
    }

    // iPad should use the normal interface
    if ( isTouchScreen() ) {
        $isMobile = false;
    }

    return $isMobile;
}

/**
 * Returns true if the application is being accessed on a touch screen interface ( like an iPad )
 */
function isTouchScreen()
{
    $ua = empty($_SERVER['HTTP_USER_AGENT']) ? "undefined" : strtolower($_SERVER['HTTP_USER_AGENT']);

    // first check if we have forced use of the touch enhanced interface
    if ( isset($_COOKIE['touchscreen']) && $_COOKIE['touchscreen'] == '1' ) {
        return true;
    }

    // next check if we should use the touch interface with our device
    if ( strpos($ua, 'ipad') !== false ) {
        return true;
    }

    return false;
}

/**
 * Returns the shortcut keys to access the shortcut links.  Shortcut
 * keys vary depending on browser versions and operating systems.
 * @return String value of the shortcut keys
 */
function get_alt_hot_key()
{
    $ua = '';
    if ( isset($_SERVER['HTTP_USER_AGENT']) )
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $isMac = strpos($ua, 'mac') !== false;
    $isLinux = strpos($ua, 'linux') !== false;

    if (!$isMac && !$isLinux && strpos($ua, 'mozilla') !== false) {
       if (preg_match('/firefox\/(\d)?\./', $ua, $matches)) {
             return $matches[1] < 2 ? 'Alt+' : 'Alt+Shift+';
       }
    }

    return $isMac ? 'Ctrl+' : 'Alt+';
}

function can_start_session()
{
    $session_id = session_id();

    return empty($session_id) ? true : false;
}

function load_link_class($properties)
{
    $class = 'Link2';
    if (!empty($properties['link_class']) && !empty($properties['link_file'])) {
        require_once $properties['link_file'];
        $class = $properties['link_class'];
    }

    return $class;
}

function getTeamSetNameJoin($table_name)
{
    return TeamSet::getTeamNameJoinSql($table_name);
}

function inDeveloperMode()
{
    return !empty($GLOBALS['sugar_config']['developerMode']);
}

/**
 * Checks if web resources should be minified
 *
 * @return bool
 */
function shouldResourcesBeMinified()
{
    global $sugar_config;

    if (!isset($sugar_config['minify_resources'])) {
        return !inDeveloperMode();
    }

    return (bool) $sugar_config['minify_resources'];
}

/**
 * Filter the protocol list for inbound email accounts.
 *
 * @param array $protocol
 */
function filterInboundEmailPopSelection($protocol)
{
    if ( !isset($GLOBALS['sugar_config']['allow_pop_inbound']) || ! $GLOBALS['sugar_config']['allow_pop_inbound'] ) {
        if( isset($protocol['pop3']) )
            unset($protocol['pop3']);
    } else
        $protocol['pop3'] = 'POP3';

    return $protocol;
}

/**
 * The function is used because currently we are not supporting mbstring.func_overload
 * For some user using mssql without FreeTDS, they may store multibyte charaters in varchar using latin_general collation. It cannot store so many mutilbyte characters, so we need to use strlen.
 * The varchar in MySQL, Orcale, and nvarchar in FreeTDS, we can store $length mutilbyte charaters in it. we need mb_substr to keep more info.
* @returns the substred strings.
 */
function sugar_substr($string, $length, $charset='UTF-8')
{
    if (mb_strlen($string,$charset) > $length) {
        $string = trim(mb_substr(trim($string),0,$length,$charset));
    }

    return $string;
}

/**
 * The function is used because on FastCGI enviroment, the ucfirst(Chinese Characters) will produce bad charcters.
 * This will work even without setting the mbstring.*encoding
 */
function sugar_ucfirst($string, $charset='UTF-8')
{
    return mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset) . mb_substr($string, 1, mb_strlen($string), $charset);
}

/**
 *
 */
function unencodeMultienum($string)
{
    if (is_array($string)) {
       return $string;
    }
    if (substr($string, 0 ,1) == "^" && substr($string, -1) == "^") {
          // Remove empty values from beginning and end of the string
          $string = preg_replace('/^(\^\^,\^)|(\^,\^\^)$/', '^', $string);

          // Get the inner part of the string without leading|trailing ^ chars
          $string = substr(substr($string, 1), 0, strlen($string) -2);
    }

    return explode('^,^', $string);
}

function encodeMultienumValue($arr)
{
    if (!is_array($arr))
        return $arr;

    if (empty($arr))
        return "";

    $string = "^" . implode('^,^', $arr) . "^";

    return $string;
}

/**
 * create_export_query is used for export and massupdate
 * We haven't handle the these fields: $field['type'] == 'relate' && isset($field['link']
 * This function will correct the where clause and output necessary join condition for them
 * @param $module: the module name
 * @param $searchFields: searchFields which is got after $searchForm->populateFromArray()
 * @param $where: where clauses
 * @return $ret_array['where']: corrected where clause
 * @return $ret_array['join']: extra join condition
 */
function create_export_query_relate_link_patch($module, $searchFields, $where)
{
    if (file_exists('modules/'.$module.'/SearchForm.html')) {
        $ret_array['where'] = $where;

        return $ret_array;
    }
    $seed = BeanFactory::newBean($module);
    foreach ($seed->field_defs as $name=>$field) {

        if ( $field['type'] == 'relate' && isset($field['link']) && !empty($searchFields[$name]['value']) ) {
            $seed->load_relationship($field['link']);
            $params = array();
            if (empty($join_type)) {
                $params['join_type'] = ' LEFT JOIN ';
            } else {
                $params['join_type'] = $join_type;
            }
            if (isset($data['join_name'])) {
                $params['join_table_alias'] = $field['join_name'];
            } else {
                $params['join_table_alias']	= 'join_'.$field['name'];

            }
            if (isset($data['join_link_name'])) {
                $params['join_table_link_alias'] = $field['join_link_name'];
            } else {
                $params['join_table_link_alias'] = 'join_link_'.$field['name'];
            }
            $join = $seed->{$field['link']}->getJoin($params, true);
            $join_table_alias = 'join_'.$field['name'];
            if (isset($field['db_concat_fields'])) {
                $db_field = db_concat($join_table_alias, $field['db_concat_fields']);
                $where = preg_replace('/'.$field['name'].'/', $db_field, $where);
            } else {
                $where = preg_replace('/(^|[\s(])' . $field['name'] . '/', '${1}' . $join_table_alias . '.'.$field['rname'], $where);
            }
        }
    }
    $ret_array = array('where'=>$where, 'join'=> isset($join['join']) ? $join['join'] : '');

    return $ret_array;
}

/**
  * We need to clear all the js cache files, including the js language files  in serval places in MB. So I extract them into a util function here.
  * @Depends on QuickRepairAndRebuild.php
  * @Relate bug 30642  ,23177
  */
function clearAllJsAndJsLangFilesWithoutOutput()
{
        global $current_language , $mod_strings;
        $MBmodStrings = $mod_strings;
        $mod_strings = return_module_language ( $current_language, 'Administration' ) ;
        include_once 'modules/Administration/QuickRepairAndRebuild.php' ;
        $repair = new RepairAndClear();
        $repair->module_list = array();
        $repair->show_output = false;
        $repair->clearJsLangFiles();
        $repair->clearJsFiles();
        $mod_strings = $MBmodStrings;
}

/**
 * This function will allow you to get a variable value from query string
 */
function getVariableFromQueryString($variable, $string)
{
    $matches = array();
    $number = preg_match("/{$variable}=([a-zA-Z0-9_-]+)[&]?/", $string, $matches);
    if ($number) {
        return $matches[1];
    } else {
        return false;
    }
}

/**
 * should_hide_iframes
 * This is a helper method to determine whether or not to show iframes (My Sites) related
 * information in the application.
 *
 * @return boolean flag indicating whether or not iframes module should be hidden
 */
function should_hide_iframes()
{
    return !class_exists("iFrame");
}

/**
 * Given a version such as 5.5.0RC1 return RC. If we have a version such as: 5.5 then return GA
 *
 * @param string $version
 * @return string RC, BETA, GA
 */
function getVersionStatus($version)
{
    if (preg_match('/^[\d\.]+?([a-zA-Z]+?)[\d]*?$/si', $version, $matches)) {
        return strtoupper($matches[1]);
    } else {
        return 'GA';
    }
}

/**
 * Return the numeric portion of a version. For example if passed 5.5.0RC1 then return 5.5. If given
 * 5.5.1RC1 then return 5.5.1
 *
 * @param string $version
 * @return version
 */
function getMajorMinorVersion($version)
{
    if (preg_match('/^([\d\.]+).*$/si', $version, $matches2)) {
        $version = $matches2[1];
        $arr = explode('.', $version);
        if (count($arr) > 2) {
            if ($arr[2] == '0') {
                $version = substr($version, 0, 3);
            }
        }
    }

    return $version;
}

/**
 * Return string composed of seconds & microseconds of current time, without dots
 * @return string
 */
function sugar_microtime()
{
    $now = explode(' ', microtime());
    $unique_id = $now[1].str_replace('.', '', $now[0]);

    return $unique_id;
}

/**
 * Extract urls from a piece of text
 * @param  $string
 * @return array of urls found in $string
 */
function getUrls($string)
{
    $lines = explode("<br>", trim($string));
    $urls = array();
    foreach ($lines as $line) {
        $regex = '/http?\:\/\/[^\" ]+/i';
        preg_match_all($regex, $line, $matches);
        foreach ($matches[0] as $match) {
            $urls[] = $match;
        }
    }

    return $urls;
}


/**
 * Sanitize image file from hostile content
 * @param string $path Image file
 * @param bool $jpeg  Accept only JPEGs?
 */
function verify_image_file($path, $jpeg = false)
{
    if (!empty($path)) {
        if (function_exists('imagepng') && function_exists('imagejpeg') && function_exists('imagecreatefromstring')) {
            $img = @imagecreatefromstring(file_get_contents($path));
            if (!$img) {
                return false;
            }
            $img_size = getimagesize($path);
            $filetype = $img_size['mime'];
            //if filetype is jpeg or if we are only allowing jpegs, create jpg image
            if ($filetype == "image/jpeg" || $jpeg) {
                ob_start();
                imagejpeg($img);
                $image = ob_get_clean();
                // not writing directly because imagejpeg does not work with streams
                if (file_put_contents($path, $image)) {
                    return true;
                }
            } elseif ($filetype == "image/gif") {
                ob_start();
                imagegif($img);
                $image = ob_get_clean();
                if (file_put_contents($path, $image)) {
                    return true;
                }
            } elseif ($filetype == "image/png") {
                // else if the filetype is png, create png
                imagealphablending($img, true);
                imagesavealpha($img, true);
                ob_start();
                imagepng($img);
                $image = ob_get_clean();
                if (file_put_contents($path, $image)) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            // check image manually
            $fp = fopen($path, "rb");
            if (!$fp) {
                return false;
            }
            $data = '';
            // read the whole file in chunks
            while (!feof($fp)) {
                $data .= fread($fp,8192);
            }

            fclose($fp);
            if(preg_match("/<(\?php|html|!doctype|script|body|head|plaintext|table|img |pre(>| )|frameset|iframe|object|link|base|style|font|applet|meta|center|form|isindex)/i",
                 $data, $m)) {
                $GLOBALS['log']->fatal("Found {$m[0]} in $path, not allowing upload");

                return false;
            }

            return true;
        }
    }

    return false;
}

/**
 * Verifies that image has proper MIME type and doesn't contain hostile content
 *
 * @param string $path  Image path
 * @param bool $jpeg_only  Accept only JPEGs?
 *
 * @return bool
 */
function verify_uploaded_image($path, $jpeg_only = false)
{
    if (strpos($path, '..') !== false) {
        return false;
    }

    if (!is_file($path)) {
        return false;
    }

    $supportedTypes = array('image/jpeg');

    if (!$jpeg_only) {
        $supportedTypes[] = 'image/png';
    }

    $img_size = getimagesize($path);

    if (!in_array($img_size['mime'], $supportedTypes)) {
        return false;
    }

    return verify_image_file($path, $jpeg_only);
}

function cmp_beans($a, $b)
{
    global $sugar_web_service_order_by;
    //If the order_by field is not valid, return 0;
    if (empty($sugar_web_service_order_by) || !isset($a->$sugar_web_service_order_by) || !isset($b->$sugar_web_service_order_by)) {
        return 0;
    }
    if (is_object($a->$sugar_web_service_order_by) || is_object($b->$sugar_web_service_order_by)
        || is_array($a->$sugar_web_service_order_by) || is_array($b->$sugar_web_service_order_by))
    {
        return 0;
    }
    if ($a->$sugar_web_service_order_by < $b->$sugar_web_service_order_by) {
        return -1;
    } else {
        return 1;
    }
}

function order_beans($beans, $field_name)
{
    //Since php 5.2 doesn't include closures, we must use a global to pass the order field to cmp_beans.
    global $sugar_web_service_order_by;
    $sugar_web_service_order_by = $field_name;
    usort($beans, "cmp_beans");

    return $beans;
}

/**
 * Return search like string
 *
 * This function takes a user input string and returns a string that contains wild card(s) that can be used in db query.
 * @param string $str  string to be searched
 * @param string $like_char  Database like character, usually '%'
 * @return string Returns a string to be searched in db query
 * @deprecated as of 7.1.5 use DBManagerFactory::getInstance()->sqlLikeString()
 */
function sql_like_string($str, $like_char, $wildcard = '%', $appendWildcard = true)
{
    $GLOBALS['log']->deprecated('sql_like_string() is deprecated as of 7.1.5, use DBManager::getInstance()->sqlLikeString() instead');
    $db = DBManagerFactory::getInstance();
    return $db->sqlLikeString($str, $like_char);
}

/**
 * @param $input - the input string to sanitize
 * @param int $quotes - use quotes
 * @param string $charset - the default charset
 * @param bool $remove - strip tags or not
 * @return string - the sanitized string
 */
function sanitize($input, $quotes = ENT_QUOTES, $charset = 'UTF-8', $remove = false)
{
    return htmlentities($input, $quotes, $charset);
}

/**
 * @return string - the full text search engine name
 * @deprecated
 */
function getFTSEngineType()
{
    $GLOBALS['log']->deprecated('The usage of getFTSEngineType is deprecated');
    if (isset($GLOBALS['sugar_config']['full_text_engine']) && is_array($GLOBALS['sugar_config']['full_text_engine'])) {
        foreach ($GLOBALS['sugar_config']['full_text_engine'] as $name => $defs) {
            return $name;
        }
    }

    return '';
}

/**
 * @param string $optionName - name of the option to be retrieved from app_list_strings
 * @return array - the array to be used in option element
 * @deprecated
 */
function getFTSBoostOptions($optionName)
{
    $GLOBALS['log']->deprecated('The usage of getFTSBoostOptions is deprecated');
    if (isset($GLOBALS['app_list_strings'][$optionName])) {
        return $GLOBALS['app_list_strings'][$optionName];
    } else {
        return array();
    }
}

/**
 * utf8_recursive_encode
 *
 * This function walks through an Array and recursively calls utf8_encode on the
 * values of each of the elements.
 *
 * @param $data Array of data to encode
 * @return utf8 encoded Array data
 */
function utf8_recursive_encode($data)
{
    $result = array();
    foreach ($data as $key=>$val) {
        if (is_array($val)) {
           $result[$key] = utf8_recursive_encode($val);
        } else {
           $result[$key] = utf8_encode($val);
        }
    }

    return $result;
}

/**
 * get_language_header
 *
 * This is a utility function for 508 Compliance.  It returns the lang=[Current Language] text string used
 * inside the <html> tag.  If no current language is specified, it defaults to lang='en'.
 *
 * @return String The lang=[Current Language] markup to insert into the <html> tag
 */
function get_language_header()
{
    return isset($GLOBALS['current_language']) ? "lang='{$GLOBALS['current_language']}'" : "lang='en'";
}


/**
 * get_custom_file_if_exists
 *
 * This function handles the repetitive code we have where we first check if a file exists in the
 * custom directory to determine whether we should load it, require it, include it, etc.  This function returns the
 * path of the custom file if it exists.  It basically checks if custom/{$file} exists and returns this path if so;
 * otherwise it return $file
 *
 * @param $file String of filename to check
 * @return $file String of filename including custom directory if found
 */
function get_custom_file_if_exists($file)
{
    $newfile = SugarAutoLoader::existingCustomOne($file);

    return $newfile?$newfile:$file;
}

/**
 * get_help_url
 *
 * This will return the URL used to redirect the user to the help documentation.
 * It can be overriden completely by setting the custom_help_url or partially by setting the custom_help_base_url
 * in config.php or config_override.php.
 *
 * @param string $send_edition
 * @param string $send_version
 * @param string $send_lang
 * @param string $send_module
 * @param string $send_action
 * @param string $dev_status
 * @param string $send_key
 * @param string $send_anchor
 * @return string the completed help URL
 */
function get_help_url($send_edition = '', $send_version = '', $send_lang = '', $send_module = '', $send_action = '', $dev_status = '', $send_key = '', $send_anchor = '')
{
    global $sugar_config;

    if (!empty($sugar_config['custom_help_url'])) {
        $sendUrl = $sugar_config['custom_help_url'];
    } else {
        if (!empty($sugar_config['custom_help_base_url'])) {
            $baseUrl= $sugar_config['custom_help_base_url'];
        } else {
            $baseUrl = "http://www.sugarcrm.com/crm/product_doc.php";
        }
        $sendUrl = $baseUrl . "?edition={$send_edition}&version={$send_version}&lang={$send_lang}&module={$send_module}&help_action={$send_action}&status={$dev_status}&key={$send_key}";
        if (!empty($send_anchor)) {
            $sendUrl .= "&anchor=".$send_anchor;
        }
    }

    return $sendUrl;
}

/**
 * generateETagHeader
 *
 * This function generates the necessary cache headers for using ETags with dynamic content. You
 * simply have to generate the ETag, pass it in, and the function handles the rest.
 *
 * @param string $etag ETag to use for this content.
 */
function generateETagHeader($etag, $cache_age = 0)
{
    header("Cache-Control: max-age={$cache_age}, private");
    header('Expires: ');
    header("ETag: " . $etag);
    header("Pragma:");
    if (isset($_SERVER["HTTP_IF_NONE_MATCH"])) {
        if ($etag == $_SERVER["HTTP_IF_NONE_MATCH"]) {
            ob_clean();
            header("Status: 304 Not Modified");
            header("HTTP/1.0 304 Not Modified");
            die();
        }
    }
}

/**
 * getReportNameTranslation
 *
 * Translates the report name if a translation exists,
 * otherwise just returns the name
 *
 * @param string $reportName
 * @return string translated report name
 */
function getReportNameTranslation($reportName)
{
    global $current_language;

    // Used for translating reports
    $mod_strings = return_module_language($current_language, 'Reports');

    // Search for the report name in the default language and get the key
    $key = array_search($reportName, return_module_language("", "Reports"));

    // If the key was found, use it to get a translation, otherwise just use report name
    if (!empty($key)) {
        $title = $mod_strings[$key];
    } else {
        $title = $reportName;
    }

    return $title;
}


/**
 * Remove vars marked senstitive from array
 * @param array $defs
 * @param SugarBean|array $data
 * @return mixed $data without sensitive fields
 */
function clean_sensitive_data($defs, $data)
{
    foreach ($defs as $field => $def) {
        if (!empty($def['sensitive'])) {
            if (is_array($data)) {
                $data[$field] = '';
            }
            if ($data instanceof SugarBean) {
                $data->$field = '';
            }
        }
    }

    return $data;
}

/**
 * Return relations with labels for duplicates
 */
function getDuplicateRelationListWithTitle($def, $var_def, $module)
{
    global $current_language;
    $select_array = array_unique($def);
    if (count($select_array) < count($def)) {
        $temp_module_strings = return_module_language($current_language, $module);
        $temp_duplicate_array = array_diff_assoc($def, $select_array);
        $temp_duplicate_array = array_merge($temp_duplicate_array, array_intersect($select_array, $temp_duplicate_array));

        foreach ($temp_duplicate_array as $temp_key => $temp_value) {
            // Don't add duplicate relationships
            if (!empty($var_def[$temp_key]['relationship']) && array_key_exists($var_def[$temp_key]['relationship'], $select_array)) {
                continue;
            }
            $select_array[$temp_key] = $temp_value;
        }

        // Add the relationship name for easier recognition
        foreach ($select_array as $key => $value) {
            $select_array[$key] .= ' (' . $key . ')';
        }
    }

    asort($select_array);

    return $select_array;
}

/**
 * Gets the list of "*type_display*".
 *
 * @return array
 */
function getTypeDisplayList()
{
    return array('record_type_display', 'parent_type_display', 'record_type_display_notes');
}

/**
 * Breaks given string into substring according
 * to 'db_concat_fields' from field definition
 * and assigns values to corresponding properties
 * of bean.
 *
 * @param SugarBean $bean
 * @param array $fieldDef
 * @param string $value
 */
function assignConcatenatedValue(SugarBean $bean, $fieldDef, $value)
{
    $valueParts = explode(' ',$value);
    $valueParts = array_filter($valueParts);
    $fieldNum   = count($fieldDef['db_concat_fields']);

    if (count($valueParts) == 1 && $fieldDef['db_concat_fields'] == array('first_name', 'last_name')) {
        $bean->last_name = $value;
    }
    else {
        for ($i = 0; $i < $fieldNum; $i++) {
            $fieldValue = array_shift($valueParts);
            $fieldName  = $fieldDef['db_concat_fields'][$i];
            $bean->$fieldName = $fieldValue !== false ? $fieldValue : '';
        }

        if (!empty($valueParts)) {
            $bean->$fieldName .= ' ' . implode(' ', $valueParts);
        }
    }
}

/**
 * Ensures that the sugarcrm/cache has been built. If not, this builds.
 * @param SugarMinifyUtils $minifyUtils
 * @param string $rootDir Path to start from.
 * @return  string The path to sugar sidecar min file.
 */
function ensureCache($minifyUtils, $rootDir)
{
    $sapiName = php_sapi_name();
    if ($sapiName !== 'cli') {
        $_REQUEST['root_directory'] = $rootDir;
    }
    $sugarSidecarPath = $rootDir . '/cache/include/javascript/sugar_sidecar.min.js';
    if (!file_exists($sugarSidecarPath) || !is_file($sugarSidecarPath)) {
        require_once 'jssource/JSGroupings.php';
        $minifyUtils->ConcatenateFiles($rootDir);
    }

    return $sugarSidecarPath;
}

/**
 * Ensures that a javascript cache file exists and if it doesn't, will create
 * the necessary JS Groupings files into the cache directory for consumption.
 *
 * @param array $files The basename of the file to check or an array of names to check
 * @param string $root The directory off which the cache file path lives
 * @param boolean $addPath Adds the path to the cache for this file if true
 * @return string|array The path to the single file specified, or the collection of files specified
 */
function ensureJSCacheFilesExist($files = array(), $root = '.', $addPath = true)
{
    // Create a reasonable default. Using sugar_sidecar since that is what was
    // used in ensureCache as a default
    if (empty($files)) {
        $files = array('sugar_sidecar.min.js');
    }

    // Make this an array to facilitate more than one file check
    if (!is_array($files)) {
        $files = array($files);
    }

    // Prepare the return
    $return = array();

    // Check existence of files to allow building only when necessary
    $cacheExists = true;
    foreach ($files as $f) {
        // Build the path to the file we are looking for
        if ($addPath) {
            $path = "$root/" . sugar_cached("include/javascript/$f");
        } else {
            $path = "$root/$f";
        }

        // Keep track of existence
        $cacheExists = $cacheExists && file_exists($path) && is_file($path);

        // Add this file to the return stack
        $return[] = $path;
    }

    // If even one of the files doesn't exist, rebuild
    if (!$cacheExists) {

        // Safe $_REQUEST['root_directory']
        $inputValidation = InputValidation::getService();

        // Maintain state as well as possible
        $rd = $inputValidation->getValidInputRequest('root_directory', 'Assert\File');

        // FIXME: setting $_REQUEST parameters ourselves ...
        // Build the concatenated files
        $_REQUEST['root_directory'] = $root;
        $minifyUtils = new SugarMinifyUtils();
        $minifyUtils->ConcatenateFiles($root);

        // FIXME: setting $_REQUEST parameters ourselves ...
        // Cleanup the root directory request var if it was found. If it was null
        // this will in effect unset it
        $_REQUEST['root_directory'] = $rd;
    }

    // For a single file check return just that file
    if (count($return) == 1) {
        return $return[0];
    }

    // For multiple file checks return all files
    return $return;
}

/**
 * Checks backward compatibility state of a module
 *
 * @param  string  $module The name of the module to check
 * @return boolean
 */
function isModuleBWC($module)
{
    global $bwcModules;

    return in_array($module, $bwcModules);
}

/**
 * Translate a bwc action to a sidecar one.
 *
 * @param string $action The action to find a sidecar mapped one.
 * @return string The mapped action or the action given if no action map was
 *   found.
 */
function translateToSidecarAction($action)
{
    static $bwcActionMap = array(
        'index' => '',
        'DetailView' => '',
        'EditView' => 'edit',
    );

    if (isset($bwcActionMap[$action])) {
        $action = $bwcActionMap[$action];
    }
    return $action;
}

/**
 * Builds a sidecar route from a BWC page.
 *
 * This will NOT check if the module is in BWC or not, it will trigger the
 * navigation to sidecar url.
 * @see isModuleBWC() to check if a module is in BWC or not.
 * This expects the sidecar actions.
 * @see translateToSidecarAction() if you need to know which action to map to.
 *
 * @param string $module The sidecar module to return to.
 * @param string $id (optional) The record id to return to.
 * @param string $action (optional) The action to return to.
 * @return string The sidecar route based on the arguments given.
 */
function buildSidecarRoute($module, $id = null, $action = null)
{
    $route = $module;
    if (!empty($id)) {
        $route .= "/$id";
    }
    if (!empty($action)) {
        $route .= "/$action";
    }
    return $route;
}

/**
 * Navigate to a sidecar url from a BWC page.
 *
 * @see buildSidecarRoute() to build the url param to pass to this function.
 *
 * @param string $url The sidecar url to navigate to.
 * @return string The script to use for a sidecar navigation
 */
function navigateToSidecar($url)
{
    return "parent.SUGAR.App.router.navigate('$url', {trigger: true});";
}

/**
 * Gets the list of required dropdown list items, keyed on dropdown list name
 *
 * @return array List of all required dropdown list items
 */
function getRequiredDropdownListItems()
{
    $required = array();
    $files = SugarAutoLoader::existingCustom('include/required_list_items.php');
    foreach ($files as $file) {
        require $file;
        if (isset($app_list_strings_required)) {
            $required = array_merge($required, $app_list_strings_required);
        }
    }
    return $required;
}

/**
 * Gets the list of required items for a named dropdown list
 *
 * @param string $name The name of the dropdown list
 * @return array The list of required items for a dropdown list, if they exist
 */
function getRequiredDropdownListItemsByDDL($name) {
    $items = getRequiredDropdownListItems();
    return isset($items[$name]) ? $items[$name] : array();
}

/**
 * Generates version key for web assets
 *
 * @return string
 */
function get_js_version_key()
{
    $string = '';
    if (isset($GLOBALS['sugar_config']['unique_key'])) {
        $string .= $GLOBALS['sugar_config']['unique_key'];
    }
    if (isset($GLOBALS['sugar_version'])) {
        $string .= $GLOBALS['sugar_version'];
    }
    if (isset($GLOBALS['sugar_flavor'])) {
        $string .= $GLOBALS['sugar_flavor'];
    }

    return md5($string);
}

/**
 * Evaluates a function defined in vardefs.
 * @param mixed $bean
 * @param mixed $function
 * @param array $args
 * @return mixed
 */
function getFunctionValue($bean, $function, $args = array())
{
    if (is_string($bean) && !empty($bean)) {
        $bean = BeanFactory::newBean($bean);
    }

    if (is_array($function)) {
        if (!empty($function['include'])) {
            require_once($function['include']);
        }

        if (!empty($function['name'])) {
            $function = $function['name'];
        }
    }

    if (!empty($bean)) {
        $function = array($bean, $function);
    }

    return call_user_func_array($function, $args);
}

/**
 * Get options for a field, based on it's definition.
 * @param array $vardef Field definition.
 * @return array|false Options for the field, or false otherwise.
 */
function getOptionsFromVardef($vardef)
{
    if (!empty($vardef['function'])) {
        if (!empty($vardef['function']['returns']) && !strcmp($vardef['function']['returns'], 'html')) {
            return false;
        }
        return getFunctionValue(
            isset($vardef['function_bean']) ? $vardef['function_bean'] : null,
            $vardef['function']
        );
    } elseif (isset($vardef['options'])) {
        return translate($vardef['options'], isset($vardef['module']) ? $vardef['module'] : '');
    }
    return false;
}

/**
 * Evaluates if a value is isTruthy
 * @param mixed $value
 * @return bool
 */
function isTruthy($value)
{
    return ($value === true || $value === 'true' || $value === 1 || $value === '1' || $value === 'on' || $value === 'yes') ? true : false;
}

/**
 * Warn a message to log
 * @param string $param
 * @param array $backtrace
 */
function sugar_upgrade_print($param, $backtrace)
{
    if ($backtrace) {
        $GLOBALS['log']->warn("Found {$param} in file: {$backtrace['file']}, line: {$backtrace['line']}");
    }
}

/**
 * Replace base print_r function call
 */
function sugar_upgrade_print_r()
{
    sugar_upgrade_print('print_r', getBacktraceData(__FUNCTION__));
}

/**
 * Replace base var_dump function call
 */
function sugar_upgrade_var_dump()
{
    sugar_upgrade_print('var_dump', getBacktraceData(__FUNCTION__));
}

/**
 * Replace base echo function call
 */
function sugar_upgrade_echo()
{
    sugar_upgrade_print('echo', getBacktraceData(__FUNCTION__));
}

/**
 * Replace base exit function call
 */
function sugar_upgrade_exit()
{
    sugar_upgrade_print('exit', getBacktraceData(__FUNCTION__));
}

/**
 * Replace base die function call
 */
function sugar_upgrade_die()
{
    sugar_upgrade_print('die', getBacktraceData(__FUNCTION__));
}

/**
 * Getting backtrace information for called function
 * @param $function
 * @return bool | array
 */
function getBacktraceData($function) {
    if(inDeveloperMode()) {
        $backtrace = debug_backtrace();
        foreach($backtrace as $backtraceItem) {
            if ($backtraceItem['function'] == $function) {
                return $backtraceItem;
            }
        }
    }
    return false;
}

/**
 * Parses shorthand bytes value
 *
 * @param string $string
 *
 * @return int|null
 * @link http://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
 */
function parseShorthandBytes($string)
{
    if (preg_match('/^(\d+)/', $string, $matches)) {
        $value = (int) $matches[1];
        $modifier = substr($string, -1);
        switch (strtoupper($modifier)) {
            case 'G':
                $value *= 1024;
                // no break
            case 'M':
                $value *= 1024;
                // no break
            case 'K':
                $value *= 1024;
        }

        return $value;
    }

    return null;
}


/**
 * Given a string with variables in the format [var], replace those variables with values pulled from $fields
 *
 * @param string $subject String with variables to replace
 * @param array  $fields Key=>Value pairs for variable replacements
 * @param bool   $use_curly If true, use {variable} syntax instead of [variable]
 *
 * @return String
 */
function replace_sugar_vars($subject, $fields, $use_curly = false)
{
    $lDelim = "[";
    $rDelim = "]";
    if ($use_curly) {
        $lDelim = "{";
        $rDelim = "}";
    }
    $matches = array();
    $count = preg_match_all('/\\' . $lDelim . '([^\\' . $rDelim . ']*)\\' . $rDelim . '/', $subject, $matches);
    //echo "\nfor $subject got matches \n" . print_r($matches, true) . "\nand the fields are\n" . print_r($fields, true);
    for ($i = 0; $i < $count; $i++) {
        $match = $matches[1][$i];
        //List views will have fields be an array where all the keys are upper case and the values are jsut strings
        if (!isset($fields[$match]) && isset($fields[strtoupper($match)])) {
            $match = strtoupper($match);
        }

        $value = isset($fields[$match]) ? $fields[$match] : null;
        if (!is_null($value)) {
            if (is_array($value) && isset($value['value'])) {
                $value = $value['value'];
            }

            if (isset($fields[$match]['type']) && $fields[$match]['type'] == 'enum'
                && isset($fields[$match]['options']) && isset($fields[$match]['options'][$value])
            ) {
                $subject = str_replace($matches[0][$i], $fields[$match]['options'][$value], $subject);
            } else {
                $subject = str_replace($matches[0][$i], $value, $subject);
            }
        }
    }
    return $subject;
}

/**
 * Returns an array of dropdown keys which are to be merged if custom language files or extensions are present
 *
 * @return array
 */
function getExemptDropdowns()
{
    return array_merge(
        array('moduleList', 'moduleListSingular'),
        getTypeDisplayList()
    );
}

/**
 * Sorts file paths and returns sorted copy.
 * Logic here is to take the newest touched file and read it last. This allows
 * for customizations from any source and the most recent change to win out.
 * If files has the same modification time original order will be preserved.
 *
 * @param array $files File paths
 * @return array Sorted file paths
 */
function sortExtensionFiles(array $files)
{
    $index = 0;
    $sorted = array();

    foreach ($files as $path) {
        $sorted[$path] = array(
            'is_override' => substr(basename($path), 0, 9) === '_override',
            'mtime' => filemtime($path),
            'index' => $index++,
        );
    }

    uasort($sorted, function ($a, $b) {
        if ($a['is_override'] != $b['is_override']) {
            return $a['is_override'] - $b['is_override'];
        }

        if ($a['mtime'] != $b['mtime']) {
            return $a['mtime'] - $b['mtime'];
        }

        return $a['index'] - $b['index'];
    });

    return array_keys($sorted);
}

/**
 * Make sure a user isn't stealing sessions so check the ip to ensure that the ip address hasn't dramatically changed
 *
 * @param string $clientIp Client IP address
 * @param string $sessionIp Session IP address
 * @return bool
 */
function validate_ip($clientIp, $sessionIp)
{
    global $sugar_config;

    // check to see if config entry is present
    if (isset($sugar_config['verify_client_ip']) && !$sugar_config['verify_client_ip']) {
        return true;
    }

    $isValidIP = true;

    $classCheck = 0;

    $session_parts = explode(".", $sessionIp);
    $client_parts = explode(".", $clientIp);
    if (count($session_parts) < 4) {
        $classCheck = 0;
    } else {
        // match class C IP addresses
        for ($i = 0; $i < 3; $i ++) {
            if ($session_parts[$i] == $client_parts[$i]) {
                $classCheck = 1;
                continue;
            } else {
                $classCheck = 0;
                break;
            }
        }
    }

    // we have a different IP address
    if ($sessionIp != $clientIp && empty($classCheck)) {
        $isValidIP = false;
    }

    return $isValidIP;

}
