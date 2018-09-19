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

// $Id: email_popup_helper.js 24878 2007-07-31 19:46:34Z chris $

function send_back_selected(module, form, field, error_message, field_to_name_array)
{
	// cn: bug 12274 - stripping false-positive security envelope
	var temp_request_data = request_data;
	if(temp_request_data.jsonObject) {
		request_data = temp_request_data.jsonObject;
	} else {
		request_data = temp_request_data; // passed data that is NOT incorrectly encoded via JSON.encode();
	}
	// cn: end bug 12274 fix

	var passthru_data = Object();
	if(typeof(request_data.passthru_data) != 'undefined')
	{
		passthru_data = request_data.passthru_data;
	}
	var form_name = request_data.form_name;
	var field_to_name_array = request_data.field_to_name_array;
	var call_back_function = eval("window.opener." + request_data.call_back_function);
	
	var array_contents = Array();
	var j=0;
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == field) { 
			if (form.elements[i].checked == true) {
				++j;
				var id = form.elements[i].value;
				array_contents_row = Array();
				for(var the_key in field_to_name_array)
				{
					if(the_key != 'toJSON')
					{
						var the_name = field_to_name_array[the_key];
						var the_value = '';
			
						if(/*module != '' && */id != '')
						{
							the_value = associated_javascript_data[id][the_key.toUpperCase()];
						}
						
						array_contents_row.push('"' + the_name + '":"' + the_value + '"');
					}
				}
				eval("array_contents.push({" + array_contents_row.join(",") + "})");
			}
		}
	}
				
	var result_data = {"form_name":form_name,"name_to_value_array":array_contents};

	if (array_contents.length ==0 ) {
		window.alert(error_message);	
		return;
	}
	
	call_back_function(result_data);
	var close_popup = window.opener.get_close_popup();

	if(close_popup)
	{
		window.close();
	}
}

function send_back(module, id)
{
	var associated_row_data = associated_javascript_data[id];

	// cn: bug 12274 - stripping false-positive security envelope
	eval("var temp_request_data = " + window.document.forms['popup_query_form'].request_data.value);
	if(temp_request_data.jsonObject) {
		var request_data = temp_request_data.jsonObject;
	} else {
		var request_data = temp_request_data; // passed data that is NOT incorrectly encoded via JSON.encode();
	}
	// cn: end bug 12274 fix

	var passthru_data = Object();
	if(typeof(request_data.passthru_data) != 'undefined')
	{
		passthru_data = request_data.passthru_data;
	}
	var form_name = request_data.form_name;
	var field_to_name_array = request_data.field_to_name_array;
	var call_back_function = eval("window.opener." + request_data.call_back_function);
	var array_contents = Array();

	// constructs the array of values associated to the bean that the user clicked
	for(var the_key in field_to_name_array)
	{
		if(the_key != 'toJSON')
		{
			var the_name = field_to_name_array[the_key];
			var the_value = '';

			if(module != '' && id != '')
			{
				the_value = associated_row_data[the_key.toUpperCase()];
			}
			
			array_contents.push('"' + the_name + '":"' + the_value + '"');
		}
	}
	
	eval("var name_to_value_array = {'0' : {" + array_contents.join(",") + "}}");

	var result_data = {"form_name":form_name,"name_to_value_array":name_to_value_array,"passthru_data":passthru_data};
	var close_popup = window.opener.get_close_popup();
	
	call_back_function(result_data);

	if(close_popup)
	{
		window.close();
	}
}