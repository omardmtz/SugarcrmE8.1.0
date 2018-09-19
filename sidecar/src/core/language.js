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

const Template = require('view/template');
const User = require('core/user');

/**
 * Language Helper.
 *
 * Provides convenient functions to pull language strings out of a
 * language label cache.
 *
 * @module Core/Language
 */

/**
 * Retrieves a string of a given type.
 *
 * If the label is a template, it will be compiled and executed with the
 * given `context`.
 *
 * @param {string} type Type of string pack: `app_strings`,
 *   `app_list_strings` or `mod_strings`.
 * @param {string} key Key of the string to retrieve.
 * @param {string} [module] Module the label belongs to.
 * @param {string|boolean|number|Array|Object} [context] Template context.
 * @return {string|undefined} String for the given key.
 * @private
 */
function get(type, key, module, context) {
    var str,
        bundle = SUGAR.App.metadata.getStrings(type);

    bundle = module ? bundle[module] : bundle;
    if (!bundle || !_.isString(bundle[key])) {
        return;
    }

    str = sanitize(bundle[key]);

    if (!_.isUndefined(context) && (str.indexOf('{{') > -1)) {
        key = 'lang.' + (module ? key + '.' + module : key);
        var tpl = Handlebars.templates[key];
        str = tpl ? tpl(context) : Template.compile(key, str)(context);
    }
    return str;
};

/**
 * Sanitizes (strips a trailing colon from) a string.
 *
 * @param {string} str String to sanitize.
 * @return {string} Sanitized string or `str` parameter if it's not a
 *   string.
 * @private
 */
function sanitize(str) {
    return (str.slice(-1) === ':') ? str.slice(0, -1) : str;
};

/**
 * The default language defined by
 * {@link #setDefaultLanguage}. Use {@link #getDefaultLanguage} to
 * retrieve this setting.
 *
 * @property {string} defaultLanguage
 * @private
 */
let defaultLanguage;

/**
 * The language that is loaded by the app and defined by
 * {@link #setCurrentLanguage}. Use {@link #getLanguage} to retrieve
 * this setting.
 *
 * @property {string} currentLanguage
 * @private
 */
let currentLanguage;

/**
 * @alias module:Core/Language
 */
module.exports = {
    /**
     * The display direction for the current language.
     *
     * @type {string}
     */
    direction: 'ltr',

    /**
     * Retrieves a string for a given key.
     *
     * This function searches the module strings first and falls back to the
     * app strings.
     *
     * If the label is a template, it will be compiled and executed with the
     * given `context`.
     *
     * @param {string} key Key of the string to retrieve.
     * @param {string|Array} [module] Module the label belongs to. If an
     *   array is passed, it will look through each module by the given
     *   order, returning the first string whose key is found in the
     *   module's language strings.
     * @param {string|boolean|number|Array|Object} [context] The template
     *   context to pass to the template in order to populate template variables.
     * @return {string} String for the given key or the `key` parameter if
     *   the key is not found in language pack.
     */
    get: function(key, module, context) {
        var str = this.getModString(key, module, context) ||
            this.getAppString(key, context) ||
            key;

        return str;
    },

    /**
     * Searches the module strings for a given key.
     *
     * @param {string} key Key of the string to retrieve.
     * @param {string|Array} [module] Module the label belongs to. If an
     *   array is passed, it will look through each module by the given
     *   order, returning the first string whose key is found in the
     *   module's language strings.
     * @param {string|boolean|number|Array|Object} [context] The template
     *   context to pass to the template in order to populate template variables.
     * @return {string|undefined} String for the given key from the module
     *   language strings. `undefined` if not found.
     */
    getModString: function(key, module, context) {
        var moduleString;

        if (_.isArray(module)) {
            _.find(module, function(moduleName) {
                moduleString = get('mod_strings', key, moduleName, context);
                return !_.isEmpty(moduleString);
            }, this);
        } else {
            moduleString = get('mod_strings', key, module, context);
        }

        return moduleString;
    },

    /**
     * Retrieves an application string for a given key.
     *
     * @param {string} key Key of the string to retrieve.
     * @param {string|boolean|number|Array|Object} [context] The template
     *   context to pass to the string/template in order to populate template
     *   variables.
     * @return {string|undefined} String for the given key from language
     *   strings. `undefined` if not found.
     */
    getAppString: function(key, context) {
        return get('app_strings', key, null, context);
    },

    /**
     * Retrieves an application list string or object.
     *
     * @param {string} key Key of the string to retrieve.
     * @return {string|Object} String or object for the given key. If key
     *   is not found, an empty object is returned.
     */
    getAppListStrings: function(key) {
        var list = SUGAR.App.metadata.getStrings('app_list_strings')[key] || {};
        if (_.isArray(list)) {
            var obj = {};
            _.each(list, function(tuple) {
                if (_.isString(tuple)) {
                    obj[tuple] = tuple;
                } else if (_.isArray(tuple) && tuple.length === 2) {
                    obj[tuple[0]] = tuple[1];
                }
            });
            list = obj;
        }
        return list;
    },

    /**
     * Gets the translated module name (by default, in singular form).
     *
     * Falls back to the plural form if the singular form is not found, and
     * eventually falls back to the `module` passed in.
     *
     * @param {string} module The module.
     * @param {Object} [options] Options object for `getModuleName`.
     * @param {boolean} [options.plural] Returns the plural form if `true`,
     *   singular otherwise.
     * @param {string} [options.defaultValue] Value to be returned if the
     *   module language string is not found.
     * @return {string} The module name.
     */
    getModuleName: function(module, options) {
        options = options || {};
        var name = !options.plural &&
            this.getModString('LBL_MODULE_NAME_SINGULAR', module) ||
            this.getModString('LBL_MODULE_NAME', module);

        if (!name && !_.isUndefined(options.defaultValue)) {
            name = this.get(options.defaultValue);
        }

        return name || module;
    },

    /**
     * Returns the correct ordered array of strings for a given list.
     *
     * @param {string} listName Name of the strings array to retrieve.
     * @return {Array} The array of strings.
     */
    getAppListKeys: function(listName) {
        var keys = [],
            list = SUGAR.App.metadata.getStrings('app_list_strings')[listName] || {};
        if (_.isArray(list)) {
            _.each(list, function(tuple) {
                if (tuple.length === 2) {
                    keys.push(tuple[0]);
                }
            });
        } else if (_.isObject(list)) {
            keys = _.keys(list);
        }
        return keys;
    },

    /**
     * Gets the IETF's BCP 47 language code for the current app language.
     *
     * @return {string} The IETF's BCP 47 language code of the default language.
     *   (e.g. `en_us`, `pt_PT`). Note: We use `_` instead of `-`.
     */
    getLanguage: function() {
        return currentLanguage;
    },

    /**
     * Sets app language code and syncs it with the server.
     *
     * @param {string} language language code such as `en_us`.
     * @param {Function} [callback] Callback function to be called on
     *   language set completes.
     * @param {Object} [options] Extra options.
     * @param {boolean} [options.noSync=false] `true` if you don't need to
     *   fetch /metadata.
     * @param {boolean} [options.noUserUpdate=false] `true` if you don't need
     *   to update /me.
     */
    setLanguage: function(language, callback, options) {
        var self = this;
        options = options || {};
        _.each(Handlebars.templates, function(value, key) {
            if (key.indexOf('lang.') === 0) {
                delete Handlebars.templates[key];
            }
        });
        if (options.noSync === true) {
            this.updateLanguage(language);
            return;
        }

        SUGAR.App.sync({
            callback: function(err) {
                var langHasChanged = false;
                if (!err) {
                    self.updateLanguage(language);
                    langHasChanged = !SUGAR.App.api.isAuthenticated() && !options.noUserUpdate;
                    SUGAR.App.cache.set('langHasChanged', langHasChanged);//persist even after reloads
                }
                if (callback) callback(err);
            },
            getPublic: !SUGAR.App.api.isAuthenticated(),
            noUserUpdate: options.noUserUpdate || false,
            language: language,
            forceRefresh: true,  // Needed to make sure new labels are injected
            metadataTypes: ['labels']
        });
    },

    /**
     * Updates language code and the display direction.
     *
     * @param {string} language Language code as defined in Sugar.
     *   (e.g. `en_us`, `pt_PT`)
     */
    updateLanguage: function(language) {
        SUGAR.App.cache.set('lang', language);
        User.setPreference('language', language);
        this.setCurrentLanguage(language);
        SUGAR.App.trigger('app:locale:change', language);
    },

    /**
     * Sets the app default language to the language specified. Use
     * {@link #getDefaultLanguage} to get the current language.
     *
     * @param {string} language The IETF's BCP 47 language code to set the
     *   default language to. (e.g. `en_us`, `pt_PT`).
     *   Note: We use `_` instead of `-`.
     */
    setDefaultLanguage: function(language) {
        defaultLanguage = language;
    },

    /**
     * Gets the default language set in the system.
     *
     * @return {string} The IETF's BCP 47 language code of the default language.
     *   (e.g. `en_us`, `pt_PT`). Note: We use `_` instead of `-`.
     */
    getDefaultLanguage: function() {
        return defaultLanguage;
    },

    /**
     * Sets the current language to the language specified. Use
     * {@link #getLanguage} to get the current language.
     *
     * Calls {@link #setDirection} with the new language code.
     *
     * @param {string} language The language to set the current language to.
     */
    setCurrentLanguage: function(language) {
        currentLanguage = language;
        this.setDirection(language);
    },

    /**
     * Sets the {@link #direction} to the desired direction according to
     * the language code specified.
     *
     * @fires 'lang:direction:change' if the language direction has
     *   changed.
     *
     * @param {string} lang Language code that the direction is based on.
     */
    setDirection: function(lang) {
        //FIXME: SC-3358 Should be getting the RTL languages from metadata.
        var rtlLanguages = ['he_IL', 'ar_SA'],
            isRTL = _.contains(rtlLanguages, lang),
            prevDirection = this.direction;

        this.direction = isRTL ? 'rtl' : 'ltr';

        if (this.direction !== prevDirection) {
            SUGAR.App.trigger('lang:direction:change');
        }
    },

    _get: function(type, key, module, context) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Language#_get is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Language#_get is a private method that you should not access to. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return get(type, key, module, context);
    },

    _sanitize: function(str) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Language#_sanitize is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Language#_sanitize is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return sanitize(str);
    }
};
