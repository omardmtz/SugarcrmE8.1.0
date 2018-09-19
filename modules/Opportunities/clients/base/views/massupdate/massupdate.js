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
    extendsFrom: "MassupdateView",
    
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['DisableMassDelete', 'CommittedDeleteWarning']);
        this._super("initialize", [options]);
    },

    /**
     *
     * @inheritdoc
     */
    setMetadata: function(options) {
        var config = app.metadata.getModule('Forecasts', 'config');

        this._super("setMetadata", [options]);

        if (!config || (config && !config.is_setup)) {
            _.each(options.meta.panels, function(panel) {
                _.every(panel.fields, function (item, index) {
                    if (_.isEqual(item.name, "commit_stage")) {
                        panel.fields.splice(index, 1);
                        return false;
                    }
                    return true;
                }, this);
            }, this);
        }
    },

    /**
     * @inheritdoc
     */
    save: function(forCalcFields) {
        var forecastCfg = app.metadata.getModule("Forecasts", "config");
        if (forecastCfg && forecastCfg.is_setup) {
            // Forecasts is enabled and setup
            var hasCommitStage = _.some(this.fieldValues, function(field) {
                    return field.name === 'commit_stage';
                }),
                hasClosedModels = false;

            if(!hasCommitStage && this.defaultOption.name === 'commit_stage') {
                hasCommitStage = true;
            }

            if(hasCommitStage) {
                hasClosedModels = this.checkMassUpdateClosedModels();
            }

            if(!hasClosedModels) {
                // if this has closed models, first time through will uncheck but not save
                // if this doesn't it will save like normal
                this._super('save', [forCalcFields]);
            }
        } else {
            // Forecasts is not enabled and the commit_stage field isn't in the mass update list
            this._super('save', [forCalcFields]);
        }
    }
})
