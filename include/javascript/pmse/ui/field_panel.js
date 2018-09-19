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
var FieldPanel = function (settings) {
    PMSE.Element.call(this, settings);
    this._open = null;
    this._massiveAction = false;
    this._onItemValueAction = null;
    this._items = new PMSE.ArrayList();
    this._open = false;
    this._owner = null;
    this._matchOwnerWidth = true;
    this._alignWithOwner = null;
    this._appendTo = null;
    this._attachedListeners = false;
    this._className = null;
    this._context = null;
    this._useOffsetLeft = false;
    this._offsetLeft = 0;
    this.onOpen = null;
    this.onClose = null;
    FieldPanel.prototype.init.call(this, settings);
};

FieldPanel.prototype = new PMSE.Element();
FieldPanel.prototype.constructor = FieldPanel;

FieldPanel.prototype.init = function (settings) {
    var defaults = {
        items: [],
        onItemValueAction: null,
        open: false,
        owner: null,
        matchOwnerWidth: true,
        alignWithOwner: "left",
        appendTo: document.body,
        className: "",
        context: document.body,
        onOpen: null,
        onClose: null,
        useOffsetLeft: false
    };

    jQuery.extend(true, defaults, settings);

    this.setOwner(defaults.owner)
        .setAppendTo(defaults.appendTo)
        .setMatchOwnerWidth(defaults.matchOwnerWidth)
        .setItems(defaults.items)
        .setOnItemValueActionHandler(defaults.onItemValueAction)
        .setAlignWithOwner(defaults.alignWithOwner)
        .setClassName(defaults.className)
        .setUseOffsetLeft(defaults.useOffsetLeft)
        ._setContext(defaults.context);

    if (defaults.open) {
        this.open();
    } else {
        this.close();
    }

    this.setOnOpenHandler(defaults.onOpen)
        .setOnCloseHandler(defaults.onClose);
};

/**
 * Sets the left offset value to use when needed
 * @param {integer} offsetLeft pixel measurement of the left offset when needed
 */
FieldPanel.prototype.setOffsetLeft = function(offsetLeft) {
    this._offsetLeft = offsetLeft;
    return this;
};

/**
 * Sets the flag on whether to use a left offset when rendering the field panel
 * @param {boolean} useOffsetLeft
 */
FieldPanel.prototype.setUseOffsetLeft = function(useOffsetLeft) {
    this._useOffsetLeft = useOffsetLeft;
    return this;
};

FieldPanel.prototype._setContext = function (context) {
    if (typeof context === 'string') {
        context = jQuery(context).get(0);
    }
    if (!isHTMLElement(context)) {
        throw new Error("_setContext(): Invalid context!");
    }
    this._context = context;
    return this;
};

FieldPanel.prototype.setAlignWithOwner = function (alignment) {
    alignment = typeof alignment === 'string' ? alignment.toLowerCase() : null;
    if (!(alignment === "left" || alignment === "right")) {
        throw new Error("setAlignWithOwner(): The parameter must be \"left\" or \"right\".");
    }
    this._alignWithOwner = alignment;
    if (this.isOpen()) {
        this._append();
    }
    return this;
};

FieldPanel.prototype.setClassName = function (cName) {
    if (typeof cName !== 'string') {
        throw new Error("setClassName(): The parameter must be a string.");
    }

    this._className = cName;

    if (this.html) {
        jQuery(this.html).addClass(cName);
    }

    return this;
};

FieldPanel.prototype.setOnOpenHandler = function (handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error("The parameter must be a function or null.");
    }
    this.onOpen = handler;
    return this;
};

FieldPanel.prototype.setOnCloseHandler = function (handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error('The parameter must be a function or null.');
    }
    this.onClose = handler;
    return this;
};

FieldPanel.prototype.setMatchOwnerWidth = function (match) {
    this._matchOwnerWidth = !!match;
    if (this._open) {
        this._append();
    }
    return this;
};

FieldPanel.prototype.setAppendTo = function (appendTo) {
    if (!(isHTMLElement(appendTo) || typeof appendTo === 'function' || appendTo instanceof PMSE.Base)) {
        throw new Error("setAppendTo(): The parameter must be an HTML element or an instance of PMSE.Base.");
    }
    this._appendTo = appendTo;
    if (this.isOpen()) {
        this._append();
    }
    return this;
};

FieldPanel.prototype.setWidth = function (w) {
    PMSE.Element.prototype.setWidth.call(this, w);
    if (this.html && typeof w === "number") {
        this.style.addProperties({"min-width": this.width});
    }
    return this;
};

FieldPanel.prototype.open = function () {
    if (!this._open) {
        //if (this.html) {
        this.getHTML();
        this._append();
        jQuery(this.getHTML()).slideDown();
        //}
        this._open = true;
        if (typeof this.onOpen === 'function') {
            this.onOpen(this);
        }
    }
    return this;
};

FieldPanel.prototype.close = function () {
    if (this._open) {
        if (this.html) {
            this.html.style.display = "none";
        }
        this._open = false;
        if (typeof this.onClose === 'function') {
            this.onClose(this);
        }
    }
    return this;
};

FieldPanel.prototype.isOpen = function () {
    return this._open;
};

FieldPanel.prototype.getOwner = function () {
    return this._owner;
};

FieldPanel.prototype.setOnItemValueActionHandler = function (handler) {
    if(!(handler === null || typeof handler === 'function')) {
        throw new Error("setOnItemValueActionHandler(): the parameter must be a function or null.");
    }
    this.onItemValueAction = handler;
    return this;
};

FieldPanel.prototype._getUsableAppendTo = function () {
    var appendTo = this._appendTo;
    if (typeof appendTo === 'function') {
        return appendTo.call(this);
    } else if (!isHTMLElement(appendTo)) {
        return appendTo.html;
    }
    return appendTo;
};

FieldPanel.prototype._append = function () {
    var position, appendTo, owner = this._owner, offsetHeight = 1, zIndex = 0, siblings, aux;
    if (owner) {
        if (!isHTMLElement(owner)) {
            owner = owner.html;
        }
        offsetHeight = owner.offsetHeight;
    }
    appendTo = this._getUsableAppendTo();
    siblings = appendTo.children;
    for (i = 0; i < siblings.length; i += 1) {
        aux = jQuery(siblings[i]).zIndex();
        if (aux > zIndex) {
            zIndex = aux;
        }
    }

    this.setZOrder(zIndex + 1);

    if (!owner || isInDOM(owner)) {
        appendTo.appendChild(this.html);
    }
    if (owner) {
        this.setWidth(this._matchOwnerWidth ? owner.offsetWidth : this.width);
        position = getRelativePosition(owner, appendTo);
        if (this._alignWithOwner === 'right') {
            // In some cases, a right alignment means an offset jog instead of a
            // full movement by the width of the element
            if (this._useOffsetLeft) {
                position.left -= this._offsetLeft;
            } else {
                position.left -= this.width - owner.offsetWidth;
            }
        }
    } else {
        this.setWidth(this.width);
        position = {left: 0, top: 0};
    }
    this.setPosition(position.left, position.top + offsetHeight - 1);
    return this;
};

FieldPanel.prototype.setOwner = function (owner) {
    if(!(owner === null || owner instanceof PMSE.Element || isHTMLElement(owner))) {
        throw new Error("setOwner(): The parameter must be an instance of PMSE.Element or null.");
    }

    this._owner = owner;
    if (this.isOpen()) {
        this._append();
    }
    return this;
};

FieldPanel.prototype._onItemValueActionHandler = function () {
    var that = this;

    return function (item, valueObject) {
        if(typeof that.onItemValueAction === 'function') {
            that.onItemValueAction(that, item, valueObject);
        }
    };
};

FieldPanel.prototype.addItem = function (item) {
    if(!FieldPanelItemFactory.isProduct(item)) {
        item = FieldPanelItemFactory.make(item);
    }
    if(!FieldPanelItemFactory.isProduct(item)) {
        throw new Error("addItem(): The parameter must be acceptable by this parent.");
    } else {
        item.onValueAction = this._onItemValueActionHandler();
        this._items.insert(item);
    }
    if(!this._massiveAction && this.html) {
        this._paintItem(item);
    }
    return this;
};

FieldPanel.prototype.clearItems = function () {
    this._items.clear();
    jQuery(this.html).empty();
    return this;
};

FieldPanel.prototype._paintItem = function (item) {
    this.html.appendChild(item.getHTML());
    return this;
};

FieldPanel.prototype._paintItems = function () {
    var i, items;
    if(this.html) {
        items = this._items.asArray();
        for (i = 0; i < items.length; i++) {
            this._paintItem(items[i]);
        }
    }
    return this;
};

FieldPanel.prototype.setItems = function (items) {
    var i ;
    this._massiveAction = true;
    this.clearItems();
    for (i = 0; i < items.length; i++) {
        this.addItem(items[i]);
    }
    this._paintItems();
    this._massiveAction = false;
    return this;
};

FieldPanel.prototype.getItems = function () {
    return this._items.asArray();
};

FieldPanel.prototype.hideItem = function (itemIndex) {
    var itemToHide = this._items.get(itemIndex);
    if (itemToHide) {
        itemToHide.hide();
    }
    return this;
};

FieldPanel.prototype.showItem = function (itemIndex) {
    var itemToHide = this._items.get(itemIndex);
    if (itemToHide) {
        itemToHide.show();
    }
    return this;
};

FieldPanel.prototype.attachListeners = function () {
    var that = this;
    if (this.html && !this._attachedListeners) {
        jQuery('.adam-modal').add(this._context).on("click", function (e) {
            var $selector = $(that.html);
            if (that._owner) {
                $selector = isHTMLElement(that._owner) ? $selector.add(that._owner) : $selector.add(that._owner.html);
            }
            if (!jQuery(e.target).closest($selector).length) {
                that.close();
            }
        });
        this._attachedListeners = true;
    }
    return this;
};

FieldPanel.prototype.createHTML = function () {
    if(!this.html) {
        PMSE.Element.prototype.createHTML.call(this);
        this.html.className = 'adam field-panel';
        this._paintItems();

        this.style.addProperties({
            position: "absolute",
            "min-width": this.width,
            height: "auto",
            zIndex: this.zOrder
        });
        this.html.style.display = this._open ? "" : "none";
        this.setClassName(this._className);
        this.attachListeners();
    }
    return this.html;
};
