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
var ReassignField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.submit = false;
    this.items = [];
    this.comboId = null;
    ReassignField.prototype.initObject.call(this, options);
    $.extend(true, this.defaults, options);
};
ReassignField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
ReassignField.prototype.type = 'ReassignField';

ReassignField.prototype.initObject = function (options) {
    var defaults = {
        marginLeft : 50,
        timeTextSize: 11,
        picture : '/img/default_user.png',
        user: '',
        message: 'default message',
        items : [],
        startDate: '3 July 2013',
        duration: null,
        completed: false,
        options: [],
        comboId: 'comboboxID'
    };
    this.act_name = options.act_name;
    this.cas_delegate_date = options.cas_delegate_date;
    this.cas_due_date = options.cas_due_date;
    this.cas_index = options.cas_index;
    this.defaultValue = options.defaultValue;
    this.act_expected_time = options.act_expected_time;
    $.extend(true, defaults, options);
    this.setMarginLeft(defaults.marginLeft)
        .setPicture(defaults.picture)
        .setTask(defaults.task)
        .setTimeTextSize(defaults.timeTextSize)
        .setStartDate(defaults.startDate)
        .setMessage(defaults.message)
        .setDuration(defaults.duration)
        .setItems(defaults.items)
        .setCompleted(defaults.completed)
        .setOptions(defaults.options)
        .setComboId(defaults.comboId);
};



ReassignField.prototype.setMarginLeft = function (marginLeft) {
    this.marginLeft = marginLeft;
    return this;
};
ReassignField.prototype.setPicture = function (picture) {
    this.picture = picture;
    return this;
};
ReassignField.prototype.setTask = function (task) {
    this.task = task;
    return this;
};
ReassignField.prototype.setTimeTextSize = function (size) {
    this.timeTextSize = size;
    return this;
};
ReassignField.prototype.setStartDate = function (date) {
    this.startDate = date;
    return this;
};
ReassignField.prototype.setMessage = function (msg) {
    this.message = msg;
    return this;
};
ReassignField.prototype.setDuration = function (time) {
    this.duration = time;
    return this;
};
ReassignField.prototype.setItems = function (items) {
    this.items = items;
    return this;
};
ReassignField.prototype.setCompleted = function (val) {
    this.completed = val;
    return this;
};
ReassignField.prototype.setOptions = function (options) {
    this.options = options;
    return this;
};
ReassignField.prototype.setComboId = function (id) {
    this.comboId = id;
    return this;
};

/**
 *
 * @param table
 * @param {Array} headers Array of JSON, each contains a property called 'text' used
 * to create the header of the table
 */
ReassignField.prototype.createTableHeaders = function () {
    var i, detailDiv, newsItem, table;
    table = this.createHTMLElement('div');
    table.style.fontSize = "12px";
    table.style.display = 'table';
    table.style.width = '97%';

    for (i = 0; i < this.parent.columns.length; i += 1){
        detailDiv = this.createHTMLElement('div');
        detailDiv.style.width = '20%';
        detailDiv.style.display = 'table-cell';
        newsItem = this.createHTMLElement('p');
        newsItem.innerHTML = '<strong>' + this.parent.columns[i] + '</strong> ';
        detailDiv.appendChild(newsItem);
        table.appendChild(detailDiv);
    }
    this.html.appendChild(table);
    this.parent.hasHeaders = true;

};

/**
 * Creates the HTML Element of the field
 */
ReassignField.prototype.createHTML = function () {
    var fieldLabel, logPicture, newsItem, datetime, detailDiv, selectDiv, selectInput, i, table;
    PMSE.Field.prototype.createHTML.call(this);

    if (!this.parent.hasHeaders) {
        this.createTableHeaders();
    }

    table = this.createHTMLElement('div');
    table.style.fontSize = "12px";
    table.style.display = 'table';
    table.style.width = '97%';

    detailDiv = this.createHTMLElement('div');
    detailDiv.style.width = '20%';
    detailDiv.style.display = 'table-cell';
    detailDiv.style.marginLeft = '5px';
    newsItem = this.createHTMLElement('p');
    newsItem.innerHTML =  this.act_name;
    detailDiv.appendChild(newsItem);
    table.appendChild(detailDiv);

    detailDiv = this.createHTMLElement('div');
    detailDiv.style.width = '20%';
    detailDiv.style.display = 'table-cell';
    detailDiv.style.marginLeft = '5px';
    newsItem = this.createHTMLElement('p');
    newsItem.innerHTML = this.cas_delegate_date;
    detailDiv.appendChild(newsItem);
    table.appendChild(detailDiv);

    detailDiv = this.createHTMLElement('div');
    detailDiv.style.width = '20%';
    detailDiv.style.display = 'table-cell';
    detailDiv.style.marginLeft = '5px';
    newsItem = this.createHTMLElement('p');
    newsItem.innerHTML = this.act_expected_time;
    detailDiv.appendChild(newsItem);
    table.appendChild(detailDiv);

    detailDiv = this.createHTMLElement('div');
    detailDiv.style.width = '20%';
    detailDiv.style.display = 'table-cell';
    detailDiv.style.marginLeft = '5px';
    newsItem = this.createHTMLElement('p');
    newsItem.innerHTML = this.cas_due_date;
    detailDiv.appendChild(newsItem);
    table.appendChild(detailDiv);


    selectDiv = this.createHTMLElement('div');
    selectDiv.style.width = '20%';
//    selectDiv.style.paddingLeft = '15px';
    selectDiv.style.display = 'table-cell';
    detailDiv.style.marginLeft = '5px';
    //durationDiv.style.height = '100%';
//    selectDiv.style.color = '#707070';
    selectDiv.style.fontSize = this.timeTextSize + "px";
    //durationDiv.innerHTML =  '<p> ' + this.duration + '</p>';


    selectInput = this.createHTMLElement('select');
    selectInput.id = this.comboId;
    for (i = 0; i < this.options.length; i += 1) {
        selectInput.appendChild(this.generateOption(this.options[i]));
    }
    this.control = selectInput;
//    selectInput.id = this.name;
    selectDiv.appendChild(selectInput);
    table.appendChild(selectDiv);

    this.html.appendChild(table);

    return this.html;
};

ReassignField.prototype.generateOption = function (item) {
    var out, selected = '', value, text;
    out = this.createHTMLElement('option');
    if (typeof item === 'object') {
        value = item.value;
        text = item.text;
    } else {
        value = item;
    }
    out.selected = this.defaultValue === value;
    out.value = value;
    out.label = text || value;
    out.appendChild(document.createTextNode(text || value));
    return out;
};


ReassignField.prototype.attachListeners = function () {
    var id, logPanel, logBefore, logMidle, that;
    that = this;

};
ReassignField.prototype.createDifList = function (type) {
    var logDiv, log, c = '', i, related;
    logDiv = this.createHTMLElement('div');
    logDiv.style.width = '45%';
    logDiv.style.position = 'relative';

    logDiv.style.cssFloat = 'left';
    logDiv.style.backgroundColor = (type === 'before') ? '#fdd' : '#cfc';
    for (i = 0; i < this.items.length; i += 1) {
        related = this.items[i];
        log = this.createHTMLElement('p');
        c = (type === 'before') ? '-' : '+';
        c += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        c += related.field + ': ';
        c += (type === 'before') ? related.before : related.after;
        log.innerHTML = c;
        $(logDiv).append(log);

    }
    return logDiv;
};

ReassignField.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};

ReassignField.prototype.getObjectValue = function () {
    var response = {};
    response['cas_id'] = this.comboId;
    response['cas_user_id'] = this.control.value;
    response['cas_index'] = this.cas_index;
    response['old_cas_user_id'] = this.defaultValue;
    return response;
};