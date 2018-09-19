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
    app.events.on('router:init', function(router) {
        /*
         * Allow modules to extend routing rules.
         *
         * Manually create a route for the router.
         * The route argument may be a routing string or regular expression.
         */
        var homeOptions = {
            dashboard: 'dashboard',
            activities: 'activities'
        };

        var getLastHomeKey = function() {
            return app.user.lastState.buildKey('last-home', 'app-header');
        };

        var routes = [
            {
                name: 'activities',
                route: 'activities',
                callback: function() {
                    // when visiting activity stream, save last state of activities
                    // so future Home routes go back to activities
                    var lastHomeKey = getLastHomeKey();
                    app.user.lastState.set(lastHomeKey, homeOptions.activities);

                    app.controller.loadView({
                        layout: 'activities',
                        module: 'Activities'
                    });
                }
            },
            {
                name: 'home',
                route: 'Home',
                callback: function() {
                    var lastHomeKey = getLastHomeKey(),
                        lastHome = app.user.lastState.get(lastHomeKey);
                    if (lastHome === homeOptions.dashboard) {
                        app.controller.loadView({
                            module: 'Home',
                            layout: 'record'
                        });
                    } else if (lastHome === homeOptions.activities) {
                        app.router.redirect('#activities');
                    }
                }
            },
            {
                name: 'homeCreate',
                route: 'Home/create',
                callback: function() {
                    app.controller.loadView({
                        module: 'Home',
                        layout: 'record',
                        create: true
                    });
                }
            },
            {
                name: 'homeRecord',
                route: 'Home/:id',
                callback: function(id) {
                    // when visiting a dashboard, save last state of dashboard
                    // so future Home routes go back to dashboard
                    var lastHomeKey = getLastHomeKey();
                    app.user.lastState.set(lastHomeKey, homeOptions.dashboard);

                    app.controller.loadView({
                        module: 'Home',
                        layout: 'record',
                        action: 'detail',
                        modelId: id
                    });
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
