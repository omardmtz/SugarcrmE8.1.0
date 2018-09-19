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

function open_contact_popup(module_name, width, height, initial_filter, close_popup, hide_clear_button, popup_request_data, popup_mode, create, metadata)
{
	// set the variables that the popup will pull from
	window.document.popup_request_data = popup_request_data;
	window.document.close_popup = close_popup;

	// launch the popup
	URL = 'index.php?mode=MultiSelect&'
		+ 'module=' + module_name
		+ '&action=ContactAddressPopup';
	
	if(initial_filter != '')
	{
		URL += '&query=true' + initial_filter;
	}
	
	if(hide_clear_button)
	{
		URL += '&hide_clear_button=true';
	}
	
	windowName = 'popup_window';
	
	windowFeatures = 'width=' + width
		+ ',height=' + height
		+ ',resizable=1,scrollbars=1';

	if (popup_mode == '' && popup_mode == 'undefined') {
		popup_mode='single';		
	}
	URL+='&mode='+popup_mode;
	if (create == '' && create == 'undefined') {
		create = 'false';
	}
	URL+='&create='+create;

	if (metadata != '' && metadata != 'undefined') {
		URL+='&metadata='+metadata;	
	}

	win = window.open(URL, windowName, windowFeatures);

	if(window.focus)
	{
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
	}

	return win;
}

function set_focus(){
	document.getElementById('name').focus();
}