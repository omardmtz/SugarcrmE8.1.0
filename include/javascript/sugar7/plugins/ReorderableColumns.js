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
    app.events.on('app:init', function() {

        /**
         * This plugin makes list view columns reorderable by a simple drag
         * and drop of the column header.
         *
         * It is only supported by `flex-list` views.
         */
        app.plugins.register('ReorderableColumns', ['view'], {

            /**
             * @property {String} _listDragColumnSelector CSS selector for the
             * draggable columns.
             * @private
             */
            _listDragColumnSelector: 'th[data-fieldname]',

            /**
             * @property {String} _listDragItemSelector CSS selector for actual
             * draggable items.
             * @private
             */
            _listDragItemSelector: 'th[data-fieldname] [data-draggable="true"]',

            /**
             * @property {Array} _listDragColumn Store the current visible
             * columns order.
             * @private
             */
            _listDragColumn: [],

            /**
             * This method will make the columns draggable and the placeholders
             * droppable.
             *
             * @private
             */
            _makeColumnReorderable: function() {

                if (this.disposed) {
                    return;
                }

                if (!this.$('table').hasClass('reorderable-columns')) {
                    app.logger.error('ReorderableColumns plugin expects the table to have .draggable-columns class ' +
                        'in order to work.');
                    return;
                }

                this._listDragColumn = _.map(this.$(this._listDragColumnSelector), function(column) {
                    return $(column).data('fieldname');
                });

                // Make columns draggable.
                this.$(this._listDragItemSelector).draggable({
                    revert: 'invalid',
                    axis: 'x',
                    stop: _.bind(function(event, ui) {
                        if (ui.helper._renderView && !this.disposed) {
                            this.render();
                        }
                    }, this)
                });

                // Make placeholders droppable.
                this.$('.th-droppable-placeholder').droppable({
                    accept: this._listDragItemSelector,
                    hoverClass: 'th-droppable-placeholder-highlight',
                    tolerance: 'touch',
                    drop: _.bind(this._onColumnDrop, this)
                });
            },

            /**
             * When a column is dropped into a placeholder, we first verify that
             * the item has moved.
             *
             * When moving to the right direction, we need to decrease the
             * destination index by 1 since when we remove the item, all the
             * others items are moved by 1 to the left.
             *
             * If it has actually moved, we take the full list of columns, and
             * move the item. Then we have to reset the catalog of visible.
             * Once this is done, local storage is updated and we render the
             * view.
             *
             * @param {Event} event The event that triggered this method.
             * @param {Object} ui jQuery UI object returned by droppable plugin.
             * @private
             */
            _onColumnDrop: function(event, ui) {
                var $draggedItem = $(ui.draggable),
                    $droppedInItem = $(event.target),
                    draggedIndex,
                    droppedInIndex,
                    initialOrder,
                    visibleOrder,
                    hasChanged;

                draggedIndex = $draggedItem
                    .closest('th')
                    .find('.th-droppable-placeholder:first')
                    .data('droppableindex');

                droppedInIndex = $droppedInItem.data('droppableindex');

                if (droppedInIndex > draggedIndex) {
                    droppedInIndex--;
                }

                initialOrder = _.clone(this._listDragColumn);
                visibleOrder = _.moveIndex(this._listDragColumn, draggedIndex, droppedInIndex);
                hasChanged = !_.isEqual(visibleOrder, initialOrder);

                if (!hasChanged) {
                    $draggedItem.draggable('option', 'revert', true);
                    return;
                }

                var newOrder = this._calculateNewOrder($draggedItem, $droppedInItem);
                // Trigger an event to let the view know to reorder the catalog
                // of fields.
                this.trigger('list:reorder:columns', this._fields, newOrder);

                // Will render the view on draggable `stop` event.
                ui.draggable._renderView = true;
            },

            /**
             * Takes the full list of fields (including hidden fields), move the
             * item, and returns the list.
             *
             * When moving to the right direction, we need to decrease the
             * destination index by 1 since when we remove the item, all the
             * others items are moved by 1 to the left.
             *
             * @param {jQuery} $draggedItem The element being dragged.
             * @param {jQuery} $droppedInItem The placeholder where the element
             * is dropped.
             * @return {Array} The full list freshly ordered.
             * @private
             */
            _calculateNewOrder: function($draggedItem, $droppedInItem) {
                var globalOrder,
                    draggedIndex,
                    droppedInIndex;

                globalOrder = _.pluck(this._fields.all, 'name');
                draggedIndex = _.indexOf(globalOrder, $draggedItem.closest('th').data('fieldname'));
                droppedInIndex = _.indexOf(globalOrder, $droppedInItem.closest('th').data('fieldname'));

                // Special case for the last column, we want to move after, not
                // before.
                if ($droppedInItem.hasClass('th-droppable-placeholder-last')) {
                    droppedInIndex++;
                }

                if (droppedInIndex > draggedIndex) {
                    droppedInIndex--;
                }

                return _.moveIndex(globalOrder, draggedIndex, droppedInIndex);
            },

            /**
             * @inheritdoc
             *
             * On render makes the list view columns reorderable.
             */
            onAttach: function(component, plugin) {
                this.on('render', _.debounce(this._makeColumnReorderable, 200), this);
            }
        });
    });
})(SUGAR.App);
