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
 * @class View.Fields.Base.Quotes.DateField
 * @alias SUGAR.App.view.fields.BaseQuotesDateField
 * @extends View.Fields.Base.DateField
 */
({
    extendsFrom: 'DateField',

    /**
     * @inheritdoc
     */
    _dispose: function() {
        // FIXME: this is a bad "fix" added -- when SC-2395 gets done to upgrade bootstrap we need to remove this
        if (this._hasDatePicker && this.$(this.fieldTag).data('datepicker')) {
            $(window).off('resize', this.$(this.fieldTag).data('datepicker').place);
        }
        this._hasDatePicker = false;

        this._super('_dispose');
    }
})
