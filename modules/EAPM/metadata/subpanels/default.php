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

$module_name='EAPM';
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
	),

	'where' => '',

	'list_fields' => array(
		'application'=>array(
	 		'vname' => 'LBL_APPLICATION',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'width' => '25%',
		),
		'name'=>array(
	 		'vname' => 'LBL_NAME',
	 		'width' => '20%',
		),
		'date_modified'=>array(
	 		'vname' => 'LBL_DATE_MODIFIED',
	 		'width' => '20%',
		),
	),
);

?>
