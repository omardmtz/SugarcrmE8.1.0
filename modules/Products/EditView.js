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

// $Id: EditView.js 42645 2008-12-18 21:41:08Z awu $


function get_popup_product(formName)
{
    open_popup('ProductTemplates', '600', '400', '&form=EditView&tree=ProductsProd', 'true', 'false',{"call_back_function":"set_product_type_return","form_name":formName,"field_to_name_array":{"id":"product_template_id","name":"name"}});
}



function set_product_type_return(popup_reply_data)
{
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	
	if (typeof (name_to_value_array['product_template_id']) != 'undefined') {
		var post_data = {"module":"ProductTemplates","record":name_to_value_array['product_template_id'],"method":"retrieve","id":name_to_value_array['product_template_id']};
	
		var global_rpcClient =  new SugarRPCClient();
	    result = global_rpcClient.call_method('retrieve',post_data,true);
	    if (result.status == 'success') {
			for (var the_key in result.record.fields)
				{
					if (typeof(window.document.forms[form_name].elements[the_key]) != 'undefined') {
						eval('var the_value=result.record.fields.' + the_key);
						if(the_key == 'cost_price' || the_key == 'list_price' || the_key == 'discount_price')
						{
							//cma -Fix bug 20372.
							the_value = formatNumber(the_value, num_grp_sep, dec_sep,'2','2');
						}
						
						window.document.forms[form_name].elements[the_key].value=the_value;
					}
				}	    	
	    }
	}

}