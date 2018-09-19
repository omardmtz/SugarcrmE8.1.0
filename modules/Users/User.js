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
// $Id: User.js 54569 2010-02-17 19:28:11Z jmertic $

function clearInboundSettings() {
	var url = document.getElementById('server_url');
	var user = document.getElementById('email_user');
	var prot = document.getElementById('protocol');
	var pass = document.getElementById('email_password');
	var port = document.getElementById('port');
	var inbox = document.getElementById('mailbox');
	
	url.value = '';
	user.value ='';
	pass.value = '';
	port.value = '';
	inbox.value = '';
	
	for(i=0; i<prot.options.length; i++) {
		if(prot.options[i].value == '') {
			prot.options[i].selected = true;
		}
	}
}

function checkInboundEmailSettings() {
	var url = document.getElementById('server_url');
	var user = document.getElementById('email_user');
	var prot = document.getElementById('protocol');
	var pass = document.getElementById('email_password');
	var port = document.getElementById('port');
	var inbox = document.getElementById('mailbox');
	var doCheck = false;
	var IEAlert = SUGAR.language.get('Users', 'ERR_IE_MISSING_REQUIRED');
	
	if(url.value != '') {
		doCheck = true;
	} else if(user.value != '') {
		doCheck = true;
	} else if(prot.value != '') {
		doCheck = true;
	} else if(pass.value != '') {
		doCheck = true;
	} else if(port.value != '') {
		doCheck = true;
	}

	if(doCheck == true) {
		if(url.value == '' || url.value == 'undefined') {
			alert(IEAlert);
			return false;
		} else if(user.value == '' || user.value == 'undefined') {
			alert(IEAlert);
			return false;
		} else if(prot.value == '' || prot.value == 'undefined') {
			alert(IEAlert);
			return false;
		} else if(pass.value == '' || pass.value == 'undefined') {
			alert(IEAlert);
			return false;
		} else if(port.value == '' || port.value == 'undefined') {
			alert(IEAlert);
			return false;
		} else if(inbox.value == '' || inbox.value == 'undefined') {
			alert(IEAlert);
			return false;
		}
	}
	
	return true;
}


function show_main() {
	var basic = document.getElementById('basic'); basic.style.display = "";
	var settings = document.getElementById('settings'); settings.style.display = "";
	var info = document.getElementById('information'); info.style.display = "";
	var address = document.getElementById('address'); address.style.display = "";
	var calendar_options = document.getElementById('calendar_options'); calendar_options.style.display = "";
	var edit_tabs = document.getElementById('edit_tabs'); edit_tabs.style.display = "";
	
	var email_options = document.getElementById('email_options'); email_options.style.display = 'none';
	var email_inbound = document.getElementById('email_inbound'); email_inbound.style.display = 'none';
}

function show_email() {
	var basic = document.getElementById('basic'); basic.style.display = "none";
	var settings = document.getElementById('settings'); settings.style.display = "none";
	var info = document.getElementById('information'); info.style.display = "none";
	var address = document.getElementById('address'); address.style.display = "none";
	var calendar_options = document.getElementById('calendar_options'); calendar_options.style.display = "none";
	var edit_tabs = document.getElementById('edit_tabs'); edit_tabs.style.display = "none";
	
	var email_options = document.getElementById('email_options'); email_options.style.display = "";
	var email_inbound = document.getElementById('email_inbound'); email_inbound.style.display = "";
}


function enable_change_password_button() {
	var butt = document.getElementById('change_password_button');
	if(document.EditView.record.value != "" && document.EditView.record.value != 'undefined') {
		butt.style.display = '';
	}
}

function refresh_signature_list(signature_id, signature_name) {
	var field=document.getElementById('signature_id');
	var bfound=0;
	for (var i=0; i < field.options.length; i++) {
			if (field.options[i].value == signature_id) {
				if (field.options[i].selected==false) {
					field.options[i].selected=true;
				}
				bfound=1;
			}
	}
	//add item to selection list.
	if (bfound == 0) {
		var newElement=document.createElement('option');
		newElement.text=signature_name;
		newElement.value=signature_id;
		field.options.add(newElement);
		newElement.selected=true;
	}	

	//enable the edit button.
	var field1=document.getElementById('edit_sig');
	field1.style.visibility="visible";
}

function setSigEditButtonVisibility() {
	var field = document.getElementById('signature_id');
	var editButt = document.getElementById('edit_sig');
	if(field.value != '') {
		editButt.style.visibility = "visible";
	} else {
		editButt.style.visibility = "hidden";
	} 
}

function open_email_signature_form(record, the_user_id) {
	URL="index.php?module=Users&action=Popup";
	if(record != "") {
		URL += "&record="+record;
	}
	if(the_user_id != "") {
		URL += "&the_user_id="+the_user_id;
	}
	windowName = 'email_signature';
	windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

	win = window.open(URL, windowName, windowFeatures);
	if(window.focus) {
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
	}
}

function setDefaultSigId(id) {
	var checkbox = document.getElementById("signature_default");
	var default_sig = document.getElementById("signatureDefault");
	
	if(checkbox.checked) {
		default_sig.value = id;
	} else {
		default_sig.value = "";
	}
}
