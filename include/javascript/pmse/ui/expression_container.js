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
var PMSE = PMSE || {};
var ExpressionContainer = function (options, parent) {
    PMSE.Element.call(this, options);
    //this.isCBOpen = null;
    //this.isDDOpen = null;
    this.tooltipHandler = null;
    this.expression = null;
    this.value = null;
    this.parent = null;
    this.onChange = null;
    this.onBeforeOpenPanel = null;
    this._select2Input = null;
    this._searchFunction = null;
    ExpressionContainer.prototype.init.call(this, options, parent);
};

ExpressionContainer.prototype = new PMSE.Element();

ExpressionContainer.prototype.type = 'ExpressionContainer';

ExpressionContainer.prototype.family = 'ExpressionContainer';

ExpressionContainer.prototype.unsupportedDataTypes = [
    'Encrypt',
    'IFrame',
    'Image',
    'MultiSelect',
    'FlexRelate',
    'Relate'
];

ExpressionContainer.prototype.init = function (options, parent) {
    var defaults = {
        expression: [],
        onBeforeOpenPanel: null,
        onChange: null
    };

    // Do not deep copy here
    $.extend(defaults, options);

    this.setExpressionValue(defaults.expression)
        //.setIsCBOpen(defaults.isCBOpen)
        //.setIsDDOpen(defaults.isDDOpen)
        .setParent(parent)
        .setOnBeforeOpenPanel(defaults.onBeforeOpenPanel)
        .setOnChangeHandler(defaults.onChange);

    this._searchFunction = _.debounce(function(queryObject) {
        var proxy = new SugarProxy(),
            termRegExp = /\{TERM\}/g,
            result = {
                more: false
            }, term = jQuery.trim(queryObject.term),
            finalData = [],
            searchURL = 'pmse_Project/CrmData/users?filter={TERM}';

        proxy.url = searchURL.replace(termRegExp, queryObject.term);

        proxy.getData(null, {
            success: function (data) {
                if (!data.success) {
                    throw new Error("ExpressionContainer's search function: Error.");
                }
                data = data.result;
                data.forEach(function (item) {
                    finalData.push({
                        value: item.value,
                        text: item.text
                    });
                });

                result.results = finalData;
                queryObject.callback(result);
            },
            error: function () {
                console.log("failure", arguments);
            }
        });
    }, 1500);
};

ExpressionContainer.prototype.setOnBeforeOpenPanel = function (handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error("setOnBeforeOpenPanel(): The parameter must be a function or null.");
    }
    this.onBeforeOpenPanel = handler;
    return this;
};

ExpressionContainer.prototype.setOnChangeHandler = function(handler) {
    if (!(handler === null || typeof handler === 'function')) {
        throw new Error("setOnChangeHandler(): The parameter must be a function or null.");
    }
    this.onChange = handler;
    return this;
};

ExpressionContainer.prototype.setExpressionValue = function (value) {
    this.expression = value;
    this.updateExpressionView();
    return this;
};

//ExpressionContainer.prototype.setIsCBOpen = function (value) {
//    this.isCBOpen = value;
//    return this;
//};
//
//ExpressionContainer.prototype.setIsDDOpen = function (value) {
//    this.isDDOpen = value;
//    return this;
//};

ExpressionContainer.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};

ExpressionContainer.prototype.clear = function () {
    return this;
};

//ExpressionContainer.prototype.addItem = function (value) {
//    console.log('AddItem method was called.' + this.id, value);
//    this.setExpressionValue(value);
//    return this;
//};

ExpressionContainer.prototype.remove = function () {
    $(this.html).remove();
    delete this.tooltipHandler;
    delete this.expression;
    delete this.value;
    delete this.parent;
};

ExpressionContainer.prototype.getObject = function () {
    return this.expression;
};

ExpressionContainer.prototype.isValid = function () {
    return true;
};

ExpressionContainer.prototype.createHTML = function () {
    var dvContainer,
        span,
        input;

    if(this.html) {
        return this.html;
    }

    span = this.createHTMLElement('span');
    dvContainer = this.createHTMLElement("div");
    dvContainer.className = 'expression-container-cell';
    $(dvContainer).attr('data-placement', 'bottom');
    dvContainer.setAttributeNode(document.createAttribute('title'));
    input = this.createHTMLElement("input");
    input.className = 'expression-container-input';
    this._select2Input = input;

    span.appendChild(dvContainer);
    span.className = 'expression-container';
    this.html = span;
    this.dvContainer = dvContainer;

    this.updateExpressionView();
    this.attachListeners();

    return this.html;
};

ExpressionContainer.prototype.updateExpressionView = function () {
    var value = this.parseValue(this.expression),
        $container;

    if (this.html) {
        $container = $(this.dvContainer);
        $container.text(value);
        $container.attr('data-original-title', value);

        if (!value) {
            $(this._select2Input).select2("destroy").remove();
            $(this.html).removeClass("list-mode").append(this.dvContainer);
        }
    }

    return this;
};

ExpressionContainer.prototype.parseValue = function (expression) {
    var val = '';
    if (expression && expression.length) {
        table = this.parent.parent.parent
        for (i = 0; i < expression.length; i += 1) {
            if (val !== '') {
                val += ' ';
            }
            val += table.globalCBControl.getLabel(expression[i]);
        }
    }
    return val;
};

ExpressionContainer.prototype.attachListeners = function () {
    var self = this;

    if(!this.html) {
        return this;
    }

    //Define Tooltip when ellipsis overflow is active
    $(this.dvContainer).on('mouseenter', function () {
        if (this.offsetWidth < this.scrollWidth) {
            this.tooltipHandler = $(this).tooltip({trigger:'manual'});
            this.tooltipHandler.tooltip('show');
        }
    }).on("mouseleave", function() {
        if (this.tooltipHandler) {
            this.tooltipHandler.tooltip('destroy');
            this.tooltipHandler = null;
        }
    });

    //Define click events to handle CriteriaBuilderControl
    $(this.html).on('click', function () {
        self.handleClick(this);
    });

    return this;
};

ExpressionContainer.prototype.handleClick = function (element) {
    var globalParent,
        parentVariable;

    globalParent = this.parent.parent.parent;
    parentVariable = this.parent.parent;

    if (parentVariable.fieldType || parentVariable.isReturnType) {
        switch (parentVariable.fieldType) {
            case "DropDown":
            case "Checkbox":
            case 'Radio':
                this.handleDropDownBuilder(globalParent, parentVariable, element);
                break;
            case "user":
                this.handleUserList(globalParent, parentVariable, element);
                break;
            default:
                this.handleCriteriaBuilder(globalParent, parentVariable, element);
        }
    } else {
        App.alert.show('expression-variable-click', {
            level: 'warning',
            messages: translate('LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE', 'pmse_Business_Rules'),
            autoClose: true
        });
    }
};

ExpressionContainer.prototype.handleCriteriaBuilder = function (globalParent, parentVariable, element) {
    var self = this,
        value,
        defaults = {
            operators: false,
            evaluations: false,
            variables: false,
            constants: false
        },
        config = {};

    if (globalParent.globalCBControl.isOpen()) {
        globalParent.globalCBControl.close();
        //this.setIsCBOpen(false);
    } else {
        globalParent.globalCBControl.setOwner(element);
        globalParent.globalCBControl.setOnChangeHandler(function (expressionControl, newValue, oldValue) {
            value = JSON.parse(newValue);
            self.setExpressionValue(value);
            if (typeof self.onChange === 'function') {
                self.onChange(newValue, oldValue);
            }
        });
        if (parentVariable.isReturnType) {
            config = {
                constants: true,
                variables: {
                    dataRoot: null,
                    data: parentVariable.inputFields,
                    dataFormat: "tabular",
                    textField: "label",
                    moduleTextField: "moduleText",
                    moduleValueField: "moduleValue"
                }
            };
        } else {
            if(this.unsupportedDataTypes.indexOf(parentVariable.fieldType) >= 0) {
                App.alert.show('expression-variable-unsupported-data-type', {
                    level: 'warning',
                    messages: translate('LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE', 'pmse_Business_Rules'),
                    autoClose: true
                });
                return;
            }
            switch (parentVariable.fieldType) {
                case 'Date':
                case 'Datetime':
                    config = {
                        operators: {
                            arithmetic: ["+","-"]
                        },
                        constants: {
                            date: parentVariable.fieldType === 'Date',
                            datetime: parentVariable.fieldType === 'Datetime',
                            timespan: parentVariable.fieldType === 'Datetime',
                            datespan: parentVariable.fieldType === 'Date'
                        },
                        variables: {
                            dataRoot: null,
                            data: parentVariable.inputFields,
                            dataFormat: "tabular",
                            textField: "label",
                            typeFilter: parentVariable.fieldType,
                            moduleTextField: "moduleText",
                            moduleValueField: "moduleValue"
                        }
                    };
                    break;
                case 'TextArea':
                case 'TextField':
                case 'email':
                case 'Phone':
                case 'URL':
                    $.extend(true, config, {
                        constants: {
                            basic: {
                                string: true
                            }
                        },
                        variables: {
                            dataRoot: null,
                            data: parentVariable.inputFields,
                            dataFormat: "tabular",
                            textField: "label",
                            typeFilter: parentVariable.fieldType,
                            moduleTextField: "moduleText",
                            moduleValueField: "moduleValue"
                        }
                    });
                    if (parentVariable.variableMode === 'conclusion' && !parentVariable.isReturnType
                        && parentVariable.fieldType === 'email') {
                        config.variables.typeFilter = function (type, data) {
                            if (parentVariable.fieldType !== type) {
                                return false;
                            }
                            return data.value !== 'email1'
                        };
                    }
                    break;
                case 'Currency':
                    $.extend(true, config, {
                        constants: {
                            currency: true
                        }
                    })
                case 'Integer':
                    $.extend(true, config, {
                        operators: {
                            arithmetic: true,
                            group: true
                        },
                        constants: {
                            basic: {
                                number: true
                            }
                        },
                        variables: {
                            dataRoot: null,
                            data: parentVariable.inputFields,
                            dataFormat: "tabular",
                            textField: "label",
                            typeFilter: parentVariable.fieldType,
                            moduleTextField: "moduleText",
                            moduleValueField: "moduleValue"
                        }
                    });
                    break;
                default:
                    if (parentVariable.isReturnType) {
                        $.extend(true, config, {
                            constants: {
                                basic: true,
                                date: true
                            }
                        });
                    } else {
                        $.extend(true, config, {
                            constants: {
                                basic: {
                                    string: true
                                }
                            },
                            variables: {
                                dataRoot: null,
                                data: parentVariable.inputFields,
                                dataFormat: "tabular",
                                textField: "label",
                                typeFilter: parentVariable.fieldType,
                                moduleTextField: "moduleText",
                                moduleValueField: "moduleValue"
                            }
                        });
                    }
                    break;
            }
        }

        $.extend(true, defaults, config);
        //globalParent.globalCBControl.clear();
        globalParent.globalCBControl
            .setOperators(defaults.operators)
            .setEvaluations(defaults.evaluations)
            .setVariablePanel(defaults.variables)
            .setConstantPanel(defaults.constants);
        globalParent.globalCBControl.setValue(this.expression);
        if (typeof this.onBeforeOpenPanel === 'function') {
            this.onBeforeOpenPanel(this);
        }
        globalParent.globalCBControl.open();
        //this.setIsCBOpen(true);
    }
};

ExpressionContainer.prototype.handleUserList = function (globalParent, parentVariable, element) {
    var self = this,
        $input = $(this._select2Input), $html = $(this.html);

    $(this.dvContainer).remove();
    $input.select2("destroy");
    $html.addClass("list-mode").append(this._select2Input);

    $input.on("change", function (e) {
        var prevValue = JSON.stringify(self.expression),
            data = e.added,
            value = [{
                expType: 'CONSTANT',
                expSubtype: 'string',
                expLabel: data.text,
                expValue: data.value
            }];

        self.setExpressionValue(value);

        if (typeof self.onChange === 'function') {
            self.onChange(JSON.stringify(self.expression), prevValue);
        }

    }).on("select2-open", function() {
        if (typeof self.onBeforeOpenPanel === 'function') {
            self.onBeforeOpenPanel(self);
        }
    }).select2({
        id: function (e) {
            return e == undefined ? null : e.value;
        },
        query: function (queryObject) {
            var result = {
                more: true,
                results: []
            };
            if (jQuery.trim(queryObject.term)) {
                self._searchFunction(queryObject);
            } else {
                queryObject.callback(result);
            }
        },
        formatNoMatches: function (term) {
            return (term && (term !== '')) ? translate('LBL_PA_FORM_COMBO_NO_MATCHES_FOUND') : '';
        }
    }).select2("data", {
        value: (this.expression && this.expression[0] && this.expression[0].expValue) || "",
        text: (this.expression && this.expression[0] && this.expression[0].expLabel) || ""
    }).select2("open");
    $input.attr("placeholder", translate('LBL_PA_FORM_COMBO_ASSIGN_TO_USER_HELP_TEXT', 'pmse_Project'));
    $input.data("select2").setPlaceholder();
    $('.select2-chosen').attr('align', 'left');
    return this;
};

ExpressionContainer.prototype.handleDropDownBuilder = function (globalParent, parentVariable, element) {
    var self = this, value;

    if (globalParent.globalDDSelector.isOpen()) {
        globalParent.globalDDSelector.close();
        //this.setIsDDOpen(false);
    } else {
        globalParent.globalDDSelector.setOwner(element);
        globalParent.globalDDSelector.setOnItemValueActionHandler(function (dropdownSelector, list, obj) {
            var prevValue = JSON.stringify(self.expression);
            if (Object.keys(obj).length === 0) {
                value = [];
            } else {
                value = [{
                    expType: "CONSTANT",
                    expSubtype: 'string',
                    expLabel: obj.text,
                    expValue: obj.value
                }];
            }
            self.setExpressionValue(value);
            globalParent.globalDDSelector.close();

            if (typeof self.onChange === 'function') {
                self.onChange(JSON.stringify(self.expression), prevValue);
            }
            //self.setIsDDOpen(false);
        });
        globalParent.globalDDSelector.setValues(parentVariable.combos[parentVariable.module + globalParent.moduleFieldSeparator
        + parentVariable.field]);
        globalParent.globalDDSelector.setValue(this.expression);
        if (typeof this.onBeforeOpenPanel === 'function') {
            this.onBeforeOpenPanel(this);
        }
        globalParent.globalDDSelector.open();
        //this.setIsDDOpen(true);

        var variables = $(globalParent.html).find('.decision-table-conclusion-column');
        var selectorWidth;
        if (parentVariable.variableMode == 'conclusion') {
            selectorWidth = variables.last().width();
        } else {
            selectorWidth = variables.first().width();
        }
        globalParent.globalDDSelector.setWidth(selectorWidth);
    }
};

