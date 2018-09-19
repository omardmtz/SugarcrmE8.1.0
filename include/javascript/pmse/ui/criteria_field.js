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
var CriteriaField = function (settings, parent) {
    PMSE.Field.call(this, settings, parent);
    this._panel = null;
    this._panelFlag = true;
    this._listenersAttached = false;
    this.fieldHeight = null;
    this._disabled = null;
    CriteriaField.prototype.init.call(this, settings);
};

CriteriaField.prototype = new PMSE.Field();
CriteriaField.prototype.constructor = CriteriaField;
CriteriaField.prototype.type = "CriteriaField";

CriteriaField.prototype.init = function(settings) {
    var that = this, defaults = {
        name: null,
        operators: {},
        evaluation: false,
        variable: false,
        constant: true,
        fieldHeight: 88,
        fieldWidth: 200,
        disabled: false,
        dateFormat: "YYYY-MM-DD",
        timeFormat: "H:i",
        decimalSeparator: ".",
        numberGroupingSeparator: ",",
        panelContext: document.body,
        currencies: []
    };

    jQuery.extend(true, defaults, settings);

    this.controlObject = new ItemContainer({
        className: 'adam-field-control',
        //width: this.fieldWidth || 200,
        //height: 88,
        onFocus: function () {
            that.scrollTo();
            if(!that._panel.isOpen() && !that._disabled) {
                that.openPanel();
            }
        },
        onBlur: function () {
            if (that._panelFlag) {
                that.closePanel();
            }
            that._panelFlag = true;
        }
    });

    this._panel = new ExpressionControl({
        name: defaults.name,
        parent: this,
        itemContainer: this.controlObject,
        owner: this.controlObject,
        dateFormat: defaults.dateFormat,
        timeFormat: defaults.timeFormat,
        operators: defaults.operators,
        evaluation: defaults.evaluation,
        variable: defaults.variable,
        constant: defaults.constant,
        decimalSeparator: defaults.decimalSeparator,
        numberGroupingSeparator: defaults.numberGroupingSeparator,
        currencies: defaults.currencies,
        panelContext: defaults.panelContext,
        onChange: this._onChange(),
        appendTo: function () {
            return (that.parent && that.parent.parent && that.parent.parent.html) || document.body;
        }
    });

    this.setEvaluations(defaults.evaluation)
        .setFieldWidth(defaults.fieldWidth)
        .setFieldHeight(defaults.fieldHeight)
        .setValue(defaults.value);

    if (defaults.disabled) {
        this.disable();
    } else {
        this.enable();
    }
};

CriteriaField.prototype.disable = function () {
    this._disabled = true;
    if (this.controlObject) {
        this.controlObject.disable();
    }
    jQuery(this.labelObject).addClass('adam-form-label-disabled');
    return this;
};

CriteriaField.prototype.enable = function () {
    this._disabled = false;
    if (this.controlObject) {
        this.controlObject.enable();
    }
    jQuery(this.labelObject).removeClass('adam-form-label-disabled');
    return this;
};

CriteriaField.prototype.setFieldWidth = function (width) {
    if(!isNaN(width) && this.controlObject) {
        this.controlObject.setWidth(this.fieldWidth = width);
    }
    return this;
};

CriteriaField.prototype.setFieldHeight = function (height) {
    if(!isNaN(height)) {
        this.controlObject.setHeight(this.fieldHeight = height);
    }
    return this;
};

CriteriaField.prototype.getItems = function () {
    return this.controlObject.getItems();
};

CriteriaField.prototype.setOperators = function (settings) {
    this._panel.setOperators(settings);
    return this;
};

CriteriaField.prototype.setEvaluations = function (settings) {
    this._panel.setEvaluations(settings);
    return this;
};

CriteriaField.prototype.setVariablePanel = function (settings) {
    this._panel.setVariablePanel(settings);
    return this;
};

CriteriaField.prototype.setConstantPanel = function (settings) {
    this._panel.setConstantPanel(settings);
    return this;
};

CriteriaField.prototype.setModuleEvaluation = function (currentEval) {
    this._panel.setModuleEvaluation(currentEval);
    return this;
};

CriteriaField.prototype.setFormResponseEvaluation = function (currentEval) {
    this._panel.setFormResponseEvaluation(currentEval);
    return this;
};

CriteriaField.prototype.setBusinessRuleEvaluation = function (currentEval) {
    this._panel.setBusinessRuleEvaluation(currentEval);
    return this;
};

CriteriaField.prototype.setUserEvaluation = function (currentEval) {
    this._panel.setUserEvaluation(currentEval);
    return this;
};

CriteriaField.prototype.clear = function () {
    this.controlObject.clearItems();
    return this;
};

CriteriaField.prototype._onChange = function () {
    var that = this;
    return function (panel, newValue, oldValue) {
        that.value = newValue;
        that.onChange(newValue, oldValue);
    };
};

CriteriaField.prototype.setValue = function (value) {
    if (this.controlObject) {
        PMSE.Field.prototype.setValue.call(this, value);
    }
    return this;
};

CriteriaField.prototype._setValueToControl = function (value) {
    var i;
    value = value || [];
    value = typeof value ===  'string' ? JSON.parse(value) : value;
    if (!jQuery.isArray(value)) {
        throw new Error("setValue(): The parameter is incorrectly formatted.");
    }
    for (i = 0; i < value.length; i += 1) {
        this.controlObject.addItem(this._panel._createItem(value[i]), null, true);
    }
    return this;
};

CriteriaField.prototype.closePanel = function () {
    if (this._panel.isPanelOpen()) {
        this._panel.close();
    }
    this.controlObject.style.removeClasses(['focused']);
    this._panel.style.removeClasses(['focused']);
    return this;
};

CriteriaField.prototype.openPanel = function() {
    if (!this._panel.isPanelOpen()) {
        this._panel.open();
        this.controlObject.style.addClasses(['focused']);
        this._panel.style.addClasses(['focused']);
    }
    return this;
};

CriteriaField.prototype.scrollTo = function () {
    var fieldsDiv = this.html.parentNode,
        scrollForControlObject = getRelativePosition(this.controlObject.html, fieldsDiv).top + $(this.controlObject.html).outerHeight() + fieldsDiv.scrollTop,
        that = this;
    if (fieldsDiv.scrollTop + $(fieldsDiv).outerHeight() < scrollForControlObject) {
        jQuery(this.html.parentNode).animate({
            scrollTop: scrollForControlObject
        }, function() {
            that.openPanel();
        });
        return;
    }

    return this;
};

CriteriaField.prototype.evalRequired = function () {
    var valid = true;
    if (this.required) {
        valid = !!this.controlObject.getItems().length;
        if (!valid) {
            $(this.controlObject).style.addClasses(['required']);
        } else {
            $(this.controlObject).style.removeClasses(['required']);
        }
    }

    return this;
};

CriteriaField.prototype.isValid = function () {
    var valid = this._panel.isValid();
    if (valid) {
        valid = PMSE.Field.prototype.isValid.call(this);
    }
    return valid;
};

CriteriaField.prototype._attachListeners = function() {
    var that = this;
    if (this.html && !this._listenersAttached) {
        jQuery(this._panel.getHTML()).on('mousedown', function (e) {
            e.stopPropagation();
            that._panelFlag = false;
        });
        if (this.parent) {
            $(this.parent.body).on('scroll', function () {
                that.closePanel();
            });
        }
        this._attachListeners = true;
    }
    return this;
};

CriteriaField.prototype.createHTML = function() {
    var fieldLabel, required = '', readAtt, that = this, divControlObjectContainer;
    if (!this.html) {
        PMSE.Field.prototype.createHTML.call(this);

        if (this.required) {
            required = '<i>*</i> ';
        }

        fieldLabel = this.createHTMLElement('span');
        fieldLabel.className = 'adam-form-label';
        fieldLabel.innerHTML = this.label + ': ' + required;
        fieldLabel.style.width = (this.parent && this.parent.labelWidth) || "30%";
        fieldLabel.style.verticalAlign = 'top';
        this.html.appendChild(fieldLabel);

        if (this.readOnly) {
            //TODO: implement readOnly
        }

        divControlObjectContainer = this.createHTMLElement('div');
        divControlObjectContainer.className = "control-object-container";
        this.html.appendChild(divControlObjectContainer);
        divControlObjectContainer.appendChild(this.controlObject.getHTML());

        //this.html.appendChild(this.controlObject.getHTML());

        if (this.helpTooltip) {
            this.html.appendChild(this.helpTooltip.getHTML());
        }

        this.labelObject = fieldLabel;
        this._attachListeners();
    }
    return this.html;
};