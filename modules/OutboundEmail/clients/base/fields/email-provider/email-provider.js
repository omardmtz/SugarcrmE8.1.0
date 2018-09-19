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
 * @class View.Fields.Base.OutboundEmail.EmailProviderField
 * @alias SUGAR.App.view.fields.BaseOutboundEmailEmailProviderField
 * @extends View.Fields.Base.RadioenumField
 */
({
    extendsFrom: 'RadioenumField',

    /**
     * Falls back to the detail template when attempting to load the disabled
     * template.
     *
     * @inheritdoc
     */
    _getFallbackTemplate: function(viewName) {
        // Don't just return "detail". In the event that "nodata" or another
        // template should be the fallback for "detail", then we want to allow
        // the parent method to determine that as it always has.
        if (viewName === 'disabled') {
            viewName = 'detail';
        }

        return this._super('_getFallbackTemplate', [viewName]);
    }
})
