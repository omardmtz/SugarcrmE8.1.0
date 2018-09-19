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
var FieldPanelButton = function (settings) {
	FieldPanelItem.call(this, settings);
	this._text = null;
	this._value = null;
	FieldPanelButton.prototype.init.call(this, settings);
};

FieldPanelButton.prototype = new FieldPanelItem();
FieldPanelButton.prototype.constructor = FieldPanelButton;

FieldPanelButton.prototype.type = "FieldPanelButton";

FieldPanelButton.prototype.init = function (settings) {
	var defaults = {
		text: "[button]",
		value: ""
	};

	jQuery.extend(true, defaults, settings);
	this.setText(defaults.text)
		.setValue(defaults.value);
};

FieldPanelButton.prototype.setValue = function (value) {
	if(typeof value !== 'string') {
		throw new Error("setValue(): The parameter must be a string.");
	}
	this._value = value;
	return this;
};

FieldPanelButton.prototype.setText = function (text) {
	if(typeof text !== 'string') {
		throw new Error("setText(): The parameter must be a string.");
	}
	if(this.html) {
		this.html.textContent = text;
	}
	this._text = text;
	return this;
};

FieldPanelButton.prototype._onClickHandler = function() {
	var that = this;
	return function (e) {
		e.preventDefault();
		that._onValueAction();
	};
};

FieldPanelButton.prototype._attachListeners = function () {
	if(this.html) {
		jQuery(this.html).on("click", this._onClickHandler());
	}
	return this;
};

FieldPanelButton.prototype.createHTML = function () {
	if(!this.html) {
		this.html = this.createHTMLElement("a");
		this.html.href = "#";
		this.html.className = "adam field-panel-button btn btn-mini btn-block";
		this.setText(this._text).setVisible(this.visible);
		this._attachListeners();
	}
	return this.html;
};

FieldPanelButton.prototype.getValueObject = function() {
	return {
		text: this._text,
		value: this._value
	};
};