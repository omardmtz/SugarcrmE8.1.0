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
    app.events.on('app:init', function() {

        /**
         * Gets system actions.
         *
         * These action items should have a `callback` string that maps to a
         * system action on
         * {@link View.Layouts.Base.SweetspotLayout#_systemActions}.
         *
         * @return {Array} Formatted items.
         */
        var getSystemActions = function() {
            var actions = [
                {
                    callback: 'openConfig',
                    action: 'config',
                    name: app.lang.get('LBL_SWEETSPOT_CONFIG'),
                    icon: 'fa-cog'
                }
            ];
            return actions;
        };

        /**
         * Verifies if the user has access to the action
         *
         * @param {string} module The module corresponding to the action.
         * @param {string} action The action
         * @returns {Object|boolean} The action object if the user has access,
         *  `false` otherwise.
         */
        var hasAccessToAction = function(module, action) {
            if (module && action.acl_action) {
                if (!app.acl.hasAccess(action.acl_action, module)) {
                    return false;
                }
                return action;
            }

            if (action.acl_action === 'admin') {
                //Edge case for admin link. We only show the Admin link when
                //user has the "Admin & Developer" or "Developer" (so developer
                //in either case; see SP-1827)
                if (!app.acl.hasAccessToAny('developer')) {
                    return false;
                }
                return action;
            }

            return action;
        };

        /**
         * Gets all the mega menu actions.
         *
         * @return {Array} Formatted items.
         */
        var getModuleLinks = function() {
            var actions = [];
            // Send the access property to options to filter ACLs as well
            var moduleList = app.metadata.getModuleNames({filter: 'display_tab', access: true});
            if (app.user.get('type') === 'admin' && app.metadata.getModule('Administration')) {
                moduleList.push('Administration');
                moduleList = _.uniq(moduleList);
            }
            _.each(moduleList, function(module) {
                var moduleMeta = app.metadata.getModule(module);
                var menuMeta = moduleMeta && moduleMeta.menu;
                var headerMeta = menuMeta && menuMeta.header && menuMeta.header.meta || [];
                var sweetspotMeta = menuMeta && menuMeta.sweetspot && menuMeta.sweetspot.meta || [];

                // merge header metadata with sweetspot metadata
                _.each(headerMeta.concat(sweetspotMeta), function(action) {
                    if (hasAccessToAction(action.acl_module || module, action) === false) {
                        return;
                    }

                    var name;
                    var jsFunc = 'push';
                    var weight;
                    var bwcModuleRoute = '#' + app.bwc.buildRoute(module);
                    var sidecarModuleRoute = '#' + module;

                    // FIXME: We need to try both because some BWC header meta
                    // returns sidecar routes. See Quotes header.php vs Reports,
                    // for example.
                    var isIndexRoute = action.route === sidecarModuleRoute || action.route === bwcModuleRoute;
                    var isCreateRoute = (action.route === '#' + module + '/create');

                    if (isIndexRoute) {
                        jsFunc = 'unshift';
                        name = app.lang.getModuleName(module, {plural: true});
                        weight = 10;
                    } else if (isCreateRoute) {
                        weight = 20;
                        name = app.lang.get(action.label, module)
                    } else {
                        weight = 30;
                        name = app.lang.get(action.label, module)
                    }
                    var actionObj = {
                        module: module,
                        label: app.lang.getModuleIconLabel(module),
                        name: name,
                        route: action.route,
                        icon: action.icon,
                        weight: weight,
                    };

                    if (action.idm_mode_link && app.metadata.getConfig().idmModeEnabled) {
                        actionObj.route = action.idm_mode_link;
                        actionObj.openwindow = true;
                    }

                    if (action.openwindow) {
                        actionObj.openwindow = action.openwindow;
                    }

                    actions[jsFunc](actionObj);
                });
            });
            var profileActions = app.metadata.getView(null, 'profileactions');
            _.each(profileActions, function(action) {
                if (hasAccessToAction(action.acl_module, action) === false) {
                    return;
                }

                var profileActionObj = {
                    name: app.lang.get(action.label, action.module),
                    route: action.route,
                    icon: action.icon,
                    weight: 10
                };

                if (action.openwindow) {
                    profileActionObj.openwindow = action.openwindow;
                }

                actions.push(profileActionObj);
            });
            return actions;
        };

        /**
         * Gets all the sweetspot actions.
         *
         * @returns {Object} The list of actions.
         */
        app.metadata.getSweetspotActions = function() {
            var collection = {};
            var actions = getModuleLinks().concat(getSystemActions());
            _.each(actions, function(action) {
                if (!action.label) {
                    // If there isn't a label, that means this action doesn't
                    // have a module, so use the action name instead.
                    action.label = app.lang.getModuleIconLabel(action.name);
                }
                collection[action.route || action.callback] = action;
            });
            return collection;
        };

    });
})(SUGAR.App);
