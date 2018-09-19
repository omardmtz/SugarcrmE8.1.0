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

({
    extendsFrom: 'ActionmenuField',

    /**
     * Binds mass collection events to a record row checkbox.
     *
     * @private
     */
    _bindModelChangeEvents: function() {
        this._super('_bindModelChangeEvents');

        this.massCollection.on('reset', function() {
            // force any disabled field to be unchecked
            var field = this.$(this.fieldTag);
            if (field.prop('disabled')) {
                field.attr('checked', false);
            }
        }, this);
    },

    /**
     * @inheritdoc
     **/
    _onMassCollectionRemoveResetAll: function() {
        // if default currency exists in collection, remove it
        _.each(this.massCollection.models, function(model, index) {
            if (model.id === '-99') {
                this.massCollection.remove(this.massCollection.models[index], {silent: true});
            }
        }, this);

        // force entire property to allow the selected row count alert to display
        if (this.massCollection.length > 0) {
            this.massCollection.entire = true;
        } else {
            this.massCollection.entire = false;
        }

        this._super('_onMassCollectionRemoveResetAll');
    },
})
