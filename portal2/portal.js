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

    // Add custom events here for now
    app.events.on('router:init', function() {
        var routes = [
            {
                name: 'portal-index',
                route: '',
                callback: function() {
                    app.controller.loadView({
                        layout: 'home'
                    });
                }
            },
            {
                name: 'logout',
                route: 'logout/?clear=:clear'
            },
            {
                name: 'logout',
                route: 'logout'
            },
            {
                name: 'signup',
                route: 'signup',
                callback: function() {
                    app.controller.loadView({
                        module: 'Signup',
                        layout: 'signup',
                        create: true
                    });
                }
            },
            {
                name: 'search',
                route: 'search/:query',
                callback: function(query) {
                    // For Safari and FF, the query always comes in as URI encoded.
                    // Decode here so we don't accidentally double encode it later. (bug55572)
                    try {
                        var decodedQuery = decodeURIComponent(query);
                        app.controller.loadView({
                            mixed: true,
                            module: 'Search',
                            layout: 'search',
                            query: decodedQuery,
                            skipFetch: true
                        });
                    } catch (err) {
                        // If not a validly encoded URI, decodeURIComponent will throw an exception
                        // If URI is not valid, don't navigate.
                        app.logger.error('Search term not a valid URI component.  Will not route search/' + query);
                    }
                }
            },
            {
                name: 'create',
                route: ':module/create',
                callback: function(module) {
                    app.controller.loadView({
                        module: module,
                        layout: 'records'
                    });

                    app.drawer.open({
                        layout: 'create',
                        context: {
                            create: true
                        }
                    }, _.bind(function(context, model) {
                        var module = context.get('module') || model.module,
                            route = app.router.buildRoute(module);
                        app.router.navigate(route, {trigger: true});
                    }, this));
                }
            },
            {
                name: 'profile',
                route: 'profile',
                callback: function() {
                    app.controller.loadView({
                        layout: 'record',
                        module: 'Contacts',
                        modelId: app.user.get('id')
                    });
                }
            },
            {
                name: 'home',
                route: 'Home',
                callback: function() {
                    app.controller.loadView({
                        layout: 'home'
                    });
                }
            },
            {
                name: 'list',
                route: ':module'
            },
            {
                name: 'record',
                route: ':module/:id'
            }
        ];
        app.router.addRoutes(routes);
    });

    // bug57318: Multiple alert warnings when multiple views get render denied on same page.
    var oHandleRenderError = app.error.handleRenderError;
    app.error.handleRenderError = function(component, method, additionalInfo) {
        function handlePortalRenderDenied(c) {
            var title = app.lang.get('ERR_NO_VIEW_ACCESS_TITLE');
            var module = app.lang.getModuleName(c.module, {plural: true});
            var message = app.utils.formatString(app.lang.get('ERR_NO_VIEW_ACCESS_MSG'), [module]);
            // TODO: We can later create some special case handlers if we DO wish to alert warn,
            // but since we have recursive views that's usually going to be overbearing.
            app.logger.warn(title + ':\n' + message);
        }
        // Only hijack view_render_denied error case, otherwise, delegate all else to sidecar handler
        if (method === 'view_render_denied') {
            handlePortalRenderDenied(component);
        } else {
            oHandleRenderError(component, method, additionalInfo);
        }
    };

    var oRoutingBefore = app.routing.beforeRoute;
    app.routing.beforeRoute = function(route, args) {
        var dm;
        var nonModuleRoutes = [
            'search',
            'error',
            'profile',
            'profileedit',
            'logout'
        ];

        app.logger.debug('Loading route. ' + (route ? route : 'No route or undefined!'));

        if (!oRoutingBefore.call(this, route, args)) {
            return false;
        }

        function alertUser(msg) {
            // TODO: Error messages should later be put in lang agnostic app strings. e.g. also in layout.js alert.
            msg = msg || 'LBL_PORTAL_MIN_MODULES';

            app.alert.show('no-sidecar-access', {
                level: 'error',
                title: app.lang.get('LBL_PORTAL_ERROR'),
                messages: [app.lang.get(msg)]
            });
        }

        // Handle index case - get default module if provided. Otherwise, fallback to Home if possible or alert.
        if (route === 'index') {
            dm = typeof(app.config) !== undefined && app.config.defaultModule ? app.config.defaultModule : null;
            if (dm && app.metadata.getModule(dm) && app.acl.hasAccess('read', dm)) {
                app.router.list(dm);
            } else if (app.acl.hasAccess('read', 'Home')) {
                app.router.index();
            } else {
                alertUser();
                return false;
            }
            // If route is NOT index, and NOT in non module routes,
            // check if module (args[0]) is loaded and user has access to it.
        } else if (
            !_.include(nonModuleRoutes, route) &&
            args[0] &&
            !app.metadata.getModule(args[0]) ||
            !app.acl.hasAccess('read', args[0])
        ) {
            app.logger.error('Module not loaded or user does not have access. ', route);
            alertUser('LBL_PORTAL_ROUTE_ERROR');
            return false;
        }
        return true;
    };

    app.Controller = app.Controller.extend({
        loadView: function(params) {
            var self = this,
                callbackAppNotAvailable, options;

            // If login page request we always need to present the login page, but we
            // also must deal with status 'offline' which means portal not enabled.
            if (params.layout === 'login') {
                app.Controller.__super__.loadView.call(this, params);
            }

            if (app.config && app.config.appStatus == 'offline') {

                // We only want to redirect back to login if not already on login!
                if (params.layout !== 'login') {
                    options = {
                        module: 'Login',
                        layout: 'login',
                        create: true
                    };
                    app.Controller.__super__.loadView.call(self, options);
                }

                callbackAppNotAvailable = function(data) {
                    app.alert.show('appOffline', {
                        level: 'error',
                        title: app.lang.get('LBL_PORTAL_ERROR'),
                        messages: app.lang.get('LBL_PORTAL_OFFLINE'),
                        autoclose: false
                    });
                };
                if (app.api.isAuthenticated()) {
                    app.logout({success: callbackAppNotAvailable, error: callbackAppNotAvailable}, {clear: true});
                } else {
                    callbackAppNotAvailable();
                }
                return;
            }

            // If it wasn't login and wasn't offline we just load'er up
            if (params.layout !== 'login') {
                app.Controller.__super__.loadView.call(this, params);
            }
        }
    });

    /**
     * Extends the `save` action to add `portal` specific params to the payload.
     *
     * @param {Object} [attributes] Model attributes.
     * @param {Object} [options] Standard save options as described by Backbone
     *   docs and optional `fieldsToValidate` parameter.
     */
    var __superBeanSave__ = app.Bean.prototype.save;
    app.Bean.prototype.save = function(attributes, options) {
        // Here is the list of params that must be set for portal use case.
        var defaultParams = {
            portal_flag: 1,
            portal_viewable: 1
        };
        var moduleFields = app.metadata.getModule(this.module).fields || {};
        for (var field in defaultParams) {
            if (moduleFields[field]) {
                this.set(field, defaultParams[field], {silent: true});
            }
        }
        // Call the prototype
        __superBeanSave__.call(this, attributes, options);
    };

    /**
     * When application finishes syncing.
     */
    app.events.on('app:sync:complete', function() {
        // Set moment.js locale with moment.js 2.8+
        app.date.locale(app.user.getPreference('language'));
    });
})(SUGAR.App);
