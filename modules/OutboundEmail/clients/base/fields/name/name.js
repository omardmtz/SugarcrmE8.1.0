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
 * @class View.Fields.Base.OutboundEmail.NameField
 * @alias SUGAR.App.view.fields.BaseOutboundEmailNameField
 * @extends View.Fields.Base.NameField
 */
({
    extendsFrom: 'BaseNameField',

    /**
     * Adds help text (LBL_SYSTEM_ACCOUNT) for the system account. Be aware
     * that this will replace any help text that is defined in metadata.
     *
     * @inheritdoc
     */
    _render: function() {
        if (this.model.get('type') === 'system') {
            this.def.help = 'LBL_SYSTEM_ACCOUNT';
        }

        return this._super('_render');
    }
})
