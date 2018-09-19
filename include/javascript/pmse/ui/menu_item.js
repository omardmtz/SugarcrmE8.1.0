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
// jscs:disable
var PMSE = PMSE || {};
/**
 * @class PMSE.MenuItem
 * Handles the items into the menu
 * @extends PMSE.Item
 *
 * @constructor
 * Creates a new instance of the PMSE.MenuItem Class
 * @param {Object} options
 * @param {PMSE.Menu} [parent]
 */
PMSE.MenuItem = function(options, parent) {
    PMSE.Item.call(this, options, parent);
    /**
     * Defines the icon to be used into the item
     * @type {String}
     */
    this.itemAnchor = null;
    this.hasMenuActive = null;
    PMSE.MenuItem.prototype.initObject.call(this, options);
};
PMSE.MenuItem.prototype = new PMSE.Item();

/**
 * Defines the object's type
 * @type {String}
 */
PMSE.MenuItem.prototype.type = 'PMSE.MenuItem';

/**
 * Initializes the object with default values
 * @param {Object} options
 * @private
 */
PMSE.MenuItem.prototype.initObject = function(options) {
    var defaults = {
        hasMenuActive: false
    };
    $.extend(true, defaults, options);
    this.setHasMenuActive(defaults.hasMenuActive);
};


PMSE.MenuItem.prototype.setHasMenuActive = function(value) {
    this.hasMenuActive = value;
    return this;
};

PMSE.MenuItem.prototype.createHTML = function() {
    var labelSpan, iconSpan;
    PMSE.Item.prototype.createHTML.call(this);


    this.itemAnchor = this.createHTMLElement('a');
    this.itemAnchor.href = '#';

    if (this.menu) {
        this.itemAnchor.className = 'adam-item-arrow';
    }

    if (this.toolTip) {
        this.itemAnchor.title = this.toolTip;
    }

    labelSpan = this.createHTMLElement('span');
    labelSpan.innerHTML = this.label;
    labelSpan.className = "adam-label";

    iconSpan = this.createHTMLElement('span');
    iconSpan.className = 'adam-item-icon ' + this.icon;

    this.itemAnchor.appendChild(iconSpan);
    this.itemAnchor.appendChild(labelSpan);

    this.html.appendChild(this.itemAnchor);
    return this.html;

};

PMSE.MenuItem.prototype.attachListeners = function() {
    var self = this;
    if (this.html) {
        $(this.itemAnchor)
            .click(function(e) {

               e.stopPropagation();
               if (!self.menu && !self.disabled && !self.selected) {
                    self.closeMenu();
                    self.action.handler();
                }
                e.preventDefault();
            })
            .mouseover(function() {
                self.setActiveItem(true);
                self.setActiveMenu(true);
                // if (self.menu && !self.disabled) {
                //     self.menu.show(self.x + self.width, self.y);
                //     self.setHasMenuActive(true);
                // }
            })
            .mouseout(function() {
                self.setActiveItem(false);
                self.setActiveMenu(false);
                // if (self.menu && !self.disabled) {
                //     self.menu.hide();
                // }
            })
            .mouseup(function(e) {
                e.stopPropagation();
            })
            .mousedown(function(e) {
                e.stopPropagation();
            });
    }
};

PMSE.MenuItem.prototype.setActiveItem = function(value) {
    if (!this.disabled && !this.unavailable) {
        if (value) {
            if (!this.focused) {
                this.style.addClasses(['adam-item-active']);
                this.style.applyStyle();
                this.parentMenu.setCurrentItem(this);
            }
        } else {
            if (!this.hasMenuActive) {
                this.style.removeClasses(['adam-item-active']);
                this.style.applyStyle();
                this.setFocused(false);
            }
        }
    }
};

PMSE.MenuItem.prototype.setActiveMenu = function(value) {
    if (this.menu && !this.disabled) {
        if (value) {
            if (!this.focused) {
                this.menu.show(this.x + this.width, this.y);
                this.setHasMenuActive(true);
                this.setFocused(true);
            }
        } else {
            if (!this.hasMenuActive) {
                this.menu.hide();
            }
        }
    }
};
