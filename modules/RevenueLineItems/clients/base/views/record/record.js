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
    extendsFrom: 'RecordView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['HistoricalSummary', 'CommittedDeleteWarning', 'MassQuote']);
        this._super('initialize', [options]);
        app.utils.hideForecastCommitStageField(this.meta.panels);
    },

    /**
     * @inheritdoc
     */
    cancelClicked: function() {
        /**
         * todo: this is a sad way to work around some problems with sugarlogic and revertAttributes
         * but it makes things work now. Probability listens for Sales Stage to change and then by
         * SugarLogic, it updates probability when sales_stage changes. When the user clicks cancel,
         * it goes to revertAttributes() which sets the model back how it was, but when you try to
         * navigate away, it picks up those new changes as unsaved changes to your model, and tries to
         * falsely warn the user. This sets the model back to those changed attributes (causing them to
         * show up in this.model.changed) then calls the parent cancelClicked function which does the
         * exact same thing, but that time, since the model was already set, it doesn't see anything in
         * this.model.changed, so it doesn't warn the user.
         */
        var changedAttributes = this.model.changedAttributes(this.model.getSynced());
        this.model.set(changedAttributes, { revert: true });
        this._super('cancelClicked');
    },

    /**
     * extend save options
     * @param {Object} options save options.
     * @return {Object} modified success param.
     */
    getCustomSaveOptions: function(options) {
        // make copy of original function we are extending
        var origSuccess = options.success;
        // return extended success function with added alert
        return {
            success: _.bind(function() {
                if (_.isFunction(origSuccess)) {
                    origSuccess();
                }
                if (!_.isEmpty(this.model.get('quote_id'))) {
                    app.alert.show('save_rli_quote_notice', {
                        level: 'info',
                        messages: app.lang.get(
                            'SAVE_RLI_QUOTE_NOTICE',
                            'RevenueLineItems'
                        ),
                        autoClose: true
                    });
                }
            }, this)
        };
    },

    /**
     * Bind to model to make it so that it will re-render once it has loaded.
     */
    bindDataChange: function() {
        this.model.on('duplicate:before', this._handleDuplicateBefore, this);
        this._super('bindDataChange');
    },

    /**
     * Handle what should happen before a duplicate is created
     *
     * @param {Backbone.Model} new_model
     * @private
     */
    _handleDuplicateBefore: function(new_model) {
        new_model.unset('quote_id');
        new_model.unset('quote_name');
    }
})
