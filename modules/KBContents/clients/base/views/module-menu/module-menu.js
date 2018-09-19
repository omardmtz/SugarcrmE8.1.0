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
 * Module menu provides a reusable and easy render of a module Menu.
 *
 * This also helps doing customization of the menu per module and provides more
 * metadata driven features.
 *
 * @class View.Views.Base.KBContents.ModuleMenuView
 * @alias SUGAR.App.view.views.BaseKBContentsModuleMenuView
 * @extends View.Views.Base.ModuleMenuView
 */
({
    extendsFrom: 'ModuleMenuView',

    /**
     * Root ID of a shown NestedSet.
     * @property {string}
     */
    categoryRoot: null,

    /**
     * Module which implements NestedSet.
     */
    moduleRoot: null,

    /**
     * Panel label.
     */
    label: null,

    /**
     * @inheritdoc
     *
     * Init additional properties and events.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        var module = this.meta.config.config_provider || this.context.get('module'),
            config = app.metadata.getModule(module, 'config');
        this.categoryRoot = this.meta.config.category_root || config.category_root || '';
        this.moduleRoot = this.meta.config.data_provider || module;

        this.label = this.meta.label || '';

        this.events = _.extend({}, this.events, {
            'click [data-event="tree:list:fire"]': 'handleCategoriesList'
        });
    },

    /**
     * Handle click on KB category menu item.
     */
    handleCategoriesList: function() {
        var treeOptions = {
            category_root: this.categoryRoot,
            module_root: this.moduleRoot,
            plugins: ['dnd', 'contextmenu'],
            isDrawer: true
        };

        var treeCallbacks = {
                'onSelect': function() {
                    return;
                },
                'onRemove': function(node) {
                    if (this.context.parent) {
                        this.context.parent.trigger('kbcontents:category:deleted', node);
                    }
                }
            },
        // @TODO: Find out why params from context for drawer don't pass to our view tree::_initSettings
            context = _.extend({}, this.context, {treeoptions: treeOptions, treecallbacks: treeCallbacks});
        if (app.drawer.getActiveDrawerLayout().module === this.moduleRoot) {
            app.drawer.closeImmediately();
        }
        app.drawer.open({
            layout: 'nested-set-list',
            context: {
                module: this.moduleRoot,
                parent: context,
                title: app.lang.getModString(this.label, this.module),
                treeoptions: treeOptions,
                treecallbacks: treeCallbacks
            }
        });
    },

    /**
     * @inheritdoc
     */
    populate: function(tplName, filter, limit) {
        if (limit <= 0) {
            return;
        }
        filter =  _.union([], filter, this.meta.filterDef || []);
        this.getCollection(tplName).fetch({
            'showAlerts': false,
            'fields': ['id', 'name'],
            'filter': filter,
            'limit': limit,
            'success': _.bind(function() {
                this._renderPartial(tplName);
            }, this)
        });
    }
})
