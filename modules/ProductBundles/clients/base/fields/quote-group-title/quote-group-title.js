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
 * @class View.Fields.Base.ProductBundles.QuoteGroupTitleField
 * @alias SUGAR.App.view.fields.BaseProductBundlesQuoteGroupTitleField
 * @extends View.Fields.Base.Field
 */
({
    /**
     * Any additional CSS classes that need to be applied to the field
     */
    css_class: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.css_class = options.def.css_class || '';
        this._super('initialize', [options]);
    }
})
