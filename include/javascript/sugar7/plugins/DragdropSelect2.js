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
         * This plugin adds selection, drag and drop functionality to any field
         * that uses select2. It can be used for moving selected items from
         * one select2 field to another.
         *
         * To use this plugin there are a few requirements:
         * 1. The items to drag/drop must have a data-id attribute with the item id
         * 2. Call setDragDropPluginEvents after select2 is initialized
         * 3. Call setDragDropPluginEvents any time a new item is selected
         */
        app.plugins.register('DragdropSelect2', ['field'], {

            selectedClass: 'drag-drop-selected',
            itemSelector: 'li.select2-search-choice',
            clickEventName: 'click.selectable',
            multiSelectNone: 'none',
            multiSelectItem: 'item',
            multiSelectRange: 'range',

            /**
             * @inheritdoc
             */
            onAttach: function() {
                this.on('init', function() {
                    this.view.on('dragDropSelect2:selected', _.bind(this._handleItemSelected, this));

                    app.shortcuts.register({
                        id: 'DragdropSelect2:SelectAll',
                        keys: 'ctrl+a',
                        component: this,
                        description: 'LBL_SHORTCUT_DRAGDROPSELECT2_SELECTALL',
                        callOnFocus: true,
                        handler: function() {
                            this.context.trigger('dragdropselect2:select:all', event);
                        }
                    });

                    this.context.on('dragdropselect2:select:all', function(event) {
                        if ($.contains(this.el, event.target) || !_.isEmpty(this.$lastSelectedItem)) {
                            this._selectAll();

                            //close select2 to allow for an immediate drag of the selected items
                            //otherwise select2 swallows the click before draggable can catch it
                            this.$selectablSelect2.select2('close');
                        }
                    }, this);
                });
            },

            /**
             * Used to check if an element has been initialized to be droppable.
             *
             * @private
             * @param {jQuery} $element The jQuery element to check.
             * @return {Boolean} Whether the element is droppable or not.
             */
            _isDroppableElement: function($element) {
                return $element.length > 0 && !_.isUndefined($element.droppable('instance'));
            },

            /**
             * Used to check if an element has been initialized to be draggable.
             *
             * @private
             * @param {jQuery} $element The jQuery element to check.
             * @return {Boolean} Whether the element is draggable or not.
             */
            _isDraggableElement: function($element) {
                return $element.length > 0 && !_.isUndefined($element.draggable('instance'));
            },

            /**
             * Add selectable, draggable, and droppable functionality to a
             * select2 component
             *
             * @param {jQuery} $select2 The element with select2 plugin applied
             */
            setDragDropPluginEvents: function($select2) {
                var $items = this.$(this.itemSelector);

                this.setSelectable($select2, $items);
                this.setDraggable($select2, $items);
                this.setDroppable($select2);
            },

            /**
             * Add selectable functionality to a select2 component
             *
             * @param {jQuery} $select2 The element with select2 plugin applied.
             * @param {jQuery} $items Items to make selectable
             */
            setSelectable: function($select2, $items) {
                var self = this;
                this.$selectablSelect2 = $select2;

                //clear out any previous events before setting up new ones
                if (this.$selectableItems) {
                    this.$selectableItems.off(this.clickEventName);
                }

                this.$selectableItems = $items;
                this.$selectableItems.on(this.clickEventName, function(event) {
                    self.markSelected($(this), self._getMultiSelectType(event));

                    //stop select2 from auto-scrolling down to the search item
                    event.stopPropagation();
                });

                //only need to set up the document click clear event once
                if (!this.$clearOnClickDoc) {
                    this.$clearOnClickDoc = $(document);
                    this.$clearOnClickDoc.on(this.clickEventName, _.bind(this._clearSelected, this));
                }

                //clear out any previous events before setting up new ones
                if (this.$clearOnContainerClick) {
                    this.$clearOnContainerClick.off(this.clickEventName);
                }

                this.$clearOnContainerClick = $select2.select2('container');
                this.$clearOnContainerClick.off(this.clickEventName);
                this.$clearOnContainerClick.on(this.clickEventName, _.bind(this._clearSelected, this));
            },

            /**
             * Mark the given item as selected, taking into account any modifiers
             * that would cause the selection to be multi-select or range select.
             *
             * @param {jQuery} $item
             * @param {string} multiSelectType Whether we are selecting multiple -
             *   valid values are: none, item, or range
             */
            markSelected: function($item, multiSelectType) {
                var rangeTraversal, $rangeItems;

                if (multiSelectType === this.multiSelectRange &&
                    !_.isEmpty(this.$lastSelectedItem) &&
                    !$item.is(this.$lastSelectedItem)
                ) {
                    rangeTraversal = ($item.index() >= this.$lastSelectedItem.index()) ? 'nextUntil' : 'prevUntil';
                    $rangeItems = this.$lastSelectedItem[rangeTraversal]($item);
                    $rangeItems.addClass(this.selectedClass);
                    $item.addClass(this.selectedClass);
                } else if (multiSelectType === this.multiSelectItem) {
                    $item.toggleClass(this.selectedClass);
                } else {
                    this._clearSelected();
                    $item.addClass(this.selectedClass);
                }

                // keep track of last selected to handle range selection
                this.$lastSelectedItem = ($item.hasClass(this.selectedClass)) ? $item : null;
                this.view.trigger('dragDropSelect2:selected', this.name);
            },

            /**
             * Add draggable functionality to a select2 component
             *
             * @param {jQuery} $select2 The element with select2 plugin applied.
             * @param {jQuery} $items Items to make draggable
             */
            setDraggable: function($select2, $items) {
                var self = this,
                    $mainPane = this.$el.closest('.main-pane'),
                    selectedClassSelector = '.' + self.selectedClass;

                //clear out any previous draggable before setting up new ones
                if (this.$draggableItems && this._isDraggableElement(this.$draggableItems)) {
                    this.$draggableItems.draggable('destroy');
                }

                this.$draggableItems = $items;
                this.$draggableItems.draggable({
                    containment: $mainPane,
                    distance: 10,
                    helper: function(event) {
                        var $helper = $('<div class="drag-drop-select2-helper"></div>'),
                            $clickedItem = $(event.target).closest('li');

                        $helper.data('sourceField', self.name);
                        $helper.appendTo($mainPane);

                        //if clicked item wasn't already selected, clear any selection and select it
                        if (!$clickedItem.hasClass(self.selectedClass)) {
                            self._clearSelected();
                            $clickedItem.addClass(self.selectedClass);
                        }

                        self.$(selectedClassSelector).each(function() {
                            $(this).find('[data-id]').clone().removeAttr('rel').appendTo($helper);
                        });

                        return $helper.get(0);
                    },
                    start: function() {
                        self.$(selectedClassSelector).hide();
                    },
                    stop: function() {
                        self.$(selectedClassSelector).show();
                    }
                });
            },

            /**
             * Add droppable functionality to a select2 component
             *
             * @param {jQuery} $select2 The element with select2 plugin applied.
             */
            setDroppable: function($select2) {
                var self = this,
                    $select2Container = $select2.select2('container');

                //if we've already added droppable to this container, no work to do
                if (this.$select2Container === $select2Container) {
                    return;
                }

                //clear out previous droppable before setting up new one
                if (this.$select2Container && this._isDroppableElement(this.$select2Container)) {
                    this.$select2Container.droppable('destroy');
                }

                this.$select2Container = $select2Container;
                this.$select2Container.droppable({
                    activeClass: 'drop-highlight',
                    hoverClass: 'drop-hover',
                    drop: function(event, ui) {
                        var sourceField = $(ui.helper).data('sourceField');
                        var sourceCollection = self.model.get(sourceField);
                        var targetCollection = self.model.get(self.name);
                        var draggedItems = [];
                        var droppedItems;

                        //if drag/drop within same field, no work to do
                        if (sourceField === self.name) {
                            return;
                        }

                        $(ui.helper).find('[data-id]').each(function() {
                            var item = sourceCollection.get($(this).data('id'));
                            if (item) {
                                draggedItems.push(item);
                            }
                        });

                        droppedItems = _.map(draggedItems, function(model) {
                            return model.clone();
                        });

                        if (_.isFunction(self.dropDraggedItems)) {
                            self.dropDraggedItems(sourceCollection, targetCollection, draggedItems, droppedItems);
                        } else {
                            sourceCollection.remove(draggedItems);
                            targetCollection.add(droppedItems);
                        }
                    }
                });
            },

            /**
             * Determine if we are multi-selecting and if so, what type.
             *
             * @param {Event} event
             * @return {string} What type of multi-select (if any) - valid values:
             *   none: no modifier - select one at a time
             *   item: meta/ctrl modifier - select multiple, one at a time
             *   range: shift modifier - select a range of items
             * @private
             */
            _getMultiSelectType: function(event) {
                if (event.shiftKey === true) {
                    return this.multiSelectRange;
                } else if (event.metaKey === true || event.ctrlKey === true) {
                    return this.multiSelectItem;
                } else {
                    return this.multiSelectNone;
                }
            },

            /**
             * Clear selection of all items
             *
             * @private
             */
            _clearSelected: function() {
                this.$(this.itemSelector).removeClass(this.selectedClass);
                this.$lastSelectedItem = null;
            },

            /**
             * Selection all items
             *
             * @private
             */
            _selectAll: function() {
                this.$(this.itemSelector).addClass(this.selectedClass);
                this.view.trigger('dragDropSelect2:selected', this.name);
            },

            /**
             * Clear selection of this field if another field is selected
             *
             * @param {string} fieldName
             * @private
             */
            _handleItemSelected: function(fieldName) {
                if (fieldName !== this.name) {
                    this._clearSelected();
                }
            },

            /**
             * Destroy event listeners on dispose.
             */
            onDetach: function() {
                if (this.$selectableItems) {
                    this.$selectableItems.off(this.clickEventName);
                }
                if (this.$clearOnClickDoc) {
                    this.$clearOnClickDoc.off(this.clickEventName);
                }
                if (this.$clearOnContainerClick) {
                    this.$clearOnContainerClick.off(this.clickEventName);
                }
                if (this.$draggableItems && this._isDraggableElement(this.$draggableItems)) {
                    this.$draggableItems.draggable('destroy');
                }
                if (this.$select2Container && this._isDroppableElement(this.$select2Container)) {
                    this.$select2Container.droppable('destroy');
                }
            }
        });
    });
})(SUGAR.App);
