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

// $Id: default.php 13968 2006-06-12 22:00:08Z jacob $
$subpanel_layout = array(
	'buttons' => array(
            array('widget_class' => 'SubPanelTopCreateButton'),
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Teams'),
	),

	'where' => '',


	'list_fields' => array(
        array(
		    'name' => 'name',
		 	'vname' => 'LBL_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '9999%',
		),
		array(
		    'name' => 'description',
		 	'vname' => 'LBL_DESCRIPTION',
			'width' => '9999%',
		)
	),
);
?>