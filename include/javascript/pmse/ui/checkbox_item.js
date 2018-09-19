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
 * @class CheckboxItem
 * Handle checkboxes into the context menu
 * @extends PMSE.Item
 *
 *
 * @constructor
 * Creates a new instance of this class
 * @param {Object} options
 * @param {PMSE.Menu} [parent]
 */
var CheckboxItem = function (options, parent) {
    PMSE.Item.call(this, options, parent);
    /**
     * Defines the checkbox's status
     * @type {Boolean}
     */
    this.checked = null;
    this.itemAnchor = null;
    CheckboxItem.prototype.initObject.call(this, options);
};
CheckboxItem.prototype = new PMSE.Item();

/**
 * Defines the object's type
 * @type {String}
 */
CheckboxItem.prototype.type = "CheckboxItem";

/**
 * Initializes the object with the default values
 * @param {Object} options
 * @private
 */
CheckboxItem.prototype.initObject = function (options) {
    var defaults = {
        checked: false
    };
    $.extend(true, defaults, options);
    this.setChecked(defaults.checked);
};

/**
 * Sets the checkbox checked property
 * @param {Boolean} value
 * @return {*}
 */
CheckboxItem.prototype.setChecked = function (value) {
    if (_.isBoolean(value)) {
        this.checked = value;
    }
    return this;
};

CheckboxItem.prototype.createHTML = function () {
    var labelSpan, iconSpan;
    PMSE.Item.prototype.createHTML.call(this);

    this.itemAnchor = this.createHTMLElement('a');
    this.itemAnchor.href = "#";

    labelSpan = this.createHTMLElement('span');
    labelSpan.innerHTML = this.label;
    labelSpan.className = "adam-label";

    iconSpan = this.createHTMLElement('span');
    iconSpan.className = (this.checked) ? 'adam-check-checked' : 'adam-check-unchecked';

    this.itemAnchor.appendChild(iconSpan);
    this.itemAnchor.appendChild(labelSpan);

    this.html.appendChild(this.itemAnchor);
    return this.html;
};

CheckboxItem.prototype.attachListeners = function () {
    var self = this;
    if (this.html) {
        $(this.itemAnchor)
            .click(function (e) {
                e.stopPropagation();
                if (!self.disabled) {
                    self.closeMenu();
                    self.action.handler(!self.checked);
                }
            })
            .mouseover(function () {
                self.setActiveItem(true);
            })
            .mouseout(function () {
                self.setActiveItem(false);
            })
            .mouseup(function (e) {
                e.stopPropagation();
            })
            .mousedown(function (e) {
                e.stopPropagation();
            });
    }
};

CheckboxItem.prototype.setActiveItem = function (value) {
    if (!this.disabled && !this.unavailable) {
        if (value) {
            this.style.addClasses(['adam-item-active']);
            this.style.applyStyle();
            this.parentMenu.setCurrentItem(this);
        } else {
            this.style.removeClasses(['adam-item-active']);
            this.style.applyStyle();
        }
    }
};
