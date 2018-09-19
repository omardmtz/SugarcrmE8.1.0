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
 * Smarty {sugar_getimage} function plugin
 *
 * Type:     function
 * Name:     sugar_getimage
 * Purpose:  Returns HTML image or sprite
 * 
 * @author Aamir Mansoor (amansoor@sugarcrm.com) 
 * @author Cam McKinnon (cmckinnon@sugarcrm.com)
 * @param array
 * @param Smarty
 */

function smarty_function_sugar_getimage($params, &$smarty) {

	// error checking for required parameters
	if(!isset($params['name'])) 
		$smarty->trigger_error($GLOBALS['app_strings']['ERR_MISSING_REQUIRED_FIELDS'] . 'name');

	// temp hack to deprecate the use of other_attributes
	if(isset($params['other_attributes']))
		$params['attr'] = $params['other_attributes'];

	// set defaults
	if(!isset($params['attr']))
		$params['attr'] = '';
	if(!isset($params['width']))
		$params['width'] = null;
	if(!isset($params['height']))
		$params['height'] = null;
	if(!isset($params['alt'])) 
		$params['alt'] = '';

	// deprecated ?
	if(!isset($params['ext']))
		$params['ext'] = null;

	return SugarThemeRegistry::current()->getImage($params['name'], $params['attr'], $params['width'], $params['height'], $params['ext'], $params['alt']);	
}
?>
