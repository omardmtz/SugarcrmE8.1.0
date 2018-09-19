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
(function(app) {

    // Sums the values of an array.
    var _sum = function(array) {
        return _.reduce(array, function(memo, num) {
            return memo + num;
        }, 0);
    };

    // Maps width classes defined into Styleguide to their actual size in
    // pixels.
    var _mapClassesToPixels = {
        'xxsmall': 21,
        'xsmall': 42,
        'small': 68,
        'medium': 110,
        'large': 178,
        'xlarge': 288,
        'xxlarge': 466
    };

    app.events.on('app:init', function() {

        /**
         * This plugin makes list view columns resizable by the end user.
         *
         * It is only supported by the list views extending
         * {@link View.Views.Base.FlexListView}.
         */
        app.plugins.register('ResizableColumns', ['view'], {

            /**
             * CSS selector for the resizable columns.
             *
             * @property {string}
             * @private
             */
            _listResizableColumnSelector: 'thead tr:first-child th[data-fieldname]',

            /**
             * This method will make the columns resizable.
             *
             * The plugin will trigger `list:column:resize:save`, passing the
             * column widths, when the user resizes a column. The view can
             * eventually listen and save them to the cache.
             *
             * The plugin calls
             * {@link View.Views.Base.FlexListView#getCacheWidths} and will
             * restore the widths if it gets an array containing the column
             * widths.
             *
             * @private
             */
            _makeColumnResizable: function() {
                if (this.disposed) {
                    return;
                }

                var proto = Object.getPrototypeOf(this);
                var getMinTableWidth;
                if (_.isFunction(proto.getMinTableWidth)) {
                    getMinTableWidth = _.bind(proto.getMinTableWidth, this);
                }

                var self = this;
                var $table = this.$('table');
                $table.resizableColumns({
                    usePixels: true,
                    selector: this._listResizableColumnSelector,
                    minWidth: 1,

                    /**
                     * Sets to `null` because we use our own store through the
                     * events.
                     */
                    store: null,

                    /**
                     * Sets the column widths to the table headers.
                     *
                     * The column widths are adjusted in case the sum of the
                     * columns is gonna be less than the table width.
                     *
                     * @param {Event} [event] The `column:resize:restore` event.
                     * @param {Array} [columns] The column widths.
                     */
                    restore: function(event, columns) {
                        if (self.disposed) {
                            return;
                        }
                        var resizableColumns = $table.data('resizableColumns');

                        if (columns) {
                            resizableColumns._expectedWidths = columns;
                        } else {
                            columns = resizableColumns._expectedWidths;
                        }

                        var expectedWidth = _sum(columns);

                        // The available width is
                        // the non-scrollable parent width
                        // less the non resizable columns.
                        var availableWidth = resizableColumns.options.getMinTableWidth() - resizableColumns._leftoverWidth;

                        // Each column has a border-right: 1px, so we need to
                        // remove a pixel per column.
                        availableWidth = availableWidth - columns.length;

                        var rate = expectedWidth / availableWidth;

                        if (rate < 1) {
                            columns = _.map(columns, function(col) {
                                return parseInt(col / rate, 10);
                            });
                        }

                        var i = 0;
                        resizableColumns.$tableHeaders.each(function(index, el) {
                            var $el, width;
                            $el = $(el);
                            width = columns[i++];
                            if ($el.attr('data-noresize') == null && width) {
                                return resizableColumns.setWidth($el[0], width);
                            }
                        });
                        resizableColumns.syncHandleWidths();
                    },

                    /**
                     * Returns the table's parent width.
                     *
                     * Feel free to override it if you need to return another
                     * minimum width.
                     * You can also set it to `null` if you don't want a minimum
                     * width.
                     *
                     * This is useful in case the table's parent element is
                     * not scrollable, so that we can make sure the table fills
                     * the full width of the non scrollable area.
                     *
                     * @return {number} The width in pixels.
                     */
                    getMinTableWidth: !_.isUndefined(getMinTableWidth) ?
                        getMinTableWidth :
                        function() {
                            return $table.parent().width();
                        }
                });

                // Restore the cache widths.
                var cachedSizes = this.getCacheWidths();
                if (_.isEmpty(cachedSizes)) {
                    cachedSizes = _.map(this._fields.visible, function(field) {
                        var width = field.expectedWidth || 'medium';
                        return _mapClassesToPixels[width] || width;
                    });
                }
                $table.trigger('column:resize:restore', [cachedSizes]);
                $(window).resize();

                // Triggers an event to tell the view to save changes.
                $table.on('column:resize:save', _.bind(function(event, columns) {
                    this.trigger('list:column:resize:save', columns);
                }, this));
            },

            /**
             * Unbinds the plugin and the events attached to the `<table>`.
             *
             * @private
             */
            _unbindResizableColumns: function() {
                if (this.disposed) {
                    return;
                }

                var $table = this.$('table');
                $table.off('column:resize:save');
                $table.resizableColumns('destroy');
            },

            /**
             * @inheritdoc
             *
             * Every time `render` is called, re-applies the plugin and cleans
             * up.
             */
            onAttach: function(component, plugin) {
                this.before('render', this._unbindResizableColumns);
                this.on('render', _.debounce(this._makeColumnResizable, 100));
            },

            /**
             * @inheritdoc
             *
             * Unbinds the plugin properly.
             */
            onDetach: function(component, plugin) {
                this._unbindResizableColumns();
            }
        });
    });
})(SUGAR.App);
