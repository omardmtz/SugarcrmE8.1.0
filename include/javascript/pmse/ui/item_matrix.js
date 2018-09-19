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
/*globals PMSE.Field, $, document*/
var ItemMatrixField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.moduleName = null;
    this.lockedFields = [];
    this.terminateFields = {};
    this.fieldWidth = null;
    this.fieldHeight = null;
    this.keyDelay = null;
    this.selectedHandler = null;
    this.searchValue = null;
    this.visualStyle = null;
    this.nColumns = null;
    this.lockedFieldsTrigger = {};
    this.unlockedFieldsTrigger = {};
    ItemMatrixField.prototype.initObject.call(this, options);
};

ItemMatrixField.prototype = new PMSE.Field();

ItemMatrixField.prototype.initObject = function (options) {
    var defaults = {
        visualStyle : 'list',
        nColumns : 2
    };
    $.extend(true, defaults, options);
//    this.setItems(defaults.items)
    this.setFieldWidth(defaults.fieldWidth)
        .setFieldHeight(defaults.fieldHeight)
        .setName(defaults.name)
        .setVisualStyle(defaults.visualStyle)
        .setNColumns(defaults.nColumns);
//        .setValueField(defaults.valueField);
};

ItemMatrixField.prototype.createHTML = function () {
    var fieldLabel, required = '', checkContainer, style;
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

    if (this.visualStyle === 'list') {
        checkContainer = this.createHTMLElement('ul');
        checkContainer.className = 'adam-item-matrix';
    } else {
        checkContainer = this.createHTMLElement('div');
        checkContainer.className = 'adam-item-matrix table';
    }

    if (this.fieldWidth && this.fieldHeight) {
        style = document.createAttribute('style');
        if (this.fieldWidth) {
            style.value += 'width: ' + this.fieldWidth + 'px; ';
        }
        if (this.fieldHeight) {
            style.value += 'height: ' + this.fieldHeight + 'px; ';
        }
        style.value += 'display: inline-block; margin: 0; overflow: auto; padding: 3px;';

        checkContainer.setAttributeNode(style);
    }
    this.html.appendChild(checkContainer);

    this.controlObject = checkContainer;

    return this.html;
};

ItemMatrixField.prototype.attachListeners = function () {
    var self = this;
    $(this.controlObject).on('click', '.item-matrix-field', function () {
        if ($(this).is(":checked")) {
            self.addLockedFields($(this).attr('value'));
        } else {
            self.removeLockedFields($(this).attr('value'));
        }
    });
    $(this.controlObject).on('change', '.item-matrix-field', function () {
        self.parent.setDirty(true);
    });
};

/* **** SETTERS **** */
ItemMatrixField.prototype.setFieldHeight = function (height) {
    this.fieldHeight = height;
    return this;
};

ItemMatrixField.prototype.setFieldWidth = function (width) {
    this.fieldWidth = width;
    return this;
};

ItemMatrixField.prototype.setNColumns = function (nColumns) {
    this.nColumns = nColumns;
    return this;
};

ItemMatrixField.prototype.setNameModule = function (moduleName) {
    this.nameModule = moduleName;
    return this;
};

ItemMatrixField.prototype.setLockedFields = function (lockedFields) {
    if (typeof lockedFields === 'object' && (lockedFields instanceof Array)) {
        this.lockedFields = lockedFields;
    }
    return this;
};

ItemMatrixField.prototype.setVisualStyle = function (vStyle) {
    this.visualStyle = vStyle;
    return this;
};

ItemMatrixField.prototype.addLockedFields = function (fieldName) {
    if (this.lockedFields.indexOf(fieldName) == -1) {
        this.lockedFields.push(fieldName);
    }
    // if this field triggers another field then we need to lock that field too.
    if (this.lockedFieldsTrigger[fieldName]) {
        $('.item-matrix-field[name=\'' + this.lockedFieldsTrigger[fieldName] + '\']').attr('checked', 'checked');
        if (this.lockedFields.indexOf(this.lockedFieldsTrigger[fieldName]) == -1) {
            this.lockedFields.push(this.lockedFieldsTrigger[fieldName]);
        }
    }
    return this;
};

ItemMatrixField.prototype.removeLockedFields = function (fieldName) {
    var index = this.lockedFields.indexOf(fieldName);
    var index2;
    this.lockedFields.splice(index, 1);
    // if this field triggers another field then we need to remove that locked field too
    if (this.unlockedFieldsTrigger[fieldName]) {
        $('.item-matrix-field[name=\'' + (this.unlockedFieldsTrigger[fieldName]) + '\']').removeAttr('checked');
        index2 = this.lockedFields.indexOf(this.unlockedFieldsTrigger[fieldName]);
        if (index2 != -1) {
            this.lockedFields.splice(this.lockedFields.indexOf(this.unlockedFieldsTrigger[fieldName]), 1);
        }
    }
    return this;
};
/**
 * Sets the combo box options
 * @param {Array} data
 * @return {*}
 */
ItemMatrixField.prototype.setList = function (data, selected) {
    var i, opt = '';
    this.lockedFieldsTrigger = {};
    this.unlockedFieldsTrigger = {};

    if (this.html) {
        $(this.controlObject).empty();
        this.lockedFields = [];
        if (this.visualStyle === 'table') {
            opt += '<div class="row">';
        }
        for (i = 0; i < data.length; i += 1) {
            // If there is a 'trigger' field in the object, then this field triggers another field to be locked as well.
            // We also need to save data to be able to unlock the dependent field in case the
            // original field is unchecked.
            if ((data[i].trigger) && (data[i].value)) {
                this.lockedFieldsTrigger[data[i].value] = data[i].trigger;
                this.unlockedFieldsTrigger[data[i].trigger] = data[i].value;
            }
            opt += this.generateOption(data[i], selected);
            if ((i + 1) % this.nColumns === 0) {
                opt += '</div><div class="row">';
            }
        }
        if (this.visualStyle === 'table') {
            opt += '</div></div>';
        }
        this.controlObject.innerHTML = opt;
    }
    return this;
};

ItemMatrixField.prototype.generateOption = function (item, selected) {
    var out = '', value, text, select;
    if (typeof item === 'object') {
        value = item.value;
        text = item.text;
    }
    if (typeof selected === 'object' && (selected instanceof Array)) {
        if (selected.indexOf(value) !== -1) {
            this.addLockedFields(value);
            select = 'checked = "checked"';
        }
    }
    if (this.visualStyle === 'list') {
        out = '<li style="list-style-type: none;"><label><input type="checkbox" name="' + value + '" value="' + value + '" class="item-matrix-field" ' + select + '/> ' + text + '</label></li>';
    } else {
        //out = '<div class="box cell"><label><input type="checkbox" name="' + value + '" value="' + value + '" class="item-matrix-field" ' + select + '/> ' + text + '</label></div>';
        out = '<div class="box cell"><input type="checkbox" name="' + value + '" value="' + value + '" class="item-matrix-field" ' + select + '/> <span>' + text + '</span></div>';
    }
    return out;
};

/* **** GETTERS **** */
ItemMatrixField.prototype.getFieldHeight = function () {
    return this.fieldHeight;
};

ItemMatrixField.prototype.getFieldWidth = function () {
    return this.fieldWidth;
};

ItemMatrixField.prototype.getNameModule = function () {
    return this.nameModule;
};

ItemMatrixField.prototype.getLockedField = function () {
    return this.lockedFields;
};

ItemMatrixField.prototype.getObjectValue = function () {
    this.value = JSON.stringify(this.lockedFields);
    return PMSE.Field.prototype.getObjectValue.call(this);
};
