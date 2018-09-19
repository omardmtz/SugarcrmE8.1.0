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

/**
 * Local storage manager. Provides handy methods to interact with local
 * storage in a cross-browser compatible way.
 *
 * By default, the cache manager uses store.js to manipulate items in the
 * `window.localStorage` object.
 *
 * The value of the key which is passed as a
 * parameter to `get/set/add` methods is prefixed with `<env>:<appId>:` string
 * to avoid clashes with other environments and applications running off the
 * same domain name and port. You can set environment and application ID in
 * the configuration file.
 *
 * @module Core/Cache
 */

/**
 * Default key prefix.
 *
 * @private
 */
let keyPrefix = '';

/**
 * The configuration object.
 *
 * @private
 */
let config = {};

/**
 * Helper method to build a local storage key.
 *
 * @param {string} key The raw key.
 * @return {string} The prefixed key.
 * @private
 */
let buildKey = (key) => keyPrefix + key;

let sugarStore = _.extend({}, store, {
    // make store compatible with stash
    cut: store.remove,
    cutAll: store.clear,
});

/**
 * Helper method used by `migrateOldKeys` for removing quotes from the previous
 * local storage library.
 *
 * @param {string} str The string to remove quote marks from.
 * @return {string} `str` with quotation marks removed.
 * @private
 */
let unquote = (str) => (new Function('return ' + str))(); // jshint ignore:line

/**
 * Attempts to migrate values from Stash to Store.
 *
 * `stash.js` used to set values with single quotes in `localStorage`.
 * `store.js` uses double quotes (standard JSON encode/decode). Hence, we
 * need to update current values in `localStorage` to be compliant with the
 * new store.
 *
 * @param {Object} cache Cache object to work on.
 * @private
 */
let migrateStorage = function(cache) {
    if (cache.has('uniqueKey')) {
        return;
    }

    // We need to clone the local storage to not mess up with it during the
    // iterations.
    let store = {};
    for (let i = 0, len = localStorage.length; i < len; i++) {
        let key = localStorage.key(i);
        store[key] = localStorage[key];
    }

    _.each(store, function(value, key) {
        try {
            JSON.parse(value);
            value = cache.store.deserialize(value);
        } catch (e) {
            value = unquote(value);
        }

        if (value === null) {
            value = undefined;
        }

        cache.store.set(key, value);
    });

    cache.set('uniqueKey', config.uniqueKey);
};

/**
 * @alias:module Core/Cache
 * @function
 * @param {Object} cfg The configuration object.
 * @param {string} cfg.appId Application identifier.
 * @param {string} cfg.env Application environment.
 *   Possible values are 'dev', 'test', and 'prod'.
 * @param {string} cfg.uniqueKey Key used to prevent local storage
 *   values from leaking to other application instances.
 */
const Cache = _.extend({}, Backbone.Events, {
    /**
     * Storage provider.
     *
     * Default: store.js. See {@link https://github.com/marcuswestin/store.js}.
     */
    store: sugarStore,

    /**
     * Initializes the local storage manager.
     */
    init: function() {
        keyPrefix = `${config.env}:${config.appId}:`;

        migrateStorage(this);

        if (this.get('uniqueKey') !== config.uniqueKey) {
            // do not leak information to other instances
            this.cutAll(true);
            this.set('uniqueKey', config.uniqueKey);
        }

        Events.register('cache:clean', this);
    },

    /**
     * Checks if the given item exists in local storage.
     *
     * @param {string} key Item key.
     * @return {boolean} `true` if `key` exists in local storage; `false`
     *   otherwise.
     */
    has: function(key) {
        return this.store.has(buildKey(key));
    },

    /**
     * Gets an item from local storage.
     *
     * @param {string} key Item key.
     * @return {number|boolean|string|Array|Object} Item with the given key.
     */
    get: function(key) {
        return this.store.get(buildKey(key));
    },

    /**
     * Puts an item into local storage.
     *
     * @param {string} key Item key.
     * @param {number|boolean|string|Array|Object} value Item to put.
     */
    set: function(key, value) {
        key = buildKey(key);

        try {
            this.store.set(key, value);
        } catch (e) {
            if (e.name.toLowerCase().indexOf('quota') > -1) {
                // Local storage is full; the app needs to handle this.
                this.clean();
                this.store.set(key, value);
            }
        }
    },

    /**
     * Removes non-critical values to free up space. It should be called
     * whenever local storage quota is exceeded.
     * You can listen to the `cache:clean` event (passes callback as argument)
     * in order to register keys to preserve after clean.
     * Keys that are not vital should not be preserved during a cleanup.
     *
     * Example:
     * ```
     * ({
     *     initialize: function(options) {
     *         Events.on('cache:clean', function(callback) {
     *             callback([
     *                 'my_important_cache_key',
     *                 'my_other_important_key',
     *             ])
     *         });
     *     },
     * });
     * ```
     *
     * @fires cache:clean
     */
    clean: function() {
        let preserveKeys = [];
        let preservedValues = {};

        //First get a list of all keys to keep
        this.trigger('cache:clean', function(keys) {
            preserveKeys = _.union(keys, preserveKeys);
        });
        //Now get those values
        _.each(preserveKeys, function(key) {
            preservedValues[key] = this.get(key);
        }, this);
        //nuke all the keys we own
        this.cutAll();

        //restore any vital values
        _.each(preservedValues, function(value, key) {
            if (!_.isUndefined(value)) {
                this.set(key, value);
            }
        }, this);
    },

    /**
     * Deletes an item from local storage.
     *
     * @param {string} key Item key.
     */
    cut: function(key) {
        key = buildKey(key);
        if (this.store.has(key)) {
            this.store.cut(key);
        }
    },

    /**
     * Deletes all items from local storage.
     *
     * By default, this method deletes all items for the current app and
     * environment. Pass `true` to this method to remove all items.
     *
     * @param {boolean} [all=false] Flag indicating if all items must be
     *   deleted from local storage.
     */
    cutAll: function(all) {
        if (all === true) {
            return this.store.cutAll();
        }

        var obj = this.store.getAll();
        _.each(obj, function(value, key) {
            if (key.indexOf(keyPrefix) === 0) {
                this.store.cut(key);
            }
        }, this);
    }
});

module.exports = function(cfg) {
    config = cfg || {};
    return Cache;
};
