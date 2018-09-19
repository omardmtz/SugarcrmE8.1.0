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

function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}

function switchLanguage(lang) {
	var loc = window.location + "";
	loc = loc.replace(/\&login_language=[^&]*/i, "");
	loc += "&login_language=" + lang;
	window.location = loc;

}

function toggleDisplay(id){

	if(this.document.getElementById(id).style.display=='none'){
		this.document.getElementById(id).style.display='inline'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='none';
		}
        document.getElementById(id+"_options").src = 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=basic_search.gif';
        document.getElementById(id+"_options").alt = LBL_HIDEOPTIONS;/*for 508 compliance fix - label defined in login.tpl*/
	}else{
		this.document.getElementById(id).style.display='none'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='inline';
		}
	document.getElementById(id+"_options").src = 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=advanced_search.gif';	
    document.getElementById(id+"_options").alt = LBL_SHOWOPTIONS;/*for 508 compliance fix - label defined in login.tpl*/
	}
}


function generatepwd(){
	document.getElementById('generate_pwd_button').value='Please Wait';
	document.getElementById('generate_pwd_button').disabled =1;
	document.getElementById('wait_pwd_generation').innerHTML = '<img src="themes/default/images/img_loading.gif?v='+SUGAR.VERSION_MARK+'" >';
var callback;
       callback = {
			success: function(o){
			document.getElementById('generate_pwd_button').value=LBL_LOGIN_SUBMIT;
        	document.getElementById('generate_pwd_button').disabled =0;
        	document.getElementById('wait_pwd_generation').innerHTML = '';
			checkok=o.responseText;
			if (checkok.charAt(0) != '1')
				document.getElementById('generate_success').innerHTML =checkok;
			if (checkok.charAt((checkok.length)-1) == '1')
				document.getElementById('generate_success').innerHTML =LBL_REQUEST_SUBMIT;
			},
            failure: function(o){
            document.getElementById('generate_pwd_button').value= LBL_LOGIN_SUBMIT;
        	document.getElementById('generate_pwd_button').disabled =0;
			document.getElementById('wait_pwd_generation').innerHTML = '';
			alert(SUGAR.language.get('app_strings','LBL_AJAX_FAILURE'));
            }
        }   
    postData = '&to_pdf=1&module=Home&action=index&entryPoint=GeneratePassword&user_name='+document.getElementById("fp_user_name").value+'&Users0emailAddress0='+document.getElementById("fp_user_mail").value+'&link=1';
    YAHOO.util.Connect.asyncRequest('POST', 'index.php', callback, postData);   
}

//onReady, check the users browser
$(function(){

    if (SUGAR.isIECompatibilityMode()){
        $("#ie_compatibility_mode_warning").show();
    }
    else if (!SUGAR.isSupportedBrowser()){
        $("#browser_warning").show();
    }
});