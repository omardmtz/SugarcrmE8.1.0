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
/*globals PMSE.Panel, $, PMSE.Proxy, TextField, ComboboxField, HiddenField, EmailPickerField, ItemMatrixField, MultipleItemField,
    CriteriaField, ItemUpdaterField, ExpressionField, TextareaField, CheckboxField, AdamnButton, RadiobuttonField */
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
PMSE.Form = function(options) {
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
     * @type {AdamProxy}
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

    this._closeOnClickContext = null;

    this._errorMessage = null;

    PMSE.Form.prototype.initObject.call(this, options);
};

PMSE.Form.prototype = new PMSE.Panel();

/**
 * Defines the object's type
 * @type {String}
 */
PMSE.Form.prototype.type = 'PMSE.Form';

/**
 * Initializes the object with the default values
 */
PMSE.Form.prototype.initObject = function(options) {
    var defaults = {
        url: null,
        data: null,
        proxyEnabled: true,
        callback: {},
        buttons: [],
        footerAlign: 'center',
        labelWidth: '30%',
        footerHeight: 40,
        headerHeight: 0,
        closeContainerOnSubmit: false,
        closeOnClickContext: '#container',
        language: {
            ERROR_INVALID_EMAIL: 'You must enter a valid email',
            ERROR_INVALID_INTEGER: 'Please enter only integer values',
            ERROR_REQUIRED_FIELD: 'This field is required',
            ERROR_COMPARISON: 'The comparison failed',
            ERROR_REGEXP: 'The pattern text didn\'t match with the specified one',
            ERROR_TEXT_LENGTH: 'The text length must be',
            ERROR_CHECKBOX_VALUES: 'Please insert Checkbox values (0 or 1)',
            ERROR_TEXT: 'Please insert text',
            ERROR_DATE : 'Please insert only valid dates',
            ERROR_PHONE: 'Please enter a valid Phone',
            ERROR_FLOAT: 'Please enter only valid float values',
            ERROR_DECIMAL: 'Please enter only valid decimal values',
            ERROR_URL: 'Please enter only valid url',

            TITLE_BUSINESS_RULE_EVALUATION: 'Business Rules Evaluation',
            LBL_BUSINESS: 'Business',
            LBL_OPERATOR: 'Operator',
            LBL_RESPONSE: 'Response',
            LBL_LOGIC_OPERATORS: 'Logic Operators',
            LBL_GROUP: 'Group',
            LBL_DIRECTION: 'Direction',
            LBL_MODULE: 'Module',
            LBL_FIELD: 'Field',
            LBL_VALUE: 'Value',
            LBL_TARGET_MODULE: 'Target Module',
            LBL_VARIABLE: 'Variable',
            LBL_USER: 'User',
            TITLE_MODULE_FIELD_EVALUATION: 'Module Field Evaluation',
            TITLE_FORM_RESPONSE_EVALUATION: 'Form Response Evaluation',
            TITLE_USER_EVALUATION: 'User Evaluation',
            LBL_FORM: 'PMSE.Form',
            LBL_STATUS: 'Status',
            LBL_APPROVED: 'Approved',
            LBL_REJECTED: 'Rejected',
            BUTTON_SUBMIT: 'Submit',
            BUTTON_CANCEL: 'Cancel'
        }
    };
    $.extend(true, defaults, options);
    this.language = defaults.language;

    this._setCloseOnClickContext(defaults.closeOnClickContext)
        .setUrl(defaults.url)
        .setData(defaults.data)
        .setProxyEnabled(defaults.proxyEnabled)
        .setProxy(defaults.proxy)
        .setCallback(defaults.callback)
        .setButtons(defaults.buttons)
        ._setErrorMessage()
        .setLabelWidth(defaults.labelWidth)
        .setFooterHeight(defaults.footerHeight)
        .setHeaderHeight(defaults.headerHeight)
        .setCloseContainerOnSubmit(defaults.closeContainerOnSubmit)
        .setFooterAlign(defaults.footerAlign);
};

/**
 * Sets error message to be shown when validation error happens.
 * @private
 */
PMSE.Form.prototype._setErrorMessage = function() {
    var iconSpan = this.createHTMLElement('span');
    iconSpan.className = 'fa fa-warning';
    var messageLabel = this.createHTMLElement('span');
    messageLabel.innerHTML = translate('LBL_PMSE_FORM_ERROR');
    this._errorMessage = this.createHTMLElement('div');
    this._errorMessage.className = 'pmse-form-error pmse-form-error-off';
    this._errorMessage.appendChild(iconSpan);
    this._errorMessage.appendChild(messageLabel);
    return this;
}

/**
 * Sets the context in which a click action should autoclose the close-on-click elements, like FieldPanels.
 * @param {HTMLElement|String} context
 * @private
 */
PMSE.Form.prototype._setCloseOnClickContext = function(context) {
    if (typeof context === 'string') {
        context = $(context).get(0);
    }
    if (!isHTMLElement(context)) {
        context = document.body;
    }
    this._closeOnClickContext = context;
    return this;
};

/**
 * Sets the form's url
 * @param {String} url
 * @return {*}
 */
PMSE.Form.prototype.setUrl = function(url) {
    this.url = url;
    return this;
};

/**
 * Sets the Proxy Enabled property
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Form.prototype.setProxyEnabled = function(value) {
    this.proxyEnabled = value;
    return this;
};

/**
 * Defines the proxy object
 * @param {AdamProxy} proxy
 * @return {*}
 */
PMSE.Form.prototype.setProxy = function(proxy) {
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
PMSE.Form.prototype.setData = function(data) {
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
PMSE.Form.prototype.setCallback = function(cb) {
    this.callback = cb;
    return this;
};

PMSE.Form.prototype.setFooterAlign = function(position) {
    this.footerAlign = position;
    return this;
};

PMSE.Form.prototype.setLabelWidth = function(width) {
    this.labelWidth = width;
    return this;
};

PMSE.Form.prototype.setFooterHeight = function(width) {
    this.footerHeight = width;
    return this;
};

PMSE.Form.prototype.setHeaderHeight = function(width) {
    this.headerHeight = width;
    return this;
};

PMSE.Form.prototype.setHeight = function(height) {
    var bodyHeight;
    PMSE.Panel.prototype.setHeight.call(this, height);
    bodyHeight = this.height - this.footerHeight - this.headerHeight;
    this.setBodyHeight(bodyHeight);
    return this;
};

PMSE.Form.prototype.setCloseContainerOnSubmit = function(value) {
    this.closeContainerOnSubmit = value;
    return this;
};
/**
 * Loads the form
 */
PMSE.Form.prototype.load = function() {
    var self = this, params = null;
    if (!this.loaded) {
        if (this.proxy) {
            params = this.getRelatedFields();
            this.proxy.getData(params, {
                success: function(response) {
                    self.data = response;
                    self.applyData.call(self);
                    self.loaded = true;
                    self.attachListeners();
                    self.setDirty(false);
                }
            });

        } else {
            this.applyData.call(this);
            this.attachListeners();
            this.loaded = true;
        }

    }
};

/**
 * Returns the URL params if the form has related records
 */
PMSE.Form.prototype.getRelatedFields = function() {
    var related = [];
    if (this.items) {
        for (i = 0; i < this.items.length; i += 1) {
            if (this.items[i].related) {
                related.push(this.items[i].related);
            }
        }
    }
    if (related.length > 0) {
        return {related: related.join(',')};
    } else {
        return null;
    }
};

/**
 * Reloads the form
 */

PMSE.Form.prototype.reload = function() {
    this.loaded = false;
    this.load();
};

/**
 * Applies the data to the form
 * @param dontLoad boolean Set the flag to trigger loaded event. Default value is FALSE
 */
PMSE.Form.prototype.applyData = function(dontLoad) {
    var propertyName, i, related;
    if (this.data) {
        //Applying related data
        if (this.data.related) {
            for (i = 0; i < this.items.length; i += 1) {
                if (this.items[i].getType() === 'ComboboxField' && this.items[i].related) {
                    related = this.items[i].related;
                    if (this.data.related[related]) {
                        this.items[i].setOptions(this.data.related[related]);
                    }
                }
            }
        }
        //Applying loaded values
        for (propertyName in this.data) {
            if (this.data.hasOwnProperty(propertyName)) {
                for (i = 0; i < this.items.length; i += 1) {
                    if (this.items[i].name === propertyName) {
                        try {
                            this.items[i].setValue(this.data[propertyName]);
                        } catch(e) {}
                        break;
                    }
                }
            }
        }
    }
    //Triggering 'loaded' form event
    if (this.callback && this.callback.loaded && !dontLoad) {
        this.callback.loaded.call(this, this.data, this.proxy !== null);
    }
};

/**
 * Add Fields Items
 * @param {(Object|PMSE.Field)}item
 */
PMSE.Form.prototype.addItem = function(item) {
    var newItem;
    if (item && item.family && item.family === 'PMSE.Field') {
        newItem = item;
        newItem.setParent(this);
    } else {
        $.extend(true, item, {language: this.language});
        if (item.jtype) {
            switch (item.jtype) {
            case 'text':
                newItem = new TextField(item, this);
                break;
            case 'combobox':
                newItem = new ComboboxField(item, this);
                break;
            case 'textarea':
                newItem = new TextareaField(item, this);
                break;
            case 'checkbox':
                newItem = new CheckboxField(item, this);
                break;
            case 'hidden':
                newItem = new HiddenField(item, this);
                break;
            case 'emailpicker':
                newItem = new EmailPickerField(item, this);
                break;
            case 'itemmatrix':
                newItem = new ItemMatrixField(item, this);
                break;
            case 'multipleitem':
                newItem = new MultipleItemField(item, this);
                break;
            case 'criteria':
                item.panelContext = this._closeOnClickContext;
                newItem = new CriteriaField(item, this);
                break;
            case 'itemupdater':
                newItem = new ItemUpdaterField(item, this);
                break;
            case 'radio':
                newItem = new RadiobuttonField(item, this);
                break;
            case 'searchableCombobox':
                newItem = new SearchableCombobox(item, this);
            }
        }
    }
    if (newItem) {
        this.items.push(newItem);
    }
};

/**
 * Sets the items
 * @param {Array} items
 * @return {*}
 */
PMSE.Form.prototype.setItems = function(items) {
    var i;
    for (i = 0; i < items.length; i += 1) {
        this.addItem(items[i]);
    }
    return this;
};

/**
 * Returns a form's field object based on its name.
 * @param name
 * @returns {Object|null}
 */
PMSE.Form.prototype.getField = function(name) {
    var field = _.find(this.items, function(item) {
        return item.name == name;
    });
    return field || null;
};

/**
 * Sets the buttons
 * @param {Array} buttons
 * @return {*}
 */
PMSE.Form.prototype.setButtons = function(buttons) {
    var i;
    for (i = 0; i < buttons.length; i += 1) {
        this.addButton(buttons[i]);
    }
    return this;
};

/**
 * Resets the form
 */
PMSE.Form.prototype.reset = function() {
    var i;
    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].reset();
    }
    this.setDirty(false);
    if (this.callback.reset) {
        this.callback.reset();
    }
};

/**
 * Submits the form
 */
PMSE.Form.prototype.submit = function() {
    var data;
    if (this.validate()) {
        data = this.getData();
        if (this.proxy) {
            this.proxy.sendData(data, this.callback);
        } else {
            if (this.callback.submit) {
                this.callback.submit(data);
            }
        }
        if (this.closeContainerOnSubmit) {
            if (this.parent && this.parent.close) {
                this.parent.close();
            }
        }
    } else {
        if (this.callback.failed) {
            this.callback.failed();
        }
    }
};

/**
 * Returns the data
 * @return {Object}
 */
PMSE.Form.prototype.getData = function() {
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
PMSE.Form.prototype.setDirty = function(value) {
    this.dirty = value;
    return this;
};

/**
 * Returns the dirty form property
 * @return {*}
 */
PMSE.Form.prototype.isDirty = function() {
    return this.dirty;
};

/**
 * Evaluate the fields' validations
 * @return {Boolean}
 */
PMSE.Form.prototype.validate = function() {
    var i, valid = true, current;
    if (this.testRequired()) {
        for (i = 0; i < this.items.length; i += 1) {
            current = this.items[i].isValid();
            valid = valid && current;
        }
    } else {
        valid = false;
        if (this.callback.required) {
            this.callback.required();
        }
    }
    $('.pmse-form-error')
        .toggleClass('pmse-form-error-on', !valid)
        .toggleClass('pmse-form-error-off', valid);
    return valid;
};

PMSE.Form.prototype.testRequired = function() {
    var i, response = true;
    for (i = 0; i < this.items.length; i += 1) {
        response = response && this.items[i].evalRequired();
    }
    return response;
};

PMSE.Form.prototype.addButton = function(button) {
    var newButton;
    if (button && button.family && button.family === 'PMSE.Button') {
        newButton = button;
        newButton.setParent(this);
    } else {
        newButton = new PMSE.Button(button, this);
    }
    if (newButton) {
        this.buttons.push(newButton);
    }
};

PMSE.Form.prototype.attachListeners = function() {
    var i;
    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].attachListeners();
    }
    for (i = 0; i < this.buttons.length; i += 1) {
        this.buttons[i].attachListeners();
    }
    //$(this.footer).draggable( "option", "disabled", true);
    $(this.body).mousedown(function(e) {
        e.stopPropagation();
    });
};

PMSE.Form.prototype.onEnterFieldHandler = function(fieldObject) {
    var that = this;
    return function() {
        var i;

        for (i = 0; i < that.items.length; i += 1) {
            if (that.items[i] !== fieldObject && (that.items[i] instanceof MultipleItemField || that.items[i] instanceof CriteriaField)) {
                that.items[i].closePanel();
            }
        }
    };
};

PMSE.Form.prototype.createHTML = function() {
    var i, html;
    PMSE.Panel.prototype.createHTML.call(this);
    this.footer.style.textAlign = this.footerAlign;
    for (i = 0; i < this.items.length; i += 1) {
        html = this.items[i].getHTML();
        $(html).find("select, input, textarea").focus(this.onEnterFieldHandler(this.items[i]));
        this.body.appendChild(html);
    }
    this.footer.appendChild(this._errorMessage);
    for (i = 0; i < this.buttons.length; i += 1) {
        this.footer.appendChild(this.buttons[i].getHTML());
    }
    this.body.style.bottom = (this.footerHeight + 8) + 'px';
    this.footer.style.height = this.footerHeight + 'px';
    this.footer.style.lineHeight = this.footerHeight + 'px';
    return this.html;
};

PMSE.Form.prototype.setParent = function(parent) {
    this.parent = parent;
    return this;
};
