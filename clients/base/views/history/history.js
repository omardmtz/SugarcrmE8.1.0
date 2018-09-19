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
 * History dashlet takes advantage of the tabbed dashlet abstraction by using
 * its metadata driven capabilities to configure its tabs in order to display
 * historic information about specific modules.
 *
 * @class View.Views.Base.HistoryView
 * @alias SUGAR.App.view.views.BaseHistoryView
 * @extends View.Views.Base.TabbedDashletView
 */
({
    extendsFrom: 'TabbedDashletView',

    /**
     * @inheritdoc
     *
     * @property {Object} _defaultSettings
     * @property {Number} _defaultSettings.filter Number of past days against
     *   which retrieved records will be filtered, supported values are '7',
     *   '30' and '90' days, defaults to '7'.
     * @property {Number} _defaultSettings.limit Maximum number of records to
     *   load per request, defaults to '10'.
     * @property {String} _defaultSettings.visibility Records visibility
     *   regarding current user, supported values are 'user' and 'group',
     *   defaults to 'user'.
     */
    _defaultSettings: {
        filter: 7,
        limit: 10,
        visibility: 'user'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta = options.meta || {};
        options.meta.template = 'tabbed-dashlet';

        this._super('initialize', [options]);
        this.tbodyTag = 'ul[data-action="pagination-body"]';
    },

    /**
     * Retrieves custom filters.
     *
     * @param {number} index Tab index.
     * @return {Array} Custom filters.
     * @protected
     */
    _getFilters: function(index) {
        var filterStr = app.date().subtract(this.settings.get('filter'), 'days').formatServer(true);

        var tab = this.tabs[index],
            filter = {},
            filters = [];

        filter[tab.filter_applied_to] = {$gte: filterStr};

        filters.push(filter);

        return filters;
    },

    /**
     * New model related properties are injected into each model.
     * Update the picture url's property for model's assigned user.
     *
     * @param {Bean} model Appended new model.
     */
    bindCollectionAdd: function(model) {
        var pictureUrl = app.api.buildFileURL({
            module: 'Users',
            id: model.get('assigned_user_id'),
            field: 'picture'
        });
        model.set('picture_url', pictureUrl);
        this._super('bindCollectionAdd', [model]);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.$('.select2').select2('destroy');

        this._super("_dispose");
    },

    /**
     * Open up a drawer to archive email.
     * @param event
     * @param params
     */
    archiveEmail: function(event, params) {
        var parentName = app.utils.getRecordName(this.model);

        app.utils.openEmailCreateDrawer(
            'create',
            {
                related: this.model,
                // Don't set email_address_id. It will be set when the email is
                // archived.
                to: app.data.createBean('EmailParticipants', {
                    _link: 'to',
                    parent: _.extend({type: this.model.module}, app.utils.deepCopy(this.model)),
                    parent_type: this.model.module,
                    parent_id: this.model.get('id'),
                    parent_name: parentName
                })
            },
            _.bind(function(model) {
                var links;

                if (model) {
                    this.layout.reloadDashlet();
                    links = app.utils.getLinksBetweenModules(this.context.parent.get('module'), 'Emails');

                    _.each(links, function(link) {
                        this.context.parent.trigger('panel-top:refresh', link.name);
                    }, this);
                }
            }, this)
        );
    }
})
