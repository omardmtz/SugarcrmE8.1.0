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

// $Id: default.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
$subpanel_layout = array(
	'top_buttons' => array(
			array('widget_class' => 'SubPanelTopCreateButton'),
	),

	'where' => '',

	'list_fields' => array(
        'holiday_date'=>array(
		 	'vname' => 'LBL_HOLIDAY_DATE',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '21%',
		),
		'description'=>array(
		 	'vname' => 'LBL_DESCRIPTION',
			'width' => '75%',
			'sortable'=>false,				
		),
		'edit_button'=>array(
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),


	),
);
?>