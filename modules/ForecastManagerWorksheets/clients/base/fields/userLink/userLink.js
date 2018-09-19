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
 * @class View.Fields.Base.ForecastsManagerWorksheets.UserLinkField
 * @alias SUGAR.App.view.fields.BaseForecastsManagerWorksheetsUserLinkField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * Attach a click event to <a class="worksheetManagerLink"> field
     */
    events: { 'click a.worksheetManagerLink': 'linkClicked' },

    /**
     * Holds the user_id for passing into userTemplate
     */
    uid: '',

    initialize: function(options) {
        this.uid = this.model.get('user_id');

        app.view.Field.prototype.initialize.call(this, options);
        return this;
    },

    format: function(value) {
        var su = this.context.get('selectedUser') || this.context.parent.get('selectedUser') || app.user.toJSON();
        if (value == su.full_name && su.id == app.user.get('id')) {
            var hb = Handlebars.compile("{{str key module context}}");
            value = hb({'key': 'LBL_MY_MANAGER_LINE', 'module': this.module, 'context': su});
        }

        return value;
    },

    /**
     * Handle a user link being clicked
     * @param event
     */
    linkClicked: function(event) {
        var uid = $(event.target).data('uid');
        var selectedUser = {
            id: '',
            user_name: '',
            full_name: '',
            first_name: '',
            last_name: '',
            is_manager: false,
            showOpps: false,
            reportees: []
        };

        var options = {
            dataType: 'json',
            success: _.bind(function(data) {
                selectedUser.id = data.id;
                selectedUser.user_name = data.user_name;
                selectedUser.full_name = data.full_name;
                selectedUser.first_name = data.first_name;
                selectedUser.last_name = data.last_name;
                selectedUser.is_manager = data.is_manager;
                selectedUser.reports_to_id = data.reports_to_id;
                selectedUser.reports_to_name = data.reports_to_name;
                selectedUser.is_top_level_manager = data.is_top_level_manager || (data.is_manager && _.isEmpty(data.reports_to_id));

                var su = this.context.get('selectedUser') || this.context.parent.get('selectedUser') || app.user.toJSON();
                // get the current selected user, if the id's match up set the showOpps to be true)
                selectedUser.showOpps = (su.id == data.id);

                this.context.parent.trigger("forecasts:user:changed", selectedUser, this.context.parent);
            }, this)
        };

        myURL = app.api.buildURL('Forecasts', 'user/' + uid);
        app.api.call('read', myURL, null, options);
    }
})
