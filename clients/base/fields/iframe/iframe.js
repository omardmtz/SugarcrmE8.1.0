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
 * @class View.Fields.Base.IframeField
 * @alias SUGAR.App.view.fields.BaseIframeField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        if (this.tplName === 'disabled') {
            this.$(this.fieldTag).attr('disabled', 'disabled');
        }
    },

    /**
     * @inheritdoc
     */
    unformat: function(value) {
        value = (value !== '' && value != 'http://') ? value.trim() : '';
        return value;
    },

    /**
     * @inheritdoc
     *
     * Formatter for the iframe field. If the iframe field definition is
     * configured with a generated url (`this.def.gen`) by another field, those
     * field values (defined in curly braces) are parsed from the model and set
     * on the value to be returned. Finally, if the value doesn't contain
     * 'http://' or 'https://', it is prepended on the value before being
     * returned.
     *
     * @param {String} value The value set on the iframe field.
     * @return {String} The formatted iframe value.
     */
    format: function(value) {
        if (_.isEmpty(value)) {
            // Name conflict with iframe's default value def and the list view's
            // default column flag
            value = _.isString(this.def['default']) ? this.def['default'] : undefined;
        }

        if (this.def.gen == '1') {
            var regex = /{(.+?)}/,
                result = null;
            do {
                result = regex.exec(value);
                if (result) {
                    value = value.replace(result[0], this.model.get(result[1]));
                }
            } while (result);
        }

        if (_.isString(value) && !value.match(/^(http|https):\/\//)) {
            value = 'http://' + value.trim();
        }
        return value;
    }
})
