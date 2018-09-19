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
$searchFields['ProductTemplates'] = 
	array (
		'name' => array( 'query_type'=>'default'),
        'status'=> array('query_type'=>'default', 'options' => 'product_status_dom', 'template_var' => 'STATUS_OPTIONS', 'options_add_blank' => true),
        'type_id'=> array('query_type'=>'default', 'options' => 'product_type_dom', 'template_var' => 'TYPE_OPTIONS'),
        'category_id'=> array('query_type'=>'default', 'options' => 'products_cat_dom', 'template_var' => 'CATEGORY_OPTIONS'),
        'manufacturer_id'=> array('query_type'=>'default', 'options' => 'manufacturer_dom', 'template_var' => 'MANUFACTURER_OPTIONS'),
        'mft_part_num' => array( 'query_type'=>'default'),
        'vendor_part_num' => array( 'query_type'=>'default'),
        'tax_class'=> array('query_type'=>'default', 'options' => 'tax_class_dom', 'template_var' => 'TAX_CLASS_OPTIONS', 'options_add_blank' => true),
        'date_available' => array( 'query_type'=>'default'),
	);
?>
