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
 * @class View.Fields.Base.PiinameField
 * @alias SUGAR.App.view.fields.BasePiinameField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * Convert the raw field type name into the label of the field
     * of the Pii module or Pii parent module; if not available,
     * use raw value.
     */
    format: function(value) {
        var module;
        var field;

        if (!this.context) {
            return value;
        }

        if (this.context.has('piiModule')) {
            module = this.context.get('piiModule');
            field = app.metadata.getField({module: module, name: value});
        } else if (this.context.parent) {
            var model = this.context.parent.get('model');
            module = model.module;
            field = model.fields[value];
        }

        if (field) {
            value = app.lang.get(field.label || field.vname, module);
        }

        return value;
    }
})
