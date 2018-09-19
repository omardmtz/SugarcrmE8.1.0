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

const Currency = require('utils/currency');
const DateUtils = require('utils/date');
const Field = require('view/field');
const Utils = require('utils/utils');
const Language = require('core/language');
const Layout = require('view/layout');
const Template = require('view/template');
const View = require('view/view');
const ViewManager = require('view/view-manager');

/**
 * Handlebars helpers.
 *
 * These functions are to be used in
 * {@link http://handlebarsjs.com|Handlebars} templates.
 *
 * Example:
 * ```
 * // to register all the helpers at once
 * Handlebars.registerHelper(require('view/hbs-helpers'));
 *
 * // to register a single helper
 * const HbsHelpers = require('view/hbs-helpers');
 * Handlebars.registerHelper('fieldOfType', HbsHelpers.fieldOfType);
 * ```
 *
 * @module View/HbsHelpers
 */

/**
 * @alias module:View/HbsHelpers
 */
const Helpers = {
    /**
     * Creates a field widget.
     *
     * Example:
     * ```
     * {{field view model=mymodel template=edit parent=fieldset}}
     * ```
     *
     * @param {View/View} view Parent view
     * @param {Object} [options] Optional params to pass to the field.
     * @param {Backbone.Model} [options.model] The model associated with the field.
     * @param {string} [options.template] The name of the template to be used.
     * @param {Field} [options.parent] The parent field of this field.
     * @return {Handlebars.SafeString} HTML placeholder for the widget.
     */
    field: function(view, options) {
        var field = ViewManager.createField({
            def: this,
            viewDefs: this,
            view: view,
            model: options.hash.model,
            viewName: options.hash.template
        });

        if (options.hash.parent && _.isArray(options.hash.parent.fields)) {
            options.hash.parent.fields.push(field);
        }

        return field.getPlaceholder();
    },

    /**
     * Creates a field widget.
     *
     * This helper is used for fields that don't have a view definition.
     *
     * @param {string} type Field type.
     * @param {string} label Label key.
     * @return {Handlebars.SafeString} HTML placeholder for the widget.
     */
    fieldOfType: function(type, label) {
        var def = {
            type: type,
            name: type,
            label: label
        };

        var field = ViewManager.createField({
            def: def,
            view: this
        });

        return field.getPlaceholder();
    },

    /**
     * Creates a layout or a view in a given layout.
     *
     * @param {View/Layout} layout Layout that the new layout should be created in.
     * @param {string} name Name of the component to be created.
     * @return {Handlebars.SafeString} The placeholder text for the component.
     */
    component: function(layout, name) {
        var viewDef,
            newComponent,
            placeholder = '';

        viewDef = _.find(layout.meta.components, function(defs) {
            var componentName,
                componentDef;

            componentDef = defs.layout || defs.view;

            if (_.isString(componentDef)) {
                componentName = componentDef;
            } else {
                componentName = componentDef.name || componentDef.type;
            }

            return componentName === name;
        });

        if (_.isObject(viewDef) && !_.isEmpty(viewDef)) {
            newComponent = layout.initComponents([viewDef])[0];
            viewDef.initializedInTemplate = true;
            placeholder = newComponent.getPlaceholder();
        }

        return placeholder;
    },

    /**
     * Iterates through options specified by a key.
     *
     * The options collection is retrieved from the language helper.
     *
     * @param {string} key Options key.
     * @param {Function} hbsOptions HBS options.
     * @return {string} HTML string.
     */
    eachOptions: function(key, hbsOptions) {
        var options,
            ret = "",
            iterator;

        // Retrieve app list strings
        options = _.isString(key) ? Language.getAppListStrings(key) : key;

        if (_.isArray(options)) {
            iterator = function(element, index) {
                ret = ret + hbsOptions.fn({key: index, value: element});
            };
        } else if (_.isObject(options)) { // Is object evaluates arrays to true, so put it second
            iterator = function(value, key) {
                ret = ret + hbsOptions.fn({key: key, value: value});
            };
        }
        else {
            options = null;
        }

        // Don't iterate if options is not iterable
        if (options) _.each(options, iterator, this);

        return ret;
    },

    /**
     * Builds a route based on hashes sent on Handlebars helper.
     *
     * Example:
     * ```
     * {{buildRoute context=this.context}}
     * {{buildRoute model=myModel action="create"}}
     * {{buildRoute module="Employees" action="edit"}}
     * ```
     *
     * If both `module` and `model` are sent, `module` will take precedence
     * over `model.module`. Similarly, `id` will take precedence over
     * `model.id`.
     *
     * @param {Object} options Handlebars options hash.
     * @param {Object} options.hash More parameters to be used by this helper.
     *   It needs at least one of `options.hash.module`, `options.hash.model`
     *   or `options.hash.context`.
     * @param {string} [options.hash.module=options.hash.model.module]
     *   The name of the module.
     * @param {Data/Bean} [options.hash.model=options.hash.context.get('model')]
     *   The model to extract the module and/or id.
     * @param {Core/Context} [options.hash.context]
     *   A context to extract the module from.
     * @param {string} [options.hash.id=options.hash.model.id]
     *   The id of the bean record.
     * @param {string} [options.hash.action] The action name.
     * @return {string} The built route.
     */
    buildRoute: function(options) {
        var ctx = options.hash.context,
            model = options.hash.model || ((ctx && ctx.get('model')) ? ctx.get('model') : {}),
            module = options.hash.module || model.module || ((ctx && ctx.get('module')) ? ctx.get('module') : null),
            id = options.hash.id || model.id,
            action = options.hash.action;

        return SUGAR.App.router.buildRoute(module, id, action);
    },

    /**
     * Extracts the value of the given field from the given bean.
     *
     * @param {Data/Bean} bean Bean instance.
     * @param {string} field Field name.
     * @param {Object} [options] Additional options.
     * @param {string} [options.hash.defaultValue=''] Default value to return
     *   if field is not set on the bean.
     * @return {string} Field value of the given bean. If field is not set the
     *   default value or empty string.
     */
    fieldValue: function(bean, field, options) {
        return bean.get(field) || options.hash.defaultValue || '';
    },

    /**
     * Executes a given block if a given array has a value.
     *
     * @param {string|Object} val Value to look for.
     * @param {Object|Array} array Array or hash object to check.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if `val` is found.
     * @param {Function} options.inverse The block to execute if `val` is not
     *   found.
     * @return {string} Result of the `block` execution if `array` contains
     *   `val` or the result of the inverse block.
     */
    has: function(val, array, options) {
        // Since we need to check both just val = val 2 and also if val is in an array, we cast
        // non arrays into arrays
        if (!_.isArray(array) && !_.isObject(array)) {
            array = [array];
        }

        return _.include(array, val) ? options.fn(this) : options.inverse(this);
    },

    /**
     * Executes a given block if a given array doesn't have a value.
     *
     * @param {string|Object} val Value to look for.
     * @param {Object|Array} array Array or hash object to check.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if `val` is not found.
     * @param {Function} options.inverse The block to execute if `val` is
     *   found.
     * @return {string} Result of the `block` execution if the `array` doesn't
     *   contain `val` or the result of the inverse block.
     */
    notHas: function(val, array, options) {
        var fn = options.fn, inverse = options.inverse;
        options.fn = inverse;
        options.inverse = fn;

        return Handlebars.helpers.has.call(this, val, array, options);
    },

    /**
     * We require sortable to be the default if not defined in either field
     * viewdefs or vardefs. Otherwise, we use whatever is provided in either
     * field vardefs or fields viewdefs where the viewdef has more
     * specificity.
     *
     * @param {string} module Module name.
     * @param {Object} fieldViewdef The field view definition
     *   (e.g. looping through meta.panels.field it will be 'this').
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if sortable.
     * @return {string} Result of the `block` execution if sortable, otherwise
     *   empty string.
     */
    isSortable: function(module, fieldViewdef, options) {
        if (Utils.isSortable(module, fieldViewdef)) {
            return options.fn(this);
        } else {
            return '';
        }
    },

    /**
     * Executes a given block if given values are equal.
     *
     * @param {string} val1 First value to compare.
     * @param {string} val2 Second value to compare.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if the given values
     *   are equal.
     * @param {Function} options.inverse The block to execute if the given
     *   values are not equal.
     * @return {string} Result of the `block` execution if the given values are
     *   equal or the result of the inverse block.
     */
    eq: function(val1, val2, options) {
        return val1 == val2 ? options.fn(this) : options.inverse(this);
    },

    /**
     * Opposite of `eq` helper.
     *
     * @param {string} val1 first value to compare
     * @param {string} val2 second value to compare.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if the given values
     *   are not equal.
     * @param {Function} options.inverse The block to execute if the given
     *   values are equal.
     * @return {string} Result of the `block` execution if the given values are
     *   not equal or the result of the inverse block.
     */
    notEq: function(val1, val2, options) {
        return val1 != val2 ? options.fn(this) : options.inverse(this);
    },

    /**
     * Same as the `eq` helper, but the second value is a (string) regex
     * expression. This works around Handlebars' lack of support for regex
     * literals.
     *
     * Note that modifiers are not supported.
     *
     * @param {string} val1 The string to test.
     * @param {string} val2 The expression to match against. It will be passed
     *   directly to the `RegExp` constructor.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if `val1` matches
     *   `val2`.
     * @param {Function} options.inverse The block to execute if `val1` does
     *   not match `val2`.
     * @return {string} Result of the `block` execution if `val1` matches
     *   `val2` or the result of the inverse block.
     */
    match: function(val1, val2, options) {
        var re = new RegExp(val2);
        if (re.test(val1)) {
            return options.fn(this);
        } else {
            return options.inverse(this);
        }
    },

    /**
     * Same as the `notEq` helper but second value is a (string) regex
     * expression. This works around Handlebars' lack of support for regex
     * literals.
     *
     * Note that modifiers are not supported.
     *
     * @param {string} val1 The string to test.
     * @param {string} val2 The expression to match against. It will be passed
     *   directly to the `RegExp` constructor.
     * @param {Object} options Additional options.
     * @param {Function} options.fn The block to execute if `val1` does not
     *   match `val2`.
     * @param {Function} options.inverse The block to execute if `val` matches
     *   `val2`.
     * @return {string} Result of the `block` execution if the given values are
     *   not equal or the result of the inverse block.
     */
    notMatch: function(val1, val2, options) {
        var re = new RegExp(val2);
        if (!re.test(val1)) {
            return options.fn(this);
        } else {
            return options.inverse(this);
        }
    },

    /**
     * Logs a value.
     *
     * @param {*} value The value to log.
     */
    log: function(value) {
        SUGAR.App.logger.debug("*****HBS: Current Context*****");
        SUGAR.App.logger.debug(this);
        SUGAR.App.logger.debug("*****HBS: Current Value*****");
        SUGAR.App.logger.debug(value);
        console.log(value);
        SUGAR.App.logger.debug("***********************");
    },

    /**
     * Retrieves an i18n-ed string by key.
     *
     * @param {string} key Key of the label.
     * @param {string} [module] Module name.
     * @param {string} [content] Template content.
     * @return {string} The string for the given label key.
     */
    str: function(key, module, content) {
        module = _.isString(module) ? module : null;
        return Language.get(key, module, content);
    },

    /**
     * Creates a relative time element to display the human readable related
     * time.
     *
     * To provide an automatic update of this relative time, use a plugin like
     * {@link sugar.liverelativetime.js}.
     *
     * @param {string} iso8601 The ISO-8601 date string to be used for a new
     *   date.
     * @param {Object} options Handlebars options hash.
     * @param {Object} options.hash More parameters to be used by this helper.
     * @param {string} [options.hash.title] The title attribute. Defaults to
     *   current user date/time preference format.
     * @param {boolean} [options.hash.dateOnly] Setting this to `true` will
     *   format the `title` attribute with the user-formatted date only.
     * @return {string} The relative time like `10 minutes ago`.
     */
    relativeTime: function(iso8601, options) {
        var date = DateUtils(iso8601);
        var attrs, html;

        options.hash.title = options.hash.title || date.formatUser(options.hash.dateOnly);

        attrs = _.map(options.hash, function(attr, key) {
            return key + '="' + Handlebars.Utils.escapeExpression(attr) + '"';
        }).join(' ');

        // TODO we need to support dateOnly on formatNow().
        html = '<time datetime="' + date.format() + '" ' + attrs + '>' + date.fromNow() + '</time>';

        return new Handlebars.SafeString(html);
    },

    /**
     * Joins all the elements of an array by a glue string.
     *
     * @param {string[]} array Array of strings to join.
     * @param {string} glue The string to join them with.
     * @return {string} All of the strings joined with the given `glue`.
     */
    arrayJoin: function(array, glue) {
        return array.join(glue);
    },

    /**
     * Converts `\r\n`, `\n\r`, `\r`, and `\n` to `<br>`.
     *
     * @param {string} s The raw string to filter.
     * @return {Handlebars.SafeString} The given `s` with all newline
     *   characters converted to `<br>` tags.
     */
    nl2br: function(s) {
        s = Handlebars.Utils.escapeExpression(s);
        var nl2br = s.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br>');
        return new Handlebars.SafeString(nl2br);
    },

    /**
     * Formats a given currency amount according to user preferences.
     *
     * @param {number} number The number to format.
     * @param {string|Object} currencyId  The currency identifier.
     * @return {string} The formatted number.
     */
    formatCurrency: function(number, currencyId) {
        if (_.isObject(currencyId)) {
            currencyId = currencyId.hash.currencyId || undefined;
        }
        return Currency.formatAmountLocale(number, currencyId);
    },

    /**
     * Formats a given string by returning the first n characters.
     *
     * @param {string} text The text to trim.
     * @param {number} n The number of characters to return.
     * @return {string} The first `n` characters of `text`.
     */
    firstChars: function(text, n) {
        return text.substring(0, n);
    },

    /**
     * Formats a given date according to user preferences.
     *
     * @param {Date|string} date The date to format.
     * @param {Object} [options] More attributes to be used on this element for
     *   reuse.
     * @param {Object} [options.hash] More parameters to be used by this helper.
     * @param {boolean} [options.hash.dateOnly] Flag to determine whether to
     *   return just date current user date/time preference format.
     * @return {string} The formatted date.
     */
    formatDate: function(date, options) {
        date = DateUtils(date);
        return date.formatUser(options.hash.dateOnly);
    },

    /**
     * Gets the translated module name (plural or singular).
     *
     * For instance, to get the singular version of the module name (make sure
     * `LBL_MODULE_NAME_SINGULAR` is defined in the module language strings of
     * your module):
     *
     * ```
     * {{getModuleName 'Accounts'}}
     * ```
     *
     * To get the plural version, set `plural` to true and make sure
     * `LBL_MODULE_NAME` is defined in the module language strings of your
     * module:
     *
     * ```
     * {{getModuleName 'Accounts' plural=true}}
     * ```
     *
     * You can pass a default value that will be returned in case the module
     * language string of your module is not found. The following example will
     * return 'Module' (since `LBL_MODULE` is defined in the module strings or
     * the app strings):
     *
     * ```
     * {{getModuleName 'undefinedModule' defaultValue='LBL_MODULE'}}
     * ```
     *
     * In the worst case scenario (the module language string is not found and
     * no default value is specified), the module key name is returned. The
     * following example will return 'undefinedModule':
     *
     * ```
     * {{getModuleName 'undefinedModule'}}
     * ```
     *
     * @param {string} module The module defined in the language strings.
     * @param {Object} [options] Optional params to pass to the helper.
     * @param {Object} [options.hash] More parameters to be used by this helper.
     * @param {boolean} [options.hash.plural] Returns the plural form if `true`,
     *   singular otherwise.
     * @param {string} [options.hash.defaultValue] Value to be returned if the
     *   module language string is not found.
     * @return {string} The module name.
     */
    getModuleName: function(module, options) {
        var opts = {
            plural: options.hash.plural,
            defaultValue: options.hash.defaultValue
        };
        return Language.getModuleName(module, opts);
    },

    /**
     * Helper for rendering a partial template. This helper can load a partial
     * from the `templateOptions` or from the same relative location as the
     * current template.
     *
     * ```
     * {{partial 'partial-name' componentFrom defaultProperties dynamicProperty=value}}
     * ```
     *
     * The data supplied to the partial with be an object with the list of
     * `dynamicProperty`s merged into `defaultProperties` object (defaults to
     * empty object if not explicitly passed).
     *
     * For fields:
     *
     * ```
     * {{partial 'edit' this properties fallbackTemplate='detail'}}
     * ```
     *
     * For layouts:
     *
     * ```
     * {{partial 'ActivityStream' this properties}}
     * ```
     *
     * For views:
     *
     * ```
     * {{partial 'record' this properties}}
     * ```
     *
     * @param {string} name Name of the partial.
     * @param {View/Component} component The view component.
     * @param {Object} [properties] Data supplied to the partial. `options.hash`
     *   is merged into this before it is used for the template. This allows the
     *   partial to provide dynamic parameters on top of the default properties.
     *   The original component is kept as `templateComponent` in these
     *   properties.
     * @param {Object} [options] Optional parameters.
     * @param {Object} [options.hash] The hash of the optional params.
     * @param {Object} [options.hash.module=component.module] Module to use.
     * @param {Object} [options.hash.fallbackTemplate] Fallback template for
     *   field partials.
     * @return {Handlebars.SafeString} The HTML of the partial template.
     */
    partial: function(name, component, properties, options) {
        var module, template, data;

        // `properties` is optional, so `options` is `properties` is no `properties` is passed.
        if (!options && properties.hash) {
            options = properties;
            properties = {};
        }

        // Data supplied to the partial
        data = _.extend({templateComponent: component}, properties, options.hash);

        module = options.hash.module || component.module;
        if (component && component.options.templateOptions && component.options.templateOptions.partials) {
            template = component.options.templateOptions.partials[name];
        }
        // TODO: need to do recursive walk up the tree if template is not found, until we find it.
        // see _super in component.js for an example
        else if (component instanceof Field) {
            var fallbackTemplate = options.hash.fallbackTemplate;
            template = Template.getField(
                component.type, // field type
                name || 'detail', // template name
                module,
                fallbackTemplate);
        }
        else if (component instanceof View) {
            var templateName = component.tplName;

            //FIXME SC-3363 use the real inheritance chain when loading partial templates
            //Try the current component first in case the template was overridden.
            template = Template.getView(component.name + '.' + name, module) ||
                Template.getView(component.name + '.' + name);

            if (!template && templateName) {
                template = Template.getView(templateName + '.' + name, module) ||
                    Template.getView(templateName + '.' + name);
            }
        }
        else if (component instanceof Layout) {
            template = Template.getLayout(component.name + '.' + name, module) ||
                Template.getLayout(component.name + '.' + name);
        }
        return new Handlebars.SafeString(template ? template(data) : '');
    }
};

module.exports = Helpers;
