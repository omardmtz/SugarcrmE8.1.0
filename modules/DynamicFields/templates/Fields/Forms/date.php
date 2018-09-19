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
// $Id: date.php 30233 2007-12-05 00:31:22Z majed $

function get_body(&$ss, $vardef){
	$td = new TemplateDate();
	$ss->assign('default_values', array_flip($td->dateStrings));
	return $ss->fetch('modules/DynamicFields/templates/Fields/Forms/date.tpl');
}

?>
