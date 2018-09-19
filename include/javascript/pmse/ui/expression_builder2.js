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
var ExpressionControl = function (settings) {
    PMSE.Element.call(this, settings);
    this._name = null;
    this._panel = null;
    this._operatorSettings = {};
    this._operatorPanel = null;
    this._evaluationSettings = {};
    this._evaluationPanel = null;
    this._evaluationPanels = {};
    this._variableSettings = null;
    this._variablePanel = null;
    this._constantSettings = null;
    this._constantPanel = null;
    this._constantPanels = {};
    this._attachedListeners = false;
    this.onChange = null;
    this._value = null;
    this._panelSemaphore = true; //true for close the panel, false to avoid closing.
    this._itemContainer = null;
    this._externalItemContainer = false;
    //this._owner = null;
    //this._matchOwnerWidth = true;
    this._proxy = null;
    //this._appendTo = null;
    this._expressionVisualizer = null;
    this._dateFormat = null;
    this._timeFormat = null;
    this._decimalSeparator = null;
    this._numberGroupingSeparator = null;
    this._auxSeparator = "|||";
    this._itemValueWildcard = '%VALUE%';
    this._currencies = [];
    this._preferredCurrency = null;
    this._parent = null;
    this.onOpen = null;
    this.onClose = null;
    this._useOffsetLeft = false;
    this._offsetLeft = 0;
    ExpressionControl.prototype.init.call(this, settings);
};

ExpressionControl.prototype = new PMSE.Element();
ExpressionControl.prototype.constructor = ExpressionControl;
ExpressionControl.prototype.type = "ExpressionControl";
ExpressionControl.prototype._regex = {
    string: /("(?:[^"\\]|\\.)*")|('(?:[^'\\]|\\.)*')/,
    datetime: /^\d{4}-((0[1-9])|(1[0-2]))-((0[1-9])|([12][0-9])|(3[01]))(\s((0[0-9])|(1[0-2])|(2[0-3])):[0-5][0-9]:[0-5][0-9])?$/,
    unittime: /^\d+[wdhm]$/
};

ExpressionControl.prototype._typeToControl = {
    "address": "text",
    "checkbox": "checkbox",
    "currency": "currency",
    "date": "date",
    "datetime": "datetime", //
    "decimal": "number",
    "encrypt": "text",
    "dropdown": "dropdown",
    "float": "number",
    "email": "text",
    "name": "text",
    "htmleditable_tinymce": "text",
    "tinyint": "integer",
    //"html": "html",
    //"iframe": "iframe",
    //"image": "image" ,
    "integer": "integer",
    "multiselect": "multiselect",
    //"flex relate": "flexrelate",
    "phone": "text",
    "radio": "radio",
    //"relate": "related",
    "textarea": "text",//"textarea",
    "url": "text",
    "textfield": "text",
    "user": "friendlydropdown"
};

ExpressionControl.prototype.initOperators = function () {
    ExpressionControl.prototype.OPERATORS  = {
        'runTime': [
            {
                text: translate('LBL_PMSE_RUNTIME_BUTTON'),
                value: 'Run Time'
            }
        ],
        "arithmetic": [
            {
                text: "+",
                value: "addition"
            },
            {
                text: "-",
                value: "substraction"
            },
            {
                text: "x",
                value: "multiplication"
            },
            {
                text: "/",
                value: "division"
            }
        ],
        "logic": [
            {
                text: "AND",
                value: "AND"
            },
            {
                text: "OR",
                value:  "OR"
            },
            {
                text: "NOT",
                value: "NOT"
            }
        ],
        'group': [
            {
                text: '(',
                value: '('
            },
            {
                text: ')',
                value: ')'
            }
        ]
    };
};

ExpressionControl.prototype.initComparisonOperators = function(module) {
    var changesLabel = App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_CHANGES', module);
    var fromLabel = App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_CHANGES_FROM', module);
    var toLabel = App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_CHANGES_TO', module);
    ExpressionControl.prototype.OPERATORS.comparison = [
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_EQUAL', module),
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_EQUAL_TEXT', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_EQUAL', module),
            value: 'equals'
        },
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_EQUAL', module),
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_EQUAL_TEXT', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_EQUAL_DATE', module),
            value: 'not_equals'
        },
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MAJOR', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MAJOR_DATE', module),
            value: 'major_than'
        },
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MINOR_THAN', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MINOR_THAN_DATE', module),
            value: 'minor_than'
        },
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MAJOR_EQUAL', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MAJOR_EQUAL_DATE', module),
            value: 'major_equals_than'
        },
        {
            text: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MINOR_EQUAL_THAN', module),
            datefield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_MINOR_EQUAL_DATE', module),
            value: 'minor_equals_than'
        },
        {
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_STARTS_TEXT', module),
                value: "starts_with"
        },
        {
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_ENDS_TEXT', module),
                value: "ends_with"
        },
        {
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_CONTAINS_TEXT', module),
                value: "contains"
        },
        {
            textfield: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_CONTAINS_TEXT', module),
                value: "does_not_contain"
        }
    ];
    ExpressionControl.prototype.OPERATORS.changes = [
        {
            text: changesLabel,
            textfield: changesLabel,
            datefield: changesLabel,
            value: 'changes'
        },
        {
            text: fromLabel,
            textfield: fromLabel,
            datefield: fromLabel,
            value: 'changes_from'
        },
        {
            text: toLabel,
            textfield: toLabel,
            datefield: toLabel,
            value: 'changes_to'
        }
    ];
};

ExpressionControl.prototype.EXTRA_OPERATORS = {};

ExpressionControl.prototype.init = function(settings) {
    var module = 'pmse_Project';
    ExpressionControl.prototype.initOperators();
    ExpressionControl.prototype.initComparisonOperators(module);
    var defaults = {
        name: null,
        width: 200,
        itemContainerHeight: 80, //only applicable when it is not external
        height: 'auto',
        operators: true,
        evaluation: false,
        variable: false,
        constant: true,
        onChange: null,
        owner: null,
        itemContainer: null,
        appendTo: document.body,
        alignWithOwner: "left",
        matchOwnerWidth: true,
        expressionVisualizer: true,
        dateFormat: "YYYY-MM-DD",
        timeFormat: "H:i",
        decimalSeparator: settings.numberGroupingSeparator === "." ? "," : ".",
        numberGroupingSeparator: settings.decimalSeparator === "," ? "." : ",",
        allowInput: true,
        onOpen: null,
        onClose: null,
        className: "",
        panelContext: document.body,
        currencies: [],
        useOffsetLeft: false
    };

    jQuery.extend(true, defaults, settings);

    this._name = defaults.name;

    this._proxy = new SugarProxy();
    if (defaults.itemContainer instanceof ItemContainer) {
        this._itemContainer = defaults.itemContainer;
        this._externalItemContainer = true;
    } else {
        this._itemContainer = new ItemContainer({
            textInputMode: defaults.allowInput ? ItemContainer.prototype.textInputMode.ALL
                : ItemContainer.prototype.textInputMode.NONE,
            width: '100%',
            height: defaults.itemContainerHeight
        });
    }

    this._panel = new FieldPanel({
        id: defaults.id,
        open: false,
        onItemValueAction: this._onPanelValueGeneration(),
        width: this.width,
        context: defaults.panelContext,
        className: defaults.className || "",
        useOffsetLeft: defaults.useOffsetLeft
    });

    this._itemContainer.setOnAddItemHandler(this._onChange())
        .setOnRemoveItemHandler(this._onChange())
        .setOnMoveChangeHandler(this._onChange())
        .setInputValidationFunction(this._inputValidationFunction())
        .setOnBeforeAddItemByInput(this._onBeforeAddItemByInput());

    this.setWidth(defaults.width)
        .setHeight(defaults.height)
        .setDateFormat(defaults.dateFormat)
        .setTimeFormat(defaults.timeFormat)
        .setDecimalSeparator(defaults.decimalSeparator)
        .setNumberGroupingSeparator(defaults.numberGroupingSeparator)
        .setCurrencies(defaults.currencies)
        .setOwner(defaults.owner)
        .setAppendTo(defaults.appendTo)
        .setOperators(defaults.operators)
        .setEvaluations(defaults.evaluation)
        .setVariablePanel(defaults.variable)
        .setConstantPanel(defaults.constant)
        .setOnChangeHandler(defaults.onChange)
        .setAlignWithOwner(defaults.alignWithOwner)
        .setMatchOwnerWidth(defaults.matchOwnerWidth)
        .setOnOpenHandler(defaults.onOpen)
        .setOnCloseHandler(defaults.onClose)
        .setUseOffsetLeft(defaults.useOffsetLeft);

    if (defaults.expressionVisualizer) {
        this.showExpressionVisualizer();
    } else {
        this.hideExpressionVisualizer();
    }

    if (defaults.parent) {
        this._parent = defaults.parent;
    }
};

/**
 * Sets the left offset value to use when needed
 * @param {integer} offsetLeft pixel measurement of the left offset when needed
 */
ExpressionControl.prototype.setOffsetLeft = function(offsetLeft) {
    this._offsetLeft = offsetLeft;
    this._panel.setOffsetLeft(offsetLeft);
    return this;
}

/**
 * Sets the flag on whether to use a left offset when rendering the field panel
 * @param {boolean} useOffsetLeft
 */
ExpressionControl.prototype.setUseOffsetLeft = function(useOffsetLeft) {
    this._useOffsetLeft = useOffsetLeft;
    return this;
};

ExpressionControl.prototype.setAlignWithOwner = function (alignment) {
    this._panel.setAlignWithOwner(alignment);
    return this;
};

ExpressionControl.prototype.setOnOpenHandler = function (handler) {
    this._panel.setOnOpenHandler(handler);
    return this;
};

ExpressionControl.prototype.setOnCloseHandler = function (handler) {
    this._panel.setOnCloseHandler(handler);
    return this;
};

ExpressionControl.prototype.getText = function () {
    return this._itemContainer.getText();
};

ExpressionControl.prototype._createCurrencyObject = function(data) {
    if (!(data.id && data.name && data.rate)) {
        throw new Error('_createCurrencyObject(): id, name and rate properties are required.');
    }
    return {
        id: data.id,
        iso: data.iso,
        name: data.name,
        rate: data.rate,
        preferred: !!data.preferred,
        symbol: data.symbol
    };
};

ExpressionControl.prototype._updateCurrenciesToCurrenciesForm = function() {
    var currenciesField;
    if (this._constantPanels.currency) {
        currenciesField = this._constantPanels.currency.getItem("currency");
        currenciesField.setOptions(this._currencies);
        if (this._preferredCurrency) {
            currenciesField.setValue(this._preferredCurrency);
        }
    }
    if (this._evaluationPanels.module) {
        currenciesField = this._evaluationPanels.module.getItem("value");
        if (currenciesField instanceof FormPanelCurrency) {
            currenciesField.setCurrencies(this._currencies);
        }
    }
    return this;
};

ExpressionControl.prototype.setCurrencies = function(currencies) {
    var i = 0;
    //remove following assignation
    if (!$.isArray(currencies)) {
        throw new Error("setCurrencies(): The parameter must be an array.");
    }
    this._currencies = [];
    for (i = 0; i < currencies.length; i++) {
        this._currencies.push(this._createCurrencyObject(currencies[i]));
        if (currencies[i].preferred) {
            this._preferredCurrency = currencies[i].id;
        }
    }
    return this._updateCurrenciesToCurrenciesForm();
};

ExpressionControl.prototype.setDecimalSeparator = function (decimalSeparator) {
    if (!(typeof decimalSeparator === 'string' && decimalSeparator && decimalSeparator.length === 1
        && !/\d/.test(decimalSeparator) && !/[\+\-\*\/]/.test(decimalSeparator))) {
        throw new Error("setDecimalSeparator(): The parameter must be a single character different than a digit and "
            + "arithmetic operator.");
    }
    if (decimalSeparator === this._numberGroupingSeparator) {
        throw new Error("setDecimalSeparator(): The decimal separator must be different from the number grouping "
            + "separator.");
    }
    this._decimalSeparator = decimalSeparator;
    return this;
};

ExpressionControl.prototype.setNumberGroupingSeparator = function (separator) {
    if (!(separator === null || (typeof separator === 'string' && separator.length <= 1))) {
        throw new Error("setNumberGroupingSeparator(): The parameter is optional should be a single character or "
            + "null.");
    }
    if (separator === this._decimalSeparator) {
        throw new Error("setNumberGroupingSeparator(): The decimal separatpr must be different from the number grouping "
            + "separator.");
    }
    this._numberGroupingSeparator = separator;
    return this;
};

ExpressionControl.prototype.setDateFormat = function (dateFormat) {
    this._dateFormat = dateFormat;
    if (this._constantPanels.date) {
        this._constantPanels.date.getItem("date").setFormat(dateFormat);
    }
    if (this._constantPanels.datetime) {
        this._constantPanels.datetime.getItem("datetime").setFormat(dateFormat);
    }
    return this;
};

ExpressionControl.prototype.setTimeFormat = function (timeFormat) {
    this._timeFormat = timeFormat;
    if (this._constantPanels.datetime) {
        this._constantPanels.datetime.getItem("datetime").setTimeFormat(timeFormat);
    }
    return this;
};

ExpressionControl.prototype._parseInputToItem = function (input) {
    var trimmedText = jQuery.trim(input), type;
    if (typeof input !== 'string') {
        throw new Error("_parseInputToItemData(): The parameter must be a string.");
    }

    if (trimmedText === '+' || trimmedText === '-') {
        type = "MATH";
    } else if (this._regex.unittime.test(trimmedText)) {
        type = "UNIT_TIME";
    } else {
        type = "FIXED_DATE";
    }

    return this._createItemData(trimmedText, type);
};

ExpressionControl.prototype._onBeforeAddItemByInput = function () {
    var that = this;
    return function (itemContainer, newItem, input, index) {
        var data = that._parseInputToItem(input);
        if (data) {
            newItem.setFullData(data);
        } else {
            return false;
        }
    };
};

ExpressionControl.prototype.isLeapYear = function (year) {
    if(year % 400 === 0 || year % 4 === 0) {
        return true;
    }
    return false;
};

ExpressionControl.prototype.isValidDateTime = function (date) {
    if (typeof date === 'string') {
        //TODO validation acccording to the set data format
        if (!this._regex.datetime.test(date)) {
            return false;
        }
        date = date.split("-");
        date[0] = parseInt(date[0], 10);
        date[1] = parseInt(date[1], 10);
        date[2] = parseInt(date[2], 10);

        if (date[1] <= 0 || date[2] <= 0 || date[1] > 12 || date[2] > 31) {
            return false;
        }
        if ((date[1] === 4 || date[1] === 6 || date[1] === 9) && date[0] > 30) {
            return false;
        }
        if ((!this.isLeapYear(date[0]) && date[2] > 28) || date[2] > 29) {
            return false;
        }
    } else {
        //TODO validations for other arguments data type
        return false;
    }
    return true;
};

ExpressionControl.prototype._inputValidationFunction = function () {
    var that = this;
    return function (itemContainer, input) {
        var trimmedText = jQuery.trim(input);
        switch (trimmedText) {
            case '+':
            case '-':
            case "NOW":
                return true;
            default:
                return that._regex.unittime.test(trimmedText) || that.isValidDateTime(trimmedText);
        }
    };
};

ExpressionControl.prototype.showExpressionVisualizer = function () {
    if (!this._externalItemContainer) {
        this._itemContainer.setVisible(true);
    }
    return this;
};

ExpressionControl.prototype.hideExpressionVisualizer = function () {
    if (!this._externalItemContainer) {
        this._itemContainer.setVisible(false);
    }
    return this;
};

ExpressionControl.prototype.setMatchOwnerWidth = function (match) {
    this._panel.setMatchOwnerWidth(!!match);
    return this;
};

ExpressionControl.prototype.setAppendTo = function (appendTo) {
    this._panel.setAppendTo(appendTo);
    return this;
};

ExpressionControl.prototype.isOpen = function() {
    return (this._panel && this._panel.isOpen()) || false;
};

ExpressionControl.prototype.getValueObject = function () {
    return this._itemContainer.getData();
};

ExpressionControl.prototype._onChange = function () {
    var that = this;
    return function (itemContainer, item, index) {
        var oldValue = that._value;
        that._value = itemContainer = JSON.stringify(itemContainer.getData());
        if (typeof that.onChange === 'function') {
            that.onChange(that, that._value, oldValue);
        }
    };
};

ExpressionControl.prototype.setOwner = function(owner) {
    this._panel.setOwner(owner);
    return this;
};

ExpressionControl.prototype.getOwner = function () {
    return this._panel.getOwner();
};

ExpressionControl.prototype.getValue = function () {
    return this._value;
};

/**
 * Set the value of the field
 * @param value
 * @param skipChangeHandler Set this to true to skip do the change in other fields
 * @returns {ExpressionControl}
 */
ExpressionControl.prototype.setValue = function (value, skipChangeHandler) {
    var i;
    if (typeof value === "string") {
        value = JSON.parse(value);
    } else if (!jQuery.isArray(value)) {
        throw new Error("The parameter must be a array formatted string or an object.");
    }

    this._itemContainer.clearItems();
    for (i = 0; i < value.length; i += 1) {
        this._itemContainer.addItem(this._createItem(value[i]), undefined, undefined, skipChangeHandler);
    }
    return this;
};

ExpressionControl.prototype.setOnChangeHandler = function (handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error("setOnChangeHandler(): the parameter must be a function or null.");
    }
    this.onChange = handler;
    return this;
};

ExpressionControl.prototype.setWidth = function (w) {
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

ExpressionControl.prototype.setHeight = function (h) {
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

ExpressionControl.prototype._getProperty = function (data, path) {
    var levels, i;
    if (data) {
        levels = path.split(".");
        for (i = 0; i < levels.length; i += 1) {
            data = data[levels[i]];
        }
    }
    return data;
};

ExpressionControl.prototype.setConstantPanel = function(settings) {
    var defaults = true;

    if (settings === false) {
        defaults = false;
    } else if (settings === true) {
        defaults = {
            basic: true,
            date: true,
            datetime: true,
            timespan: true,
            datespan: false,
            currency: true
        };
    } else {
        defaults = jQuery.extend(true, defaults, settings);
    }

    this._constantSettings = defaults;

    if (this._constantPanel) {
        this._createBasicConstantPanel()
            ._createDateConstantPanel()
            ._createDateTimeConstantPanel()
            ._createTimespanPanel()
            ._createDatespanPanel()
            ._createCurrencyPanel();
    }

    return this;
};

ExpressionControl.prototype.setVariablePanel = function (settings) {
    var defaults = {
        dataURL: null,
        dataRoot: null,
        data: [],
        dataFormat: "tabular",
        dataChildRoot: null,
        textField: "text",
        valueField: "value",
        typeField: "type",
        typeFilter: null,
        filterMode: "inclusive",
        moduleTextField: null,
        moduleValueField: null
    };

    if (settings === false) {
        defaults = false;
    } else {
        jQuery.extend(true, defaults, settings);
        if (defaults.dataURL) {
            if (typeof defaults.dataURL !== "string") {
                throw new Error("setVariablePanel(): The \"dataURL\" property must be a string.");
            }
            if (!(defaults.dataRoot === null || typeof defaults.dataRoot === "string")) {
                throw new Error("setVariablePanel(): The \"dataRoot\" property must be a string or null.");
            }
            defaults.data = [];
        } else {
            if (!jQuery.isArray(defaults.data)) {
                throw new Error("setVariablePanel(): The \"data\" property must be an array.");
            }
        }

        if (defaults.dataFormat !== "tabular" && defaults.dataFormat !== "hierarchical") {
            throw new Error("setVariablePanel(): The \"dataFormat\" property only can have the \"hierarchical\" or "
                + "\"tabular\" values.");
        }
        if (typeof defaults.dataChildRoot !== "string" && defaults.dataFormat === "hierarchical") {
            throw new Error("setVariablePanel(): You set the \"dataFormat\" property to \"hierarchical\" so the "
                + "\"dataChildRoot\" property must be specified.");
        }
        if (typeof defaults.textField !== "string") {
            throw new Error("setVariablePanel(): The \"textField\" property must be a string.");
        }
        if (typeof defaults.valueField !== "string") {
            throw new Error("setVariablePanel(): The \"valueField\" property must be a string.");
        }
        if (typeof defaults.typeField !== "string") {
            throw new Error("setVariablePanel(): The \"typeField\" property must be a string.");
        }
        if (!(defaults.typeFilter === null || typeof defaults.typeFilter === "string"
            || typeof defaults.typeFilter === 'function' || jQuery.isArray(defaults.typeFilter))) {
            throw new Error("setVariablePanel(): The \"typeFilter\" property must be a string, function, array or null.");
        }
        if (typeof defaults.moduleTextField !== "string") {
            throw new Error("setVariablePanel(): The \"moduleTextField\" property must be a string.");
        }
        if (typeof defaults.moduleValueField !== "string") {
            throw new Error("setVariablePanel(): The \"moduleValueField\" property must be a string.");
        }
        if (defaults.filterMode !== 'inclusive' && defaults.filterMode !== 'exclusive') {
            throw new Error("setVariablePanel(): The \"filterMode\" property must be \"exclusive\" or \"inclusive\"");
        }
    }

    this._variableSettings = defaults;

    if (this._variablePanel) {
        this._createVariablePanel();
    }

    return this;
};

ExpressionControl.prototype.setModuleEvaluation = function (settings) {
    var defaults = {
        dataURL: null,
        dataRoot: null,
        textField: "text",
        valueField: "value",
        fieldDataURL: null,
        fieldDataURLAttr: null,
        fieldDataRoot: null,
        fieldTextField: "text",
        fieldValueField: "value",
        fieldTypeField: "type"
    }, that = this, moduleField;

    if (settings === false) {
        defaults = false;
    } else {
        jQuery.extend(true, defaults, settings);
    }

    if (defaults) {
        if (typeof defaults.dataURL !== "string") {
            throw new Error("setModuleEvaluation(): The \"dataURL\" property must be a string.");
        }
        if (!(typeof defaults.dataRoot === "string" || defaults.dataRoot === null)) {
            throw new Error("setModuleEvaluation(): The \"dataRoot\" property must be a string or null.");
        }
        if (typeof defaults.textField !== "string") {
            throw new Error("setModuleEvaluation(): The \"textField\" property must be a string.");
        }
        if (typeof defaults.valueField !== "string") {
            throw new Error("setModuleEvaluation(): The \"valueField\" property must be a string.");
        }
        if (typeof defaults.fieldDataURL !== "string") {
            throw new Error("setModuleEvaluation(): The \"fieldDataURL\" property must be a string.");
        }
        if (defaults.fieldDataURLAttr !== null && typeof defaults.fieldDataURLAttr !== "object") {
            throw new Error("setModuleEvaluation(): The \"fieldDataURLAttr\" property must be an object or null.");
        }
        if (!(typeof defaults.fieldDataRoot === "string" || defaults.fieldDataRoot === null)) {
            throw new Error("setModuleEvaluation(): The \"fieldDataRoot\" property must be a string.");
        }
        if (typeof defaults.fieldTextField !== "string") {
            throw new Error("setModuleEvaluation(): The \"fieldTextField\" property must be a string.");
        }
        if (typeof defaults.fieldValueField !== "string") {
            throw new Error("setModuleEvaluation(): The \"fieldValueField\" property must be a string.");
        }
        if (typeof defaults.fieldTypeField !== "string") {
            throw new Error("setModuleEvaluation(): The \"fieldTypeField\" property must be a string.");
        }
    }

    if (!this._evaluationSettings) {
        this._evaluationSettings = {};
    }
    this._evaluationSettings.module = defaults;
    if (this._evaluationPanel) {
        this._createModulePanel();
    }
    return this;
};

ExpressionControl.prototype.setFormResponseEvaluation = function (settings) {
    var defaults = {
        dataURL: null,
        dataRoot: null,
        textField: "text",
        valueField: "value"
    };

    if (settings === false) {
        defaults = false;
    } else {
        jQuery.extend(true, defaults, settings);
    }

    if (defaults) {
        if (typeof defaults.dataURL !== "string") {
            throw new Error("setFormResponseEvaluation(): The \"dataURL\" parameter must be a string.");
        }
        if (!(typeof defaults.dataRoot === "string" || defaults.dataRoot === null)) {
            throw new Error("setFormResponseEvaluation(): The \"dataRoot\" parameter must be a string or null.");
        }
        if (typeof defaults.textField !== "string") {
            throw new Error("setFormResponseEvaluation(): The \"textField\" parameter must be a string.");
        }
        if (typeof defaults.valueField !== "string") {
            throw new Error("setFormResponseEvaluation(): The \"valueField\" parameter must be a string.");
        }
    }

    this._evaluationSettings.formResponse = defaults;

    if (this._evaluationPanels.formResponse) {
        this._createFormResponsePanel();
    }

    return this;
};

ExpressionControl.prototype.setBusinessRuleEvaluation = function (settings) {
    var defaults = {
        dataURL: null,
        dataRoot: null,
        textField: "text",
        valueField: "value"
    };

    if (settings === false) {
        defaults = false;
    } else {
        jQuery.extend(true, defaults, settings);

        if (typeof defaults.dataURL !== "string") {
            throw new Error("setBusinessRuleEvaluation(): The parameter must be a string.");
        }
        if (!(typeof defaults.dataRoot === "string" || defaults.dataRoot === null)) {
            throw new Error("setBusinessRuleEvaluation(): The parameter must be a string or null.");
        }
        if (typeof defaults.textField !== "string") {
            throw new Error("setBusinessRuleEvaluation(): The parameter must be a string.");
        }
        if (typeof defaults.valueField !== "string") {
            throw new Error("setBusinessRuleEvaluation(): The parameter must be a string.");
        }
    }
    this._evaluationSettings.businessRule = defaults;
    return this;
};

ExpressionControl.prototype.setUserEvaluation = function (settings) {
    var defaults = {
        defaultUsersDataURL: null,
        defaultUsersDataRoot: null,
        defaultUsersLabelField: "text",
        defaultUsersValueField: "value",
        userRolesDataURL: null,
        userRolesDataRoot: null,
        userRolesLabelField: "text",
        userRolesValueField: "value",
        usersDataURL: null,
        usersDataRoot: null,
        usersLabelField: "text",
        usersValueField: "value"
    };

    if (settings === false) {
        defaults = false;
    } else {
        jQuery.extend(true, defaults, settings);
        if (typeof defaults.defaultUsersDataURL !== "string") {
            throw new Error("setUserEvaluation(): The \"defaultUsersDataURL\" must be a string.");
        }
        if (!(typeof defaults.defaultUsersDataRoot === "string" || defaults.defaultUsersDataRoot === null)) {
            throw new Error("setUserEvaluation(): The \"defaultUsersDataRoot\" must be a string or null.");
        }
        if (typeof defaults.defaultUsersLabelField !== "string") {
            throw new Error("setUserEvaluation(): The \"defaultUsersLabelField\" must be a string.");
        }
        if (typeof defaults.defaultUsersValueField !== "string") {
            throw new Error("setUserEvaluation(): The \"defaultUsersValueField\" must be a string.");
        }
        if (typeof defaults.userRolesDataURL !== "string") {
            throw new Error("setUserEvaluation(): The \"userRolesDataURL\" must be a string.");
        }
        if (!(typeof defaults.userRolesDataRoot === "string" || defaults.userRolesDataRoot === null)) {
            throw new Error("setUserEvaluation(): The \"userRolesDataRoot\" must be a string or null.");
        }
        if (typeof defaults.userRolesLabelField !== "string") {
            throw new Error("setUserEvaluation(): The \"userRolesLabelField\" must be a string.");
        }
        if (typeof defaults.userRolesValueField !== "string") {
            throw new Error("setUserEvaluation(): The \"userRolesValueField\" must be a string.");
        }
        if (typeof defaults.usersDataURL !== "string") {
            throw new Error("setUserEvaluation(): The \"usersDataURL\" must be a string.");
        }
        if (!(typeof defaults.usersDataRoot === "string" || defaults.usersDataRoot === null)) {
            throw new Error("setUserEvaluation(): The \"usersDataRoot\" must be a string or null.");
        }
        if (typeof defaults.usersLabelField !== "string") {
            throw new Error("setUserEvaluation(): The \"usersLabelField\" must be a string.");
        }
        if (typeof defaults.usersValueField !== "string") {
            throw new Error("setUserEvaluation(): The \"usersValueField\" must be a string.");
        }
    }

    this._evaluationSettings.user = defaults;

    if (this._evaluationPanel) {
        this._createUserPanel();
    }
    return this;
};

ExpressionControl.prototype.setEvaluations = function (evaluations) {
    var panels = ["module", "form", "business_rule", "user"], i, currentEval, _evaluationSettings = {};

    if (evaluations === false) {
        this._evaluationSettings = false;// this._evaluationSettings.form = this._evaluationSettings.business_rule = this._evaluationSettings.user = false;
    } else if (typeof evaluations === 'object') {
        for (i = 0; i < panels.length; i += 1) {
            currentEval = evaluations[panels[i]] || false;
            switch (panels[i]) {
                case "module":
                    this.setModuleEvaluation(currentEval);
                    break;
                case "form":
                    this.setFormResponseEvaluation(currentEval);
                    break;
                case "business_rule":
                    this.setBusinessRuleEvaluation(currentEval);
                    break;
                case "user":
                    this.setUserEvaluation(currentEval);
            }
        }
    }
    return this;
};

ExpressionControl.prototype.setOperators = function (operators) {
    var key, i, usableItems, j;
    if (this._operatorSettings !== operators) {
        this._operatorSettings = {};
        if (typeof operators === 'object') {
            for (key in this.OPERATORS) {
                if (this.OPERATORS.hasOwnProperty(key)) {
                    if (typeof operators[key] === "boolean") {
                        if (!operators[key]) {
                            this._operatorSettings[key] = false;
                        } else {
                            this._operatorSettings[key] = this.OPERATORS[key];
                        }
                    } else if (jQuery.isArray(operators[key])) {
                        this._operatorSettings[key] = [];
                        for (i = 0; i < operators[key].length; i += 1) {
                            for (j = 0; j < this.OPERATORS[key].length; j += 1) {
                                if (this.OPERATORS[key][j].text === operators[key][i]) {
                                    this._operatorSettings[key].push(this.OPERATORS[key][j]);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        } else if (typeof operators === 'boolean') {
            if (operators) {
                this._operatorSettings = this.OPERATORS;
            } else {
                this._operatorSettings = operators;
            }
        } else {
            throw new Error("setOperators(): The parameter must be an object literal with settings or boolean.");
        }
    }
    if (this._operatorPanel) {
        this._createOperatorPanel();
    }
    return this;
};

ExpressionControl.prototype._getStringOrNumber = function (value) {
    var aux, wildcard, isNum = false;

    wildcard = "@" + (Math.random(1) * 10).toString().replace(".", "") + "@";

    value = jQuery.trim(value);
    if (this._decimalSeparator !== ".") {
        isNum = value.indexOf(".") < 0;
        if (isNum) {
            aux = value.replace(this._getDecimalSeparatorRegExp(), ".");
        }
    }
    if(isNum && !isNaN(aux) && aux !== "") {
        aux = aux.split(".");
        value = parseInt(aux[0]);
        value += aux[1] ? parseInt(aux[1]) / Math.pow(10, aux[1].length) : 0;
    } else if (value.length > 1) {
        if (value[0] === "\"" && value.slice(-1) === "\"") {
            value = value.slice(1, -1);
        } else if (value[0] === "'" && value.slice(-1) === "'") {
            value = value.slice(1, -1);
        }
    }
    return value;
};

ExpressionControl.prototype.getLabel = function (data) {
    var label, aux, that = this;
    if (data.expType === 'MODULE' || (data.expType === 'CONSTANT'
        && (data.expSubtype === 'date' || data.expSubtype === 'datetime') )) {
        aux = data.expSubtype.toLowerCase();

        label = data.expLabel.replace(new RegExp(this._itemValueWildcard, "g"), function () {
            if (aux === "date") {
                return FormPanelDate.format(data.expValue, that._dateFormat);
            } else if (aux === "datetime") {
                return FormPanelDatetime.format(data.expValue, that._dateFormat, that._timeFormat);
            } else if (aux === 'currency') {
                return FormPanelNumber.format(data.expValue, {
                    precision: 2,
                    groupingSeparator: this._numberGroupingSeparator,
                    decimalSeparator: this._decimalSeparator
                });
            } else {
                return data.expLabel;
            }
        });
    } else {
        label = data.expLabel;
    }

    return label;
};

ExpressionControl.prototype._createItem = function (data, usableItem) {
    var newItem, aux, label, that = this;

    if(usableItem instanceof SingleItem) {
        newItem = usableItem;
    } else {
        newItem = new SingleItem();
    }
    newItem.setFullData(data);
    newItem.setText(this.getLabel(data));
    return newItem;
};
//THIS METHOD MUST BE REPLACED FOR ANOTHER ONE WITH BETTER PERFORMANCE!!!!
ExpressionControl.prototype._getOperatorType = function(operator) {
    var type, key, i, items;
    for (key in this.OPERATORS) {
        if (this.OPERATORS.hasOwnProperty(key)) {
            items = this.OPERATORS[key];
            for (i = 0; i < items.length; i += 1) {
                if(items[i].text === operator) {
                    return key.toUpperCase();
                }
            }
        }
    }

    return null;
};

ExpressionControl.prototype._closeParentPanels = function() {
    if (this._parent) {
        if (this._parent instanceof CriteriaField) {
            this._parent.closePanel();
        } else if (this._parent instanceof UpdaterField) {
            this._parent.closePanels();
        }
    } else {
        this.close();
    }
};

ExpressionControl.prototype._onPanelValueGeneration = function () {
    var that = this;
    return function (panel, subpanel, data) {
        var itemData;
        var valueType;
        var value;
        var aux;
        var parent = subpanel.getParent() || {};
        var label;
        var valueField;
        if (parent.id !== 'variables-list') {
            switch (subpanel.id) {
                case "button-panel-operators":
                    if (data.value == 'close') {
                        that._closeParentPanels();
                    } else if (data.value === 'Run Time') {
                        itemData = {
                            expType: 'CONSTANT',
                            expSubtype: "date",
                            expLabel: translate('LBL_PMSE_RUNTIME_BUTTON'),
                            expValue: 'now'
                        };
                    } else {
                        itemData = {
                            expType: that._getOperatorType(data.value),
                            expLabel: data.value,
                            expValue: data.value
                        };
                    }
                    break;
                case "form-response-evaluation":
                    itemData = {
                        expType: "CONTROL",
                        expLabel: subpanel.getItem("form").getSelectedText() + " " +
                            subpanel.getItem("operator").getSelectedText() + " " +
                            data.status,
                        expOperator: data.operator,
                        expValue: data.status,
                        expField: data.form
                    };
                    break;
                case "form-module-field-evaluation":
                    aux = data.field.split(that._auxSeparator);
                    valueField = subpanel.getItem("value");
                    if (aux[1] === 'Currency') {
                        value = valueField.getAmount();
                    } else if (aux[1] === 'MultiSelect') {
                        value = data.value;
                    } else {
                        value = that._getStringOrNumber(data.value);
                    }
                    valueType = typeof data.value === 'string' ? typeof value : typeof data.value;
                    var op = subpanel.getItem('operator').getSelectedText();
                    label = subpanel.getItem('module').getSelectedData().module_label + ': ' +
                        subpanel.getItem('field').getSelectedText() + ' ' +
                        op + ' ';

                    if (op != 'changes') {
                        switch (aux[1]) {
                            case 'Date':
                            case 'Datetime':
                                label += '%VALUE%';
                                break;
                            case 'user':
                            case 'DropDown':
                                label += valueField.getSelectedText();
                                break;
                            case 'Currency':
                                label += valueField.getCurrencyText() + ' %VALUE%';
                                break;
                            case 'MultiSelect':
                                label += valueField.getSelectionAsText();
                                break;
                            default:
                                label += (valueType === 'string' ? '"' + value + '"' : data.value);
                        }
                    }

                    itemData = {
                        expType: "MODULE",
                        expSubtype: aux[1],
                        expLabel: label,
                        expValue: value,
                        expOperator: data.operator,
                        expModule: data.module,
                        expField: aux[0]
                    };
                    if (aux[1] === 'Currency') {
                        itemData.expCurrency = valueField.getCurrency();
                    }
                    break;
                case 'form-business-rule-evaluation':
                    value = that._getStringOrNumber(data.response);
                    valueType = typeof value;
                    itemData = {
                        expType: "BUSINESS_RULES",
                        expLabel: subpanel.getItem("rule").getSelectedText() + " " +
                            subpanel.getItem("operator").getSelectedText() + " " +
                            (valueType === "string" ? "\"" + value + "\"" : value),
                        expValue: value,
                        expOperator: data.operator,
                        expField: data.rule
                    };
                    break;
                case 'form-user-evaluation':
                    switch (data.operator) {
                        case 'USER_ADMIN|equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_ADMIN_FULL';
                            break;
                        case 'USER_ROLE|equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_ROLE_FULL';
                            break;
                        case 'USER_IDENTITY|equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_USER_FULL';
                            break;
                        case 'USER_ADMIN|not_equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_ADMIN_FULL';
                            break;
                        case 'USER_ROLE|not_equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_ROLE_FULL';
                            break;
                        case 'USER_IDENTITY|not_equals':
                            label = 'LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_USER_FULL';
                            break;
                        default:
                            throw new Error("_onPanelValueGenerator(): Invalid response.");
                    }

                    aux = data.operator.split("|");
                    value = data.value || null;

                    label = translate(label).replace(/%(TARGET|VALUE)%/g, function(x) {
                        if (x === '%TARGET%') {
                            return subpanel.getItem("user").getSelectedText();
                        } else if (x === '%VALUE%') {
                            return subpanel.getItem("value").getSelectedText();
                        }
                        return x;
                    });

                    itemData = {
                        expType: aux[0],
                        expLabel: label,
                        expValue: value,
                        expOperator: aux[1],
                        expField: data.user
                    };
                    break;
                case 'form-constant-basic':
                    if (data.type === 'number') {
                        aux = data.value.split(that._getDecimalSeparatorRegExp());
                        value = parseInt(aux[0], 10);
                        if (aux[1]) {
                            aux = parseInt(aux[1], 10) / Math.pow(10, aux[1].length);
                        } else {
                            aux = 0;
                        }
                        value += aux * (value >= 0 ? 1 : -1);
                        valueType = data.value;
                    } else if (data.type === 'boolean') {
                        value = data.value.toLowerCase() === "false" || data.value === "0" ? false : !!data.value;
                        valueType = value ? "TRUE" : "FALSE";
                    } else {
                        value = data.value;
                        valueType = "\"" +  data.value + "\"";
                    }
                    itemData = {
                        expType: 'CONSTANT',
                        expSubtype: data.type,
                        expLabel: valueType,
                        expValue: value
                    };
                    break;
                case 'form-constant-date':
                    itemData = {
                        expType: 'CONSTANT',
                        expSubtype: "date",
                        expLabel: '%VALUE%',
                        expValue: data.date
                    };
                    break;
                case 'form-constant-datetime':
                    itemData = {
                        expType: 'CONSTANT',
                        expSubtype: "datetime",
                        expLabel: '%VALUE%',
                        expValue: data.datetime
                    };
                    break;
                case 'form-constant-datespan':
                case 'form-constant-timespan':
                    itemData = {
                        expType: "CONSTANT",
                        expSubtype: "timespan",
                        expLabel: data.ammount + data.unittime,
                        expValue: data.ammount + data.unittime
                    };
                    break;
                case 'form-constant-currency':
                    itemData = {
                        expType: 'CONSTANT',
                        expSubtype: 'currency',
                        expLabel: subpanel.getItem("currency").getSelectedText() + " " +
                            subpanel.getItem("amount").getFormattedValue(),
                        expValue: data.amount,
                        expField: data.currency
                    };
                    break;
                default:
                    throw new Error("_onPanelValueGeneration(): Invalid source data.");
            }
        } else {
            itemData = {
                expType: "VARIABLE",
                expSubtype: data.type,
                expLabel: data.text,
                expValue: data.value,
                expModule: data.module
            };
        }

        if (subpanel instanceof FormPanel) {
            subpanel.reset();
        }
        if (itemData) {
            that._itemContainer.addItem(that._createItem(itemData));
        }
    };
};

ExpressionControl.prototype._createOperatorPanel = function () {
    var key;
    if (!this._operatorPanel) {
        this._operatorPanel = new FieldPanelButtonGroup({
            id: "button-panel-operators"
        });
    }
    if (this._operatorSettings) {
        this._operatorPanel.clearItems();
        for (key in this._operatorSettings) {
            if (this._operatorSettings.hasOwnProperty(key)) {
                if (typeof this._operatorSettings[key] === "object") {
                    usableItems = this._operatorSettings[key];
                    for (i = 0; i < usableItems.length; i += 1) {
                        this._operatorPanel.addItem({
                            value: usableItems[i].text
                        });
                    }
                }
            }
        }
        this._operatorPanel.setVisible(!!this._operatorPanel.getItems().length);
    } else {
        this._operatorPanel.setVisible(false);
    }
    return this._operatorPanel;
};

ExpressionControl.prototype.addVariablesList = function (data, cfg) {
    var i, conf, itemsContentHook = function (item, data) {
        var mainLabel = "[item]", span1, span2, wrapperDiv;

        mainLabel = data.text;
        wrapperDiv = this.createHTMLElement('div');
        span1 = this.createHTMLElement("span");
        span1.className = "adam expressionbuilder-variableitem-text";
        span1.textContent = mainLabel;
        span2 = this.createHTMLElement("span");
        span2.className = "adam expressionbuilder-variableitem-datatype";
        span2.textContent = data.type;
        wrapperDiv.appendChild(span1);
        wrapperDiv.appendChild(span2);
        mainLabel = wrapperDiv;

        return mainLabel;
    };
    conf = {
        fieldToFilter: "type",
        filter: cfg.typeFilter,
        filterMode: cfg.filterMode,
        title: cfg.moduleText,
        data: [],
        itemsContent: itemsContentHook
    };
    for (i = 0; i < data.length; i += 1) {
        conf.data.push({
            value: data[i][cfg.valueField],
            text: data[i][cfg.textField],
            type: data[i][cfg.typeField],
            module: cfg.moduleValue
        });
    }
    newList = new ListPanel(conf);
    if (newList.getItems().length) {
        this._variablePanel.addItem(newList);
    }
    return this;
};

ExpressionControl.prototype._onLoadVariableDataSuccess = function () {
    var that = this;
    return function (data) {
        var settings = that._variableSettings, cfg, i, j, fields, newList, aux = {}, filterFunction;
        if (settings.dataRoot) {
            data = data[settings.dataRoot];
        }
        if (settings.dataFormat === "hierarchical") {
            for (i = 0; i < data.length; i += 1) {
                that.addVariablesList(
                    data[i][settings.dataChildRoot],
                    {
                        textField: settings.textField,
                        valueField: settings.valueField,
                        typeField: settings.typeField,
                        typeFilter: settings.typeFilter,
                        filterMode: settings.filterMode,
                        moduleText: data[i][settings.moduleTextField],
                        moduleValue: data[i][settings.moduleValueField]
                    }
                );
                /*cfg = {
                    fieldToFilter: that._variableSettings.typeField,
                    filter: that._variableSettings.typeFilter,
                    title: data[i][settings.moduleTextField],
                    data: [],
                    itemsContent: itemsContentHook
                };
                fields = data[i][settings.dataChildRoot];
                for (j = 0; j < fields.length; j += 1) {
                    cfg.data.push({
                        value: fields[j][settings.valueField],
                        text: fields[j][settings.textField],
                        type: fields[j][settings.typeField],
                        module: data[i][settings.moduleValueField]
                    });
                }
                newList = new ListPanel(cfg);
                if (newList.getItems().length) {
                    that._variablePanel.addItem(newList);
                }*/
            }
        } else {
            if (typeof settings.typeFilter === 'string') {
                filterFunction = function (value) {
                    return settings.typeFilter === value;
                };
            } else if (jQuery.isArray(settings.typeFilter)) {
                filterFunction = function (value) {
                    return settings.typeFilter.indexOf(value) >= 0;
                };
            } else if (typeof settings.typeFilter === 'function') {
                filterFunction = settings.typeFilter;
            } else {
                filterFunction = function () {
                    return true;
                };
            }
            for (i = 0; i < data.length; i += 1) {
                if (filterFunction(data[i][settings.typeField], data[i])) {
                    if (!aux[data[i][settings.moduleValueField]]) {
                        aux[data[i][settings.moduleValueField]] = {
                            fields: []
                        };
                    }
                    aux[data[i][settings.moduleValueField]].fields.push(data[i]);
                }
            }
            j = 0;
            for (i in aux) {
                if (aux.hasOwnProperty(i)) {
                    that.addVariablesList(aux[i].fields, {
                        textField: settings.textField,
                        valueField: settings.valueField,
                        typeField: settings.typeField,
                        typeFilter: settings.typeFilter,
                        filterMode: settings.filterMode,
                        moduleText: aux[i].fields[0][settings.moduleTextField],
                        moduleValue: aux[i].fields[0][settings.moduleValueField]
                    });
                }
            }
        }
    };
};

ExpressionControl.prototype._onLoadVariableDataError = function () {};

ExpressionControl.prototype._createVariablePanel = function () {
    var settings = this._variableSettings, i;
    if (!this._variablePanel) {
        this._variablePanel = new MultipleCollapsiblePanel({
            id: "variables-list",
            title: translate("LBL_PMSE_EXPCONTROL_VARIABLES_PANEL_TITLE"),
            onExpand: this._onExpandPanel()
        });
        this._panel.addItem(this._variablePanel);
    }
    if (settings) {
        this._variablePanel.clearItems();
        if (settings.dataURL) {
            this._proxy.url = settings.dataURL;
            this._proxy.getData({
                base_module: PROJECT_MODULE
            }, {
                success: this._onLoadVariableDataSuccess(),
                error : this._onLoadVariableDataError()
            });
        } else {
            (this._onLoadVariableDataSuccess())(settings.data);
        }
    }
    this._variablePanel.setVisible(!!settings);
    return this._variablePanel;
};

ExpressionControl.prototype._createModulePanel = function () {
    var moduleField, that = this, settings = this._evaluationSettings.module, currentType;
    var currentVal, currentMod;
    if (!this._evaluationPanels.module) {
        this._evaluationPanels.module = new FormPanel({
            expressionControl: this,
            id: "form-module-field-evaluation",
            title: translate("LBL_PMSE_EXPCONTROL_MODULE_FIELD_EVALUATION_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "dropdown",
                    name: "module",
                    label: translate("LBL_PMSE_EXPCONTROL_MODULE_FIELD_EVALUATION_MODULE"),
                    width: "100%",
                    required: true,
                    dependantFields: ['field']
                },
                {
                    type: "dropdown",
                    name: "field",
                    label: translate("LBL_PMSE_EXPCONTROL_MODULE_FIELD_EVALUATION_VARIABLE"),
                    width: "35%",
                    required: true,
                    dependantFields: ['value'],
                    dependencyHandler: function (dependantField, field, value) {
                        var settings = that._evaluationSettings.module,
                            url = settings.fieldDataURL.replace("{{MODULE}}", value),
                            attributes = jQuery.extend(true, {
                                base_module: PROJECT_MODULE,
                                call_type: 'PD'
                            }, settings.fieldDataURLAttr || {});

                        if (value) {
                            dependantField.setDataURL(url)
                                .setAttributes(attributes)
                                .setDataRoot(settings.fieldDataRoot)
                                .setLabelField(settings.fieldTextField)
                                .setValueField(function (field, data) {
                                    return data[settings.fieldValueField] + that._auxSeparator + data[settings.fieldTypeField];
                                })
                                .load();
                            dependantField.fireDependentFields();
                        } else {
                            dependantField.clearOptions();
                        }
                    }
                },
                {
                    type: "dropdown",
                    name: "operator",
                    label: "",
                    width: "30%",
                    labelField: "text",
                    valueField: "value",
                    required: true,
                    options: this.OPERATORS.comparison,
                    dependantFields: ['value']
                },
                {
                    type: "text",
                    name: "value",
                    label: translate("LBL_PMSE_EXPCONTROL_MODULE_FIELD_EVALUATION_VALUE"),
                    width: "35%",
                    required: true,
                    dependencyHandler: function (dependantField, parentField, value) {
                        var operatorField;
                        if (parentField.getName() == 'field') {
                            var form = dependantField.getForm(),
                                type = value.split(that._auxSeparator)[1],
                                selVal = $('#evn_params').val(),
                                modVal = form.getItem('module')._value;
                            type = type && that._typeToControl[type.toLowerCase()];
                            operatorField = form.getItem('operator');
                            if ((type && type !== currentType) ||
                                type === 'dropdown' ||
                                selVal !== currentVal ||
                                modVal !== currentMod) {
                                var labelField = 'textfield',
                                    newFieldSettings = {
                                        type: type,
                                        width: dependantField.width,
                                        label: dependantField.getLabel(),
                                        name: dependantField.getName()
                                    },
                                    operators = that.OPERATORS.comparison.slice(0, 2),
                                    setPrecision = true,
                                    setGrouping = true;
                                currentType = type;
                                currentVal = selVal;
                                currentMod = modVal;
                                switch (type) {
                                    case 'checkbox':
                                        labelField = 'text';
                                        break;
                                    case 'dropdown':
                                        labelField = 'text';
                                    case 'multiselect':
                                    case 'radio':
                                        var aux = parentField.getSelectedData() ||
                                                parentField._getFirstAvailableOption(),
                                            itemsObj = aux.optionItem;
                                        newFieldSettings.options = [];
                                        Object.keys(itemsObj).forEach(function(item, index, arr) {
                                            newFieldSettings.options.push({
                                                value: item,
                                                label: itemsObj[item]
                                            });
                                        });
                                        break;
                                    case 'friendlydropdown':
                                        newFieldSettings.options = [
                                            {
                                                'label': translate('LBL_PMSE_FORM_OPTION_CURRENT_USER'),
                                                'value': 'currentuser'
                                            },
                                            {
                                                'label': translate('LBL_PMSE_FORM_OPTION_RECORD_OWNER'),
                                                'value': 'owner'
                                            },
                                            {
                                                'label': translate('LBL_PMSE_FORM_OPTION_SUPERVISOR'),
                                                'value': 'supervisor'
                                            }
                                        ];
                                        newFieldSettings.searchMore = {
                                            module: 'Users',
                                            fields: ['id', 'full_name'],
                                            filterOptions: null
                                        };
                                        newFieldSettings.searchValue = PMSE_USER_SEARCH.value;
                                        newFieldSettings.searchLabel = PMSE_USER_SEARCH.text;
                                        newFieldSettings.searchURL = PMSE_USER_SEARCH.url;
                                        break;
                                    case 'datetime':
                                        newFieldSettings.timeFormat = that._timeFormat;
                                    case 'date':
                                        newFieldSettings.dateFormat = that._dateFormat;
                                        operators = that.OPERATORS.comparison.slice(0, 6);
                                        labelField = 'datefield';
                                        break;
                                    case 'currency':
                                        newFieldSettings.currencies = that._currencies;
                                        newFieldSettings.precision = 2;
                                        setPrecision = false;
                                        newFieldSettings.groupingSeparator = that._numberGroupingSeparator;
                                        setGrouping = false;
                                    case 'decimal':
                                    case 'float':
                                    case 'number':
                                        if (setPrecision) {
                                            newFieldSettings.precision = -1;
                                            setPrecision = false;
                                        }
                                    case 'integer':
                                        labelField = 'text';
                                        if (setPrecision) {
                                            newFieldSettings.precision = 0;
                                        }
                                        if (setGrouping) {
                                            newFieldSettings.groupingSeparator = '';
                                        }
                                        newFieldSettings.decimalSeparator = that._decimalSeparator;
                                        operators = that.OPERATORS.comparison.slice(0, 6);
                                        break;
                                    case 'text':
                                        operators = that.OPERATORS.comparison.slice();
                                        operators.splice(2, 4);
                                        break;
                                    default:
                                }
                                if (that.EXTRA_OPERATORS[labelField]) {
                                    operators = operators.concat(that.EXTRA_OPERATORS[labelField]);
                                }
                                if (that._name == 'evn_criteria') {
                                    if (!selVal) {
                                        operators = operators.concat(that.OPERATORS.changes);
                                    } else if (selVal == 'updated' || selVal == 'allupdates') {
                                        var url = parentField._dataURL,
                                            base = parentField._attributes ? parentField._attributes.base_module : false;
                                        if (url && base && url.endsWith(base)) {
                                            operators = operators.concat(that.OPERATORS.changes);
                                        }
                                    }
                                }
                                operatorField.setLabelField(labelField);
                                operatorField.setOptions(operators);
                                var newField = form._createField(newFieldSettings);
                                form.replaceItem(newField, dependantField);
                                newField.setDependencyHandler(dependantField._dependencyHandler);
                                dependantField = newField;
                            }
                        } else {
                            operatorField = parentField;
                        }
                        var showValue = (operatorField.getValue() != 'changes');
                        dependantField.setVisible(showValue);
                    }
                }
            ],
            onCollapse: function (formPanel) {
                var valueField = formPanel.getItem("value");

                if (valueField instanceof FormPanelDate) {
                    valueField.close();
                }
            }
        });
        this._evaluationPanel.addItem(this._evaluationPanels.module);
    }
    if (settings) {
        moduleField = this._evaluationPanels.module.getItem("module");
        moduleField.setDataURL(settings.dataURL)
            .setDataRoot(settings.dataRoot)
            .setLabelField(settings.textField)
            .setValueField(settings.valueField)
            .load();
        this._evaluationPanel.enable();
        this._evaluationPanel.setVisible(true);
    } else {
        this._evaluationPanel.disable();
    }
    return this._evaluationPanels.module;
};

ExpressionControl.prototype._createFormResponsePanel = function () {
    var formField, settings;
    if (!this._evaluationPanels.formResponse) {
        this._evaluationPanels.formResponse = new FormPanel({
            expressionControl: this,
            id: "form-response-evaluation",
            title: translate("LBL_PMSE_EXPCONTROL_FORM_RESPONSE_EVALUATION_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "dropdown",
                    name: "form",
                    label: translate("LBL_PMSE_EXPCONTROL_FORM_RESPONSE_EVALUATION_FORM"),
                    width: "40%"
                }, {
                    type: "dropdown",
                    name: "operator",
                    label: "",
                    width: "20%",
                    options: this.OPERATORS.comparison.slice(0, 2),
                    valueField: "value",
                    labelField: "text"
                }, {
                    type: "dropdown",
                    name: "status",
                    label: translate("LBL_PMSE_EXPCONTROL_FORM_RESPONSE_EVALUATION_STATUS"),
                    width: "40%",
                    options: [
                        {
                            label: "Approved",
                            value: "Approved"
                        }, {
                            label: "Rejected",
                            value: "Rejected"
                        }
                    ]
                }
            ]
        });
    }
    settings = this._evaluationSettings.formResponse;
    if (settings) {
        formField = this._evaluationPanels.formResponse.getItem("form");
        this._evaluationPanel.addItem(this._evaluationPanels.formResponse);
        formField.setDataURL(settings.dataURL)
            .setDataRoot(settings.dataRoot)
            .setLabelField(settings.textField)
            .setValueField(settings.valueField)
            .load();
    }

    return this._evaluationPanels.formResponse;
};

ExpressionControl.prototype._createBusinessRulePanel = function () {
    var rulesField, settings = this._evaluationSettings.businessRule;
    if (!this._evaluationPanels.businessRule) {
        this._evaluationPanels.businessRule = new FormPanel({
            expressionControl: this,
            id: "form-business-rule-evaluation",
            type: "form",
            title: translate("LBL_PMSE_EXPCONTROL_BUSINESS_RULES_EVALUATION_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "dropdown",
                    name: "rule",
                    label: translate("LBL_PMSE_EXPCONTROL_BUSINESS_RULES_EVALUATION_BR"),
                    width: "40%",
                    required: true
                }, {
                    type: "dropdown",
                    label: "",
                    name: "operator",
                    width: "20%",
                    labelField: "text",
                    options: this.OPERATORS.comparison.slice(0, 2),
                }, {
                    type: "text",
                    label: translate("LBL_PMSE_EXPCONTROL_BUSINESS_RULES_EVALUATION_RESPONSE"),
                    name: "response",
                    width: "40%"
                }
            ]
        });
    }
    if (settings) {
        rulesField = this._evaluationPanels.businessRule.getItem("rule");
        this._evaluationPanel.addItem(this._evaluationPanels.businessRule);
        rulesField.setDataURL(settings.dataURL)
            .setDataRoot(settings.dataRoot)
            .setLabelField(settings.textField)
            .setValueField(settings.valueField)
            .load();
    }

    return this;
};

ExpressionControl.prototype._createUserPanel = function () {
    var userField, settings = this._evaluationSettings.user;
    if (!this._evaluationPanels.user) {
        this._evaluationPanels.user = new FormPanel({
            expressionControl: this,
            id: "form-user-evaluation",
            type: "form",
            title: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "dropdown",
                    name: "user",
                    label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_USER"),
                    width: "35%",
                    options: [
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_CURRENT"),
                            value: "current_user"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_SUPERVISOR"),
                            value: "supervisor"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_OWNER"),
                            value: "owner"
                        }
                    ]
                }, {
                    type: "dropdown",
                    name: "operator",
                    label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_OPERATOR"),
                    width: "30%",
                    dependantFields: ['value'],
                    options: [
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_ADMIN"),
                            value: "USER_ADMIN|equals"
                        },
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_ROLE"),
                            value: "USER_ROLE|equals"
                        },
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_USER"),
                            value: "USER_IDENTITY|equals"
                        },
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_ADMIN"),
                            value: "USER_ADMIN|not_equals"
                        },
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_ROLE"),
                            value: "USER_ROLE|not_equals"
                        },
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_IS_NOT_USER"),
                            value: "USER_IDENTITY|not_equals"
                        }
                    ]
                }, {
                    type: "friendlydropdown",
                    name: "value",
                    label: translate("LBL_PMSE_EXPCONTROL_USER_EVALUATION_VALUE"),
                    width: "35%",
                    required: true,
                    searchValue: PMSE_USER_SEARCH.value,
                    searchLabel: PMSE_USER_SEARCH.text,
                    dependencyHandler: function(dependantField, field, value) {
                        var condition = value.split("|")[0];
                        dependantField.setSearchURL(null)
                            .setDataURL(null);
                        switch (condition) {
                            case 'USER_ADMIN':
                                dependantField.clearOptions().disable();
                                var spans = document.getElementsByClassName('select2-chosen');
                                for (var i=0;i<spans.length;i++) {
                                    spans[i].innerText = "";
                                }
                                break;
                            case 'USER_ROLE':
                                dependantField.setDataURL(settings.userRolesDataURL)
                                    .setDataRoot(settings.userRolesDataRoot)
                                    .setLabelField(settings.userRolesLabelField)
                                    .setValueField(settings.userRolesValueField)
                                    .disableSearchMore()
                                    .load()
                                    .enable();
                                break;
                            case 'USER_IDENTITY':
                                dependantField.clearOptions()
                                    .setSearchURL(PMSE_USER_SEARCH.url)
                                    .enable()
                                    .enableSearchMore({
                                        module: "Users",
                                        fields: ["id", "full_name"]
                                    });
                        }
                    }
                }
            ]
        });
        this._evaluationPanel.addItem(this._evaluationPanels.user);
    }
    if (settings) {
        userField = this._evaluationPanels.user.getItem("user");
        userField.setDataURL(settings.defaultUsersDataURL)
            .setDataRoot(settings.defaultUsersDataRoot)
            .setLabelField(settings.defaultUsersLabelField)
            .setValueField(settings.defaultUsersValueField)
            .load();
        this._evaluationPanels.user.enable();
    } else {
        this._evaluationPanels.user.disable();
    }
    return this;
};

ExpressionControl.prototype._isRegExpSpecialChar = function (c) {
    switch (c) {
        case "\\":
        case "^":
        case "$":
        case "*":
        case "+":
        case "?":
        case ".":
        case "(":
        case ")":
        case "|":
        case "{":
        case "}":
            return true;
    }
    return false;
};

ExpressionControl.prototype._getDecimalSeparatorRegExp = function () {
    var prefix = "";
    if (this._isRegExpSpecialChar(this._decimalSeparator)) {
        prefix = "\\";
    }
    return new RegExp(prefix + this._decimalSeparator, "g");
};

ExpressionControl.prototype._getNumberRegExp = function () {
    var prefix = "";
    if (this._isRegExpSpecialChar(this._decimalSeparator)) {
        prefix = "\\";
    }
    return new RegExp("^-?\\d+(" + (prefix + this._decimalSeparator) + "\\d+)?$");
};

ExpressionControl.prototype._onBasicConstantKeyUp = function () {
    var that = this;
    return function (field, nextValue, keyCode) {
        var form = field.getForm(),
            numberButton = form.getItem("btn_number"),
            booleanButton = form.getItem("btn_boolean"),
            nextValue = nextValue.toLowerCase();

        if (that._getNumberRegExp().test(nextValue)) {
            numberButton.enable();
            booleanButton.enable();
        } else {
            numberButton.disable();
            if (nextValue === "true" || nextValue === "false") {
                booleanButton.enable();
            } else {
                booleanButton.disable();
            }
        }
    };
};

ExpressionControl.prototype._createDateConstantPanel = function() {
    var settings = this._constantSettings.date;
    if (!this._constantPanels.date) {
        this._constantPanels.date = new FormPanel({
            expressionControl: this,
            id: "form-constant-date",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_FIXED_DATE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "date",
                    name: "date",
                    label: "Date",
                    width: "100%",
                    dateFormat: this._dateFormat,
                    required: true
                }
            ],
            onCollapse: function (formPanel) {
                formPanel.getItem("date").close();
            }
        });
        this._constantPanel.addItem(this._constantPanels.date);
    }
    if (settings) {
        this._constantPanels.date.enable();
    } else {
        this._constantPanels.date.disable();
    }

    return this;
};

ExpressionControl.prototype._createDateTimeConstantPanel = function() {
    var settings = this._constantSettings.datetime;
    if (!this._constantPanels.datetime) {
        this._constantPanels.datetime = new FormPanel({
            expressionControl: this,
            id: "form-constant-datetime",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_FIXED_DATETIME"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "datetime",
                    name: "datetime",
                    label: "Date Time",
                    width: "100%",
                    dateFormat: this._dateFormat,
                    timeFormat: this._timeFormat,
                    required: true
                }
            ],
            onCollapse: function (formPanel) {
                formPanel.getItem("datetime").close();
            }
        });
        this._constantPanel.addItem(this._constantPanels.datetime);
    }
    if (settings) {
        this._constantPanels.datetime.enable();
    } else {
        this._constantPanels.datetime.disable();
    }
    return this;
};

ExpressionControl.prototype._createTimespanPanel = function() {
    var settings = this._constantSettings.timespan;
    if (!this._constantPanels.timespan) {
        this._constantPanels.timespan = new FormPanel({
            expressionControl: this,
            id: "form-constant-timespan",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "integer",
                    name: "ammount",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_AMOUNT"),
                    filter: "integer",
                    width: "40%",
                    required: true,
                    disabled: true
                }, {
                    type: "dropdown",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_UNIT"),
                    name: "unittime",
                    width: "60%",
                    disabled: true,
                    options: [
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_YEARS"),
                            value: "y"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_MONTHS"),
                            value: "m"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_WEEKS"),
                            value: "w"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_DAYS"),
                            value: "d"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_HOURS"),
                            value: "h"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_MINUTES"),
                            value: "min"
                        }
                    ]
                }
            ]
        });
        this._constantPanel.addItem(this._constantPanels.timespan);
    }
    if (settings) {
        this._constantPanels.timespan.enable();
    } else {
        this._constantPanels.timespan.disable();
    }

    return this;
};

ExpressionControl.prototype._createDatespanPanel = function() {
    var settings = this._constantSettings.datespan;
    if (!this._constantPanels.datespan) {
        this._constantPanels.datespan = new FormPanel({
            expressionControl: this,
            id: "form-constant-datespan",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_TITLE"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            items: [
                {
                    type: "integer",
                    name: "ammount",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_AMOUNT"),
                    filter: "integer",
                    width: "40%",
                    required: true,
                    disabled: true
                }, {
                    type: "dropdown",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_UNIT"),
                    name: "unittime",
                    width: "60%",
                    disabled: true,
                    options: [
                        {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_YEARS"),
                            value: "y"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_MONTHS"),
                            value: "m"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_WEEKS"),
                            value: "w"
                        }, {
                            label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TIMESPAN_DAYS"),
                            value: "d"
                        }
                    ]
                }
            ]
        });
        this._constantPanel.addItem(this._constantPanels.datespan);
    }
    if (settings) {
        this._constantPanels.datespan.enable();
    } else {
        this._constantPanels.datespan.disable();
    }

    return this;
};

ExpressionControl.prototype._createCurrencyPanel = function() {
    var settings = this._constantSettings.currency;
    if (!this._constantPanels.currency) {
        this._constantPanels.currency = new FormPanel({
            expressionControl: this,
            id: "form-constant-currency",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_CURRENCY"),
            items: [
                {
                    type: "dropdown",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_CURRENCY_CURRENCY"),
                    name: "currency",
                    width: "40%",
                    labelField: function (dropdown, item) {
                        return item.iso ? item.symbol + " (" + item.iso + ")" : item.name;
                    },
                    valueField: 'id',
                    required: true,
                    onChange: function (dropdown, newValue, oldValue) {
                        var form = dropdown.getForm(),
                            amountField = form.getItem("amount"),
                            originalAmount = amountField.getValue(),
                            convertedAmount = originalAmount,
                            selectedData;

                        origCurrency = dropdown.getOptionData(oldValue);
                        destCurrency = dropdown.getSelectedData();

                        if (origCurrency.rate === 1) {
                            convertedAmount = originalAmount * destCurrency.rate;
                        } else if (destCurrency.rate === 1) {
                            convertedAmount = originalAmount / origCurrency.rate;
                        } else if (origCurrency.rate !== destCurrency.rate) {
                            convertedAmount = originalAmount / origCurrency.rate * destCurrency.rate;
                        }

                        amountField.setValue(convertedAmount);
                    }
                }, {
                    type: "number",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_CURRENCY_AMOUNT"),
                    name: "amount",
                    width: "60%",
                    decimalSeparator: this._decimalSeparator,
                    groupingSeparator: this._numberGroupingSeparator,
                    required: true
                }
            ]
        });
        this._constantPanel.addItem(this._constantPanels.currency);
        this._updateCurrenciesToCurrenciesForm();
    }
    if (settings) {
        this._constantPanels.currency.enable();
    } else {
        this._constantPanels.currency.disable();
    }
    return this;
};

ExpressionControl.prototype._createBasicConstantPanel = function () {
    var settings = this._constantSettings.basic, onClickHandler, basicForm, aux;
    if (!this._constantPanels.basic) {
        onClickHandler = function (clickedButton) {
            var form = clickedButton.getForm(),
                typeField = form.getItem("type");

            typeField.setValue(clickedButton.getName().substr(4));
            form.submit();
        };
        this._constantPanels.basic = new FormPanel({
            expressionControl: this,
            id: "form-constant-basic",
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC"),
            foregroundAppendTo: this._panel._getUsableAppendTo(),
            submitVisible: false,
            items: [
                {
                    name: "value",
                    type: "text",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC_VALUE"),
                    width: "100%",
                    onKeyUp: this._onBasicConstantKeyUp()
                }, {
                    name: 'type',
                    type: 'hidden',
                    label: ""
                }, {
                    type: "button",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC_ADD_STRING"),
                    name: "btn_string",
                    width: "33%",
                    onClick: onClickHandler
                }, {
                    type: "button",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC_ADD_NUMBER"),
                    name: "btn_number",
                    width: "33%",
                    disabled: true,
                    onClick: onClickHandler
                }, {
                    type: "button",
                    label: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC_ADD_BOOLEAN"),
                    name: "btn_boolean",
                    width: "33%",
                    onClick: onClickHandler
                }
            ],
            onSubmit: function (form) {
                var typeField = form.getItem("type"), enabledButtons = 0, btn, btns, i, aux;

                if(!typeField.getValue()) {
                    btns = ["btn_string", "btn_number", "btn_boolean"];
                    for (i = 0; i < btns.length; i += 1) {
                        aux = form.getItem(btns[i]);
                        if (aux.visible && !aux.isDisabled()) {
                            btn = aux;
                            enabledButtons += 1;
                        }
                    }
                    if (enabledButtons === 1) {
                        form.getItem("type").setValue(btn.getLabel().substr(4));
                    } else {
                        return false;
                    }
                }
            }
        });
        this._constantPanel.addItem(this._constantPanels.basic);
    }
    basicForm = this._constantPanels.basic;

    if (settings) {
        basicForm.getItem("btn_string").setVisible(settings === true || !!settings.string);
        basicForm.getItem("btn_number").setVisible(settings === true || !!settings.number);
        basicForm.getItem("btn_boolean").setVisible(settings === true || !!settings.boolean);
        if (settings !== true && !!settings.number && !settings.string && !settings.boolean) {
            this._constantPanels.basic.setTitle(App.lang.get('LBL_PMSE_EXPCONTROL_CONSTANTS_BASIC_NUMBER', 'pmse_Project'));
        }
        settings = settings === true || (settings.string || settings.number || settings.boolean);
        if (settings) {
            this._constantPanel.setVisible(true);
            basicForm.enable();
        } else {
            basicForm.disable();
        }

    } else {
        basicForm.disable();
    }

    return this;
};

ExpressionControl.prototype._onExpandPanel = function() {
    var that = this;
    return function(panel) {
        var items = that._panel.getItems(), i;
        for (i = 0; i < items.length; i += 1) {
            if (items[i] instanceof CollapsiblePanel && items[i] !== panel) {
                items[i].collapse();
            }
        }
    };
};

ExpressionControl.prototype._createMainPanel = function () {
    var items = [];
    if (!this._externalItemContainer) {
        items.push(this._itemContainer);
    }
    if (!this._panel.getItems().length) {
        this._createOperatorPanel();
        items.push(this._operatorPanel);

        this._evaluationPanel = new MultipleCollapsiblePanel({
            title: translate("LBL_PMSE_EXPCONTROL_EVALUATIONS_TITLE"),
            onExpand: this._onExpandPanel()
        });
        if (this._evaluationSettings) {
            this._createModulePanel();
            this._createFormResponsePanel();
            this._createBusinessRulePanel();
            this._createUserPanel();
        }
        items.push(this._evaluationPanel);
        this._evaluationPanel.setVisible(!!this._evaluationPanel.getItems().length);

        this._constantPanel = new MultipleCollapsiblePanel({
            title: translate("LBL_PMSE_EXPCONTROL_CONSTANTS_TITLE"),
            onExpand: this._onExpandPanel()
        });
        if (this._constantSettings) {
            this._createDateConstantPanel();
            this._createDateTimeConstantPanel();
            this._createTimespanPanel();
            this._createDatespanPanel();
            this._createCurrencyPanel();
            this._createBasicConstantPanel();
        }
        items.push(this._constantPanel);
        this._constantPanel.setVisible(!!this._constantPanel.getItems().length);

        this._panel.setItems(items);
        this._createVariablePanel();
    }
    return this._panel;
};

/*ExpressionControl.prototype._appendPanel = function () {
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
        this._panel.setWidth(this._matchOwnerWidth ? owner.offsetWidth : this.width);
        position = getRelativePosition(owner, appendPanelTo);
    } else {
        this._panel.setWidth(this.width);
        position = {left: 0, top: 0};
    }
    this._panel.setPosition(position.left, position.top + offsetHeight - 1);
    return this;
};*/

ExpressionControl.prototype.isPanelOpen = function () {
    return this._panel && this._panel.isOpen();
};

ExpressionControl.prototype.open = function () {
    this.getHTML();
    if (!this.isPanelOpen()) {
        this._constantPanel.collapse(true);
        this._variablePanel.collapse(true);
        this._evaluationPanel.collapse(true);
    }
    this._panel.open();
    return this;
};

ExpressionControl.prototype.close = function () {
    this._panel.close();
    return this;
};

ExpressionControl.prototype.isValid = function() {
    var i, cIsEval, pIsEval, valid = true, prev = null, current, pendingToClose = 0, dataNum = 0, msg = "invalid criteria syntax", items = this._itemContainer.getItems();

    for (i = 0; i < items.length; i += 1) {
        current = items[i].getData();
        cIsEval = current.expType === "MODULE" || current.expType === "BUSINESS_RULES" || current.expType === "CONTROL"
        || current.expType === "USER_ADMIN" || current.expType === "USER_ROLE"
        || current.expType === "USER_IDENTITY" || current.expType === "CONSTANT" || current.expType === "VARIABLE";

        if (cIsEval || (current.expType === "GROUP" && current.expValue === "(") || (current.expType === "LOGIC" && current.expValue === "NOT")) {
            valid = !(prev && (pIsEval || (prev.expType === "GROUP" && prev.expValue === ")")));
            if (valid &&
                (current.expOperator == 'changes' ||
                    current.expOperator == 'changes_from' ||
                    current.expOperator == 'changes_to')) {
                if (this._name == 'evn_criteria') {
                    var selVal = $('#evn_params').val();
                    valid = !selVal || selVal == 'updated' || selVal == 'allupdates';
                } else {
                    valid = false;
                }
            }
        } else {
            valid = prev && ((prev.expType === "GROUP" && prev.expValue === ")") || (pIsEval || cIsEval));
            valid = valid === null ? true : valid;
        }

        if (current.expType === 'GROUP') {
            if (current.expValue === ')') {
                valid = valid && pendingToClose > 0;
                pendingToClose -= 1;
            } else if (current.expValue === '(') {
                pendingToClose += 1;
            }
        }

        if (!valid) {
            break;
        }
        prev = current;
        pIsEval = cIsEval;
    }

    if (valid) {
        if (prev) {
            valid = valid && prev.expType !== 'LOGIC' && prev.expType !== 'ARITHMETIC' && !(prev.expType === 'GROUP' && prev.expValue === "(");
        }
        valid = valid && pendingToClose === 0;
    }

    return valid;
};

ExpressionControl.prototype.createHTML = function () {
    var control;
    if (!this.html) {
        this._createMainPanel();
        this.html = this._panel.getHTML();

        this.style.applyStyle();

        this.style.addProperties({
            width: this.width,
            height: this.height,
            zIndex: this.zOrder
        });
    }

    return this.html;
};
