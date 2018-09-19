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
 * @class View.Fields.Base.LabelField
 * @alias SUGAR.App.view.fields.BaseLabelField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @override
     *
     * No-op function because this field is static.
     */
    bindDataChange: $.noop,

    /**
     * @override
     *
     * No-op function because this field is static.
     */
    bindDomChange: $.noop,

    /**
     * @override
     *
     * No-op function because this field is static.
     */
    unbindDom: $.noop,

    /**
     * @inheritdoc
     */
    format: function(value) {
        if (this.def.formatted_value) {
            value = this.def.formatted_value;
        } else {
            value = app.lang.get(this.def.default_value, this.module);
        }
        return value;
    }
})
