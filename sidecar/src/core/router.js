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

const Acl = require('core/acl');
const Alert = require('view/alert');
const Utils = require('utils/utils');
const Language = require('core/language');
const ErrorHandler = require('core/error');
const Events = require('core/events');
const Routing = require('core/routing');

/**
 * App router. It extends the standard Backbone.js router.
 *
 * The router manages the watching of the address hash and routes to the correct
 * handler. You need to add your routes with their callback using `addRoutes`
 * method.
 * To add your routes, you have to listen to the `router:init` event.
 *
 * Example:
 * ```
 * const Events = require('core/events');
 * Events.on('router:init', function(router) {
 *     var routes = [
 *         {
 *             route: 'MyModule/my_custom_route',
 *             name: 'MyModule',
 *             callback: MyModule
 *         }
 *     ];
 *     SUGAR.App.router.addRoutes(routes);
 * });
 * ```
 *
 * @module Core/Router
 */
const Router = Backbone.Router.extend({
    /**
     * Sets custom routes and binds them if available.
     *
     * @param {Object} [opts] options to initialize the router.
     */
    initialize: function(opts) {
        opts = opts || {};

        /**
         * The previous fragment.
         *
         * @type {string}
         * @private
         */
        this._previousFragment = '';

        /**
         * The current fragment.
         *
         * @type {string}
         * @private
         */
        this._currentFragment = '';
    },

    /**
     * Calls {@link Core.Routing#before} before invoking a route handler
     * and {@link Core.Routing#after} after the handler is invoked.
     *
     * @param {Function} handler Route callback handler.
     * @private
     */
    _routeHandler: function(handler) {
        var args = Array.prototype.slice.call(arguments, 1),
            route = handler.route;

        if (Routing.beforeRoute(route, args)) {
            this._previousFragment = this._currentFragment;
            this._currentFragment = this.getFragment();
            handler.apply(this, args);
            Routing.after(route, args);
        }
    },

    /**
     * Checks if a module exists and displays 404 error screen if it does not.
     *
     * @param {string} module The module to check.
     * @return {boolean} `true` if module exists, `false` otherwise.
     * @private
     */
    _moduleExists: function(module) {
        if (module && _.isUndefined(SUGAR.App.metadata.getModule(module))) {
            ErrorHandler.handleHttpError({status: 404});
            return false;
        }
        return true;
    },

    /**
     * Adds the `notFound` route.
     *
     * @private
     */
    _addDefaultRoutes: function() {
        var defaultRoutes = [
            {
                name: 'notFound',
                route: /^.*$/,
                callback: function () {
                    // no matching routes (e.g.: '#//' or '#unkown/path/route)
                    ErrorHandler.handleHttpError({status: 404});
                }
            }
        ];
        this.addRoutes(defaultRoutes);
    },

    /**
     * Registers a handler for a named route.
     *
     * This method wraps the handler into {@link Core.Router#_routeHandler}
     * method.
     *
     * @param {string} route Route expression.
     * @param {string} name Route name.
     * @param {Function} [callback] Route handler. If not supplied, will
     *   use the method name that matches the `name` param.
     */
    route: function (route, name, callback) {
        if (!name) {
            throw new Error('You need to provide a route name.');
        } else if (!_.isEmpty(this._routes[name])) {
            SUGAR.App.logger.error('Route "' + name + '" is being overridden. This is highly NOT advisable.');
        }

        this._routes[name] = callback;

        if (!callback) {
            callback = this[name];
        }

        callback.route = name;
        callback = _.wrap(callback, this._routeHandler);
        Backbone.Router.prototype.route.call(this, route, name, callback);
    },

    /**
     * Gets the current Backbone fragment.
     *
     * @return {string} The current Backbone history fragment.
     */
    getFragment: function() {
        return Backbone.history.getFragment();
    },

    /**
     * Updates the URL with the given fragment.
     *
     * @param {string} fragment The fragment to navigate to.
     * @param {Object} [options] The options hash.
     * @param {boolean} [options.trigger] `true` to fire the route callback.
     * @param {boolean} [options.replace] `true` to modify the current URL
     *   without adding an entry to the `window.history` object.
     * @return {Core.Router} This router.
     */
    navigate: function(fragment, options) {
        Backbone.Router.prototype.navigate.apply(this, arguments);
        if (!(options && options.trigger)) {
            this._previousFragment = this._currentFragment;
            this._currentFragment = this.getFragment();
        }
        return this;
    },

    /**
     * Gets the previous Backbone fragment.
     *
     * @return {string} The previous Backbone fragment.
     */
    getPreviousFragment: function() {
        return this._previousFragment;
    },

    /**
     * Navigates to the previous route in history.
     */
    goBack: function() {
        window.history.back();
    },

    /**
     * Navigates the window history.
     *
     * @param {number} steps Number of steps to navigate (can be negative).
     */
    go: function(steps) {
        window.history.go(steps);
    },

    /**
     * Initializes the router.
     */
    init: function() {
        /**
         * Routes hashmap by name. See {@link#get} for more info.
         *
         * @type {Object}
         * @private
         */
        this._routes = {};
        this._addDefaultRoutes();

        Events.trigger('router:init');
    },

    /**
     * Starts Backbone history which in turn starts routing the hashtag.
     *
     * See Backbone.history documentation for details.
     */
    start: function() {
        if (!Backbone.History.started) {
            Backbone.history.start();
        }
    },

    /**
     * Stops `Backbone.history`.
     */
    stop: function() {
        Backbone.history.stop();
    },

    /**
     * Resets the router.
     *
     * Stops `Backbone.history` and cleans up routes. Then initializes and
     * starts the router again.
     */
    reset: function() {
        SUGAR.App.router.stop();
        Backbone.history.handlers = [];
        SUGAR.App.router.init();
        SUGAR.App.router.start();
    },

    /**
     * Add routes into the router.
     *
     * Currently, Backbone stops after the first matching route.
     * Therefore, the order of how custom routes are added is important.
     * In general, the developer should add the more specific route first,
     * so that the intended route gets called.
     *
     * For example, the route `MyRoute/create` will call `myRouteCreate` in
     * the following code snippet:
     * ```
     * var routes = [
     *     {
     *         name: 'myRouteCreate',
     *         route: 'MyRoute/create',
     *         callback: myRouteCreate
     *     },
     *     {
     *         name: 'myRoute',
     *         route: "MyRoute(/:my_custom_route)",
     *         callback: myRoute
     *     }
     * ];
     * ```
     * If the order of `myRouteCreate` and `myRoute` is reversed, triggering
     * `MyRoute/create` will call `myRoute` with `:my_custom_route` set to
     * `create`, which may not be intended.
     *
     * @param {Array} routes The ordered list of routes.
     */
    addRoutes: function(routes) {
        if (!routes) {
            return;
        }

        var newRoutes = routes.reverse();
        _.each(newRoutes, function(route) {
            this.route(route.route, route.name, route.callback);
        }, this);
    },

    /**
     * Retrieves the callback associated with the given route name.
     *
     * @param {string} name The route to get the callback function.
     * @return {Function} The callback associated with this route name.
     */
    get: function (name) {
        return this._routes[name];
    },

    /**
     * Re-triggers the current route.
     * Used to refresh the current layout/page without doing a hard refresh.
     */
    refresh: function(){
        Backbone.history.loadUrl(Backbone.history.fragment);
    },

    /**
     * Builds a route.
     *
     * This is a convenience function.
     * If you need to override this, define a `customBuildRoute` function on
     * {@link Utils/Utils} and return an empty string if you want to
     * fall back to this definition of `buildRoute`.
     *
     * @param {Core/Context|string} moduleOrContext The name of the module
     *   or a context object to extract the module from.
     * @param {string} [id] The model's ID.
     * @param {string} [action] Action name.
     * @return {string} route The built route.
     */
    buildRoute: function(moduleOrContext, id, action) {
        var route;

        if (_.isFunction(Utils.customBuildRoute)) {
            route = Utils.customBuildRoute.apply(this, arguments);
            if (!_.isEmpty(route)) {
                return route;
            }
        }

        if (moduleOrContext) {
            // If module is a context object, then extract module from it
            route = (_.isString(moduleOrContext)) ? moduleOrContext : moduleOrContext.get("module");

            if (id) {
                route += "/" + id;
            }

            if (action) {
                route += "/" + action;
            }
        } else {
            route = action;
        }

        return route;
    },

    redirect: function(route, options) {
        this.navigate(route, _.extend({trigger: true, replace: true}, options));
    },

    // ----------------------------------------------------
    // Route handlers
    // ----------------------------------------------------

    /**
     * Handles the `index` route.
     *
     * Loads `home` layout for the `Home` module or `list` route with default
     * module defined in `SUGAR.App.config`.
     */
    index: function() {
        SUGAR.App.logger.debug("Route changed to index");
        if (SUGAR.App.config.defaultModule) {
            this.navigate(SUGAR.App.config.defaultModule, {trigger:true});
        }
        else if (Acl.hasAccess('read', 'Home')) {
            this.navigate('Home', {trigger:true});
        }
    },

    /**
     * Handles the `list` route.
     *
     * @param {string} module Module name.
     */
    list: function(module) {
        if (!this._moduleExists(module)) {
            return;
        }
        SUGAR.App.logger.debug("Route changed to list of " + module);
        SUGAR.App.controller.loadView({
            module: module,
            layout: "records"
        });
    },

    /**
     * Handles arbitrary layout for a module that doesn't have a record
     * associated with the layout.
     *
     * @param {string} module Module name.
     * @param {string} layout Layout name.
     */
    layout: function(module, layout) {
        if (!this._moduleExists(module)) {
            return;
        }
        SUGAR.App.logger.debug("Route changed to layout: " + layout + " for " + module);
        SUGAR.App.controller.loadView({
            module: module,
            layout: layout
        });
    },

    /**
     * Handles the `create` route.
     *
     * @param {string} module Module name.
     */
    create: function(module) {
        if (!this._moduleExists(module)) {
            return;
        }
        SUGAR.App.logger.debug("Route changed: create " + module);
        SUGAR.App.controller.loadView({
            module: module,
            create: true,
            layout: "edit"
        });
    },

    /**
     * Routes to the login page.
     *
     * You have to implement a `login` layout to use it.
     *
     * @fires app:login
     * @fires app:login:success after a successful external login.
     */
    login: function() {
        SUGAR.App.logger.debug("Logging in");
        SUGAR.App.controller.loadView({
            module: "Login",
            layout: "login",
            create: true
        });

        // Need to hide the megamenu here otherwise we get a login screen
        // with a megamenu. This is done AFTER the login view loading since
        // loadView fires a _render call on login.js, which rerenders the
        // header in refreshAdditionalComponents().
        Events.trigger('app:login');

        if(SUGAR.App.config.externalLogin) {
            // This will attempt reauth
            SUGAR.App.api.ping(null, {
                success: function() {
                    // If we have success then show the megamenu again
                    Events.trigger('app:login:success');
                    SUGAR.App.router.refresh();
                },
                error: function() {
                    Alert.show('needs_login_error', {
                        level: 'error',
                        messages: Language.getModString('ERR_INVALID_PASSWORD', 'Users'),
                        title: Language.get('LBL_INVALID_CREDS_TITLE')
                    });
                }
            });
        }
    },

    /**
     * Logs out the user and routes to the login page.
     *
     * @param {boolean} clear Refreshes the page once logout is complete to
     *   clear any sensitive data from browser tab memory.
     * @fires 'app:logout:success'
     */
    logout: function(clear) {
        if (!SUGAR.App.api.isAuthenticated()) {
            // We don't want to store the #logout fragment in the URL
            // history. This will re-direct to the root defined in the
            // Backbone router, and replace the URL.
            this.redirect('/');
            return;
        }

        clear = (clear === "1");
        SUGAR.App.logger.debug("Logging out: " + clear);
        SUGAR.App.logout({
            complete() {
                SUGAR.App.router.navigate("#");
                if (!SUGAR.App.config.externalLogin) {
                    if (clear) {
                        //We have to reload to clear any sensitive data from browser tab memory.
                        window.location.reload();
                    } else {
                        SUGAR.App.router.login();
                    }
                } else {
                    SUGAR.App.controller.loadView({
                        module: 'Login',
                        layout: 'logout',
                        skipFetch: true,
                        create: true
                    });
                }
            },
            success(data) {
                Events.trigger('app:logout:success', data);
            },
        }, clear);
    },

    /**
     * Handles the `record` route.
     *
     * @param {string} module Module name.
     * @param {string} id Record ID. If `id` is `create`, it will load the create view.
     * @param {string} [action] Action name (`edit`, etc.). Defaults to `detail` if not specified.
     * @param {string} [layout] The layout to use for this route. Defaults to `record` if not specified.
     */
    record: function(module, id, action, layout) {
        if (!this._moduleExists(module)) {
            return;
        }

        var oldCollection = SUGAR.App.controller.context.get('collection'),
            oldListCollection = SUGAR.App.controller.context.get('listCollection'),
            opts = {
                module: module,
                layout: layout || "record",
                action: (action || "detail")
            };

        if (id !== "create") {
            _.extend(opts, {modelId: id});
        } else {
            _.extend(opts, {create: true});
            opts.layout = "create";
        }

        //If we come from a list view, we get the current collection
        if (oldCollection && oldCollection.module === module && oldCollection.get(id)) {
            opts.listCollection = oldCollection;
        }

        //If we come from a detail view, we need to get the cached collection
        if (oldListCollection && oldListCollection.module === module && oldListCollection.get(id)) {
            opts.listCollection = oldListCollection;
        }

        SUGAR.App.controller.loadView(opts);
    },
});

module.exports = Router;
