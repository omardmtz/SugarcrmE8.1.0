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

function validatePortalName(e) {
    var portalName = document.getElementById('portal_name'); 
    var portalNameExisting = document.getElementById("portal_name_existing"); 
    var portalNameVerified = document.getElementById('portal_name_verified');
	if(typeof(portalName.parentNode.lastChild) != 'undefined' &&
        portalName.parentNode.lastChild.tagName =='SPAN'){
	   portalName.parentNode.lastChild.innerHTML = '';
	}

    if(portalName.value == portalNameExisting.value) {
       return;
    }
    
	var callbackFunction = function success(data) {
	    //data.responseText contains the count of portal_name that matches input field
		count = data.responseText;	
		if(count != 0) {
		   add_error_style('form_EmailQCView_Contacts', 'portal_name', SUGAR.language.get('app_strings', 'ERR_EXISTING_PORTAL_USERNAME'));
		   for(wp = 1; wp <= 10; wp++) {
			   window.setTimeout('fade_error_style(style, ' + wp * 10 + ')', 1000 + (wp * 50));
		   }
		   portalName.focus();
		}
		
	    if(portalNameVerified.parentNode.childNodes.length > 1) {
	       portalNameVerified.parentNode.removeChild(portalNameVerified.parentNode.lastChild);
	    }
	    
        verifiedTextNode = document.createElement('span');
        verifiedTextNode.innerHTML = '';
	    portalNameVerified.parentNode.appendChild(verifiedTextNode);
	    
		portalNameVerified.value = count == 0 ? "true" : "false";
		verifyingPortalName = false;
	}

    if(portalNameVerified.parentNode.childNodes.length > 1) {
       portalNameVerified.parentNode.removeChild(portalNameVerified.parentNode.lastChild);
    }

    if(portalName.value != '' && !verifyingPortalName) {    
       document.getElementById('portal_name_verified').value = "false";
	   verifiedTextNode = document.createElement('span');
	   portalNameVerified.parentNode.appendChild(verifiedTextNode);
       verifiedTextNode.innerHTML = SUGAR.language.get('app_strings', 'LBL_VERIFY_PORTAL_NAME');
       verifyingPortalName = true;
	   var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=Contacts&action=ValidPortalUsername&portal_name=' + portalName.value, {success: callbackFunction, failure: callbackFunction});
    }
}
