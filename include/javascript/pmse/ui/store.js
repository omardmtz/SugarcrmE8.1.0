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
 * @class PMSE.Store
 * Description of the class PMSE.Store...
 * @constructor
 * Creates an instance of the class PMSE.Store
 */
PMSE.Store = function(options) {
    /**
     * Array of records defined by a model
     * @type {Array}
     */
    this.records = [];

    /**
     * The model this PMSE.Store must work with
     * @type {Object}
     */
    this.model = null;

    /**
     * The proxy of this store
     * @type {null}
     */
    this.proxy = null;

    PMSE.Store.prototype.initObject.call(this, options);
};

/**
 * The type of each instance of this class
 * @property {string}
 */
PMSE.Store.prototype.type = 'PMSE.Store';

/**
 * Initializes the element with the options given
 * @param {Object} options options for initializing the object
 */
PMSE.Store.prototype.initObject = function(options) {
    var defaults = {};
    $.extend(true, defaults, options);
};

/**
 * Adds a record to this store
 * @param record
 * @chainable
 */
PMSE.Store.prototype.addRecord = function(record) {
    this.records.push(record);
    return this;
};

/**
 * Gets a record by an index
 * @param index
 * @return {Object}
 */
PMSE.Store.prototype.getRecord = function(index) {
    return this.records[index];
};

/**
 * Gets the size of this store
 * @return {Number}
 */
PMSE.Store.prototype.getSize = function() {
    return this.records.length;
};
