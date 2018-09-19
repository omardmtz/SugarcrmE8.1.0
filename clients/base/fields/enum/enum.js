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
 * @class View.Fields.Base.EnumField
 * @alias SUGAR.App.view.fields.BaseEnumField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input.select2',

    /**
     * HTML tag of the append value checkbox.
     *
     * @property {String}
     */
    appendValueTag: 'input[name=append_value]',

    /**
     * Whether this field is in the midst of fetching options for its dropdown.
     *
     * @type {Boolean}
     */
    isFetchingOptions: false,

    /**
     * The dropdown elements.
     *
     *     @example The format of the object is:
     *     ```
     *     {
     *         "key1": "value1",
     *         "key2": "value2",
     *         "key3": "value3"
     *     }
     *     ```
     *
     * @type {Object}
     */
    items: null,

    /**
     * The keys of dropdown elements and their index in the related
     * `app_list_keys` array.
     *
     *     @example The format of the object is:
     *     ```
     *     {
     *         "key1": 1,
     *         "key2": 0,
     *         "key3": 2
     *     }
     *     ```
     *
     * If no `app_list_keys` entry, or if elements in the expected order, the
     * object will be empty.
     *
     * @type {Object}
     */
    _keysOrder: null,

    /**
     * @inheritdoc
     */
    initialize: function() {
        this._super('initialize', arguments);

        //Reset the availible options based on the user's access and the model's values
        if (_.isString(this.def.options)) {
            var self = this;

            this.listenTo(this.model, "sync", function(model){
                var options = app.lang.getAppListStrings(self.def.options);
                if (options) {
                    self.items = self._filterOptions(options);
                }
            });
        }
    },

    /**
     * @inheritdoc
     *
     * Returns the direction of the field depending on the nature of the first
     * option when the language direction is `rtl`.
     */
    direction: function() {
        if (_.isEmpty(this.items) || app.lang.direction !== 'rtl') {
            return;
        }

        var firstOption = _.values(this.items)[0];
        return app.utils.isDirectionRTL(firstOption) ? 'rtl' : 'ltr';
    },

    /**
     * Bind the additional keydown handler on select2
     * search element (affected by version 3.4.3).
     *
     * Invoked from {@link app.plugins.Editable}.
     * @param {Function} callback Callback function for keydown.
     */
    bindKeyDown: function(callback) {
        var $el = this.$(this.fieldTag);
        if ($el) {
            $el.on('keydown.record', {field: this}, callback);
            var plugin = $el.data('select2');
            if (plugin) {
                if (plugin.focusser) {
                    plugin.focusser.on('keydown.record', {field: this}, callback);
                }
                plugin.search.on('keydown.record', {field: this}, callback);
            }
        }
    },

    /**
     * Unbind the additional keydown handler.
     *
     * Invoked from {@link app.plugins.Editable}.
     * @param {Function} callback Callback function for keydown.
     */
    unbindKeyDown: function(callback) {
        if (this.$el) {
            var $el = this.$(this.fieldTag);
            if ($el) {
                $el.off('keydown.record', callback);
                var plugin = $el.data('select2');
                if (plugin) {
                    plugin.search.off('keydown.record', callback);
                }
            }
        }
    },

    /**
     * @override
     * @protected
     * @chainable
     */
    _render: function() {
        var self = this;
        if (!this.items || _.isEmpty(this.items)) {
            this.loadEnumOptions(false, function() {
                self.isFetchingOptions = false;
                //Re-render widget since we have fresh options list
                if(!this.disposed){
                    this.render();
                }
            });
            if (this.isFetchingOptions){
                // Set loading message in place of empty DIV while options are loaded via API
                this.$el.html('<div class="select2-loading">' + app.lang.get('LBL_LOADING') + '</div>');
                return this;
            }
        }
        //Use blank value label for blank values on multiselects
        if (this.def.isMultiSelect && !_.isUndefined(this.items['']) && this.items[''] === '') {
            var obj = {};
            _.each(this.items, function(value, key) {
               // Only work on key => value pairs that are not both blank
               if (key !== '' && value !== '') {
                   obj[key] = value;
               }
            }, this);
            this.items = obj;
        }
        this.items = this._filterOptions(this.items);
        var optionsKeys = _.isObject(this.items) ? _.keys(this.items) : [],
            defaultValue = this._checkForDefaultValue(this.model.get(this.name), optionsKeys);

        app.view.Field.prototype._render.call(this);
        // if displaying the noaccess template, just exit the method
        if (this.tplName == 'noaccess') {
            return this;
        }
        var select2Options = this.getSelect2Options(optionsKeys);
        var $el = this.$(this.fieldTag);
        //FIXME remove check for tplName SC-2608
        if (this.tplName === 'edit' || this.tplName === 'list-edit' || this.tplName === 'massupdate') {
            $el.select2(select2Options);
            var plugin = $el.data('select2');

            if (plugin && this.dir) {
                plugin.container.attr('dir', this.dir);
                plugin.results.attr('dir', this.dir);
            }

            if (plugin && plugin.focusser) {
                plugin.focusser.on('select2-focus', _.bind(_.debounce(this.handleFocus, 0), this));
            }
            $el.on('change', function(ev) {
                var value = ev.val;
                if (_.isUndefined(value)) {
                    return;
                }
                if (self.model) {
                    self.model.set(self.name, self.unformat(value));
                }
            });
            if (this.def.isMultiSelect && this.def.ordered) {
                $el.select2('container').find('ul.select2-choices').sortable({
                    containment: 'parent',
                    start: function() {
                        $el.select2('onSortStart');
                    },
                    update: function() {
                        $el.select2('onSortEnd');
                    }
                });
            }
        } else if (this.tplName === 'disabled') {
            $el.select2(select2Options);
            $el.select2('disable');
        }
        //Setup selected value in Select2 widget
        if (!_.isUndefined(this.value)) {
            // To make pills load properly when autoselecting a string val
            // from a list val needs to be an array
            if (!_.isArray(this.value)) {
                this.value = [this.value];
            }
            // Trigger the `change` event only if we automatically set the
            // default value.
            $el.select2('val', this.value, !!defaultValue);
        }
        return this;
    },

    /**
     * Sets the model value to the default value if required
     * @private
     */
    _checkForDefaultValue: function(currentValue, optionsKeys){

        // Javascript keys function returns strings even if keys are numbers.  The parameter optionsKeys
        // is obtained by _.keys() operation on an object. Even if the object keys were numeric originally,
        // optionsKeys will be an array of strings. Hence we need to cast currentValue to a string
        // for comparison sake.
        if ((typeof currentValue !== 'undefined') && (currentValue !== null)) {
            currentValue = currentValue.toString();
        }

        var action = this.action || this.view.action;
        //After rendering the dropdown, the selected value should be the value set in the model,
        //or the default value. The default value fallbacks to the first option if no other is selected
        //or the selected value is not available in the list of items,
        //if the user has write access to the model for the field we are currently on.
        //This should be done only if available options are loaded, otherwise the value in model will be reset to
        //default even if it's in available options but they are not loaded yet
        if (!this.def.isMultiSelect
            && !_.isEmpty(this.items)
            && !(this.model.has(this.name) && optionsKeys.indexOf(currentValue) > -1)
            && app.acl.hasAccessToModel('write', this.model, this.name)
            && (action == 'edit' || action == 'create')
        ) {
            var defaultValue = this._getDefaultOption(optionsKeys);
            //Forecasting uses backbone model (not bean) for custom enums so we have to check here
            if (_.isFunction(this.model.setDefault)) {
                this.model.setDefault(this.name, defaultValue);
            }
        }
    },

    /**
     * Called when focus on inline editing
     */
    focus: function () {
        //We must prevent focus for multi select otherwise when inline editing the dropdown is opened and it is
        //impossible to click on a pill `x` icon in order to remove an item
        if(this.action !== 'disabled' && !this.def.isMultiSelect) {
            this.$(this.fieldTag).select2('open');
        }
    },

    /**
     * Load the options for this field and pass them to callback function.  May be asynchronous.
     * @param {Boolean} fetch (optional) Force use of Enum API to load options.
     * @param {Function} callback (optional) Called when enum options are available.
     */
    loadEnumOptions: function(fetch, callback, error) {
        var self = this;
        var _module = this.getLoadEnumOptionsModule();
        var _itemsKey = 'cache:' + _module + ':' + this.name + ':items';

        this.items = this.def.options || this.context.get(_itemsKey);

        fetch = fetch || false;

        if (fetch || !this.items) {
            this.isFetchingOptions = true;
            var _key = 'request:' + _module + ':' + this.name;
            //if previous request is existed, ignore the duplicate request
            if (this.context.get(_key)) {
                var request = this.context.get(_key);
                request.xhr.done(_.bind(function(o) {
                    if (this.items !== o) {
                        this.items = o;
                        callback.call(this);
                    }
                }, this));
            } else {
                var request = app.api.enumOptions(_module, self.name, {
                    success: function(o) {
                        if(self.disposed) { return; }
                        if (self.items !== o) {
                            self.items = o;
                            self.context.set(_itemsKey, self.items);
                        }
                    },
                    error: function(e) {
                        if (self.disposed) {
                            return;
                        }

                        if (error) {
                            error(e);
                        }

                        // Continue to use Sugar7's default error handler.
                        if (_.isFunction(app.api.defaultErrorHandler)) {
                            app.api.defaultErrorHandler(e);
                        }

                        self.items = {'': app.lang.get('LBL_NO_DATA', self.module)};
                    },
                    complete: function() {
                        if (!self.disposed) {
                            self.context.unset(_key);
                            callback.call(self);
                        }
                    }
                });
                this.context.set(_key, request);
            }
        } else if (_.isString(this.items)) {
            this.items = app.lang.getAppListStrings(this.items);
        }
    },

    /**
     * Allow overriding of what module is used for loading the enum options
     *
     * @return {string}
     */
    getLoadEnumOptionsModule: function() {
        return this.module;
    },

    /**
     * Helper function for generating Select2 options for this enum
     * @param {Array} optionsKeys Set of option keys that will be loaded into Select2 widget
     * @return {Object} Select2 options, refer to Select2 documentation for what each option means
     */
    getSelect2Options: function(optionsKeys){
        var select2Options = {};
        select2Options.allowClear = _.indexOf(optionsKeys, "") >= 0;
        select2Options.transformVal = _.identity;

        /* From http://ivaynberg.github.com/select2/#documentation:
         * Initial value that is selected if no other selection is made
         */
        if(!this.def.isMultiSelect) {
            select2Options.placeholder = app.lang.get("LBL_SEARCH_SELECT");
        }

        /* From http://ivaynberg.github.com/select2/#documentation:
         * "Calculate the width of the container div to the source element"
         */
        select2Options.width = this.def.enum_width ? this.def.enum_width : '100%';

        /* Because the select2 dropdown is appended to <body>, we need to be able
         * to pass a classname to the constructor to allow for custom styling
         */
        select2Options.dropdownCssClass = this.def.dropdown_class ? this.def.dropdown_class : '';

        /* To get the Select2 multi-select pills to have our styling, we need to be able
         * to either pass a classname to the constructor to allow for custom styling
         * or set the 'select2-choices-pills-close' if the isMultiSelect option is set in def
         */
        select2Options.containerCssClass = this.def.container_class ? this.def.container_class : (this.def.isMultiSelect ? 'select2-choices-pills-close' : '');

        /* Because the select2 dropdown is calculated at render to be as wide as container
         * to make it differ the dropdownCss.width must be set (i.e.,100%,auto)
         */
        if (this.def.dropdown_width) {
            select2Options.dropdownCss = { width: this.def.dropdown_width };
        }

        /* All select2 dropdowns should only show the search bar for fields with 7 or more values,
         * this adds the ability to specify that threshold in metadata.
         */
        select2Options.minimumResultsForSearch = this.def.searchBarThreshold ? this.def.searchBarThreshold : 7;

        /* If is multi-select, set multiple option on Select2 widget.
         */
        if (this.def.isMultiSelect) {
            select2Options.multiple = true;
        }

        /* If we need to define a custom value separator
         */
        select2Options.separator = this.def.separator || ',';
        if (this.def.separator) {
            select2Options.tokenSeparators = [this.def.separator];
        }

        select2Options.initSelection = _.bind(this._initSelection, this);
        select2Options.query = _.bind(this._query, this);
        select2Options.sortResults = _.bind(this._sortResults, this);

        return select2Options;
    },

    /**
     * Set the option selection during select2 initialization.
     * Also used during drag/drop in multiselects.
     * @param {Selector} $ele Select2 element selector
     * @param {Function} callback Select2 data callback
     * @private
     */
    _initSelection: function($ele, callback){
        var data = [];
        var options = _.isString(this.items) ? app.lang.getAppListStrings(this.items) : this.items;
        options = this.items = this._filterOptions(options);
        var values = $ele.val();
        if (this.def.isMultiSelect) {
            values = values.split(this.def.separator || ',');
        }
        _.each(_.pick(options, values), function(value, key){
            data.push({id: key, text: value});
        }, this);
        if(data.length === 1){
            callback(data[0]);
        } else {
            callback(data);
        }
    },

    /**
     * Returns dropdown list options which can be used for editing
     *
     * @param {Object} Dropdown list options
     * @return {Object}
     * @private
     */
    _filterOptions: function (options) {
        var currentValue,
            syncedVal,
            newOptions = {},
            filter = app.metadata.getEditableDropdownFilter(this.def.options);

        /**
         * Flag to indicate that the options have already been filtered and do
         * not need to be sorted.
         *
         * @type {boolean}
         */
        this.isFiltered = !_.isEmpty(filter);

        if (!this.isFiltered) {
            return options;
        }

        if (!_.contains(this.view.plugins, "Editable")) {
            return options;
        }
        //Force the current value(s) into the availible options
        syncedVal = this.model.getSynced();
        currentValue = _.isUndefined(syncedVal[this.name]) ? this.model.get(this.name) : syncedVal[this.name];
        if (_.isString(currentValue)) {
            currentValue = [currentValue];
        }

        var currentIndex = {};

        // add current values to the index in case if current model is saved to the server in order to prevent data loss
        if (!this.model.isNew()) {
            _.each(currentValue, function(value) {
                currentIndex[value] = true;
            });
        }

        //Now remove the disabled options
        if (!this._keysOrder) {
            this._keysOrder = {};
        }
        _.each(filter, function(val, index) {
            var key = val[0],
                visible = val[1];
            if ((visible || key in currentIndex) && !_.isUndefined(options[key]) && options[key] !== false) {
                this._keysOrder[key] = index;
                newOptions[key] = options[key];
            }
        }, this);

        return newOptions;
    },

    /**
     * Adapted from eachOptions helper in hbt-helpers.js
     * Select2 callback used for loading the Select2 widget option list
     * @param {Object} query Select2 query object
     * @private
     */
    _query: function(query){
        var options = _.isString(this.items) ? app.lang.getAppListStrings(this.items) : this.items;
        var data = {
            results: [],
            // only show one "page" of results
            more: false
        };
        if (_.isObject(options)) {
            _.each(options, function(element, index) {
                var text = "" + element;
                //additionally filter results based on query term
                if(query.matcher(query.term, text)){
                    data.results.push({id: index, text: text});
                }
            });
        } else {
            options = null;
        }
        query.callback(data);

        // Special hack for Firefox bug http://stackoverflow.com/questions/13040897/firefox-scrollbar-resets-incorrectly
        $(this.$(this.fieldTag).data('select2').results[0]).scrollTop(1).scrollTop(0);
    },

    /**
     * Sort the dropdown items.
     *
     * - If `def.sort_alpha` is `true`, return the dropdown items sorted
     * alphabetically.
     * - If {@link Core.Language#getAppListKeys} is defined for
     * `this.def.options`, return the items in this order.
     * - Otherwise, fall back to the default behavior and just return the
     * `results`.
     *
     * This method is the implementation of the select2 `sortResults` option.
     * See {@link http://ivaynberg.github.io/select2/ official documentation}.
     *
     * @param {Array} results The list of results `{id: *, text: *}.`
     * @param {jQuery} container jQuery wrapper of the node that should contain
     *  the representation of the result.
     * @param {Object} query The query object used to request this set of
     *  results.
     * @return {Array} The list of results {id: *, text: *} sorted.
     * @private
     */
    _sortResults: function(results, container, query) {
        var sortedResults;

        if (this.def.sort_alpha) {
            sortedResults = _.sortBy(results, function(item) {
                return item.text;
            });
            return sortedResults;
        }

        // Do not sort if options have already been filtered; or if the key ordering is empty,
        // we should not change the order as the options were generated by a function.
        if (this.isFiltered && _.isEmpty(this._keysOrder)) {
            return results;
        }

        this._setupKeysOrder();
        // If the key ordering is empty, we should not change the order as the options were generated by a function.
        if (_.isEmpty(this._keysOrder)) {
            return results;
        }

        return _.sortBy(results, function(item) {
            return this._keysOrder[item.id];
        }, this);
    },

    _setupKeysOrder: function() {
        var keys;
        var orderedKeys;
        var filteredOrderedKeys;
        var visibilityGrid;

        if (!_.isEmpty(this._keysOrder)) {
            return;
        }

        visibilityGrid = this.def.visibility_grid || {};

        var hasTrigger = visibilityGrid.values && visibilityGrid.trigger && this.model.has(visibilityGrid.trigger);

        // in case we have visibility grid, build keys according to its order
        if (hasTrigger) {
            var trigger = this.model.get(visibilityGrid.trigger);
            var _gridKeysOrder = visibilityGrid.values[trigger];

            if (_gridKeysOrder) {
                this._keysOrder = _.reduce(_gridKeysOrder, function(memo, value, index) {
                    memo[value] = index;
                    return memo;
                }, {});

                return;
            }
        } else {
            keys = _.keys(this.items);
            this._keysOrder = {};

            orderedKeys = _.map(app.lang.getAppListKeys(this.def.options), function(appListKey) {
                return appListKey.toString();
            });

            filteredOrderedKeys = _.intersection(orderedKeys, keys);

            if (!_.isEqual(filteredOrderedKeys, keys)) {
                _.each(filteredOrderedKeys, function(key, index) {
                    return this._keysOrder[key] = index;
                }, this);
            }
        }
    },

    /**
     * Helper function for retrieving the default value for the selection
     * @param {Array} optionsKeys Set of option keys that will be loaded into Select2 widget
     * @return {string} The default value
     */
    _getDefaultOption: function (optionsKeys) {
        //  Return the default if it's available in the definition.
        if (this.def && (!_.isEmptyValue(this.def.default)) ) {
            return this.def.default;
        } else {
            this._setupKeysOrder();
            var invertedKeysOrder = _.invert(this._keysOrder);
            //Check if we have a keys order, and that the sets of keys match
            if (!_.isEmpty(invertedKeysOrder) && _.isEmpty(_.difference(_.keys(this._keysOrder), optionsKeys))) {
                return _.first(invertedKeysOrder);
            }
            return _.first(optionsKeys);
        }
    },

    /**
     *  Convert select2 value into model appropriate value for sync
     *
     * @param value Value from select2 widget
     * @return {String|Array} Unformatted value as String or String Array
     */
    unformat: function(value) {
        if (this.def.isMultiSelect && _.isArray(value)) {
            var possibleKeys = _.keys(this.items);
            if (!this.def.ordered) {
                // if it's not ordered, i.e. sortable, force order
                value = _.intersection(possibleKeys, value);
            } else {
                // no need to force order, just keep valid keys
                value = _.intersection(value, possibleKeys);
            }
            return value;
        }

        if (this.def.isMultiSelect && _.isNull(value)) {
            return [];  // Returning value that is null equivalent to server.  Backbone.js won't sync attributes with null values.
        } else {
            return value;
        }
    },

    /**
     * Convert server value into one appropriate for display in widget
     *
     * @param value
     * @return {Array} Value for select2 widget as String Array
     */
    format: function(value){
        if (this.def.isMultiSelect && _.isArray(value) && _.indexOf(value, '') > -1) {
            value = _.clone(value);

            // Delete empty values from the select list
            delete value[''];
        }
        if(this.def.isMultiSelect && _.isString(value)){
            return this.convertMultiSelectDefaultString(value);
        } else {
            return value;
        }
    },

    /**
     * Converts multiselect default strings into array of option keys for template
     * @param {String} defaultString string of the format "^option1^,^option2^,^option3^"
     * @return {Array} of the format ["option1","option2","option3"]
     */
    convertMultiSelectDefaultString: function(defaultString) {
        var result = defaultString.split(",");
        _.each(result, function(value, key) {
            // Remove empty values in the selection
            if (value !== '^^') {
                result[key] = value.replace(/\^/g,"");
            }
        });
        return result;
    },

    /**
     * @inheritdoc
     * Avoid rendering process on select2 change in order to keep focus.
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:' + this.name, function() {
                if (_.isEmpty(this.$(this.fieldTag).data('select2'))) {
                    this.render();
                } else {
                    this.$(this.fieldTag).select2('val', this.format(this.model.get(this.name)));
                }
            }, this);
        }
    },

    /**
     * Override to remove default DOM change listener, we use Select2 events instead
     * Binds append value checkbox change for massupdate.
     *
     * @override
     */
    bindDomChange: function() {
        var $el = this.$(this.appendValueTag);
        if ($el.length) {
            $el.on('change', _.bind(function() {
                this.appendValue = $el.prop('checked');
                //FIXME: Should use true booleans (SC-2828)
                this.model.set(this.name + '_replace', this.appendValue ? '1' : '0');
            }, this));
        }

    },

    /**
     * @override
     */
    unbindDom: function() {
        this.$(this.appendValueTag).off();
        this.$(this.fieldTag).select2('destroy');
        this._super('unbindDom');
    },

    /**
     * @override
     */
    unbindData: function() {
        var _key = 'request:' + this.module + ':' + this.name;
        this.context.unset(_key);
        app.view.Field.prototype.unbindData.call(this);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.unbindKeyDown();
        this._super('_dispose');
    }
})
