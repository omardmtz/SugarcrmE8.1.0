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
 * @class View.Fields.Base.Quotes.QuoteFooterInputField
 * @alias SUGAR.App.view.fields.BaseQuotesQuoteFooterInputField
 * @extends View.Fields.Base.Field
 */
({
    /**
     * The value dollar amount
     */
    value_amount: undefined,

    /**
     * The value percent amount
     */
    value_percent: undefined,

    /**
     * @inheritdoc
     */
    format: function(value) {
        if (!value) {
            this.value_amount = app.currency.formatAmountLocale('0');
            this.value_percent = '0%';
        }
    }
})
