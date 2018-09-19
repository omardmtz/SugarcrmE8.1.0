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
(function (app) {
    app.events.on('app:init', function () {
        /**
         * Get the list of shortcuts that is allowed in this session.
         *
         * @param {View.Layout} layout
         * @returns {Array}
         */
        var getShortcutList = function(layout) {
            return layout.options.meta.shortcuts || layout.shortcuts;
        };

        app.plugins.register('ShortcutSession', ['layout'], {
            /**
             * Create new shortcut session.
             */
            onAttach: function() {
                var shortcutList = getShortcutList(this);
                if (!_.isEmpty(shortcutList)) {
                    app.shortcuts.createSession(shortcutList, this);
                }
            }
        });
    });
})(SUGAR.App);
