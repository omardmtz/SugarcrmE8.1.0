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
var DropdownSelector = function (options) {
    options.open = false;
    FieldPanel.call(this, options);
    this._owner = null;
    this.onChange = null;
    this.values = null;
    this.value = null;
    this._open = null;
    this._appendTo = null;
    this._matchOwnerWidth = null;
    this._adjustWidth = null;
    DropdownSelector.prototype.init.call(this, options);
};

DropdownSelector.prototype = new FieldPanel();

DropdownSelector.prototype.type = 'DropboxSelector';

DropdownSelector.prototype.init = function (options) {
    var defaults = {
        width: 250,
        height: 'auto',
        owner: null,
        onChange: null,
        values: null,
        value: null,
        open: false,
        appendTo: document.body,
        matchOwnerWidth: true,
        adjustWidth: true
    };
    $.extend(true, defaults, options);
    this.setMatchOwnerWidth(defaults.matchOwnerWidth)
        .setAdjustWidth(defaults.adjustWidth)
        .setWidth(defaults.width)
        .setHeight(defaults.height)
        .setOwner(defaults.owner)
        .setOnChangeHandler(defaults.onChange)
        .setValues(defaults.values)
        .setValue(defaults.value)
        .setIsOpen(defaults.open)
        .setAppendTo(defaults.appendTo);
};

DropdownSelector.prototype.setAdjustWidth = function(value) {
    this._adjustWidth = value;
    return this;
};

DropdownSelector.prototype.setOwner = function (value) {
    this._owner = value;
    return this;
};

DropdownSelector.prototype.setIsOpen = function (value) {
    this._open = value;
    return this;
};

DropdownSelector.prototype.isPanelOpen = function () {
    return this._open;
};

DropdownSelector.prototype.setAppendTo = function (el) {
    this._appendTo = el;
    return this;
};

DropdownSelector.prototype.setWidth = function (w) {
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

DropdownSelector.prototype.setHeight = function (h) {
    if (!(typeof h === 'number' ||
        (typeof h === 'string' && (h === "auto" || /^\d+(\.\d+)?(em|px|pt|%)?$/.test(h))))) {
        throw new Error("setHeight(): invalid parameter.");
    }
    this.height = h;
    if (this.html) {
        this.style.addProperties({height: this.height});
    }
    return this;
};

DropdownSelector.prototype.setMatchOwnerWidth = function (value) {
    this._matchOwnerWidth = value;
    return this;
};

DropdownSelector.prototype.open = function () {
    this.getHTML();
    if (!this.isPanelOpen()) {
        this._appendPanel();
        $(this.html).slideDown();
        this.setIsOpen(true);
    }
    return this;
};

DropdownSelector.prototype.close = function () {
    if (this.html) {
        this.html.style.display = "none";
    }
    this.setIsOpen(false);
};

DropdownSelector.prototype.setOnChangeHandler = function (handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error("setOnChangeHandler(): the parameter must be a function or null.");
    }
    this.onChange = handler;
    return this;
};

DropdownSelector.prototype.setValues = function (combo) {
    this.values = combo;
    if (this.html) {
        this.createElements();
    }
    return this;
};

DropdownSelector.prototype.setValue = function (value) {
    this.value = value;
    return this;
};

DropdownSelector.prototype.createHTML = function () {
    var list;
    FieldPanel.prototype.createHTML.call(this);
    this.style.applyStyle();



    list = new ListPanel({
        bodyHeight: 100, //Change later to 'auto'
        maxBodyHeight: 200,
        collapsed: false,
        headerVisible: false,
        itemsContent: "{{text}}"
    });

    this.addItem(list);
    this.listHtml = list;


    //this.style.addProperties({
    //    width: this.width,
    //    height: this.height,
    //    zIndex: this.zOrder
    //});
    this.style.addProperties({
        position: "absolute",
        height: "auto",
        'min-width' : 0,
        zIndex: this.zOrder
    });
    this.createElements();
    this.attachListeners();
    return this.html;
};

DropdownSelector.prototype.createElements = function () {
    var key, text;

    this.listHtml.clearItems();
    this.listHtml.addItem(new CloseListItem({
        data: {}
    }));
    if (jQuery.isArray(this.values)) {
        for (key = 0; key < this.values.length; key += 1) {
            this.listHtml.addDataItem({
                value: this.values[key].value,
                text: this.values[key].text
            });
        }
    } else {
        for (key in this.values) {
            text = this.values[key];
            this.listHtml.addDataItem({
                value: key,
                text: text.toString ? text.toString() : text
            });
        }
    }
    return this;
};

DropdownSelector.prototype.attachListeners = function () {
    var self = this;
    if (this.html) {
        $(document).on("click", function (e) {
            var selector = "#" + self.id;
            if (e.target !== self.html && !$(e.target).parents(selector).length && e.target.parentNode !== self._owner) {
                self.close();
            }
        });
    }
    return this;
};

DropdownSelector.prototype._appendPanel = function () {
    var position, appendPanelTo = this._appendTo, owner = this._owner, offsetHeight = 1, zIndex = 0, siblings, aux;
    if (owner) {
        if (!isHTMLElement(owner)) {
            owner = owner.html;
        }
        offsetHeight = owner.offsetHeight;
    }
    if (typeof appendPanelTo === 'function') {
        appendPanelTo = appendPanelTo.call(this);
    }
    if (!isHTMLElement(appendPanelTo)) {
        appendPanelTo = appendPanelTo.html;
    }
    siblings = appendPanelTo.children;
    for (i = 0; i < siblings.length; i += 1) {
        aux = jQuery(siblings[i]).zIndex();
        if (aux > zIndex) {
            zIndex = aux;
        }
    }

    this.setZOrder(zIndex + 1);

    if (!owner || isInDOM(owner)) {
        appendPanelTo.appendChild(this.html);
    }
    if (owner) {
        if (this._adjustWidth) {
            // Only match owner width if there is one to match
            this.setWidth(this._matchOwnerWidth ? owner.offsetWidth : this.width);
        }
        position = getRelativePosition(owner, appendPanelTo);
    } else {
        this.setWidth(this.width);
        position = {left: 0, top: 0};
    }
    this.setPosition(position.left, position.top + offsetHeight - 1);
    return this;
};




