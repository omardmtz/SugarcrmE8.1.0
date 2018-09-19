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
 * @class View.Views.Base.Dashboards.RecordView
 * @alias SUGAR.App.view.views.BaseDashboardsRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',

    /**
     * @inheritdoc
     */
    getDeleteMessages: function() {
        var messages = {};
        var modelName = app.lang.get(this.model.get('name'), this.model.get('dashboard_module'));

        messages.confirmation = app.lang.get('LBL_DELETE_DASHBOARD_CONFIRM', this.module, {name: modelName});
        messages.success = app.lang.get('LBL_DELETE_DASHBOARD_SUCCESS', this.module, {
            name: modelName
        });

        return messages;
    }
})
