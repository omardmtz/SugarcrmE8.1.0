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
 *
 * @class View.Views.Base.MultiSelectionListLinkView
 * @alias SUGAR.App.view.views.BaseMultiSelectionListLinkView
 * @extends View.Views.Base.MultiSelectionListView
 */
({
    extendsFrom: 'MultiSelectionListView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.meta.selection = _.extend({}, options.meta.selection, {isLinkAction: true});
    },

    /**
     * @override
     */
    initializeEvents: function() {
        this.context.on('selection-list:link:multi', this._selectMultipleAndClose, this);
        this.context.on('selection-list:select', this._refreshList, this);
    },

    /**
     * After a model is selected, refresh the list view and add the model to
     * selections.
     *
     * @private
     */
    _refreshList: function() {
        this.context.reloadData({
            recursive: false,
            error: function() {
                app.alert.show('server-error', {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR'
                });
            }
        });
    },

    /**
     * Selects multiple models to link and fire the mass link event.
     *
     * @private
     */
    _selectMultipleAndClose: function() {
        var selections = this.context.get('mass_collection');
        if (selections && selections.length > 0) {
            this.layout.once('list:masslink:complete', this._closeDrawer, this);
            this.layout.trigger('list:masslink:fire');
        }
    },

    /**
     * Closes the drawer and then refreshes record page with new links.
     *
     * @private
     */
    _closeDrawer: function(model, data, response) {
        app.drawer.close();

        var context = this.context.get('recContext');
        var view = this.context.get('recView');

        if (context.has('parentModel')) {
            var parentModel = context.get('parentModel');
            var syncedAttributes = parentModel.getSynced();
            var updatedAttributes = _.reduce(data.record, function(memo, val, key) {
                    if (!_.isEqual(syncedAttributes[key], val)) {
                        memo[key] = val;
                    }
                    return memo;
                }, {});
            parentModel.set(updatedAttributes);
            //Once parent model is reset, reset internal synced attributes as well
            parentModel.setSyncedAttributes(data.record);
        }

        context.set('skipFetch', false);
        context.reloadData();
    }
})
