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
 * This `FiltersCollection` is designed to be used like:
 *
 *     filters = app.data.createBeanCollection('Filters');
 *     filters.setModuleName('Accounts');
 *     filters.setFilterOptions(filterOptions); // Optional
 *     filters.load({
 *         success: _.bind(function() {
 *             // You can start using `filters.collection`
 *         }, this)
 *     });
 *
 * Even though {@link #load} and {@link #collection} are the recommended way to
 * manipulate the filters of a module, you can still use this `BeanCollection`
 * in a more traditional way.
 *
 *     filters = app.data.createBeanCollection('Filters');
 *     filters.fetch({
 *         filter: [
 *             {'created_by': app.user.id},
 *             {'module_name': module}
 *         ],
 *         success: _.bind(function() {
 *             // You can start using the collection
 *         }, this)
 *     });
 *
 * **Note** that in this case you're not taking advantage of the internal cache
 * memory.
 *
 * @class Data.Base.FiltersBeanCollection
 * @extends Data.BeanCollection
 */
({
    /**
     * This is the public and recommended API for manipulating the collection
     * of filters of a module.
     *
     * {@link #load} will create this collection provided that you set the
     * module name with {@link #setModuleName}.
     *
     * @property {Backbone.Collection|null}
     */
    collection: null,

    /**
     * The filter options used by {@link #load} when retrieving and building the
     * filter collection.
     *
     * See {@link #setFilterOptions}.
     *
     * @property {Object}
     * @private
     */
    _filterOptions: {},

    /**
     * Clears the filter options for the next call to {@link #load}.
     *
     * @chainable
     */
    clearFilterOptions: function() {
        this._filterOptions = {};
        return this;
    },

    /**
     * Maintains the filters collection in sorted order.
     *
     * User's filters will be positioned first in the collection, the
     * predefined filters will be positioned second.
     *
     * @param {Data.Bean} model1 The first model.
     * @param {Data.Bean} model2 The second model.
     * @return {Number} If `-1`, the first model goes before the second model,
     *   if `1`, the first model goes after the second model.
     */
    comparator: function(model1, model2) {
        if (model1.get('editable') === false && model2.get('editable') !== false) {
            return 1;
        }
        if (model1.get('editable') !== false && model2.get('editable') === false) {
            return -1;
        }
        if (this._getTranslatedFilterName(model1).toLowerCase() < this._getTranslatedFilterName(model2).toLowerCase()) {
            return -1;
        }
        return 1;
    },

    /**
     * Retrieves the list of filters of a module.
     *
     * The list includes predefined filters (defined in the metadata) as well as
     * the current user's filters.
     *
     * The collection is saved in memory the first time filters are retrieved,
     * so next calls to {@link #load} will just return the cached version.
     *
     * **Note:** Template filters are retrieved and saved in memory but are not
     * in the collection unless you pass the `initial_filter` options. See
     * {@link #setFilterOptions}. Only one template filter can be available at
     * a time.
     *
     * @param {Object} [options]
     * @param {Function} [options.success] Callback function to execute code
     *   once the filters are successfully retrieved.
     * @param {Function} [options.error] Callback function to execute code
     *   in case an error occured when retrieving filters.
     */
    load: function(options) {
        options = options || {};

        var module = this.moduleName,
            prototype = this._getPrototype(),
            collection;

        if (!module) {
            app.logger.error('This Filters collection has no module defined.');
            return;
        }

        if (this.collection) {
            this.collection.off();
        }

        // Make sure only one request is sent for each module.
        prototype._request = prototype._request || {};
        if (prototype._request[module]) {
            prototype._request[module].xhr.done(_.bind(function() {
                this._onSuccessCallback(options.success);
            }, this));
            return;
        }

        // Try to retrieve cached filters.
        prototype._cache = prototype._cache || {};
        if (prototype._cache[module]) {
            this._onSuccessCallback(options.success);
            return;
        }

        this._initFiltersModuleCache();

        // No cache found, retrieve filters.
        this._loadPredefinedFilters();

        var requestObj = {
            showAlerts: false,
            filter: [
                {'created_by': app.user.id},
                {'module_name': module}
            ],
            success: _.bind(function(models) {
                this._cacheFilters(models);
                this._onSuccessCallback(options.success);
            }, this),
            complete: function() {
                delete prototype._request[module];
            },
            error: function() {
                if (_.isFunction(options.error)) {
                    options.error();
                } else {
                    app.logger.error('Unable to get filters from the server.');
                }
            }
        };
        prototype._request[module] = prototype.fetch.call(this, requestObj);
    },

    /**
     * Defines the module name of the filter collection. This is mandatory in
     * order to use {@link #load}.
     *
     * @param {String} module The module name.
     * @chainable
     */
    setModuleName: function(module) {
        this.moduleName = module;
        return this;
    },

    /**
     * Defines the filter options used by {@link #load}.
     *
     * **Options supported:**
     *
     * - `{String} [initial_filter]` The id of the template filter.
     *
     * - `{String} [initial_filter_lang_modules]` The list of modules to look up
     *   the filter label string.
     *
     * - `{String} [filter_populate]` The populate hash in case we want to
     *   create a relate template filter on the fly.
     *
     * Filter options can be cleared with {@link #clearFilterOptions}.
     *
     * @param {String|Object} key The name of the option, or an hash of
     *   options.
     * @param {Mixed} [val] The default value for the `key` argument.
     * @chainable
     */
    setFilterOptions: function(key, val) {
        var options;
        if (_.isObject(key)) {
            options = key;
        } else {
            (options = {})[key] = val;
        }
        this._filterOptions = _.extend({}, this._filterOptions, options);
        return this;
    },

    /**
     * Saves the list of filters in memory.
     *
     * This allows us not to parse the metadata everytime in order to get the
     * predefined and template filters, and not to fetch the API everytime in
     * order to get the user's filters.
     *
     * @param {Mixed} models A list of filters (`Backbone.Collection` or
     *   `Object[]`) or one filter (`Data.Base.FiltersBean` or `Object`).
     * @private
     */
    _cacheFilters: function(models) {
        if (!models) {
            return;
        }
        var filters = _.isFunction(models.toJSON) ? models.toJSON() : models;
        filters = _.isArray(filters) ? filters : [filters];

        var prototype = this._getPrototype();
        _.each(filters, function(filter) {
            if (filter.editable === false) {
                prototype._cache[this.moduleName].predefined[filter.id] = filter;
            } else if (filter.is_template) {
                prototype._cache[this.moduleName].template[filter.id] = filter;
            } else {
                prototype._cache[this.moduleName].user[filter.id] = filter;
            }
        }, this);
    },

    /**
     * Create the collection of filters.
     *
     * The collection contains predefined filters and the current user's
     * filters.
     *
     * @return {Backbone.Collection} The collection of filters.
     * @private
     */
    _createCachedCollection: function() {
        var prototype = app.data.getCollectionClasses().Filters.prototype,
            module = this.moduleName,
            collection;

        // Creating the collection class.
        prototype._cachedCollection = prototype._cachedCollection || Backbone.Collection.extend({
            model: app.data.getBeanClass('Filters'),
            _setInitialFilter: this._setInitialFilter,
            comparator: this.comparator,
            _getPrototype: this._getPrototype,
            _getTranslatedFilterName: this._getTranslatedFilterName,
            _cacheFilters: this._cacheFilters,
            _updateFilterCache: this._updateFilterCache,
            _removeFilterCache: this._removeFilterCache,
            initialize: function(models, options) {
                this.on('add', this._cacheFilters, this);
                this.on('cache:update', this._updateFilterCache, this);
                this.on('remove', this._removeFilterCache, this);
            }
        });

        collection = new prototype._cachedCollection();
        collection.moduleName = module;
        collection._filterOptions = this._filterOptions;
        collection.defaultFilterFromMeta = prototype._cache[module].defaultFilterFromMeta;
        // Important to pass silent `true` to avoid saving in memory again.
        collection.add(_.toArray(prototype._cache[module].predefined), {silent: true});
        collection.add(_.toArray(prototype._cache[module].user), {silent: true});

        return collection;
    },

    /**
     * Gets the translated name of a filter.
     *
     * If the model is not editable or is a template, the filter name must be
     * defined as a label that is internationalized.
     * We allow injecting the translated module name into filter names.
     *
     * @param {Data.Bean} model The filter model.
     * @return {String} The translated filter name.
     * @private
     */
    _getTranslatedFilterName: function(model) {
        var name = model.get('name') || '';

        if (model.get('editable') !== false && !model.get('is_template')) {
            return name;
        }
        var module = model.get('module_name') || this.moduleName;

        var fallbackLangModules = model.langModules || [module, 'Filters'];
        var moduleName = app.lang.getModuleName(module, {plural: true});
        var text = app.lang.get(name, fallbackLangModules) || '';
        return app.utils.formatString(text, [moduleName]);
    },

    /**
     * Loads predefined filters from metadata and stores them in memory.
     *
     * Also determines the default filter. The default filter will be the last
     * `default_filter` property found in the filters metadata.
     *
     * @private
     */
    _loadPredefinedFilters: function() {
        var cache = this._getPrototype()._cache[this.moduleName],
            moduleMeta = app.metadata.getModule(this.moduleName);

        if (!moduleMeta) {
            app.logger.error('The module "' + this.moduleName + '" has no metadata.');
            return;
        }

        var moduleFilterMeta = moduleMeta.filters;
        if (!moduleFilterMeta) {
            app.logger.error('The module "' + this.moduleName + '" has no filter metadata.');
            return;
        }

        _.each(moduleFilterMeta, function(template) {
            if (!template || !template.meta) {
                return;
            }
            if (_.isArray(template.meta.filters)) {
                this._cacheFilters(template.meta.filters);
            }
            if (template.meta.default_filter) {
                cache.defaultFilterFromMeta = template.meta.default_filter;
            }
        }, this);
    },

    /**
     * Success callback applied once filters are retrieved in order to prepare
     * the bean collection.
     *
     * @param {Function} [callback] Custom success callback. The collection is
     *   readily available as the first argument to this callback function.
     * @private
     */
    _onSuccessCallback: function(callback) {
        this.collection = this._createCachedCollection();
        if (this._filterOptions.initial_filter) {
            this.collection._setInitialFilter();
        }

        if (_.isFunction(callback)) {
            callback(this.collection);
        }
    },

    /**
     * Sets an initial/template filter to the collection.
     *
     * Filter options:
     *
     * If the `initial_filter` id is `$relate`, a new filter will be created for
     * you, and will be populated by `filter_populate` definition.
     *
     * If you pass any other `initial_filter` id, the function will look up for
     * this template filter in memory and create it.
     *
     * @private
     */
    _setInitialFilter: function() {
        var filterId = this._filterOptions.initial_filter;

        if (!filterId) {
            return;
        }

        if (filterId === '$relate') {
            var filterDef = {};
            _.each(this._filterOptions.filter_populate, function(value, key) {
                filterDef[key] = '';
            });
            this.add([
                {
                    'id': '$relate',
                    'editable': true,
                    'is_template': true,
                    'filter_definition': [filterDef]
                }
            ], {silent: true});
        } else {
            var prototype = this._getPrototype();
            var filter = prototype._cache[this.moduleName].template[filterId];
            if (!filter) {
                return;
            }
            this.add(filter, {silent: true});
        }
        this.get(filterId).set('name', this._filterOptions.initial_filter_label);
        this.get(filterId).langModules = this._filterOptions.initial_filter_lang_modules;
    },

    /**
     * Saves the list of filters in memory.
     *
     * Only user's filters are refreshed. We want to ignore changes to template
     * filters and predefined filters.
     *
     * @param {Data.Base.FiltersBean|Object} model The filter model to update in
     *   memory.
     * @param {String} model.id The filter id.
     * @private
     */
    _updateFilterCache: function(model) {
        if (!model) {
            return;
        }
        var attributes = _.isFunction(model.toJSON) ? model.toJSON() : model;
        if (attributes.is_template || attributes.editable === false) {
            return;
        }
        this._cacheFilters(model);
    },

    /**
     * Removes a filter stored in memory.
     *
     * @param {Data.Base.FiltersBean|Object} model The filter model to remove
     *   from memory.
     * @param {String} model.id The filter id.
     * @private
     */
    _removeFilterCache: function(model) {
        var prototype = this._getPrototype();
        delete prototype._cache[this.moduleName].predefined[model.id];
        delete prototype._cache[this.moduleName].template[model.id];
        delete prototype._cache[this.moduleName].user[model.id];
    },

    /**
     * Initializes the filter cache for this module.
     *
     * @private
     */
    _initFiltersModuleCache: function() {
        var prototype = this._getPrototype();
        prototype._cache = prototype._cache || {};
        prototype._cache[this.moduleName] = {
            defaultFilterFromMeta: null,
            predefined: {},
            template: {},
            user: {}
        };
    },

    /**
     * Clears all the filters and their associated HTTP requests from the cache.
     */
    resetFiltersCacheAndRequests: function() {
        var prototype = this._getPrototype();
        prototype._cache = {};
        _.each(prototype._request, function(request, module) {
            request.xhr.abort();
        });
        prototype._request = {};
    },

    /**
     * Gets the prototype object of this class.
     *
     * @return {Object} The prototype.
     * @private
     */
    _getPrototype: function() {
        return app.data.getCollectionClasses().Filters.prototype;
    },

    /**
     * Removes all the listeners.
     */
    dispose: function() {
        if (this.collection) {
            this.collection.off();
        }
        this.off();
    }
})
