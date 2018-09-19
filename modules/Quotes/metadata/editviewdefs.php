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
$viewdefs['Quotes']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'form' => array('footerTpl'=>'modules/Quotes/tpls/EditViewFooter.tpl'),
                           ),
    'panels' => array (
	  'lbl_quote_information' => array (
	    array (
	      'name',
	      'opportunity_name',
	    ),
	    
	    array (
	      array('name' => 'quote_num', 'type' => 'readonly', 'displayParams'=>array('required'=>false)),
	      'quote_stage',
	    ),
	    
	    array (
	      'purchase_order_num',
	      'date_quote_expected_closed',
	    ),
	    
	    array (
	      'payment_terms',
	      'original_po_date',
	    ),
	    array(
	      'description',
	    ),
	  ),
	
	'lbl_bill_to' => array (
	    array (
	      array('name'=>'billing_account_name', 'displayParams'=>array('key'=>array('billing', 'shipping'), 'copy'=>array('billing', 'shipping'), 'billingKey'=>'billing', 'shippingKey'=>'shipping', 'copyPhone'=>false, 'call_back_function' => 'set_billing_return')),	
	      array('name'=>'shipping_account_name','displayParams'=>array('key'=>array('shipping'), 'copy'=>array('shipping'), 'shippingKey'=>'shipping', 'copyPhone'=>false, 'call_back_function' => 'set_shipping_return')),
	    ),
	    
	    array (
	      array('name'=>'billing_contact_name','displayParams' => array('initial_filter' => '&account_id_advanced="+this.form.{$fields.billing_account_name.id_name}.value+"&account_name_advanced="+this.form.{$fields.billing_account_name.name}.value+"'),),
	       array('name'=>'shipping_contact_name','displayParams' => array('initial_filter' => '&account_id_advanced="+this.form.{$fields.shipping_account_name.id_name}.value+"&account_name_advanced="+this.form.{$fields.shipping_account_name.name}.value+"'),),
	    ),
    ),
    'lbl_address_information' => array (
	    array (
	      array (
		      'name' => 'billing_address_street',
	          'hideLabel' => true,      
		      'type' => 'address',
		      'displayParams'=>array('key'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
	      ),
	      
	      array (
		      'name' => 'shipping_address_street',
		      'hideLabel'=>true,
		      'type' => 'address',
		      'displayParams'=>array('key'=>'shipping', 'copy'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),      
	      ),
	    ),
    ),
    
    'LBL_PANEL_ASSIGNMENT' => array ( 
	    array (
	      'assigned_user_name',

	      array('name'=>'team_name','displayParams'=>array('required'=>true)),
	    ),    
    ),
)

);
?>
