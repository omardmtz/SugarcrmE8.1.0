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
(function (app) {
    app.events.on("app:init", function () {
        app.plugins.register("GridBuilder", ["view"], {
            /**
             * Acts as a GridBuilder factory, returning strategy that can build the grid with either labels on top of
             * or labels inline with their fields.
             *
             * Usage:
             *
             * var panel        = {
             *          fields:      [{...}, {...}, ...],
             *          columns:     2,
             *          labels:      true,
             *          labelsOnTop: true,
             *     },
             *     lastTabIndex = 14, // this might be a rolling counter across all panels
             *     options      = {
             *          fields:      panel.fields,
             *          columns:     panel.columns,
             *          labels:      panel.labels,
             *          labelsOnTop: panel.labelsOnTop,
             *          tabIndex:    lastTabIndex
             *      },
             *      gridResults = this.getGridBuilder(options).build();
             *
             * panel.grid   = gridResults.grid;
             * lastTabIndex = gridResults.lastTabIndex;
             *
             * @param params fields      The fields that are to be placed in the grid.
             *               columns     The maximum number of cells that can fit on a row.
             *               tabIndex    The last tab index. The grid should start with the next index.
             *               labels      true=include labels; false=exclude labels
             *               labelsOnTop true=on the top of the field; false=on the left of the field
             * @returns {GridBuilder} An object that derives from GridBuilder.
             */
            getGridBuilder: function(params) {
                /**
                 * Defines the Row class to be used by GridBuilder.
                 *
                 * @type {Row}
                 */
                function Row(options) {
                    this.initialize(options);
                }

                _.extend(Row.prototype, {
                    initialize: function(options) {
                        this.cells    = []; // the cells on the row
                        this.span     = 0; // keeps track of the span of all cells on the row
                        this.maxSpan  = options.maxSpan || 12; // the maximum span for all cells on the row
                        this.maxCells = options.maxCells || 1; // the maximum number of cells on the row
                    },

                    /**
                     * Adds a field to the row.
                     *
                     * @param field               The field object to be added.
                     * @param tabIndex (optional) The tab index to give to the field.
                     */
                    addCell: function(field, tabIndex) {
                        if (!_.isEmpty(tabIndex)) {
                            field.index = tabIndex;
                        }

                        // push a new cell, containing a field, onto the row
                        this.cells.push(field);

                        // update the row's span to account for the span of the cell that was just added
                        this.span += field.cellSpan;
                    },

                    /**
                     * Tells whether or not the row has the room to fit a cell with the specified span.
                     *
                     * @param cellSpan The size of the cell that is asking if it can fit.
                     * @returns {boolean}
                     */
                    hasMoreRoom: function(cellSpan) {
                        // if all available columns in the row have been filled or there isn't enough room remaining on
                        // the current row to contain the field, then there isn't enough room to fit a cell of the
                        // specified span
                        if (this.cells.length == this.maxCells || (this.span + cellSpan) > this.maxSpan) {
                            return false;
                        }

                        return true;
                    }
                });

                /**
                 * Defines the base GridBuilder class. This class is considered to be abstract.
                 *
                 * @type {GridBuilder}
                 * @abstract
                 */
                function GridBuilder(options) {
                    this.initialize(options);
                }

                _.extend(GridBuilder.prototype, {
                    initialize: function(options) {
                        options         = options || {};
                        options.fields  = options.fields || {};
                        this.grid       = []; // the rows on the grid
                        this.fields     = app.utils.deepCopy(options.fields); // the fields to be built on the grid
                        this.maxRowSpan = 12; // the maximum span for all cells on a row
                        this.maxColumns = options.columns || 1; // the maximum number of cells on a row
                        this.tabIndex   = options.tabIndex || 0; // the last tab index, start with the next index
                    },

                    /**
                     * Formats the grid to eliminate the Row objects from the return data so that they are abstracted
                     * away from outside callers.
                     *
                     * @returns {*} Override GridBuilder._getGrid() to customize the return value.
                     */
                    build: function() {
                        var grid = []; // the new grid, in collapsed form

                        _.each(this.grid, function(row, index) {
                            grid[index] = []; // start a new row

                            _.each(row.cells, function(cell) {
                                grid[index].push(cell); // add a cell to the current row
                            }, this);
                        }, this);

                        // replace the object-oriented grid with the simpler data structure so it can be returned
                        this.grid = grid;

                        return this._getGrid();
                    },

                    /**
                     * Creates a new row and adds it to the grid.
                     *
                     * @returns {Row} The row is returned so it can be immediately used by the caller.
                     */
                    addRow: function() {
                        var row = new Row({maxSpan: this.maxRowSpan, maxCells: this.maxColumns});
                        this.grid.push(row);

                        return row;
                    },

                    /**
                     * Adds a field to the next available row on the grid.
                     *
                     * @param field The field object to be added.
                     */
                    addField: function(field) {
                        var currentRow; // the row onto which the field should be added

                        if (this.grid.length < 1) {
                            // there are no rows on the grid, so add the first one
                            currentRow = this.addRow();
                        } else {
                            // use the last row on the grid
                            currentRow = this.grid[this.grid.length-1];
                        }

                        if (!currentRow.hasMoreRoom(field.cellSpan)) {
                            // there is no more room for this field on the last row, so add a new one
                            currentRow = this.addRow();
                        }

                        // add the field to current row
                        // pre-increment the tab index for the field
                        currentRow.addCell(field, ++this.tabIndex);
                    },

                    /**
                     * Returns the grid and the last tab index used by the GridBuilder. Override this method to
                     * customize a GridBuilder's return value.
                     *
                     * @return {Object}
                     * @private
                     */
                    _getGrid: function() {
                        return {
                            grid:         this.grid,
                            lastTabIndex: this.tabIndex
                        };
                    },

                    /**
                     * Calculate a field's span.
                     *
                     * @param field
                     * @private
                     */
                    _calculateFieldSpan: function(field) {
                        if (_.isUndefined(field.span)) {
                            // the field span must be initialized
                            field.span = Math.floor(this.maxRowSpan / this.maxColumns);
                        }

                        // prevent a span of 0
                        if (field.span < 1) {
                            field.span = 1;
                        }

                        // fields can't be greater than the maximum allowable span
                        if (field.span > this.maxRowSpan) {
                            field.span = this.maxRowSpan;
                        }

                        // the field takes up the space specified by its span only
                        field.cellSpan = field.span;
                    }
                });

                /**
                 * Defines a GridBuilder class that produces a grid with the labels inline with their fields.
                 *
                 * @type {LabelsInlineGridBuilder}
                 * @extends {GridBuilder}
                 */
                var LabelsInlineGridBuilder = function(options) {
                    this.initialize(options);
                }

                app.utils.extendFrom(LabelsInlineGridBuilder, GridBuilder, {
                    /**
                     * Iterates over the fields and constructs the grid. Cells will account for the spans for both the
                     * labels and the fields.
                     *
                     * @returns {*} The value returned by GridBuilder.build().
                     */
                    build: function() {
                        _.each(this.fields, function(field) {
                            this._calculateFieldSpan(field);

                            if (_.isUndefined(field.labelSpan)) {
                                // the label span must be initialized
                                // 4 for label span because we are using a 1/3 ratio between field span and label span
                                // with a max of 12
                                field.labelSpan = Math.floor(4 / this.maxColumns);
                            }

                            // prevent a labelSpan of 0
                            if (field.labelSpan < 1) {
                                field.labelSpan = 1;
                            }

                            if (_.isUndefined(field.dismiss_label)) {
                                field.dismiss_label = false;
                            }

                            if (field.dismiss_label !== true) {
                                // take the label span out of the field span
                                // i.e., upon initialization, the field span includes the space for its label
                                field.span -= field.labelSpan;

                                // need to once more prevent a span of 0
                                if (field.span < 1) {
                                    field.span = 1;
                                }
                            }

                            this.addField(field);
                        }, this);

                        return GridBuilder.prototype.build.call(this);
                    }
                });

                /**
                 * Defines a GridBuilder class that produces a grid with the labels on top of their fields.
                 *
                 * @type {LabelsOnTopGridBuilder}
                 * @extends {GridBuilder}
                 */
                var LabelsOnTopGridBuilder = function(options) {
                    this.initialize(options);
                }

                app.utils.extendFrom(LabelsOnTopGridBuilder, GridBuilder, {
                    /**
                     * Iterates over the fields and constructs the grid. Cells will only account for the spans for the
                     * fields.
                     *
                     * @returns {*} The value returned by GridBuilder.build().
                     */
                    build: function() {
                        _.each(this.fields, function(field) {
                            this._calculateFieldSpan(field);

                            if (_.isUndefined(field.labelSpan)) {
                                // the label span should be the same as the field span
                                field.labelSpan = field.span
                            }

                            if (_.isUndefined(field.dismiss_label)) {
                                field.dismiss_label = false;
                            }

                            this.addField(field);
                        }, this);

                        return GridBuilder.prototype.build.call(this);
                    }
                });

                // return the GridBuilder, with the following options, that matches what the caller needs
                var options = {fields: params.fields, columns: params.columns, tabIndex: params.tabIndex};

                if (params.labelsOnTop === false && params.labels) {
                    return new LabelsInlineGridBuilder(options);
                } else {
                    return new LabelsOnTopGridBuilder(options);
                }
            }
        });
    });
})(SUGAR.App);
