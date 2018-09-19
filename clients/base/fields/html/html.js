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
 * @class View.Fields.Base.HtmlField
 * @alias SUGAR.App.view.fields.BaseHtmlField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldSelector: '.htmlareafield', //iframe selector

    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @inheritdoc
     *
     * The html area is always a readonly field.
     * (see htmleditable for an editable html field)
     */
    initialize: function(options) {
        options.def.readonly = true;
        this._super('initialize', [options]);
    },

    /**
     * @inheritdoc
     *
     * Set the name of the field on the iframe as well as the contents
     *
     * @private
     */
    _render: function() {
        app.view.Field.prototype._render.call(this);

        this._getFieldElement().attr('name', this.name);
        this.setViewContent();
    },

    /**
     * Sets read only html content in the iframe
     */
    setViewContent: function(){
        var value = this.value || this.def.default_value;
        var field = this._getFieldElement();
        if(field && field.get(0) && !_.isEmpty(field.get(0).contentDocument)) {
            if(field.contents().find('body').length > 0){
                field.contents().find('body').html(value);
            }
        }
    },

    /**
     * Finds iframe element in the field template
     *
     * @return {HTMLElement} element from field template
     * @private
     */
    _getFieldElement: function() {
        return this.$el.find(this.fieldSelector);
    }

})
