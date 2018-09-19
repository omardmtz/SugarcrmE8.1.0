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
 * @class View.Views.Base.HistorySummaryHeaderpaneView
 * @alias SUGAR.App.view.views.BaseHistorySummaryHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    events: {
        'click a[name=cancel_button]': 'cancel'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.model = this.context.parent && this.context.parent.get('model') || this.model;
    },

    /**
     * @override
     */
    _formatTitle: function(title) {
        var parent = this._getParentModel();
        var recordName = this._getParentModelName();
        if (parent && recordName) {
            return new Handlebars.SafeString(app.lang.get(title, parent.module, {name: recordName}));
        }
        return title;
    },

    /**
     * Gets the parent model of this historical summary view.
     *
     * @return {Data.Bean} The parent model.
     * @private
     */
    _getParentModel: function() {
        return this.context.parent.get('model');
    },

    /**
     * Gets the name of the parent model.
     *
     * @return {string} The parent model name.
     * @protected
     */
    _getParentModelName: function() {
        var parent = this._getParentModel();
        return app.utils.formatNameModel(parent.module, parent.attributes) || parent.get('name');
    },

    /**
     * Cancel and close the drawer
     */
    cancel: function() {
        app.drawer.close();
    }
})
