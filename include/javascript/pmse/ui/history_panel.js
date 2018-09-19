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
 * @class PMSE.Form
 * Handles form panels
 * @extends PMSE.Panel
 *
 * @constructor
 * Creates a new instance of the object
 * @param {Object} options
 */
var HistoryPanel = function (options) {
    PMSE.Panel.call(this, options);

    /**
     * Defines if the form has a proxy
     * @type {Boolean}
     */
    this.proxyEnabled = null;

    /**
     * Defines the form's url
     * @type {String}
     */
    this.url = null;

    /**
     * Defines the form's proxy object
     * @type {PMSE.Proxy}
     */
    this.proxy = null;
    /**
     * Defines the form loading state
     * @type {Boolean}
     */
    this.loaded = false;
    /**
     * Defines the form's data
     * @type {Object}
     */
    this.data = null;
    /**
     * Defines the callback functions
     * @type {Object}
     */
    this.callback = {};
    /**
     * Defines the dirty form state
     * @type {Boolean}
     */
    this.dirty = false;

    this.buttons = [];

    this.footerAlign = null;

    this.labelWidth = null;

    this.footerHeight = null;

    this.headerHeight = null;

    this.closeContainerOnSubmit = null;

    this.parent = null;

    HistoryPanel.prototype.initObject.call(this, options);
};

HistoryPanel.prototype = new PMSE.Panel();

/**
 * Defines the object's type
 * @type {String}
 */
HistoryPanel.prototype.type = 'HistoryPanel';

/**
 * Initializes the object with the default values
 */
HistoryPanel.prototype.initObject = function (options) {
    var defaults = {
        url: null,
        data: null,
        proxyEnabled: true,
        callback: {},
        buttons: [],
        footerAlign: 'center',
        labelWidth: '30%',
        footerHeight: 10,
        headerHeight: 0,
        closeContainerOnSubmit: false,
        logType: 'message'

    };
    $.extend(true, defaults, options);
    this.setUrl(defaults.url)
        .setCallback(defaults.callback)
        .setLabelWidth(defaults.labelWidth)
        .setFooterAlign(defaults.footerAlign)
        .setLogType(defaults.logType);
};

/**
 * Sets the form's url
 * @param {String} url
 * @return {*}
 */
HistoryPanel.prototype.setUrl = function (url) {
    this.url = url;
    return this;
};

/**
 * Sets the Proxy Enabled property
 * @param {Boolean} value
 * @return {*}
 */
HistoryPanel.prototype.setProxyEnabled = function (value) {
    this.proxyEnabled = value;
    return this;
};

/**
 * Defines the proxy object
 * @param {PMSE.Proxy} proxy
 * @return {*}
 */
HistoryPanel.prototype.setProxy = function (proxy) {
    if (proxy && proxy.family && proxy.family === 'PMSE.Proxy') {
        this.proxy = proxy;
        this.url = proxy.url;
        this.proxyEnabled = true;
    } else {
        if (this.proxyEnabled) {
            if (proxy) {
                if (!proxy.url) {
                    proxy.url = this.url;
                }
                this.proxy = new PMSE.Proxy(proxy);
            } else {
                if (this.url) {
                    this.proxy = new PMSE.Proxy({url: this.url});
                }
            }
        }
    }
    return this;
};

/**
 * Defines the form's data object
 * @param {Object} data
 * @return {*}
 */
HistoryPanel.prototype.setData = function (data) {
    this.data = data;
    if (this.loaded) {
        this.applyData();
    }
    return this;
};

/**
 * Sets the form's callback object
 * @param {Object} cb
 * @return {*}
 */
HistoryPanel.prototype.setCallback = function (cb) {
    this.callback = cb;
    return this;
};

HistoryPanel.prototype.setFooterAlign = function (position) {
    this.footerAlign = position;
    return this;
};

HistoryPanel.prototype.setLabelWidth = function (width) {
    this.labelWidth = width;
    return this;
};

HistoryPanel.prototype.setFooterHeight = function (width) {
    this.footerHeight = width;
    return this;
};

HistoryPanel.prototype.setHeaderHeight = function (height) {
    this.headerHeight = height;
    return this;
};

HistoryPanel.prototype.setCloseContainerOnSubmit = function (value) {
    this.closeContainerOnSubmit = value;
    return this;
};
HistoryPanel.prototype.setLogType = function (type) {
    this.logType = type;
    return this;
};
/**
 * Loads the form
 */
HistoryPanel.prototype.load = function () {
    if (!this.loaded) {
        if (this.proxy) {
            this.data = this.proxy.getData();
        }
        if (this.callback.loaded) {
            this.callback.loaded(this.data, this.proxy !== null);
        }
        //this.applyData();
        this.attachListeners();
        this.loaded = true;
    }
};

/**
 * Reloads the form
 */
//
//HistoryPanel.prototype.reload = function () {
//    this.loaded = false;
//    this.load();
//};

/**
 * Applies the data to the form
 */
//HistoryPanel.prototype.applyData = function (dontLoad) {
//    var propertyName, i, related;
//    if (this.data) {
//        if (this.data.related) {
//            for (i = 0; i < this.items.length; i += 1) {
//                if (this.items[i].getType() === 'ComboboxField' && this.items[i].related) {
//                    related = this.items[i].related;
//                    if (this.data.related[related]) {
//                        this.items[i].setOptions(this.data.related[related]);
//                    }
//                }
//            }
//        }
//        for (propertyName in this.data) {
//            for (i = 0; i < this.items.length; i += 1) {
//                if (this.items[i].name === propertyName) {
//                    this.items[i].setValue(this.data[propertyName]);
//                    break;
//                }
//            }
//        }
//    }
//    if (this.callback.loaded && !dontLoad) {
//        this.callback.loaded(this.data, this.proxy !== null);
//    }
//};

/**
 * Add Fields Items
 * @param {(Object|PMSE.Field)}item
 */
HistoryPanel.prototype.addLog = function (options) {
    var html,
        newItem;
    newItem = new LogField(options);



    newItem.setParent(this);
    html = newItem.createHTML();

    this.body.appendChild(html);
    this.items.push(newItem);
    return this;
};


/**
 * Sets the items
 * @param {Array} items
 * @return {*}
 */
//HistoryPanel.prototype.setItems = function (items) {
//    var i;
//    for (i = 0; i < items.length; i += 1) {
//        this.addItem(items[i]);
//    }
//    return this;
//};


/**
 * Returns the data
 * @return {Object}
 */
HistoryPanel.prototype.getData = function () {
    var i, result = {};
    for (i = 0; i < this.items.length; i += 1) {
        $.extend(result, this.items[i].getObjectValue());
    }
    return result;
};

/**
 * Sets the dirty form property
 * @param {Boolean} value
 * @return {*}
 */
HistoryPanel.prototype.setDirty = function (value) {
    this.dirty = value;
    return this;
};

/**
 * Returns the dirty form property
 * @return {*}
 */
HistoryPanel.prototype.isDirty = function () {
    return this.dirty;
};

/**
 * Evaluate the fields' validations
 * @return {Boolean}
 */
HistoryPanel.prototype.validate = function () {
    var i, valid = true, current;
    for (i = 0; i < this.items.length; i += 1) {
        current = this.items[i].isValid();
        valid = valid && current;
    }
    return valid;
};

HistoryPanel.prototype.testRequired = function () {
    var i, response = true;
    for (i = 0; i < this.items.length; i += 1) {
        response = response && this.items[i].evalRequired();
    }
    return response;
};



HistoryPanel.prototype.attachListeners = function () {
    var i;
    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].attachListeners();
    }
//    for (i = 0; i < this.buttons.length; i += 1) {
//        this.buttons[i].attachListeners();
//    }
    //$(this.footer).draggable( "option", "disabled", true);
    $(this.body).mousedown(function (e) {
        e.stopPropagation();
    });
};



HistoryPanel.prototype.setHeight = function (height) {
    var bodyHeight;
    PMSE.Panel.prototype.setHeight.call(this, height);
    bodyHeight = this.height - this.footerHeight - this.headerHeight;
    this.setBodyHeight(bodyHeight);
    return this;
};

HistoryPanel.prototype.createHTML = function () {
    var i, footerHeight, html;
    PMSE.Panel.prototype.createHTML.call(this);
    this.footer.style.textAlign = this.footerAlign;
    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].setParent(this);
        html = this.items[i].getHTML();
        //$(html).find("select, input, textarea").focus(this.onEnterFieldHandler(this.items[i]));
        this.body.appendChild(html);
    }
//    for (i = 0; i < this.buttons.length; i += 1) {
//        this.footer.appendChild(this.buttons[i].getHTML());
//    }
    this.body.style.bottom = '8px';
    //this.footer.style.height = this.footerHeight + 'px';
    return this.html;
};

HistoryPanel.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};

HistoryPanel.prototype.getLogField = function (id) {
    var field = null, i;
    for (i = 0; i < this.items.length; i += 1) {
        if (this.items[i].id === id) {
            field = this.items[i];
            return field;
        }
    }
    return field;
};