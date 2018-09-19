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
    // make sure the accessibility module is set up
    app.accessibility = app.accessibility || {};
    app.accessibility.helpers = app.accessibility.helpers || {};

    /**
     * The Label Accessibility Helper is responsible for making an element
     * compliant with accessibility standards by adding an aria-label to
     * all field elements.
     *
     * This approach is 'best effort' and will only catch fields that match
     * the fieldTag selector on the sidecar field controller.
     */
    app.accessibility.helpers.label = {
        /**
         * Detects if component already has aria-label set and adds if not
         *
         * @param {Component} component
         * The element to test for accessibility compliance.
         */
        run: function(component) {
            var self = this;

            // only need to do this for fields
            if (!(component instanceof app.view.Field)) {
                return;
            }

            if (component.label && component.fieldTag) {
                component.$el.find(component.fieldTag).each(function() {
                    self._makeElementCompliant($(this), component.label);
                });
            }
        },

        /**
         * Determines whether or not an element requires any additional
         * accessibility compliance.
         *
         * Compliant if either of the following applies:
         * - tag is not one that requires label
         * - label already set
         *
         * @param {jQuery} $el The element to test for accessibility compliance.
         * @return {Boolean}
         *
         * @private
         */
        _isElementCompliant: function($el) {
            var ariaLabel,
                tag;

            ariaLabel = $el.attr('aria-label');
            tag = $el.prop('tagName').toLowerCase();

            return (!_.contains(['input', 'button', 'select', 'textarea'], tag) || !_.isUndefined(ariaLabel));
        },

        /**
         * Adds aria-label if element is not already compliant
         *
         * @param {jQuery} $el The element to make compliant.
         * @param {string} label The label to add as an aria-label
         *
         * @private
         */
        _makeElementCompliant: function($el, label) {
            if (!this._isElementCompliant($el)) {
                $el.attr('aria-label', label);
            }
        }
    };
})(SUGAR.App);
