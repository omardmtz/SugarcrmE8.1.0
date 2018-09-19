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

$subpanel_layout = array(
	'top_buttons' => array(
			/*array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Queues'),*/
	),
	'where' => "",

	'fill_in_additional_fields'=>true,
	'list_fields' => array(
		'name'			=> array (
			'vname'		=> 'LBL_NAME',
			'width'		=> '50%',
			'sortable'	=> false,
		),
		'status'		=> array (
			 'vname'	=> 'LBL_STATUS',
			 'width'	=> '10%',
			 'sortable'	=> true,
		),
		'execute_time'	=> array (
			 'vname'	=> 'LBL_EXECUTE_TIME',
			 'width'	=> '10%',
			 'sortable'	=> true,
		),
		'date_modified'	=> array (
			 'vname'	=> 'LBL_DATE_MODIFIED',
			 'width'	=> '10%',
			 'sortable'	=> true,
		),
		),
);

?>