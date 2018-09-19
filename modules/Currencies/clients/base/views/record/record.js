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
    extendsFrom: 'RecordView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._checkIfBaseCurrency(options);
        this._super('initialize', [options]);
    },

    /**
     * Checks to see if the currency is the base currency
     * @param options
     * @private
     */
    _checkIfBaseCurrency: function(options) {
        if (options.context.get('modelId') == app.currency.getBaseCurrencyId()) {
            var mainDropdownBtn = this._findButton(options.meta.buttons, 'main_dropdown');

            //disable edit
            if (mainDropdownBtn) {
                // disable the edit button
                var editBtn = this._findButton(mainDropdownBtn.buttons, 'edit_button');
                if (editBtn) {
                    editBtn.css_class = editBtn.css_class || '';
                    editBtn.css_class += ' disabled';
                }
            }
            //set fields to read only.
            _.each(options.meta.panels, function(panel) {
                _.each(panel.fields, function(field) {
                    field.readonly = true;
                }, this);
            }, this);
        }
    },

    /**
     * Finds buttons of a given type
     *
     * @param buttons
     * @param name
     * @return {*}
     * @private
     */
    _findButton: function(buttons, name) {
        return _.find(buttons, function(btn) {
            return btn.name === name;
        });
    }

})
