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


{literal}<script>
if(typeof JotPad == 'undefined') { // since the dashlet can be included multiple times a page, don't redefine these functions
	JotPad = function() {
	    return {
	    	/**
	    	 * Called when the textarea is blurred
	    	 */
	        blur: function(ta, id) {
	        	ajaxStatus.showStatus('{/literal}{$saving}{literal}'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	var va=YAHOO.lang.JSON.stringify(ta.value);
    	    	postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=saveText&id=' + id + '&savedText=' + va;
				var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
								  {success: JotPad.saved, failure: JotPad.saved}, postData);
	        },
		    /**
	    	 * Called when the textarea is double clicked on
	    	 */
			edit: function(divObj, id) {
				ta = document.getElementById('jotpad_textarea_' + id);
				if(SUGAR.isIE) ta.value = divObj.innerHTML.replace(/<br>/gi, "\n");
				else ta.value = divObj.innerHTML.replace(/<br>/gi, '');
				ta.value = ta.value.replace(/&amp;/, "&");
				divObj.style.display = 'none';
				ta.style.display = '';
				ta.focus();
			},
		    /**
	    	 * handle the response of the saveText method
	    	 */
	        saved: function(data) {
	        	eval(data.responseText);
	           	ajaxStatus.showStatus('{/literal}{$saved}{literal}');
	           	if(typeof result != 'undefined') {
					ta = document.getElementById('jotpad_textarea_' + result['id']);
					theDiv = document.getElementById('jotpad_' + result['id']);
					theDiv.innerHTML = result['savedText'];
				}
				ta.style.display = 'none';
				theDiv.style.display = '';
	           	window.setTimeout('ajaxStatus.hideStatus()', 2000);
	        }
	    };
	}();
}
</script>{/literal}