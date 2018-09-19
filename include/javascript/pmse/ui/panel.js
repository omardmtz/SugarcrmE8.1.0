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
 * @class PMSE.Panel
 * Handles panels to be inserted into containers
 * @extends PMSE.Container
 *
 * @constructor
 * Creates a new instance of the object
 * @param {Object} options
 */
PMSE.Panel = function(options) {
    PMSE.Container.call(this, options);
    /**
     * Defines the header HTML element
     * @type {HTMLElement}
     */
    this.header = null;
    /**
     * Defines the footer HTML Element
     * @type {HTMLElement}
     */
    this.footer = null;
    /**
     * Defines the layout object
     * @type {PMSE.Layout}
     */
    this.layout = null;

    this.language = {};
    PMSE.Panel.prototype.initObject.call(this, options);
};

PMSE.Panel.prototype = new PMSE.Container();
/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Panel.prototype.type = 'PMSE.Panel';

/**
 * Defines the object's family
 * @type {String}
 */
PMSE.Panel.prototype.family = 'PMSE.Panel';

/**
 * Initializes the object with the default values
 */
PMSE.Panel.prototype.initObject = function(options) {
    var defaults = {
        layout: null
    };
    $.extend(true, defaults, options);
    this.setHeader(defaults.header)
        .setFooter(defaults.footer)
        .setLayout(defaults.layout);
};

/**
 * Sets the header HTML element
 * @param {HTMLElement} h
 */
PMSE.Panel.prototype.setHeader = function(h) {
    this.header = h;
    return this;
};

/**
 * Sets the header HTML element
 * @param {HTMLElement} f
 */
PMSE.Panel.prototype.setFooter = function(f) {
    this.footer = f;
    return this;
};

/**
 * Sets the header HTML element
 * @param {PMSE.Layout} layout
 */
PMSE.Panel.prototype.setLayout = function(layout) {
    if (layout && layout.family && layout.family === 'PMSE.Layout') {
        this.layout = layout;
    } else {
        this.layout = new PMSE.Layout(layout);
    }
    return this;
};

PMSE.Panel.prototype.createHTML = function() {
    var headerDiv, footerDiv;
    PMSE.Container.prototype.createHTML.call(this);
    this.style.removeProperties(['width', 'height', 'position', 'top', 'left', 'z-index']);
    this.style.addClasses(['adam-panel']);
    if (this.header) {
        this.html.insertBefore(this.header, this.body);
    } else {
        headerDiv = this.createHTMLElement('div');
        headerDiv.className = 'adam-panel-header';
        this.html.insertBefore(headerDiv, this.body);
        this.header = headerDiv;
    }
    if (this.footer) {
        this.html.appendChild(this.footer);
    } else {
        footerDiv = this.createHTMLElement('div');
        footerDiv.className = 'adam-panel-footer';
        this.html.appendChild(footerDiv);
        this.footer = footerDiv;
    }
    this.body.className = 'adam-panel-body';
    return this.html;
};

PMSE.Panel.prototype.load = function() {

};
