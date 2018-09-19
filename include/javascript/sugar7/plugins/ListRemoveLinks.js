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
 * Remove any surrounding anchor tags from content displayed within the list view; leaving just the text. It is
 * undesirable to allow users to click links that navigate them away from the page when in the context of a modal
 */
(function(app) {
    app.events.on('app:init', function() {
        app.plugins.register('ListRemoveLinks', ['view'], {
            onAttach: function(component, plugin) {
                var removeLinks = function() {
                    component.$('a:not(.rowaction, .dropdown-toggle, .dropdown-menu *)').contents().unwrap();
                };

                component.on('render', removeLinks, null, component);
                app.events.on('list:preview:decorate', removeLinks, this);
            },
            onDetach: function() {
                app.events.off('list:preview:decorate', null, this);
            }
        });
    });
})(SUGAR.App);
