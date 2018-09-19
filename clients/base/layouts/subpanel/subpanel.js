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
 * @class View.Layouts.Base.SubpanelLayout
 * @alias SUGAR.App.view.layouts.BaseSubpanelLayout
 * @extends View.Layouts.Base.PanelLayout
 */
({
    extendsFrom: 'PanelLayout',

    /**
     * What is our current dataview
     */
    dataView: 'subpanel-list',

    /**
     * @override
     */
    initialize: function(options) {
        //Check for the override_subpanel_list_view from the parent layout metadata and replace the list view if found.
        if (options.meta && options.def && options.def.override_subpanel_list_view) {
            _.each(options.meta.components, function(def) {
                if (def.view && def.view.indexOf('subpanel-list') !== -1) {
                    def.view = options.def.override_subpanel_list_view;
                }
            });
            // set the dataview if we override it
            this.dataView = options.def.override_subpanel_list_view;
            // override last_state.id with "override_subpanel_list_view" for unique state name.
            if (options.meta.last_state.id) {
                options.meta.last_state.id = options.def.override_subpanel_list_view;
            }
        }

        if (options.meta && options.def && options.def.override_paneltop_view) {
            _.each(options.meta.components, function(def) {
                if (def.view && def.view == 'panel-top') {
                    def.view = options.def.override_paneltop_view;
                }
            });
        }

        this._super('initialize', [options]);

        // if the dataView variable does not equal 'subpanel-list', it means it was changed
        // and we need to set the correct dataView on the context so when the data is fetched via
        // the api, it pulls the correct fields.
        if (this.dataView !== 'subpanel-list') {
            this.context.set('dataView', this.dataView);
        }

        // FIXME in SC-3360 - this is a hacky flag to be able to fetch a collection of
        // links. We will be able to remove this code once we introduce the CollectionsAPI.
        var ignoreRole = this.context.get('ignore_role');
        if (ignoreRole) {
            var collection = this.collection;
            var options = collection.getOption() || {};
            var params = options.params || {};
            params.ignore_role = ignoreRole;
            collection.setOption('params', params);
        }

        // binding so subpanels can trigger other subpanels to reload by link name
        // example: ctx.trigger('subpanel:reload', {links: ['opportunities','revenuelineitems']});
        if (this.context.parent) {
            this.context.parent.on('subpanel:reload', function(args) {
                app.logger.warn('`subpanel:reload` is deprecated. Use `context.reloadData()` to reload and expand.');
                if (!_.isUndefined(args) && _.isArray(args.links) && _.contains(args.links, this.context.get('link'))) {
                    this.context.reloadData({recursive: false});
                }
            }, this);
        }
    },

    /**
     * @inheritdoc
     */
    show: function() {
        this.context.set('hidden', false);
        this._super('show');
    },

    /**
     * @inheritdoc
     */
    hide: function() {
        this.context.set('hidden', true);
        this._super('hide');
    }
})
