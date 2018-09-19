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
 function get_body(&$ss, $vardef){
 	$multi = false;
    $radio = false;
 	if (isset ($vardef['type']) && $vardef['type'] == 'multienum')
 		$multi = true;

 	$selected_options = "";
 	if ($multi && !empty($vardef['default'])) {
 		$selected_options = unencodeMultienum( $vardef['default']);
 	} else if (isset($vardef['default'])){
 		$selected_options = $vardef['default'];
 	}

    $edit_mod_strings = return_module_language($GLOBALS['current_language'], 'EditCustomFields');

	if ((!empty($_REQUEST['type']) && $_REQUEST['type'] == 'radioenum') ||
        (isset($vardef['type']) && $vardef['type'] == 'radioenum')) {
		$edit_mod_strings['LBL_DROP_DOWN_LIST'] = $edit_mod_strings['LBL_RADIO_FIELDS'];
        $radio = true;
	}

    $my_list_strings = enum_get_lists();
    // should not display read only options
    $excludedOptions = array('Elastic_boost_options');
    foreach ($excludedOptions as $options) {
        if (isset($my_list_strings[$options])) {
            unset($my_list_strings[$options]);
        }
    }

	$dropdowns = array_keys($my_list_strings);
    if(!empty($vardef['options']) && !empty($my_list_strings[$vardef['options']])){
    		$default_dropdowns = $my_list_strings[$vardef['options']];
    }else{
    	//since we do not have a default value then we should assign the first one.
    	$key = $dropdowns[0];
    	$default_dropdowns = $my_list_strings[$key];
    }

    $selected_dropdown = '';
    if(!empty($vardef['options'])){
    	$selected_dropdown = $vardef['options'];

    }
    $show = true;
	if(!empty($_REQUEST['refresh_dropdown']))
		$show = false;

	$ss->assign('dropdowns', $dropdowns);
	$ss->assign('default_dropdowns', $default_dropdowns);
	$ss->assign('selected_dropdown', $selected_dropdown);
	$ss->assign('show', $show);
	$ss->assign('selected_options', $selected_options);
	$ss->assign('multi', isset($multi) ? $multi: false);
    $ss->assign('radio', isset($radio) ? $radio: false);
	$ss->assign('dropdown_name',(!empty($vardef['options']) ? $vardef['options'] : ''));

	$ss->assign('app_list_strings', "''");
	return $ss->fetch('modules/DynamicFields/templates/Fields/Forms/enum.tpl');
 }

/**
 * Returns drop-down lists available for requested package and module
 *
 * @return array
 */
function enum_get_lists()
{
    global $app_list_strings;

    $package_strings = array();
    if (!empty($_REQUEST['view_package'])) {
        $view_package = $_REQUEST['view_package'];
        if ($view_package != 'studio') {
            $mb = new ModuleBuilder();
            $module = $mb->getPackageModule($view_package, $_REQUEST['view_module']);
            $lang = $GLOBALS['current_language'];
            $module->mblanguage->generateAppStrings(false);
            $package_strings = $module->mblanguage->appListStrings[$lang . '.lang.php'];
        }
    }

    $my_list_strings = array_merge($app_list_strings, $package_strings);
    $my_list_strings = array_filter($my_list_strings, 'is_array');
    ksort($my_list_strings);

    return $my_list_strings;
}
