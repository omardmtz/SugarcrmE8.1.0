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
 * @class PMSE.Validator
 * Handles the validations of the fields
 * @extends PMSE.Base
 *
 * @constructor
 * Create a new instance of the class
 * @param {Object} options
 * @param {PMSE.Field} parent
 */
PMSE.Validator = function(options, parent) {
    PMSE.Base.call(this, options);
    /**
     * Defines the PMSE.Field parent
     * @type {PMSE.Field}
     */
    this.parent = null;
    /**
     * Defines the criteria object
     * @type {Object}
     */
    this.criteria = null;
    /**
     * Defines if the object is validated
     * @type {Boolean}
     */
    this.validated = false;
    /**
     * Defines the validation state
     * @type {null/Boolean}
     */
    this.valid = null;
    /**
     * Defines the error message to show in case of the validation fails
     * @type {null/Boolean}
     */
    this.errorMessage = null;
    PMSE.Validator.prototype.initObject.call(this, options, parent);
};
PMSE.Validator.prototype = new PMSE.Base();

/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Validator.prototype.type = 'PMSE.Validator';

/**
 * Defines the object's family
 * @type {String}
 */
PMSE.Validator.prototype.family = 'PMSE.Validator';

/**
 * Initializes the object with default values
 * @param {Object} options
 * @param {PMSE.Field} parent
 */
PMSE.Validator.prototype.initObject = function(options, parent) {
    var defaults = {
        criteria: null,
        errorMessage: 'the validation has failed'
    };
    $.extend(true, defaults, options);
    this.setCriteria(defaults.criteria)
        .setParent(parent)
        .setErrorMessage(defaults.errorMessage);
};

/**
 * Sets the validation error message to show in case of the validation fails
 * @param {String} errorMessage
 * @return {*}
 */
PMSE.Validator.prototype.setErrorMessage = function(errorMessage) {
    this.errorMessage = errorMessage;
    return this;
};

/**
 * GSets the validation error message to show in case of the validation fails
 * @param {String} errorMessage
 * @return {*}
 */
PMSE.Validator.prototype.getErrorMessage = function() {
    return this.errorMessage;
};

/**
 * Sets the validation criteria
 * @param {Object} criteria
 * @return {*}
 */
PMSE.Validator.prototype.setCriteria = function(criteria) {
    this.criteria = criteria;
    return this;
};

/**
 * Sets the parent field
 * @param {PMSE.Field} parent
 * @return {*}
 */
PMSE.Validator.prototype.setParent = function(parent) {
    this.parent = parent;
    return this;
};

/**
 * Evaluates the validator
 */
PMSE.Validator.prototype.validate = function() {
    this.valid = true;
};

/**
 * Returns the validation response
 * @return {*}
 */
PMSE.Validator.prototype.isValid = function() {
    this.validate();
    return this.valid;
};
