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
 * @class View.Views.Base.Reports.FilterRowsView
 * @alias SUGAR.App.view.views.BaseReportsFilterRowsView
 * @extends View.Views.Base.FilterRowsView
 */
({
    extendsFrom: 'FilterRowsView',

    /**
     * @inheritdoc
     */
    loadFilterFields: function(module) {
        this._super('loadFilterFields', [module]);
        // last_run_date is a related datetime fields and shouldn't rely on its id_name
        if (this.fieldList && this.fieldList.last_run_date) {
            delete this.fieldList.last_run_date.id_name;
        }
    }
})
