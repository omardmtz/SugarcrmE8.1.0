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
 * @class View.Layouts.Base.Reports.DrillthroughDrawerLayout
 * @alias SUGAR.App.view.layouts.BaseReportsDrillthroughDrawerLayout
 * @extends View.Layout
 */
({
    plugins: ['ShortcutSession'],

    shortcuts: [
        'Sidebar:Toggle',
        'List:Headerpane:Create',
        'List:Select:Down',
        'List:Select:Up',
        'List:Scroll:Left',
        'List:Scroll:Right',
        'List:Select:Open',
        'List:Inline:Edit',
        'List:Delete',
        'List:Inline:Cancel',
        'List:Inline:Save',
        'List:Favorite',
        'List:Follow',
        'List:Preview',
        'List:Select',
        'SelectAll:Checkbox',
        'SelectAll:Dropdown',
        'Filter:Search',
        'Filter:Create',
        'Filter:Edit',
        'Filter:Show'
    ],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * Cache for enum and enum like values
         */
        this.enums = {};
    },

    /**
     * Override the default loadData method to allow for manually constructing
     * context for each component in layout. We are loading data from the
     * ReportAPI in public method updateList. We need to first get any enum data
     * so that we can translate to english
     *
     * @override
     */
    loadData: function() {
        var enumsToFetch = this.context.get('enumsToFetch');
        // Make requests for any enums here so they can happen while the drawer is still rendering
        if (!_.isEmpty(enumsToFetch) && _.isEmpty(this.enums)) {
            this._loadEnumOptions(enumsToFetch);
        } else {
            this.updateList();
        }
    },
    /**
     * Make a request for each enum like field so we can reverse lookup values later
     *
     * @param enumsToFetch
     * @private
     */
    _loadEnumOptions: function(enumsToFetch) {
        var reportDef = this.context.get('reportData');
        var count = enumsToFetch.length;

        var enumSuccess = function(key, data) {
            count--;

            // cache the values inverted to help with reverse lookup
            this.enums[key] = _.invert(data);

            // I love that I have to simulate Promise.all but anyways, once
            // we have all our enum data, then make the record list request
            if (count === 0) {
                this.updateList();
            }
        };
        _.each(enumsToFetch, function(field) {
            var module = reportDef.full_table_list[field.table_key].module;
            var key = field.table_key + ':' + field.name;
            app.api.enumOptions(module, field.name, {
                success: _.bind(enumSuccess, this, key)
            });
        }, this);
    },

    /**
     * Fetch report related records based on drawer context as defined in
     * saved-reports-chart dashlet or Report detail view with context containing
     * a filter definition based on a chart click event. This method will also
     * render the list component in layout after data is fetched.
     */
    updateList: function() {
        var chartModule = this.context.get('chartModule');
        var reportId = this.context.get('reportId');
        var reportDef = this.context.get('reportData');
        var params = this.context.get('dashConfig');

        // At this point, we should have finished all translations and requests for translations so
        // we can finally build the filter in english
        var filterDef = SUGAR.charts.buildFilter(reportDef, params, this.enums);
        this.context.set('filterDef', filterDef);
        var useSavedFilters = this.context.get('useSavedFilters') || false;

        var endpoint = function(method, model, options, callbacks) {
            var params = _.extend(options.params || {},
                {view: 'list', group_filters: filterDef, use_saved_filters: useSavedFilters});
            var url = app.api.buildURL('Reports', 'records', {id: reportId}, params);
            return app.api.call('read', url, null, callbacks);
        };
        var callbacks = {
            success: _.bind(function(data) {
                if (this.disposed) {
                    return;
                }
                this.context.trigger('refresh:count');
                this.context.trigger('refresh:drill:labels');
            }, this),
            error: function(o) {
                app.alert.show('listfromreport_loading', {
                    level: 'error',
                    messages: app.lang.get('ERROR_RETRIEVING_DRILLTHRU_DATA', 'Reports')
                });
            },
            complete: function(data) {
                app.alert.dismiss('listfromreport_loading');
            }
        };
        var collection = this.context.get('collection');
        collection.module = chartModule;
        collection.model = app.data.getBeanClass(chartModule);
        collection.setOption('endpoint', endpoint);
        collection.setOption('fields', this.context.get('fields'));
        collection.fetch(callbacks);
        var massCollection = this.context.get('mass_collection');
        if (massCollection) {
            massCollection.setOption('endpoint', endpoint);
        }
    }
})
