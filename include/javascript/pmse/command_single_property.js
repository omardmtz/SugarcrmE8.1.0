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
/*global jCore

 */
var CommandSingleProperty = function (receiver, options) {
    jCore.Command.call(this, receiver);
    this.propertyName = null;
    this.before = null;
    this.after = null;
    CommandSingleProperty.prototype.initObject.call(this, options);
};

CommandSingleProperty.prototype = new jCore.Command();

CommandSingleProperty.prototype.type = "commandSingleProperty";

CommandSingleProperty.prototype.initObject = function (options) {
    this.propertyName = options.propertyName;
    this.before = options.before;
    this.after = options.after;
};

CommandSingleProperty.prototype.execute = function () {
    this.receiver[this.propertyName] = this.after;
    this.receiver.canvas.triggerCommandAdam(this.receiver, [this.propertyName], [this.before], [this.after]);
    this.receiver.canvas.bpmnValidation();
};

CommandSingleProperty.prototype.undo = function () {
    this.receiver[this.propertyName] = this.before;
    this.receiver.canvas.triggerCommandAdam(this.receiver, [this.propertyName], [this.after], [this.before]);
    this.receiver.canvas.bpmnValidation();
};

CommandSingleProperty.prototype.redo = function () {
    this.execute();
};
