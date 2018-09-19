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

const Utils = require('utils/utils');

let path = window.location.pathname.replace(/\/[^\/]*$/, '/cookie.html');
let getCookie = function() {
    return {};
};

/**
 * Cookie storage for authentication data.
 *
 * @module Utils/Cookie
 */

/**
 * @alias module:Utils/Cookie
 */
const Cookie = {
    /**
     * Initializes cookie support and executes code after it is ready.
     *
     * @param {Function} callback Called after cookie support is ready.
     */
    initAsync: function(callback) {
        var frame = document.createElement('iframe');
        frame.style.display = 'none';
        frame.src = path;
        document.body.appendChild(frame);

        frame.contentWindow.onload = function() {
            getCookie = function() {
                return frame.contentWindow.getCookie();
            };
            callback();
        };
    },

    /**
     * Checks if the item exists in storage.
     *
     * @param {string} key Item key.
     * @return {boolean} Whether the item exists in storage.
     */
    has: function(key) {
        return key in getCookie();
    },

    /**
     * Gets an item from storage.
     *
     * @param {string} key Item key.
     * @return {string} Item with the given key.
     */
    get: function(key) {
        return getCookie()[key];
    },

    /**
     * Puts an item into storage.
     *
     * @param {string} key Item key.
     * @param {string} value Item to put.
     */
    set: function(key, value) {
        Utils.cookie.setCookie(key, value, null, path);
    },

    /**
     * Deletes an item from storage.
     *
     * @param {string} key Item key.
     */
    cut: function(key) {
        Utils.cookie.setCookie(key, '', -1, path);
    }
};

module.exports = Cookie;
