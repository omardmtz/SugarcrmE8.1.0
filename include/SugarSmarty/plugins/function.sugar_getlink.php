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
 * Smarty {sugar_getlink} function plugin
 *
 * Type:     function
 * Name:     sugar_getlink
 * Purpose:  Returns HTML link <a> with embedded image or normal text
 * 
 * @param array
 * @param Smarty
 */

function smarty_function_sugar_getlink($params, &$smarty) {

	// error checking for required parameters
	if(!isset($params['url'])) 
		$smarty->trigger_error($GLOBALS['app_strings']['ERR_MISSING_REQUIRED_FIELDS'] . 'url');
	if(!isset($params['title']))
		$smarty->trigger_error($GLOBALS['app_strings']['ERR_MISSING_REQUIRED_FIELDS'] . 'title');

	// set defaults
	if(!isset($params['attr']))
		$params['attr'] = '';
	if(!isset($params['img_name'])) 
		$params['img_name'] = '';
	if(!isset($params['img_attr']))
		$params['img_attr'] = '';
	if(!isset($params['img_width']))
		$params['img_width'] = null;
	if(!isset($params['img_height']))
		$params['height'] = null;
	if(!isset($params['img_placement']))
		$params['img_placement'] = 'imageonly';
	if(!isset($params['img_alt']))
		$params['img_alt'] = '';

	return SugarThemeRegistry::current()->getLink($params['url'], $params['title'], $params['attr'], $params['img_name'], 
		$params['img_attr'], $params['img_width'], $params['img_height'], $params['img_alt'], $params['img_placement']);	
}
?>
