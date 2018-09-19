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
function set_return_teams_for_editview(popup_reply_data) {
	var form_name = popup_reply_data.form_name;
	var field_name = popup_reply_data.field_name;
	teams = popup_reply_data.teams;
	var team_values = Array();
	
	//remove any empty fields before adding more.
	var isFirstFieldEmpty = collection[form_name + '_' + field_name].clean_up();
	var index = 0;
	
	for(team_id in teams) {
		 if(teams[team_id]['team_id']) {
			 var temp_array = []; 
			 temp_array['name'] = teams[team_id]['team_name'];

			 temp_array['name'] = replaceHTMLChars(temp_array['name']);
			 
	         temp_array['id'] = teams[team_id]['team_id'];
	         if(isFirstFieldEmpty && index == 0){
	         	collection[form_name + '_' + field_name].replace_first(temp_array);
	         }else{
	         	collection[form_name + '_' + field_name].add(temp_array);
	         }
	         index++;
		 }
	}
	if(collection[form_name + '_' + field_name].more_status) {
    	collection[form_name + '_' + field_name].js_more();
        collection[form_name + '_' + field_name].show_arrow_label(true);
    }
}


function set_primary_team(form_name, element_name, primary_team) {	
    radioElement = window.document.forms[form_name][element_name];
    if(radioElement.type) {
    	radioElement.checked = true;
    } else if(primary_team == '') {
    	found_checked = false;
    	for(i=0; i < radioElement.length; i++) {
    		if(radioElement[i].checked) {
    	   		found_checked = true;
    	   		break;
    		}
    	}	 
    	
    	if(!found_checked) {
    	   radioElement[0].checked = true;	
    	}   	
    } else {
    	for(i=0; i < radioElement.length; i++) {
    		if(radioElement[i].value == primary_team) {
    	   		radioElement[i].checked = true;
    	   		break;
    		}
    	}	
    }
}


function is_primary_team_selected(form_name, element_name) {
	table_element_id = form_name + '_' + document.forms[form_name][element_name].name + '_table';
	if(document.getElementById(table_element_id)) {
		   input_elements = YAHOO.util.Selector.query('input[type=radio]', document.getElementById(table_element_id));
		   has_primary = false;

		   for(t in input_elements) {
			    primary_field_id = document.forms[form_name][element_name].name + '_collection_' + input_elements[t].value;
		        if(input_elements[t].type && input_elements[t].type == 'radio' && input_elements[t].checked == true) {
		           if(document.forms[form_name].elements[primary_field_id].value != '') {
		        	  has_primary = true;
		           }
		           break;
		        }								   
		   }   
		   return has_primary;
	} //if
	return true;
}


function get_selected_teams(form_name, element_name) {
	var selected_teams = new Array();
	selected_teams['primary'] = new Array();
	selected_teams['secondaries'] = new Array();
	
	table_element_id = form_name + '_' + document.forms[form_name][element_name].name + '_table';
	if(document.getElementById(table_element_id)) {
		   input_elements = YAHOO.util.Selector.query('input[type=radio]', document.getElementById(table_element_id));
           var secondary_count = 0;
		   
		   for(t in input_elements) {
			    primary_field_id = 'id_' + document.forms[form_name][element_name].name + '_collection_' + input_elements[t].value;
			    if(input_elements[t].type && input_elements[t].type == 'radio' && input_elements[t].checked == true) {
		           if(document.forms[form_name].elements[primary_field_id].value != '') {
		        	   selected_teams['primary'] = document.forms[form_name].elements[primary_field_id].value;
		           } 
		        } else if(document.forms[form_name].elements[primary_field_id].value != '') {
		           selected_teams['secondaries'][secondary_count++] = document.forms[form_name].elements[primary_field_id].value;
		        }
		   }
	} //if
	return selected_teams;
}