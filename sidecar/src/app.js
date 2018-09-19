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

const BeforeEvent = require('core/before-event');
const Logger = require('utils/logger');
const Cache = require('core/cache');
const Utils = require('utils/utils');
const Controller = require('core/controller');
const Language = require('core/language');
const Routing = require('core/routing');
const MetadataManager = require('core/metadata-manager');

/**
 * SugarCRM namespace.
 *
 * @ignore
 */
var SUGAR = SUGAR || {};

SUGAR.App = (function() {
    var _app,
        _modules = {};

    /**
     * Flag indicating an `app.sync` is in progress.
     *
     * @type {boolean}
     * @private
     */
    var _isSyncing = false;

    /**
     * A list of callback functions to call whenever `sync` is completed.
     *
     * @type {Array}
     * @private
     */
    var _syncCallbacks = [];

    var _make$ = function(selector) {
        return selector instanceof $ ? selector : $(selector);
    };

    /**
     * Constructor class for the main framework app.
     *
     * `SUGAR.App` contains the core instance of the app. All related modules
     * can be found within the `SUGAR` namespace.
     *
     * An uninitialized instance will exist on page load but you will need to
     * call {@link App#init} to initialize your instance.
     *
     * By default, the app uses `body` element and `div#content` as root element
     * and content element respectively.
     *
     * ```
     * var app = SUGAR.App.init({
     *     el: '#root',
     *     contentEl: '#content'
     * });
     * ```
     *
     * If you want to initialize an app without initializing its modules,
     *
     * ```
     * var app = SUGAR.App.init({el: '#root', silent: true});
     * ```
     *
     * @class
     * @name App
     * @param {Object} [opts] Configuration options. See full list of options
     *   in {@link #init}.
     * @return {App} Application instance.
     * @mixes Core/BeforeEvent
     */
    function App(opts) {
        var appId = _.uniqueId('SugarApp_');
        opts = opts || {};

        return _.extend({
            /**
             * Unique application ID.
             *
             * @type {string}
             * @memberOf App
             * @instance
             */
            appId: appId,

            /**
             * Base element to use as the root of the app.
             *
             * @type {jQuery}
             * @memberOf App
             * @instance
             */
            $rootEl: _make$(opts.el || 'body'),

            /**
             * Content element selector.
             *
             * The {@link Core.Controller application controller} loads layouts
             * into the content element.
             *
             * @type {jQuery}
             * @memberOf App
             * @instance
             */
            $contentEl: _make$(opts.contentEl || '#content'),

            /**
             * Additional components.
             *
             * These components are created and rendered only once, when the
             * application starts.
             *
             * Application specific code is needed for managing the components
             * after they have been put into DOM by the framework.
             *
             * @type {Object}
             * @memberOf App
             * @instance
             */
            additionalComponents: {}

        }, this, Backbone.Events);
    }

    return {
        /**
         * Initializes the app.
         *
         * @param {Object} [opts] Initialization options.
         * @param {string} [opts.el='body'] The selector for the
         *   {@link #$rootEl base element} to use as the root of the app.
         * @param {string} [opts.contentEl='#content'] The selector for the
         *   {@link #$contentEl content element}.
         * @param {boolean} [opts.silent=false] Flag to indicate if modules
         *   should be initialized during application init process.
         * @param {Function} [opts.defaultErrorHandler] Allows you to define a
         *   custom error handler. Defaults to
         *   {@link Core.Error#handleHttpError}.
         * @return {App} Application instance
         * @fires app:init if `opts.silent` is not `false`.
         * @fires app:sync:error if the public metadata could not be synced.
         * @memberOf App
         */
        init: function(opts) {
            _app = _app || _.extend(this, new App(opts));
            _.extend(_app, BeforeEvent);

            // Register app specific events
            _app.events.register(
                /**
                 * Fires when the app object is initialized. Modules bound to
                 * this event will initialize.
                 *
                 * @event app:init
                 * @memberOf App
                 */
                'app:init',
                this
            );

            _app.events.register(
                /**
                 * Fires when the application has
                 * finished loading its dependencies and should initialize
                 * everything.
                 *
                 * @event app:start
                 * @memberOf App
                 */
                'app:start',
                this
            );

            _app.events.register(
                /**
                 * Fires when the app is beginning to sync data / metadata from
                 * the server.
                 *
                 * @event app:sync
                 * @memberOf App
                 */
                'app:sync',
                this
            );

            _app.events.register(
                /**
                 * Fires when the app has finished its syncing process and is
                 * ready to proceed.
                 *
                 * @event app:sync:complete
                 * @memberOf App
                 */
                'app:sync:complete',
                this
            );

            _app.events.register(
                /**
                 * Fires when a sync process failed.
                 *
                 * @event app:sync:error
                 * @memberOf App
                 */
                'app:sync:error',
                this
            );

            _app.events.register(
                /**
                 * Fires when a sync process failed during initialization of
                 * the app.
                 *
                 * @event app:sync:public:error
                 * @memberOf App
                 */
                'app:sync:public:error',
                this
            );

            _app.events.register(
                /**
                 * * Fires when logging in.
                 *
                 * @event app:login
                 * @memberOf App
                 */
                'app:login',
                this
            );

            _app.events.register(
                /**
                 * Fires when login succeeds.
                 *
                 * @event app:login:success
                 * @memberOf App
                 */
                'app:login:success',
                this
            );

            _app.events.register(
                /**
                 * Fires when the app logs out.
                 *
                 * @event app:logout
                 * @memberOf App
                 */
                'app:logout',
                this
            );

            _app.events.register(
                /**
                 * Fires when route changes a new view has been loaded.
                 *
                 * @event app:view:change
                 * @memberOf App
                 */
                'app:view:change',
                this
            );

            _app.events.register(
                /**
                 * Fires when client application's user changes the locale, thus
                 * indicating that the application should "re-render" itself.
                 *
                 * @event app:locale:change
                 * @memberOf App
                 */
                'app:locale:change',
                this
            );

            _app.events.register(
                /**
                 * Fires when the language display direction changes.
                 *
                 * Possible language display directions are `RTL` and `LTR`.
                 *
                 * @event lang:direction:change
                 * @memberOf App
                 */
                'lang:direction:change',
                this
            );

            // App cache must be initialized first
            if (_app.cache) {
                _app.cache.init(this);
            }

            // Instantiate controller: <Capitalized-appId>Controller or Controller.
            var className = Utils.capitalize(_app.config ? _app.config.appId : '') + 'Controller';
            var Klass = this[className] || Controller;

            /**
             * Reference to the main controller.
             *
             * @type {Core.Controller}
             * @memberOf App
             * @instance
             */
            this.controller = new Klass();

            /**
             * Reference to the API interface that the application uses to
             * request the server.
             *
             * @type {SUGAR.Api}
             * @memberOf App
             * @instance
             */
            _app.api = SUGAR.Api.getInstance({
                defaultErrorHandler: (opts && opts.defaultErrorHandler) ? opts.defaultErrorHandler : SUGAR.App.error.handleHttpError,
                serverUrl: _app.config.serverUrl,
                platform: _app.config.platform,
                timeout: _app.config.serverTimeout,
                keyValueStore: _app[_app.config.authStore || 'cache'],
                clientID: _app.config.clientID,
                disableBulkApi: _app.config.disableBulkApi,
                externalLoginUICallback: opts && opts.externalLoginUICallback
            });

            this._init(opts);

            return _app;
        },

        /**
         * Initializes application.
         *
         * Performs loading css (only if `config.loadCss` is `true`), metadata
         * sync and calls sync callback.
         *
         * @param {Object} opts Options.
         * @param {boolean} [opts.silent=false] Flag to indicate if modules
         *   should be initialized during application init process.
         * @return {App} Application instance.
         * @private
         * @memberOf App
         */
        _init: function(opts) {
            var self = this;
            var syncCallback = function(error) {

                // _app will be nulled out if destroy was called on app before we
                // asynchronously get here. This happens when running tests (see spec-helper).
                if (!_app) {
                    return;
                }
                if (error) {
                    self.trigger('app:sync:public:error', error);
                    return;
                }
                self._initModules();
                if (!opts.silent) {
                    _app.controller.setElement(_app.$rootEl);
                    _app.trigger('app:init', self);
                }

                if (opts.callback && _.isFunction(opts.callback)) {
                    opts.callback(_app);
                }
            };
            var cssCallback = function(callback) {
                if (_app.config.loadCss) {
                    _app.loadCss(callback);
                } else {
                    callback();
                }
            };
            if (_app.config.syncConfig !== false) {
                var options = {
                    getPublic: true
                };
                cssCallback(function() {
                    MetadataManager.sync(syncCallback, options);
                });
            } else {
                cssCallback(function() {
                    syncCallback();
                });
            }
            return _app;
        },

        /**
         * Initializes all modules that have an `init` function.
         *
         * @private
         * @memberOf App
         */
        _initModules: function() {
            _.each(_modules, function(module) {
                if (_.isFunction(module.init)) {
                    module.init(this);
                }
            }, this);
        },

        /**
         * Extends base settings with settings from the server.
         *
         * @private
         * @deprecated since 7.10. Please use {@link #setConfig} instead.
         * @memberOf App
         */
        _loadConfig: function() {
            this.logger.warn('`App._loadConfig` is deprecated since 7.10. Please use `App.setConfig` instead.');
            _app.config = _app.config || {};
            _app.config = _.extend(_app.config, MetadataManager.getConfig());
        },

        /**
         * Updates the config object based on the new metadata.
         *
         * Reloads modules that depend on the config.
         *
         * @memberOf App
         * @param {Object} [config] The config object. If not passed, we'll grab
         *   it using {@link Core/MetadataManager#getConfig}.
         */
        setConfig: function(config) {
            // extend our config with settings from local storage if we have it
            this.config = this.config || {};
            this.config = _.extend(this.config, config);

            // Reload the modules that depend on the configuration.
            this.logger = Logger(this.config.logger);
            this.cache = Cache({
                uniqueKey: this.config.uniqueKey,
                env: this.config.env,
                appId: this.config.appId
            });
        },

        /**
         * Loads application CSS.
         *
         * Will make an HTTP request and retrieve either a list of CSS files to
         * load, or directly plain text css.
         *
         * @param {Function} [callback] Function called once CSS is loaded.
         * @memberOf App
         */
        loadCss: function(callback) {
            _app.api.css(_app.config.platform, _app.config.themeName, {
                success: function(rsp) {

                    if (_app.config.loadCss === 'url') {
                        _.each(rsp.url, function(url) {
                            $('<link>')
                                .attr({
                                    rel: 'stylesheet',
                                    href: Utils.buildUrl(url),
                                })
                                .appendTo('head');
                        });
                    }
                    else {
                        _.each(rsp.text, function(text) {
                            $('<style>').html(text).appendTo('head');
                        });
                    }

                    if (_.isFunction(callback)) {
                        callback();
                    }
                }
            });
        },

        /**
         * Starts the main execution phase of the application.
         *
         * @memberOf App
         * @fires app:start
         */
        start: function() {
            _app.events.registerAjaxEvents();
            _app.controller.loadAdditionalComponents(_app.config.additionalComponents);
            _app.trigger('app:start', this);
            Routing.start();
        },

        /**
         * Destroys the instance of the current app.
         *
         * @memberOf App
         */
        destroy: function() {
            // TODO: Not properly implemented
            if (Backbone.history) {
                Backbone.history.stop();
            }

            _app = null;
        },

        /**
         * Augments the application with a module.
         *
         * Module should be an object with an optional `init(app)` function.
         * The init function is passed the current instance of
         * the application when app's {@link App#init} method gets called.
         * Use the `init` function to perform custom initialization logic during
         * app initialization.
         *
         * @param {string} name Name of the module.
         * @param {Object} obj Module to augment the app with.
         * @param {boolean} [init=false] Flag indicating if the module should be
         *   initialized immediately.
         * @memberOf App
         */
        augment: function(name, obj, init) {
            this[name] = obj;
            _modules[name] = obj;

            if (name === 'config') {
                this.setConfig(obj);
            }

            if (init && obj.init && _.isFunction(obj.init)) {
                obj.init.call(obj, _app);
            }
        },

        /**
         * Syncs an app.
         *
         * The events are not fired if the sync happens for public metadata.
         *
         * @param {Object} [options] Options. See full list of options
         *   you can pass to {@link Core.MetadataManager#sync}.
         * @param {Function} [options.callback] Function to be invoked when the
         *   sync operation completes.
         * @param {boolean} [options.getPublic=false] Flag indicating if only
         *   public metadata should be synced.
         *
         * @fires app:sync when the synchronization process begins.
         * @fires app:sync:complete when the series of synchronization
         *   operations have finished.
         * @fires app:sync:error if synchronization fails.
         * @memberOf App
         */
        sync: function(options) {
            var self = this;
            options = options || {};

            // For public call, we need to do just metadata sync without triggering events
            if (options.getPublic) {
                return self.syncPublic(options);
            }

            // Register the callback if any.
            if (options.callback) {
                _syncCallbacks.push(options.callback);
            }

            // If already in `sync`, we can skip as the callback is registered
            // and will be called.
            if (_isSyncing) {
                return;
            }
            _isSyncing = true;

            // 1. Update server info and run compatibility check
            // 2. Update preferred language if it was changed
            // 3. Load user preferences
            // 4. Fetch metadata
            // 5. Declare models
            async.waterfall([function(callback) {
                self.isSynced = false;
                self.trigger('app:sync');
                var doUpdateLanguage = !options.noUserUpdate && (options.language || self.cache.get('langHasChanged'));
                if (doUpdateLanguage) {
                    var language = options.language || Language.getLanguage();
                    self.user.updateLanguage(language, callback);
                    _app.cache.cut('langHasChanged');
                }
                else {
                    callback();
                }
            }, function(cbw) {
                async.parallel([
                    function(callback) {
                        self.user.load(callback);
                    }, function(callback) {
                        self.metadata.sync(function(err) {
                            self.data.declareModels();
                            callback(err);
                        }, options);
                    }], function(err) {
                    cbw(err);
                });
            }, function(callback) {
                var serverInfo = self.metadata.getServerInfo();
                self.config.sugarLogic = self.config.sugarLogic || {};

                if (serverInfo &&
                    self.config.sugarLogic.enabled &&
                    self.utils.versionCompare(serverInfo.version, self.config.sugarLogic.minServerVersion, ">="))
                {
                    self.fetchSugarLogic(callback);
                }
                else {
                    self.config.sugarLogic.enabled = false;
                    callback();
                }
            }],
                function(err) {
                    if (err) {
                        self.trigger('app:sync:error', err);
                    } else {
                        self.isSynced = true;
                        self.trigger('app:sync:complete');
                        // TODO this will be removed by SC-5256.
                        if (window.jQuery) {
                            jQuery.migrateMute = self.logger.getLevel().value > self.logger.levels.WARN.value;
                        }
                    }

                    _.each(_syncCallbacks, function(callback) {
                        callback(err);
                    });
                    // Reset the properties.
                    _isSyncing = false;
                    _syncCallbacks = [];
                }
            );
        },

        /**
         * Syncs public metadata.
         *
         * @param {Object} [options] Options. See full list of options
         *   you can pass to {@link Core.MetadataManager#sync}.
         * @param {Function} [options.callback] Function to be invoked when the
         *   sync operation completes.
         * @memberOf App
         */
        syncPublic: function(options) {
            options = options || {};
            options.getPublic = true;
            this.metadata.sync(options.callback, options);
        },

        /**
         * Navigates to a new route.
         *
         * @param {Core/Context} [context] Context object to extract the module
         *   from.
         * @param {Data/Bean} [model] Model object to route with.
         * @param {string} [action] Action name.
         * @memberOf App
         */
        navigate: function(context, model, action) {
            var route, id, module;
            context = context || _app.controller.context;
            model = model || context.get('model');
            id = model.id;
            module = context.get('module') || model.module;

            route = this.router.buildRoute(module, id, action);
            this.router.navigate(route, {trigger: true});
        },

        /**
         * Logs in to the app.
         *
         * @param {Object} credentials User credentials.
         * @param {Object} credentials.username User name.
         * @param {Object} credentials.password User password.
         * @param {Object} [info] Extra data to be passed in login request such
         *   as client user agent, etc.
         * @param {Object} [callbacks] Object containing the callbacks.
         * @param {Function} [callbacks.success] The success callback.
         * @param {Function} [callbacks.error] The error callback.
         * @param {Function} [callbacks.complete] The complete callback.
         * @memberOf App
         * @fires app:login:success on successful login.
         */
        login: function(credentials, info, callbacks) {
            callbacks = callbacks || {};

            info = info || {};
            info.current_language = Language.getLanguage();
            _app.api.login(credentials, info, {
                success: function(data) {
                    _app.trigger('app:login:success', data, credentials.username);
                    if (callbacks.success) callbacks.success(data);
                },
                error: function(error) {
                    _app.error.handleHttpError(error);
                    if (callbacks.error) callbacks.error(error);
                },
                complete: callbacks.complete
            });
        },

        /**
         * Logs out of this app.
         *
         * @param {Object} [callbacks] Object containing the callbacks.
         * @param {Function} [callbacks.success] The success callback.
         * @param {Function} [callbacks.error] The error callback.
         * @param {Function} [callbacks.complete] The complete callback.
         * @param {boolean} [clear=false] Flag indicating if user information
         *   must be deleted from cache.
         * @param {Object} [options={}] jQuery/Zepto request options.
         * @return {SUGAR.HttpRequest} XHR request object.
         * @fires app:logout
         * @memberOf App
         */
        logout: function(callbacks, clear, options) {
            var originalComplete, originalError;
            callbacks = callbacks || {};
            originalComplete = callbacks.complete;
            originalError = callbacks.error;

            callbacks.complete = function(data) {
                // The 'clear' comes from the logout URL (see router.js)
                _app.trigger('app:logout', clear);
                if (originalComplete) {
                    originalComplete(data);
                }
            };
            callbacks.error = function(error) {
                _app.error.handleHttpError(error);
                if (originalError) originalError(error);
            };

            return _app.api.logout(callbacks, options);
        },

        fetchSugarLogic: function(callback) {
            if (_app.config.sugarLogic.isDynamic) {
                _app.api.call(
                    'read',
                    _app.api.buildURL('ExpressionEngine', 'functions'),
                    null,
                    {
                        success: function(expressions) {
                            _app.cacheSugarLogicExpressions(expressions);
                            _app.loadSugarLogic(expressions, callback);
                        },
                        error: function(err) {
                            // TODO: Consider turning off SL altogether
                            callback(err);
                        }
                    },
                    { dataType: 'application/text' }
                );
            }
            else callback();

        },

        cacheSugarLogicExpressions: function(expressions) {
            _app.cache.set("sugarlogic", expressions);
        },

        _loadSugarLogic: function() {
            return _app.cache.get("sugarlogic");
        },

        loadSugarLogic: function(expressions, callback) {
            return _app.compileJs(expressions || _app._loadSugarLogic(), callback);
        },

        compileJs: function(js, callback) {
            try {
                eval.call(window, js); // jshint ignore:line
                if (callback) callback();
            }
            catch (e) {
                Logger.fatal("Failed to compile js");
                // TODO: Consider turning off SL altogether
                if (callback) callback(e);
                return e;
            }

            return null;
        },

        /**
         * Checks if the server version and flavor are compatible.
         *
         * @param {Object} data Server information.
         * @return {boolean|Object} `true` if server is compatible and an error
         *   object if not.
         * @memberOf App
         */
        isServerCompatible: function(data) {
            var flavors = this.config.supportedServerFlavors,
                minVersion = this.config.minServerVersion,
                isSupportedFlavor,
                isSupportedVersion,
                error;

            // We assume the app is not interested in the compatibility check if it doesn't have compatibility config.
            if (_.isEmpty(flavors) && !minVersion) {
                return true;
            }

            // Undefined or null data with defined compatibility config means the server is incompatible

            isSupportedFlavor = !!((_.isEmpty(flavors)) || (data && _.contains(flavors, data.flavor)));
            isSupportedVersion = !!(!minVersion || (data && this.utils.versionCompare(data.version, minVersion, '>=')));

            if (isSupportedFlavor && isSupportedVersion) {
                return true;
            } else if (!isSupportedVersion) {
                error = {
                    code: 'server_version_incompatible',
                    label: 'ERR_SERVER_VERSION_INCOMPATIBLE'
                };
            } else {
                error = {
                    code: 'server_flavor_incompatible',
                    label: 'ERR_SERVER_FLAVOR_INCOMPATIBLE'
                };
            }

            error.server_info = data;
            return error;
        },

        modules: _modules
    };

}());

// The assignments below are temporary since components should just require the
// mixins/components they need instead of relying on the global variable.
const Context = require('core/context');
SUGAR.App.Context = Context;
const ctxFactory = {
    /**
     * Gets a new instance of the context object.
     *
     * @param {Object} [attributes] Any parameters and state properties to
     *   attach to the context.
     * @return {Core/Context} New context instance.
     * @deprecated since 7.10.
     */
    getContext: function (attributes) {
        SUGAR.App.logger.warn('The function `app.context.getContext()` is deprecated in 7.10. ' +
            'Please use the `new` keyword to create a context.');
        return new Context(attributes);
    }
};

SUGAR.App.augment('Bean', require('data/bean'));
SUGAR.App.augment('BeanCollection', require('data/bean-collection'));
SUGAR.App.augment('MixedBeanCollection', require('data/mixed-bean-collection'));
SUGAR.App.augment('error', require('core/error'));
SUGAR.App.augment('context', ctxFactory);
SUGAR.App.augment('utils', Utils);
SUGAR.App.augment('cookie', require('utils/cookie'));
SUGAR.App.augment('Controller', Controller);
SUGAR.App.augment('events', require('core/events'));
SUGAR.App.augment('acl', require('core/acl'));
SUGAR.App.augment('metadata', MetadataManager);
SUGAR.App.augment('currency', require('utils/currency'));
SUGAR.App.augment('date', require('utils/date'));
SUGAR.App.augment('math', require('utils/math'));
SUGAR.App.augment('plugins', require('core/plugin-manager'));
_.mixin(require('utils/underscore-mixins'));
SUGAR.App.augment('user', require('core/user'));
Handlebars.registerHelper(require('view/hbs-helpers'));
SUGAR.App.augment('validation', require('data/validation'));
SUGAR.App.augment('data', require('data/data-manager'));
SUGAR.App.augment('lang', Language);
SUGAR.App.augment('template', require('view/template'));
SUGAR.App.Router = require('core/router');
SUGAR.App.augment('routing', Routing);
const ViewManager = require('view/view-manager');
SUGAR.App.augment('view', ViewManager);
ViewManager.Component = require('view/component');
SUGAR.App.augment('alert', require('view/alert'));
ViewManager.AlertView = require('view/alert-view');
ViewManager.Field = require('view/field');
ViewManager.Layout = require('view/layout');
ViewManager.View = require('view/view');
