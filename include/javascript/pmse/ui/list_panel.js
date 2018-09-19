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
var ListPanel = function(settings) {
	CollapsiblePanel.call(this, settings);
	this._itemsContent = null;
	this._data = null;
	this._proxy = null;
	this._dataURL = null;
	this._autoload = null;
	this._dataRoot = null;
	this._htmlMessage = null;
	this._showingLoadingMessage = null;
	this._filter = [];
	this._filterMode = null; 
	this.onItemClick = null;
	this.onLoad = null;
	ListPanel.prototype.init.call(this, settings);
};

ListPanel.prototype = new CollapsiblePanel();
ListPanel.prototype.constructor = ListPanel;
ListPanel.prototype.type = "ListPanel";

ListPanel.prototype.init = function (settings) {
	var defaults = {
		items: [],
		itemsContent: "[list item]",
		data: null,
		onItemClick: null,
		dataURL: null,
		autoload: false,
		dataRoot: null,
		onLoad: null,
		filter: [],
		filterMode: 'inclusive', 
		fieldToFilter: null
	};

	jQuery.extend(true, defaults, settings);

	this._proxy = new SugarProxy();
	this._autoload = defaults.autoload;

	this.setFilterMode(defaults.filterMode)
		.setItemsContent(defaults.itemsContent)
		.setOnItemClickHandler(defaults.onItemClick)
		.setDataURL(defaults.dataURL)
		.setDataRoot(defaults.dataRoot)
		.setOnLoadHandler(defaults.onLoad);

	if(typeof this._dataURL === 'string' && this._autoload) {
		this.load();
	} else {
		if(jQuery.isArray(defaults.data)) {
			this.setDataItems(defaults.data, defaults.fieldToFilter, defaults.filter);
		} else {
			this.setItems(defaults.items);
		}
	}
};

ListPanel.prototype.setFilterMode = function (filterMode) { 	 	
	if (filterMode !== 'inclusive' && filterMode !== 'exclusive') { 	 	
		throw new Error('setFilterMode(): The value for the parameter should be \"inclusive\" or \"exclusive\"'); 	 	
	} 	 	
	this._filterMode = filterMode; 	 	
	return this; 	 	
}; 	 	
 	 	
ListPanel.prototype.getFilterMode = function (filterMode) { 	 	
	return this._filterMode; 	 	
};

ListPanel.prototype.setOnLoadHandler = function (handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("onLoadHandler(): The parameter must be a function or null.");
	}
	this.onLoad = handler;
	return this;
};

ListPanel.prototype._checkItemsNum = function () {
	if(this._items.getSize() === 0) {
		this.showMessage("[0 items]");
	} else {
		this.removeMessage();
	}
	return this;
};

ListPanel.prototype.setDataRoot = function (dataRoot) {
	if (!(dataRoot === null || typeof dataRoot === 'string')) {
		throw new Error("setDataRoot(): The parameter must be a string or null.");
	}
	this._dataRoot = dataRoot;
	return this;
};

ListPanel.prototype._createMessageBox = function () {
	var element;
	if (!this._htmlMessage) {
		element = this.createHTMLElement("div");
		element.className = "adam list-panel-message";
		this._htmlMessage = element;
	} else {
		element = this._htmlMessage;
	}
	return element;
};

ListPanel.prototype.showMessage = function (message) {
	var element = this._createMessageBox();
	this._showingLoadingMessage = false;
	jQuery(element).empty();
	if (isHTMLElement(message)) {
		element.appendChild(message);
	} else if (typeof message === 'string') {
		element.textContent = message;	
	}
	$(this._htmlBody).prepend(this._htmlMessage);
	return this;
};

ListPanel.prototype.removeMessage = function () {
	jQuery(this._htmlMessage).remove();
	this._showingLoadingMessage = false;
	return this;
};

ListPanel.prototype._onLoadDataError = function () {
	var that = this;
	return function (httpError) {
		var i = that.createHTMLElement("strong");
		i.appendChild(document.createTextNode("An error occurred, please try again."));
		that.showMessage(i);
	};
};

ListPanel.prototype._onLoadDataSuccess = function() {
	var that = this;
	return function (data) {
		var items = that._dataRoot ? data[that._dataRoot] : data;
		that.removeMessage()
			.setDataItems(items)
			._checkItemsNum();
		if (typeof that.onLoad === 'function') {
			that.onLoad(that, data);
		}
	};
};

ListPanel.prototype._showLoadingMessage = function() {
	var element, icon;
	if (this._showingLoadingMessage) {
		return this;
	}
	element = this.createHTMLElement("span");
	icon = this.createHTMLElement("i");
	icon.className = "adam list-panel-spinner icon-spinner icon-spin";
	element.appendChild(icon);
	element.appendChild(document.createTextNode("loading..."));
	
	this.showMessage(element);
	this._showingLoadingMessage = true;
	return this;
};

ListPanel.prototype.load = function() {
	if(typeof this._dataURL !== 'string') {
		throw new Error("load(): The url wasn't set properly.");
	}
	this._proxy.url = this._dataURL;
	this.clearItems();
	this._showLoadingMessage();
	this._proxy.getData(null, {
		success: this._onLoadDataSuccess(),
		error : this._onLoadDataError()
	});
	return this;
};

ListPanel.prototype.setDataURL = function (dataURL) {
	if(!(dataURL === null || typeof dataURL === 'string')) {
		throw new Error("setDataURL(): The parameter must be a string or null.");
	}
	this._dataURL = dataURL;
	return this;
};

ListPanel.prototype.setOnItemClickHandler = function (handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnItemClickHandler(): The parameter must be a function or null.");
	}
	this.onItemClick = handler;
	return this;
};

ListPanel.prototype._onItemClickHandler = function () {
	var that = this;
	return function (item) {
		if(typeof that.onItemClick === 'function') {
			that.onItemClick(that, item);
		}
		that._onValueAction(item);
	};
};

ListPanel.prototype.addDataItem = function (data) {
	var newItem;
	if(typeof data !== 'object') {
		throw new Error("addDataItem(): The parameter must be an object.");
	}
	newItem = {
		data: data
	};
	this.addItem(newItem);
	return this;
};

ListPanel.prototype._filterData = function (data, fieldToFilter, filter) {
	var filteredData = [], i, validationFunction = false, that = this;;

	if (jQuery.isArray(filter) && filter.length) {
		validationFunction = function (data) {
			var i = 0;
			if (that._filterMode === 'inclusive') {
				return filter.indexOf(data[fieldToFilter]) >= 0;
			} else { 	 	
				return filter.indexOf(data[fieldToFilter]) === -1; 	 	
			}
		};
	} else if (typeof filter === 'string') {
		validationFunction = function (data) {
			return filter.toLowerCase() === data[fieldToFilter].toLowerCase();
		};
	} else if (typeof filter === 'function') {
		validationFunction = filter;
	}

	if (typeof fieldToFilter === 'string' && validationFunction) {
		for (i = 0; i < data.length; i += 1) {
			if (validationFunction(data[i])) {
				filteredData.push(data[i]);
			}
		}
		return filteredData;
	}

	return data;
};

ListPanel.prototype.getFilter = function () {
	return this._filter.slice(0);
};

ListPanel.prototype.setDataItems = function (data, fieldToFilter, filter) {
	var i;
	if(jQuery.isArray(data)) {
		this._massiveAction = true;
		data = this._filterData(data, fieldToFilter, filter);
		this.clearItems();
		for (i = 0; i < data.length; i += 1) {
			this.addDataItem(data[i]);
		}
		this._filter = filter || [];
		this._paintItems();
		this._massiveAction = false;
	}
	return this;
};

ListPanel.prototype.setItemsContent = function (itemsContent) {
	if (!(typeof itemsContent === 'string' || typeof itemsContent === 'function')) {
		throw new Error("setItemsContent(): The parameter must be a string or a function.");
	}
	this._itemsContent = itemsContent;
	return this;
};

ListPanel.prototype.setItems = function(items) {
	if(this._itemsContent) {
		CollapsiblePanel.prototype.setItems.call(this, items);
	}
	return this;
};

ListPanel.prototype.addItem = function (item) {
	var newItem;
	if(item instanceof ListItem) {
		newItem = item;
	} else {
		if (!item.text) {
			item.text = this._itemsContent;
		}
		newItem = new ListItem(item);
	}
	newItem.setOnClickHandler(this._onItemClickHandler());
	CollapsiblePanel.prototype.addItem.call(this, newItem);
	return this;
};

ListPanel.prototype._createBody = function () {
	var element = this.createHTMLElement('ul');
	element.className = 'list-panel';
	return element;
};

ListPanel.prototype.getValueObject = function (item) {
	return item.getData();
};
