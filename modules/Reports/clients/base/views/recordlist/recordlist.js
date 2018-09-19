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
 * @class View.Views.Base.Reports.RecordlistView
 * @alias SUGAR.App.view.views.BaseReportsRecordlistView
 * @extends View.Views.Base.RecordListView
 */
({
    extendsFrom: 'RecordListView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.contextEvents = _.extend({}, this.contextEvents, {
            'list:editreport:fire': 'editReport'
        });
        this._super('initialize', [options]);
    },

    /**
     * Go to the Reports Wizard Edit page
     *
     * @param {Data.Bean} model Selected row's model.
     * @param {RowActionField} field
     */
    editReport: function(model, field) {
        var route = app.bwc.buildRoute('Reports', model.id, 'ReportsWizard');
        app.router.navigate(route, {trigger: true});
    }
})
