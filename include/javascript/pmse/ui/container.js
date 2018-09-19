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
 * @class PMSE.Container
 * Handle Containers
 * @extends PMSE.Element
 *
 * @constructor
 * Create a new instance of the container class
 * @param {Object} options
 */
PMSE.Container = function(options) {
    PMSE.Element.call(this, options);
    /**
     * Defines the items part of the container
     * @type {Array}
     */
    this.items = [];
    /**
     * Defines the pointer to the body HTML Element
     * @type {HTMLElement}
     */
    this.body = null;
    /**
     * Defines the height for the body HTML Element, if is not specified then the body height is auto
     * @type {Number}
     */
    this.bodyHeight = null;
    PMSE.Container.prototype.initObject.call(this, options);
};

PMSE.Container.prototype = new PMSE.Element();
/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Container.prototype.type = 'PMSE.Container';
/**
 * Defines the object's family
 * @type {String}
 */
PMSE.Container.prototype.family = 'PMSE.Container';

/**
 * Initialize the object with the default values
 */
PMSE.Container.prototype.initObject = function(options) {
    var defaults = {
        items: [],
        body: null
    };
    $.extend(true, defaults, options);
    this.setItems(defaults.items)
        .setBody(defaults.body)
        .setBodyHeight(defaults.bodyHeight);
};

/**
 * Sets the items property
 * @param {Array}
 */
PMSE.Container.prototype.setItems = function(items) {
    this.items = items;
    return this;
};

PMSE.Container.prototype.setBodyHeight = function(height) {
    this.bodyHeight = height;
    if(this.body) {
        if(typeof height === 'number') {
            this.body.style.height = height + 'px';    
        } else {
            this.bodyHeight = null;
            this.body.style.height = '';
        }
        this.height = $(this.html).height();
    }
    return this;
};

/**
 * Sets the body HTML Element
 * @param {HTMLElement} html
 */
PMSE.Container.prototype.setBody = function(html) {
    this.body = html;
    return this;
};

/**
 * Returns the body HTML Element
 */
PMSE.Container.prototype.getBody = function() {
    return this.body;
};

/**
 * Creates the HTML Element
 */
PMSE.Container.prototype.createHTML = function() {
    var body;
    PMSE.Element.prototype.createHTML.call(this);
    body = this.createHTMLElement('div');
    body.className = 'j-container';
    this.html.appendChild(body);
    this.body = body;
    this.setBodyHeight(this.bodyHeight);
    return this.html;
};
