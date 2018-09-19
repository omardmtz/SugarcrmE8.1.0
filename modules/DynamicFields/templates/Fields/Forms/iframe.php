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
	$vars = $ss->get_template_vars();
	$fields = $vars['module']->mbvardefs->vardefs['fields'];
	$fieldOptions = array();
	foreach($fields as $id=>$def) {
		$fieldOptions[$id] = $def['name'];
	}
	$ss->assign('fieldOpts', $fieldOptions);
    $ss->assign('hideReportable', true);
	return $ss->fetch('modules/DynamicFields/templates/Fields/Forms/iframe.tpl');
 }
