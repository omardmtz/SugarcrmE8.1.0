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
     * The SugarCRM Accessibility API provides an interface and plugin
     * framework to apply accessibility rules to any element.
     *
     * Executes all accessibility helpers on a {@link Component} anytime that
     * component is rendered.
     *
     * Helpers:
     *
     * Accessibility helpers are loaded from SUGAR.App.accessibility.helpers.
     * Each helper is responsible for one aspect of accessibility. Below is a
     * sample helper.
     * <pre><code>
     * (function(app) {
     *     app.accessibility = app.accessibility || {};
     *     app.accessibility.helpers = app.accessibility.helpers || {};
     *     app.accessibility.helpers['foo'] = {
     *         // note that the parameter can be either a component or a
     *         // jQuery-wrapped element, so each helper is responsible for
     *         // extracting the object it should deal with
     *         run: function(component) {
     *             // do something to make the element compliant with
     *             // accessibility standards
     *         }
     *     }
     * })(SUGAR.App);
     * </code></pre>
     * @class SUGAR.accessibility
     * @singleton
     * @alias SUGAR.App.accessibility
     */
    app.augment('accessibility', {
        /**
         * Initializes the accessibility module.
         *
         * Overrides {@link Component#initialize} to execute all accessibility helpers
         * on the components.
         *
         * @param {App} app
         */
        init: function(app) {
            if (app.accessibility.helpers && !_.isEmpty(app.accessibility.helpers)) {
                app.view.Component.prototype.initialize = _.wrap(app.view.Component.prototype.initialize, function(func, options) {
                    func.call(this, options);
                    this.on('render', function() {
                        app.accessibility.run(this);
                    }, this);
                });
            }
        },

        /**
         * Applies accessibility helpers to an element to make the element
         * compliant with accessibility standards.
         *
         * @param {Component/jQuery} component
         * The element to test for accessibility compliance.
         *
         * @param {String/Array} [helper]
         * One or more names of specified helpers to run. All registered
         * helpers will be run if undefined.
         */
        run: function(component, helper) {
            var helpers = this.whichHelpers(helper);

            _.each(helpers, function(klass, name) {
                if (_.isFunction(klass.run)) {
                    app.logger.debug('Applying accessibility helper `' + name + '`');
                    klass.run(component);
                } else {
                    app.logger.debug('Unable to apply accessibility helper `' + name + '`');
                }
            });
        },

        /**
         * Determines which helpers are to be used according to the input.
         *
         * Filters out any named helpers that are not registered.
         *
         * @param {String/Array} [helper]
         * One or more names of specified helpers to run.
         *
         * @return {Array}
         * An array of helpers to run. All registered helpers are returned if
         * no helper names are provided as a parameter.
         */
        whichHelpers: function(helper) {
            var helpers;

            if (_.isUndefined(helper)) {
                return app.accessibility.helpers;
            }

            if (!_.isArray(helper)) {
                helper = [helper];
            }

            helpers = _.reduce(helper, function(memo, name) {
                if (!!app.accessibility.helpers[name]) {
                    memo[name] = app.accessibility.helpers[name];
                }

                return memo;
            }, {});

            return helpers;
        },

        /**
         * Generates a human-readable string for identifying an element.
         *
         * Primarily used for logging purposes, this method is useful for
         * debugging.
         *
         * @param {jQuery} $el The element for which the tag should be
         *   generated.
         *
         * @return {string} A string representing an element's tag, with all
         *   attributes. The element's selector, if one exists, is returned
         *   when a representation cannot be reasonably generated.
         */
        getElementTag: function($el) {
            var attributes,
                tagName;

            tagName = ($el.prop('tagName') || '').toLowerCase();
            attributes = [tagName];

            if ($el[0] && $el[0].attributes) {
                _.each($el[0].attributes, function(attr) {
                    attributes.push('[' + attr.name + '="' + attr.value + '"]');
                });
            } else if ($el.selector) {
                return $el.selector;
            }

            return attributes.join('');
        },

        /**
         * Allows for calling `click` event on element if
         * spacebar or enter keydown event is fired.
         *
         * @param {Event} evt The `keydown` event.
         * @param {jQuery} $el The element that should receive click event.
         */
        handleKeyClick: function(evt, $el) {
            var code = evt.which;
            // 13 = Return, 32 = Space
            if ((code === 13) || (code === 32)) {
                evt.preventDefault();
                evt.stopPropagation();
                // prevent key autorepeat from calling click
                if (evt.originalEvent && evt.originalEvent.repeat) {
                    return;
                }
                $el.click();
            }
        }
    }, false);
})(SUGAR.App);
