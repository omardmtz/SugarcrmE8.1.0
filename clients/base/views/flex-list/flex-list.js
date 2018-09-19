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
/**
 * @class View.Views.Base.FlexListView
 * @alias SUGAR.App.view.views.BaseFlexListView
 * @extends View.Views.Base.ListView
 */
({
    extendsFrom: 'ListView',
    className: 'flex-list-view',
    // Model being previewed (if any)
    _previewed: null,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        //Store left column fields
        this.leftColumns = [];
        //Store right column fields
        this.rightColumns = [];
        this.addActions();

        this.template = app.template.getView('flex-list');
        this.events = _.clone(this.events);

        /**
         * The last state key that contains the full list of fields displayable
         * in list views of this module.
         *
         * @property {string}
         * @protected
         */
        this._allListViewsFieldListKey = app.user.lastState.buildKey('field-list', 'list-views', this.module);

        /**
         * The last state key that contains the visible state of the fields and
         * their position in the table for this specific view.
         *
         * @property {string}
         * @protected
         */
        this._thisListViewFieldListKey = app.user.lastState.key('visible-fields', this);

        if (this.meta.sticky_resizable_columns) {
            /**
             * The last state key that contains the user defined column widths
             * for this specific view.
             *
             * @property {string}
             * @protected
             */
            this._thisListViewFieldSizesKey = app.user.lastState.key('width-fields', this);
        }

        this._fields = this.parseFields();

        this.addPreviewEvents();

        //add debounce in initialize so that subclasses will not all use the same prototype function
        this.resize = _.bind(_.debounce(this.resize, 200), this);
        this.bindResize();

        var rightColumnsEvents = {};
        //add an event delegate for right action dropdown buttons onclick events
        if (this.rightColumns.length) {
            rightColumnsEvents = {
                'hidden.bs.dropdown .actions': 'resetDropdownDelegate',
                'shown.bs.dropdown .actions': 'delegateDropdown',
                'shown.bs.dropdown .morecol': '_toggleAria',
                'hidden.bs.dropdown .morecol': '_toggleAria'
            };
        }

        this.events = _.extend(rightColumnsEvents, this.events, {
            'click [data-widths=reset]': 'resetColumnWidths',
            'click [data-columns-order=reset]': 'resetColumnOrder'
        });

        this.on('list:reorder:columns', this.reorderCatalog, this);
        this.on('list:toggle:column', this.saveCurrentState, this);
        this.on('list:save:laststate', this.saveCurrentState, this);
        this.on('list:column:resize:save', this.saveCurrentWidths, this);
        this.on('list:scrollLock', this.scrollLock, this);
    },

    // fn to turn off event listeners and reenable tooltips
    resetDropdownDelegate: function(e) {
        this.$el.removeClass('no-touch-scrolling');
        var $b = this.$(e.currentTarget).first();
        $b.parent().closest('.list').removeClass('open');
        $b.off('resetDropdownDelegate.right-actions');
    },

    delegateDropdown: function(e) {
        var $buttonGroup = this.$(e.currentTarget).first(), // the button group
            windowHeight = $(window).height() - 65; // height of window less padding

        // fn to detect menu colliding with window bottom
        var needsDropupClass = function($b) {
                var menuHeight = $b.height() + $b.children('ul').first().height();
                return (
                     windowHeight < $b.offset().top + menuHeight
                );
            };

        this.$el.addClass('no-touch-scrolling');
        // add open class to parent list to elevate absolute z-index for iOS
        $buttonGroup.parent().closest('.list').addClass('open');
        // detect window bottom collision
        $buttonGroup.toggleClass('dropup', needsDropupClass($buttonGroup));
        // listen for delegate reset
        $buttonGroup.on('resetDropdownDelegate.right-actions', this.resetDropdownDelegate);
        // add a listener to scrolling container
        $buttonGroup.parents('.main-pane')
            .on('scroll.right-actions', _.bind(_.debounce(function() {
                // detect window bottom collision on scroll
                $buttonGroup.toggleClass('dropup', needsDropupClass($buttonGroup));
            }, 30), this));
    },

    /**
     * Sets a button accessibility class 'aria-expanded' to true or false
     * depending on if the dropdown menu is open or closed.
     *
     * @param {Event} provides the needed currentTarget
     * @private
     */
    _toggleAria: function(e) {
        var $dropdown = this.$(e.currentTarget).find('.dropdown'),
            $button = $dropdown.find('[data-toggle="dropdown"]');
        $button.attr('aria-expanded', $dropdown.hasClass('open'));
    },

    addPreviewEvents: function () {
        //When clicking on eye icon, we need to trigger preview:render with model&collection
        this.context.on("list:preview:fire", function (model) {
            app.events.trigger("preview:render", model, this.collection, true);
        }, this);

        //When switching to next/previous record from the preview panel, we need to update the highlighted row
        app.events.on("list:preview:decorate", this.decorateRow, this);
        if (this.layout) {
            this.layout.on("list:sort:fire", function () {
                //When sorting the list view, we need to close the preview panel
                app.events.trigger("preview:close");
            }, this);
            this.layout.on("list:paginate:success", function () {
                //When fetching more records, we need to update the preview collection
                app.events.trigger("preview:collection:change", this.collection);
                // If we have a model in preview, redecorate the row as previewed
                if (this._previewed) {
                    this.decorateRow(this._previewed);
                }
            }, this);
        }
    },

    /**
     * Parse fields to identify which fields are visible and which fields are
     * hidden.
     *
     * In practice, it creates a catalog that lists the fields that are
     * visible (user configuration if exists, otherwise default metadata
     * configuration) and all the fields (no matter their visible state) used to
     * populate the ellipsis dropdown.
     *
     * By default the catalog is sorted by the order defined in the metadata. If
     * user configuration is found, the catalog is sorted per user preference.
     *
     * @return {Object} The catalog object.
     */
    parseFields: function() {
        var fields = _.flatten(_.pluck(this.meta.panels, 'fields'));

        /**
         * The default order of the fields.
         *
         * @property {string[]}
         * @private
         */
        this._defaultFieldOrder = _.pluck(fields, 'name');
        var catalog = this._createCatalog(fields);

        /**
         * The custom order of the fields.
         *
         * See {@link #_getFieldsLastState}.
         *
         * @property {string[]}
         * @private
         */
        this._thisListViewFieldList = this._getFieldsLastState();

        if (this._thisListViewFieldList) {
            catalog = this._toggleFields(catalog, this._thisListViewFieldList, false);
            catalog = this.reorderCatalog(catalog, this._thisListViewFieldList.position, false);
        }
        return catalog;
    },

    /**
     * Retrieves the user configuration from the cache.
     *
     * The cached value changed in 7.2. In an entry is found in the local
     * storage and is at the wrong format, the value is converted to the new
     * format. If no entry found, or the entry has an unexpected format, it
     * throws an exception and return undefined.
     *
     * @return {Object/undefined} An object whom keys are field names, and
     * values are an object containing the position and the visible state,
     * or `undefined` in case of failure.
     *
     * @private
     */
    _getFieldsLastState: function() {
        if (!this._thisListViewFieldListKey) {
            return;
        }
        var data = app.user.lastState.get(this._thisListViewFieldListKey);
        if (_.isUndefined(data)) {
            return;
        }
        if (!_.isArray(data) || _.isEmpty(data)) {
            app.logger.error('The format of "' + this._thisListViewFieldListKey + '" is unexpected, skipping.');
            return;
        }
        if (_.isString(data[0])) {
            // Old format detected.
            return this._convertFromOldFormat(data);
        }
        return this._decodeCacheData(data);
    },

    /**
     * Create an object that contains 2 keys. Each key is associated to an array
     * that contains the field metadata.
     * List of keys:
     * - `visible` lists fields user wants to see,
     * - `all` lists all the fields, with a `selected` attribute that indicates
     * their visible state (used to populate the ellipsis dropdown).
     *
     * @param {Array} fields The list of field definition for this view.
     * @return {Object} The catalog object.
     * @private
     */
    _createCatalog: function(fields) {
        var catalog = {};
        catalog._byId = {};
        catalog.visible = [];
        catalog.all = [];

        _.each(fields, function(fieldMeta, i) {
            catalog._byId[fieldMeta.name] = this._patchField(fieldMeta, i);
        }, this);
        catalog.all = _.toArray(catalog._byId);
        catalog.visible = _.where(catalog.all, { selected: true });
        return catalog;
    },

    /**
     * Patch a field metadata for this list view.
     *
     * Note that {@link View.FlexListView requires the attributes `selected` and
     * `position`} in order to work properly.
     *
     * @param {Object} fieldMeta The field metadata.
     * @param {Number} index The index of the field in the field list.
     * @return {Object} The patched metadata.
     * @private
     */
    _patchField: function(fieldMeta, index) {
        var isVisible = (fieldMeta['default'] !== false);
        return _.extend({
            selected: isVisible,
            position: index + 1
        }, fieldMeta);
    },

    /**
     * Take the existing catalog and toggle field visibility based on the last
     * state found in the cache.
     *
     * If for some reason, the field is not found at all in the cached data, it
     * fallbacks to the default visible state of that field (defined in the
     * metadata).
     *
     * @param {Object} catalog The catalog of fields.
     * @param {Object} fields The decoded cached data that contains fields
     * wanted visible and fields wanted hidden.
     * @param {Boolean} saveLastState(optional) `true` to save last state,
     * `false` otherwise. `true` by default.
     * @return {Object} The catalog with visible state of fields based on user
     * preference.
     * @private
     */
    _toggleFields: function(catalog, fields, saveLastState) {
        if (_.isEmpty(fields) || (_.isEmpty(fields.visible) && _.isEmpty(fields.hidden))) {
            return catalog;
        }
        saveLastState = _.isUndefined(saveLastState) ? true : saveLastState;
        _.each(fields.visible, function(fieldName) {
            var f = catalog._byId[fieldName];
            if (f) {
                f.selected = true;
            }
        }, this);
        _.each(fields.hidden, function(fieldName) {
            var f = catalog._byId[fieldName];
            if (f) {
                f.selected = false;
            }
        }, this);
        catalog.all = _.sortBy(_.toArray(catalog._byId), function(f) {
            return f.position;
        });
        catalog.visible = _.where(catalog.all, { selected: true });

        if (saveLastState) {
            this.trigger('list:save:laststate');
        }

        return catalog;
    },

    /**
     * Sort the catalog of fields per the list of field names passed as
     * argument.
     *
     * @param {Object} catalog Field definitions listed in 2 categories:
     * `visible` / `all`.
     * @param {Array} order Array of field names used to sort the catalog.
     * @param {Boolean} saveLastState(optional) `true` to save last state,
     * `false` otherwise. `true` by default.
     * @return {Object} catalog The catalog of fields entirely sorted.
     */
    reorderCatalog: function(catalog, order, saveLastState) {
        saveLastState = _.isUndefined(saveLastState) ? true : saveLastState;

        order = _.union(order, _.pluck(catalog.all, 'name'));

        _.each(order, function(fieldName, i) {
            var f = catalog._byId[fieldName];
            if (f) {
                f.position = ++i;
            }
        });
        catalog.all = _.sortBy(_.toArray(catalog._byId), function(f) {
            return f.position;
        });
        catalog.visible = _.where(catalog.all, { selected: true });

        if (saveLastState) {
            this.trigger('list:save:laststate');
        }

        return catalog;
    },

    /**
     * Takes the minimized value stored into the cache and decode it to make
     * it more readable and more manipulable.
     *
     *     @example
     *     If field storage entry is:
     *     <pre><code>
     *     [
     *         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'
     *     ]
     *     </code></pre>
     *     And encoded data is:
     *     <pre><code>
     *     [
     *         0, [1,5], [1,2], 0, [0,1], [1,3], 0, [1,4]
     *     ]
     *     </code></pre>
     *     The decoded data will be:
     *     <pre><code>
     *     {
     *         visible: ['B', 'C', 'F', 'H'],
     *         hidden: ['E'],
     *         position: ['E', 'C', 'B', 'F', 'H']
     *     }
     *     </code></pre>
     *     `visible` contains the list of visible fields,
     *     `hidden` contains the list of hidden fields,
     *     `position` is the order of fields,
     *     indexes whom value is `0` are skipped (fields not displayable).
     *
     * @param {Array} encodedData The minimized data.
     * @return {Object} The decoded data.
     * @private
     */
    _decodeCacheData: function(encodedData) {
        var decodedData = {
            visible: [],
            hidden: [],
            position: []
        };

        var fieldList = this._appendFieldsToAllListViewsFieldList();
        _.each(encodedData, function(fieldArray, i) {
            if (!_.isArray(fieldArray)) {
                return;
            }
            var name = fieldList[i];
            if (fieldArray[0]) {
                decodedData.visible.push(name);
            } else {
                decodedData.hidden.push(name);
            }
            decodedData.position[fieldArray[1]] = name;
        });
        decodedData.position = _.difference(decodedData.position, [undefined]);
        return decodedData;
    },

    /**
     * Takes the decoded data and minimize it to save cache size.
     *
     *     @example
     *     If field storage entry is:
     *     <pre><code>
     *     [
     *         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'
     *     ]
     *     </code></pre>
     *     And decoded data is:
     *     <pre><code>
     *     {
     *         visible: ['B', 'C', 'F', 'H'],
     *         hidden: ['E'],
     *         position: ['E', 'C', 'B', 'F', 'H']
     *     }
     *     </code></pre>
     *     The encoded data will be:
     *     <pre><code>
     *     [
     *         0, [1,5], [1,2], 0, [0,1], [1,3], 0, [1,4]
     *     ]
     *     </code></pre>
     *     `0` means the field is not displayable. (i.e: `A`, `D`, `G`),
     *     the first item is the visible state: `1` visible, `0` hidden,
     *     the second item of the array is the position of the field.
     *
     * @param {Object} decodedData The decoded data.
     * @return {Array} The minimized data.
     * @private
     */
    _encodeCacheData: function(decodedData) {
        var encodedData = [];

        var fieldList = this._appendFieldsToAllListViewsFieldList();
        _.each(fieldList, function(fieldName) {
            var value = 0;
            if (_.contains(decodedData.position, fieldName)) {
                value = [
                    _.contains(decodedData.visible, fieldName) ? 1 : 0,
                    _.indexOf(decodedData.position, fieldName) + 1
                ];
            }
            encodedData.push(value);
        });
        return encodedData;
    },

    /**
     * Takes the decoded data and minimize it to save cache size.
     *
     * For example, if the field's storage entry is:
     *
     *     ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']
     *
     * And the decoded data is:
     *
     *     {
     *         visible: ['B', 'C', 'E', 'F', 'H'],
     *         widths: [125, 50, 60, 150, 200]
     *     }
     *
     * The encoded data will be:
     *
     *     [0, 125, 50, 0, 60, 150, 0, 200]
     *
     * `0` means the field has no user defined width. (i.e: `A`, `D`, `G`)
     * This is either because the column is hidden, or not displayable in this
     * list view.
     *
     * @param {Object} decodedData The decoded data.
     * @return {Array} The encoded data.
     * @private
     */
    _encodeCacheWidthData: function(decodedData) {
        var encodedData = [];

        var fieldList = this._appendFieldsToAllListViewsFieldList();
        var visibleIndex = 0;
        _.each(fieldList, function(fieldName) {
            var value = 0;
            if (_.contains(decodedData.visible, fieldName)) {
                value = decodedData.widths[visibleIndex++];
            }
            encodedData.push(value);
        });
        return encodedData;
    },

    /**
     * Takes the minimized value stored in the cache and decodes it to make it
     * more readable and easier to manipulate.
     *
     * If the field's storage entry is:
     *
     *     ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']
     *
     * And the encoded data is:
     *
     *     [0, 125, 50, 0, 60, 150, 0, 200]
     *
     * The decoded data will be:
     *
     *     {
     *         visible: ['B', 'C', 'F', 'H'],
     *         widths: [125, 50, 60, 150, 200]
     *     }
     *
     * - `visible` contains the list of visible fields,
     * - `widths` is the widths of fields, indexes whose value is `0` are
     * skipped (fields not being displayed).
     *
     * @param {Array} encodedData The minimized data.
     * @return {Object} The decoded data.
     * @private
     */
    _decodeCacheWidthData: function(encodedData) {
        var decodedData = {
            'visible': [],
            'widths': []
        };

        var fieldList = this._appendFieldsToAllListViewsFieldList();
        _.each(_.pluck(this._fields.visible, 'name'), function(fieldName) {
            var index = _.indexOf(fieldList, fieldName);
            var width = encodedData[index] || 0;
            decodedData.visible.push(fieldName);
            decodedData.widths.push(width);
        });
        return decodedData;
    },

    /**
     * Append the list of fields defined in the metadata that are missing in the
     * field storage cache entry.
     *
     * We initially used `_.uniq` to guarantee the unicity of fields. It appears
     * that this `underscore` method is slow, unlike `Lo-Dash` one. Meanwhile a
     * potential migration to Lo-Dash, it is faster to build an object whom keys
     * are field names.
     *
     * @return {Array} The list of all the fields that are displayable in listå
     * views of this module.
     * @private
     */
    _appendFieldsToAllListViewsFieldList: function() {
        this._allListViewsFieldList = app.user.lastState.get(this._allListViewsFieldListKey) || [];
        var obj = {};
        _.each(this._allListViewsFieldList, function(fieldName) {
            obj[fieldName] = fieldName;
        });

        _.each(this.meta.panels, function(panel) {
            _.each(panel.fields, function(fieldMeta, i) {
                obj[fieldMeta.name] = fieldMeta.name;
            }, this);
        }, this);
        this._allListViewsFieldList = _.keys(obj);
        app.user.lastState.set(this._allListViewsFieldListKey, this._allListViewsFieldList);
        return this._allListViewsFieldList;
    },

    /**
     * Converts the old localStorage data for fields visibility to the new
     * decoded format.
     *
     * {@link View.FlexListView#_encodeCacheData To see how the new format looks like.}
     *
     *     @example Only visible fields used to be stored. Example of data stored:
     *     <pre><code>
     *     [
     *         'B', 'C', 'F', 'H'
     *     ]
     *     </code></pre>
     *     If the list of fields defined in the metadata was:
     *     <pre><code>
     *     [
     *         'E', 'C', 'B', 'F', 'H'
     *     ]
     *     </code></pre>
     *     The decoded data would be:
     *     <pre><code>
     *     {
     *         visible: ['B', 'C', 'F', 'H'],
     *         hidden: ['E'],
     *         position: ['E', 'C', 'B', 'F', 'H']
     *     }
     *     </code></pre>
     *
     * @return {Array} The data converted to the new decoded format.
     * @private
     */
    _convertFromOldFormat: function(visibleFieldList) {
        var thisViewFieldList = _.reduce(_.map(this.meta.panels, function(panel) {
            return _.pluck(panel.fields, 'name');
        }), function(memo, field) {
            return memo.concat(field);
        }, []);

        var decoded = {
            visible: [],
            hidden: [],
            position: []
        };
        _.each(thisViewFieldList, function(fieldName, i) {
            if (_.contains(visibleFieldList, fieldName)) {
                decoded.visible.push(fieldName);
            } else {
                decoded.hidden.push(fieldName);
            }
            decoded.position.push(fieldName);
        });
        app.user.lastState.set(this._thisListViewFieldListKey, this._encodeCacheData(decoded));
        return decoded;
    },

    /**
     * Save to the cache the current order of fields, and their visible state.
     *
     *     @example Example of value stored in the cache:
     *     <pre><code>
     *     [
     *         ['A', 'B', 'D', 'C'],
     *         [0, 1, 0, 1]
     *     ]
     *     </code></pre>
     * Means the current order is `ABDC`, and only `B` and `C` are visible
     * fields.
     */
    saveCurrentState: function() {
        if (!this._thisListViewFieldListKey) {
            return;
        }
        var allFields = _.pluck(this._fields.all, 'name'),
            visibleFields = _.pluck(this._fields.visible, 'name');
        var decoded = {
            visible: visibleFields,
            hidden: _.difference(allFields, visibleFields),
            position: allFields
        };
        app.user.lastState.set(this._thisListViewFieldListKey, this._encodeCacheData(decoded));
        this._thisListViewFieldList = this._getFieldsLastState();
    },

    /**
     * Add actions to left and right columns
     */
    addActions: function() {
        var meta = this.meta;
        if (_.isObject(meta.selection)) {
            this.isSearchAndSelectAction = meta.selection.isSearchAndSelectAction;
            switch (meta.selection.type) {
                case 'single':
                    this.addSingleSelectionAction();
                    break;
                case 'multi':
                    this.addMultiSelectionAction();
                    break;
                default:
                    break;
            }
        }
        if (meta && _.isObject(meta.rowactions)) {
            this.addRowActions();
        }
    },
    /**
     * Add single selection field to left column
     */
    addSingleSelectionAction: function () {
        var _generateMeta = function (name, label) {
            return {
                'type': 'selection',
                'name': name,
                'sortable': false,
                'label': label || ''
            };
        };
        var def = this.meta.selection;
        this.leftColumns.push(_generateMeta(def.name || this.module + '_select', def.label));
    },
    /**
     * Add multi selection field to left column
     */
    addMultiSelectionAction: function() {
        var _generateMeta = function(buttons, disableSelectAllAlert) {
            return {
                'type': 'fieldset',
                'fields': [
                    {
                        'type': 'actionmenu',
                        'buttons': buttons || [],
                        'disable_select_all_alert': !!disableSelectAllAlert
                    }
                ],
                'value': false,
                'sortable': false
            };
        };
        var buttons = this.meta.selection.actions;
        var disableSelectAllAlert = !!this.meta.selection.disable_select_all_alert;
        this.leftColumns.push(_generateMeta(buttons, disableSelectAllAlert));
    },
    /**
     * Add fieldset of rowactions to the right column
     */
    addRowActions: function() {
        var _generateMeta = function(label, css_class, buttons) {
            return {
                'type': 'fieldset',
                'fields': [
                    {
                        'type': 'rowactions',
                        'label': label || '',
                        'css_class': css_class,
                        'buttons': buttons || []
                    }
                ],
                'value': false,
                'sortable': false
            };
        };
        var def = this.meta.rowactions;
        this.rightColumns.push(_generateMeta(def.label, def.css_class, def.actions));
    },
    /**
     * Decorate a row in the list that is being shown in Preview
     * @param model Model for row to be decorated.  Pass a falsy value to clear decoration.
     */
    decorateRow: function (model) {
        // If there are drawers, make sure we're updating only list views on active drawer.
        if (_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)) {
            this._previewed = model;
            this.$('.btn.rowaction.active').removeClass('active').attr('aria-pressed', false);
            this.$('tr.highlighted').removeClass('highlighted current');
            if (model) {
                var rowName = model.module + "_" + model.id;
                var curr = this.$('tr[name="' + rowName + '"]');
                curr.addClass('current highlighted');
                this.$('tr.current .btn.rowaction[data-event="list:preview:fire"]')
                    .addClass('active')
                    .attr('aria-pressed', true);
            }
        }
    },

    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        this.colSpan = this._fields.visible.length || 0;
        if (this.leftColumns.length) {
            this.colSpan++;
        }
        if (this.rightColumns.length) {
            this.colSpan++;
        }
        if (this.colSpan < 2) {
            this.colSpan = null;
        }
        this._super('_renderHtml');

        if (this.leftColumns.length) {
            this.$el.addClass('left-actions');
        }
        if (this.rightColumns.length) {
            this.$el.addClass('right-actions');
        }

        var displayWidthSetting = this._thisListViewFieldSizes ||
            !_.isUndefined(app.user.lastState.get(this._thisListViewFieldSizesKey));
        var displayOrderSetting = false;
        if (this._thisListViewFieldList) {
            var customOrder = _.union(this._thisListViewFieldList.position, this._defaultFieldOrder);
            displayOrderSetting = !_.isEqual(customOrder, this._defaultFieldOrder);
        }
        this._toggleSettings('widths', displayWidthSetting);
        this._toggleSettings('order', displayOrderSetting);

        this.resize();
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        // FIXME SC-3484 Testing `this.closestComponent('sidebar')` is required
        // to make unit tests pass.
        if (this.closestComponent('sidebar') && !(app.drawer.count())) {
                this._setHelperScrollBar();
        }
    },

    /**
     * Sets up the helper scrollbar.
     *
     * It first sets the helper `scrollWidth` and `width`. Then it adds
     * listeners on the spy and helper scrollbars to make them follow each
     * other.
     * Then it adds a listener on the vertical scrolling to watch when the
     * bottom of the table is visible, and when it is, to hide the helper since
     * the scrollbar at the bottom of the table is visible.
     *
     * @private
     */
    _setHelperScrollBar: function() {
        /**
         * The 'helper' scrollbar is the horizontal scrollbar fixed to the
         * bottom of the screen.
         *
         * @property {jQuery}
         */
        this.$helper = this.$('[data-scroll-spy]');

        // if no helper was found, just go about our way and not produce any javascript errors
        if (this.$helper.length === 0) {
            return;
        }

        /**
         * The `spy` is the list container element.
         *
         * @property {jQuery}
         */
        this.$spy = this.$('.' + this.$helper.data('scrollSpy'));

        this.$helper.find('div').width(this.$spy.get(0).scrollWidth);
        this._updateHelperWidth();
        this.listenTo(this.closestComponent('sidebar'), 'sidebar:toggle', _.bind(this._updateHelperWidth, this));

        this.$helper.on('scroll.' + this.cid, _.bind(function() {
            this.$spy.scrollLeft(this.$helper.scrollLeft());
        }, this));
        this.$spy.on('scroll.' + this.cid, _.bind(function() {
            this.$helper.scrollLeft(this.$spy.scrollLeft());
        }, this));

        // `#content` is the scrolling element in responsive view.
        $('#content').on('scroll.' + this.cid, _.bind(function() {
            this._toggleScrollHelper();
        }, this));

        // `.main-pane` is the scrolling element in desktop view.
        $('.main-pane').on('scroll.' + this.cid, _.bind(function() {
            this._toggleScrollHelper();
        }, this));
    },

    /**
     * Toggles the helper scroll bar.
     *
     * If the spy's `width` is greater than its `scrollWidth` (the screen is
     * large enough) OR if the footer is higher than the table (the table is not
     * visible on the screen), we hide the helper scrollbar.
     * Also, we hide it if the bottom of the table is higher than the footer
     * (the natural scroll bar is present).
     *
     * @private
     */
    _toggleScrollHelper: function() {
        if (this.$spy.get(0).scrollWidth <= this.$spy.width() ||
            this.$('tbody').offset().top + this.$helper.height() > $('footer').offset().top
        ) {
            this.$helper.toggle(false);
            return;
        }

        this.$helper.toggle(!(this.$('.scrollbar-landmark').offset().top < $('footer').offset().top));
        if (this.$helper.css('display') !== 'none') {
            this.$helper.scrollLeft(this.$spy.scrollLeft());
        }
    },

    /**
     * Updates the helper scrollbar width depending on whether dashboard is
     * open or not.
     *
     * @private
     */
    _updateHelperWidth: function() {
        if (this.$helper.length === 0) {
            return;
        }
        this.$helper.toggleClass('dash-collapsed', !$('.side.sidebar-content').is(':visible'));
    },

    /**
     * Saves the current field widths in {@link #_thisListViewFieldSizes}.
     *
     * If the stickiness is enabled, it also saves the widths into the cache,
     * so that the next time the view is loaded, the user retrieves his
     * preferred widths.
     *
     * Example of a value stored in the cache:
     *
     *     [125, 0, 52, 115, 0, 0, 51]
     *
     * Represents the current widths of fields `ABCDEF`, but no width has been
     * defined for fields `B`, `E` and `F` (because they were hidden or not
     * displayable).
     *
     * @param {Array} columns The widths of the current visible fields.
     */
    saveCurrentWidths: function(columns) {
        // Needed in order to fix the scroll helper whenever the widths change.
        this.resize();
        if (!this._thisListViewFieldListKey) {
            return;
        }
        var visibleFields = _.pluck(this._fields.visible, 'name');
        var decoded = {
            visible: visibleFields,
            widths: columns
        };
        var encoded = this._encodeCacheWidthData(decoded);
        this._toggleSettings('widths', true);

        /**
         * The list of user defined column widths for this specific view.
         *
         * @property {Array}
         * @protected
         */
        this._thisListViewFieldSizes = encoded;

        if (this._thisListViewFieldSizesKey) {
            app.user.lastState.set(this._thisListViewFieldSizesKey, encoded);
        }
    },

    /**
     * Resets the column widths to the default settings.
     *
     * If the stickiness is enabled, it also removes the entry from the cache.
     */
    resetColumnWidths: function() {
        this._thisListViewFieldSizes = null;
        if (this._thisListViewFieldSizesKey) {
            app.user.lastState.remove(this._thisListViewFieldSizesKey);
        }
        if (!this.disposed) {
            this.render();
            this._toggleSettings('widths', false);
        }
    },

    /**
     * Resets the column order to the default settings.
     */
    resetColumnOrder: function() {
        var fields = _.flatten(_.pluck(this.meta.panels, 'fields'));
        this._fields = this._createCatalog(fields);
        this.saveCurrentState();
        if (this.disposed) {
            return;
        }
        this.render();
    },

    /**
     * Shows, or hides, the reset setting option from the settings dropdown.
     *
     * @param {string} category The setting to show or hide.
     * @param {boolean} show `true` to show it, `false` to hide it.
     * @private
     */
    _toggleSettings: function(category, show) {
        this.$('li[data-settings-li=' + category + ']').toggle(show);
    },

    /**
     * Gets the list of widths for each visible field in the list view.
     *
     * If the stickiness is enabled, it will look for the entry in the cache.
     *
     * @return {Array} The list of widths if found, `undefined` otherwise.
     */
    getCacheWidths: function() {
        var encodedData = this._thisListViewFieldSizes ||
            app.user.lastState.get(this._thisListViewFieldSizesKey);
        if (!encodedData) {
            return;
        }
        return this._decodeCacheWidthData(encodedData).widths;
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        $('#content, .main-pane').off('scroll.' + this.cid);
        $(this).parents('.main-pane').off('scroll.right-actions');
        this.$('.flex-list-view .actions').trigger('resetDropdownDelegate.right-actions');
        $(window).off('resize.flexlist-' + this.cid);

        if (this.$helper) {
            this.$helper.off('scroll.' + this.cid);
        }
        if (this.$spy) {
            this.$spy.off('scroll.' + this.cid);
        }

        this._super('unbind');
    },

    bindResize: function() {
        $(window).on("resize.flexlist-" + this.cid, _.bind(this.resize, this));
    },

    /**
     * Temporarily overwrites the css from the .scroll-width class so that
     * row field dropdown menues aren't clipped by overflow-x property.
     */
    scrollLock: function(lock) {
        var $content = this.$('.flex-list-view-content');
        if (lock) {
            $content.css({'overflow-y': 'visible', 'overflow-x': 'hidden'});
        } else {
            $content.removeAttr('style');
        }
    },

    /**
     * Updates the class of this flex list as scrollable or not, and
     * adjusts/toggles the scroll helper.
     */
    resize: function() {
        if (this.disposed) {
            return;
        }
        var $content = this.$('.flex-list-view-content');
        if (!$content.length) {
            return;
        }
        var toggle = $content.get(0).scrollWidth > $content.width() + 1;
        this.$el.toggleClass('scroll-width', toggle);

        if (this.$helper && this.$helper.length > 0) {
            this.$helper.find('div').width(this.$spy.get(0).scrollWidth);
            this._toggleScrollHelper();
        }
    }
})
