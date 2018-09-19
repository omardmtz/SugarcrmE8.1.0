
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
    extendsFrom : 'RecordlistView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['CommittedDeleteWarning']);
        this._super("initialize", [options]);
        this.before('mergeduplicates', this._checkMergeModels, this);
    },

    /**
     * Event handler to make sure that before the merge drawer shows, make sure that all the models contain the first
     * records opportunity_id
     *
     * @param {Array} mergeModels
     * @returns {boolean}
     * @private
     */
    _checkMergeModels: function(mergeModels) {
        var primaryRecordOppId = _.first(mergeModels).get('opportunity_id');
        var invalid_models = _.find(mergeModels, function(model) {
            return !_.isEqual(model.get('opportunity_id'), primaryRecordOppId);
        });

        if (!_.isUndefined(invalid_models)) {
            app.alert.show("merge_duplicates_different_opps_warning", {
                level: "warning",
                messages: app.lang.get('WARNING_MERGE_RLIS_WITH_DIFFERENT_OPPORTUNITIES', this.module)
            });
            return false;
        }

        return true;
    },

    /**
     * @inheritdoc
     *
     * Augment to remove the fields that should not be displayed.
     */
    _createCatalog: function(fields) {
        var forecastConfig = app.metadata.getModule('Forecasts', 'config'),
            isSetup = (forecastConfig && forecastConfig.is_setup);

        if (isSetup) {
            fields = _.filter(fields, function(fieldMeta) {
                if (fieldMeta.name.indexOf('_case') !== -1) {
                    var field = 'show_worksheet_' + fieldMeta.name.replace('_case', '');
                    return (forecastConfig[field] == 1);
                }
                return true;
            });
        } else {
            // Forecast is not setup
            fields = _.reject(fields, function(fieldMeta) {
                return (fieldMeta.name === 'commit_stage');
            });
        }

        var catalog = this._super('_createCatalog', [fields]);
        return catalog;
    }
})

