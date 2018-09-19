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
    extendsFrom: 'MergeDuplicatesView',

    /**
     * @inheritdoc
     */
    _initializeMergeFields: function(module) {
        var config = app.metadata.getModule('Forecasts', 'config');
        if (!config || !config.is_setup) {
            if(!_.contains(this.fieldNameBlacklist, 'commit_stage')) {
                this.fieldNameBlacklist.push('commit_stage');
            }
        } else if (_.contains(this.fieldNameBlacklist, 'commit_stage')) {
            this.fieldNameBlacklist.splice(_.indexOf(this.fieldNameBlacklist, 'commit_stage'), 1);
        }

        this._super('_initializeMergeFields', [module]);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this._super('bindDataChange');

        var config = app.metadata.getModule('Forecasts', 'config');

        if(config && config.is_setup && config.forecast_by === 'RevenueLineItems') {
            // make sure forecasts exists and is setup
            this.collection.on('change:sales_stage change:commit_stage reset', function(model) {
                var myModel = model;

                //check to see if this is a collection (for the reset event), use this.primaryRecord instead if true;
                if (!_.isUndefined(model.models)) {
                    myModel = this.primaryRecord;
                }
                var salesStage = myModel.get('sales_stage'),
                    commit_stage = this.getField('commit_stage');

                if(salesStage) {
                    if(_.contains(config.sales_stage_won, salesStage)) {
                        // check if the sales_stage has changed to a Closed Won stage
                        if(config.commit_stages_included.length) {
                            // set the commit_stage to the first included stage
                            myModel.set('commit_stage', _.first(config.commit_stages_included));
                        } else {
                            // otherwise set the commit stage to just "include"
                            myModel.set('commit_stage', 'include');
                        }
                        commit_stage.setDisabled(true);
                        this.$('input[data-record-id="' + myModel.get('id') + '"][name="copy_commit_stage"]').prop("checked", true);
                    } else if(_.contains(config.sales_stage_lost, salesStage)) {
                        // check if the sales_stage has changed to a Closed Lost stage
                        // set the commit_stage to exclude
                        myModel.set('commit_stage', 'exclude');
                        commit_stage.setDisabled(true);
                        this.$('input[data-record-id="' + myModel.get('id') + '"][name="copy_commit_stage"]').prop("checked", true);
                    } else {
                        commit_stage.setDisabled(false);
                    }
                }
            }, this);
        }
    }
})
