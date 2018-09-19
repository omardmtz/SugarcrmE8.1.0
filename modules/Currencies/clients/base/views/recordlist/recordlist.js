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
 * Currencies Record List.
 *
 * @class View.Views.Base.Currencies.RecordlistView
 * @alias SUGAR.App.view.views.BaseCurrenciesRecordlistView
 * @extends View.Views.Base.RecordlistView
 */
({
    extendsFrom: 'RecordlistView',

    /**
     * @inheritdoc
     **/
    bindDataChange: function() {
        this.collection.on('data:sync:complete', function() {
            this.collection.each(function(model) {
                if (model.get('id') == app.currency.getBaseCurrencyId()) {
                    model.isDefault = true;
                    var defaultLang = app.lang.get('LBL_CURRENCY_DEFAULT', 'Currencies');
                    if (defaultLang) {
                        model.set('name', defaultLang);
                    }
                }
            }, this);

            this.render();
        }, this);

        // call the parent
        this._super('bindDataChange');
    },

    /**
     * @inheritdoc
     **/
    _render: function() {
        this._super('_render');

        var $tableRow = this.$('tr[name="Currencies_-99"]');
        var $rowCheckBox = $tableRow.find('input[name="check"]');
        var $rowActionDropdown = $tableRow.find('a.dropdown-toggle');
        var $defaultCurrencyLabel = $tableRow.find('[data-type="name"] div.ellipsis_inline');

        // Add the default currency class to the default currency row
        if ($defaultCurrencyLabel.length) {
            $defaultCurrencyLabel.addClass('defaultCurrencyLabel');
        }

        // disable the checkbox
        if ($rowCheckBox.length) {
            $rowCheckBox.prop('disabled', true);
        }

        // remove actions
        if ($rowActionDropdown.length) {
            $rowActionDropdown.remove();
        }
    }
})
