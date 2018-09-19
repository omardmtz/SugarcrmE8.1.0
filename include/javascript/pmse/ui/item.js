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
 * @class PMSE.Item
 * Handles a menu item
 * @extends PMSE.Element
 *
 * @constructor
 * Creates a new instance of the class
 * @param {(Object|PMSE.Action)} options
 * @param {PMSE.Menu} [parent]
 */
PMSE.Item = function(options, parent) {
    PMSE.Element.call(this, options);
    /**
     * Defines the Label of the item
     * @type {String}
     */
    this.label = null;
    /**
     * Defines the action associated
     * @type {PMSE.Action}
     */
    this.action = null;
    /**
     * Defines the parent menu associated
     * @type {PMSE.Menu}
     */
    this.parentMenu = null;
    /**
     * Defines the child menu associated
     * @type {PMSE.Menu}
     */
    this.menu = null;
    /**
     * Defines the tooltip value
     * @type {String}
     */
    this.toolTip = null;
    /**
     * Defines the tooltip value
     * @type {String}
     */
    this.toolTip = null;
    /**
     * Define an selected state
     * @type {null}
     */
    this.selected = null;

    this.disabled = null;

    this.focused = null;

    this.icon = null;

    PMSE.Item.prototype.initObject.call(this, options, parent);
};
PMSE.Item.prototype = new PMSE.Element();

/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Item.prototype.type = 'PMSE.Item';

/**
 * Defines the object's family
 * @type {String}
 */
PMSE.Item.prototype.family = 'PMSE.Item';

/**
 * Initialize the object with the default values
 * @param {(Object|PMSE.Action)} options
 */
PMSE.Item.prototype.initObject = function(options, parent) {

    var defaults = {
        label: null,
        menu: null,
        toolTip: null,
        selected: false,
        parentMenu: parent || null,
        disabled: false,
        focused: false,
        icon: 'adam-menu-icon-empty'
    };
    if (options && options.isAction) {
        this.loadAction(options, parent);
    } else {
        $.extend(true, defaults, options);
        this.setLabel(defaults.label)
            .setToolTip(defaults.toolTip)
            .setSelected(defaults.selected)
            .setParentMenu(defaults.parentMenu)
            .setDisabled(defaults.disabled)
            .setIcon(defaults.icon)
            .setFocused(defaults.focused);
        if (!defaults.action) {
            this.action = new PMSE.Action({
                text: defaults.label,
                cssStyle: defaults.icon,
                handler: defaults.handler
            });
        }
        if (defaults.menu) {
            this.setChildMenu(defaults.menu);
        }
    }
};

/**
 * Loads the action to the item
 * @param {PMSE.Action} action
 */
PMSE.Item.prototype.loadAction = function(action, parent) {
    this.action = action;
    this.setLabel(this.action.text);
    this.setIcon(this.action.cssStyle);
    this.setToolTip(this.action.toolTip);
    this.setSelected(this.action.selected);
    this.setDisabled(this.action.disabled);
    this.setParentMenu(parent);
    this.setFocused(false);
    if (action.menu) {
        this.setChildMenu(action.menu);
    }
};

/**
 * Sets the item's label
 * @param {String} text
 * @return {*}
 */
PMSE.Item.prototype.setLabel = function(text) {
    this.label = text;
    if (this.action) {
        this.action.setText(text);
    }
    return this;
};

PMSE.Item.prototype.setIcon = function(icon) {
    this.icon = icon;
    if (this.action) {
        this.action.setCssClass(icon);
    }
    return this;
};



/**
 * Defines the way to paint this item
 */
PMSE.Item.prototype.paint = function() {
    //TODO Implement this class
};

/**
 * Sets the parent menu
 * @param {PMSE.Menu} parent
 * @return {*}
 */
PMSE.Item.prototype.setParentMenu = function(parent) {
    this.parentMenu = parent;
    return this;
};

/**
 * Sets the child PMSE.Menu
 * @param {PMSE.Menu} child
 * @return {*}
 */
PMSE.Item.prototype.setChildMenu = function(child) {
    if (child instanceof PMSE.Menu) {
        //child.setParentMenu(this.parentMenu);
        child.setCanvas(this.parentMenu.canvas);
        child.setParent(this);
        this.menu = child;
    } else {
        //child.parentMenu = this.parentMenu;
        child.canvas = this.parentMenu.canvas;
        child.parent = this;
        this.menu = new PMSE.Menu(child);
    }
    return this;
};

PMSE.Item.prototype.setDisabled = function(value) {
    this.disabled = value;
    return this;
};

PMSE.Item.prototype.setFocused = function(value) {
    this.focused = value;
    return this;
};

/**
 * Sets the tool tip value
 * @param {String} value
 * @return {*}
 */
PMSE.Item.prototype.setToolTip = function(value) {
    this.toolTip = value;
    return this;
};

/**
 * Sets the selected value
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Item.prototype.setSelected = function(value) {
    this.selected = value;
    return this;
};

PMSE.Item.prototype.createHTML = function() {
    var li;
    li = this.createHTMLElement('li');
    li.className = 'adam-item';
    if (this.selected) {
        li.className = li.className + ' adam-selected';
    } else if (this.disabled) {
        li.className = li.className + ' adam-disabled';
    }
    li.id = UITools.getIndex();
    this.html = li;
    return this.html;
};

PMSE.Item.prototype.attachListeners = function() {

};
PMSE.Item.prototype.closeMenu = function() {
    if (this.parentMenu && this.parentMenu.canvas && this.parentMenu.canvas.currentMenu) {
        this.parentMenu.canvas.currentMenu.hide();
    }
};
