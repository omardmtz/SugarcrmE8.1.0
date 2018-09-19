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
 
// $Id: layout_defs.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $

$layout_defs['UserHolidays'] = array(
	'subpanel_setup' => array(
		'holidays' => array(
			'order' => 30,
			'sort_by' => 'holiday_date',
			'sort_order' => 'asc',
			'module' => 'Holidays',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'holidays',
			'add_subpanel_data' => 'holiday_id',
/*			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopButtonQuickCreate', 'view' => 'UsersQuickCreate',),
			),*/
			'title_key' => 'LBL_USER_HOLIDAY_SUBPANEL_TITLE',
		),
	),
);
?>