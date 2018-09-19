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
 * @class View.Fields.Base.Audit.CurrencyField
 * @alias SUGAR.App.view.fields.BaseAuditCurrencyField
 * @extends View.Fields.Base.CurrencyField
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        //audit log is always in base currency. Make sure the currency def reflects that.
        this.def.is_base_currency = true;
    }
})
