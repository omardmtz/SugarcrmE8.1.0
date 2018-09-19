{*
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
*}

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<th colspan="4" align="left" ><h4>{$MOD.LBL_WIZ_NEWSLETTER_TITLE_STEP2}</h4></th>
		</tr>
		<tr><td class="datalabel" colspan="3">{$MOD.LBL_WIZARD_BUDGET_MESSAGE}<br></td><td>&nbsp;</td></tr>
		<tr><td class="datalabel" colspan="4">&nbsp;</td></tr>
		<tr>
		<td scope="col"><span sugar='slot14'>{$MOD.LBL_CAMPAIGN_BUDGET}</span sugar='slot'></td>
		<td ><span sugar='slot14b'><input type="text" size="10" maxlength="15" id="budget" name="wiz_step2_budget" title="{$MOD.LBL_CAMPAIGN_BUDGET}" value="{$CAMP_BUDGET}"></span sugar='slot'></td>
		<td scope="col"><span sugar='slot15'>{$MOD.LBL_CAMPAIGN_ACTUAL_COST}</span sugar='slot'></td>
		<td ><span sugar='slot15b'><input type="text" size="10" maxlength="15" id="actual_cost" name="wiz_step2_actual_cost" title="{$MOD.LBL_CAMPAIGN_ACTUAL_COST}" value="{$CAMP_ACTUAL_COST}"></span sugar='slot'></td>
		</tr>
		<tr>
		<td scope="col"><span sugar='slot16'>{$MOD.LBL_CAMPAIGN_EXPECTED_REVENUE}</span sugar='slot'></td>
		<td ><span sugar='slot16b'><input type="text" size="10" maxlength="15" id="expected_revenue" name="wiz_step2_expected_revenue" title="{$MOD.LBL_CAMPAIGN_EXPECTED_REVENUE}" value="{$CAMP_EXPECTED_REVENUE}"></span sugar='slot'></td>
		<td scope="col"><span sugar='slot17'>{$MOD.LBL_CAMPAIGN_EXPECTED_COST}</span sugar='slot'></td>
		<td ><span sugar='slot17b'><input type="text" size="10" maxlength="15" id="expected_cost" name="wiz_step2_expected_cost" title="{$MOD.LBL_CAMPAIGN_EXPECTED_COST}" value="{$CAMP_EXPECTED_COST}"></span sugar='slot'></td>
		</tr>
		<tr>
		<td scope="col"><span sugar='slot18'>{$MOD.LBL_CURRENCY}</span sugar='slot'></td>
		<td><span sugar='slot18b'><select title='{$MOD.LBL_CURRENCY}' name='wiz_step2_currency_id' id='currency_id'   onchange='ConvertItems(this.options[selectedIndex].value);'>{$CURRENCY}</select></span sugar='slot'></td>
		<td scope="col"><span sugar='slot17'>{$MOD.LBL_CAMPAIGN_IMPRESSIONS}</span sugar='slot'></td>
		<td ><span sugar='slot17b'><input type="text" size="10" maxlength="15" id="impressions" name="wiz_step2_impressions" title="{$MOD.LBL_CAMPAIGN_IMPRESSIONS}" value="{$CAMP_IMPRESSIONS}"></span sugar='slot'></td></tr>
		<tr>
		<td scope="col"><span sugar='slot18'>&nbsp;</span sugar='slot'></td>
		<td><span sugar='slot18b'>&nbsp;</td>
		<td scope="col"><span sugar='slot19'>&nbsp;</span sugar='slot'></td>
		<td><span sugar='slot19b'>&nbsp;</span sugar='slot'></td>
		</tr>
		<tr>
		<td valign="top" scope="row"><span sugar='slot20'>{$MOD.LBL_CAMPAIGN_OBJECTIVE}</span sugar='slot'></td>
		<td colspan="4"><span sugar='slot20b'><textarea id="objective" name="wiz_step2_objective" title='{$MOD.LBL_CAMPAIGN_OBJECTIVE}' cols="110" rows="5">{$OBJECTIVE}</textarea></span sugar='slot'></td>
		</tr>
		<tr>
		<td >&nbsp;</td>
		<td>&nbsp;</td>
		<td >&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
	</table>
	<p>
	
	<script>
	var	num_grp_sep ='{$NUM_GRP_SEP}';
	var	dec_sep = '{$DEC_SEP}';

    /*
     * this is the custom validation script that will validate the fields on step2 of wizard
     */
	{literal}
    function validate_step2(){
        //add fields to validation and call generic validation script
        var requiredTxt = SUGAR.language.get('app_strings', 'ERR_MISSING_REQUIRED_FIELDS');
        if(validate['wizform']!='undefined'){delete validate['wizform']};
        addToValidate('wizform', 'budget', 'float', false,  document.getElementById('budget').title);
        addToValidate('wizform', 'actual_cost', 'float', false,  document.getElementById('actual_cost').title);
        addToValidate('wizform', 'expected_revenue', 'float', false,  document.getElementById('expected_revenue').title);
        addToValidate('wizform', 'expected_cost', 'float', false,  document.getElementById('expected_cost').title);
        addToValidate('wizform', 'impressions', 'float', false,  document.getElementById('impressions').title);        
		var check_date = new Date();
		oldStartsWith =84;
		return check_form('wizform');
    }    
    
	function ConvertItems(id) {
		var items = new Array();
	
		//get the items that are to be converted
		expected_revenue = document.getElementById('expected_revenue');
		budget = document.getElementById('budget');
		actual_cost = document.getElementById('actual_cost');
		expected_cost = document.getElementById('expected_cost');	
	
		//unformat the values of the items to be converted
		expected_revenue.value = unformatNumber(expected_revenue.value, num_grp_sep, dec_sep);
		expected_cost.value = unformatNumber(expected_cost.value, num_grp_sep, dec_sep);
		budget.value = unformatNumber(budget.value, num_grp_sep, dec_sep);
		actual_cost.value = unformatNumber(actual_cost.value, num_grp_sep, dec_sep);
		
		//add the items to an array
		items[items.length] = expected_revenue;
		items[items.length] = budget;
		items[items.length] = expected_cost;
		items[items.length] = actual_cost;
	
		//call function that will convert currency
		ConvertRate(id, items);
	
		//Add formatting back to items
		expected_revenue.value = formatNumber(expected_revenue.value, num_grp_sep, dec_sep);
		expected_cost.value = formatNumber(expected_cost.value, num_grp_sep, dec_sep);
		budget.value = formatNumber(budget.value, num_grp_sep, dec_sep);
		actual_cost.value = formatNumber(actual_cost.value, num_grp_sep, dec_sep);
	}    
	{/literal}
	</script>	
