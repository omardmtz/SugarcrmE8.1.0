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
 * @class PMSE.Action
 * Handle Actions
 * @extends PMSE.Base
 *
 *
 * @constructor
 * Create a new instance of the class
 * @param {Object} options
 */
PMSE.Action = function(options) {
    PMSE.Base.call(this, options);
    /**
     * Defines the text of the action
     * @type {String}
     */
    this.text = null;
    /**
     * Defines if the actions is enabled
     * @type {Boolean}
     */
    this.disabled = null;
    /**
     * Defines if the action should be showed
     * @type {Boolean}
     */
    this.hidden = null;
    /**
     * Defines the handler of the action
     * @type {Function}
     */
    this.handler = null;
    /**
     * Defines a style for the action
     * @type {String}
     */
    this.cssStyle = null;
    /**
     * Define a tooltip for action
     * @type {null}
     */
    this.toolTip = null;
    /**
     * Define an selected state
     * @type {null}
     */
    this.selected = null;
    /**
     * Defines the object associated to this action
     * @type {Object}
     */
    this.related = null;
    PMSE.Action.prototype.initObject.call(this, options);
};
PMSE.Action.prototype = new PMSE.Base();
/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Action.prototype.type = 'PMSE.Action';
/**
 * Defines the object's family
 * @type {String}
 */
PMSE.Action.prototype.family = 'PMSE.Action';

/**
 * Initialize the object with default values
 * @param {Object} options
 */
PMSE.Action.prototype.initObject = function(options) {
    var defaults = {
        text: null,
        cssStyle: null,
        disabled: false,
        hidden: false,
        selected: false,
        handler: function() {

        },
        related: null
    };
    $.extend(true, defaults, options);
    this.setText(defaults.text)
        .setCssClass(defaults.cssStyle)
        .setToolTip(defaults.toolTip)
        .setSelected(defaults.selected)
        .setDisabled(defaults.disabled)
        .setHidden(defaults.hidden)
        .setHandler(defaults.handler)
        .setRelated(defaults.related);
};

/**
 * Sets the action text property
 * @param text
 * @return {*}
 */
PMSE.Action.prototype.setText = function(text) {
    this.text = text;
    return this;
};

/**
 * Sets the action's handler
 * @param {Function} fn
 * @return {*}
 */
PMSE.Action.prototype.setHandler = function(fn) {
    if (_.isFunction(fn)) {
        this.handler = fn;
    }
    return this;
};

/**
 * Sets the CSS classes
 * @param {String} css
 * @return {*}
 */
PMSE.Action.prototype.setCssClass = function(css) {
    this.cssStyle = css;
    return this;
};

/**
 * Sets the tooltip
 * @param tooltip
 * @return {PMSE.Action}
 */
PMSE.Action.prototype.setToolTip = function(tooltip) {
    this.toolTip = tooltip;
    return this;
}

/**
 * Set the selected property
 * @param selected
 * @return {PMSE.Action}
 */
PMSE.Action.prototype.setSelected = function(selected) {
    this.selected = selected;
    return this;
}

/**
 * Sets the enabled property
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Action.prototype.setDisabled = function(value) {
    if (_.isBoolean(value)) {
        this.disabled = value;
        if (this.related) {
            if (_.isFunction(this.related.paint)) {
                this.related.paint();
            }
        }
    }
    return this;
};


/**
 * Sets the hidden property
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Action.prototype.setHidden = function(value) {
    if (_.isBoolean(value)) {
        this.hidden = value;
        if (this.related) {
            if (_.isFunction(this.related.paint)) {
                this.related.paint();
            }
        }
    }
    return this;
};

/**
 * Sets the action's associated object
 * @param {Object} relation
 * @return {*}
 */
PMSE.Action.prototype.setRelated = function(relation) {
    this.related = relation;
    return this;
};

/**
 * Turns on the action
 */
PMSE.Action.prototype.enable = function() {
    this.setDisabled(false);
};

/**
 * Turns off the action
 */
PMSE.Action.prototype.disable = function() {
    this.setDisabled(true);
};

/**
 * Shows the action
 */
PMSE.Action.prototype.hide = function() {
    this.setHidden(true);
};

/**
 * Hides the action
 */
PMSE.Action.prototype.show = function() {
    this.setHidden(false);
};

/**
 * Returns the enabled property
 * @return {Boolean}
 */
PMSE.Action.prototype.isEnabled = function() {
    return !this.disabled;
};

/**
 * Returns the hidden property
 * @return {Boolean}
 */
PMSE.Action.prototype.isHidden = function() {
    return this.hidden;
};

/**
 * Defines the action validation
 * @type {Boolean}
 */
PMSE.Action.prototype.isAction = true;

