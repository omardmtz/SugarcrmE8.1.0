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
 * @class PMSE.Modal
 * Handle modal divs
 * @extends PMSE.Base
 *
 * @constructor
 * Creates a new instance of the object
 * @param {Object} options
 */
PMSE.Modal = function(options) {
    PMSE.Base.call(this, options);
    /**
     * Defines the state of the modal object
     * @type {Boolean}
     */
    this.visible = null;
    /**
     * Defines the property of loading
     * @type {Boolean}
     */
    this.loaded = false;
    /**
     * Defines the HTML Element Pointer
     * @type {HTMLElement}
     */
    this.html = null;
    /**
     * Defines the click handler
     * @type {Function}
     */
    this.clickHander = null;

    PMSE.Modal.prototype.initObject.call(this, options);
};

PMSE.Modal.prototype = new PMSE.Base();

/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Modal.prototype.type = 'PMSE.Modal';

/**
 * Initializes the object with default values
 * @param {Object} options
 */
PMSE.Modal.prototype.initObject = function(options) {
    var defaults = {
        visible: false,
        clickHander: function() {}
    };
    $.extend(true, defaults, options);
    this.setVisible(defaults.visible)
        .setClickHandler(defaults.clickHander);
};

/**
 * Sets the visible property
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Modal.prototype.setVisible = function(value) {
    this.visible = value;
    return this;
};

/**
 * Sets the click handler
 * @param {Function} fn
 * @return {*}
 */
PMSE.Modal.prototype.setClickHandler = function(fn) {
    this.clickHander = fn;
    return this;
};

/**
 * Shows the modal object
 */
PMSE.Modal.prototype.show = function(child) {
    var modalDiv;
    if (!this.html) {
        modalDiv = document.createElement('div');
        modalDiv.className = 'adam-modal';
        modalDiv.id = this.id;
        this.html = modalDiv;
    }
    if (child instanceof PMSE.Element) {
        this.html.appendChild(child.getHTML());
    }
    document.body.appendChild(this.html);
    this.setVisible(true);
    if (!this.loaded) {
        this.loaded = true;
    }
};

/**
 * Hide the modal object
 */
PMSE.Modal.prototype.hide = function() {
    var parentElement;
    if (this.visible) {
        parentElement = this.html.parentElement;
        parentElement.removeChild(this.html);
        this.setVisible(false);
    }
};
