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
/**
 * @class ItemContainer
 * Control that will be used as a container for the SingleItems objects.
 */
var ItemContainer = function (settings) {
	PMSE.Element.call(this, settings);
	this._items = new PMSE.ArrayList();
	this._massiveAction = false;
	this.onAddItem = null;
	this.onRemoveItem = null;
	this.onSelect = null;
	this.onInputChar = null;
	this._textInputMode = null;
	this._textInputs = new PMSE.ArrayList();
	this._inputValidationFunction = null;
	this._selectedIndex = null;
	this.onBeforeAddItemByInput = null;
	this._className = null;
	this.onBlur = null;
	this.onFocus = null;
	this._blurTimer = null;
	this._blurred = true;
	this._blurSemaphore = true;
	this._disabled = false;
	ItemContainer.prototype.init.call(this, settings);
};

ItemContainer.prototype = new  PMSE.Element();
ItemContainer.prototype.constructor = ItemContainer;

ItemContainer.prototype.textInputMode = {
	'NONE': 0,
	'END': 1,
	'ALL': 2
};

ItemContainer.prototype.init = function (settings) {
	var defaults = {
		items: [],
		onAddItem: null,
		onRemoveItem: null,
        onMoveItem: null,
		width: 200,
		height: 80,
		textInputMode: this.textInputMode.ALL,
		inputValidationFunction: null,
		onBeforeAddItemByInput: null,
		onSelect: null,
		onInputChar: null,
		onBlur: null,
		onFocus: null,
		className: "",
		disbaled: false
	};

	jQuery.extend(true, defaults, settings);

	if (typeof defaults.textInputMode !== 'number') {
		throw new Error("init(): The textInputMode config option must be a number");
	}
	this._textInputMode = defaults.textInputMode;

	this.setWidth(defaults.width)
		.setHeight(defaults.height)
		.setItems(defaults.items)
		.setOnAddItemHandler(defaults.onAddItem)
		.setInputValidationFunction(defaults.inputValidationFunction)
		.setOnBeforeAddItemByInput(defaults.onBeforeAddItemByInput)
		.setOnRemoveItemHandler(defaults.onRemoveItem)
		.setOnSelectHandler(defaults.onSelect)
		.setOnInputCharHandler(defaults.onInputChar)
		.setOnBlurHandler(defaults.onBlur)
		.setOnFocusHandler(defaults.onFocus)
		.setClassName(defaults.className);

	if (defaults.disabled) {
		this.disable();
	} else {
		this.enable();
	}
};

ItemContainer.prototype.getText = function () {
	var i, items = this._items.asArray(), text = "";
	for (i = 0; i < items.length; i += 1) {
		text += " " + items[i].getText();
	}
	return text.substr(1);
};

ItemContainer.prototype.setWidth = function(w) {
	if (!(typeof w === 'number' ||
		(typeof w === 'string' && (w === "auto" || /^\d+(\.\d+)?(em|px|pt|%)?$/.test(w))))) {
		throw new Error("setWidth(): invalid parameter.");
	}
	this.width = w;
    if (this.html) {
        this.style.addProperties({width: this.width});
    }
    return this;
};

ItemContainer.prototype.disable = function () {
	var i, items;
	if (!this._disabled) {
		items = this._items.asArray();

		for (i = 0; i < items.length; i += 1 ) {
			items[i].disable();
		}
		jQuery(this.html).find('input').attr('disabled', true);
		this._disabled = true;
	}

	return true;
};

ItemContainer.prototype.enable = function () {
	if (this._disabled) {
		items = this._items.asArray();

		for (i = 0; i < items.length; i += 1 ) {
			items[i].enable();
		}
		jQuery(this.html).find('input').attr('disabled', false);
		this._disabled = false;
	}
	return this;
};

ItemContainer.prototype.setClassName = function (className) {
	if(typeof className !== 'string') {
		throw new Error("setClassName(): The parameter must be a string.");
	}
	this.style.addClasses([className]);
	return this;
};

ItemContainer.prototype.setOnFocusHandler = function (handler) {
	if (!(handler === null || typeof handler === "function")) {
		throw new Error("setOnFocusHandler(): The parameter must be a function or null.");
	}
	this.onFocus = handler;
	return this;
};

ItemContainer.prototype.setOnBlurHandler = function (handler) {
	if (!(handler === null || typeof handler === "function")) {
		throw new Error("setOnBlurHandler(): The parameter must be a function or null.");
	}
	this.onBlur = handler;
	return this;
};

ItemContainer.prototype.setOnInputCharHandler = function (handler) {
	if (!(handler === null || typeof handler === "function")) {
		throw new Error("setOnInputCharHandler(): The parameter must be a function or null.");
	}
	this.onInputChar = handler;
	return this;
};

ItemContainer.prototype.setOnSelectHandler = function (handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnSelectHandler(): The parameter must be a function or null.");
	}
	this.onSelect = handler;
	return this;
};

ItemContainer.prototype.setOnBeforeAddItemByInput = function (handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error("setOnBeforeAddItemByInput(): The parameter must be a function or null.");
	}
	this.onBeforeAddItemByInput = handler;
	return this;
};

ItemContainer.prototype.setInputValidationFunction = function(fn) {
	if(!(fn === null || typeof fn === 'function')) {
		throw new Error("setInputValidationFunction(): The parameter must be a function or null.");
	}
	this._inputValidationFunction = fn;
	return this;
};

ItemContainer.prototype.setOnAddItemHandler = function (handler) {
	if (!(handler === null || typeof handler === 'function')) {
		throw new Error('setOnAddItemHandler(): The parameter must be a function or null.');
	}
	this.onAddItem = handler;
	return this;
};

ItemContainer.prototype.setOnRemoveItemHandler = function (handler) {
	if(!(handler === null || typeof handler === 'function')) {
		throw new Error('setOnRemoveItemHandler(): The parameter must be a function or null.');
	}
	this.onRemoveItem = handler;
	return this;
};

/**
 * Set a function to be called when you move a SingleItem
 *
 * @param {Function} handler
 * @return {ItemContainer}
 */
ItemContainer.prototype.setOnMoveChangeHandler = function(handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error('setOnMoveChangeHandler(): The parameter must be a function or null.');
    }
    this.onMoveItem = handler;
    return this;
};

ItemContainer.prototype._addInputText = function (reference) {
	var input = this.createHTMLElement("input");
	input.className = 'adam item-container-input';
	input.disabled = this._disabled;
	this._textInputs.insert(input);
	if(this.html) {
		if(typeof reference === 'number') {
			reference = this._items.get(reference);
		}
		if(reference && reference instanceof SingleItem) {
			this.html.insertBefore(input, reference.getHTML());
		} else {
			this.html.appendChild(input);
		}
	}
	return this;
};

ItemContainer.prototype.clearItems = function () {
	jQuery(this.html).empty();
	this._items.clear();
	this._textInputs.clear();
    this._selectedIndex = 0;
	if (this._textInputMode !== this.textInputMode.NONE) {
		this._addInputText();
	}
	return this;
};

ItemContainer.prototype.isParentOf = function (item) {
	return this === item.getParent();
};

ItemContainer.prototype._paintItem = function (item, index) {
	if (this.html) {
		if(this.isParentOf(item)) {
			if (typeof index === 'number') {
				if (index === 0) {
					jQuery(this.html).prepend(item.getHTML());
				} else if (index < this._items.getSize() - 1) {
					jQuery(this.html).find('.item-container-input').eq(index).before(item.getHTML());
				} else {
					jQuery(this.html).find('.item-container-input').last().before(item.getHTML());
				}
			} else {
				if (this._textInputMode === this.textInputMode.NONE) {
					this.html.appendChild(item.getHTML());
				} else {
					jQuery(this.html).find('.item-container-input').last().before(item.getHTML());
				}
			}
			if(this._textInputMode === this.textInputMode.ALL) {
				this._addInputText(index || item);
			}
            this.fixInputWidths();
		}
	}
	return this;
};

ItemContainer.prototype._paintItems = function () {
	var i, items = this._items.asArray();
	if (this.html) {
        if (this._textInputMode === this.textInputMode.ALL && this._textInputs.getSize() === 0) {
	    	this._addInputText();
	    }
    	for(i = 0; i < items.length; i++) {
			this._paintItem(items[i]);
		}
		if(this._textInputMode === this.textInputMode.END) {
			this._addInputText();
		}
	}
	return this;
};

ItemContainer.prototype._onRemoveItemHandler = function () {
	var that = this;
	return function (item) {
		that.removeItem(item);
		if(typeof that.onRemoveItem === 'function') {
			that.onRemoveItem(that, item);
		}
	};
};


/**
 * Adds a item to the list
 * @param item
 * @param index
 * @param noFocusNext
 * @param skipCallback if this is true, callback call will be skipped
 * @returns {ItemContainer}
 */
ItemContainer.prototype.addItem = function(item, index, noFocusNext, skipCallback) {
	if (!(item instanceof SingleItem || typeof item === "object")) {
		throw new Error("The paremeter must be an object literal or null.");
	}
	if (!(item instanceof SingleItem)) {
		item = new SingleItem(item);
	}
	item.setParent(this);
	item.setOnRemoveHandler(this._onRemoveItemHandler());

    if (_.isUndefined(index)) {
        index = this._selectedIndex;
    }

	if (typeof index === 'number' && index >= 0) {
		this._items.insertAt(item, index);
        this._selectedIndex = index + 1;
	} else {
		this._items.insert(item);
        this._selectedIndex = this._items.getSize();
	}

	if (!this._massiveAction) {
		this._paintItem(item, index);
		if(!noFocusNext && jQuery(item.getHTML()).next().get(0) !== jQuery(':focus').get(0)) {
			jQuery(item.getHTML()).next().focus();
		} else {
			this._selectedIndex += 1;
			if (this.html) {
				this.html.scrollTop += this.html.scrollHeight;
			}
		}
	}

        if (typeof this.onAddItem === 'function' && !skipCallback) {
		if(typeof index === 'number' && index >= 0) {
			item = this._items.get(index);
		} else {
			item = this._items.get(this._items.getSize() - 1);
		}
		this.onAddItem(this, item, index);
	}
	return this;
};

ItemContainer.prototype.removeItem = function (item) {
	if(this.isParentOf(item)) {
		this._items.remove(item);
		jQuery(item.getHTML()).prev('.item-container-input').remove().end()
			.next().focus()
			.end().remove();
	}
	return this;
};

/**
 * Add an item to a specific location
 *
 * @param {SingleItem} item
 * @param {number} newIndex where to put the item
 * @return {ItemContainer}
 */
ItemContainer.prototype.moveItem = function(item, newIndex) {
    if (!(item instanceof SingleItem || typeof item === 'object')) {
        throw new Error('The paremeter must be of type SingleItem.');
    }

    this._items.remove(item);
    this._items.insertAt(item, newIndex);
    this.onMoveItem(this);
    return this;
};

ItemContainer.prototype.setVisible = function (value) {
    if (_.isBoolean(value)) {
        this.visible = value;
        if (this.html) {
            if (value) {
                this.style.addProperties({display: ""});
            } else {
                this.style.addProperties({display: "none"});
            }
        }
    }
    return this;
};

ItemContainer.prototype.setItems = function (items) {
	var i;
	if(!jQuery.isArray(items)) {
		throw new Error("setItems(): The parameter must be an array.");
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

ItemContainer.prototype.getItems = function () {
	return this._items.asArray();
};

ItemContainer.prototype._getTextWidth = function (text, target) {
	var w, $label, label = this.createHTMLElement("span"), styles = window.getComputedStyle(target);
	label.style.padding = 0;
	label.style.fontFamily = styles.getPropertyValue("font-family");
	label.style.fontSize = styles.getPropertyValue("font-size");
	label.style.fontWeight = styles.getPropertyValue("font-weight");
	label.style.whiteSpace = 'nowrap';
	label.style.display =  "none";
	label.textContent = text.replace(/\s/g, "_");
	target.parentNode.appendChild(label);
	$label = jQuery(label);
	w = $label.outerWidth();
	$label.remove();
	return w;
};

ItemContainer.prototype._isValidInput = function (input) {
	var isValid = true;
	if(typeof this._inputValidationFunction === 'function') {
		isValid = this._inputValidationFunction(this, input);
	}
	return isValid;
};

ItemContainer.prototype.select = function (index) {
	if(this.html && !this._disabled) {
		if(typeof index === 'number') {
			jQuery(this.html).find('.adam.item-container-input').eq(index).focus();
		} else {
			jQuery(this.html).find('.adam.item-container-input').last().focus();
		}
		if(typeof this.onSelect === 'function') {
			this.onSelect(this);
		}
	}
	return this;
};

ItemContainer.prototype.getData = function () {
	var data = [], items = this._items.asArray();
	for (i = 0; i < items.length; i += 1) {
		data.push(items[i].getData());
	}
	return data;
};

ItemContainer.prototype.getSelectedIndex = function() {
	return this._selectedIndex;
};

ItemContainer.prototype._onBlur = function () {
	var that = this;
	return function () {
		clearInterval(that._blurTimer);
		if(typeof that.onBlur === 'function') {
			that._blurred = true;
			that.onBlur(that);
		}
	};
};

/**
 * Change the input widths to extend to end of line
 */
ItemContainer.prototype.fixInputWidths = function() {
    var elements = $(this.html).children();

    if (elements.length === 0) {
        return;
    }
    var lineWidth = 0;

    // Subtract 17px from width to allow room for the scroll bar
    var totalWidth = $(this.html).width() - 17;

    _.each(elements, function(element) {
        var $element = $(element);
        if ($element.hasClass('item-container-input')) {
            $element.width(1);
        }
        var elementWidth = $element.outerWidth(true);

        if (lineWidth + elementWidth >= totalWidth) {
            if ($element.hasClass('single-item')) {
                var prevInput = $element.prev();
                var newWidth = totalWidth - lineWidth;
                if (newWidth < 1) {
                    newWidth = 1;
                }
                prevInput.width(newWidth);
                lineWidth = elementWidth;
            }
        } else {
            lineWidth += elementWidth;
        }
    }, this);
};

ItemContainer.prototype._attachListeners = function () {
	var that, _tempValue = "";
	if(this.html) {
		that = this;
		jQuery(this.html).on('mousedown', function() {
			if(!that._blurred) {
				that._blurSemaphore = false;
			}
		}).on('click', function () {
			that._blurSemaphore = true;
            that.fixInputWidths();
			that.select();
		}).on('focusin', function(e) {
			clearInterval(that._blurTimer);
			if(that._blurred && typeof that.onFocus === 'function' && that._blurSemaphore) {
				that._blurred = false;
				that.onFocus(that);
			}
		}).on('focusout', function(e) {
			if (!that._blurred) {
				that._blurTimer = setInterval(that._onBlur(), 20);
			}
		}).on('focus', '.adam.item-container-input', function () {
			that._selectedIndex = $(this.parentNode).find('input').index(this);
		}).on('click', '.adam.item-container-input', function (e) {
			e.stopPropagation();
		}).on('focusout', '.adam.item-container-input', function (e) {
			if (!that._blurSemaphore) {
				e.stopPropagation();
				that._blurSemaphore = true;
			}
		}).on('keydown', '.adam.item-container-input', function(e) {
			var width, newValue, newItem, index, returnedValue, keyIdentifier;
			switch (e.keyCode) {
				case 37:
				case 39:
					if(e.shiftKey) {
						index = jQuery(that.html).find('.adam.item-container-input').index(this);
						newItem = jQuery(that.html).find('.adam.item-container-input').eq(index + (e.keyCode === 37 ? -1 : 1));
						if(newItem.length) {
							this.value = "";
							this.style.width = "1px";
							newItem.get(0).focus();
						}
					}
					break;
				case 27:
					this.value = "";
					break;
				case 13:
					e.preventDefault();
					if (that._isValidInput(this.value)) {
						newItem = new SingleItem({
							text: this.value
						});
						index = jQuery(that.html).find('.adam.item-container-input').index(this);
						if(typeof that.onBeforeAddItemByInput === 'function') {
							returnedValue = that.onBeforeAddItemByInput(that, newItem, this.value, index);
							if(returnedValue === false) {
								newItem = null;
							} else if (returnedValue instanceof SingleItem) {
								newItem = returnedValue;
							}
						}
						if(newItem instanceof SingleItem) {
							that.addItem(newItem, index);
						}
						this.value = "";
						this.style.width = "1px";
						this.select();
					} else {
						this.select();
					}
					break;
				default:
					try {
						keyIdentifier = e.originalEvent.keyIdentifier;
						//There's not a keyIdentifier property in IE, so we use the "char" property
						if (keyIdentifier) {
							keyIdentifier = eval('"\\u' + keyIdentifier.replace(/(U\+)/, "") + '"');
						} else if (typeof e.char === 'string') {
							//ie
							keyIdentifier = e.char;
						} else {
							//firefox
							keyIdentifier = String.fromCharCode(e.which);
							keyIdentifier += keyIdentifier ? " " : "";
						}
					} catch(e) {
						keyIdentifier = "";
					}
					if (this.selectionStart !== this.selectionEnd) {
						newValue = String.fromCharCode(e.keyCode);
					} else {
						newValue = this.value + keyIdentifier;
					}
					width = that._getTextWidth(newValue, this) || 1;
					this.style.width = width + "px";
			}
		}).on("keyup", '.adam.item-container-input', function (e) {
			var keyIdentifier;
			try {
				keyIdentifier = e.originalEvent.keyIdentifier;
				//There's not a keyIdentifier property in IE, so we use the "char" property
				if (keyIdentifier) {
					keyIdentifier = eval('"\\u' + keyIdentifier.replace(/(U\+)/, "") + '"');
				} else if (typeof e.char === 'string') {
					//ie
					keyIdentifier = e.char;
				} else {
					//firefox
					keyIdentifier = String.fromCharCode(e.which);
				}
			} catch(e) {}
			if((keyIdentifier || _tempValue !== this.value) && typeof that.onInputChar === 'function') {
				that.onInputChar(that, keyIdentifier, this.value, e.keyCode);
			}
			_tempValue = this.value;
		}).on("blur", '.adam.item-container-input', function() {
			var value = this.value;
			if(this.value !== "") {
				this.value = "";
				this.style.width = "1px";
			}
			//if(this.value !== value && typeof that.onInputChar === 'function') {
			//	that.onInputChar(that, "", this.value);
			//}
		});
	}
	return this;
};

ItemContainer.prototype.createHTML = function() {
    var self = this;
	if (!this.html) {
		this.html = this.createHTMLElement('ul');
		this.html.className = "adam item-container";
		this.style.applyStyle();
		this.style.addProperties({
            left: this.x,
            top: this.y,
            width: this.width,
            height: this.height,
            zIndex: this.zOrder
        });
        this._textInputs.clear();
        this._paintItems();
        this._attachListeners();
        this.setVisible(this.visible);

        // Let pills be moveable
        $(this.html).sortable({
            items: 'li',
            opacity: 1,
            forcePlaceholderSize: true,
            placeholder: 'adam single-item pill-placeholder',
            tolerance: 'pointer',
            start: function(e, ui) {
                var inputs = $(self.html).find('input');
                _.each(inputs, function(input) {
                    $(input).width(1);
                });
            },
            update: function(e, ui) {
                var movedItem = self._items.find('id', ui.item.context.id);
                var newIndex = ui.item.parent().children('li').index(ui.item[0]);
                self.moveItem(movedItem, newIndex);
                var items = self._items.asArray();
                self.setItems(items);
                // Reset the event listeners for each of the SingleItems
                _.each(items, function(item) {
                    item._eventListenersAttached = false;
                    item._attachListeners();
                });
            }
        });
	}
	return this.html;
};
