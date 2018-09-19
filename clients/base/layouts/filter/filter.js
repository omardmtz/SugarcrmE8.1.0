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
 * Layout for filtering a collection.
 *
 * Composed of a module dropdown(optional), a filter dropdown and an input.
 *
 * @class View.Layouts.Base.FilterLayout
 * @alias SUGAR.App.view.layouts.BaseFilterLayout
 * @extends View.Layout
 */
({
    className: 'filter-view search',

    events: {
        'click .add-on.fa-times': function() { this.trigger('filter:clear:quicksearch'); }
    },

    /**
     * The collection of filters.
     *
     * @property {Data.BeanCollection}
     */
    filters: null,

    /**
     * @override
     * @param {Object} opts
     */
    initialize: function(opts) {
        var filterLayout = app.view._getController({type:'layout',name:'filter'});
        filterLayout.loadedModules = filterLayout.loadedModules || {};
        app.view.Layout.prototype.initialize.call(this, opts);

        this.layoutType = app.utils.deepCopy(this.options.meta.layoutType) || this.context.get('layout') || this.context.get('layoutName') || app.controller.context.get('layout');

        this.aclToCheck = (this.layoutType === 'record')? 'view' : 'list';

        // Can't use getRelevantContextList here, because the context may not
        // have all the children we need.
        if (this.layoutType === 'records' || this.layoutType === 'activities') {
            // filters will handle data fetching so we skip the standard data fetch
            this.context.set('skipFetch', true);
        } else {
            if(this.context.parent) {
                this.context.parent.set('skipFetch', true);
            }
            this.context.on('context:child:add', function(childCtx) {
                if (childCtx.get('link')) {
                    // We're in a subpanel.
                    childCtx.set('skipFetch', true);
                }
            }, this);
        }

        /**
         * bind events
         */
        this.on('filter:apply', this.applyFilter, this);

        this.on('filter:create:close', function() {
            if (!this.createPanelIsOpen()) {
                return;
            }
            this.layout.trigger('filter:create:close');
            // When canceling creating a new filter, we want to go back to the `all_records` filter
            if (this.getLastFilter(this.layout.currentModule, this.layoutType) === 'create') {
                // For that we need to remove the last state key and trigger reinitialize
                this.clearLastFilter(this.layout.currentModule, this.layoutType);
                this.layout.trigger("filter:reinitialize");
            }
            this.context.editingFilter = null;
        }, this);

        this.on('filter:create:open', function(filterModel) {
            this.context.editingFilter = filterModel;
            this.layout.trigger('filter:create:open', filterModel);
        }, this);

        this.on('subpanel:change', function(linkName) {
            this.layout.trigger('subpanel:change', linkName);
        }, this);

        this.on('filter:get', this.initializeFilterState, this);

        this.on('filter:change:filter', this.handleFilterChange, this);

        this.layout.on('filter:apply', function(query, def) {
            this.trigger('filter:apply', query, def);
        }, this);

        this.layout.on('filterpanel:change', this.handleFilterPanelChange, this);
        this.layout.on('filterpanel:toggle:button', this.toggleFilterButton, this);

        //When a filter is saved, update the cache and set the filter to be the currently used filter
        this.context.on('filter:add', this.addFilter, this);

        // When a filter is deleted, update the cache and set the default filter
        // to be the currently used filter.
        this.layout.on('filter:remove', this.removeFilter, this);

        this.layout.on('filter:reinitialize', function() {
            this.initializeFilterState(this.layout.currentModule, this.layout.currentLink);
        }, this);

        this.listenTo(app.events, 'dashlet:filter:save', this.refreshDropdown);
    },

    /**
     * This function refreshes the list of filters in the filter dropdown, and
     * is invoked when a filter is saved on a dashlet (`dashlet:filter:save`).
     * It triggers a `filter:reinitialize` event and resets the cached
     * module in `loadedModules` on the filter layout if the dashlet module
     * matches the `currentModule` on the filter layout.
     *
     * @param {String} module
     */
    refreshDropdown: function(module) {
        if (module === this.layout.currentModule) {
            var filterLayout = app.view._getController({type:'layout', name:'filter'});
            filterLayout.loadedModules[module] = false;
            this.layout.trigger('filter:reinitialize');
        }
    },

    /**
     * handles filter removal
     * @param model
     */
    removeFilter: function(model) {
        this.filters.collection.remove(model);
        this.context.set('currentFilterId', null);
        this.clearLastFilter(this.layout.currentModule, this.layoutType);
        this.layout.trigger('filter:reinitialize');
    },

    /**
     * Saves last filter id to app cache.
     *
     * @param {String} filterModule The name of the filtered module.
     * @param {String} layoutName The name of the current layout.
     * @param {String} filterId The filter id.
     */
    setLastFilter: function(filterModule, layoutName, filterId) {
        var filterOptions = this.context.get('filterOptions') || {};
        this.context.set('currentFilterId', filterId);
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('last-' + filterModule + '-' + layoutName, this);
            app.user.lastState.set(key, filterId);
        }
    },

    /**
     * Gets last filter id from cache.
     *
     * @param {String} filterModule The name of the filtered module.
     * @param {String} layoutName The name of the current layout.
     * @return {String} The filter id.
     */
    getLastFilter: function(filterModule, layoutName) {
        // Check if we've already loaded it.
        var filter = this.context.get('currentFilterId');
        if (!_.isEmpty(filter)) {
            return filter;
        }

        var filterOptions = this.context.get('filterOptions') || {};
        // Load from cache.
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('last-' + filterModule + '-' + layoutName, this);
            filter = app.user.lastState.get(key);
        }

        // Check if there is an initial filter defined that we should use instead.
        if (_.isEmpty(filter) && filterOptions.initial_filter) {
            filter = filterOptions.initial_filter;
        }

        this.context.set('currentFilterId', filter);
        return filter;
    },

    /**
     * Clears last filter id from cache.
     *
     * @param {String} filterModule The name of the filtered module.
     * @param {String} layoutName The name of the current layout.
     */
    clearLastFilter: function(filterModule, layoutName) {
        var filterOptions = this.context.get('filterOptions') || {};
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('last-' + filterModule + '-' + layoutName, this);
            app.user.lastState.remove(key);
        }
        this.clearFilterEditState();
    },

    /**
     * Retrieves the current edit state from cache.
     *
     * @return {Object} The filter attributes if found.
     */
    retrieveFilterEditState: function() {
        var filterOptions = this.context.get('filterOptions') || {};
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('edit-' + this.layout.currentModule + '-' + this.layoutType, this);
            return app.user.lastState.get(key);
        }
    },

    /**
     * Saves the current edit state into the cache
     *
     * @param {Object} filter
     */
    saveFilterEditState: function(filter) {
        var filterOptions = this.context.get('filterOptions') || {};
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('edit-' + this.layout.currentModule + '-' + this.layoutType, this);
            app.user.lastState.set(key, filter);
        }
    },

    /**
     * Removes the edit state from the cache
     */
    clearFilterEditState: function() {
        var filterOptions = this.context.get('filterOptions') || {};
        if (filterOptions.stickiness !== false) {
            var key = app.user.lastState.key('edit-' + this.layout.currentModule + '-' + this.layoutType, this);
            app.user.lastState.remove(key);
        }
    },

    /**
     * Removes deprecated cache entries of one module.
     *
     * The {@link Data.Base.FiltersBeanCollection} is now responsible for
     * storing the list of filters in memory. This list is no longer saved to
     * the local storage.
     *
     * First version, the list was stored with a key looking like this:
     *
     *     this.module + ':filter:saved-' + this.layout.currentModule
     *
     * Second version, the list was stored with a key looking like this:
     *
     *     module + ':filter:saved-filters
     *
     * Examples of keys we need to remove:
     *
     *     Home:filter:saved-Accounts
     *     Accounts:filter:saved-Accounts
     *     Contacts:filter:saved-Accounts
     *     Accounts:filter:saved-filters
     *     Contacts:filter:saved-filters
     *
     * @param {String} module The module name.
     */
    removeDeprecatedCache: function(module) {
        app.user.lastState.remove(app.user.lastState.key('saved-' + module, this));

        var layoutModule = this.module;
        this.module = module;
        // The filter collection used to be cached. It's now only saved in
        // memory so we need to remove the potential existing cache entry.
        app.user.lastState.remove(app.user.lastState.key('saved-filters', this));
        this.module = layoutModule;
    },

    /**
     * Handles filter addition or update.
     *
     * @param {Data.Base.FiltersBean} model The filter model that is created or
     *   updated.
     */
    addFilter: function(model) {
        var id = model.get('id');
        this.filters.collection.add(model, {merge: true});
        this.filters.collection.trigger('cache:update', model);
        this.setLastFilter(this.layout.currentModule, this.layoutType, id);
        this.context.set('currentFilterId', id);
        this.clearFilterEditState();
        this.layout.trigger('filter:reinitialize');
    },

    /**
     * Enables or disables a filter toggle button (e.g. activity or subpanel toggle buttons)
     * @param {String} toggleDataView the string used in `data-view` attribute for that toggle element (e.g. 'subpanels', 'activitystream')
     * @param {Boolean} on pass true to enable, false to disable
     */
    toggleFilterButton: function (toggleDataView, on) {
        var toggleButtons = this.layout.$('.toggle-actions a.btn');

        // Loops toggle buttons for 'data-view' that corresponds to `toggleDataView` and enables/disables per `on`
        _.each(toggleButtons, function(btn) {
            if($(btn).data('view') === toggleDataView) {
                if(on) {
                    $(btn).removeAttr('disabled').removeClass('disabled');
                } else {
                    $(btn).attr('disabled', 'disabled').addClass('disabled');
                    $(btn).attr('title', app.lang.get('LBL_NO_DATA_AVAILABLE'));
                }
            }
        });
    },

    /**
     * Handles filter panel changes between activity and subpanels
     * @param {String} name Name of panel
     * @param {Boolean} silent Whether to trigger filter events
     * @param {Boolean} setLastViewed Whether to set last viewed to `name` panel
     */
    handleFilterPanelChange: function(name, silent, setLastViewed) {
        this.showingActivities = name === 'activitystream';
        var module = this.showingActivities ? "Activities" : this.module;
        var link;

        this.$el.css('visibility', app.acl.hasAccess(this.aclToCheck, module) ? 'visible' : 'hidden');
        if(this.layoutType === 'record' && !this.showingActivities) {
            // FIXME: TY-499 will address removing the dependancy on this.layout
            module = link = app.user.lastState.get(app.user.lastState.key('subpanels-last', this.layout)) || 'all_modules';
            if (link !== 'all_modules') {
                module = app.data.getRelatedModule(this.module, link);
            }
        } else {
            link = null;
        }
        if (!silent) {
            this.trigger("filter:render:module");
            this.trigger("filter:change:module", module, link);
        }
        if (setLastViewed) {
            // Asks filterpanel to update user.lastState with new panel name as last viewed
            this.layout.trigger('filterpanel:lastviewed:set', name);
        }
    },

    /**
     * Handles filter change.
     *
     * @param {String} id The filter id.
     */
    handleFilterChange: function(id) {
        this.setLastFilter(this.layout.currentModule, this.layoutType, id);

        var filter, editState = this.retrieveFilterEditState();
        // Figure out if we have an edit state. This would mean user was editing the filter so we want him to retrieve
        // the filter form in the state he left it.
        filter = this.filters.collection.get(id) || app.data.createBean('Filters', {module_name: this.moduleName});
        if (editState && (editState.id === id || (id==='create' && !editState.id))) {
            filter.set(editState);
        } else {
            editState = false;
        }

        this.context.set('currentFilterId', filter.get('id'));

        var editable = filter.get('editable') !== false;

        // If the user selects a filter that has an incomplete filter
        // definition (i.e. filter definition != filter_template), open the
        // filterpanel to indicate it is ready for further editing.
        var isIncompleteFilter = filter.get('filter_template') &&
            JSON.stringify(filter.get('filter_definition')) !== JSON.stringify(filter.get('filter_template'));

        // If the user selects a filter template that gets populated by values
        // passed in the context/metadata, open the filterpanel to show the
        // actual search.
        var isTemplateFilter = filter.get('is_template');

        var modelHasChanged = !_.isEmpty(filter.changedAttributes(filter.getSynced()));

        if (editable &&
            (isIncompleteFilter || isTemplateFilter || editState || id === 'create' || modelHasChanged)
        ) {
            this.layout.trigger('filter:set:name', '');
            this.trigger('filter:create:open', filter);
            this.layout.trigger('filter:toggle:savestate', true);
        } else {
            // FIXME: TY-1457 should improve this
            this.context.editingFilter = null;
            this.layout.trigger('filter:create:close');
        }

        var ctxList = this.getRelevantContextList();
        var clear = false;
        //Determine if we need to clear the collections
        _.each(ctxList, function(ctx) {
            var filterDef = filter.get('filter_definition');
            var orig = ctx.get('collection').origFilterDef;
            ctx.get('collection').origFilterDef = filterDef;  //Set new filter def on each collection
            if (_.isUndefined(orig) || !_.isEqual(orig, filterDef)) {
                clear = true;
            }
        });
        //If so, reset collections and trigger quicksearch to repopulate
        if (clear) {
            _.each(ctxList, function(ctx) {
                ctx.get('collection').resetPagination();
                // Silently reset the collection otherwise the view is re-rendered.
                // It will be re-rendered on request response.
                ctx.get('collection').reset(null, { silent: true });
            });
            this.trigger('filter:apply');
        }
    },
    /**
     * Applies filter on current contexts
     * @param {String} query search string
     * @param {Object} dynamicFilterDef(optional)
     */
    applyFilter: function(query, dynamicFilterDef) {
        // TODO: getRelevantContextList needs to be refactored to handle filterpanels in drawer layouts,
        // as it will return the global context instead of filtering a list view within the drawer context.
        // As a result, this flag is needed to prevent filtering on the global context.
        var filterOptions = this.context.get('filterOptions') || {};
        if (filterOptions.auto_apply === false) {
            return;
        }

        // to make sure quick filter is handled properly
        if (_.isEmpty(query)) {
            var filterQuicksearchView = this.getComponent('filter-quicksearch');
            query = filterQuicksearchView && filterQuicksearchView.$el.val() || '';
        }

        //If the quicksearch field is not empty, append a remove icon so the user can clear the search easily
        this._toggleClearQuickSearchIcon(!_.isEmpty(query));
        var self = this;
        var ctxList = this.getRelevantContextList();

        // Here we split the relevant contexts into two groups, 'count', and
        // 'fetch'. For the 'count' contexts, we do a 'fetchOnlyIds' on their
        // collection so we can update the count and highlight the subpanel
        // icon, even though they are collapsed. For the 'fetch' group, we do a
        // full collection fetch so the subpanel can render its list view.
        var relevantCtx = _.groupBy(ctxList, function(ctx) {
            return ctx.get('collapsed') ? 'count' : 'fetch';
        });

        var batchId = relevantCtx.count && relevantCtx.count.length > 1 ? _.uniqueId() : false;
        _.each(relevantCtx.count, function(ctx) {
            var ctxCollection = ctx.get('collection');
            var origFilterDef = dynamicFilterDef || ctxCollection.origFilterDef || [];
            var filterDef = self.buildFilterDef(origFilterDef, query, ctx);
            var options = {
                //Show alerts for this request
                showAlerts: true,
                apiOptions: {
                    bulk: batchId
                }
            };

            ctxCollection.filterDef = filterDef;
            ctxCollection.origFilterDef = origFilterDef;
            ctxCollection.resetPagination();

            options = _.extend(options, ctx.get('collectionOptions'));
            ctx.resetLoadFlag({recursive: false});
            ctx.set('skipFetch', true);
            ctx.loadData(options);

            // We need to reset twice so we can trigger the other bulk call.
            ctx.resetLoadFlag({recursive: false});
            options.success = _.bind(function(hasAmount, properties) {
                if (!this.disposed) {
                    ctx.trigger('refresh:count', hasAmount, properties);
                }
            }, this);
            ctxCollection.hasAtLeast(ctx.get('limit'), options);
        });

        // FIXME: Filters should not be triggering the bulk request and should
        // be moved to subpanels instead. Will be fixed as part of SC-4533.
        if (batchId) {
            app.api.triggerBulkCall(batchId);
        }

        batchId = relevantCtx.fetch && relevantCtx.fetch.length > 1 ? _.uniqueId() : false;
        _.each(relevantCtx.fetch, function(ctx) {
            var ctxCollection = ctx.get('collection');
            var origFilterDef = dynamicFilterDef || ctxCollection.origFilterDef || [];
            var filterDef = self.buildFilterDef(origFilterDef, query, ctx);
            var options = {
                //Show alerts for this request
                showAlerts: true,
                apiOptions: {
                    bulk: batchId
                },
                success: function(collection, response, options) {
                    // Close the preview pane to ensure that the preview
                    // collection is in sync with the list collection.
                    app.events.trigger('preview:close');
                }
            };

            ctxCollection.filterDef = filterDef;
            ctxCollection.origFilterDef = origFilterDef;

            ctx.resetLoadFlag({recursive: false});
            ctx.set('skipFetch', false);
            ctx.loadData(options);
        });
        if (batchId) {
            app.api.triggerBulkCall(batchId);
        }
    },

    /**
     * Look for the relevant contexts. It can be
     * - the activity stream context
     * - the list view context on records layout
     * - the selection list view context on records layout
     * - the contexts of the subpanels on record layout
     * @return {Array} array of contexts
     */
    getRelevantContextList: function() {
        var contextList = [];
        if (this.showingActivities) {
            _.each(this.layout._components, function(component) {
                var ctx = component.context;
                if (component.name == 'activitystream' && !ctx.get('modelId') && ctx.get('collection')) {
                    //FIXME: filter layout's _components array has multiple references to same activitystreams layout object
                    contextList.push(ctx);

                }
            });
        } else {
            if (this.layoutType === 'records') {
                var ctx = this.context;
                if (!ctx.get('modelId') && ctx.get('collection')) {
                    contextList.push(ctx);
                }
            } else {
                //Locate and add subpanel contexts
                _.each(this.context.children, function(ctx) {
                    if (ctx.get('isSubpanel') && !ctx.get('hidden') && !ctx.get('modelId') && ctx.get('collection')) {
                        contextList.push(ctx);
                    }
                });
            }
        }
        return _.uniq(contextList);
    },

    /**
     * Builds the filter definition based on preselected filter and module quick search fields
     * @param {Object} oSelectedFilter
     * @param {String} searchTerm
     * @param {Context} context
     * @return {Array} array containing filter def
     */
    buildFilterDef: function(oSelectedFilter, searchTerm, context) {
        var selectedFilter = app.utils.deepCopy(oSelectedFilter),
            isSelectedFilter = _.size(selectedFilter) > 0,
            module = context.get('module'),
            filtersBeanPrototype = app.data.getBeanClass('Filters').prototype,
            searchFilter = filtersBeanPrototype.buildSearchTermFilter(module, searchTerm),
            isSearchFilter = _.size(searchFilter) > 0;

        selectedFilter = _.isArray(selectedFilter) ? selectedFilter : [selectedFilter];
        /**
         * Filter fields that don't exist either on vardefs or search definition.
         *
         * Special fields (fields that start with `$`) like `$favorite` aren't
         * cleared.
         *
         * TODO move this to a plugin method when refactoring the code (see SC-2555)
         * TODO we should support cleanup on all levels (currently made on 1st
         * level only).
         */
        var specialField = /^\$/,
            meta = app.metadata.getModule(module);
        selectedFilter = _.filter(selectedFilter, function(def) {
            var fieldName = _.keys(def).pop();
            return specialField.test(fieldName) || meta.fields[fieldName];
        }, this);

        if (isSelectedFilter && isSearchFilter) {
            selectedFilter.push(searchFilter[0]);
            return [{'$and': selectedFilter }];
        } else if (isSelectedFilter) {
            return selectedFilter;
        } else if (isSearchFilter) {
            return searchFilter;
        }

        return [];
    },

    /**
     * Loads the full filter panel for a module.
     *
     * @param {String} moduleName The module name.
     * @param {String} [linkName] The related module link name, by default it
     *   will load the last selected filter,
     * @param {String} [filterId] The filter ID to initialize with. By default
     *   it will load the last selected filter or the default filter from
     *   metadata.
     */
    initializeFilterState: function(moduleName, linkName, filterId) {

        if (this.showingActivities) {
            moduleName = 'Activities';
            linkName = null;
        } else {
            moduleName = moduleName || this.module;

            if (this.layoutType === 'record') {
                // FIXME: TY-499 will address removing the dependancy on this.layout
                linkName = app.user.lastState.get(app.user.lastState.key('subpanels-last', this.layout)) ||
                    linkName ||
                    'all_modules';

                // if the incoming module is the same as the layoutModule then we need to find the other side.
                if (linkName !== 'all_modules' && this.layout.module === moduleName) {
                    moduleName = app.data.getRelatedModule(moduleName, linkName) || moduleName;
                }
            }
        }

        filterId = filterId || this.getLastFilter(moduleName, this.layoutType);

        this.layout.trigger('filterpanel:change:module', moduleName, linkName);
        this.trigger('filter:change:module', moduleName, linkName, true);
        this.getFilters(moduleName, filterId);
    },

    /**
     * Retrieves the appropriate list of filters from cache if found, otherwise
     * from the server.
     *
     * @param {String} moduleName The module name.
     * @param {String} [defaultId] The filter `id` to select once loaded.
     */
    getFilters: function(moduleName, defaultId) {
        if (moduleName === 'all_modules') {
            this.selectFilter('all_records');
            return;
        }
        var filterOptions = this.context.get('filterOptions') || {};

        if (this.filters) {
            this.filters.dispose();
        }

        // Remove deprecated cache entries.
        this.removeDeprecatedCache(moduleName);

        this.filters = app.data.createBeanCollection('Filters');
        this.filters.setModuleName(moduleName);
        this.filters.setFilterOptions(filterOptions);

        this.filters.load({
            success: _.bind(function() {
                if (this.disposed) {
                    return;
                }
                defaultId = defaultId || this.filters.collection.defaultFilterFromMeta;
                this.selectFilter(defaultId);

            }, this)
        });
    },

    /**
     * Selects a filter.
     *
     * @triggers filter:select:filter to select the filter in the dropdown.
     *
     * @param {String} filterId The filter id to select.
     * @return {String} The selected filter id.
     */
    selectFilter: function(filterId) {
        var possibleFilters,
            selectedFilterId = filterId;

        if (selectedFilterId !== 'create') {
            possibleFilters = [selectedFilterId, this.filters.collection.defaultFilterFromMeta, 'all_records'];
            possibleFilters = _.filter(possibleFilters, this.filters.collection.get, this.filters.collection);
            selectedFilterId = _.first(possibleFilters);
        }
        this.trigger('filter:render:filter');
        this.trigger('filter:select:filter', selectedFilterId);
        return selectedFilterId;
    },

    /**
     * Utility function to know if the create filter panel is opened.
     * @return {boolean} `true` if opened, `false` otherwise
     */
    createPanelIsOpen: function() {
        return !this.layout.$(".filter-options").is(":hidden");
    },

    /**
     * Determines whether a user can create a filter for the current module.
     * @return {boolean} `true` if creatable, `false` otherwise
     */
    canCreateFilter: function() {
        // Check for create in meta and make sure that we're only showing one
        // module, then return false if any is false.
        var contexts = this.getRelevantContextList(),
            creatable = app.acl.hasAccess("create", "Filters"),
            meta;
        // Short circuit if we don't have the ACLs to create Filter beans.

        if (creatable && contexts.length === 1) {
            meta = app.metadata.getModule(contexts[0].get("module"));
            if (meta && _.isObject(meta.filters)) {
                _.each(meta.filters, function(value) {
                    if (_.isObject(value)) {
                        creatable = creatable && value.meta.create !== false;
                    }
                });
            }
        }

        return creatable;
    },

    /**
     * Append or remove an icon to the quicksearch input so the user can clear the search easily
     * @param {Boolean} addIt TRUE if you want to add it, FALSO to remove
     */
    _toggleClearQuickSearchIcon: function(addIt) {
        if (addIt && !this.$('.fa-times.add-on')[0]) {
            this.$el.append('<i class="fa fa-times add-on"></i>');
        } else if (!addIt) {
            this.$('.fa-times.add-on').remove();
        }
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        if (app.acl.hasAccess(this.aclToCheck, this.module)) {
            app.view.Layout.prototype._render.call(this);
        }
    },

    /**
     * @override
     */
    unbind: function() {
        if (this.filters) {
            this.filters.dispose();
        }
        this.filters = null;
        app.view.Layout.prototype.unbind.call(this);
    }

})
