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
function isoUpdate( formElem ) {
    if ( typeof(js_iso4217[formElem.value]) == 'undefined' ) {
        return false;
    }

    var thisForm = formElem.form;
    var thisCurr = js_iso4217[formElem.value];
    
    if ( thisForm.name.value == '' ) {
        thisForm.name.value = thisCurr.name;
    }
    if ( thisForm.symbol.value == '' ) {
        thisForm.symbol.value = '';
        for ( var i = 0 ; i < thisCurr.unicode.length ; i++ ) {
            thisForm.symbol.value = thisForm.symbol.value + String.fromCharCode(thisCurr.unicode[i]);
        }
    }
    
    return true;
}
