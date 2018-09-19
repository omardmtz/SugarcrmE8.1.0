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
 * @class View.Fields.Base.ManageSubscriptionField
 * @alias SUGAR.App.view.fields.BaseManageSubscriptionField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    initialize: function (options) {
        this._super("initialize", [options]);
        this.type = 'rowaction';
    },

    /**
     * Event to navigate to the BWC Manage Subscriptions
     */
    rowActionSelect: function() {

        var route = app.bwc.buildRoute('Campaigns', this.model.id, 'Subscriptions', {
            return_module: this.module,
            return_id: this.model.id
        });
        app.router.navigate(route, {trigger: true});
    },

    /**
     * @inheritdoc
     * Check access for Campaigns Module.
     */
    hasAccess: function() {
        var access = app.acl.hasAccess('view', 'Campaigns');
        return access && this._super('hasAccess');
    }
})
