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
var FieldPanelButtonGroup = function(settings) {
	FieldPanelItem.call(this, settings)
	this._items = new PMSE.ArrayList();
	this._label = null;
	this._htmlLabel = null;
	this._htmlItemsContainer = null;
	this._massiveAction = false;
    this._closeButton = null;
	FieldPanelButtonGroup.prototype.init.call(this, settings);
};

FieldPanelButtonGroup.prototype = new FieldPanelItem();
FieldPanelButtonGroup.prototype.constructor = FieldPanelButtonGroup;
FieldPanelButtonGroup.prototype.type = "FieldPanelButtonGroup";

FieldPanelButtonGroup.prototype.init = function(settings) {
	var defaults = {
		items: [],
		label: ""
	};

	jQuery.extend(true, defaults, settings);

	this.setItems(defaults.items)
		.setLabel(defaults.label);

    this._closeButton = {
        text: 'x',
        value: 'close',
        html: null
    };
};

FieldPanelButtonGroup.prototype.setLabel = function (label) {
	if (typeof label !== 'string') {
		throw new Error("setLabel(): The parameter must be a string.");
	}
	this._label = label;
	if(this._htmlLabel) {
		if (this._label) {
			jQuery(this.html).prepend(this._htmlLabel);
			this._htmlLabel.textContent = label;
		} else {
			jQuery(this._htmlLabel).remove();
		}
	}
	return this;
};

FieldPanelButtonGroup.prototype.clearItems = function () {
	this._items.clear();
	if (this._htmlItemsContainer) {
		jQuery(this._htmlItemsContainer).empty();
	}
	return this;
};

FieldPanelButtonGroup.prototype._paintItem = function (newButton) {
	var that = this;
	if (!newButton.html) {
		newButton.html = this.createHTMLElement("button");
		newButton.html.className = 'adam field-panel-button-group-button btn btn-mini';
		newButton.html.appendChild(document.createTextNode(newButton.text));
		jQuery(newButton.html).on("click", function() {
			that._onValueAction(newButton);
		});
	}
	this._htmlItemsContainer.appendChild(newButton.html);
	return this;
};

FieldPanelButtonGroup.prototype.addItem = function (item) {
	var newButton = {
		text: item.text || item.value || "[button]",
		value: item.value || item.text || null,
		html: null
	};
	this._items.insert(newButton);
	if (!this._massiveAction && this._htmlItemsContainer) {
		this._paintItem(newButton);
	}
	return this;
};

FieldPanelButtonGroup.prototype._paintItems = function () {
    var i;
    var items;
    var self = this;
	if (this.html) {
		items = this._items.asArray();
		for (i = 0; i < items.length; i += 1) {
			this._paintItem(items[i]);
		}
        if (!this._closeButton.html) {
            this._closeButton.html = this.createHTMLElement('button');
            this._closeButton.html.className = 'adam field-panel-button-group-button btn btn-mini btn-close';
            this._closeButton.html.appendChild(document.createTextNode(this._closeButton.text));
            jQuery(this._closeButton.html).on('click', function() {
                self._onValueAction(self._closeButton);
            });
        }
        this.html.appendChild(this._closeButton.html);
	}
	return this;
};

FieldPanelButtonGroup.prototype.setItems = function (items) {
	var i;
	if(!jQuery.isArray(items)) {
		throw new Error("setItems(): The parameter must be an array.");
	}
	this._massiveAction = true;
	this.clearItems();
	for (i = 0; i < items.length; i += 1) {
		this.addItem(items[i]);
	}
	this._paintItems();
	this._massiveAction = false;
	return this;
};

FieldPanelButtonGroup.prototype.getItems = function () {
	return this._items.asArray();
};

FieldPanelButtonGroup.prototype.getValueObject = function(item) {
	return {
		text: item.text,
		value: item.value
	};
};

FieldPanelButtonGroup.prototype.createHTML = function () {
	if(!this.html) {
		this.html = this.createHTMLElement("div");
		this.html.className = "adam field-panel-button-group";
		this._htmlLabel = this.createHTMLElement("span");
		this._htmlLabel.className = "adam field-panel-button-group-label";
		this._htmlItemsContainer = this.createHTMLElement("div");
		this._htmlItemsContainer.className = "adam field-panel-button-container btn-group";

		this.html.appendChild(this._htmlLabel);
		this.html.appendChild(this._htmlItemsContainer);
		this.setLabel(this._label)._paintItems().setVisible(this.visible);
	}
	return this.html;
};
