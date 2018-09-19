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
$searchFields['Documents'] = 
	array (
		'document_name' => array( 'query_type'=>'default'),
        'category_id'=> array('query_type'=>'default', 'options' => 'document_category_dom', 'template_var' => 'CATEGORY_OPTIONS'),
        'subcategory_id'=> array('query_type'=>'default', 'options' => 'document_subcategory_dom', 'template_var' => 'SUBCATEGORY_OPTIONS'),
		'active_date'=> array('query_type'=>'default'),
		'exp_date'=> array('query_type'=>'default'),
		'assigned_user_id'=> array('query_type'=>'default'),
		'filename' => array(
    		    'query_type' => 'format',
    		    'operator' => 'subquery',
    		    'subquery' => 'SELECT document_revisions.id FROM document_revisions
			           WHERE document_revisions.deleted=0
				   AND document_revisions.filename LIKE \'{0}\'',
                    'db_field' => array(0 => 'document_revision_id')
                ),
		'favorites_only' => array(
            'query_type'=>'format',
			'operator' => 'subquery',
			'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites 
			                    WHERE sugarfavorites.deleted=0 
			                        and sugarfavorites.module = \'Documents\' 
			                        and sugarfavorites.assigned_user_id = {0}',
			'db_field'=>array('id')),
		//Range Search Support 
	    'range_date_entered' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	    'start_range_date_entered' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	    'end_range_date_entered' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	    'range_date_modified' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
	    'start_range_date_modified' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),
        'end_range_date_modified' => array ('query_type' => 'default', 'enable_range_search' => true, 'is_date_field' => true),	
	    //Range Search Support 			
	);
?>
