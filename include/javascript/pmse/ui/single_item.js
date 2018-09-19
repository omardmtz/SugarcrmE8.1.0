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
var SingleItem = function (settings) {
	DataItem.call(this, settings);
	this.onRemove = null;
	this._htmlTextContainer = null;
	this._htmlIconContainer = null;
	this._htmlRemoveButton = null;
	this._htmlItemContent = null;
	SingleItem.prototype.init.call(this, settings);
};

SingleItem.prototype = new DataItem();

SingleItem.prototype.constructor = SingleItem;

SingleItem.prototype.init = function (settings) {
	var defaults = {
		onRemove: null,
		removable: true
	};

	jQuery.extend(true, defaults, settings);

	this.setOnRemoveHandler(defaults.onRemove);
};

SingleItem.prototype.disable = function () {
	if (this.html) {
		this._htmlRemoveButton.style.display = "none";
	}
	return DataItem.prototype.disable.call(this);
};

SingleItem.prototype.enable = function () {
	if (this.html) {
		this._htmlRemoveButton.style.display = "";
	}
	return DataItem.prototype.enable.call(this);
};

SingleItem.prototype.setOnRemoveHandler = function(handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnRemoveHandler(): The parameter must be a function or null.");
	}
	this.onRemove = handler;
	return this;
};

SingleItem.prototype._onRemoveButtonClick = function() {
	var that = this;
	return function(e) {
		e.preventDefault();
		e.stopPropagation();
		if(typeof that.onRemove === 'function') {
			that.onRemove(that);
		}
	};
};

SingleItem.prototype._attachListeners = function() {
	if (this.html && !this._eventListenersAttached) {
		DataItem.prototype._attachListeners.call(this);
		jQuery(this._htmlRemoveButton).on('click', this._onRemoveButtonClick());
		jQuery(this.html).on("focus focusin focusout blur", function (e) {
			e.stopPropagation();
		})
		this._eventListenersAttached = true;
	}
	return this;
};

SingleItem.prototype.createHTML = function () {
	var textContainer, iconContainer, removeButton, itemContent;
	if (this.html) {
		return this.html;
	}
	//create the main html element to content all the other object's components.
	this.html = this.createHTMLElement('li');
	this.html.id = this.id;
	this.html.className = 'adam single-item';
	//create the object's components
	textContainer = this.createHTMLElement('span');
	textContainer.className = 'adam single-item-text';
	iconContainer = this.createHTMLElement('span');
	iconContainer.className = 'adam single-item-icon';
	itemContent = this.createHTMLElement('a');
	itemContent.className = 'adam single-item-content';
	itemContent.href = '#';
	removeButton = this.createHTMLElement('a');
	removeButton.href = "#";
	removeButton.className = 'adam single-item-remove fa fa-times-circle';
	//append the components to its respective parent elements;
	itemContent.appendChild(iconContainer);
	itemContent.appendChild(textContainer);
	this.html.appendChild(itemContent);
	this.html.appendChild(removeButton);
	//save the references to the components into object's member variables.
	this._htmlTextContainer = textContainer;
	this._htmlIconContainer = iconContainer;
	this._htmlRemoveButton = removeButton;
	this._htmlItemContent = itemContent;

	//Set properties that need html to b executed completly
	this.setText(this._text);

	if (this._disabled) {
		this.disable();
	} else {
		this.enable();
	}

	return this._attachListeners().html;
};
