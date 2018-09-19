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
PMSE.Grid = function (options) {
    PMSE.Container.call(this, options);

    /**
     * Array of JS objects describing all the columns of this grid
     * The required properties of each objects are:
     *
     * - text (Text to give to the column)
     * - dataIndex (index located in each record of the store used to give a value to a cell)
     *
     * @type {Array}
     */
    this.columns = [];

    /**
     * The data of this grid is stored in a store
     * @type {JCore.data.Store}
     */
    this.store = null;

    PMSE.Grid.prototype.initObject.call(this, options);
};

PMSE.Grid.prototype = new PMSE.Container();

/**
 * The type of each instance of this class
 * @property {string}
 */
PMSE.Grid.prototype.type = 'PMSE.Grid';
PMSE.Grid.prototype.family = 'PMSE.Panel';

/**
 * Initializes the element with the options given
 * @param {Object} options options for initializing the object
 */
PMSE.Grid.prototype.initObject = function (options) {
    var defaults = {
        store: null,
        columns: []
    };
    $.extend(true, defaults, options);
    this.setStore(defaults.store)
        .setColumns(defaults.columns);
};

/**
 * TODO: ADD COMMENTS HERE
 */
PMSE.Grid.prototype.createHTML = function () {
    var i,
        table,
        record;
    //PMSE.Grid.superclass.prototype.createHTML.call(this);
    PMSE.Container.prototype.createHTML.call(this);
    // create the table
    table = document.createElement('table');
    // header
    this.createTableHeaders(table, this.columns);
    // content
    for (i = 0; this.store && i < this.store.getSize(); i += 1) {
        record = this.store.getRecord(i);
        this.createTableRow(table, this.columns, record);
    }

    // append the table's html to the body
    this.body.html.appendChild(table);
};

/**
 *
 * @param table
 * @param {Array} headers Array of JSON, each contains a property called 'text' used
 * to create the header of the table
 */
PMSE.Grid.prototype.createTableHeaders = function (table, headers) {
    var row = document.createElement('tr'),
        th,
        i;
    for (i = 0; i < headers.length; i += 1) {
        th = document.createElement('th');
        th.innerHTML = headers[i].text;
        row.appendChild(th);
    }
    table.appendChild(row);
    return table;
};

/**
 * Sets the parent object
 * @param {PMSE.Panel} parent
 * @return {*}
 */
PMSE.Grid.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
}

/**
 *
 * @param table
 * @param columns
 * @param record
 */
PMSE.Grid.prototype.createTableRow = function (table, columns, record) {
    var i,
        td,
        row = document.createElement('tr');
    for (i = 0; i < columns.length; i += 1) {
        td = document.createElement('td');
        td.innerHTML = record[columns[i].dataIndex] || "";
        row.appendChild(td);
    }
    table.appendChild(row);
    return table;
};

/**
 * Setter of the store of this object
 * @param {JCore.data.Store} newStore
 * @chainable
 */
PMSE.Grid.prototype.setStore = function (newStore) {
    this.store = newStore;
    return this;
};

/**
 * Getter of the store of this object
 * @return {JCore.data.Store}
 */
PMSE.Grid.prototype.getStore = function () {
    return this.store;
};

/**
 * Setter of the columns of this object
 * @param {Array} newColumns
 * @chainable
 */
PMSE.Grid.prototype.setColumns = function (newColumns) {
    this.columns = newColumns;
    return this;
};

/**
 * Getter of the columns of this object
 * @return {Array}
 */
PMSE.Grid.prototype.getColumns = function () {
    return this.columns;
};
