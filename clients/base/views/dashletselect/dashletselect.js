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
 * "Add a dashlet" view.
 * Displays a list of dashlets filtered according to current module and layout.
 *
 * @class View.Views.Base.DashletselectView
 * @alias SUGAR.App.view.views.DashletselectView
 * @extends View.Views.Base.FilteredListView
 */
({
    extendsFrom: 'FilteredListView',

    /**
     * @inheritdoc
     *
     * Displays a filtered list of dashlets.
     * Uses client-generated collection with disabled load state, custom
     * preview and select actions.
     */
    initialize: function(options) {
        var meta = app.metadata.getView(null, 'dashletselect') || {};
        options.meta = _.extend({}, meta, options.meta || {});

        this._super('initialize', [options]);

        // To avoid reset while sorting.
        this.context = _.extend(_.clone(this.context), {
            resetLoadFlag: function() {
                return;
            }
        });

        this.context.on('dashletlist:select-and-edit', function(model) {
            this.selectDashlet(model.get('metadata'));
        }, this);

        this.context.on('dashletlist:preview:fire', function(model) {
            this.previewDashlet(model.get('metadata'));
        }, this);
    },

    /**
     * Load dashlet preview by passing preview metadata
     *
     * @param {Object} metadata Preview metadata.
     */
    previewDashlet: function(metadata) {
        var layout = this.layout,
            previewLayout;
        while (layout) {
            if (layout.getComponent('preview-pane')) {
                previewLayout = layout.getComponent('preview-pane').getComponent('dashlet-preview');
                previewLayout.showPreviewPanel();
                break;
            }
            layout = layout.layout;
        }

        if (previewLayout) {
            // If there is no preview property, use the config property
            if (!metadata.preview) {
                metadata.preview = metadata.config;
            }
            var previousComponent = _.last(previewLayout._components);
            if (previousComponent.name !== 'dashlet-preview') {
                var index = previewLayout._components.length - 1;
                previewLayout._components[index].dispose();
                previewLayout.removeComponent(index);
            }

            var contextDef,
                component = {
                    label: app.lang.get(metadata.label, metadata.preview.module),
                    type: metadata.type,
                    preview: true
                };
            if (metadata.preview.module || metadata.preview.link) {
                contextDef = {
                    skipFetch: false,
                    forceNew: true,
                    module: metadata.preview.module,
                    link: metadata.preview.link
                };
            } else if (metadata.module) {
                contextDef = {
                    module: metadata.module
                };
            }

            component.view = _.extend({module: metadata.module}, metadata.preview, component);
            if (contextDef) {
                component.context = contextDef;
            }

            previewLayout.initComponents([
                {
                    layout: {
                        type: 'dashlet',
                        label: app.lang.get(metadata.preview.label || metadata.label, metadata.preview.module),
                        preview: true,
                        components: [
                            component
                        ]
                    }
                }
            ], this.context);
            previewLayout.loadData();
            previewLayout.render();
        }
    },

    /**
     * Load dashlet configuration view by passing configuration metadata
     *
     * @param {Object} metadata Configuration metadata.
     */
    selectDashlet: function(metadata) {
        var model = new app.Bean();

        app.drawer.load({
            layout: {
                type: 'dashletconfiguration',
                components: [
                    {
                        view: _.extend({}, metadata.config, {
                            label: app.lang.get(metadata.label, metadata.config.module),
                            type: metadata.type,
                            config: true,
                            module: metadata.config.module || metadata.module
                        })
                    }
                ]
            },
            context: {
                module: metadata.config.module || metadata.module,
                model: model,
                forceNew: true,
                skipFetch: true
            }
        });
    },

    /**
     * Filtering the available dashlets with the current page's module and
     * layout view.
     *
     * @param {Array} dashlets A list of dashlet components.
     * @return {Array} A list of filtered dashlet set.
     */
    getFilteredList: function(dashlets) {
        var parentModule = app.controller.context.get('module');
        var parentView = app.controller.context.get('layout');

        return _.chain(dashlets)
            .filter(function(dashlet) {
                var filter = dashlet.filter;
                // if there is no filter for this dashlet, include it
                if (_.isUndefined(filter)) {
                    return true;
                }

                var filterModules = filter.module || [parentModule];
                var filterViews = filter.view || [parentView];

                if (_.isString(filterModules)) {
                    filterModules = [filterModules];
                }
                if (_.isString(filterViews)) {
                    filterViews = [filterViews];
                }

                //if the filter is matched, then it returns true
                return _.contains(filterModules, parentModule) &&
                    _.contains(filterViews, parentView);
            })
            .value();
    },

    /**
     * Iterates dashlets metadata and extract the dashlet components among them.
     *
     * @param {String} type The component type (layout|view).
     * @param {String} name The component name.
     * @param {String} module The module name.
     * @param {Object} meta The metadata.
     * @return {Array} list of available dashlets.
     * @private
     */
    _getDashlets: function(type, name, module, meta) {
        var dashlets = [],
            hadDashlet = meta && meta.dashlets &&
                app.view.componentHasPlugin({
                    type: type,
                    name: name,
                    module: module,
                    plugin: 'Dashlet'
                });
        if (!hadDashlet) {
            return dashlets;
        }
        _.each(meta.dashlets, function(dashlet) {
            if (!dashlet.config) {
                return;
            }
            var description = app.lang.get(dashlet.description, dashlet.config.module);
            if (!app.acl.hasAccess('access', module || dashlet.config.module)) {
                return;
            }
            dashlets.push({
                type: name,
                filter: dashlet.filter,
                metadata: _.extend({
                    component: name,
                    module: module,
                    type: name
                }, dashlet),
                title: app.lang.get(dashlet.label, dashlet.config.module),
                description: description
            });
        }, this);
        return dashlets;
    },

    /**
     * Retrieves all base view's metadata.
     *
     * @return {Array} All base view's metadata.
     * @private
     */
    _addBaseViews: function() {
        var components = [];
        _.each(app.metadata.getView(), function(view, name) {
            var dashlets = this._getDashlets('view', name, null, view.meta);
            if (!_.isEmpty(dashlets)) {
                components = _.union(components, dashlets);
            }
        }, this);
        return components;
    },

    /**
     * Retrieves all module view's metadata.
     *
     * @return {Array} The module view's metadata.
     * @private
     */
    _addModuleViews: function() {
        var components = [];
        _.each(app.metadata.getModuleNames({filter: 'visible'}), function(module) {
            _.each(app.metadata.getView(module), function(view, name) {
                var dashlets = this._getDashlets('view', name, module, view.meta);
                if (!_.isEmpty(dashlets)) {
                    components = _.union(components, dashlets);
                }
            }, this);
        }, this);
        return components;
    },

    /**
     * @inheritdoc
     *
     * Instead of fetching context, it will retrieve all dashable components
     * based on metadata. Sorts the components by `title` alphabetically.
     */
    loadData: function() {
        if (this.collection.length) {
            this.filteredCollection = this.collection.models;
            return;
        }

        var dashletCollection = _.union(this._addBaseViews(), this._addModuleViews()),
            filteredDashletCollection = this.getFilteredList(dashletCollection);

        this.collection.comparator = function(model) {
            return model.get('title');
        };

        this.collection.add(filteredDashletCollection);
        this.collection.dataFetched = true;
        this._renderData();
    },

    /**
     * @inheritdoc
     *
     * DashletSelect isn't a read module, no need to compare fields with defs.
     */
    getFields: function() {
        return _.flatten(_.pluck(this.meta.panels, 'fields'));
    }

})
