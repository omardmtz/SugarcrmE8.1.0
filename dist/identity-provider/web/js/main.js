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

var getLocalLoginButton = function () {
    return document.getElementById('login_btn');
};

var submitForm = function (e) {
    e.preventDefault();
    document.getElementById('login_section').submit();
};

//need to separate functions because touchpad fires key down events
var onInputKeyDown = function (e) {
    if (e && ((e.keyCode && e.keyCode != 13) || !e.keyCode)) {
        return;
    }
    submitForm(e);
};

var onDOMContentLoaded = function (e) {
    getLocalLoginButton().onclick = submitForm;

    // Auto-hide alert after timeout.
    var alertContainer = document.getElementById('alert-message-container');
    if (alertContainer) {
        setTimeout(function () {
            alertContainer.style.display = 'none';
        }, 2000);
    }
};

document.addEventListener("DOMContentLoaded", onDOMContentLoaded);
