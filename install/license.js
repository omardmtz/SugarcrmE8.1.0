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
// $Id: license.js 4260 2005-04-18 02:08:11Z bob $

function toggleLicenseAccept(){
    var theForm     = document.forms[0];

    if( theForm.setup_license_accept.checked ){
        theForm.setup_license_accept.checked = "";
    }
    else {
        theForm.setup_license_accept.checked = "yes";
    }

    toggleNextButton();
}

function toggleNextButton(){
    var theForm     = document.forms[0];
    var nextButton  = document.getElementById( "button_next" );

    if( theForm.setup_license_accept.checked ){
        nextButton.disabled = '';
        nextButton.focus();
    }
    else {
        nextButton.disabled = "disabled";
    }
}
