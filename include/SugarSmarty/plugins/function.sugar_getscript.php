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
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sugar_getscript} function plugin
 *
 * Type:     function<br>
 * Name:     sugar_getscript<br>
 * Purpose:  Creates script tag for filename with caching string
 *
 * @param array
 * @param Smarty
 */
function smarty_function_sugar_getscript($params, &$smarty)
{
	if(!isset($params['file'])) {
		   $smarty->trigger_error($GLOBALS['app_strings']['ERR_MISSING_REQUIRED_FIELDS'] . 'file');
	}
 	return getVersionedScript($params['file']);
}
?>