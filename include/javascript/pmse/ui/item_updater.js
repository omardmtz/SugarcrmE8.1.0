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
var ItemUpdaterField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.fields = [];
    this.options = [];
    this.fieldHeight = null;
    this.visualObject = null;
    this.language = {};
    ItemUpdaterField.prototype.initObject.call(this, options);
};

ItemUpdaterField.prototype = new PMSE.Field();
ItemUpdaterField.prototype.type = 'ItemUpdaterField';

ItemUpdaterField.prototype.initObject = function (options){
    var defaults = {
        fields: [],
        fieldHeight: null,
        language: {
            LBL_ERROR_ON_FIELDS: 'Please, correct the fields with errors'
        }
    };
    $.extend(true, defaults, options);
    this.language = defaults.language;
    this.setFields(defaults.fields)
        .setFieldHeight(defaults.fieldHeight);
};

ItemUpdaterField.prototype.setFields = function (items) {
    var i, aItems = [], newItem;
    for (i = 0; i < items.length; i += 1) {
        if (items[i].type === 'FieldUpdater') {
            items[i].setParent(this);
            aItems.push(items[i]);
        } else {
            newItem = new FieldUpdater(item[i], this);
            aItems.push(newItem);
        }
    }
    this.fields = aItems;
    return this;
};

ItemUpdaterField.prototype.setFieldHeight = function (value) {
    this.fieldHeight = value;
    return this;
};

ItemUpdaterField.prototype.getObjectValue = function () {
    var f, auxValue = [];
    this.convertOptionsToFields();
    for (f = 0; f < this.fields.length; f += 1) {
        auxValue.push(this.fields[f].getJSONObject());
    }
    this.value = JSON.stringify(auxValue);
    return PMSE.Field.prototype.getObjectValue.call(this);
};

ItemUpdaterField.prototype.getJsonValue = function () {
    var index;
    var jsonFields = [];
    for (index = 0; index < this.options.length; index++) {
        if (this.options && this.options[index].active) {
            field = new FieldUpdater(this.options[index], this);
            jsonFields.push(field.getJSONObject());
        }
    }
    return JSON.stringify(jsonFields);
};

ItemUpdaterField.prototype.convertOptionsToFields = function () {
    var fields = [], i;
    for (i = 0; i < this.options.length; i += 1) {
        if (this.options && this.options[i].active) {
            fields.push(new FieldUpdater(this.options[i], this));
        }
    }
    this.fields = fields;
    return this;
};

ItemUpdaterField.prototype.setOptions = function (data) {
    var i, options = [], newOption, messageMap;
    if (data) {
        for (i = 0; i < data.length; i += 1) {
            if (data[i].type.toLowerCase() !== 'id') {
                if (data[i].type === 'FieldOption') {
                    newOption = data[i];
                } else {
                    newOption =  new FieldOption({
                        fieldId   : data[i].value,
                        fieldName : data[i].text,
                        fieldType : data[i].type.toLowerCase(),
                        fieldItems: data[i].optionItem,
                        required  : !!data[i].required
                    }, this);
                }
                options.push(newOption);
            }
        }
    }
    this.options = options;

    if (this.html) {
        this.visualObject.innerHTML = '';
        for (i = 0; i < this.options.length; i += 1) {
            insert = this.options[i].getHTML();
            if (i % 2 === 0) {
                insert.className += ' updater-inverse';
            }
            this.visualObject.appendChild(insert);
        }
    }
    return this;
};

ItemUpdaterField.prototype.createHTML = function () {
    var fieldLabel, required = '', criteriaContainer;
    PMSE.Field.prototype.createHTML.call(this);

    if (this.required) {
        required = '<i>*</i> ';
    }

    fieldLabel = this.createHTMLElement('span');
    fieldLabel.className = 'adam-form-label';
    fieldLabel.innerHTML = required + this.label + ':';
    fieldLabel.style.width = this.parent.labelWidth;
    fieldLabel.style.verticalAlign = 'top';
    this.html.appendChild(fieldLabel);

    criteriaContainer = this.createHTMLElement('div');
    criteriaContainer.className = 'adam-item-updater table';
    criteriaContainer.id = this.id;

    if (this.fieldWidth || this.fieldHeight) {
        style = document.createAttribute('style');
        if (this.fieldWidth) {
            style.value += 'width: ' + this.fieldWidth + 'px; ';
        }
        if (this.fieldHeight) {
            style.value += 'height: ' + this.fieldHeight + 'px; ';
        }
        style.value += 'display: inline-block; margin: 0; overflow: auto; padding: 3px;';
        criteriaContainer.setAttributeNode(style);
    }

    for (i = 0; i < this.options.length; i += 1) {
        insert = this.options[i].getHTML();
        if (i % 2 === 0) {
            insert.className = insert.className + ' updater-inverse';
        }
        criteriaContainer.appendChild(insert);
    }

    this.html.appendChild(criteriaContainer);

    if (this.helpTooltip) {
        this.html.appendChild(this.helpTooltip.getHTML());
    }

    this.visualObject = criteriaContainer;

    return this.html;
};

ItemUpdaterField.prototype.setValue = function (value) {
    this.value = value;
    if (this.options && this.options.length > 0) {
        try{
            fields = JSON.parse(value);
            if (fields && fields.length > 0) {
                for (i = 0; i < fields.length; i += 1) {
                    for (j = 0; j < this.options.length; j += 1) {
                        if (fields[i].field === this.options[j].fieldId) {
                            this.options[j].active = true;
                            this.options[j].checkboxControl.checked = true;
                            this.options[j].textControl.disabled = false;
                            this.options[j].fieldValue = fields[i].value;
                            this.options[j].value = fields[i].value;
                            if(this.options[j].fieldType === 'date') {
                                $(this.options[j].textControl)
                                    .datepicker( "option", {disabled: false});
                            } else if(this.options[j].fieldType === 'datetime') {
                                $(this.options[j].textControl)
                                    .datetimepicker( "option", {disabled: false});
                            }
                            if (this.options[j].fieldType == 'checkbox') {
                                this.options[j].textControl.checked = ((fields[i].value == 'on')?true:false);
                            }
                            this.options[j].textControl.value = fields[i].value;
                            //
                            break;
                        }
                    }
                }
            }
        } catch (e) {}
    }
    return this;
};

ItemUpdaterField.prototype.isValid = function() {
    var i, valid = true, current, field;
    for (i = 0; i < this.options.length; i += 1) {
        field = this.options[i];
        valid = valid && field.isValid();
        if(!valid) {
            break;
        }
    }

    if (valid) {
        valid = valid && PMSE.Field.prototype.isValid.call(this);
    } else {
        this.visualObject.scrollTop += getRelativePosition(field.getHTML(), this.visualObject).top;
    }
    return valid;
};

/*
ItemUpdaterField.prototype.validate = function () {
    var i, valid = true, current;
    for (i = 0; i < this.options.length; i += 1) {
        if (this.options[i].checkboxControl.checked) {
            current = this.options[i].isValid();
            valid = valid && current;
        }

    }
    return valid;
};*/

//

var FieldUpdater = function (options, parent) {
    PMSE.Base.call(this, options);
    this.field = null;
    this.fieldName = null;
    this.value = null;
    this.parent = null;
    this.label = null;
    this.module = null;
    FieldUpdater.prototype.initObject.call(this, options, parent);
};

FieldUpdater.prototype = new PMSE.Base();
FieldUpdater.prototype.type = "FieldUpdater";
FieldUpdater.prototype.initObject = function (options, parent) {
    if (options && options.type === 'FieldOption') {
        this.setField(options.fieldId)
            .setFieldName(options.fieldName)
            .setValue(options.fieldValue)
            .setParent(parent || null);
    } else {
        var defaults = {
            field: null,
            fieldName: null,
            value: null,
            label: null,
            module: null
        };
        $.extend(true, defaults, options);
        this.setField(defaults.field)
            .setFieldName(defaults.fieldName)
            .setValue(defaults.value)
            .setLabel(defaults.label)
            .setModule(defaults.module)
            .setParent(parent || null);
    }
};

FieldUpdater.prototype.setField = function (value, name) {
    this.field = value;
    if (typeof name !== 'undefined') {
        this.fieldName = name;
    }
    return this;
};

FieldUpdater.prototype.setFieldName = function (value) {
    this.fieldName = value;
    return this;
};

FieldUpdater.prototype.setValue = function (value) {
    this.value = value;
    return this;
};

FieldUpdater.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};

FieldUpdater.prototype.setLabel = function (label) {
    this.label = label;
    return this;
};

FieldUpdater.prototype.setModule = function (value) {
    this.module = value;
    return this;
};

FieldUpdater.prototype.getLabel = function () {
    var output;
    if (!this.label) {
        if (this.field && this.fieldName) {
            this.label = this.fieldName + ' = ' + "'" + this.value + "'";
        }
    }
    return this.label;
};

FieldUpdater.prototype.getJSONObject = function() {
    return {
        field: this.field,
        fieldName: this.fieldName,
        value: this.value
    };
};

//FieldOption
    var FieldOption = function (options, parent) {
        PMSE.Element.call(this, options);
        /**
         * Defines the parent PMSE.Form
         * @type {PMSE.Form}
         */
        this.parent = null;
        this.active = null;
        this.fieldId = null;
        this.fieldName = null;
        this.fieldValue = null;
        this.fieldItems = null;
        this.checkboxControl = null;
        this.textControl = null;
        this.parent = null;
        this.value = null;
        this.language = {};
        this.maxLength = null;
        this.required = null;
        FieldOption.prototype.initObject.call(this, options, parent);
    };

    FieldOption.prototype = new PMSE.Element();
    FieldOption.prototype.type = 'FieldOption';

    FieldOption.prototype.initObject = function (options, parent) {
        var defaults;

        defaults = {
            active: false,
            fieldId: null,
            fieldValue: "",
            fieldName: null,
            fieldType: null,
            fieldItems: null,
            maxLength: 0,
            language: {
                ERROR_FIELD_REQUIRED: 'This field is required',
                ERROR_INVALID_INTEGER: 'This field accepts only a integer value',
                ERROR_INVALID_DATETIME: 'This field accepts only a datetime value',
                ERROR_INVALID_DATE: 'This field accepts only a date value',
                ERROR_INVALID_PHONE: 'This field accepts only a phone value',
                ERROR_INVALID_FLOAT: 'This field accepts only a float value',
                ERROR_INVALID_DECIMAL: 'This field accepts only a decimal value',
                ERROR_INVALID_URL: 'This field accpets only an url',
                ERROR_INVALID_CURRENCY: 'This field accepts only a currency value',
                ERROR_INVALID_EMAIL: 'This field accepts only e-mail addresses'
            },
            required: false
        };
        $.extend(true, defaults, options);
        this.language = defaults.language;
        this.setParent(parent);
        this.setRequired(defaults.required)
            .setMaxLength(defaults.maxLength)
            .setActive(defaults.active)
            .setFieldId(defaults.fieldId)
            .setFieldName(defaults.fieldName)
            .setFieldValue(defaults.fieldValue)
            .setFieldType(defaults.fieldType)
            .setFieldItems(defaults.fieldItems)
            .setMessageError(defaults.messageError);
    };

    FieldOption.prototype.setRequired = function(required) {
        this.required = !!required;
        if(this.html) {
            $(this.html).find('.required.noshadow').show();
        }
        return this;
    };

    FieldOption.prototype.setMaxLength = function(maxLength) {
        var maxLength = parseInt(maxLength, 10);
        if(!isNaN(maxLength)) {
            this.maxLength = maxLength;
            if(this.textControl) {
                if(maxLength > 0) {
                    this.textControl.maxLength = maxLength;    
                } else {
                    this.textControl.removeAttribute('maxlength');
                }
            }
        }
        return this;
    };

    FieldOption.prototype.setActive = function (value) {
        this.active = value;
        return this;
    };

    FieldOption.prototype.setFieldId = function (value) {
        this.fieldId = value;
        return this;
    };

    FieldOption.prototype.setFieldName = function (value) {
        this.fieldName = value;
        return this;
    };

    FieldOption.prototype.setFieldValue = function (value) {
        this.fieldValue = value;
        this.value = value;
        if(this.textControl) {
            $(this.textControl).val(value);
        }
        return this;
    };

    FieldOption.prototype.setFieldType = function (value) {
        this.fieldType = value;
        return this;
    };

    FieldOption.prototype.setFieldItems = function (value) {
        this.fieldItems = value;
        return this;
    };

    FieldOption.prototype.setParent = function (value) {
        this.parent = value;
        return this;
    };

    FieldOption.prototype.createHTML = function () {
        var div,
            checkbox,
            label,
            edit,
            combo,
            checkboxf,
            readAtt,
            disabledValue, 
            span;
        PMSE.Element.prototype.createHTML.call(this);
        this.style.removeProperties(['width', 'height', 'position', 'top', 'left', 'z-index']);
        this.style.width = '100%';
        this.style.addClasses(['row']);

        div = this.createHTMLElement('div');
        div.className = 'cell';
        div.style.width = '30%';
        checkbox = document.createElement('input');
        checkbox.id = "chk_" + this.id;
        checkbox.type = 'checkbox';
        checkbox.className = 'adam-updater-checkbox';
        div.appendChild(checkbox);

        this.checkboxControl = checkbox;
        label = document.createElement('span');
        label.innerHTML = this.fieldName;
        label.className = 'adam-updater-label';
        div.appendChild(label);
        label = label.cloneNode(false);
        label.className = 'required noshadow';
        label.textContent = '*';
        label.style.display = this.required ? 'inline' : 'none';
        div.appendChild(label);
        this.html.appendChild(div);

        div = this.createHTMLElement('div');
        div.className = 'cell';
        div.style.width = '58%';

        if (this.fieldType === 'dropdown') {
            combo = document.createElement('select');
            for (var item in this.fieldItems) {
                var optionItem = document.createElement("option");
                optionItem.value = item;
                optionItem.style.marginBottom = '0px';
                optionItem.innerHTML = item;
                combo.appendChild(optionItem);
            }
            combo.id = "val_" + this.id;
            combo.type = 'dropdown';
            combo.className = 'adam-updater-value';
            div.appendChild(combo);
            combo.value = this.fieldValue;
            readAtt = document.createAttribute('disabled');
            combo.setAttributeNode(readAtt);
            this.textControl = combo;
        } else if (this.fieldType === 'checkbox') {
            checkboxf = document.createElement('input');
            checkboxf.id = "val_" + this.id;
            checkboxf.type = 'checkbox';
            checkboxf.className = 'adam-updater-checkbox';
            var label = document.createElement('label')
            label.htmlFor = "label" + this.id;
            //label.appendChild(document.createTextNode('Enabled'));
            div.appendChild(label);
            div.appendChild(checkboxf);
            checkboxf.checked = ((this.fieldValue == 'on')?true:false);
            readAtt = document.createAttribute('disabled');
            readAttLabel = document.createAttribute('disabled');
            checkboxf.setAttributeNode(readAtt);
            label.setAttributeNode(readAttLabel);
            this.textControl = checkboxf;
        } else {
            edit = document.createElement('input');
            edit.id = "val_" + this.id;
            edit.type = 'text';
            edit.className = 'adam-updater-value';
            edit.readOnly = this.fieldType === 'date' || this.fieldType === 'datetime';
            div.appendChild(edit);
            edit.value = this.fieldValue;
            readAtt = document.createAttribute('disabled');
            edit.setAttributeNode(readAtt);
            if (this.fieldType === 'password') {
                edit.type = 'password';
            }
            this.textControl = edit;
        }
        this.setMaxLength(this.maxLength);
        if (this.fieldType === 'date') {
            $(edit).datepicker({
                showOn: 'button',
                constrainInput: false,
                disabled : true
            }).next('button').text('').button({icons:{primary : 'ui-icon-calendar'}});
        }
        if (this.fieldType === 'datetime') {
            $(edit).datetimepicker({
                showOn: 'button',
                constrainInput: false,
                disabled : true
            }).next('button').text('').button({icons:{primary : 'ui-icon-calendar'}});
        }


        this.html.appendChild(div);

        div = this.createHTMLElement('div');
        div.className = 'cell';
        div.style.width = '5%';
        this.html.appendChild(div);

        div = this.createHTMLElement('div');
        div.className = 'clear';
        this.html.appendChild(div);

        this.attachListeners();

        return this.html;
    };

    FieldOption.prototype.attachListeners = function () {
        var root = this;
        $(this.checkboxControl).click(function (e) {
            if (root.checkboxControl.checked) {
                root.textControl.disabled = false;
                root.setActive(true).setFieldValue(root.textControl.value);
                //console.log(root);
                if (root.fieldType  === 'date' || root.fieldType === 'datetime') {
                    $(root.textControl).datepicker( "option", { disabled: false } );
                }


            } else {
                root.textControl.disabled = true;
                root.setActive(false);
                $(root.textControl).removeClass('required');
                if (root.fieldType  === 'date' || root.fieldType === 'datetime') {
                    $(root.textControl).datepicker( "option", { disabled: true } );
                }
                root.setFieldValue('');
            }
        });
        $(this.textControl).change(function (e) {
            if (root.textControl.type == 'checkbox') {
                root.textControl.value = 'off';
                if (root.textControl.checked == true) {
                    root.textControl.value = 'on';
                }
            }
            root.setFieldValue(root.textControl.value);
        });
        $(this.checkboxControl).change(function (e) {
            root.parent.parent.setDirty(true);
        });

    };

    /**
     * Sets the fields validation error message
     * @param {String} msg
     * @return {*}
     */
    FieldOption.prototype.setMessageError = function (msg) {
        this.messageError = msg;
        return this;
    };

    FieldOption.prototype.evalRequired = function() {
        if(this.required) {
            switch(this.fieldType) {
                case 'checkbox':
                    return true;
                default:
                    return !!this.textControl.value;
            }
        } else {
            return true;
        }
    };

    FieldOption.prototype.validInput = function() {
        var valid = true, 
            value = this.textControl.value,
            aux;

        switch(this.fieldType) {
            case "integer":
                return /^-?\d+$/.test(value);
            case "datetime":
                if(!/^\d\d(\/\d\d){2}(\d){2}\s\d\d(:\d\d){1,2}$/.test(value)) {
                    return false;
                }
                aux = value.split(" ");
                value = aux[1];
                value = value.split(":");
                value[0] = parseInt(value[0], 10);
                value[1] = parseInt(value[1], 10);
                value[2] = value[2] ? parseInt(value[2], 10) : null;
                if(value[0] > 23 || value[1] > 59 || (!value[2] ? false : value[2] > 59)) {
                    return false;
                }
                value = aux[0];
            case "date":
                if(!/^\d\d\/\d\d\/(\d){4}$/.test(value)) {
                    return false;
                }
                value = value.split("/");
                aux = {};
                aux.y = parseInt(value[2], 10);
                aux.m = parseInt(value[0], 10);
                aux.d = parseInt(value[1], 10);

                if(aux.m < 1 || aux.m > 12 || aux.d < 1 || aux.d > 31) {
                    return false;
                }

                if(aux.m === 4 || aux.m === 6 || aux.m === 9 || aux.m === 11) {
                    if(aux.d > 30) {
                        return false;
                    } 
                } else if(aux.m === 2) {
                    //check if it's a leap year
                    if((aux.y % 4 === 0 && aux.y % 100 !== 0) || aux.y % 400 === 0) {
                        if(aux.d > 29) {
                            return false;
                        }
                    } else {
                        if(aux.d > 28) {
                            return false;
                        }
                    }
                }
                break;
            case "float":
                return /^-?\d*(\.\d+)?$/.test(value);
            case "decimal":
                return /^-?\d+(\.\d{1,2})?$/.test(value);
            case "url":
                return /^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i.test(value);
            case "currency":
                return /^-?\d*(\.\d+)?$/.test(value);
            case "email":
                //return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
                return /^.+@.+$/ig.test(value);
            case "checkbox":
            case "dropdown":
            case "textfield":
            case "name":
            case "password":
            case "textarea":
            case "phone":
                return true;
                break;
        }

        return true;
    };

    FieldOption.prototype.isValid = function () {
        var i, res = true, message;

        if(!this.checkboxControl.checked) {
            return true;
        }

        res = this.evalRequired();
        if(res) {
            switch(this.fieldType) {
                case 'currency':
                case 'date':
                case 'datetime':
                case 'decimal':
                case 'float':
                case 'integer': 
                case 'email':
                    res = this.validInput();
                    break;
                default:
                    res = this.textControl.value ? this.validInput() : true;
            }

            if(!res) {
                switch(this.fieldType) {
                    case "integer":
                        message = this.language.ERROR_INVALID_INTEGER;
                        break;
                    case "datetime":
                        message = this.language.ERROR_INVALID_DATETIME;
                        break;
                    case "date":
                        message = this.language.ERROR_INVALID_DATE;
                        break;
                    case "phone":
                        message = this.language.ERROR_INVALID_PHONE;
                        break;
                    case "float":
                        message = this.language.ERROR_INVALID_FLOAT;
                        break;
                    case "decimal":
                        message = this.language.ERROR_INVALID_DECIMAL;
                        break;
                    case "url":
                        message = this.language.ERROR_INVALID_URL;
                        break;
                    case "currency":
                        message = this.language.ERROR_INVALID_CURRENCY;
                        break;
                    case "email":
                        message = this.language.ERROR_INVALID_EMAIL;
                        break;
                    case "checkbox":
                    case "dropdown":
                    case "textfield":
                    case "name":
                    case "password":
                    case "textarea":
                        message = '';
                        break;
                }
            }
        }

        return res;
    };


