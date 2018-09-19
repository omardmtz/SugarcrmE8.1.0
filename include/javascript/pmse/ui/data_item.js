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
//This is an abstract class
var PMSE = PMSE || {};
var DataItem = function(settings) {
    PMSE.Element.call(this, settings);
	this._parent = null;
	this._data = {};
	this._text = null;
	this._eventListenersAttached = false;
	this._htmlItemContent = null;
	this.onClick = null;
	this._disabled = false;
	DataItem.prototype.init.call(this, settings);
};

DataItem.prototype = new PMSE.Element();
DataItem.prototype.constructor = DataItem;
DataItem.prototype.type = "DataItem";

DataItem.prototype.init = function(settings) {
	var defaults = {
		data: {},
		onClick: null,
		text: "[item]",
		parent: null,
		disabled: false
	};

	jQuery.extend(true, defaults, settings);

	this.setFullData(defaults.data)
		.setOnClickHandler(defaults.onClick)
		.setText(defaults.text)
		.setParent(defaults.parent);

	if (defaults.disabled) {
		this.disable();
	} else {
		this.enable();
	}
};

DataItem.prototype.disable = function () {
	if (this.html) {
		this.style.addClasses(["adam-disabled"]);
	}
	this._disabled = true;
	return this;
};

DataItem.prototype.enable = function () {
	if (this.html) {
		this.style.removeClasses(["adam-disabled"]);
	}
	this._disabled = false;
	return this;
};

DataItem.prototype.setParent = function (parent) {
	if(!(parent === null || parent instanceof ItemContainer)) {
		throw new Error("setParent(): The parameter must be an instace of ItemContainer or null.");
	}
	this._parent = parent;
	return this;
};

DataItem.prototype.getParent = function () {
	return this._parent;
};

DataItem.prototype._getFinalText = function () {
	var regExpMatch, parts, current, i, finalText, text = this._text;
	if(typeof text === 'string') {
		if(regExpMatch = text.match(/^\{\{([a-zA-z0-9\.\-]+)\}\}$/)) {
			parts = regExpMatch[1].split(".");
			current = this._data;
			for(i = 0; i < parts.length; i++) {
				current = current[parts[i]];
				if(!current) {
					break;
				}
			}
			finalText = current && typeof current !== 'object' ? current : "";
		} else {
			finalText = text;
		}
	} else {
		finalText = this._text(this, this.getData()) || "";
	}
	return finalText;
};

DataItem.prototype.setText = function (text) {
	if(!(typeof text === 'string' || typeof text === 'function')) {
		throw new Error("setText(): The parameter must be a string or function.");
	}
	this._text = text;
	if(this._htmlTextContainer) {
		this._htmlTextContainer.textContent = this._getFinalText();
	}
	return this;
};

DataItem.prototype.getText = function () {
	return this._htmlTextContainer ? this._htmlTextContainer.textContent : this._getFinalText();
};

DataItem.prototype.setOnRemoveHandler = function(handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnRemoveHandler(): The parameter must be a function or null.");
	}
	this.onRemove = handler;
	return this;
};

DataItem.prototype.setOnClickHandler = function(handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnClickHandler(): The parameter must be a function or null.")
	}
	this.onClick = handler;
	return this;
};

DataItem.prototype.clearData = function (key) {
	if(key === undefined) {
		this._data = {};
	} else {
		delete this._data[key];
	}
	return this;
};

DataItem.prototype.setData = function (key, value) {
	this._data[key] = value;
	return this;
};

DataItem.prototype.setFullData = function (data) {
	var key;
	this.clearData();
	for (key in data) {
		if (data.hasOwnProperty(key)) {
			this.setData(key, data[key]);
		}
	}
	if(this._htmlTextContainer) {
		this.setText(this._text);
	}
	return this;
};

DataItem.prototype._onClick = function() {
	var that = this;
	return function(e) {
		e.preventDefault();
		e.stopPropagation();
		if(typeof that.onClick === 'function' && !that._disabled) {
			that.onClick(that);
		}
	};
};

DataItem.prototype.getData = function() {
	var dataObject = {}, key;
	for(key in this._data) {
		dataObject[key] = this._data[key];
	}
	return dataObject;
};

DataItem.prototype._attachListeners = function() {
	if (this.html && !this._eventListenersAttached) {
		jQuery(this._htmlItemContent).on('click', this._onClick());
		this._eventListenersAttached = true;
	}
	return this;
};

DataItem.prototype.createHTML = function () {
	throw new Error("createHTML(): Calling an abstract method in DataItem.");
};