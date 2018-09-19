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
        var components = [];

        /**
         * Plugin is used to notify different about events happened in KB.
         */
        app.plugins.register('KBNotify', ['view', 'field'], {
            onAttach: function(component, plugin) {
                components.push(component);
            },

            /**
             * Method used to notify all components that using plugin.
             *
             * @param {string} name Event name
             * @param {Object} options Options passed with event
             */
            notifyAll: function(name, options) {
                _.each(components, function(component) {
                    component.trigger(name, options);
                });
            },

            onDetach: function() {
                components = _.without(components, this);
            }
        });
    });

})(SUGAR.App);
