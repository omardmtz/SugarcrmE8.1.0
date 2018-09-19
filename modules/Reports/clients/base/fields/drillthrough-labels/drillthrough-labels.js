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
 * @class View.Fields.Base.Reports.DrillthroughLabelsField
 * @alias SUGAR.App.view.fields.BaseReportsDrillthroughLabelsField
 * @extends View.Fields.Base.BaseField
 */
({

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.context.on('refresh:drill:labels', this.render, this);
    },

    /**
     * @override We want to grab the data from the context, not the model
     */
    format: function(value) {
        var params = this.context.get('dashConfig');
        var reportDef = this.context.get('reportData');
        var chartModule = this.context.get('chartModule');
        var filterDef = this.context.get('filterDef');
        var filterFields = _.flatten(_.map(filterDef, function(filter) {
            return _.keys(filter);
        }));
        var groupDefs = _.filter(reportDef.group_defs, function(groupDef) {
            var groupField = groupDef.table_key + ':' + groupDef.name;
            return _.contains(filterFields, groupField);
        });
        if (groupDefs.length > 0) {
            var group = SUGAR.charts.getFieldDef(groupDefs[0], reportDef);
            var module = group.custom_module || group.module || chartModule;
            this.groupName = app.lang.get(group.vname, module) + ': ';
            this.groupValue = params.groupLabel;
        }
        if (groupDefs.length > 1) {
            var series = SUGAR.charts.getFieldDef(groupDefs[1], reportDef);
            var module = series.custom_module || series.module || chartModule;
            this.seriesName = app.lang.get(series.vname, module) + ': ';
            this.seriesValue = params.seriesLabel;
        }

        // returns nothing
        return value;
    }
})
