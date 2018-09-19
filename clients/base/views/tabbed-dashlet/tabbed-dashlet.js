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
 * Tabbed dashlet is an abstraction that allows new tabbed dashlets to be
 * easily created based on a metadata driven configurable set of tabs, where
 * each new tab is created under a tabs array, where a specific set of
 * properties can be defined.
 *
 * Supported properties:
 *
 * - {Boolean} active If specific tab should be active by default.
 * - {String} filter_applied_to Date field to be used on date switcher, defaults
 *   to date_entered.
 * - {Array} filters Array of filters to be applied.
 * - {String} label Tab label.
 * - {Array} labels Array of labels (singular/plural) to be applied when
 *   LBL_MODULE_NAME_SINGULAR and LBL_MODULE_NAME aren't available or there's a
 *   need to use custom labels depending on the number of records available.
 * - {String} link Relationship link to be used if we're on a record view
 *   context, leading to only associated records being shown.
 * - {String} module Module from which the records are retrieved.
 * - {String} order_by Sort records by field.
 * - {String} record_date Date field to be used to print record date, defaults
 *   to 'date_entered' if none supplied.
 * - {Array} row_actions Row actions to be applied to each record.
 *
 * Example:
 * <pre><code>
 * // ...
 * 'tabs' => array(
 *     array(
 *         'filter_applied_to' => 'date_entered',
 *         'filters' => array(
 *             'type' => array('$equals' => 'out'),
 *         ),
 *         'labels' => array(
 *             'singular' => 'LBL_DASHLET_EMAIL_OUTBOUND_SINGULAR',
 *             'plural' => 'LBL_DASHLET_EMAIL_OUTBOUND_PLURAL',
 *         ),
 *         'link' => 'emails',
 *         'module' => 'Emails',
 *     ),
 *     //...
 * ),
 * //...
 * </code></pre>
 *
 * @class View.Views.Base.TabbedDashletView
 * @alias SUGAR.App.view.views.BaseTabbedDashletView
 * @extends View.View
 */
({
    plugins: ['Dashlet', 'RelativeTime', 'ToggleVisibility', 'Pagination'],

    events: {
        'click [data-action=show-more]': 'showMore',
        'click [data-action=tab-switcher]': 'tabSwitcher'
    },

    /**
     * Default settings used when none are provided via metadata.
     *
     * @template
     * @protected
     */
    _defaultSettings: {},

    /**
     * Bind the separate context to avoid sharing context's handlers
     * between its extension dashlets.
     */
    initDashlet: function() {
        this._initSettings();
        if (this.meta.config) {
            return;
        }

        this.collection = app.data.createBeanCollection(this.module);
        this.context = this.context.getChildContext({
            forceNew: true,
            model: this.context.parent && this.context.parent.get('model'),
            collection: this.collection,
            //FIXME: name is temporary - special case for LinkedModel - SC-2550
            name: 'tabbed-dashlet',
            skipFetch: true
        });

        this.context.set('parentModule', this.module);

        this._initMaxHeightTarget();
        this._initEvents();
        this._initTabs();
        this._initTemplates();
    },

    /**
     * Initialize max height target element by overriding its value and
     * setting it to a specific tab inner element.
     *
     * @chainable
     * @template
     * @protected
     */
    _initMaxHeightTarget: function() {
        this.maxHeightTarget = this.meta.max_height_target || 'div.tab-content';

        return this;
    },

    /**
     * Initialize events.
     *
     * @chainable
     * @template
     * @protected
     */
    _initEvents: function() {
        this.settings.on('change:filter', this.loadData, this);
        this.on('tabbed-dashlet:unlink-record:fire', this.unlinkRecord, this);
        this.context.on('tabbed-dashlet:refresh', this.refreshTabsForModule, this);
        this.context.on('change:collection', this.onCollectionChange, this);

        return this;
    },

    /**
     * Initialize tabs.
     *
     * @chainable
     * @protected
     */
    _initTabs: function() {
        this.tabs = [];
        _.each(this.dashletConfig.tabs, function(tab, index) {
            if (tab.active) {
                this.settings.set('activeTab', index);
            }

            var collection = this._createCollection(tab);
            if (_.isNull(collection)) {
                return;
            }

            collection.on('add', this.bindCollectionAdd, this);
            collection.on('reset', this.bindCollectionReset, this);

            this.tabs[index] = tab;
            this.tabs[index].collection = collection;
            this.tabs[index].relate = _.isObject(collection.link);
            this.tabs[index].record_date = tab.record_date || 'date_entered';
            this.tabs[index].include_child_items = tab.include_child_items || false;
        }, this);

        return this;
    },

    /**
     * Initialize templates.
     *
     * This will get the templates from either the current module (since we
     * might want to customize it per module) or from core templates.
     *
     * Please define your templates on:
     *
     * - `custom/clients/{platform}/view/tabbed-dashlet/tabs.hbs`
     * - `custom/clients/{platform}/view/tabbed-dashlet/toolbar.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/tabbed-dashlet/tabs.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/tabbed-dashlet/toolbar.hbs`
     *
     * @chainable
     * @template
     * @protected
     */
    _initTemplates: function() {
        this._tabsTpl = app.template.getView(this.name + '.tabs', this.module) ||
            app.template.getView(this.name + '.tabs') ||
            app.template.getView('tabbed-dashlet.tabs', this.module) ||
            app.template.getView('tabbed-dashlet.tabs');

        this._toolbarTpl = app.template.getView(this.name + '.toolbar', this.module) ||
            app.template.getView(this.name + '.toolbar') ||
            app.template.getView('tabbed-dashlet.toolbar', this.module) ||
            app.template.getView('tabbed-dashlet.toolbar');

        return this;
    },

    /**
     * Sets up settings, starting with defaults.

     * @chainable
     * @protected
     */
    _initSettings: function() {
        var settings = _.extend({},
            this._defaultSettings,
            this.settings.attributes);

        this.settings.set(settings);

        return this;
    },

    /**
     * New model related properties are injected into each model.
     * Update the record date associating by tab's record date value.
     *
     * @param {Data.Bean} model Appended new model.
     */
    bindCollectionAdd: function(model) {
        var tab = this._getTab(model.collection);
        model.set('record_date', model.get(tab.record_date));
    },

    /**
     * Bind event triggers for each updated models on collection reset.
     *
     * @param {Data.BeanCollection} collection Activated tab's collection.
     */
    bindCollectionReset: function(collection) {
        _.each(collection.models, this.bindCollectionAdd, this);
    },

    /**
     * Bind event listener for the updating collection count on the tab.
     */
    onCollectionChange: function() {
        var prevCollection = this.context.previous('collection');
        if (prevCollection) {
            prevCollection.off(null, this.updateCollectionCount, this);
        }
        this.collection = this.context.get('collection');
        this.collection.on('add remove reset', _.debounce(this.updateCollectionCount, 100), this);
    },

    /**
     * Update the collection's count on the active tab.
     */
    updateCollectionCount: function() {
        var tabIndex = this.settings.get('activeTab');
        var count = this.collection.length;

        if (this.collection.next_offset >= 0) {
            count += '+';
        }
        this.$('[data-action=tab-switcher][data-index=' + tabIndex + ']')
            .children('[data-action=count]')
            .text(count);
    },

    /**
     * Retrieve records template.
     *
     * This will get the template from either the active tab associated module,
     * from the current module (since we might want to customize it per module)
     * or from core templates.
     *
     * Please define your template on:
     *
     * - `custom/clients/{platform}/view/tabbed-dashlet/records.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/tabbed-dashlet/records.hbs`
     *
     * @param {String} module Module name.
     * @return {Function} Template function.
     * @protected
     */
    _getRecordsTemplate: function(module) {
        this._recordsTpl = this._recordsTpl || {};

        if (!this._recordsTpl[module]) {
            this._recordsTpl[module] = app.template.getView(this.name + '.records', module) ||
                app.template.getView(this.name + '.records', this.module) ||
                app.template.getView(this.name + '.records') ||
                app.template.getView('tabbed-dashlet.records', this.module) ||
                app.template.getView('tabbed-dashlet.records');
        }

        return this._recordsTpl[module];
    },

    /**
     * Create collection based on tab properties and current context,
     * furthermore if supplied tab has a valid 'link' property a related
     * collection will be created instead.
     *
     * @param {Object} tab Tab properties.
     * @return {Data.BeanCollection|null} A new instance of bean collection or `null`
     *   if we cannot access module metadata.
     * @protected
     */
    _createCollection: function(tab) {
        if (this.context.parent) {
            var module = this.context.parent.get('module');
        } else {
            var module = this.module;
        }

        var meta = app.metadata.getModule(module);
        if (_.isUndefined(meta)) {
            return null;
        }

        var options = {};
        if (meta.fields[tab.link] && meta.fields[tab.link].type === 'link') {
            options = {
                link: {
                    name: tab.link,
                    bean: this.model
                }
            };
        }

        var collection = app.data.createBeanCollection(tab.module, null, options);

        return collection;
    },

    /**
     * Retrieves collection options for a specific tab.
     *
     * @param {number} index Tab index.
     * @return {Object} Collection options.
     * @return {number} return.limit The number of records to retrieve.
     * @return {number} return.offset The offset for pagination.
     * @return {Object} return.params Additional parameters to the API call.
     * @return {Object|null} return.fields Specifies the fields on each
     * requested model.
     * @return {boolean|undefined} return.myItems Whether or not there is user
     * visibility when the module is not Meetings or Calls.
     * @protected
     */
    _getCollectionOptions: function(index) {
        var tab = this.tabs[index];
        var options = {
            limit: this.settings.get('limit'),
            offset: 0,
            params: {
                order_by: tab.order_by || null,
                include_child_items: tab.include_child_items || null
            },
            fields: tab.fields || null
        };

        if (tab.module != 'Meetings' && tab.module != 'Calls') {
            options.myItems = this.getVisibility() === 'user';
        }

        return options;
    },

    /**
     * Retrieves collection filters for a specific tab.
     *
     * @param {number} index Tab index.
     * @return {Array} Collection filters.
     * @protected
     */
    _getCollectionFilters: function(index) {
        var tab = this.tabs[index];
        var filters = [];

        _.each(tab.filters, function(condition, field) {
            var filter = {};
            filter[field] = condition;

            filters.push(filter);
        });

        if ((tab.module === 'Meetings' || tab.module === 'Calls')
            && this.getVisibility() === 'user') {
            filters.push({
                "$or":[{"assigned_user_id":app.user.id},
                       {"users.id":app.user.id}]
            });
        }

        return filters;
    },

    /**
     * Retrieves tab based on supplied collection.
     *
     * @param {Object} collection Collection of the desired tab.
     * @return {Object} Tab.
     * @private
     */
    _getTab: function(collection) {
        return _.find(this.tabs, function(tab) {
            return tab.collection === collection;
        }, this);
    },

    /**
     * Override this method to provide custom filters.
     *
     * @param {number} index Tab index.
     * @return {Array} Custom filters.
     * @template
     * @protected
     */
    _getFilters: function(index) {
        return [];
    },

    /**
     * Fetch data for view tabs based on selected options and filters.
     *
     * @param {Object} options Options that are passed to collection/model's
     *   fetch method.
     */
    loadData: function(options) {

        if (this.disposed || this.meta.config) {
            return;
        }
        this.loadDataForTabs(this.tabs, options);

    },

    /**
     * Refresh tabs for the given module
     * @param module {String} name of module needing refresh
     */
    refreshTabsForModule: function(module) {
        var toRefresh = [];
        _.each(this.tabs, function(tab) {
            if (tab.module === module) {
               toRefresh.push(tab);
            }
        });
        this.loadDataForTabs(toRefresh, {});
    },

    /**
     * Load data for passed set of tabs.
     * @param tabs {Array} Set of tabs to update.
     * @param options {Object} load options.
     */
    loadDataForTabs: function(tabs, options) {
        options = options || {};
        var self = this;
        var loadDataRequests = [];
        _.each(tabs, function(tab, index) {
            loadDataRequests.push(function(callback) {
                tab.collection.setOption(self._getCollectionOptions(index));

                tab.collection.filterDef = _.union(
                    self._getCollectionFilters(index),
                    self._getFilters(index)
                );
                tab.collection.fetch({
                    relate: tab.relate,
                    complete: function() {
                        tab.collection.dataFetched = true;
                        callback(null);
                    }
                });
            });
        }, this);
        if (!_.isEmpty(loadDataRequests)) {
            async.parallel(loadDataRequests, function() {
                if (self.disposed) {
                    return;
                }
                self.collection = self.tabs[self.settings.get('activeTab')].collection;
                self.context.set('collection', self.collection);

                self.render();

                if (_.isFunction(options.complete)) {
                    options.complete.call(self);
                }
            });
        }
    },

    /**
     * Convenience callback for updating this
     * and related dashlets once a model has been removed.
     * @return {Function} complete callback.
     * @private
     */
    _getRemoveModelCompleteCallback: function() {
        return _.bind(function(model) {
            if (this.disposed) {
                return;
            }
            this.collection.remove(model);
            this.render();
            this.context.trigger('tabbed-dashlet:refresh', model.module);
        }, this);
    },

    /**
     * Show more records for current collection.
     */
    showMore: function() {
        this.getNextPagination({
            showAlerts: true,
            limit: this.settings.get('limit')
        });
    },

    /**
     * Event handler for tab switcher.
     *
     * @param {Event} event Click event.
     */
    tabSwitcher: function(event) {
        var index = this.$(event.currentTarget).data('index');
        if (index === this.settings.get('activeTab')) {
            return;
        }

        this.settings.set('activeTab', index);
        this.collection = this.tabs[index].collection;
        this.context.set('collection', this.collection);
        this.render();
    },

    /**
     * Additional logic on switch visibility event.
     *
     * See {@link app.plugins.ToggleVisibility}.
     */
    visibilitySwitcher: function() {
        var activeVisibility;
        if (!this.isManager) {
            return;
        }
        activeVisibility = this.getVisibility();
        this.$el.find('[data-action=visibility-switcher]')
            .attr('aria-pressed', function() {
                return $(this).val() === activeVisibility;
            });
    },

    /**
     * Unlinks the selected record.
     *
     * Shows a confirmation alert and removes the model on confirm.
     *
     * @param {Data.Bean} model Selected model.
     */
    unlinkRecord: function(model) {
        var self = this;
        var name = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' ' + name;
        app.alert.show(model.get('id') + ':unlink_confirmation', {
            level: 'confirmation',
            messages: app.utils.formatString(app.lang.get('NTC_UNLINK_CONFIRMATION_FORMATTED'), [context]),
            onConfirm: function() {
                model.destroy({
                    showAlerts: true,
                    relate: true,
                    success: self._getRemoveModelCompleteCallback()
                });
            }
        });
    },

    /**
     * @inheritdoc
     *
     * New model related properties are injected into each model.
     */
    _renderHtml: function() {
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }

        var tab = this.tabs[this.settings.get('activeTab')];

        var recordsTpl = this._getRecordsTemplate(tab.module);

        this.toolbarHtml = this._toolbarTpl(this);
        this.tabsHtml = this._tabsTpl(this);
        this.recordsHtml = recordsTpl(this);

        this.row_actions = tab.row_actions;

        this._super('_renderHtml');
    },

    /**
     *  Handle Avatar display, in case image doesn't exist.
     *
     *  FIXME: render avatar should happen when rendering each row, after pagination.(SC-2605)
     *  @private
     */
    _renderAvatars: function() {
        this.$('img.avatar')
            .on('load', function() {
                $(this).removeClass('hide');
            })
            .on('error', function() {
                $(this).parent().removeClass('avatar avatar-md')
                                .addClass('label label-module label-module-md label-Users');
                $(this).parent().find('span').removeClass('hide');
            });
        this.$('img.avatar').each(function() {
            var img = $(this);
            img.attr('src', img.data('src'));
        });
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        _.each(this.tabs, function(tab) {
            tab.collection.off(null, null, this);
        });

        this._super('_dispose');
    }
})
