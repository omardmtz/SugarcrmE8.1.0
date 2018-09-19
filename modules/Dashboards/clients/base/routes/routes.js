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

(function(app) {
    /*
     * Add the Dashboard module routes to Sugar's router
     */
    app.events.on('router:init', function(router) {
        var routes = [
            {
                name: 'dashboardCreate',
                route: 'Dashboards/create',
                callback: function() {
                    app.error.handleHttpError({status: 404});
                }
            },
            {
                name: 'manageDashboards',
                route: 'Dashboards',
                callback: function(urlParams) {
                    if (!this._moduleExists('Dashboards')) {
                        return;
                    }
                    // This may be filled in with values from urlParams
                    // Expected (optional) keys are moduleName and viewName
                    var params = {};

                    if (urlParams) {
                        // FIXME TY-1469: Use the URL splitter in the router
                        // and remove this block of code.
                        var paramsArray = urlParams.split('&');
                        _.each(paramsArray, function(paramPair) {
                            var keyValueArray = paramPair.split('=');
                            if (keyValueArray.length > 1) {
                                params[keyValueArray[0]] = keyValueArray[1];
                            }
                        });
                    }

                    var moduleName = params.moduleName;
                    var viewName = params.viewName;

                    // Initialize the options for `app.controller.loadView`
                    var viewOptions = {
                        module: 'Dashboards',
                        layout: 'records'
                    };

                    // If `previousModule` is defined, we need to pre-apply a
                    // filter to the dashboards list view.
                    if (!_.isUndefined(moduleName)) {
                        var initialFilter;
                        var translatedModule = app.lang.getModuleName(moduleName, {plural: true});
                        var filterLabel;
                        var filterOptions;
                        var filterDef = {
                            dashboard_module: [moduleName]
                        };
                        // FIXME TY-1458: If we're here, then `previousLayout`
                        // should also be defined. The `if` statement and its
                        // contents would no longer be necessary. The `else`
                        // contents would be the only portion remaining.
                        if (_.isUndefined(viewName)) {
                            initialFilter = 'module';
                            filterLabel = app.lang.get('LBL_FILTER_BY_MODULE', 'Dashboards', {
                                module: translatedModule
                            });
                        } else {
                            initialFilter = 'module_and_layout';
                            filterDef.view_name = viewName;
                            filterLabel = app.lang.get('LBL_FILTER_BY_MODULE_AND_VIEW', 'Dashboards', {
                                module: translatedModule,
                                view: app.lang.getAppListStrings('dashboard_view_name_list')[viewName] || viewName
                            });
                        }

                        filterOptions = new app.utils.FilterOptions();
                        filterOptions.setInitialFilter(initialFilter);
                        filterOptions.setInitialFilterLabel(filterLabel);
                        filterOptions.setFilterPopulate(filterDef);

                        viewOptions.filterOptions = filterOptions.format();
                    }
                    app.controller.loadView(viewOptions);
                }
            }
        ];

        /*
         * Triggering the event on init will go over all those listeners
         * and add the routes to the router.
         */
        app.router.addRoutes(routes);
    });
})(SUGAR.App);
