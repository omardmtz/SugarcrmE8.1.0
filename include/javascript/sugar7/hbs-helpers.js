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
 * Handlebars helpers.
 *
 * These functions are to be used in handlebars templates.
 * @class View.Handlebars.helpers
 * @singleton
 */
(function(app) {
    app.events.on("app:init", function() {

        /**
         * Gets the letters used for the icons shown in various headers for
         * each module, based on the translated singular module name.
         *
         * This does not always match the name of the module in the model,
         * e.g. "Product" maps to "Quoted Line Item".
         *
         * If the module has an icon string defined, use it, otherwise
         * fallback to the module's translated name.
         *
         * If there are spaces in the name, (e.g. Revenue Line Items or
         * Product Catalog), it takes the initials from the first two words,
         * instead of the first two letters (e.g. RL and PC, instead of Re
         * and Pr).
         *
         * @param {string} module Module to which the icon belongs.
         */
        Handlebars.registerHelper('moduleIconLabel', function(module) {
            return app.lang.getModuleIconLabel(module);
        });

        /**
         * Handlebar helper to get the Tooltip used for the icons shown in various headers for each module, based on the
         * translated singular module name.  This does not always match the name of the module in the model,
         * i. e. Product == Revenue Line Item
         * @param {String} module to which the icon belongs
         */
        Handlebars.registerHelper('moduleIconToolTip', function(module) {
            return app.lang.getModuleName(module);
        });

        /**
         * Handlebar helper to translate any dropdown values to have the appropriate labels
         * @param {String} value The value to be translated.
         * @param {String} key The dropdown list name.
         */
        Handlebars.registerHelper('getDDLabel', function(value, key) {
            return app.lang.getAppListStrings(key)[value] || value;
        });

        /**
         * Handlebar helper to retrieve a view template as a sub template
         * @param {String} key Key for the template to retrieve.
         * @param {Object} data Data to pass into the compiled template
         * @param {Object} options (optional) Optional parameters
         * @return {String} String Template
         */
        Handlebars.registerHelper('subViewTemplate', function(key, data, options) {
            var frame, template;

            template = app.template.getView(key, options.hash.module);

            // merge the hash variables into the frame so they can be added as
            // private @variables via the data option below
            frame = _.extend(Handlebars.createFrame(options.data || {}), options.hash);

            return template ? template(data, {data: frame}) : '';
        });

        /**
         * Handlebar helper to retrieve a field template as a sub template
         * @param {String} fieldName determines which field to use.
         * @param {String} view determines which template within the field to use.
         * @param {Object} data Data to pass into the compiled template
         * @param {Object} options (optional) Optional parameters
         * @return {String} String Template
         */
        Handlebars.registerHelper('subFieldTemplate', function(fieldName, view, data, options) {
            var frame, template;

            template = app.template.getField(fieldName, view, options.hash.module);

            // merge the hash variables into the frame so they can be added as
            // private @variables via the data option below
            frame = _.extend(Handlebars.createFrame(options.data || {}), options.hash);

            return template ? template(data, {data: frame}) : '';
        });

        /**
         * Handlebar helper to retrieve a layout template as a sub template
         * @param {String} key Key for the template to retrieve.
         * @param {Object} data Data to pass into the compiled template
         * @param {Object} options (optional) Optional parameters
         * @return {String} String Template
         */
        Handlebars.registerHelper('subLayoutTemplate', function(key, data, options) {
            var frame, template;

            template = app.template.getLayout(key, options.hash.module);

            // merge the hash variables into the frame so they can be added as
            // private @variables via the data option below
            frame = _.extend(Handlebars.createFrame(options.data || {}), options.hash);

            return template ? template(data, {data: frame}) : '';
        });

        /**
         * @method buildUrl
         * Builds an URL based on hashes sent on handlebars helper.
         *
         * Example:
         * <pre><code>
         * {{buildUrl url="path/to/my-static-file.svg"}}
         * </code></pre>
         *
         * @see Utils.Utils#buildUrl to know how we are building the url.
         *
         * @param {Object} options
         *   The hashes being sent by handlebars helper. Currently requires
         *   `options.hash.url` until we extend this to be used for image
         *   fields.
         * @return {String}
         *   The safely built url.
         */
        Handlebars.registerHelper('buildUrl', function(options) {
            return new Handlebars.SafeString(app.utils.buildUrl(options.hash.url));
        });

        /**
         * @method loading
         * Display animated loading message.
         *
         * To display loading message with default markup:
         *
         *     {{loading 'LBL_ALERT_TITLE_LOADING' }}
         *
         * You can also apply specific css classes:
         *
         *     // this will add the class `someCssClass` to `div.loading`.
         *     {{loading 'LBL_ALERT_TITLE_LOADING' cssClass='someCssClass'}}
         *
         * @param {Object} [options] Optional params.
         * @param {Object} [options.hash] The hash of the optional params.
         * @param {string} [options.hash.cssClass] A space-separated list of
         *   classes to apply to `div.loading`.
         */
        Handlebars.registerHelper('loading', function(str, options) {
            str = app.lang.get(str);
            var cssClass = ['loading'];
            if (_.isString(options.hash.cssClass)) {
                cssClass = _.unique(cssClass.concat(
                    Handlebars.Utils.escapeExpression(options.hash.cssClass).split(' ')
                ));
            }
            return new Handlebars.SafeString(
                '<div class="' + cssClass.join(' ') + '">'
                + Handlebars.Utils.escapeExpression(str)
                + '<i class="l1">&#46;</i><i class="l2">&#46;</i><i class="l3">&#46;</i>'
                + '</div>'
            );
        });

    });
})(SUGAR.App);
