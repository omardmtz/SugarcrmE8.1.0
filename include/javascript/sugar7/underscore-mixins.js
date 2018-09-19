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

        _.mixin({
            /**
             * Move an item to a specific index.
             *
             * @param {Array} initialArray The initial array.
             * @param {number} fromIndex The index of the item to move.
             * @param {number} toIndex The index where the item is moved.
             * @return {Array} The array reordered.
             */
            moveIndex: function(array, fromIndex, toIndex) {
                // Remove the item, and add it back to its new position.
                array.splice(toIndex, 0, _.first(array.splice(fromIndex, 1)));
                return array;
            }
        });

    });
})(SUGAR.App);
