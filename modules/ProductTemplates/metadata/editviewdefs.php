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
$viewdefs['ProductTemplates']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
),
 'panels' =>array (
  
 'default' => 
  array (    
    array (
      array('name'=>'name', 'label' => 'LBL_NAME','displayParams'=>array('required'=>true)),
      array (
	      'name' =>'status',
	      'label' =>'LBL_STATUS',
	    ),	    
    ),
    
    array (
	    	array (
	      'name' =>  'category_name',
	      'label' => 'LBL_CATEGORY_NAME',
	    ),
     
    ),
    
    array (
    	array (
	      'name' =>  'website',
	      'label' => 'LBL_URL',
	    ),
     array (
	      'name' =>  'date_available',
	      'label' =>'LBL_DATE_AVAILABLE',
	    ),
     
    ),
    
    array (
    	array (
	      'name' =>   'tax_class',
	      'label' => 'LBL_TAX_CLASS',
	    ),
     array (
	      'name' =>  'qty_in_stock',
	      'label' => 'LBL_QUANTITY',
	    ),
    ),
    
    array (
    	array (
	      'name' =>   'manufacturer_id',
	      'label' => 'LBL_LIST_MANUFACTURER_ID',
	    ),
     array (
	      'name' =>'weight',
	      'label' =>'LBL_WEIGHT',
	    ),
    ),
    
    array (
    	 array (
	      'name' =>'mft_part_num',
	      'label' =>'LBL_MFT_PART_NUM',
	    ),
    ),
    
    array (
    	array (
	      'name' =>'vendor_part_num',
	      'label' =>'LBL_VENDOR_PART_NUM',
	    ),
     array (
	      'name' =>'type_id',
	      'label' =>'LBL_TYPE',
	    ),
    ),
    
    array (
    	array (
	      'name' => 'currency_id',
	      'label' => 'LBL_CURRENCY',
	    ),
     array (
	      'name' => 'support_name',
	      'label' => 'LBL_SUPPORT_NAME',
	    ),
    ),
    
    array (
    	array (
	      'name' => 'cost_price',
	      'label' => 'LBL_COST_PRICE',
	    ),
     array (
	      'name' =>  'support_contact',
	      'label' => 'LBL_SUPPORT_CONTACT',
	    ),
    ),
    
    array (
    	array (
	      'name' => 'list_price',
	      'label' => 'LBL_LIST_PRICE',
	    ),
     array (
	      'name' => 'support_description',
	      'label' => 'LBL_SUPPORT_DESCRIPTION',
	    ),
    ),
    
    array (
    	array (
	      'name' =>  'discount_price',
	      'label' => 'LBL_DISCOUNT_PRICE',
	    ),
     array (
	      'name' =>  'support_term',
	      'label' => 'LBL_SUPPORT_TERM',
	    ),
    ),
    
    array (
    	  array (
	      'name' => 'pricing_formula',
	      'label' => 'LBL_PRICING_FORMULA',
	    ),
    ),
    
    array (
      array('name'=>'description','label'=> 'LBL_DESCRIPTION'),
    ),
  ),
)


);
?>