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


class SugarWidgetFieldDecimal extends SugarWidgetFieldInt
{
 function displayListPlain($layout_def)
 {
 	
     //Bug40995
	if(isset($layout_def['precision']) && $layout_def['precision']!='')
	 {
		return format_number(parent::displayListPlain($layout_def), $layout_def['precision'], $layout_def['precision']);
	 }
	 //Bug40995
	 else
	 {
		return format_number(parent::displayListPlain($layout_def), 2, 2);
	 }
 }
}

?>
