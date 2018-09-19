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
var PMSE = PMSE || {};
/**
 * @class SeparatorItem
 * Handles the menu item separator
 * @extends PMSE.Item
 *
 * @constructor
 * Creates a new instance of a class
 * @param {Object} options
 * @param {PMSE.Menu} [parent]
 */
var SeparatorItem = function (options, parent) {
    PMSE.Item.call(this, options, parent);
};
SeparatorItem.prototype = new PMSE.Item();

/**
 * Defines the object's type
 * @type {String}
 */
SeparatorItem.prototype.type = "SeparatorItem";

/**
 * Creates the HTML
 * @return {HTMLElement}
 */
SeparatorItem.prototype.createHTML = function () {
    var spanSep, itemSep;

    itemSep = this.createHTMLElement('li');
    itemSep.className = 'adam-item-separator';

    spanSep = this.createHTMLElement('span');
    spanSep.className = 'adam-separator';
    spanSep.innerHTML = " ";

    itemSep.appendChild(spanSep);
    this.html = itemSep;

    return this.html;
};
