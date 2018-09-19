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

 * Description:  Contains a variety of utility functions for the Import module
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/

/**
 * Returns an input control for this fieldname given
 *
 * @param  string $module
 * @param  string $fieldname
 * @param  string $vardef
 * @param  string $value
 * @param  array  $displayParams
 * @return string html for input element for this control
 */
function getControl(
    $module,
    $fieldname,
    $vardef = null,
    $value = '',
    $displayParams = array()
) {
    global $current_language, $app_strings, $dictionary, $app_list_strings, $current_user;

    // use the mod_strings for this module
    $mod_strings = return_module_language($current_language,$module);

 	// set the filename for this control
    $file = create_cache_directory('modules/Import/') . $module . $fieldname . '.tpl';

    if ( !is_file($file)
            || inDeveloperMode()
            || !empty($_SESSION['developerMode']) ) {

        if ( !isset($vardef) ) {
            $focus = BeanFactory::newBean($module);
            $vardef = $focus->getFieldDefinition($fieldname);
        }

        // if this is the id relation field, then don't have a pop-up selector.
        if ($vardef['type'] == 'relate' && isset($vardef['id_name']) && $vardef['id_name'] == $vardef['name']) {
            $vardef['type'] = 'varchar';
        }

        // create the dropdowns for the parent type fields
        if ( $vardef['type'] == 'parent_type' ) {
            $vardef['type'] = 'enum';
        }

        // remove the special text entry field function 'getEmailAddressWidget'
        if (isset($vardef['function'])) {
            if (is_array($vardef['function']) && isset($vardef['function']['name'])) {
                $fn = $vardef['function']['name'];
            } else {
                $fn = $vardef['function'];
            }
            if ($fn === 'getEmailAddressWidget') {
                unset($vardef['function']);
            }
        }

        // no widget for email field
        if ($vardef['type'] == 'email') {
            $vardef['type'] = 'varchar';
        }

        // load SugarFieldHandler to render the field tpl file
        static $sfh;

        if(!isset($sfh)) {
            $sfh = new SugarFieldHandler();
        }

        $displayParams['formName'] = 'importstep3';

        $contents = $sfh->displaySmarty('fields', $vardef, 'ImportView', $displayParams);

        // Remove all the copyright comments
        $contents = preg_replace('/\{\*[^\}]*?\*\}/', '', $contents);

        // hack to disable one of the js calls in this control
        if (isset($vardef['function'])) {
            if (is_array($vardef['function']) && isset($vardef['function']['name'])) {
                $fn = $vardef['function']['name'];
            } else {
                $fn = $vardef['function'];
            }
            if ($fn === 'getCurrencyDropDown' || $fn === 'getCurrencies') {
                $contents .= "{literal}<script>function CurrencyConvertAll() { return; }</script>{/literal}";
            }
        }

        // Save it to the cache file
        if($fh = @sugar_fopen($file, 'w')) {
            fputs($fh, $contents);
            fclose($fh);
        }
    }

    // Now render the template we received
    $ss = new Sugar_Smarty();

    // Create Smarty variables for the Calendar picker widget
    global $timedate;
    $time_format = $timedate->get_user_time_format();
    $date_format = $timedate->get_cal_date_format();
    $ss->assign('USER_DATEFORMAT', $timedate->get_user_date_format());
 	$ss->assign('TIME_FORMAT', $time_format);
    $ss->assign('module', $module);
    $time_separator = ":";
    $match = array();
    if(preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
        $time_separator = $match[1];
    }
    $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
    if(!isset($match[2]) || $match[2] == '') {
        $ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M");
    }
    else {
        $pm = $match[2] == "pm" ? "%P" : "%p";
        $ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M" . $pm);
    }

    $ss->assign('CALENDAR_FDOW', $current_user->get_first_day_of_week());

    // populate the fieldlist from the vardefs
    $fieldlist = array();
    if ( !isset($focus) || !($focus instanceof SugarBean) )
        $focus = BeanFactory::newBean($module);
    // create the dropdowns for the parent type fields
    if ( $vardef['type'] == 'parent_type' ) {
        $focus->field_defs[$vardef['name']]['options'] = $focus->field_defs[$vardef['group']]['options'];
    }
    $vardefFields = $focus->getFieldDefinitions();
    foreach ( $vardefFields as $name => $properties ) {
        $fieldlist[$name] = $properties;
        // fill in enums
        if(isset($fieldlist[$name]['options']) && is_string($fieldlist[$name]['options']) && isset($app_list_strings[$fieldlist[$name]['options']]))
            $fieldlist[$name]['options'] = $app_list_strings[$fieldlist[$name]['options']];
        // Bug 32626: fall back on checking the mod_strings if not in the app_list_strings
        elseif(isset($fieldlist[$name]['options']) && is_string($fieldlist[$name]['options']) && isset($mod_strings[$fieldlist[$name]['options']]))
            $fieldlist[$name]['options'] = $mod_strings[$fieldlist[$name]['options']];
        // Bug 22730: make sure all enums have the ability to select blank as the default value.
        if(isset($fieldlist[$name]['options']) && is_array($fieldlist[$name]['options']) && !isset($fieldlist[$name]['options']['']))
            $fieldlist[$name]['options'][''] = '';
    }
    // fill in function return values
    if ( !in_array($fieldname,array('email1','email2')) )
    {
        if ((!empty($fieldlist[$fieldname]['function']['returns']) && $fieldlist[$fieldname]['function']['returns'] == 'html')
                || (isset($fieldlist[$fieldname]['function']) && $fieldlist[$fieldname]['function'] == 'getCurrencies'))
        {

            $doRegex = false;
            if (isset($fieldlist[$fieldname]['function']) && $fieldlist[$fieldname]['function'] == 'getCurrencies') {
                $doRegex = true;
                $function = 'getCurrencyDropDown';
            } else {
                $function = $fieldlist[$fieldname]['function']['name'];
            }
            // include various functions required in the various vardefs
            if ( isset($fieldlist[$fieldname]['function']['include']) && is_file($fieldlist[$fieldname]['function']['include']))
                require_once($fieldlist[$fieldname]['function']['include']);
            $value = $function($focus, $fieldname, $value, 'EditView');
            // Bug 22730 - add a hack for the currency type dropdown, since it's built by a function.
            if ( preg_match('/getCurrency.*DropDown/s',$function) || $doRegex)
                $value = str_ireplace('</select>','<option value="">'.$app_strings['LBL_NONE'].'</option></select>',$value);
        }
        elseif($fieldname == 'assigned_user_name' && empty($value))
        {
            $fieldlist['assigned_user_id']['value'] = $GLOBALS['current_user']->id;
            $value = $GLOBALS['current_user']->full_name;
        }
        elseif($fieldname == 'team_name' && empty($value))
        {
            $value = json_encode(array());
        }
    }
    $fieldlist[$fieldname]['value'] = $value;
    $ss->assign("fields",$fieldlist);
    $ss->assign("form_name",'importstep3');
    $ss->assign("bean",$focus);

    // add in any additional strings
    $ss->assign("MOD", $mod_strings);
    $ss->assign("APP", $app_strings);
    return $ss->fetch($file);
}
