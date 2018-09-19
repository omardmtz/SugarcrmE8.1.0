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

////////////////////////////////////////////// 
// TEMPLATE:
////////////////////////////////////////////// 
$js_loaded = false;
function template_groups_chooser(&$args) {
	global $mod_strings,$js_loaded;
  	$table_style = '';
  	if (!empty($args['display'])) {
  		$table_style = "display: ".$args['display']."\"";
  	}
	$uparraow_big = SugarThemeRegistry::current()->getImage('uparrow_big','border="0" style="margin-bottom: 1px;"'
,null,null,'.gif',$mod_strings['LBL_SORT']);
	$downarrow_big = SugarThemeRegistry::current()->getImage('downarrow_big','border="0" style="margin-top: 1px;" ',null,null,'.gif',$mod_strings['LBL_SORT']);
	$leftarrow_big = SugarThemeRegistry::current()->getImage('leftarrow_big','border="0" style="margin-right: 1px;"'
,null,null,'.gif',$mod_strings['LBL_SORT']);
	$rightarrow_big = SugarThemeRegistry::current()->getImage('rightarrow_big','border="0" style="margin-left: 1px;"',null,null,'.gif',$mod_strings['LBL_SORT']);

	
	$smarty = new Sugar_Smarty();
	$smarty->assign('args', $args);
	$smarty->assign('js_loaded', $js_loaded);
	$smarty->assign('table_style', $table_style);
	$smarty->assign('uparraow_big', $uparraow_big);
	$smarty->assign('downarrow_big', $downarrow_big);
	$smarty->assign('leftarrow_big', $leftarrow_big);
	$smarty->assign('rightarrow_big', $rightarrow_big);
	$smarty->assign('table_style', $table_style);
 	if ($js_loaded == false) {
  		$js_loaded = true; 		
 	}
	return $smarty->fetch("modules/Reports/templates/_template_groups_chooser.tpl");
} // fn

////////////////////////////////////////////// 
// TEMPLATE:
////////////////////////////////////////////// 
function template_groups_chooser_js(&$args)
{
?>
<script language="javascript">
var object_refs = new Object();

function left_to_right(left_name,right_name,id) 
{
	var display_columns_ref = object_refs[left_name];
	var hidden_columns_ref = object_refs[right_name];

	var left_td = document.getElementById(left_name+'_td');
	var right_td = document.getElementById(right_name+'_td');

	var selected_left = new Array();
	var notselected_left = new Array();
	var notselected_right = new Array();

	var left_array = new Array();

        // determine which options are selected in left 
	for (i=0; i < display_columns_ref.options.length; i++)
	{
		if ( display_columns_ref.options[i].selected == true) 
		{
			selected_left[selected_left.length] = {text: display_columns_ref.options[i].text, value: display_columns_ref.options[i].value};
		}
		else
		{
			notselected_left[notselected_left.length] = {text: display_columns_ref.options[i].text, value: display_columns_ref.options[i].value};
      if ( typeof(display_columns_ref.options[i].saved_text) != 'undefined')
      {
        notselected_left[notselected_left.length - 1].saved_text =  display_columns_ref.options[i].saved_text;     
      }

		}
		
	}

	for (i=0; i < hidden_columns_ref.options.length; i++)
	{
		notselected_right[notselected_right.length] = {text:hidden_columns_ref.options[i].text, value:hidden_columns_ref.options[i].value};
		
	}

	var left_select_html_info = new Object();
	var left_options = new Array();
	var left_select = new Object();

	left_select['name'] = left_name+'[]';
	left_select['id'] = left_name;
	left_select['size'] = '10';
	left_select['multiple'] = 'true';

	var right_select_html_info = new Object();
	var right_options = new Array();
	var right_select = new Object();

	right_select['name'] = right_name+'[]';
	right_select['id'] = right_name;
	right_select['size'] = '10';
	right_select['multiple'] = 'true';

	for (i=0;i < notselected_right.length;i++)
	{
		right_options[right_options.length] = notselected_right[i];	
	}

	for (i=0;i < selected_left.length;i++)
	{
		right_options[right_options.length] = selected_left[i];	
	}
	for (i=0;i < notselected_left.length;i++)
	{
		left_options[left_options.length] = notselected_left[i];	
	}
	left_select_html_info['options'] = left_options;
	left_select_html_info['select'] = left_select;
	right_select_html_info['options'] = right_options;
	right_select_html_info['select'] = right_select;

	var left_html = buildSelectHTML(left_select_html_info);
	var right_html = buildSelectHTML(right_select_html_info);


	left_td.innerHTML = left_html;
	right_td.innerHTML = right_html;

	addSelectOptionAttrs(left_select_html_info,left_td.getElementsByTagName('select')[0]);
	addSelectOptionAttrs(right_select_html_info,right_td.getElementsByTagName('select')[0]);

	object_refs[left_name] = left_td.getElementsByTagName('select')[0];
	object_refs[right_name] = right_td.getElementsByTagName('select')[0];
	try
	{
		eval("onmoveright_"+id+"();");
	} catch(e) {
	}

}


function right_to_left(left_name,right_name,id) 
{
	var display_columns_ref = object_refs[left_name];
	var hidden_columns_ref = object_refs[right_name];

	var left_td = document.getElementById(left_name+'_td');
	var right_td = document.getElementById(right_name+'_td');

	var selected_right = new Array();
	var notselected_right = new Array();
	var notselected_left = new Array();

	for (i=0; i < hidden_columns_ref.options.length; i++)
	{
		if (hidden_columns_ref.options[i].selected == true) 
		{
			selected_right[selected_right.length] = {text:hidden_columns_ref.options[i].text, value:hidden_columns_ref.options[i].value};
		}
		else
		{
			notselected_right[notselected_right.length] = {text:hidden_columns_ref.options[i].text, value:hidden_columns_ref.options[i].value};
		}
		
	}

	for (i=0; i < display_columns_ref.options.length; i++)
	{
		notselected_left[notselected_left.length] = {text:display_columns_ref.options[i].text, value:display_columns_ref.options[i].value};

    if ( typeof(display_columns_ref.options[i].saved_text) != 'undefined')
    {
		  notselected_left[notselected_left.length - 1].saved_text =  display_columns_ref.options[i].saved_text;
    }
		
	}

	var left_select_html_info = new Object();
	var left_options = new Array();
	var left_select = new Object();

	left_select['name'] = left_name+'[]';
	left_select['id'] = left_name;
	left_select['multiple'] = 'true';
	left_select['size'] = '10';

	var right_select_html_info = new Object();
	var right_options = new Array();
	var right_select = new Object();

	right_select['name'] = right_name+ '[]';
	right_select['id'] = right_name;
	right_select['multiple'] = 'true';
	right_select['size'] = '10';

	for (i=0;i < notselected_left.length;i++)
	{
		left_options[left_options.length] = notselected_left[i];	
	}

	for (i=0;i < selected_right.length;i++)
	{
		left_options[left_options.length] = selected_right[i];	
	}
	for (i=0;i < notselected_right.length;i++)
	{
		right_options[right_options.length] = notselected_right[i];	
	}
	left_select_html_info['options'] = left_options;
	left_select_html_info['select'] = left_select;
	right_select_html_info['options'] = right_options;
	right_select_html_info['select'] = right_select;

	var left_html = buildSelectHTML(left_select_html_info);
	var right_html = buildSelectHTML(right_select_html_info);

	left_td.innerHTML = left_html;
	right_td.innerHTML = right_html;

	addSelectOptionAttrs(left_select_html_info,left_td.getElementsByTagName('select')[0]);
	addSelectOptionAttrs(right_select_html_info,right_td.getElementsByTagName('select')[0]);

	object_refs[left_name] = left_td.getElementsByTagName('select')[0];
	object_refs[right_name] = right_td.getElementsByTagName('select')[0];
	try
	{
		eval("onmoveleft_"+id+"();");
	} catch(e) {
	}
	

}


function up(name,id) 
{
	var td = document.getElementById(name+'_td');
	var obj = td.getElementsByTagName('select')[0];
	obj = (typeof obj == "string") ? document.getElementById(obj) : obj;
	if (obj.tagName.toLowerCase() != "select" && obj.length < 2)
		return false;
	var sel = new Array();

	for (i=0; i<obj.length; i++) {
		if (obj[i].selected == true) {
			sel[sel.length] = i;
		}
	}
	for (i in sel) {
		if (sel[i] != 0 && !obj[sel[i]-1].selected) {
			var tmp = new Array(obj[sel[i]-1].text, obj[sel[i]-1].value, obj[sel[i]-1].saved_text);
			obj[sel[i]-1].text = obj[sel[i]].text;
			obj[sel[i]-1].saved_text = obj[sel[i]].saved_text;
			obj[sel[i]-1].value = obj[sel[i]].value;
			obj[sel[i]].text = tmp[0];
			obj[sel[i]].value = tmp[1];
			obj[sel[i]].saved_text = tmp[2];
			obj[sel[i]-1].selected = true;
			obj[sel[i]].selected = false;
		}
	}
}


function down(name,id) 
{
	var td = document.getElementById(name+'_td');
	var obj = td.getElementsByTagName('select')[0];
	if (obj.tagName.toLowerCase() != "select" && obj.length < 2)
		return false;
	var sel = new Array();
	for (i=obj.length-1; i>-1; i--) {
		if (obj[i].selected == true) {
			sel[sel.length] = i;
		}
	}
	for (i in sel) {
		if (sel[i] != obj.length-1 && !obj[sel[i]+1].selected) {
			var tmp = new Array(obj[sel[i]+1].text, obj[sel[i]+1].value, obj[sel[i]+1].saved_text);
			obj[sel[i]+1].text = obj[sel[i]].text;
			obj[sel[i]+1].saved_text = obj[sel[i]].saved_text;
			obj[sel[i]+1].value = obj[sel[i]].value;
			obj[sel[i]].text = tmp[0];
			obj[sel[i]].value = tmp[1];
			obj[sel[i]].saved_text = tmp[2];
			obj[sel[i]+1].selected = true;
			obj[sel[i]].selected = false;
		}
	}
}

</script>
<?php
}

?>
