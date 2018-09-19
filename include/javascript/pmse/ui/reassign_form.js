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
/*globals PMSE.Panel, $, PMSE.Proxy, TextField, ComboboxField, HiddenField, EmailPickerField, ItemMatrixField, MultipleItemField,
    CriteriaField, ItemUpdaterField, ExpressionField, TextareaField, CheckboxField, PMSE.Button, RadiobuttonField */
var PMSE = PMSE || {};
/**
 * @class ReassignForm
 * Handles form panels
 * @extends PMSE.Panel
 *
 * @constructor
 * Creates a new instance of the object
 * @param {Object} options
 */
var ReassignForm = function (options) {
    PMSE.Form.call(this, options);


    ReassignForm.prototype.initObject.call(this, options);
};

ReassignForm.prototype = new PMSE.Form();

/**
 * Defines the object's type
 * @type {String}
 */
ReassignForm.prototype.type = 'ReassignForm';

/**
 * Initializes the object with the default values
 */
ReassignForm.prototype.initObject = function (options) {
    var defaults = {
        columns: []
    };
    this.hasHeaders = false;
    $.extend(true, defaults, options);
    this.setColumns(defaults.columns);
};

ReassignForm.prototype.setColumns = function (columns) {
  this.columns = columns;
};