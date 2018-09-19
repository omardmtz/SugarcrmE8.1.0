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
 * @class View.Fields.Base.ProductBundles.QuoteDataActiondropdownField
 * @alias SUGAR.App.view.fields.BaseProductBundlesQuoteDataActiondropdownField
 * @extends View.Fields.Base.ActiondropdownField
 */
({
    /**
     * @inheritdoc
     */
    extendsFrom: 'BaseActiondropdownField',

    /**
     * @inheritdoc
     */
    className: 'quote-data-actiondropdown',

    /**
     * Skipping ActionmenuField's override, just returning this.def.buttons
     *
     * @inheritdoc
     */
    _getChildFieldsMeta: function() {
        return app.utils.deepCopy(this.def.buttons);
    },

    /**
     * Overriding for quote-data-group-header in create view to display a specific template
     *
     * @inheritdoc
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        if (this.view.name === 'quote-data-group-header' && this.view.isCreateView) {
            this.template = app.template.getField('quote-data-actiondropdown', 'list', this.model.module);
        }
    }
})
