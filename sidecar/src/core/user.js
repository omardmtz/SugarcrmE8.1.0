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

const Events = require('core/events');

function makeLastState() {
    var keySeparator = ':',
        keyPrefix = 'last-state',
        lastStates = {},
        preservedKeys = [ ];

    var buildLastStateKeyForStorage = function(key) {
        var keyParts = key.split(keySeparator);
        var storedKey = [SUGAR.App.user.id, keyPrefix];

        storedKey = storedKey.concat(keyParts);

        return storedKey.join(keySeparator);
    };

    var getLastStateId = function(component) {
        var lastStateId;

        if (component.meta && component.meta.last_state) {
            lastStateId = component.meta.last_state.id;
        }

        return lastStateId;
    };

    /**
     * Allows interactions with the last state values, which are used to
     * implement last application states or "stickiness".
     * @class
     * @name Core/User.LastState
     */

    /**
     * @type {Core/User.LastState}
     * @name lastState
     * @memberOf module:Core/User
     */
    return {
        /**
         * Get the last state value given a key. If it doesn't exist,
         * return the default value as specified in the component
         * metadata.
         *
         * @param {string} key The local storage key.
         * @return {*} The last state
         *   value.
         * @memberOf Core/User.LastState
         */
        get: function(key) {
            var result, storedKey;

            if (!_.isUndefined(key)) {
                storedKey = buildLastStateKeyForStorage(key);
                result = SUGAR.App.cache.get(storedKey);
                result = result === void 0 ? this.defaults(key) : result;
            }

            return result;
        },

        /**
         * Saves the last state in local storage.
         *
         * @param {string} key The local storage key.
         * @param {string} value The value to associate with `key`.
         * @memberOf Core/User.LastState
         */
        set: function(key, value) {
            if (!_.isUndefined(key) && !_.isUndefined(value)) {
                var storedKey = buildLastStateKeyForStorage(key);
                SUGAR.App.cache.set(storedKey, value);
            }
        },

        /**
         * Registers a state as important (should survive a cache clean).
         *
         * @param {string} key The key of the state to preserve.
         * @memberOf Core/User.LastState
         */
        preserve: function(key) {
            if (!_.isUndefined(key)) {
                preservedKeys.push(key);
            }
        },

        /**
         * Gets the key for a given component, which is used as a key
         * for CRUD operations on last state values.
         *
         * @param {string} name State type name.
         * @param {Object} component Component name.
         * @return {string} A last state key corresponding to the
         *   given values.
         * @memberOf Core/User.LastState
         */
        key: function(name, component) {
            var lastStateId = getLastStateId(component);
            return this.buildKey(name, lastStateId, component.module);
        },

        /**
         * Builds the key for a given name, lastStateId, and
         * (optionally) module, which is used as a key for CRUD
         * operations on last state values.
         *
         * @param {string} name State type name.
         * @param {string} lastStateId Last state identifier.
         * @param {string} [module] Module name.
         * @return {string} A last state key corresponding to the
         *   given values.
         * @memberOf Core/User.LastState
         */
        buildKey: function(name, lastStateId, module) {
            var keyString, keyParts = [];

            if (lastStateId) {
                if (module) {
                    keyParts.push(module);
                }
                keyParts.push(lastStateId, name);
                keyString = keyParts.join(keySeparator);
            }

            return keyString;
        },

        /**
         * Gets the default last state for a key.
         *
         * @param {string} key A last state key.
         * @return {string} The default last state for the given `key`.
         * @memberOf Core/User.LastState
         */
        defaults: function(key) {
            return lastStates[key];
        },

        /**
         * Registers last states default values given a component.
         * The default value is specified in the component metadata.
         *
         * @param {Object} component Component to register default
         *   states for.
         * @memberOf Core/User.LastState
         */
        register: function(component) {
            var lastStateId = getLastStateId(component);
            if (lastStateId){
                _.each(component.meta.last_state.defaults, function(defaultState, key) {
                    lastStates[this.key(key, component)] = defaultState;
                }, this);
            }
        },

        /**
         * Deletes last state from local storage.
         *
         * @param {string} key The state to remove from local storage.
         * @memberOf Core/User.LastState
         */
        remove: function(key) {
            var storedKey;
            if (!_.isUndefined(key)) {
                storedKey = buildLastStateKeyForStorage(key);
                SUGAR.App.cache.cut(storedKey);
            }
        },

        /**
         * Retrieves a list of important last value keys in the cache.
         *
         * @return {Array} List of high value keys in cache.
         * @private
         * @memberOf Core/User.LastState
         */
        _getPreservedKeys: function() {
            var ret = [];
            _.each(preservedKeys, function(key) {
                ret.push(buildLastStateKeyForStorage(key));
            });
            return ret;
        }
    };
}

/**
 * Represents application's current user object.
 *
 * The user object contains settings that are fetched from the server
 * and whatever settings the application wants to store.
 *
 * ```
 * // Sample user object that is fetched from the server:
 * {
 *      id: "1",
 *      full_name: "Administrator",
 *      user_name: "admin",
 *      preferences: {
 *          timezone: "America\/Los_Angeles",
 *          datepref: "m\/d\/Y",
 *          timepref: "h:ia"
 *      }
 * }
 *
 * // Use it like this:
 * const User = require('core/user');
 * var userId = User.get('id');
 * // Set app specific settings
 * User.set('sortBy:Cases', 'case_number');
 *
 * // Bind event handlers if necessary
 * User.on('change', function() {
 *     // Do your thing
 * });
 * ```
 *
 * @module Core/User
 */
var User = Backbone.Model.extend({
    /**
     * Retrieves and sets the user preferences.
     *
     * @param {Function} [callback] Callback called when update completes.
     */
    load: function(callback) {
        SUGAR.App.api.me('read', null, null, {
            success: _.bind(function(data) {
                if (data && data.current_user) {
                    // Set the user pref hash into the cache for use in
                    // checking user pref state change
                    if (data.current_user._hash) {
                        SUGAR.App.cache.set('userpref:hash', data.current_user._hash);
                    }
                    this.set(data.current_user);
                    var language = this.getPreference('language');
                    if (SUGAR.App.lang.getLanguage() !== language) {
                        SUGAR.App.lang.setLanguage(language, null, {
                            noUserUpdate: true,
                            noSync: SUGAR.App.isSynced || SUGAR.App.metadata.isSyncing()
                        });
                    }
                }
                if (callback) callback();
            }, this),
            error: function(err) {
                SUGAR.App.error.handleHttpError(err);
                if (callback) callback(err);
            }
        });
    },

    // Fixme This doesn't belong in user. See TY-526.
    /**
     * Loads the current user's locale.
     *
     * @param {Function} [callback] Called when loading the locale completes.
     */
    loadLocale: function(callback) {
        SUGAR.App.api.call('read', SUGAR.App.api.buildURL('locale'), null, {
            success: function(data) {
                if (callback) callback(data);
            },
            error: function(err) {
                SUGAR.App.error.handleHttpError(err);
                if (callback) callback(err);
            }
        });
    },

    /**
     * Retrieves the current user's preferred language.
     *
     * @return {string} The current user's preferred language.
     */
    getLanguage: function() {
        return this.getPreference('language') || SUGAR.App.cache.get('lang');
    },

    /**
     * Updates the user's preferred language.
     *
     * @param {string} language Language key.
     * @param {Function} [callback] Callback called when update completes.
     */
    updateLanguage: function(language, callback) {
        //Note that `err` is only relevant here when called for error case
        var done = function(err) {
            if (!err) SUGAR.App.lang.updateLanguage(language);
            if (callback) callback(err);
        };
        this.update("update", {preferred_language: language}, done);
    },

    /**
     * Updates the user's profile.
     *
     * @param {Object} attributes The model attributes to update for user.
     * @param {Function} [callback] Callback called when update completes.
     */
    updateProfile: function(attributes, callback) {
        //Note that `err` is only relevant here when called for error case
        var done = function(err) {
            if (callback) callback(err);
        };
        this.update('update', attributes, done);
    },

    /**
     * Updates the user's preferences.
     *
     * @param {Object} attributes The attributes to update for user.
     * @param {Function} [callback] Callback called when update completes.
     */
    updatePreferences: function(attributes, callback) {
        var self = this;
        if (SUGAR.App.api.isAuthenticated()) {
            SUGAR.App.api.call('update', SUGAR.App.api.buildURL('me/preferences'), attributes, {
                success: function(data) {
                    if (data._hash) {
                        SUGAR.App.cache.set('userpref:hash', data._hash);
                        self.set({'_hash': data._hash});
                    }
                    //Immediately update our user's preferences to reflect latest changes
                    _.each(attributes, function(val, key) {
                        if (data[key]) {
                            self.setPreference(key, data[key]);
                        }
                    });
                    if (callback) callback();
                },
                error: function(err) {
                    SUGAR.App.error.handleHttpError(err);
                    if (callback) callback(err);
                }
            });
        } else {
            if (callback) callback();
        }
    },

    /**
     * Updates the user.
     *
     * @param {string} method Operation type: either 'read', 'update',
     *   'create', or 'delete'. {@see SUGAR.Api#me}.
     * @param {Object} payload An object literal with payload.
     * @param {Object} callback Callback called when update completes. In
     *   case of error, `App.error.handleHttpError` will be called here.
     */
    update: function(method, payload, callback) {
        if (SUGAR.App.api.isAuthenticated()) {
            SUGAR.App.api.me(method, payload, null, {
                success: _.bind(function(data) {
                    if (data.current_user) {
                        if (data.current_user._hash) {
                            SUGAR.App.cache.set('userpref:hash', data.current_user._hash);
                        }
                        this.set(data.current_user);
                    }
                    if (callback) callback();
                }, this),
                error: function(err) {
                    SUGAR.App.error.handleHttpError(err);
                    if (callback) callback(err);
                }
            });
        } else {
            callback();
        }
    },

    /**
     * Gets ACLs.
     * Precondition: either the user is logged in or an `_reset` call has
     * set the user manually.
     *
     * @return {Object} Dictionary of ACLs.
     */
    getAcls: function() {
        return this.get('acl') || {};
    },

    /**
     * Gets a preference by name.
     *
     * @param {string} name The preference name.
     * @return {*} The value of the user preference, or `name` if no
     *   corresponding preference value exists.
     *
     * @todo support category parameter for preferences.
     */
    getPreference: function(name) {
        var preferences = this.get('preferences') || {};
        return preferences[name];
    },

    /**
     * Set preference by name. Will only be stored locally.
     *
     * @param {string} name The preference name.
     * @param {*} value The new preference value.
     * @return {Object} The instance of this user.
     *
     * @todo support category parameter for preferences.
     * @todo support save preferences on server.
     */
    setPreference: function(name, value) {
        var preferences = this.get('preferences') || {};
        preferences[name] = value;
        return this.set('preferences', preferences);
    },

    /**
     * Returns an object with all the user's currency preferences.
     * If the user hasn't specified any preferences,
     * these default to system currency preferences.
     *
     * @return {Object} The user's currency preferences.
     */
    getCurrency: function() {
        var preferences = this.get('preferences'),
            currencyObj = {};

        if (preferences) {
            currencyObj.currency_id = preferences.currency_id;
            currencyObj.currency_iso = preferences.currency_iso;
            currencyObj.currency_name = preferences.currency_name;
            currencyObj.currency_rate = preferences.currency_rate;
            currencyObj.currency_show_preferred = preferences.currency_show_preferred;
            currencyObj.currency_symbol = preferences.currency_symbol;
        }

        return currencyObj;
    },

    lastState: makeLastState()
});

const MyUser = new User();

Events.on('app:logout', function(clear) {
    SUGAR.App.cache.cut('userpref:hash');
    if (clear === true) {
        MyUser.clear({silent: true});
    }
});

Events.on('cache:clean', function(cb) {
    cb(MyUser.lastState._getPreservedKeys());
});

module.exports = MyUser;
