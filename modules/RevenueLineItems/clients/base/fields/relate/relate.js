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
 * @class View.Fields.Base.RevenueLineItems.RelateField
 * @alias SUGAR.App.view.fields.BaseRevenueLineItemsRelateField
 * @extends View.Fields.Base.RelateField
 */
({
    extendsFrom: 'BaseRelateField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // deleting for RLI create when there is no account_id.
        if (options && options.def.filter_relate && !options.model.has('account_id')) {
            delete options.def.filter_relate;
        }

        this._super('initialize', [options]);
    }
})
