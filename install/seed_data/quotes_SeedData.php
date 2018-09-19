<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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



global $current_user;
global $sugar_demodata;

if(!empty($sugar_demodata['quotes_seed_data']['quotes'])) {


    if (isset($taxrate_id_arr) && !empty($taxrate_id_arr)) {
        $taxRate = BeanFactory::getBean('TaxRates', $taxrate_id_arr[0]);
    }

   foreach($sugar_demodata['quotes_seed_data']['quotes'] as $key=>$quote) {

		$focus = new Quote();
		$focus->assigned_user_id = $sugar_demodata['users'][array_rand($sugar_demodata['users'])]['id'];
		$focus->id = create_guid();
    	$focus->new_with_id = true;
		$focus->name = $quote['name'];
		$focus->description = !empty($quote['description']) ? $quote['description'] : '';
		$focus->quote_stage = !empty($quote['quote_stage']) ? $quote['quote_stage'] : '';
		$focus->date_quote_expected_closed = $quote['date_quote_expected_closed'];
		if(!empty($quote['purcahse_order_num'])) {
		   $focus->purchase_order_num = $quote['purcahse_order_num'];
		}
		
   		if(!empty($quote['original_po_date'])) {
		   $focus->original_po_date = $quote['original_po_date'];
		}

   		if(!empty($quote['payment_terms'])) {
		   $focus->payment_terms = $quote['payment_terms'];
		}
		
		$focus->quote_type = 'Quotes';
		$focus->calc_grand_total = 1;
		$focus->show_line_nums = 1;
		$focus->team_id = $current_user->team_id;
		$focus->team_set_id = $current_user->team_set_id;
        $focus->currency_id = '-99';
        $focus->base_rate = '1.0';

        if (isset($taxrate_id_arr) && !empty($taxrate_id_arr)) {
            $focus->taxrate_id = $taxRate->id;
            $focus->taxrate_value = $taxRate->value;
        }

		//Set random account and contact ids
		$sql = 'SELECT * FROM accounts WHERE deleted = 0';
		$result = $GLOBALS['db']->limitQuery($sql,0,10,true,"Error retrieving Accounts");
	    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
	    	$focus->billing_account_id = $row['id'];
	    	$focus->name = str_replace('[account name]', $row['name'], $focus->name);
	    	$focus->billing_address_street = $row['billing_address_street'];
	    	$focus->billing_address_city = $row['billing_address_city'];
	    	$focus->billing_address_state = $row['billing_address_state'];
	    	$focus->billing_address_country = $row['billing_address_country'];
	    	$focus->billing_address_postalcode = $row['billing_address_postalcode'];
	    	$focus->shipping_address_street = $row['shipping_address_street'];
	    	$focus->shipping_address_city = $row['shipping_address_city'];
	    	$focus->shipping_address_state = $row['shipping_address_state'];
	    	$focus->shipping_address_country = $row['shipping_address_country'];
	    	$focus->shipping_address_postalcode = $row['shipping_address_postalcode'];
	    	break;
	    }

        $focus->save();

		foreach($quote['bundle_data'] as $bundle_key=>$bundle) {
			$pb = new ProductBundle();
	        $pb->team_id = $focus->team_set_id;
            $pb->team_set_id = $focus->team_set_id;
            $pb->currency_id = $focus->currency_id;
            $pb->base_rate = $focus->base_rate;
			$pb->bundle_stage = $bundle['bundle_stage'];
			$pb->name = $bundle['bundle_name'];
            $pb->shipping = '0.00';

            $product_bundle_id = $pb->save();

            //Save the products
            foreach($bundle['products'] as $product_key=>$products) {
            	$sql = 'SELECT * FROM product_templates WHERE name = \'' . $products['name'] . '\'';
	            $result = $GLOBALS['db']->query($sql);
	            while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
	                $product = new Product();
	                
	            	foreach($product->column_fields as $field) {
						if(isset($row[$field])) {
	                       $product->$field = $row[$field];
						}
					}	                
					$product->product_template_id = $row['id'];
					$product->name = $products['name'];
					$product->id = create_guid();
					$product->new_with_id = true;
	                $product->quantity = $products['quantity'];
					$product->currency_id = $focus->currency_id;
                    $product->base_rate = $focus->base_rate;
					$product->team_id = $focus->team_id;
					$product->team_set_id = $focus->team_set_id;
					$product->account_id = $focus->billing_account_id;
					$product->status = 'Quotes';
					
					if ($focus->quote_stage == 'Closed Accepted') {
						$product->status='Orders';
					}

                    $product->ignoreQuoteSave = true;
					$product_id = $product->save();

                    $product->load_relationship('quotes');
                    $product->quotes->add($focus);

                    $pb->load_relationship('products');
                    $pb->products->add($product, array('product_index' => $product_key));
					break;
	            } //while
	            
            } //foreach

            unset($pb->products);
            $pb->save();
            
            //Save any product bundle comment
            if(isset($bundle['comment'])) {
				$product_bundle_note = new ProductBundleNote();
				$product_bundle_note->description = $bundle['comment'];
				$product_bundle_note->save();

                $pb->load_relationship('product_bundle_notes');
				$pb->product_bundle_notes->add($product_bundle_note, array('note_index' => $bundle_key));
	        }

            $focus->load_relationship('product_bundles');
            $focus->product_bundles->add($pb, array('bundle_index' => $bundle_key + 1));
	        
		} //foreach
        unset($focus->product_bundles);
		
		//Save the quote
		$focus->save();
   } //foreach
}
