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
var FieldPanelItem = function(settings) {
	PMSE.Element.call(this, settings);
	this._parent = null;
	this.onValueAction = null;
	FieldPanelItem.prototype.init.call(this, settings);
};

FieldPanelItem.prototype = new PMSE.Element();
FieldPanelItem.prototype.constructor = FieldPanelItem;

FieldPanelItem.prototype.family = "FieldPanelItem";
FieldPanelItem.prototype.type = "FieldPanelItem";

FieldPanelItem.prototype.init = function (settings) {
	var defaults = {
		parent: null,
		onValueAction: null
	};

	jQuery.extend(true, defaults, settings);

	this.setParent(defaults.parent)
		.setOnValueActionHandler(defaults.onValueAction);
};

FieldPanelItem.prototype.setParent = function (parent) {
	if(!(parent === null || parent instanceof FieldPanel)) {
		throw new Error("setParent(): The parameter must be an instance of FieldPanel or null.");
	}
	this._parent = parent;

	return this;
};

FieldPanelItem.prototype.getParent = function () {
	return this._parent;
};

FieldPanelItem.prototype.setVisible = function (value) {
    this.visible = !!value;
    if (this.html) {
        if (value) {
            this.style.addProperties({display: ""});
        } else {
            this.style.addProperties({display: "none"});
        }
    }
    return this;
};

FieldPanelItem.prototype.setOnValueActionHandler = function (handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnValueActionHandler(): The parameter must be a function or null.");
	}
	this.onValueAction = handler;
	return this;
};

FieldPanelItem.prototype._onValueAction = function (anyArgument) {
	if(typeof this.onValueAction === 'function') {
		this.onValueAction(this, this.getValueObject(anyArgument));
	}
	return this;
};

FieldPanelItem.prototype.getValueObject =  function() {
	throw new Error("getValueObject(): Trying to call an abstract method.");
};