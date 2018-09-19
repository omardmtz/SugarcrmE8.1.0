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
 * @class View.Fields.Base.Forecasts.AssignQuotaField
 * @alias SUGAR.App.view.fields.BaseForecastsAssignQuotaField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    /**
     * Should be this disabled if it's not rendered?
     */
    disableButton: true,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'rowaction';
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.context.on('forecasts:worksheet:quota_changed', function() {
            this.disableButton = false;
            if (!this.disposed) {
                this.render();
            }
        }, this);

        this.context.on('forecasts:worksheet:committed', function() {
            this.disableButton = true;
            if (!this.disposed) {
                this.render();
            }
        }, this);

        this.context.on('forecasts:assign_quota', this.assignQuota, this);
    },

    /**
     * We override this so we can always disable the field
     *
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        // only set field as disabled if it's actually rendered into the dom
        // otherwise it will cause problems and not show correctly when disabled
        if (this.getFieldElement().length > 0) {
            this.setDisabled(this.disableButton);
        }
    },

    /**
     * Only show this if the current user is a manager and we are on their manager view
     *
     * @inheritdoc
     */
    hasAccess: function() {
        var su = (this.context.get('selectedUser')) || app.user.toJSON(),
            isManager = su.is_manager || false,
            showOpps = su.showOpps || false;
        return (su.id === app.user.get('id') && isManager && showOpps === false);
    },

    /**
     * Run the XHR Request to Assign the Quotas
     *
     * @param {string} worksheetType            What worksheet are we on
     * @param {object} selectedUser             What user is calling the assign quota
     * @param {string} selectedTimeperiod        Which timeperiod are we assigning quotas for
     */
    assignQuota: function(worksheetType, selectedUser, selectedTimeperiod) {
        app.api.call('create', app.api.buildURL('ForecastManagerWorksheets/assignQuota'), {
            'user_id': selectedUser.id,
            'timeperiod_id': selectedTimeperiod
        }, {
            success: _.bind(function(o) {
                app.alert.dismiss('saving_quota');
                app.alert.show('success', {
                    level: 'success',
                    autoClose: true,
                    autoCloseDelay: 10000,
                    title: app.lang.get("LBL_FORECASTS_WIZARD_SUCCESS_TITLE", "Forecasts") + ":",
                    messages: [app.lang.get('LBL_QUOTA_ASSIGNED', 'Forecasts')]
                });
                this.disableButton = true;
                this.context.trigger('forecasts:quota_assigned');
                if (!this.disposed) {
                    this.render();
                }
            }, this)
        });
    }
})
