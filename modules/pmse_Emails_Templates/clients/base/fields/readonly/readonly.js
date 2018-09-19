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
 * @class View.Fields.Base.pmse_Emails_Templates.ReadonlyField
 * @alias SUGAR.App.view.fields.Basepmse_Emails_TemplatesReadonlyField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input.inherit-width',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.def.readonly = true;
        this._super('initialize', [options]);
    },
    
    _render: function() {
        if (this.view.name === 'record') {
            this.def.link = false;
        } else if (this.view.name === 'preview') {
            this.def.link = true;
        }
        this._super('_render');
    },

    /**
     * Gets the recipients DOM field
     *
     * @returns {Object} DOM Element
     */
    getFieldElement: function() {
        return this.$(this.fieldTag);
    },

    /**
     * @inheritdoc
     */
    format: function(value) {
        return app.lang.getModuleName(value, {plural: true})
    }
})
