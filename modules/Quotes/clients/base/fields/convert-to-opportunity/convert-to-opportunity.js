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
 * @class View.Fields.Base.Quotes.ConvertToOpportunity
 * @alias SUGAR.App.view.fields.BaseQuotesConvertToOpportunity
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     *
     * @param {Object} options
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'rowaction';

        this.context.on('button:convert_to_opportunity:click', this._onCreateOppFromQuoteClicked, this);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('sync', this._toggleDisable, this);
        this.model.on('change:opportunity_id', this._toggleDisable, this);
    },

    /**
     * Handler for when "Create Opp from Quote" is clicked
     * @private
     */
    _onCreateOppFromQuoteClicked: function() {
        var id = this.model.get('id');
        var url = app.api.buildURL('Quotes/' + id + '/opportunity');

        app.alert.show('convert_to_opp', {
            level: 'info',
            title: app.lang.get('LBL_QUOTE_TO_OPPORTUNITY_STATUS'),
            messages: ['']
        });

        app.api.call(
            'create',
            url,
            null,
            {
                success: this._onCreateOppFromQuoteCallback,
                error: this._onCreateOppFromQuoteError
            });
    },

    /**
     * Success callback for Create Opp From Quote
     * @param data Data from the server
     * @private
     */
    _onCreateOppFromQuoteCallback: function(data) {
        var id = data.record.id;
        var url = 'Opportunities/' + id;
        app.alert.dismiss('convert_to_opp');
        app.router.navigate(url, {trigger: true});
    },

    /**
     * Error callback for Create Opp From Quote
     * @param data
     * @private
     */
    _onCreateOppFromQuoteError: function(data) {
        app.alert.dismiss('convert_to_opp');
        app.alert.show('error_convert', {
            level: 'error',
            title: app.lang.get('LBL_ERROR'),
            messages: [data.message]
        });
    },

    /**
     * Reusable method for the event actions
     *
     * @private
     */
    _toggleDisable: function() {
        var opportunityId = this.model.get('opportunity_id');
        this.setDisabled(!(_.isUndefined(opportunityId) || _.isEmpty(opportunityId)));
    }
});
