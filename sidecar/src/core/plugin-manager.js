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
 * Plugin manager.
 *
 * Example:
 *
 * ```
 * const PluginManager = require('core/plugin-manager');
 *
 * PluginManager.register('fast-click-highlight', ['view', 'field'], {
 *     color : "red",
 *     events: {
 *         'click .fast-click-highlighted': 'onClickItem'
 *     },
 *     onClickItem: function(e) {
 *         alert(1)
 *     },
 *
 *     // The onAttach function will be called every time the plugin is
 *     // attached to a new component. It will be executed from the scope of
 *     // the component being attached to.
 *     //Applied after the plugin has been mixed into the component.
 *     onAttach: function(component, plugin) {
 *         this.on('render', function(){
 *             //same as plugin.color and component.$el.css
 *             this.$el.css('color', this.color);
 *         });
 *     }
 * });
 * ```
 *
 * If you want to use the current plugin for a view, you have to declare the
 * plugin in it:
 *
 * ```
 * const ViewManager = require('view/view-manager');
 * var MyView = ViewManager.View.extend({
 *     initialize: function(options) {},
 *     plugins: ['fast-click-highlight'],
 *      ...,
 * });
 *
 * // or
 *
 * $plugins: [{
 *     'fast-click-highlight': {
 *         events: {
 *             'click article' : 'onClickItem'
 *         }
 *     }
 * }],
 *
 * onClickItem: function() {
 *     alert(2);
 * }
 * ```
 *
 * If you want to disable a plugin, you have to use the `disabledPlugins`
 * property as in the following example:
 *
 * ```
 * var MyView = ViewManager.View.extend({
 *     initialize: function(options) {},
 *     disabledPlugins: ['fast-click-highlight'],
 *     ...,
 * });
 * ```
 * @module Core/PluginManager
 */

/**
 * Gets a plugin by name.
 *
 * @param {string} name The plugin name.
 * @param {string} type The component type. Can be one of the following:
 *   'view', 'layout', 'field', 'model' or 'collection'.
 * @return {Object} The desired plugin object.
 * @private
 */
function get(name, type) {
    if (PluginManager.plugins[type] && PluginManager.plugins[type][name]) {
        return PluginManager.plugins[type][name];
    }
};

/**
 * Checks if a plugin is disabled.
 *
 * @param {View/Component|Data/Bean|Data/BeanCollection} component The
 *   component.
 * @param {string} pluginName The plugin name.
 * @return {boolean} `true` if the plugin is disabled; `false` otherwise.
 * @private
 */
function isPluginDisabled(component, pluginName) {
    return !!_.find(component.disabledPlugins, function(name) {
        return name === pluginName;
    });
};

/**
 * @alias module:Core/PluginManager
 */
const PluginManager = {
    /**
     * A hash map containing all registered plugins.
     */
    plugins: {
        view: {},
        field: {},
        layout: {},
        model: {},
        collection: {}
    },

    /**
     * Attaches a plugin to a view.
     *
     * @param {View/Component|Data/Bean|Data/BeanCollection} component The
     *   component.
     * @param {string} type The component type. Can be one of the following:
     *   'view', 'layout', 'field', 'model' or 'collection'.
     */
    attach: function(component, type) {
        _.each(component.plugins, function(pluginName) {
            var prop = null;
            if (_.isObject(pluginName)) {
                var n = _.keys(pluginName)[0];
                prop = pluginName[n];
                pluginName = n;
            }

            var p = get(pluginName, type);
            if (p && !isPluginDisabled(component, pluginName)) {
                var events = _.extend({}, (_.isFunction(component.events)) ?
                    component.events() : component.events, p.events);

                _.extend(component, p);

                if (prop) {
                    _.extend(component, prop);

                    if (prop.events) {
                        _.extend(events, prop.events);
                    }
                }

                component.events = events;

                //If a plugin has an onAttach function, call it now so that the plugin can initialize
                if (_.isFunction(p.onAttach)) {
                    p.onAttach.call(component, component, p);
                }
            }
        }, this);
    },

    /**
     * Detaches plugins and calls the `onDetach` method on each one.
     *
     * @param {View/Component|Data/Bean|Data/BeanCollection} component The
     *   component.
     * @param {string} type The component type. Can be one of the following:
     *   'view', 'layout', 'field', 'model' or 'collection'.
     */
    detach: function(component, type) {
        _.each(component.plugins, function(name) {
            var plugin = get(name, type);
            if (plugin && _.isFunction(plugin.onDetach)) {
                plugin.onDetach.call(component, component, plugin);
            }
        }, this);
    },

    /**
     * Registers a plugin.
     *
     * @param {string} name The plugin name.
     * @param {string|string[]} validTypes The list of component types this
     *   plugin can be applied to ('view', 'field', 'layout', 'model' and/or
     *   'collection').
     * @param {Object} plugin The plugin object.
     */
    register: function(name, validTypes, plugin) {
        if (!_.isArray(validTypes)) {
            validTypes = [validTypes];
        }

        _.each(validTypes , function(type) {
            this.plugins[type] = this.plugins[type] || {};
            this.plugins[type][name] = plugin;
        }, this);
    },

    _isPluginDisabled: function(component, pluginName) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.pluginManager#_isPluginDisabled is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.pluginManager#_isPluginDisabled is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

       return isPluginDisabled(component, pluginName);
    },

    _get: function(name, type) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.pluginManager#_get is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.pluginManager#_get is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return get(name, type);
    }
};

module.exports = PluginManager;
