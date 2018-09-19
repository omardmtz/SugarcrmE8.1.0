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

class QuotesViewEdit extends ViewEdit
{
    /**
 	 * @see SugarView::display()
 	 */
 	public function display()
 	{
 	    require_once('modules/Quotes/Layouts.php');
		require_once('include/EditView/EditView2.php');


		global $beanFiles;
		require_once($beanFiles['Quote']);
		require_once($beanFiles['TaxRate']);
		require_once($beanFiles['Shipper']);

		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $current_user;
		global $timedate;
		global $locale;

		$original_quote = BeanFactory::newBean('Quotes');
		if($this->ev->isDuplicate)
		{
			$this->bean->id = "";
			$this->bean->quote_num = "";
			$original_quote->retrieve($_REQUEST['record']);
		}

		//needed when creating a new quote only with a default account value passed in
		if((empty($this->bean->id))  && !$this->ev->isDuplicate)
		{
			$this->bean->quote_num= '';
		    $this->bean->total= '0.00';
		    $this->bean->shipping= '0.00';
		    $this->bean->tax= '0.00';
		    $this->bean->subtotal= '0.00';
		   	if (isset($_REQUEST['opportunity_name'])) {
				$this->bean->opportunity_name = $_REQUEST['opportunity_name'];
			}
			if (isset($_REQUEST['opportunity_id'])) {
				$this->bean->opportunity_id = $_REQUEST['opportunity_id'];
			}
			if (isset($_REQUEST['account_name'])) {
				$this->bean->billing_account_name = $_REQUEST['account_name'];
				$this->bean->shipping_account_name = $_REQUEST['account_name'];
			}
			if (isset($_REQUEST['account_id'])) {
				$this->bean->billing_account_id = $_REQUEST['account_id'];
				$this->bean->shipping_account_id = $_REQUEST['account_id'];
				require_once($beanFiles['Account']);
				$account = BeanFactory::getBean('Accounts', $this->bean->shipping_account_id);
				$this->bean->shipping_address_street 	= $account->shipping_address_street;
				$this->bean->shipping_address_city 		= $account->shipping_address_city;
				$this->bean->shipping_address_state 		= $account->shipping_address_state;
				$this->bean->shipping_address_country 	= $account->shipping_address_country;
				$this->bean->shipping_address_postalcode = $account->shipping_address_postalcode;
				$this->bean->billing_address_street 		= $account->billing_address_street;
				$this->bean->billing_address_city 		= $account->billing_address_city;
				$this->bean->billing_address_state 		= $account->billing_address_state;
				$this->bean->billing_address_country 	= $account->billing_address_country;
				$this->bean->billing_address_postalcode 	= $account->billing_address_postalcode;
			}
			if (isset($_REQUEST['contact_id'])) {
				$this->bean->contact_id = $_REQUEST['contact_id'];
				require_once($beanFiles['Contact']);
				$contact = BeanFactory::getBean('Contacts', $this->bean->contact_id);
				$this->bean->billing_contact_name 		= $locale->formatName($contact);
				$this->bean->billing_contact_id 			= $contact->id;
                $this->bean->shipping_contact_name 		= $locale->formatName($contact);
				$this->bean->shipping_contact_id 		= $contact->id;
				$this->bean->shipping_address_street 	= $contact->primary_address_street;
				$this->bean->shipping_address_city 		= $contact->primary_address_city;
				$this->bean->shipping_address_state 		= $contact->primary_address_state;
				$this->bean->shipping_address_country 	= $contact->primary_address_country;
				$this->bean->shipping_address_postalcode = $contact->primary_address_postalcode;
			}

			if (isset($_REQUEST['date_quote_expected_closed'])) {
				$this->bean->date_quote_expected_closed = $_REQUEST['date_quote_expected_closed'];
			}
			if (isset($_REQUEST['currency_id'])) {
				$this->bean->currency_id = $_REQUEST['currency_id'];
			}
		}


		$currency = BeanFactory::getBean('Currencies', $this->bean->currency_id);

		// Set the number grouping and decimal separators
		$seps = get_number_seperators();
		$dec_sep = $seps[1];
		$num_grp_sep = $seps[0];
		$this->ss->assign('NUM_GRP_SEP', $num_grp_sep);
		$this->ss->assign('DEC_SEP', $dec_sep);

		$significantDigits = $locale->getPrecedentPreference('default_currency_significant_digits', $current_user);
        $this->ss->assign('PRECISION', $significantDigits);


		if((is_admin($current_user) || is_admin_for_module($GLOBALS['current_user'],'Quotes')) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
            $this->ss->assign('ADMIN_EDIT', '<a href="' . htmlspecialchars('index.php?' . http_build_query(array(
                'module' => 'DynamicLayout',
                'action' => 'index',
                'from_action' => $this->request->getValidInputRequest('action'),
                'from_module' => $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName'),
                'record' => $this->request->getValidInputRequest('record', 'Assert\Guid', ''),
            )), ENT_QUOTES, 'UTF-8') . '">' . SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>");
		}
		$this->ss->assign('QUOTE_STAGE_OPTIONS', get_select_options_with_id($app_list_strings['quote_stage_dom'], $this->bean->quote_stage));
		$this->ss->assign('DEFAULT_PRODUCT_STATUS', $app_list_strings['product_status_quote_key']);
		if (isset($this->bean->subtotal)) $this->ss->assign('SUBTOTAL', $this->bean->subtotal);
		else $this->ss->assign('SUBTOTAL', "0.00");
		if (isset($this->bean->tax)) $this->ss->assign('TAX', $this->bean->tax);
		else $this->ss->assign('TAX', "0.00");
		if (isset($this->bean->shipping)) $this->ss->assign("SHIPPING", $this->bean->shipping);
		else $this->ss->assign('SHIPPING', "0.00");
		if (isset($this->bean->deal_tot)) $this->ss->assign('DEAL_TOT', $this->bean->deal_tot);
        else $this->ss->assign('DEAL_TOT', "0.00");
        if (isset($this->bean->new_sub)) $this->ss->assign('NEW_SUB', $this->bean->new_sub);
        else $this->ss->assign('NEW_SUB', "0.00");
		if (isset($this->bean->total)) $this->ss->assign('TOTAL', $this->bean->total);
		else $this->ss->assign('TOTAL', "0.00");
		if (isset($this->bean->subtotal_usdollar)) $this->ss->assign('SUBTOTAL_USDOLLAR', $this->bean->subtotal_usdollar);
		else $this->ss->assign('SUBTOTAL_USDOLLAR', "0.00");
		if (isset($this->bean->tax_usdollar)) $this->ss->assign('TAX_USDOLLAR', $this->bean->tax_usdollar);
		else $this->ss->assign('TAX_USDOLLAR', "0.00");
		if (isset($this->bean->shipping_usdollar)) $this->ss->assign('SHIPPING_USDOLLAR', $this->bean->shipping_usdollar);
		else $this->ss->assign('SHIPPING_USDOLLAR', "0.00");
		if (isset($this->bean->total_usdollar)) $this->ss->assign('TOTAL_USDOLLAR', $this->bean->total_usdollar);
		else $this->ss->assign('TOTAL_USDOLLAR', "0.00");


		$this->ss->assign('USER_DATEFORMAT', '('. $timedate->get_user_date_format().')');
		$this->ss->assign('CALENDAR_DATEFORMAT', $timedate->get_cal_date_format());
		$taxrate = BeanFactory::newBean('TaxRates');
		$this->ss->assign('TAXRATE_OPTIONS', get_select_options_with_id($taxrate->get_taxrates(false), $this->bean->taxrate_id));
		if (empty($this->bean->taxrate_value)) { $this->ss->assign('TAXRATE_VALUE', $taxrate->get_default_taxrate_value() / 100); }
		else { $this->ss->assign('TAXRATE_VALUE', $this->bean->taxrate_value / 100); }

		$shipper = BeanFactory::newBean('Shippers');
		$this->ss->assign('SHIPPER_OPTIONS', get_select_options_with_id($shipper->get_shippers(true), $this->bean->shipper_id));

		if (empty($this->bean->assigned_user_id) && empty($this->bean->id))  $this->bean->assigned_user_id = $current_user->id;
		if (empty($this->bean->assigned_name) && empty($this->bean->id))  $this->bean->assigned_user_name = $current_user->user_name;
		$this->ss->assign('ASSIGNED_USER_OPTIONS', get_select_options_with_id(get_user_array(TRUE, 'Active', $this->bean->assigned_user_id), $this->bean->assigned_user_id));
		$this->ss->assign('ASSIGNED_USER_NAME', $this->bean->assigned_user_name);
		$this->ss->assign('ASSIGNED_USER_ID', $this->bean->assigned_user_id );

		if(!empty($this->bean->calc_grand_total) && $this->bean->calc_grand_total == 1){
			$this->ss->assign('CALC_GRAND_TOTAL_CHECKED', 'checked');
		}

		if(!empty($this->bean->show_line_nums) && $this->bean->show_line_nums == 1){
			$this->ss->assign('SHOW_LINE_NUMS_CHECKED', 'checked');
		}

		// Set Currency values and currency javascript
		$currency = new ListCurrency();
		$base_rate = '1.00';

        if ((!empty($this->bean->id) || $this->ev->isDuplicate) &&
            isset($this->bean->currency_id) &&
            !empty($this->bean->currency_id)) {
            $curid = $this->bean->currency_id;
        } elseif ( isset($_REQUEST['currency_id']) && !empty($_REQUEST['currency_id']) ) {
            $curid = $_REQUEST['currency_id'];
        } elseif ( empty($this->bean->id) ) {
            $curid = $current_user->getPreference('currency');
            if ( empty($curid) ) {
                $curid = -99;
            }
        } else {
            $curid = -99;
        }

		if ($this->bean->isClosed()) {
			$base_rate = $this->bean->base_rate;
		} else {
			$base_rate = null;
		}

        $selectCurrency = $currency->getSelectOptions($curid, $base_rate);
        $this->ss->assign("CURRENCY", $selectCurrency);
		$this->ss->assign('CURRENCY_JAVASCRIPT', $currency->getJavascript());

        if($this->bean->fetched_row['date_quote_expected_closed'] == '1970-01-01' ||
            $this->bean->fetched_row['date_quote_expected_closed'] == '0001-01-01') {
            $this->bean->date_quote_expected_closed = '';
        }

		$add_row = array();
		if (!empty($this->bean->id))
		{
			$this->bean->load_relationship('product_bundles');
            $product_bundle_list = $this->bean->product_bundles->getBeans();
            usort($product_bundle_list, array('ProductBundle', 'compareProductBundlesByIndex'));

			$quote_currency_id = $this->bean->currency_id;
			$quote_base_rate = $this->bean->base_rate;
			$convert_format = function($value, $prod_currency, $prod_base_rate) use ($quote_currency_id, $quote_base_rate) {
				if ($prod_currency !== $quote_currency_id) {
					$value = SugarCurrency::convertWithRate($value, $prod_base_rate, $quote_base_rate);
				}
				return SugarCurrency::formatAmountUserLocale($value, $quote_currency_id, false);
			};

            if(is_array($product_bundle_list)){

				foreach ($product_bundle_list as $product_bundle) {
                    $escaped_bundle_name = str_replace('\\', '\\\\', $product_bundle->name);
					$bundle_list = $product_bundle->get_product_bundle_line_items();
                    $add_row[] = "quotesManager.addTable('$product_bundle->id', '$product_bundle->bundle_stage', '" .
                        $escaped_bundle_name . "', '" .
                        format_money($product_bundle->shipping, false) . "' );\n";

					if (is_array($bundle_list)) {
						while (list($key, $line_item) = each ($bundle_list)) {
							if ($line_item->object_name == "Product") {
								/* @var $line_item Product */
                                $tax_class_name = isset($line_item->tax_class) ? $line_item->tax_class : "";

								$encoded_name = js_escape(br2nl($line_item->name));

								$add_row[] = "quotesManager.addRow('$line_item->id','" . format_number($line_item->quantity, $significantDigits, $significantDigits) . "','$line_item->product_template_id','$encoded_name'"
											. ", '".$convert_format($line_item->cost_price, $line_item->currency_id, $line_item->base_rate) . "'"
											. ", '".$convert_format($line_item->list_price, $line_item->currency_id, $line_item->base_rate) ."'"
											. ", '".$convert_format($line_item->discount_price, $line_item->currency_id, $line_item->base_rate) . "'"
                                            . ", '$line_item->pricing_formula', "
                                            . "'', '$line_item->pricing_factor', "
                                            . "'$line_item->tax_class', "
                                            . "'$tax_class_name', "
                                            . "'$line_item->mft_part_num', "
                                            . "'$product_bundle->id', "
                                            . "'$product_bundle->bundle_stage', "
                                            . "'$escaped_bundle_name', '"
											. format_number($product_bundle->shipping)."', '".js_escape(br2nl($line_item->description))."', '". $line_item->type_id."'"
											. ", '".format_number($line_item->discount_amount, $significantDigits, $significantDigits)."'"
		                                    . ", ".($line_item->discount_select?1:0)
		                                    . ", ".($line_item->deal_calc?1:0)
		                                    . ", '".$line_item->status."');\n";
							}
							else if ($line_item->object_name == "ProductBundleNote") {
								/* @var $line_item ProductBundleNote */
								$encoded_description = js_escape(br2nl($line_item->description));
								//$encoded_description = html_entity_decode($encoded_description);
								$add_row[] = "quotesManager.addCommentRow('$line_item->id', '$product_bundle->id', '$encoded_description');\n";
							}
						} //while
					} //if
				} //foreach
			}
		} else {
		    // this else part is to create a new product_bundle for the duplicate quote
			$original_quote->load_relationship('product_bundles');
			$product_bundle_list = $original_quote->product_bundles->getBeans();
            usort($product_bundle_list, array('ProductBundle', 'compareProductBundlesByIndex'));

            if(is_array($product_bundle_list)){

				foreach ($product_bundle_list as $product_bundle) {

					$product_list = $product_bundle->get_products();
					if (is_array($product_list)) {
						foreach ($product_list as $line_item) {
                            $tax_class_name = isset($line_item->tax_class) ? $line_item->tax_class : "";

							$add_row[] = "quotesManager.addRow('','$line_item->quantity','$line_item->product_template_id','$line_item->name'"
											. ", '".format_number($line_item->cost_usdollar, $significantDigits, $significantDigits, array('convert' => true, 'currency_id' => $curid)) . "'"
											. ", '".format_number($line_item->list_usdollar, $significantDigits, $significantDigits, array('convert' => true, 'currency_id' => $curid)) ."'"
											. ", '".format_number($line_item->discount_usdollar, $significantDigits, $significantDigits, array('convert' => true, 'currency_id' => $curid)) . "'"
							    .", '', '', '$line_item->pricing_factor', '$line_item->tax_class', '$tax_class_name',
								'$line_item->mft_part_num', 'group_$product_bundle->id', '$product_bundle->bundle_stage', '$product_bundle->name', '".format_money($product_bundle->shipping,FALSE)
							    ."', '".js_escape(br2nl($line_item->description))."', '"
							    . $line_item->type_id ."','"
							    . format_number($line_item->discount_amount_usdollar, $significantDigits, $significantDigits, array('convert' => !$line_item->discount_select, 'currency_id' => $curid))."',"
                                .($line_item->discount_select?1:0)
							    . ",0, '". $line_item->status."');\n";

						} //foreach
						if(empty($product_list)){
								$add_row[] = "quotesManager.addTable('group_$product_bundle->id','$product_bundle->bundle_stage', '$product_bundle->name' , ' ".format_money($product_bundle->shipping,FALSE)."');\n";
						} //if
                        //bug 39573 - Comments are not duplicated in quotes
                        $bundle_list = $product_bundle->get_product_bundle_line_items();
                        if (is_array($bundle_list)){
                            while (list($key, $line_item) = each ($bundle_list)){
                                if ($line_item->object_name == "ProductBundleNote"){
                                    $encoded_description = js_escape(br2nl($line_item->description));
                                    $add_row[] = "quotesManager.addCommentRow('$line_item->id', 'group_$product_bundle->id', '$encoded_description');\n";

                                }
                            }
                        } //end bug 39573
					} //if
				}
			}
		} //if-else for !empty($this->bean->id)

		//Bug#53607: Create the javascript code to store the rendering function in a queue
        $add_row_js = 'var add_row_stack = [];';
        foreach($add_row as $script_command) {
            $add_row_js .= "add_row_stack.push(function(){
                $script_command
            });";
        }

		//Bug#53607: Rather than executing all rendering row script once, it will keep in a queue.
        //           And it will render the specified number of rows every interval.
        $add_row_js .= "function add_rows_on_load() {
            if(typeof add_row_stack != 'undefined' && add_row_stack.length > 0) {
                //interval is in msec,
                //size is the number of rows rendering every time
                asyncLoading = true; // to indicate that the content is still loading
                var _interval = 100,
                    _size = 3,
                    _exec = add_row_stack.splice(0, _size),
                    _header_button = document.getElementById('SAVE_HEADER'),
                    _footer_button = document.getElementById('SAVE_FOOTER');
                if(_header_button) {
                    _header_button.disabled = true;
                }
                if(_footer_button) {
                    _footer_button.disabled = true;
                }
                for(idx in _exec) {
                    _exec[idx]();
                }
                window.setTimeout(add_rows_on_load, _interval);
            } else {
                asyncLoading = false; // content is loaded
                var _header_button = document.getElementById('SAVE_HEADER'),
                    _footer_button = document.getElementById('SAVE_FOOTER');
                if(_header_button) {
                    _header_button.disabled = false;
                }
                if(_footer_button) {
                    _footer_button.disabled = false;
                }
            }
        }";
		$this->ss->assign("ADD_ROWS", $add_row_js);

		$setup_script = '';
		$taxclass = translate('tax_class_dom');
		foreach($taxclass as $value=>$name){
			$setup_script .= "quotesManager.add_tax_class('$name', '$value');\n";
		}
		$this->ss->assign("SETUP_SCRIPT", $setup_script);

		$this->ss->assign('TAXRATE_JAVASCRIPT', $taxrate->get_taxrate_js());
		$this->ss->assign('CALCULATE_FUNCTION', '<script type="text/javascript">YAHOO.util.Event.onDOMReady(function(){quotesManager.calculate(document);});</script>');

		$this->ss->assign('NO_MATCH_VARIABLE', '<script type="text/javascript">sqs_no_match_text = "' . $app_strings['ERR_SQS_NO_MATCH'] . '";</script>');

		$str = "<script language=\"javascript\">
		YAHOO.util.Event.onAvailable('add_tables', add_rows_on_load);
		</script>";
		$this->ss->assign('SAVED_SEARCH_SELECTS', $str);

 		parent::display();
 		echo '<script>sqs_must_match = false;</script>';
 	}
    
}
