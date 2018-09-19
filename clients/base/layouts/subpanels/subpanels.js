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
 * @class View.Layouts.Base.SubpanelsLayout
 * @alias SUGAR.App.view.layouts.BaseSubpanelsLayout
 * @extends View.Layout
 */
({
    /**
     * @inheritdoc
     */
    className: 'subpanels-layout',

    /**
     * Default settings used for the sortable plugin.
     *
     * - `{Boolean} sortable` Whether or not this layout should be sortable.
     * - `{Boolean} showAlerts` Whether or not to show alerts when the subpanel
     *   ordering is changed.
     *
     * These defaults can be overridden through the metadata (shown below) or by
     * customizing this layout.
     *
     *     // ...
     *     'settings' => array(
     *         'sortable' => false,
     *         //...
     *     ),
     *     //...
     *
     * @property {Object}
     * @protected
     */
    _defaultSettings: {
        showAlerts: true,
        sortable: true
    },

    /**
     * Settings after applying metadata settings on top of
     * {@link #_defaultSettings}.
     *
     * @property {Object}
     * @protected
     */
    _settings: {},

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._initSettings();
        this._bindEvents();
    },

    /**
     * Merges settings defined in the metadata with {@link #_defaultSettings}.
     *
     * @protected
     * @chainable
     */
    _initSettings: function() {
        this._settings = _.extend({},
            this._defaultSettings,
            this.meta && this.meta.settings || {}
        );
        return this;
    },

    /**
     * Binds events that this layout uses.
     *
     * @protected
     */
    _bindEvents: function() {
        if (this.layout) {
            this.listenTo(this.layout, 'subpanel:change', this.showSubpanel);
        }

        this.on('subpanels:reordered', this._saveNewOrder, this);
    },

    /**
     * Initializes the jQuery Sortable plugin to this layout, only if the
     * `sortable` property on the {@link #_settings} object is set to `true`.
     *
     * By default, the `handle` to drag subpanels is specified as the
     * `panel-top` component. The `helper` attribute is set to `clone` because
     * Firefox dispatches a click event when the dragged element is removed and
     * inserted by jQuery, see:
     * [bug ticket](https://bugzilla.mozilla.org/show_bug.cgi?id=787944).
     *
     * @protected
     * @chainable
     */
    _initSortablePlugin: function() {
        if (this._settings && this._settings.sortable === true) {
            this.$el.sortable({
                axis: 'y',
                containment: this.$el,
                handle: '[data-sortable-subpanel=true]',
                helper: 'clone',
                tolerance: 'pointer',
                scrollSensitivity: 50,
                scrollSpeed: 15,
                update: _.bind(this.handleSort, this)
            });
        }
        return this;
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this._initSortablePlugin();
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (!_.isEmpty(this.$el.data('sortable'))) {
            this.$el.sortable('destroy');
        }
        this._super('_dispose');
    },

    /**
     * The subpanels list is saved into local storage.
     *
     * Displays a `success` alert message if the `showAlerts` setting is `true`.
     *
     * This method is called by the `subpanel:reordered` event, see
     * {@link #initialize}.
     *
     * @protected
     * @param {Object} component The `subpanel` component
     * @param {String[]} order The new order of `subpanel` components.
     */
    _saveNewOrder: function(component, order) {
        var key = app.user.lastState.buildKey('order', 'subpanels', this.module);
        app.user.lastState.set(key, order);

        if (this._settings.showAlerts === true) {
            app.alert.show('subpanel_order_updated', {
                level: 'success',
                messages: app.lang.get('LBL_SAVED_LAYOUT', this.module),
                autoClose: true
            });
        }
    },

    /**
     * Handler for subpanel re-order.
     *
     * @param {Event} evt The jQuery update event.
     * @param {Object} ui The jQuery UI object.
     */
    handleSort: function(evt, ui) {
        var newOrder = this.$el.sortable('toArray', {
                attribute: 'data-subpanel-link'
            });

        this.trigger('subpanels:reordered', this, newOrder);
    },

    /**
     * Removes subpanels that user doesn't have access to. SP-924: Error message when opening subpanel
     * user doesn't have access to.
     *
     * @param {Array} components list of child components from layout definition
     * @return {Object} pruned components
     * @private
     * @override
     */
    _pruneNoAccessComponents: function(components) {
        var prunedComponents = [];
        var layoutFromContext = this.context ? this.context.get('layout') || this.context.get('layoutName') : null;
        this.layoutType = layoutFromContext ? layoutFromContext : app.controller.context.get('layout');
        this.aclToCheck = this.aclToCheck || 'list';
        _.each(components, function(component) {
            var relatedModule,
                link = component.context ? component.context.link : null;
            if (link) {
                relatedModule = app.data.getRelatedModule(this.module, link);
                if (!relatedModule || relatedModule && app.acl.hasAccess(this.aclToCheck, relatedModule)) {
                    prunedComponents.push(component);
                }
            }
        }, this);
        return prunedComponents;
    },

    /**
     *
     * Removes hidden subpanels from list of components before adding them to layout
     *
     * @param {Array} components list of child components from layout definition
     * @return {Object} pruned components
     * @private
     * @override
     */
    _pruneHiddenComponents: function(components) {
        var hiddenSubpanels = app.metadata.getHiddenSubpanels();
        var visibleSubpanels = _.filter(components, function(component){
            var relatedModule = app.data.getRelatedModule(this.module, component.context.link);
            return _.isEmpty(_.find(hiddenSubpanels, function(hiddenPanel){
                if (relatedModule !== false) {
                    //hidden subpanels seem to come back in lower case, so we do a case insenstiive compare of module names
                    return hiddenPanel.toLowerCase() === relatedModule.toLowerCase();
                }
                return true;
            }));
        }, this);
        return visibleSubpanels;
    },

    /**
     * @inheritdoc
     *
     * We override this method which is called early in the Sidecar framework to
     * prune any hidden or acl prohibited components.
     */
    _addComponentsFromDef: function(components, context, module) {
        // First checks for hidden components, then checks for ACLs on those components.
        var allowedComponents = this._pruneHiddenComponents(components);
        allowedComponents = this._pruneNoAccessComponents(allowedComponents);
        allowedComponents = this.reorderSubpanels(allowedComponents);

        // Call original Layout with pruned components
        this._super('_addComponentsFromDef', [allowedComponents, context, module]);
        this._markComponentsAsSubpanels();
        this._disableSubpanelToggleButton(allowedComponents);
    },

    /**
     * Orders the `subpanel` components and strips out any components from the
     * specified `order` that are no longer available.
     *
     * @param {Array} components The list of `subpanel` component objects.
     * @return {Array} The ordered list of `subpanel` component objects.
     */
    reorderSubpanels: function(components) {
        var key = app.user.lastState.buildKey('order', 'subpanels', this.module),
            order = app.user.lastState.get(key);

        if (_.isEmpty(order)) {
            return components;
        }

        var componentOrder = _.pluck(_.pluck(components, 'context'), 'link');
        order = _.intersection(order, componentOrder);

        _.each(order, function(link, index) {
            var comp = _.find(components, function(comp) {
                return comp.context.link === link;
            });
            comp.position = ++index;
        });

        components = _.sortBy(components, function(comp) {
            return comp.position;
        });

        return components;
    },

    /**
     * If no subpanels are left after pruning hidden and ACL-prevented
     * subpanels, we disable the filter panel's subpanel toggle button.
     *
     * @param {Array} allowedComponents The pruned subpanels.
     */
    _disableSubpanelToggleButton: function(allowedComponents) {
        if (!this.layout || !_.isEmpty(allowedComponents)) {
            return;
        }

        this.layout.trigger('filterpanel:change', 'activitystream', true, true);
        this.layout.trigger('filterpanel:toggle:button', 'subpanels', false);
    },

    /**
     * Show the subpanel for the given linkName and hide all others
     * @param {String} linkName name of subpanel link
     */
    showSubpanel: function(linkName) {
        if (!app.config.collapseSubpanels) {
            // this.layout is the filter layout which subpanels is child of; we
            // use it here as it has a last_state key in its meta
            // FIXME: TY-499 will address removing the dependancy on this.layout
            var cacheKey = app.user.lastState.key('subpanels-last', this.layout);
            if (linkName) {
                app.user.lastState.set(cacheKey, linkName);
            } else {
                // Fixes SP-836; esentially, we need to clear subpanel-last-<module>
                // anytime 'All' selected.
                app.user.lastState.remove(cacheKey);
            }
        }

        _.each(this._components, function(component) {
            var link = component.context.get('link');
            if (!linkName) {
                component.show();
            } else if (linkName === link) {
                component.show();
                component.context.set('collapsed', false);
            } else {
                component.hide();
            }
        });
    },

    /**
     * Mark component context as being subpanels
     */
    _markComponentsAsSubpanels: function() {
        _.each(this._components, function(component) {
            component.context.set("isSubpanel", true);
        });
    },

    /**
     * Load data for all subpanels. Need to override the layout's loadData() because
     * it calls loadData() for the context, which we do not want to do here.
     * @param options
     */
    loadData: function(options) {
        var self = this,
            load = function(){
                _.each(this._components, function(component) {
                    component.loadData(options);
                });
            };
        if (self.context.parent && !self.context.parent.isDataFetched()) {
            var parent = this.context.parent.get("model");
            parent.once("sync", load);
        }
        else {
            load();
        }
    }
})
