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
 * @class View.Views.Base.CasessummaryView
 * @alias SUGAR.App.view.views.BaseCasessummaryView
 * @extends View.View
 */
({
    events: {
        'shown.bs.tab a[data-toggle="tab"]': 'resize',
    },

    plugins: ['Dashlet', 'Chart'],
    className: 'cases-summary-wrapper',

    tabData: null,
    tabClass: '',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.tooltipTemplate = app.template.getField('chart', 'singletooltiptemplate', this.module);
        this.locale = SUGAR.charts.getSystemLocale();

        this.chart = sucrose.charts.pieChart()
                .margin({top: 0, right: 0, bottom: 0, left: 0})
                .donut(true)
                .donutLabelsOutside(true)
                .donutRatio(0.447)
                .rotateDegrees(0)
                .arcDegrees(360)
                .maxRadius(110)
                .hole(this.total)
                .showTitle(false)
                .tooltips(true)
                .showLegend(false)
                .direction(app.lang.direction)
                .colorData('data')
                .tooltipContent(_.bind(function(eo, properties) {
                    var point = {};
                    point.key = this.chart.getKey()(eo);
                    point.label = app.lang.get('LBL_CHART_COUNT');
                    point.value = this.chart.getValue()(eo);
                    point.percent = sucrose.utility.numberFormatPercent(point.value, properties.total, this.locality);
                    return this.tooltipTemplate(point).replace(/(\r\n|\n|\r)/gm, '');
                }, this))
                .strings({
                    noData: app.lang.get('LBL_CHART_NO_DATA'),
                    noLabel: app.lang.get('LBL_CHART_NO_LABEL')
                })
                .locality(this.locale);

        this.locality = this.chart.locality();
    },

    /**
     * Generic method to render chart with check for visibility and data.
     * Called by _renderHtml and loadData.
     */
    renderChart: function() {
        if (!this.isChartReady()) {
            return;
        }

        // Set value of label inside donut chart
        this.chart.hole(this.total);
        d3sugar.select(this.el).select('svg#' + this.cid)
            .datum(this.chartCollection)
            .transition().duration(500)
            .call(this.chart);

        this.chart_loaded = _.isFunction(this.chart.update);
        this.displayNoData(!this.chart_loaded);
    },

    /**
     * Build content with favorite fields for content tabs
     */
    addFavs: function() {
        var self = this;
        //loop over metricsCollection
        _.each(this.tabData, function(tabGroup) {
            if (tabGroup.models && tabGroup.models.length > 0) {
                _.each(tabGroup.models, function(model) {
                    var field = app.view.createField({
                            def: {type: 'favorite'},
                            model: model,
                            meta: {view: 'detail'},
                            viewName: 'detail',
                            view: self
                        });
                    field.setElement(self.$('[data-model-id="' + model.id + '"]'));
                    field.render();
                });
            }
        });
    },

    /* Process data loaded from REST endpoint so that d3 chart can consume
     * and set general chart properties
     */
    evaluateResult: function(data) {
        this.total = data.models.length;

        var countClosedCases = data.where({status: 'Closed'})
                .concat(data.where({status: 'Rejected'}))
                .concat(data.where({status: 'Duplicate'})).length,
            countOpenCases = this.total - countClosedCases;

        this.chartCollection = {
            data: [],
            properties: {
                title: app.lang.get('LBL_CASE_SUMMARY_CHART'),
                value: 3,
                label: this.total
            }
        };
        this.chartCollection.data.push({
            key: app.lang.get('LBL_DASHLET_CASESSUMMARY_CLOSE_CASES'),
            classes: 'sc-fill-green',
            value: countClosedCases
        });
        this.chartCollection.data.push({
            key: app.lang.get('LBL_DASHLET_CASESSUMMARY_OPEN_CASES'),
            classes: 'sc-fill-red',
            value: countOpenCases
        });

        if (!_.isEmpty(data.models)) {
            this.processCases(data);
        }
    },

    /**
     * Build tab related data and set tab class name based on number of tabs
     * @param {data} object The chart related data.
     */
    processCases: function(data) {
        this.tabData = [];

        var status2css = {
                'Rejected': 'label-success',
                'Closed': 'label-success',
                'Duplicate': 'label-success'
            },
            stati = _.uniq(data.pluck('status')),
            statusOptions = app.metadata.getModule('Cases', 'fields').status.options || 'case_status_dom';

        _.each(stati, function(status, index) {
            if (!status2css[status]) {
                this.tabData.push({
                    index: index,
                    status: status,
                    statusLabel: app.lang.getAppListStrings(statusOptions)[status],
                    models: data.where({'status': status}),
                    cssClass: status2css[status] ? status2css[status] : 'label-important'
                });
            }
        }, this);

        this.tabClass = ['one', 'two', 'three', 'four', 'five'][this.tabData.length] || 'four';
    },

    /**
     * @inheritdoc
     */
    loadData: function(options) {
        var self = this,
            oppID,
            accountBean,
            relatedCollection;
        if (this.meta.config) {
            return;
        }
        oppID = this.model.get('account_id');
        if (oppID) {
            accountBean = app.data.createBean('Accounts', {id: oppID});
        }
        relatedCollection = app.data.createRelatedCollection(accountBean || this.model, 'cases');
        relatedCollection.fetch({
            relate: true,
            success: function(data) {
                self.evaluateResult(data);
                if (!self.disposed) {
                    // we have to rerender the entire dashlet, not just the chart,
                    // because the HBS file is dependant on processCases completion
                    self.render();
                    self.addFavs();
                }
            },
            error: _.bind(function() {
                this.displayNoData(true);
            }, this),
            complete: options ? options.complete : null,
            limit: -1,
            fields: this.getFieldNames()
        });
    },

    /**
     * Get the list of field names to render the dashlet correctly
     * @return {string[]} The list of fields we need to fetch
     * @override
     */
    getFieldNames: function() {
        // FIXME TY-920: we shouldn't have to override this per-dashlet
        return this.dashletConfig && this.dashletConfig.dashlets[0].fields || [];
    }
})
