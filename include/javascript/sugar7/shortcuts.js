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
     * Bind keyboard keys to a particular functional call.
     * @param {Array} keys - an array of strings of shortcut key combinations and sequences
     * @param {Function} func - callback function to be called
     * @param {View.Component} component - component that the shortcut keys are registered from
     */
    var bindShortcutKeys = function(keys, func, component) {
        var wrapper = _.wrap(func, function(callback) {
            var args = Array.prototype.slice.call(arguments, 1);
            if (!component.disposed) {
                callback.apply(component, args);
            }
            return false;
        });

        Mousetrap.bind(keys, wrapper);
    };

    /**
     * Remove keyboard binding to a particular set of keys.
     * @param {Array} keys - an array of strings of shortcut key combinations and sequences
     */
    var unbindShortcutKeys = function(keys) {
        Mousetrap.unbind(keys);
    };

    /**
     * Get custom shortcut keys, given shortcut ID.
     * @param {string} id shortcut ID
     * @param {Array} keys default shortcut keys
     * @return {Array} custom shortcut keys
     */
    var getShortcutKey = function(id, keys) {
        var shortcuts = app.user.getPreference('shortcuts');
        return (shortcuts && shortcuts[id]) ? shortcuts[id] : keys;
    };

    /**
     * Find callOnFocus value for the given key in the shortcuts object.
     * @param {Object} shortcuts Shortcuts object.
     * @param {string} key Shortcut key combination or sequence.
     * @return {boolean}
     */
    var findCallOnFocus = function(shortcuts, key) {
        var callOnFocus = false,
            shortcutKey = _.find(shortcuts, function(shortcut, id) {
                return _.contains(getShortcutKey(id, shortcut.keys), key)
            });

        if (shortcutKey) {
            callOnFocus = !!shortcutKey.callOnFocus;
        }

        return callOnFocus;
    };

    /**
     * Should not execute callback function if it's focused inside an input field, unless
     * shortcuts are registered to be called on focus.
     *
     * Should not call shortcut callbacks if shortcuts are disabled.
     */
    var originalStopCallback = Mousetrap.stopCallback;
    Mousetrap.stopCallback = function(e, element, combo) {
        var isInInputField = function () {
            return element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA'
                || element.isContentEditable;
        };

        // do not call callback if shortcuts have been disabled
        if (!app.shortcuts.isEnabled()) {
            return true;
        }

        if (isInInputField() && app.shortcuts.shouldCallOnFocus(combo)) {
            //We need to trigger change here so that the model would recognize any changes
            //to the input field while still in focus.
            $(element).trigger('change');
            return false;
        } else {
            return originalStopCallback(e, element);
        }
    };

    /**
     * Shortcuts should only be enabled when logged in.
     */
    app.events.on('app:login:success', function() {
        app.shortcuts.enable();
    });
    app.events.on('app:logout:success', function() {
        app.shortcuts.disable();
    });

    /**
     * Clear active shortcut keys before any view change
     */
    app.events.once('app:init', function() {
        app.before('app:view:change', function() {
            app.shortcuts.clearSession();
            return true;
        });
    });

    /**
     * Provides framework to add shortcut keys to the application. Shortcut keys are grouped into sessions,
     * which then can be activated, deactivated, saved, and restored.
     *
     * To enable shortcuts, ShortcutSession plugin must be added to the top-level layout. Any allowed shortcuts
     * for that session must be listed in shortcuts array in either the JavaScript controller or the metadata.
     *
     * An example of how to register a shortcut:
     *
     * <pre><code>
     * var shortcut = {
     *          id: 'Alert:Confirm',
     *          keys: 'enter',
     *          component: this,
     *          description: 'LBL_DESCRIPTION',
     *          handler: function() {}
     *     }
     * app.shortcuts.register(shortcut);
     * </code></pre>
     *
     * An example of how to register a global shortcut:
     *
     * <pre><code>
     * var shortcut = {
     *          id: 'Search:Focus',
     *          keys: ['s', 'mod+alt+0'],
     *          component: this,
     *          description: 'LBL_SHORTCUT_SEARCH',
     *          handler: function() {
     *              this.trigger('navigate:to:component', 'quicksearch-bar');
     *              this.triggerExpand();
     *          }
     *      }
     * app.shortcuts.registerGlobal(shortcut);
     * </code></pre>
     *
     * This framework is implemented on top of Mousetrap JS library (http://craig.is/killing/mice)
     *
     * Supported keys:
     * - all alphanumeric characters and symbols
     * - shift, ctrl, alt, option, meta, command
     * - backspace, tab, enter, return, capslock, esc, escape, space, pageup, pagedown, end, home, ins, del
     * - left, up, right, down
     *
     * Key combination: 'ctrl+s'
     * Multiple keys: ['ctrl+a','command+a']
     * Key sequence: 'f ctrl+a'
     *
     * @class Utils.Shortcuts
     * @alias SUGAR.App.shortcuts
     * @singleton
     */
    app.augment('shortcuts', {
        _currentSession: null, //current shortcut key session
        _savedSessions: [], //saved shortcut keys, which then can be restored.
        _globalShortcuts: {}, //global shortcut keys that can never be unregistered.
        _enable: false, //are shortcuts enabled?

        /**
         * Make the given shortcuts active.
         * @param {string|Array} shortcutIds
         * @param {View.Layout} layout - the layout that the shortcut session is tied to
         */
        createSession: function(shortcutIds, layout) {
            var self = this;

            this.clearSession();
            this._currentSession = new ShortcutSession(shortcutIds, layout);
            this._currentSession.activate();

            layout._dispose = _.wrap(layout._dispose, function(func) {
                var args = Array.prototype.slice.call(arguments, 1);
                func.apply(layout, args);
                self.deleteSavedSession(layout);
            });
        },

        /**
         * Remove all currents shortcuts.
         */
        clearSession: function() {
            if (this._currentSession) {
                this._currentSession.deactivate();
                this._currentSession = null;
            }
        },

        /**
         * Register shortcut keys to a session.
         *
         * WARNING: The method signature of passing 5 arguments has been deprecated in 7.8 and will be removed in 7.9
         * and has been replaced by a method signature of passing in a shortcut object
         *
         * Note: In the event of key conflicts, only the last registration will be kept.
         *
         * Note: Shortcut keys are not available for dashboards and dashlets.
         *
         * Note: Global shortcut keys cannot be overridden.
         *
         * @param {Object|string} shortcut shortcut key or shortcut id(deprecated)
         * @param {string} shortcut.id unique ID of the shortcut
         * @param {string|Array} shortcut.keys a string or an array of strings of shortcut key combinations and sequences
         * @param {Function} shortcut.handler callback function to be called
         * @param {View.Component} shortcut.component component that the shortcut keys are registered from
         * @param {string} shortcut.description description of what the shortcut does
         * @param {boolean} [shortcut.callOnFocus] execute callback function even when focused on input field.
         *
         * @param {string|Array} keys(optional)(deprecated) a string or an array of strings of shortcut key combinations and sequences
         * @param {Function} func(optional)(deprecated) callback function to be called
         * @param {View.Component} component(optional)(deprecated) component that the shortcut keys are registered from
         * @param {boolean} callOnFocus(optional)(deprecated) execute callback function even when focused on input field.
         */
        register: function(shortcut, keys, func, component, callOnFocus) {
            var session;

            //TODO: This conditional check is used for backward compatibility and will be removed as part of MAR-3427
            if (_.isString(shortcut)) {
                app.logger.warn('app.shortcuts.register(id, keys, func, component, callOnFocus) is deprecated and will be removed in 7.9. ' +
                    'Please use the app.shortcuts.register(shortcut) method signature.');

                shortcut = {
                    id : shortcut,
                    keys : keys,
                    handler: func,
                    component: component,
                    callOnFocus : callOnFocus
                };
            }

            if (!_.isArray(shortcut.keys)) {
                shortcut.keys = [shortcut.keys];
            }

            if (this._isGlobalShortcutKey(shortcut.keys)) {
                return;
            }

            session = this._getShortcutSessionForComponent(shortcut.component);
            if (session) {
                session.register(shortcut);
            }
        },

        /**
         * Register application-wide shortcut keys.
         *
         * Note: Global shortcut keys cannot be overridden.
         *
         * @param {Object} shortcut shortcut key
         * @param {string} shortcut.id unique ID of the shortcut
         * @param {string|Array} shortcut.keys a string or an array of strings of shortcut key combinations and sequences
         * @param {Function} shortcut.handler callback function to be called
         * @param {View.Component} shortcut.component component that the shortcut keys are registered from
         * @param {string} shortcut.description description of what the shortcut does
         * @param {boolean} [shortcut.callOnFocus] execute callback function even when focused on input field.
         */
        registerGlobal: function(shortcut) {
            if (!_.isArray(shortcut.keys)) {
                shortcut.keys = [shortcut.keys];
            }

            if (this._isGlobalShortcutKey(shortcut.keys)) {
                return;
            }

            this._globalShortcuts[shortcut.id] = {
                keys: shortcut.keys,
                func: shortcut.handler,
                component: shortcut.component,
                description: shortcut.description
            };

            if (shortcut.callOnFocus) {
                this._globalShortcuts[shortcut.id].callOnFocus = shortcut.callOnFocus;
            }

            bindShortcutKeys(shortcut.keys, shortcut.handler, shortcut.component);
        },

        /**
         * Unregister shortcut keys from a session.
         * @param {string} id - unique ID of the shortcut
         * @param {View.Component} component - component that the shortcut keys are registered from
         */
        unregister: function(id, component) {
            var session = this._getShortcutSessionForComponent(component);
            if (session) {
                session.unregister(id);
            }
        },

        /**
         * Save the currently active shortcut session.
         */
        saveSession: function() {
            this._savedSessions.push(this._currentSession); //active session could be null if there is no shortcut session
            this.clearSession();
        },

        /**
         * Restore the last shortcut session.
         */
        restoreSession: function() {
            if (this._savedSessions.length === 0) {
                return;
            }

            this.clearSession();
            this._currentSession = this._savedSessions.pop();

            if (!this._currentSession) {
                //do nothing if the active session has no shortcut session
                return;
            }

            if (this._currentSession.layout.disposed) {
                // There is a possibility of having old sessions around when layout is disposed without restoring the
                // session first.
                this.restoreSession();
            } else {
                this._currentSession.activate();
            }
        },

        /**
         * Enable shortcuts.
         */
        enable: function() {
            this._enable = true;
        },

        /**
         * Disable shortcuts.
         */
        disable: function() {
            this._enable = false;
        },

        /**
         * Is shortcuts enabled?
         * @return {boolean}
         */
        isEnabled: function() {
            return this._enable;
        },

        /**
         * Get the current shortcut session.
         * @return {ShortcutSession}
         */
        getCurrentSession: function() {
            return this._currentSession;
        },

        /**
         * Get the last saved shortcut session.
         * @return {ShortcutSession}
         */
        getLastSavedSession: function() {
            return _.last(this._savedSessions);
        },

        /**
         * Delete saved session that is tied to the particular layout.
         * @param {View.Layout} layout - the layout that the shortcut session is tied to
         */
        deleteSavedSession: function(layout) {
            var savedSessionToDelete,
                savedSession;

            for (var index = 0; index < this._savedSessions.length; index++) {
                savedSession = this._savedSessions[index];
                if (savedSession && savedSession.layout === layout) {
                    savedSessionToDelete = index;
                    break;
                }
            }

            if (!_.isUndefined(savedSessionToDelete)) {
                this._savedSessions.splice(savedSessionToDelete, 1);
            }
        },

        /**
         * Save user's custom shortcut keys.
         *
         * Note: Global shortcuts cannot be customized.
         *
         * @param {Array} shortcuts array of objects of shortcut IDs and keys
         * @param {Function} callback function to be called once save is done
         */
        saveCustomShortcutKey: function(shortcuts, callback) {
            var self = this,
                preferences;

            if (_.isEmpty(shortcuts)) {
                callback();
                return;
            }

            preferences = app.user.get('preferences');

            if (_.isEmpty(preferences.shortcuts)) {
                preferences.shortcuts = {};
            }

            _.each(shortcuts, function(shortcut) {
                if (this._isGlobalShortcutId(shortcut.id)) {
                    app.logger.error(shortcut.id + ' is a global shortcut and cannot be customized.');
                } else {
                    preferences.shortcuts[shortcut.id] = shortcut.keys;
                }
            }, this);

            this.getCurrentSession().deactivate();
            app.user.updatePreferences(preferences, function(error) {
                self.getCurrentSession().activate();
                callback(error);
            });
        },

        /**
         * Delete custom shortcut keys for the user.
         * @param {Array} shortcuts array of shortcut IDs
         * @param {Function} callback to be called once shortcuts have been restored
         */
        removeCustomShortcutKeys: function(shortcuts, callback) {
            var self = this,
                hasChanged = false,
                preferences = app.user.get('preferences');

            if (_.isEmpty(preferences.shortcuts)) {
                callback();
                return;
            }

            _.each(shortcuts, function(id) {
                if (preferences.shortcuts[id]) {
                    hasChanged = true;
                    delete preferences.shortcuts[id];
                }
            });

            if (hasChanged) {
                this.getCurrentSession().deactivate();
                app.user.updatePreferences(preferences, function(error) {
                    self.getCurrentSession().activate();
                    callback(error);
                });
            }
        },

        /**
         * Get global shortcut IDs and keys.
         * @return {Array}
         */
        getRegisteredGlobalShortcuts: function() {
            return _.map(this._globalShortcuts, function(shortcut, id) {
                return {
                    id: id,
                    keys: shortcut.keys,
                    description: shortcut.description
                };
            });
        },

        /**
         * Should this key be called when on focus?
         * @param {string} key
         * @return {boolean}
         */
        shouldCallOnFocus: function(key) {
            var shouldCall = false;

            if (this._currentSession) {
                shouldCall = this._currentSession.shouldCallOnFocus(key)
            }

            if (!shouldCall) {
                shouldCall = findCallOnFocus(this._globalShortcuts, key);
            }

            return shouldCall;
        },

        /**
         * Get the shortcut session that is tied to component.
         * @param {View.Component} component - component that the shortcut keys are registered from
         * @return {ShortcutSession}
         * @private
         */
        _getShortcutSessionForComponent: function(component) {
            var session,
                parentLayout = app.utils.getParentLayout(component);

            if (this._currentSession && this._currentSession.layout === component) {
                session = this._currentSession;
            } else {
                session = _.find(this._savedSessions, function(shortcuts) {
                    return (shortcuts && shortcuts.layout === component);
                });
            }

            if (_.isUndefined(session) && parentLayout) {
                session = this._getShortcutSessionForComponent(parentLayout);
            }

            return session;
        },

        /**
         * Are any of these keys a global shortcut key?
         * @param {Array} keys - an array of strings of shortcut key combinations and sequences
         * @return {boolean}
         * @private
         */
        _isGlobalShortcutKey: function(keys) {
            return !_.every(keys, function(key) {
                return _.every(this._globalShortcuts, function(shortcut) {
                    return _.indexOf(shortcut.keys, key) === -1;
                });
            }, this);
        },

        /**
         * Is this shortcut ID a global shortcut?
         * @param {string} id unique ID of the shortcut
         * @return {boolean}
         * @private
         */
        _isGlobalShortcutId: function(id) {
            return !!this._globalShortcuts[id];
        }
    }, false);

    /**
     * Shortcut Session - a set of shortcuts, which can be activated and deactivated as a group.
     * @param {Array} shortcutIds - an array of strings of shortcut IDs that will be enabled
     * @param {View.Layout} layout - the layout that this shortcut session is tied to, which
     *                               is normally the top-level layout
     * @constructor
     */
    var ShortcutSession = function(shortcutIds, layout) {
        this.layout = layout; //layout that this session is tied to
        this._active = false; //is session active?
        this._shortcuts = {}; //registered shortcut keys

        _.each(shortcutIds, function(id) {
            this._allowShortcut(id);
        }, this);
    };

    _.extend(ShortcutSession.prototype, {
        /**
         * Activate the shortcut keys in this session.
         */
        activate: function() {
            this.deactivate();
            this._active = true;

            _.each(this._shortcuts, function(shortcut, id) {
                var shortcutKey;
                if (!_.isEmpty(shortcut)) {
                    shortcutKey = getShortcutKey(id, shortcut.keys);
                    bindShortcutKeys(shortcutKey, shortcut.func, shortcut.component);
                }
            }, this);
        },

        /**
         * Deactivate the shortcut keys in this session.
         */
        deactivate: function() {
            if (this.isActive()) {
                _.each(this._shortcuts, function(shortcuts, id) {
                    this._unbindShortcutKeys(id);
                }, this);
                this._active = false;
            }
        },

        /**
         * Is this session active?
         * @return {boolean}
         */
        isActive: function() {
            return this._active;
        },

        /**
         * Register shortcut keys in this session.
         * @param {Object} shortcut shortcut key
         * @param {string} shortcut.id unique ID of the shortcut
         * @param {string|Array} shortcut.keys a string or an array of strings of shortcut key combinations and sequences
         * @param {Function} shortcut.handler callback function to be called
         * @param {View.Component} shortcut.component component that the shortcut keys are registered from
         * @param {string} shortcut.description description of what the shortcut does
         * @param {boolean} [shortcut.callOnFocus] execute callback function even when focused on input field.
         */
        register: function(shortcut) {
            if (!this._isShortcutAllowed(shortcut.id) || this._isInDashboard(shortcut.component)) {
                return;
            }

            if (!_.isArray(shortcut.keys)) {
                shortcut.keys = [shortcut.keys];
            }

            this.unregister(shortcut.id);
            this._bindShortcutKeys(shortcut);
        },

        /**
         * Unregister shortcut keys from this session.
         * @param {string} id - unique ID of the shortcut
         */
        unregister: function(id) {
            if (!this._isShortcutAllowed(id)) {
                return;
            }

            if (this.isActive()) {
                this._unbindShortcutKeys(id);
            }

            this._shortcuts[id] = {};
        },

        /**
         * Get shortcut IDs and keys in this session.
         * @return {Array}
         */
        getRegisteredShortcuts: function() {
            var registeredShortcuts = [];

            _.each(this._shortcuts, function(shortcut, id) {
                if (!_.isEmpty(shortcut)) {
                    registeredShortcuts.push({
                        id: id,
                        keys: getShortcutKey(id, shortcut.keys),
                        description: shortcut.description
                    });
                }
            });

            return registeredShortcuts;
        },

        /**
         * Should this key be called when on focus?
         * @param {string} key
         * @return {boolean}
         */
        shouldCallOnFocus: function(key) {
            var shouldCall = false;

            if (this.isActive()) {
                shouldCall = findCallOnFocus(this._shortcuts, key);
            }

            return shouldCall;
        },

        /**
         * Add the shortcut keys to the session and bind them if the session is active.
         * @param {Object} shortcut shortcut key
         * @param {string} shortcut.id unique ID of the shortcut
         * @param {string|Array} shortcut.keys a string or an array of strings of shortcut key combinations and sequences
         * @param {Function} shortcut.handler callback function to be called
         * @param {View.Component} shortcut.component component that the shortcut keys are registered from
         * @param {string} shortcut.description description of what the shortcut does
         * @param {boolean} [shortcut.callOnFocus] execute callback function even when focused on input field.
         * @private
         */
        _bindShortcutKeys: function(shortcut) {
            var shortcutKey;

            if (!this._isShortcutAllowed(shortcut.id)) {
                return;
            }

            this._shortcuts[shortcut.id].keys = shortcut.keys;
            this._shortcuts[shortcut.id].func = shortcut.handler;
            this._shortcuts[shortcut.id].component = shortcut.component;
            this._shortcuts[shortcut.id].description = shortcut.description;
            if (shortcut.callOnFocus) {
                this._shortcuts[shortcut.id].callOnFocus = shortcut.callOnFocus;
            }

            if (this.isActive()) {
                shortcutKey = getShortcutKey(shortcut.id, shortcut.keys);
                bindShortcutKeys(shortcutKey, shortcut.handler, shortcut.component);
            }
        },

        /**
         * Unbind shortcut keys for the given shortcut ID.
         * @param {string} unique ID of the shortcut
         * @private
         */
        _unbindShortcutKeys: function(id) {
            var keysToUnbind;

            if (this._isShortcutAllowed(id)) {
                keysToUnbind = getShortcutKey(id, this._shortcuts[id].keys);
                if (keysToUnbind) {
                    unbindShortcutKeys(keysToUnbind);
                }
            }
        },

        /**
         * Allow shortcut in this session.
         * @param {string} id Unique ID of the shortcut.
         * @private
         */
        _allowShortcut: function(id) {
            this._shortcuts[id] = {};
        },

        /**
         * Is shortcut allowed to be in this session?
         * @param {string} id Unique ID of the shortcut.
         * @return {boolean}
         * @private
         */
        _isShortcutAllowed: function(id) {
            return !_.isUndefined(this._shortcuts[id]);
        },

        /**
         * Is the component in a dashlet or a dashboard?
         * @param {View.Component} component
         * @return {boolean}
         * @private
         */
        _isInDashboard: function(component) {
            var layout = component.layout || (!_.isUndefined(component.view) && component.view.layout);
            return (layout.type === 'dashlet') || (layout.type === 'dashboard');
        }
    });
})(SUGAR.App);
