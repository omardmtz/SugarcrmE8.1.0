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
        app.lang = _.extend(app.lang, {

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
            getModuleIconLabel: function(module) {
                var name = app.lang.getAppListStrings('moduleIconList')[module] ||
                        app.lang.getModuleName(module);
                var space = name.indexOf(' ');
                var hasSpace = space !== -1;
                var result;

                if (hasSpace) {
                    result = name.substring(0, 1) + name.substring(space + 1, space + 2);
                } else {
                    result = name.substring(0, 2);
                }

                return result;
            }
        });

    });

    /**
     * When application finishes syncing.
     */
    app.events.on('app:sync:complete', function() {

        var language = app.user.getPreference('language')

        if (language) {
            language = language.replace('_', '-')

            // Set moment.js locale with moment.js 2.8+
            app.date.locale(language.toLowerCase());

            if ($.fn.select2.locales) {
                var twoLetterCode = language.substring(0, 2).toLowerCase();
                if (twoLetterCode in $.fn.select2.locales) {
                    $.extend($.fn.select2.defaults, $.fn.select2.locales[twoLetterCode]);
                }
                if (language in $.fn.select2.locales) {
                    $.extend($.fn.select2.defaults, $.fn.select2.locales[language]);
                }
            }
        }

    });

    app.events.on('lang:direction:change', function() {
        var direction = app.lang.direction,
            isRTL = direction === 'rtl';
        $('html').toggleClass('rtl', isRTL);
    });

})(SUGAR.App);
