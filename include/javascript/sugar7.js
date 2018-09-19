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
    /**
     * A whitelist of routes that are allowed to be redirected to bwc
     *
     * @type {Array}
     * @private
     */
    var bwcRedirectRoutes = [
        'config',
        'create',
        'editAllRecurrences',
        'layout',
        'list',
        'record',
        'record_layout',
        'record_layout_action',
        'vcardImport'
    ];

    app.events.on('router:init', function() {
        // FIXME: Routes should be an extension of router.js, and not in a
        // privately-scoped variable; will be addressed in SC-2761.
        var routes = [
            {
                name: "index",
                route: ""
            },
            {
                name: "logout",
                route: "logout/?clear=:clear"
            },
            {
                name: "logout",
                route: "logout"
            },
            {
                name: "forgotpassword",
                route: "forgotpassword",
                callback: function() {
                    app.controller.loadView({
                        module: "Forgotpassword",
                        layout: "forgotpassword",
                        create: true
                    });
                }
            },
            {
                name: 'externalAuthError',
                route: 'externalAuthError',
                callback: function() {
                    app.controller.loadView({
                        module: 'Login',
                        layout: 'login',
                        create: true
                    });
                    app.alert.show('needs_login_error', {
                        level: 'error',
                        messages: app.lang.getAppString('LBL_LOGIN_INACTIVE_USER'),
                        title: app.lang.get('LBL_INVALID_CREDS_TITLE')
                    });
                    app.router.redirect('/');
                }
            },
            {
                name: 'about',
                route: 'about',
                callback: function() {
                    app.controller.loadView({
                        layout: 'about',
                        module: 'Home',
                        skipFetch: true
                    });
                }
            },
            {
                name: "forecasts",
                route: "Forecasts",
                callback: function() {

                    var acls = app.user.getAcls().Forecasts,
                        hasAccess = (!_.has(acls, 'access') || acls.access == 'yes'),
                        forecastBy = app.metadata.getModule('Forecasts', 'config'),
                        forecastByAcl = app.user.getAcls()[forecastBy ? forecastBy.forecast_by : {}],
                        hasForecastByAccess = (!_.has(forecastByAcl, 'access') || forecastByAcl.access === 'yes'),
                        title = '',
                        msg = '';

                    //check for access, set error messages if none
                    if (hasAccess) {
                        if (hasForecastByAccess) {
                            if (!app.utils.checkForecastConfig()) {
                                title = 'LBL_FORECASTS_MISSING_STAGE_TITLE';
                                msg = 'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES';
                            }
                        } else {
                            title = 'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE';
                            msg = 'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG';
                        }
                    } else {
                        title = 'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE';
                        msg = 'LBL_FORECASTS_ACLS_NO_ACCESS_MSG';
                    }

                    //if no errors, go on to the Forecast module, otherwise, display error message
                    if (title == '' && msg == '') {
                        app.controller.loadView({
                            module: 'Forecasts',
                            layout: 'records'
                        });
                    } else {
                        app.alert.show('no_access_to_forecasts', {
                            level: 'error',
                            title: app.lang.get(title, 'Forecasts') + ':',
                            messages: [app.lang.get(msg, 'Forecasts')]
                        });
                    }
                }
            },
            {
                name: 'profile',
                route: 'profile',
                callback: function() {
                    var route = app.bwc.buildRoute('Users', app.user.get('id'));
                    app.router.navigate(route, {trigger: true, replace: true});
                }
            },
            {
                name: "bwc",
                route: "bwc/*url",
                callback: function(url, params) {
                    url = url || '';
                    var bwcUrl = _.isEmpty(params) ? url : url + '?' + params;
                    app.logger.debug("BWC: " + bwcUrl);

                    var frame = $('#bwc-frame');
                    if (frame.length === 1 &&
                        app.utils.rmIframeMark('index.php' + frame.get(0).contentWindow.location.search) === bwcUrl
                    ) {
                        // close any drawers
                        app.drawer.reset();
                        // update hash link only
                        return;
                    }

                    if (bwcUrl === 'index.php') {
                        app.router.navigate('#Home', {trigger: true});
                        return;
                    }
                    var options = {
                        layout: 'bwc',
                        url: bwcUrl
                    };
                    var module = /module=([^&]*)/.exec(bwcUrl);

                    if (!_.isNull(module) && !_.isEmpty(module[1])) {
                        options.module = module[1];
                        // on BWC import we want to try and take the import module as the module
                        if (module[1] === 'Import') {
                            module = /import_module=([^&]*)/.exec(bwcUrl);
                            if (!_.isNull(module) && !_.isEmpty(module[1])) {
                                options.module = module[1];
                            }
                        }
                    }

                    app.controller.loadView(options);
                }
            },
            {
                name: 'search',
                route: 'search(/)(:term)',
                callback: function(term, urlParams) {
                    var searchTerm = term ? term : '';
                    var params = {modules: [], tags: []};

                    if (urlParams) {
                        var paramsArray = urlParams.split('&');
                        _.each(paramsArray, function(paramPair) {
                            var keyValueArray = paramPair.split('=');
                            if (keyValueArray.length > 1) {
                                params[keyValueArray[0]] = keyValueArray[1].split(',');
                            }
                        });
                    }

                    var appContext = app.controller.context;

                    // Set the new search term and module list in the context, if necessary.
                    var termHasChanged = appContext.get('searchTerm') !== searchTerm;
                    var modulesHaveChanged = !_.isEqual(appContext.get('module_list'), params.modules);

                    params.tags = _.map(params.tags, function(tag) {
                        return decodeURIComponent(tag);
                    });
                    var tagsHaveChanged = !_.isEqual(appContext.get('tagParams'), params.tags);

                    if (termHasChanged) {
                        appContext.set('searchTerm', searchTerm);
                    }
                    if (modulesHaveChanged) {
                        appContext.set('module_list', params.modules);
                    }
                    if (tagsHaveChanged) {
                        appContext.set('tagParams', params.tags);
                    }

                    if (tagsHaveChanged) {
                        appContext.trigger('tagsearch:fire:new')
                    } else if (termHasChanged || modulesHaveChanged) {
                        appContext.trigger('search:fire:new');
                    }

                    // Trigger an event on the quicksearch in the header. The
                    // header cannot rely on the context, since it is
                    // initialized once for the whole app.
                    var header = app.additionalComponents.header;
                    var quicksearch = header && header.getComponent('quicksearch');
                    if (quicksearch) {
                        quicksearch.trigger('route:search');
                    }

                    // If we are on the search page, we prevent the routing. The
                    // listener on `change:searchTerm` in the layout will trigger
                    // the new search.
                    if (appContext && appContext.get('search')) {
                        return;
                    }

                    app.controller.loadView({
                        layout: 'search',
                        searchTerm: searchTerm,
                        module_list: params.modules,
                        tagParams: params.tags,
                        mixed: true
                    });
                }
            },
            {
                name: "list",
                route: ":module"
            },
            {
                name: 'create',
                route: ':module/create',
                callback: function(module) {
                    // FIXME: We shouldn't be calling private methods like this.
                    // Will be addressed in SC-2761.
                    if (!app.router._moduleExists(module)) {
                        return;
                    }

                    var prevLayout = app.controller.context.get('layout');
                    // FIXME we shouldn't rely on the layout type: SC-5319
                    if (prevLayout && prevLayout !== 'login') {
                        app.drawer.open({
                            layout: 'create',
                            context: {
                                module: module,
                                create: true,
                                fromRouter: true
                            }
                        }, function(context, model) {
                            if (model && model.module === app.controller.context.get('module')) {
                                app.controller.context.reloadData();
                            }
                        });
                        return;
                    }

                    app.router.record(module, 'create');
                }
            },
            {
                name: "vcardImport",
                route: ":module/vcard-import",
                callback: function(module) {
                    // FIXME: We shouldn't be calling private methods like this.
                    // Will be addressed in SC-2761.
                    if (!app.router._moduleExists(module)) {
                        return;
                    }
                    app.controller.loadView({
                        module: module,
                        layout: "records"
                    });

                    app.drawer.open({
                        layout: 'vcard-import'
                    }, _.bind(function() {
                        //if drawer is closed (cancel), just put the URL back to default view for module
                        var route = app.router.buildRoute(module);
                        app.router.navigate(route, {replace: true});
                    }, this));
                }
            },
            {
                name: 'editAllRecurrences',
                route: ':module/:id/edit/all_recurrences',
                callback: function(module, id) {
                    // FIXME: We shouldn't be calling private methods like this.
                    // Will be addressed in SC-2761.
                    if (!app.router._moduleExists(module)) {
                        return;
                    }
                    app.controller.loadView({
                        module: module,
                        layout: 'record',
                        action: 'edit',
                        modelId: id,
                        all_recurrences: true
                    });
                }
            },
            {
                name: "layout",
                route: ":module/layout/:view"
            },
            {
                name: 'config',
                route: ':module/config',
                callback: function (module) {
                    // FIXME: We shouldn't be calling private methods like this.
                    // Will be addressed in SC-2761.
                    if (!app.router._moduleExists(module)) {
                        return;
                    }

                    var prevLayout = app.controller.context.get('layout');
                    // FIXME we shouldn't rely on the layout type: SC-5319
                    if (prevLayout && prevLayout !== 'login') {
                        app.drawer.open({
                            layout: 'config-drawer',
                            context: {
                                module: module,
                                fromRouter: true
                            }
                        });

                        return;
                    }

                    app.controller.loadView({
                        layout: 'config-drawer',
                        module: module
                    });
                }
            },
            {
                name: 'record',
                route: ':module/:id(/:action)'
            },
            {
                name: 'recordLayoutAction',
                route: ':module/:id/layout/:layout(/:action)',
                callback: function(module, id, layout, action) {
                    // FIXME: We shouldn't be calling private methods like this.
                    // Will be addressed in SC-2761.
                    if (!app.router._moduleExists(module)) {
                        return;
                    }
                    app.router.record(module, id, action, layout);
                }
            }
        ];

        app.router.addRoutes(routes);
    });

    app.events.on('app:init', function() {
        // allow subscription to successful token refresh
        app.api.setRefreshTokenSuccessCallback(function(callback) {
            callback();
            app.events.trigger("api:refreshtoken:success");
        });
    });

    app.routing.before('route', function(options) {
        var hasAccess = app.router.hasAccessToModule(options) !== false,
            isBwcRedirect = app.router.bwcRedirect(options) !== false;

        return hasAccess && isBwcRedirect;
    });

    //template language string for each page
    //i.e. records for listview, record for recordview
    var titles = {
            'records': 'TPL_BROWSER_SUGAR7_RECORDS_TITLE',
            'record': 'TPL_BROWSER_SUGAR7_RECORD_TITLE',
            'about': 'TPL_BROWSER_SUGAR7_ABOUT_TITLE',
            'activities': 'TPL_BROWSER_SUGAR7_RECORD_TITLE'
        };
    // FIXME: This should have unit test coverage, e.g. on `app:view:change`
    // ensure `document.title` is updated. Will be addressed in SC-2761.
    var getTitle = function(model) {
        var context = app.controller.context,
            module = context.get('module'),
            template = Handlebars.compile(app.lang.get(titles[context.get('layout')], module) || ''),
            moduleName = app.lang.getModuleName(module, {plural: true}),
            title;
        var titleInfo = _.extend({
            module: moduleName,
            appId: app.config.systemName || app.config.appId
        }, model ? model.attributes : {});

        // If the model has a name attached (in model.attributes),
        // we want to check if it's translatable.
        if (titleInfo.name) {
            // In the case of Dashboards record view page,
            // the translation is not stored in the current module,
            // and we want to look up from the module that dashboard is for
            if (moduleName === 'Dashboards' && titleInfo.dashboard_module) {
                titleInfo.name = app.lang.get(titleInfo.name, titleInfo.dashboard_module);
            } else {
                titleInfo.name = app.lang.get(titleInfo.name, moduleName);
            }
        }
        title = template(titleInfo);

        // title may contain XML entities because Handlebars escapes characters
        // by replacing them for use in HTML, so the true text needs to be
        // lifted before it can be set on the title
        return $('<span/>').html(title).text();
    };
    // FIXME: This should have unit test coverage, will be addressed in SC-2761.
    //set current document title with template format
    var setTitle = function(model) {
        var title = getTitle(model);
        document.title = title || document.title;
    };
    //store previous view's model
    var prevModel;

    app.events.on("app:view:change", function() {
        var context = app.controller.context,
            module = context.get("module"),
            metadata = app.metadata.getModule(module),
            title;

        if (prevModel) {
            //if previous model is existed, clean out setTitle listener
            prevModel.off("change", setTitle);
        }

        if (_.isEmpty(metadata) || metadata.isBwcEnabled) {
            //For BWC module, current document title will be replaced with BWC title
            title = $('#bwc-frame').get(0) ? $('#bwc-frame').get(0).contentWindow.document.title : getTitle();
        } else {
            var currModel = context.get('model');
            if (!_.isEmpty(currModel)) {
                title = getTitle(currModel);
                //for record view, the title should be updated once model is fetched
                currModel.on("change", setTitle, this);
                prevModel = currModel;
            } else {
                title = getTitle();
            }
        }
        document.title = title || document.title;
    }, this);

    // FIXME: This should have unit test coverage, will be addressed in SC-2761.
    var refreshExternalLogin = function() {
        var config = app.metadata.getConfig();
        app.api.setExternalLogin(config && config['externalLogin']);

        if (config && (_.isNull(config['externalLoginSameWindow']) || config['externalLoginSameWindow'] === false)) {
            app.api.setExternalLoginUICallback(window.open);
        }
    };

    app.events.on("app:sync:complete", refreshExternalLogin, this);
    app.events.on("app:init", refreshExternalLogin, this);

    // FIXME: This functionality should move into router.js, see SC-2761.
    app.Router = app.Router.extend({
        /**
         * Redirects the user to the appropriate BWC route if the requested
         * module has metadata and is in backward compatibility mode.
         *
         * If the route contains a valid BWC action that isn't the original
         * route, this function will route to that action (if the route is
         * invalid), otherwise it will route to the detail view (if an ID is
         * present).
         *
         * @param {Object} options Object containing routing information.
         * @return {boolean} Returns `false` if it will redirect to bwc, `true`
         *   otherwise.
         */
        bwcRedirect: function(options) {
            if (options.route && !_.contains(bwcRedirectRoutes, options.route)) {
                // this route is a non bwc redirecting one
                return true;
            }

            if (_.isArray(options.args) && options.args[0]) {
                var module = options.args[0];
                var id = options.args[1];
                var action = id ? 'DetailView' : 'index';
                var meta = app.metadata.getModule(module);

                if (meta && meta.isBwcEnabled) {
                    var sidecarAction = options.args[2] || options.route,
                        bwcAction = app.bwc.getAction(sidecarAction);

                    if (bwcAction !== sidecarAction) {
                        action = bwcAction;
                    }

                    var redirect = 'bwc/index.php?module=' + module + '&action=' + action;

                    if (id) {
                        redirect += '&record=' + id;
                    }

                    // let the entire before flow to finish before triggering a new navigate
                    _.defer(function() {
                        app.router.navigate(redirect, {trigger: true, replace: true});
                    });

                    return false;
                }
            }

            return true;
        },

        /**
         * Check module access before navigating to certain routes.
         * Redirects to access denied page if user is lacking module access.
         *
         * @param {Object} [options] Object containing routing information.
         * @return {Boolean} Returns `false` if the ACL check for module access
         *   fails, `true` otherwise.
         */
        hasAccessToModule: function(options) {
            options = options || {};

            var checkAccessRoutes = {
                    'record': 'view',
                    'create': 'create',
                    'vcardImport': 'create'
                },
                route = options.route || '',
                args = options.args || [],
                module = args[0],
                accessCheck = checkAccessRoutes[route];

            if (accessCheck && !app.acl.hasAccess(accessCheck, module)) {
                _.defer(function() {
                    app.controller.loadView({layout: 'access-denied'});
                });
                return false;
            }

            // FIXME: Show wizard functionality should be broken out into
            // another function; will be addressed in SC-2761.

            // Check if first time login wizard should be shown
            var showWizard = false;
            if (app.user && app.user.has('show_wizard')) {
                showWizard = app.user.get('show_wizard');
                if (showWizard) {
                    // If the license settings need to be input, don't show the wizard
                    var system_config = app.metadata.getConfig();
                    if (system_config.system_status &&
                        system_config.system_status.level &&
                        system_config.system_status.level === 'admin_only'
                    ) {
                        showWizard = false;
                    }
                }
            }
            // FIXME: Should be in a separate function, will be addressed in
            // SC-2761.
            if (showWizard) {
                var callbacks = {
                    complete: function() {
                        var module = app.utils.getWindowLocationParameterByName('module', window.location.search),
                            action = app.utils.getWindowLocationParameterByName('action', window.location.search);

                        // work around for saml authentication of a new user
                        if (_.isString(module) && _.isString(action) &&
                            module.toLowerCase() === 'users' && action.toLowerCase() === 'authenticate') {
                            window.location = window.location.pathname;
                        } else {
                            window.location.reload(); //Reload when done
                        }
                    }
                };
                app.controller.loadView({
                    layout: 'first-login-wizard',
                    module: 'Users',
                    modelId: app.user.get('id'),
                    callbacks: callbacks,
                    wizardName: app.user.get('type')
                });
                // FIXME: Should be event-driven, see:
                // https://github.com/sugarcrm/Mango/pull/18722#discussion_r11782561
                // Will be addressed in SC-2761.
                app.additionalComponents.header.hide();
                return false;
            }

            //If the password has expired (and we're not logging out which is ignored)
            if (route && route !== 'logout' && app.user && app.user.get('is_password_expired')) {
                app.controller.loadView({
                    layout: 'password-expired',
                    module: 'Users',
                    callbacks: {
                        complete: function() {
                            // Reload when password reset
                            window.location.reload();
                        }
                    },
                    modelId: app.user.get('id')
                });
                app.additionalComponents.header.hide();

                return false;
            }
        }
    });

    app.augment("progress", _.extend({
        init: function() {
            NProgress.configure({
                template: '<div class="loading gate">' +
                    '    <div class="progress progress-danger">' +
                    '        <div role="bar" class="bar"></div>' +
                    '    </div>' +
                    '</div>'
            });
            NProgress.start();
            NProgress.set(0.25);
        },
        hide: function() {
            $("#nprogress").hide();
        }}, NProgress),
        false
    );

    // For SAML Logout support
    app.events.on("app:logout:success", function(data) {
        if (app.config && app.config.externalLogin && data && data.url) {
            if (!$('#logoutframe').length) {
                $('#sugarcrm').append('<iframe id="logoutframe" name="logoutframe" />');
                $('#logoutframe').hide();
            }
            $('#logoutframe').load(function() {
                $('#logoutframe').off('load');
                $('#logoutframe').attr('src','');
            });

            if (typeof data.url == 'string') { // HTTP-Redirect binding
                $('#logoutframe').attr('src',data.url);
            } else if (typeof data.url == 'object') { // HTTP-POST binding
                var formHTML = '<form id="externalLogoutForm" method="POST" target="logoutframe" action="' +
                    data.url.url + '">';
                _.each(data.url.params, function(value, key, list) {
                    formHTML += '<input type="hidden" name="' + _.escape(key) + '" value="' + _.escape(value) + '" />';
                });
                formHTML += '</form>' +
                    '<script type="text/javascript">document.getElementById("externalLogoutForm").submit();</script>';

                $('#sugarcrm').append(formHTML);
            }

        }
    });

    // remove filters from the cache on application logout
    app.events.on('app:logout', function() {
        var filters = app.data.getCollectionClasses().Filters;
        if (filters) {
            filters.prototype.resetFiltersCacheAndRequests();
        }
    });

    /**
     * Shortcuts should be disabled in setup wizard.
     */
    app.user.on('change:show_wizard', function(user, show_wizard) {
        if (show_wizard) {
            app.shortcuts.disable();
        } else {
            app.shortcuts.enable();
        }
    });
})(SUGAR.App);
