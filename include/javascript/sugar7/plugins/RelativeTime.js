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

    if (!$.fn.liverelativedate) {
        return;
    }

    app.events.on('app:init', function() {

        /**
         * RelativeTime plugin keeps your relative time placeholders updated to
         * the minute.
         *
         * It uses `sugar.liverelativedate.js` jQuery plugin to keep all your
         * built relative time labels by the Handlebars helper
         * {@link Handlebars.helpers#relativeTime} updated.
         */
        app.plugins.register('RelativeTime', ['view', 'field'], {

            /**
             * When attaching the plugin, listen to the render event and make
             * all `[datatime]` elements refreshed live.
             *
             * @param {View.Component} component The component this plugin will
             *   be attached to.
             */
            onAttach: function(component) {
                component.on('render', function() {
                    component.$('[datetime]').liverelativedate();
                });
            },

            /**
             * Confirm that on render the existing live dates are detached
             * before we render again.
             *
             * We don't use `before('render')` because other components might
             * return false and block the render to be triggered, and would
             * make this plugin not working properly until next unblocked
             * render.
             *
             * @protected
             */
            _render: function() {
                this.$('[datetime]').liverelativedate('destroy');
                Object.getPrototypeOf(this)._render.call(this);
            },

            /**
             * When detaching the plugin, make sure all previous elements will
             * be removed from the liverelativetime list to be updated.
             *
             * @param {View.Component} component The component this plugin is
             *   attached to.
             */
            onDetach: function(component) {
                component.$('[datetime]').liverelativedate('destroy');
            }
        });
    });
})(SUGAR.App);
