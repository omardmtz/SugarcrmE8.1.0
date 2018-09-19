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

// $Id: dashlets.js 24711 2007-07-27 01:51:57Z awu $

SUGAR.dashlets = function() {
	return {
		/**
		 * Generic javascript method to use post a form 
		 * 
		 * @param object theForm pointer to the form object
		 * @param function callback function to call after for form is sent
		 *
		 * @return bool false
		 */ 
		postForm: function(theForm, callback) {	
			var success = function(data) {
				if(data) {
					callback(data.responseText);
				}
			}
			YAHOO.util.Connect.setForm(theForm); 
			var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php', {success: success, failure: success});
			return false;
		},
		/**
		 * Generic javascript method to use Dashlet methods
		 * 
		 * @param string dashletId Id of the dashlet being call
		 * @param string methodName method to be called (function in the dashlet class)
		 * @param string postData data to send (eg foo=bar&foo2=bar2...)
		 * @param bool refreshAfter refreash the dashlet after sending data
		 * @param function callback function to be called after dashlet is refreshed (or not refresed) 
		 */ 
		callMethod: function(dashletId, methodName, postData, refreshAfter, callback) {
        	ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'));
        	response = function(data) {
        		ajaxStatus.hideStatus();
				if(refreshAfter) SUGAR.mySugar.retrieveDashlet(dashletId);
				if(callback) {
					callback(data.responseText);
				}
        	}
	    	post = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=' + methodName + '&id=' + dashletId + '&' + postData;
			var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
							  {success: response, failure: response}, post);
		}
	 };
}();

if(SUGAR.util.isTouchScreen() && typeof iScroll == 'undefined') {

	with (document.getElementsByTagName("head")[0].appendChild(document.createElement("script")))
	{
		setAttribute("id", "newScript", 0);
		setAttribute("type", "text/javascript", 0);
		setAttribute("src", "include/javascript/iscroll.js?v="+SUGAR.VERSION_MARK, 0);
	}

}