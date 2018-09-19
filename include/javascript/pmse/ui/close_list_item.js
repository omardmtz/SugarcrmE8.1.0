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
var CloseListItem = function (options) {
    ListItem.call(this, options);
    CloseListItem.prototype.init.call(this, options);
};

CloseListItem.prototype = new ListItem();

CloseListItem.prototype.init = function (options) {
    this.setText(function () {
        var dv = document.createElement("div"),
            icon = document.createElement("span");
        dv.className = 'close-list-item';
        icon.className = 'icon-remove';
        dv.appendChild(icon);
        return dv;
    });
};

CloseListItem.prototype.createHTML = function () {
    ListItem.prototype.createHTML.call(this);
    $(this.html).css('background-color','#A0A0A0');
    return this.html;
};

