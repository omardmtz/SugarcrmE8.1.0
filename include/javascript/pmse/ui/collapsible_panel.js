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
var CollapsiblePanel = function (settings) {
	FieldPanelItem.call(this, settings);
	this._items = new PMSE.ArrayList();
	this._title = "";
	this._massiveAction = false;
	this._htmlHeader = null;
	this._htmlTitle = null;
	this._htmlBody = null;
	this._bodyHeight = null;
	this._htmlCollapsibleIcon = null;
	this._htmlTitleContainer = null;
	this._collapsed = null;
	this.onCollapse = null;
	this.onExpand = null;
	this._attachedListeners = null;
	this._initialized = false;
	this._enabledAnimations = null;
	this._disabled = false;
	this._onEnablementStatusChange = null;
	this._headerVisible = false;
	CollapsiblePanel.prototype.init.call(this, settings);
};

CollapsiblePanel.prototype = new FieldPanelItem();
CollapsiblePanel.prototype.constructor = CollapsiblePanel;
CollapsiblePanel.prototype.type = "CollapsiblePanel";

CollapsiblePanel.prototype.init = function (settings) {
	var defaults = {
		title: "[panel]",
		items: [],
		bodyHeight: "auto",
		collapsed: true,
		width: '100%',
		onCollapse: null,
		onExpand: null,
		enabledAnimations: true,
		disabled: false,
		onEnablementStatusChange: null,
		headerVisible: true
	};

	jQuery.extend(true, defaults, settings);

	if (defaults.enabledAnimations) {
		this.enableAnimations();
	} else {
		this.disableAnimations();
	}

	if (defaults.disabled) {
		this.disable();
	} else {
		this.enable();
	}

	this.setWidth(defaults.width)
		.setTitle(defaults.title)
		.setItems(defaults.items)
		.setBodyHeight(defaults.bodyHeight)
		.setOnCollapseHandler(defaults.onCollapse)
		.setOnExpandHandler(defaults.onExpand)
		.setOnEnablementStatusChangeHandler(defaults.onEnablementStatusChange);

	if (defaults.collapsed) {
		this.collapse();
	} else {
		this.expand();
	}

	if (defaults.headerVisible) {
		this.showHeader();
	} else {
		this.hideHeader();
	}
	this._initialized = true;
};

CollapsiblePanel.prototype.showHeader = function () {
	this._headerVisible = true;
	if (this._htmlHeader) {
		this._htmlHeader.style.display = '';
	}
	return this;
};

CollapsiblePanel.prototype.hideHeader = function () {
	this._headerVisible = false;
	if (this._htmlHeader) {
		this._htmlHeader.style.display = 'none';
	}
	return this;
};

CollapsiblePanel.prototype.setOnEnablementStatusChangeHandler = function (handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnEnablementStatusChangeHandler(): The parameter must be a function or null.");
	}
	this.onEnablementStatusChange = handler;
	return this;
};

CollapsiblePanel.prototype.isDisabled = function () {
	return this._disabled;
};

CollapsiblePanel.prototype.disable = function () {
	if (!this._disabled) {
		this.collapse();
		this.style.addClasses(['collapsible-panel-disabled']);
		this._disabled = true;
		if (typeof this.onEnablementStatusChange === 'function') {
			this.onEnablementStatusChange(this, false);
		}
	}
	return this;
};

CollapsiblePanel.prototype.enable = function () {
	if (this._disabled) {
		this.style.removeClasses(['collapsible-panel-disabled']);
		this._disabled = false;
		if (typeof this.onEnablementStatusChange === 'function') {
			this.onEnablementStatusChange(this, true);
		}
	}
	return this;
};

CollapsiblePanel.prototype.setParent = function (parent) {
	if(!(parent === null || parent instanceof FieldPanel || parent instanceof MultipleCollapsiblePanel)) {
		throw new Error("setParent(): The parameter must be an instance of FieldPanel, MultipleCollapsiblePanel or "
			+ "null.");
	}
	this._parent = parent;

	return this;
};

CollapsiblePanel.prototype.enableAnimations = function	() {
	this._enabledAnimations = true;
	return this;
};

CollapsiblePanel.prototype.disableAnimations = function () {
	this._enabledAnimations = false;
	return this;
};

CollapsiblePanel.prototype.setOnExpandHandler = function(handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnExpandHandler(): The paremeter must be a function or null.");
	}
	this.onExpand = handler;
	return this;
};

CollapsiblePanel.prototype.setOnCollapseHandler = function(handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnCollapseHandler(): The paremeter must be a function or null.");
	}
	this.onCollapse = handler;
	return this;
};

CollapsiblePanel.prototype.setWidth = function(w) {
	if(typeof w === 'number') {
	    PMSE.Element.prototype.setWidth(w);
	} else {
		if(/^\d+(.\d+)?(px|%)?$/.test(w)) {
			this.width = w;
			if (this.html) {
	            this.style.addProperties({width: w});
	        }
		} else {
			throw new Error("setWidth(): The parameter must be a number or a valid number/unit formatted string.");
		}
	}
	return this;
};

CollapsiblePanel.prototype.isCollapsed = function () {
	return this._collapsed;
};

CollapsiblePanel.prototype.collapse = function (noAnimation) {
	this._collapsed = true;
	if(this._htmlBody) {
		jQuery(this._htmlCollapsibleIcon).removeClass('fa-caret-up').addClass('fa-caret-down');
		if(isInDOM(this.html)) {
			if (!this._enabledAnimations || noAnimation) {
				jQuery(this._htmlBody).stop(true, true).hide();
			} else {
				jQuery(this._htmlBody).stop(true, true).slideUp();
			}
		} else {
			this._htmlBody.style.display = 'none';
		}
		if (this._initialized && typeof this.onCollapse === 'function') {
			this.onCollapse(this);
		}
	}
	return this;
};

CollapsiblePanel.prototype.expand = function (noAnimation) {
	this._collapsed = false;
	if(this._htmlBody) {
		jQuery(this._htmlCollapsibleIcon).removeClass('fa-caret-down').addClass('fa-caret-up');
		if (!this._enabledAnimations || noAnimation) {
			jQuery(this._htmlBody).stop(true, true).show();
		} else {
			jQuery(this._htmlBody).stop(true, true).slideDown();
		}
		if (this._initialized && typeof this.onExpand === 'function') {
			this.onExpand(this);
		}
	}
	return this;
};

CollapsiblePanel.prototype.toggleCollapse = function() {
	if(this._collapsed) {
		this.expand();
	} else {
		this.collapse();
	}
	return this;
};

CollapsiblePanel.prototype.setBodyHeight = function (height) {
	this._bodyHeight = height;
	if(this._htmlBody) {
		this._htmlBody.style.maxHeight = isNaN(height) ? height : height + "px";
	}
	return this;
};

CollapsiblePanel.prototype.setTitle = function (title) {
	if(typeof title !== 'string') {
		throw new Error("setTitle(): The parameter must be a string.");
	}

	this._title = title;
	if(this._htmlTitle) {
		this._htmlTitle.textContent = title;
	}
	return this;
};

CollapsiblePanel.prototype.getTitle = function () {
	return this._title;
};

CollapsiblePanel.prototype._unpaintItem = function (item) {
	if(item.html) {
		//IE compatibilty
		if (item.html.remove) {
			item.html.remove();
		} else {
			item.html.removeNode(true);
		}
	}
	return this;
};

CollapsiblePanel.prototype._unpaintItems = function () {
	var i, items = this._items.asArray();
	if(this.html) {
		for (i = 0; i < items.length; i += 1) {
			this._unpaintItem(items[i]);
		}
	}
	return this;
};

CollapsiblePanel.prototype.removeItem = function (item) {
	var itemToRemove = this.getItem(item);
	if (itemToRemove) {
		this._items.remove(itemToRemove);
		this._unpaintItem(itemToRemove);
	}
	return this;
};

CollapsiblePanel.prototype.clearItems = function () {
	var i, items = this._items.asArray();
	this._unpaintItems();
	if(this._massiveAction) {
		this._items.clear();
	}
	return this;
};

CollapsiblePanel.prototype._paintItem = function (item, index) {
	var itemAtIndex;
	if(this.html) {
		if (index) {
			itemAtIndex = this._items.get(index);
		}
		if (itemAtIndex) {
			this._htmlBody.insertBefore(item.getHTML(), itemAtIndex.getHTML());
		} else {
			this._htmlBody.appendChild(item.getHTML());
		}
	}
	return this;
};

CollapsiblePanel.prototype._paintItems = function () {
	var i, items;
	if(this.html) {
		items = this._items.asArray();
		this._unpaintItems();
		for(i = 0; i < items.length; i += 1) {
			this._paintItem(items[i]);
		}
	}
	return this;
};

CollapsiblePanel.prototype.addItem = function (item, index) {
	if (typeof index === 'number') {
		this._items.insertAt(item, index);
	} else if (index === null || index === undefined) {
		this._items.insert(item);
	} else {
		throw new Error("addItem(): The second parameter is optional, in case of use it must be a number.");
	}
	if(!this._massiveAction) {
		this._paintItem(item, index);
	}
	return this;
};

CollapsiblePanel.prototype.getItem = function (field) {
	if (typeof field === 'string') {
		return this._items.find("id", field);
	} else if (typeof field === 'number') {
		return this._items.get(field);
	} else if (typeof field instanceof FormPanelItem && this._items.indexOf(field) >= 0) {
		return field;
	}
	return null;
};

CollapsiblePanel.prototype.setItems = function (items) {
	var i;
	if(!jQuery.isArray(items)) {
		throw new Error("setItems(): The parameter must be an array.")
	}
	this._massiveAction = true;
	this.clearItems();
	for(i = 0; i < items.length; i++) {
		this.addItem(items[i]);
	}
	this._paintItems();
	this._massiveAction = false;
	return this;
};

CollapsiblePanel.prototype.getItems = function () {
	return this._items.asArray();
};

CollapsiblePanel.prototype._createBody = function () {
	throw new Error("_createBody(): This function must be overwritten in subclases, since it's been called from an abstract one.");
};

CollapsiblePanel.prototype._attachListeners = function () {
	var that;
	if(this.html && !this._attachedListeners) {
		that = this;
		jQuery(this._htmlTitleContainer).on('click', function() {
			if (!that._disabled) {
				that.toggleCollapse();
			}
		});
	}

	return this;
};

CollapsiblePanel.prototype.createHTML = function () {
	var htmlHeader, htmlTitle, htmlBody, htmlTitleContainer, collapsibleIcon;
	if(!this.html) {
		this.html = this.createHTMLElement('div');
		this.html.id = this.id;
		this.html.className = "adam collapsible-panel";
		htmlHeader = this.createHTMLElement('div');
		htmlHeader.className = "adam collapsible-panel-header";
		htmlTitleContainer = this.createHTMLElement('h4');
		htmlTitleContainer.className = "adam collapsible-panel-title";
		htmlTitle = this.createHTMLElement('span');
		collapsibleIcon = this.createHTMLElement('i');
		collapsibleIcon.className = 'adam collapsible-panel-icon fa';

		htmlTitleContainer.appendChild(collapsibleIcon);
		htmlTitleContainer.appendChild(htmlTitle);
		htmlHeader.appendChild(htmlTitleContainer);
		this.html.appendChild(htmlHeader);
		htmlBody = this._createBody();
		htmlBody.className += " adam collapsible-panel-body";
		this._htmlBody = htmlBody;
		this.html.appendChild(htmlBody);

		this._htmlCollapsibleIcon = collapsibleIcon;
		this._htmlTitleContainer = htmlTitleContainer;
		this._htmlHeader = htmlHeader;
		this._htmlTitle = htmlTitle;
		this._htmlBody = htmlBody;

		this.setBodyHeight(this._bodyHeight);
		this.setTitle(this._title);

		this._paintItems();
		this._collapsed ? this.collapse() : this.expand();
		this._attachListeners();
		this.setVisible(this.visible);

		if (this._headerVisible) {
			this.showHeader();
		} else {
			this.hideHeader();
		}

		this.style.applyStyle();

	    this.style.addProperties({
	        width: this.width,
	        height: "auto"
	    });
	}

	return this.html;
};