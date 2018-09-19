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
// We suggest that if you wish to modify an existing formula, copy & paste the existing formula file to a new file
// this will prevent conflicts with future upgrades.

// To add a new formula, you will need to register the new file below and in the pricing_formula_dom array
// in modules/ProductTemplates/language/<lang>.lang.php
$price_formulas = array(
	//$discount_price manually entered by admin
	'Fixed'=>'modules/ProductTemplates/formulas/price_fixed.php'

	//Profit Margin: $discount_price = $cost_price * 100 /(100 - $factor)
	,'ProfitMargin'=>'modules/ProductTemplates/formulas/price_profit_margin.php'

	//Percentage Markup: $discount_price = $cost_price x (1 + $percentage)
	,'PercentageMarkup'=>'modules/ProductTemplates/formulas/price_cost_markup.php'

	//Percentage Discount: $discount_price = $list_price x (1 - $percentage)
	,'PercentageDiscount'=>'modules/ProductTemplates/formulas/price_list_discount.php'

	//List: $discount_price = $list_price
	,'IsList'=>'modules/ProductTemplates/formulas/price_list.php'
	);

function get_formula_details($pricing_factor) {
	global $price_formulas;
	foreach ($price_formulas as $formula=>$file) {
		require_once($file);
		$focus = new $formula;
		$readonly = $focus->is_readonly();
		$edit_html = $focus->get_edit_html($pricing_factor);
		$formula_js = $focus->get_formula_js();
		$output[$formula] = array('readonly'=>$readonly,'edit_html'=>$edit_html,'formula_js'=>$formula_js);
	}
	return $output;
}

function get_edit($formulas, $formula) {
	$the_script = '';
	//begin by creating all the divs for each formula's price factor
	foreach ($formulas as $name=>$content) {
		if ($name == $formula) {
			$the_script  .= "<div align='center' id='edit_$name' style='display:inline'> ${content['edit_html']}</div> \n";
		}
		else {
			$the_script  .= "<div align='center' id='edit_$name' style='display:none'> ${content['edit_html']}</div> \n";
		}
	}
	$the_script .= "<script type='text/javascript' language='Javascript'> \n";
	$the_script .= "<!--  to hide script contents from old browsers \n\n";
	$the_script .= "function show_factor() { \n";
	//first turn off all pricing factor divs
	foreach ($formulas as $name=>$content) {
		$the_script .= "	this.document.getElementById('edit_$name').style.display='none'; \n";
	}

	//then turn on a new pricing factor div based on the selected formula
	$the_script .= "	switch(this.document.getElementById('pricing_formula').value) { \n";
	foreach ($formulas as $name=>$content) {
		$the_script .= "		case '$name': \n";
		$the_script .= "			this.document.getElementById('edit_$name').style.display='inline'; \n";
		$the_script .= "		  	return true; \n";
	}
	$the_script .= "		} \n";
	$the_script .= "} \n";

	$the_script .= "function set_discount_price(form) { \n";
	$the_script .= "	switch(this.document.getElementById('pricing_formula').value) { \n";
	foreach ($formulas as $name=>$content) {
		$the_script .= "		case '$name': \n";
		$the_script .= "			${content['formula_js']} \n";
		$the_script .= "			this.document.getElementById('pricing_factor').value = this.document.getElementById('pricing_factor_$name').value; \n";
		$the_script .= "		  	return true; \n";
}
	$the_script .= "		} \n";
	$the_script .= "} \n";

	$the_script .= "//  End -->\n</script> \n\n";

	return $the_script;
}

function get_detail($formula, $factor) {
	global $mod_strings, $price_formulas;
	if (isset($price_formulas[$formula]))
	{
		require_once($price_formulas[$formula]);
		$focus = new $formula;
		return $focus->get_detail_html($formula, $factor);
	}
	else
	{
		return '';
	}
}

?>
