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
({
    extendsFrom: 'ConvertResultsView',

    /**
     * Fetches the data for the leads model
     */
    populateResults: function() {
        if (!_.isEmpty(this.model.get('lead_id'))){
            var leads = app.data.createBean('Leads', { id: this.model.get('lead_id')});
            leads.fetch({
                success: _.bind(this.populateLeadCallback, this)
            });
        }
    },

    /**
     * Success callback for retrieving associated lead model
     * @param leadModel
     */
    populateLeadCallback: function (leadModel) {
        var rowTitle;

        this.associatedModels.reset();

        rowTitle = app.lang.get('LBL_CONVERTED_LEAD',this.module);

        leadModel.set('row_title', rowTitle);

        this.associatedModels.push(leadModel);

        app.view.View.prototype.render.call(this);
    }
})
