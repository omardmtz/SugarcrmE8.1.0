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
$searchFields['Quotes'] = 
	array (
		'name' => array( 'query_type'=>'default'),
		'account_name'=> array('query_type'=>'default'),
		'date_quote_expected_closed'=> array('query_type'=>'default', 'operator'=>'='),
		'amount'=> array('query_type'=>'default','db_field'=>array('total')),
        'quote_stage'=> array('query_type'=>'default', 'options' => 'quote_stage_dom', 'template_var' => 'QUOTE_STAGE_OPTIONS', 'options_add_blank' => true),
        'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true, 'vname' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
        'assigned_user_id'=> array('query_type'=>'default'),
        'quote_type'=> array('query_type'=>'default', 'options' => 'quote_type_dom', 'template_var' => 'TYPE_OPTIONS', 'options_add_blank' => true),
        'quote_num'=> array('query_type'=>'default','operator'=>'in'),
		'favorites_only' => array(
            'query_type'=>'format',
			'operator' => 'subquery',
			'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites 
			                    WHERE sugarfavorites.deleted=0 
			                        and sugarfavorites.module = \'Quotes\'
			                        and sugarfavorites.assigned_user_id = {0}',
			'db_field'=>array('id')),
		'open_only' => array(
			'query_type'=>'default',
			'db_field'=>array('quote_stage'),
			'operator'=>'not in',
			'closed_values' => array('Closed Lost', 'Closed Accepted', 'Closed Dead'),
			'type'=>'bool',
		),
		//Range Search Support 
	   'range_date_entered' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	   'start_range_date_entered' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
	   'end_range_date_entered' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	   'range_date_modified' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	   'start_range_date_modified' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
       'end_range_date_modified' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),	
       'range_date_quote_expected_closed' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'start_range_date_quote_expected_closed' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
       'end_range_date_quote_expected_closed' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'range_date_quote_closed' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'start_range_date_quote_closed' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
       'end_range_date_quote_closed' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'range_date_order_shipped' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'start_range_date_order_shipped' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
       'end_range_date_order_shipped' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),		
       'range_original_po_date' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
       'start_range_original_po_date' => array ('query_type' => 'default',  'enable_range_search' => true, 'is_date_field' => true),
       'end_range_original_po_date' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),	

       'range_total_usdollar' => array ('query_type' => 'default', 'enable_range_search' => true),
	   'start_range_total_usdollar' => array ('query_type' => 'default',  'enable_range_search' => true),
       'end_range_total_usdollar' => array ('query_type' => 'default', 'enable_range_search' => true),
       'range_quote_num' => array ('query_type' => 'default', 'enable_range_search' => true),
	   'start_range_quote_num' => array ('query_type' => 'default',  'enable_range_search' => true),
       'end_range_quote_num' => array ('query_type' => 'default', 'enable_range_search' => true),				
		//Range Search Support 				
	);
?>
