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
 
// $Id: layout_defs.php 14754 2006-07-18 18:45:17Z awu $

$layout_defs['Products'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
		'products' => array(
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Products'),
			),
			'order' => 10,
			'module' => 'Products',
			'sort_order' => 'desc',
			'sort_by' => 'date_purchased',
			'subpanel_name' => 'ForProducts',
			'get_subpanel_data' => 'related_products',
			'add_subpanel_data' => 'product_id',
			'title_key' => 'LBL_RELATED_PRODUCTS_TITLE',
			'get_distinct_data'=> true,
		),
		
		'notes' => array(
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
			),
			'order' => 20,
			'sort_order' => 'desc',
			'sort_by' => 'date_modified',
			'module' => 'Notes',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'notes',
			'add_subpanel_data' => 'note_id',
			'title_key' => 'LBL_NOTES_SUBPANEL_TITLE',
		),
		
        'documents' => array(
            'order' => 25,
            'module' => 'Documents',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
            'get_subpanel_data' => 'documents',
            'top_buttons' => 
            array (
                0 => 
                array (
                    'widget_class' => 'SubPanelTopButtonQuickCreate',
                    ),
                1 => 
                array (
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect',
                    ),
                ),
        ),

		'contracts' => array(
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contracts'),
			),
			'order' => 30,
			'sort_order' => 'desc',
			'sort_by' => 'date_modified',
			'module' => 'Contracts',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'contracts',
			'add_subpanel_data' => 'contract_id',
			'title_key' => 'LBL_CONTRACTS_SUBPANEL_TITLE',
		),		
	),
);
?>