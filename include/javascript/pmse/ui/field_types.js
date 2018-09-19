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
/*global PMSE.Field, $, document, PMSE.Element*/
var PMSE = PMSE || {};
/**
 * @class TextField
 * Handle text input fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var TextField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    /**
     * Defines the maximum number of characters supported
     * @type {Number}
     */
    this.maxCharacters = null;
    TextField.prototype.initObject.call(this, options);
};
TextField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
TextField.prototype.type = 'TextField';

/**
 * Initializes the object with the default values
 * @param {Object} options
 */
TextField.prototype.initObject = function (options) {
    var defaults = {
        maxCharacters: 0,
        value: "",
        initialValue: ""
    };
    $.extend(true, defaults, options);
    this.setMaxCharacters(defaults.maxCharacters)
        .setInitialValue(defaults.initialValue)
        .setValue(defaults.value);
};

/**
 * Sets the maximun characters property
 * @param {Number} value
 * @return {*}
 */
TextField.prototype.setMaxCharacters = function (value) {
    this.maxCharacters = value;
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
TextField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('input');
    textInput.type = "text";
    textInput.id = this.name;
    textInput.value = this.value || "";
    if (this.fieldWidth) {
        textInput.style.width = this.fieldWidth;
    }
    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;
    this.controlObject = textInput;

    return this.html;
};

/**
 * Attaches event listeners to the text field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link TextField#event-change Change Input field event}
 * - {@link TextField#event-keydown key down event into an input field}
 *
 * @chainable
 */
TextField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        $(this.controlObject)
            .change(function () {
                self.setValue(this.value, true);
                self.onChange();
            })
            .keydown(function (e) {
                e.stopPropagation();
            });
    }
    return this;
};

/**
 * @class ComboboxField
 * Handles drop down fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var ComboboxField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    /**
     * Defines the combobox options
     * @type {Array}
     */
    this.options = [];
    this.related = null;
    this._isValid = true;
    ComboboxField.prototype.initObject.call(this, options);
};
ComboboxField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
ComboboxField.prototype.type = 'ComboboxField';

/**
 * Initializes the object with default values
 * @param {Object} options
 */
ComboboxField.prototype.initObject = function (options) {
    var defaults = {
        options: [],
        related: null,
        isValid: true
    };
    $.extend(true, defaults, options);
    this.setOptions(defaults.options)
        .setRelated(defaults.related)
        .setValid(defaults.isValid);
};

/**
 * Sets the combo box options
 * @param {Array} data
 * @return {*}
 */
ComboboxField.prototype.setOptions = function (data) {
    var i;
    this.options = data;
    if (this.html) {
        for (i = 0; i < this.options.length; i += 1) {
            this.controlObject.appendChild(this.generateOption(this.options[i]));
        }

        if (!this.value) {
            this.value = this.controlObject.value;
        }
    }
    return this;

};

/**
 * Adds a single option to the dropdown
 * @param data
 * @return {ComboboxField}
 */
ComboboxField.prototype.addOption = function(data) {
    if ((this.html) && (data)) {
        this.controlObject.appendChild(this.generateOption(data));
    }
    if (!this.value) {
        this.value = this.controlObject.value;
    }
    return this;
};

ComboboxField.prototype.setRelated = function (data) {
    this.related = data;
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
ComboboxField.prototype.createHTML = function () {
    var fieldLabel, selectInput, required = '', opt, i, disableAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    this.html.appendChild(fieldLabel);

    selectInput = this.createHTMLElement('select');
    selectInput.id = this.name;
    for (i = 0; i < this.options.length; i += 1) {
        selectInput.appendChild(this.generateOption(this.options[i]));
    }
    if (!this.value) {
        this.value = selectInput.value;
    }
    if (this.fieldWidth) {
        selectInput.style.width = this.fieldWidth;
    }
    if (this.readOnly) {
        disableAtt = document.createAttribute('disabled');
        selectInput.setAttributeNode(disableAtt);
    }
    this.html.appendChild(selectInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;
    this.controlObject = selectInput;

    if (this.disabled) {
        this.disable();
    } else if (!this.readOnly) {
        this.enable();
    }

    return this.html;
};

ComboboxField.prototype.removeOptions = function () {
    if (this.options) {
        while (this.controlObject.firstChild) {
            this.controlObject.removeChild(this.controlObject.firstChild);
        }
        this.options = [];
    }
    return this;
};


ComboboxField.prototype.generateOption = function (item) {
    var out, selected = '', value, text;
    out = this.createHTMLElement('option');
    if (typeof item === 'object') {
        value = item.value;
        text = item.text;
    } else {
        value = item;
    }
    out.selected = this.value === value;
    out.value = value;
    out.label = text || value;
    out.appendChild(document.createTextNode(text || value));
    return out;
};

/**
 * Returns the data associated to the current selected value.
 * @return {Object|null}
 */
ComboboxField.prototype.getSelectedData = function () {
    return _.find(this.options, function (item) {
        return item.value == this.value;
    }, this) || null;
};

/**
 * Attaches event listeners to the combo box field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link TextField#event-change Change Input field event}
 *
 * @chainable
 */
ComboboxField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        $(this.controlObject)
            .change(function (e) {
                var oldValue = self.value;
                self.setValue(this.value, true);
                self.onChange(this.value, oldValue);
            });
    }
    return this;
};

ComboboxField.prototype.isValid = function() {
    return this._isValid;
};

ComboboxField.prototype.setValid = function(valid) {
    this._isValid = valid ? true : false;
    this.decorateValid();
    return this;
};

ComboboxField.prototype.decorateValid = function() {
    $(this.controlObject).toggleClass(this._invalidFieldClass, !this.isValid());
};

/**
 * @class TextareaField
 * Handles TextArea fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var TextareaField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.fieldHeight = null;
    TextareaField.prototype.initObject.call(this, options);
};
TextareaField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
TextareaField.prototype.type = "TextareaField";

TextareaField.prototype.initObject = function (options) {
    var defaults = {
        fieldHeight: null,
        value: "",
        initialValue: ""
    };
    $.extend(true, defaults, options);
    this.setFieldHeight(defaults.fieldHeight)
        .setInitialValue(defaults.initialValue)
        .setValue(defaults.value);
};

TextareaField.prototype.setFieldHeight = function (height) {
    this.fieldHeight = height;
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
TextareaField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    fieldLabel.style.verticalAlign = 'top';
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('textarea');
    textInput.id = this.name;
    textInput.value = this.value;
    if (this.fieldWidth) {
        textInput.style.width = this.fieldWidth;
    }
    if (this.fieldHeight) {
        textInput.style.height = this.fieldHeight;
    }
    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }

    this.controlObject = textInput;
    this.labelObject = fieldLabel;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to the text area , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link TextareaField#event-change Change Input field event}
 * - {@link TextareaField#event-keydown key down event into an input field}
 *
 * @chainable
 */

TextareaField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        $(this.controlObject)
            .change(function () {
                self.setValue(this.value, true);
                self.onChange();
            })
            .keydown(function (e) {
                e.stopPropagation();
            });
    }
    return this;
};
//

/**
 * @class CheckboxField
 * Handles the checkbox fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var CheckboxField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.defaults = {
        //options: {},
        onClick: function (e, ui) {}
    };
    $.extend(true, this.defaults, options);
};

CheckboxField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
CheckboxField.prototype.type = 'CheckboxField';

/**
 * Creates the HTML Element of the field
 */
CheckboxField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
//    fieldLabel.style.verticalAlign = 'top';
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('input');
    textInput.id = this.name;
    textInput.type = 'checkbox';
    if (this.value) {
        textInput.checked = true;
    } else {
        textInput.checked = false;
    }
    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;
    this.controlObject = textInput;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to checkbox field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link CheckboxField#event-onClick on click mouse event}
 * - {@link CheckboxField#event-change Change Input field event}
 * - {@link CheckboxField#event-keydown key down event into an input field}
 *
 * @chainable
 */
CheckboxField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        if (typeof this.defaults.onClick !== 'undefined' && typeof this.defaults.onClick === 'function') {
            $(this.controlObject).on('click', function (e, ui) {return self.defaults.onClick(); });
        }

        $(this.controlObject)
            .change(function (a, b) {
                var val;
                if (this.checked) {
                    val = true;
                } else {
                    val = false;
                }
                self.setValue(val, true);
                self.onChange();
            });
    }
    return this;
};

CheckboxField.prototype.getObjectValue = function () {
    var response = {};
    if (this.value) {
        response[this.name] = true;
    } else {
        response[this.name] = false;
    }
    return response;
};

CheckboxField.prototype.evalRequired = function () {
    var response = true;
    if (this.required) {
        response = this.value;
        this.markFieldError(!response);
    }
    return response;
};
/**
 * @class RadiobuttonField
 * Handles the radio button fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var RadiobuttonField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.defaults = {
        options: {},
        onClick: function (e, ui) {}
    };
    $.extend(true, this.defaults, options);
    //RadiobuttonField.prototype.initObject.call(this, options);
};
RadiobuttonField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
RadiobuttonField.prototype.type = 'RadiobuttonField';

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
RadiobuttonField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }
//    console.log(this.defaults);
    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';

    textInput = this.createHTMLElement('input');
    textInput.name = this.name;
    textInput.type = 'radio';
    textInput.value = this.value;

    if (typeof (this.defaults.labelAlign) === 'undefined' ||
            this.defaults.labelAlign === 'left') {
        fieldLabel.style.width = this.parent.labelWidth;
        fieldLabel.innerHTML = this.label + ': ' + required;
        fieldLabel.style.verticalAlign = 'top';
        fieldLabel.style.width = this.parent.labelWidth;
        this.html.appendChild(fieldLabel);
        this.html.appendChild(textInput);
    } else if (this.defaults.labelAlign === 'right') {
        fieldLabel.innerHTML = '&nbsp;' + this.label + required;
        textInput.style.marginLeft = (this.defaults.marginLeft) ? this.defaults.marginLeft + 'px' : '0px';
        fieldLabel.style.width = this.parent.labelWidth;
        this.html.appendChild(textInput);
        this.html.appendChild(fieldLabel);
    }

    if (this.value) {
        textInput.checked = true;
    } else {
        textInput.checked = false;
    }

    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }

    this.controlObject = textInput;
    this.labelObject = fieldLabel;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to radio field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link RadiobuttonField#event-onClick on click mouse event}
 * - {@link RadiobuttonField#event-change Change Input field event}
 *
 * @chainable
 */
RadiobuttonField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        if (typeof this.defaults.onClick !== 'undefined' && typeof this.defaults.onClick === 'function') {
            $(this.controlObject).on('click', function (e, ui) {return self.defaults.onClick(); });
        }
        $(this.controlObject)
            .change(function (a, b) {
                self.onChange();
            });
//        $(this.controlObject)
//            .change(function (a, b) {
//                var val;
//                if (this.checked) {
//                    val = true;
//                } else {
//                    val = false;
//                }
//                self.setValue(val, true);
//                self.onChange();
//            });
    }
    return this;
};

RadiobuttonField.prototype.getObjectValue = function () {
    return this.value;
};

RadiobuttonField.prototype.evalRequired = function () {
    var response = true;
    if (this.required) {
        response = this.value;
        this.markFieldError(!response);
    }
    return response;
};

RadiobuttonField.prototype._setValueToControl = function (value) {
    if (this.html && this.controlObject) {
        this.controlObject.checked = this.value;
    }
    return this;
};

/**
 * @class LabelField
 * Handles the Label fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var LabelField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.submit = false;
    this.defaults = {
        options: {
            marginLeft : 10
        }
    };
    $.extend(true, this.defaults, options);
};
LabelField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
LabelField.prototype.type = 'LabelField';

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
LabelField.prototype.createHTML = function () {
    var fieldLabel;
    PMSE.Field.prototype.createHTML.call(this);

    fieldLabel = this.createHTMLElement('span');
//    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ':';
    fieldLabel.style.verticalAlign = 'top';
    fieldLabel.style.marginLeft = this.defaults.options.marginLeft + 'px';
    this.html.appendChild(fieldLabel);

    return this.html;
};

/**
 * @class HiddenField
 * Handle the hidden fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var HiddenField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
};
HiddenField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
HiddenField.prototype.type = 'HiddenField';

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
HiddenField.prototype.createHTML = function () {
    PMSE.Element.prototype.createHTML.call(this);
    return this.html;
};

//

var EmailGroupField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
};

EmailGroupField.prototype = new PMSE.Field();

EmailGroupField.prototype.type = 'EmailGroupField';

/**
 * @class DateField
 * Handle text input fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var DateField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    /**
     * Defines the maximum number of characters supported
     * @type {Number}
     */
    this.maxCharacters = null;
    DateField.prototype.initObject.call(this, options);
};
DateField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
DateField.prototype.type = 'TextField';

/**
 * Initializes the object with the default values
 * @param {Object} options
 */
DateField.prototype.initObject = function (options) {
    var defaults = {
        maxCharacters: 0
    };
    $.extend(true, defaults, options);
    this.setMaxCharacters(defaults.maxCharacters);
};

/**
 * Sets the maximun characters property
 * @param {Number} value
 * @return {*}
 */
DateField.prototype.setMaxCharacters = function (value) {
    this.maxCharacters = value;
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
DateField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('input');
    textInput.id = this.name;
    textInput.value = this.value || "";
    $(textInput).datepicker();
    if (this.fieldWidth) {
        textInput.style.width = this.fieldWidth;
    }
    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;
    this.controlObject = textInput;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to date field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link TextareaField#event-change Change Input field event}
 * - {@link TextareaField#event-keydown key down event into an input field}
 *
 * @chainable
 */
DateField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        $(this.controlObject)
            .change(function () {
                self.setValue(this.value, true);
                self.onChange();
            })
            .keydown(function (e) {
                e.stopPropagation();
            });
    }
    return this;
};
DateField.prototype.disable = function () {
    PMSE.Field.prototype.disable.call(this);
    $(this.controlObject).datepicker('hide');
    return this;
};

/**
 * @class NumberField
 * Handle text input fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var NumberField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    /**
     * Defines the maximum number of characters supported
     * @type {Number}
     */
    this.maxCharacters = null;
    NumberField.prototype.initObject.call(this, options);
};
NumberField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
NumberField.prototype.type = 'TextField';

/**
 * Initializes the object with the default values
 * @param {Object} options
 */
NumberField.prototype.initObject = function (options) {
    var defaults = {
        maxCharacters: 0
    };
    $.extend(true, defaults, options);
    this.setMaxCharacters(defaults.maxCharacters);
};

/**
 * Sets the maximun characters property
 * @param {Number} value
 * @return {*}
 */
NumberField.prototype.setMaxCharacters = function (value) {
    this.maxCharacters = value;
    return this;
};

/**
 * Creates the basic html node structure for the given object using its
 * previously defined properties
 * @return {HTMLElement}
 */
NumberField.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('input');
    textInput.id = this.name;
    textInput.value = this.value || "";
    if (this.fieldWidth) {
        textInput.style.width = this.fieldWidth;
    }
    if (this.readOnly) {
        readAtt = document.createAttribute('readonly');
        textInput.setAttributeNode(readAtt);
    }
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;
    this.controlObject = textInput;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to the text field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link TextField#event-change Change Input field event}
 * - {@link TextField#event-keydown key down event into an input field}
 *
 * @chainable
 */
NumberField.prototype.attachListeners = function () {
    var self = this;
    if (this.controlObject) {
        $(this.controlObject)
            .change(function () {
                self.setValue(this.value, true);
                self.onChange();
            });
    }
    return this;
};

/**
 * Validates a number field
 * @return {boolean}
 */
NumberField.prototype.isValid = function() {
    // Start with a true return
    var valid = true;
    // If this field is required, check whether it is numeric
    if (this.required && !$.isNumeric(this.value)) {
        // If not, mark it invalid and mark a field error
        valid = false;
        this.markFieldError(!valid);
    }
    return valid;
};

/**
 * @class CheckboxGroup
 * Handles the checkbox fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var CheckboxGroup = function (options, parent) {
    PMSE.Field.call(this, options, parent);
//    this.defaults = {
//        options: {},
//        onClick: function (e, ui) {}
//    };
//    $.extend(true, this.defaults, options);
    this.controlObject = {};
    CheckboxGroup.prototype.initObject.call(this, options);
};

CheckboxGroup.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
CheckboxGroup.prototype.type = 'CheckboxGroup';

/**
 * Initializes the object with the default values
 * @param {Object} options
 */
CheckboxGroup.prototype.initObject = function (options) {
    var defaults = {
        items: []
    };
    $.extend(true, defaults, options);
    //this.setMaxCharacters(defaults.maxCharacters);
    this.items = defaults.items;
};

/**
 * Creates the HTML Element of the field
 */
CheckboxGroup.prototype.createHTML = function () {
    var fieldLabel, input, required = '', readAtt, div, i, text, ul, li, root = this, object;
    //this.controlObject.control = [];
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }
    div = this.createHTMLElement('div');
    div.style.display = 'inline-block';
    div.style.width = "30%";
    div.style.verticalAlign = 'top';
    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required + '&nbsp;&nbsp;&nbsp;&nbsp;';
    fieldLabel.style.width = this.parent.labelWidth;
//    fieldLabel.style.verticalAlign = 'top';
    div.appendChild(fieldLabel);
    this.html.appendChild(div);


    div = this.createHTMLElement('div');
    div.style.display = 'inline-block';
    div.style.width = "40%";
    ul =  this.createHTMLElement('ul');

    for (i = 0; i < this.items.length; i += 1) {
        li = this.createHTMLElement('li');
        li.style.listStyleType = 'none';
        input = this.createHTMLElement('input');
        input.id = this.items[i].value;
        input.type = 'checkbox';
        if (this.items[i].checked) {
            input.checked = true;
        } else {
            input.checked = false;
        }
        if (this.readOnly) {
            readAtt = document.createAttribute('readonly');
            input.setAttributeNode(readAtt);
        }
        li.appendChild(input);

        object = {'control': input};
        if (this.items[i].checked) {
            object.checked = true;
        }
        this.controlObject[this.items[i].value] = object;
//        <label for="male">Male</label>
        text = document.createElement("Label");
        text.innerHTML = ' &nbsp;&nbsp;' + this.items[i].text;
        li.appendChild(text);

        ul.appendChild(li);

        $(input).change(function () {
            if (this.checked) {
                //control.checked = true;
                root.controlObject[$(this).attr('id')].checked = true;
            } else {
                //control.checked = false;
                root.controlObject[$(this).attr('id')].checked = false;
            }
        });
    }
    div.appendChild(ul);
    this.html.appendChild(div);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};

/**
 * Attaches event listeners to checkbox field , it also call some methods to set and evaluate
 * the current value (to send it to the database later).
 *
 * The events attached to this field are:
 *
 * - {@link CheckboxField#event-onClick on click mouse event}
 * - {@link CheckboxField#event-change Change Input field event}
 * - {@link CheckboxField#event-keydown key down event into an input field}
 *
 * @chainable
 */
CheckboxGroup.prototype.attachListeners = function () {
    var self = this, i, control;
//    if (this.controlObject) {
//        if (typeof this.defaults.onClick !== 'undefined' && typeof this.defaults.onClick === 'function') {
//            $(this.controlObject).on('click', function (e, ui) {return self.defaults.onClick(); });
//        }
//
//        $(this.controlObject)
//            .change(function (a, b) {
//                var val;
//                if (this.checked) {
//                    val = true;
//                } else {
//                    val = false;
//                }
//                self.setValue(val, true);
//                self.onChange();
//            });
//    }
//    for (i = 0; i < this.controlObject.length; i += 1) {
//    }
    return this;
};

CheckboxGroup.prototype.getObjectValue = function () {
    var response = {}, i, control, array = [];
    $.each(this.controlObject, function (key, value) {
        //console.log(key);
        if (value.checked) {
            array.push(key);
        }
    });

    response[this.name] = array;
    return response;
};

CheckboxGroup.prototype.evalRequired = function () { var response = true;
    if (this.required) {
        response = this.value;
        this.markFieldError(!response);
    }
    return response;
};

var SearchableCombobox = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this._placeholder = null;
    this._pageSize = null;
    this._valueField = null;
    this._textField = null;
    this._options = [];
    this._searchURL = null;
    this._searchValue = null;
    this._searchLabel = null;
    this._searchFunction = null;
    this._searchDelay = null;
    this._searchMore = null;
    this._searchMoreList = null;
    this._isValid = true;
    SearchableCombobox.prototype.initObject.call(this, options, parent);
};

SearchableCombobox.prototype = new PMSE.Field();
SearchableCombobox.prototype.constructor = SearchableCombobox;
SearchableCombobox.prototype.type = "SearchableCombobox";

SearchableCombobox.prototype.initObject = function (options, parent) {
    var defaults = {
        placeholder: "",
        pageSize: 5,
        textField: "text",
        valueField: "value",
        fieldWidth: "200px",
        options: [],
        searchURL: null,
        searchLabel: "text",
        searchValue: "value",
        searchDelay: 1500,
        searchMore: false,
        isValid: true
    };

    $.extend(true, defaults, options);

    this._placeholder = defaults.placeholder;
    this._textField = defaults.textField;
    this._valueField = defaults.valueField;
    this._pageSize = typeof defaults.pageSize === 'number' && defaults.pageSize >= 1 ? Math.floor(defaults.pageSize) : 0;
    this.setFieldWidth(defaults.fieldWidth)
        .setSearchDelay(defaults.searchDelay)
        .setSearchValue(defaults.searchValue)
        .setSearchLabel(defaults.searchLabel)
        .setSearchURL(defaults.searchURL)
        .setOptions(defaults.options)
        .setValid(defaults.isValid);

    if (defaults.searchMore) {
        this.enableSearchMore(defaults.searchMore);
    } else {
        this.disableSearchMore();
    }
};

SearchableCombobox.prototype.isValid = function() {
    return this.disabled || this._isValid;
};

SearchableCombobox.prototype.setValid = function(valid) {
    this._isValid = valid ? true : false;
    this.decorateValid();
    return this;
};

SearchableCombobox.prototype.decorateValid = function() {
    $(this.controlObject).toggleClass(this._invalidFieldClass, !this.isValid());
};

SearchableCombobox.prototype._createSearchMoreOption = function () {
    var dropdownHTML, additionalList, listItem, tpl;
    if (this.controlObject && ! this._searchMoreList) {
        dropdownHTML = this.controlObject.data("select2").dropdown;
        additionalList = this.createHTMLElement('ul');
        additionalList.className = 'select2-results adam-searchmore-list';
        listItem = this.createHTMLElement('li');
        tpl = this.createHTMLElement('div');
        tpl.className = 'select2-result-label';
        tpl.appendChild(document.createTextNode(translate('LBL_SEARCH_AND_SELECT_ELLIPSIS')));
        listItem.appendChild(tpl);
        additionalList.appendChild(listItem);
        dropdownHTML.append(additionalList);
        this._searchMoreList = additionalList;
    }
    return this;
};

SearchableCombobox.prototype.enableSearchMore = function (options) {
    if (typeof options !== 'object') {
        throw new Error("enableSearchMore(): The parameter must be an object.");
    }
    this._searchMore = options;
    if (this.controlObject) {
        this._createSearchMoreOption();
        this._searchMoreList.style.display = '';
    }
    return this;
};

SearchableCombobox.prototype.disableSearchMore = function () {
    this._searchMore = false;
    if (this.controlObject) {
        this._createSearchMoreOption();
        this._searchMoreList.style.display = 'none';
    }
    return this;
};

SearchableCombobox.prototype.setSearchDelay = function (delay) {
    if (typeof delay !== 'number') {
        throw new Error("setSearchDelay(): The parameter must be a number.");
    }

    this._searchDelay = delay;

    return this;
};

SearchableCombobox.prototype.setSearchValue = function(value) {
    if (!(typeof value === 'string' || typeof value === 'function' || value === null)) {
        throw new Error("setSearchValue(): The parameter must be a string or a function or null.");
    }
    this._searchValue = value;
    return this;
};

SearchableCombobox.prototype.setSearchLabel = function(label) {
    if (!(typeof label === 'string' || typeof label === 'function' || label === null)) {
        throw new Error("setSearchLabel(): The parameter must be a string or a function or null.")
    }
    this._searchLabel = label;
    return this;
};

SearchableCombobox.prototype._getFilteredOptions = function (queryObject, items, textField, valueField) {
    var finalData = [],
        term = jQuery.trim(queryObject.term);

    items.forEach(function(item, index, arr) {
        if (!term || queryObject.matcher(term, item[textField])) {
            finalData.push({
                id: item[valueField],
                text: item[textField]
            });
        }
    });

    return finalData;
};

SearchableCombobox.prototype._resizeListSize = function () {
    var list = this.controlObject.data("select2").dropdown,
        listItemHeight;
    list = $(list).find('ul[role=listbox]');
    listItemHeight = list.find('li').eq(0).outerHeight();
    list.get(0).style.maxHeight = (listItemHeight * this._pageSize) + 'px';
    return this;
};

SearchableCombobox.prototype.setSearchURL = function(url) {
    var delayToUse, that = this;

    if (!(typeof url === 'string' || url === null)) {
        throw new Error("setSearchURL(): The parameter must be a string or null.");
    }
    if (url !== null && (!this._searchLabel || !this._searchValue)) {
        throw new Error("setSearchURL(): You can't set the Suggestions URL if the Suggestions Label or "
        + "Suggestions Value are set to null.");
    }
    this._searchURL = url;

    delayToUse = url ? this._searchDelay : 0;

    this._searchFunction = _.debounce(function(queryObject) {
        var proxy = new SugarProxy(),
            result = {
                more: false
            }, term = jQuery.trim(queryObject.term),
            finalData,
            getText = function(obj, criteria) {
                if (typeof criteria === 'function') {
                    return criteria(obj);
                } else {
                    return obj[criteria];
                }
            };

        finalData = queryObject.page > 1 ? [] : that._getFilteredOptions(queryObject, that._options, 'text', 'id');

        if (term && that._searchURL) {
            proxy.url = this._searchURL.replace(/\{%TERM%\}/g, queryObject.term)
                .replace(/\{%OFFSET%\}/g, (queryObject.page - 1) * that._pageSize);

            if (that._pageSize > 0) {
                proxy.url = proxy.url.replace(/\{%PAGESIZE%\}/g, that._pageSize);
            }

            proxy.getData(null, {
                success: function (data) {
                    result.more = data.next_offset >= 0 ? true : false;
                    data = data.records;
                    data.forEach(function (item) {
                        finalData.push({
                            id: getText(item, that._searchValue),
                            text: getText(item, that._searchLabel)
                        });
                    });

                    result.results = finalData;
                    queryObject.callback(result);
                    that._resizeListSize();
                },
                error: function () {
                    console.log("failure", arguments);
                }
            });
        } else {
            result.results = finalData;
            queryObject.callback(result);
        }
    }, delayToUse);

    return this;
};

SearchableCombobox.prototype.setOptions = function (options) {
    var that = this;
    if (!$.isArray(options)) {
        throw new Error("setOptions(): The parameter must be an array.");
    }

    this._options = [];

    options.forEach(function(item, index, arr) {
        that._options.push({
            id: item.value,
            text: item.text
        });
    });
    return this;
};

SearchableCombobox.prototype._setValueToControl = function (value) {
    if (this.html && this.controlObject) {
        this.controlObject.select2("val", value);
    }
    return this;
};

SearchableCombobox.prototype.setValue = function (value, change) {
    var theValue, theText;

    if (value && typeof value === 'object') {
        theText = value[this._textField];
        value = value[this._valueField];
    }

    theValue = change ? value : value || this.initialValue;

    if (this.controlObject) {
        this.controlObject.data("text", theText);
        this.controlObject.select2("val", theValue, false);
        this.value = this.controlObject.select2("val");
    } else {
        this.value = theValue;
    }
    return this;
};

SearchableCombobox.prototype.setReadOnly = function (value) {
    this.readOnly = value;
    if (this.html) {
        this.controlObject.select2("readonly", value);
    }
    return this;
};

SearchableCombobox.prototype.disable = function () {
    if (this.controlObject) {
        this.labelObject.className = 'adam-form-label-disabled';
        this.controlObject.select2("disable");
    }
    if (!this.oldRequiredValue) {
        this.oldRequiredValue = this.required;
    }
    this.setRequired(false);
    this.disabled = true;
    this.decorateValid();
    return this;
};

SearchableCombobox.prototype.enable = function () {
    if (this.controlObject) {
        this.labelObject.className = 'adam-form-label';
        this.controlObject.select2("enable");
    }
    if (this.oldRequiredValue) {
        this.setRequired(this.oldRequiredValue);
    }
    this.disabled = false;
    this.decorateValid();
    return this;
};

SearchableCombobox.prototype._queryFunction = function () {
    var that = this;
    return function (queryObject) {
        var result = {
            more: false
        }, finalData;
        if (jQuery.trim(queryObject.term)) {
            that._searchFunction(queryObject);
        } else {
            finalData = that._getFilteredOptions(queryObject, that._options, 'text', 'id');
            result.results = finalData;
            queryObject.callback(result);
        }
    };
};

SearchableCombobox.prototype._initSelection = function () {
    var that = this;
    return function ($el, callback) {
        var value = $el.val(), i, text = $el.data("text");
        for (i = 0; i < that._options.length; i += 1) {
            if (that._options[i]["id"] === value) {
                callback({
                    id: that._options[i]["id"],
                    text: that._options[i]["text"]
                });
                return;
            }
        }
        callback({
            id: value,
            text: text || value
        });
    };
};

SearchableCombobox.prototype.getSelectedText = function () {
    var data = {};

    if (this.controlObject) {
        data = $(this.controlObject).select2("data");
    }
    return data.text || "";
};

SearchableCombobox.prototype._openSearchMore = function() {
    var that = this, zIndex = $(that.html).closest(".adam-modal").zIndex();
    return function () {
        that.controlObject.select2("close");
        $(that.html).closest(".adam-modal").zIndex(-1);
        App.drawer.open({
                layout: "selection-list",
                context: that._searchMore
            },
            _.bind(function (drawerValues) {
                $(that.html).closest(".adam-modal").zIndex(zIndex);
            if (!_.isUndefined(drawerValues)) {
                that.setValue({text: drawerValues.value, value: drawerValues.id}, true);
                that.onChange();
            }
        }, this));
    };
};

SearchableCombobox.prototype.attachListeners = function () {
    var that = this;
    if (this.controlObject) {
        $(this._searchMoreList).find('li').on('mousedown', this._openSearchMore());
        this.controlObject.on("change", function () {
            that.value = that.controlObject.select2("val");
            that.onChange();
        });
    }
    return this;
};

SearchableCombobox.prototype.createHTML = function () {
    var fieldLabel, textInput, required = '', readAtt;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = this.label + ': ' + required;
    fieldLabel.style.width = this.parent.labelWidth;
    this.html.appendChild(fieldLabel);

    textInput = this.createHTMLElement('input');
    textInput.type = "text";
    textInput.id = this.name;
    this.controlObject = $(textInput);
    this.controlObject.select2({
        placeholder: this._placeholder,
        query: this._queryFunction(),
        initSelection: this._initSelection(),
        width: this.fieldWidth || "200px",
        formatNoMatches: function (term) {
            return (term && (term !== '')) ? translate('LBL_PA_FORM_COMBO_NO_MATCHES_FOUND') : '';
        }
    });
    if (this._searchMore) {
        this.enableSearchMore(this._searchMore);
    } else {
        this.disableSearchMore();
    }
    this.controlObject.select2("val", this.value, false);
    if (this.readOnly) {
        this.setReadOnly(true);
    }
    this.html.appendChild(this.controlObject.data("select2").container[0]);
    this.html.appendChild(textInput);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }
    this.labelObject = fieldLabel;

    if (this.disabled) {
        this.disable();
    } else {
        this.enable();
    }

    return this.html;
};
