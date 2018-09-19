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
 * @class View.Fields.Base.StatusField
 * @alias SUGAR.App.view.fields.BaseStatusField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * Additional CSS Classes to be added to hbs
     */
    cssClasses: '',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.buildCSSClasses();
    },

    /**
     * Gets the field value and sets cssClasses
     */
    buildCSSClasses: function() {
        var status = this.model.get(this.name);
        if (status) {
            status = status.replace(' ', '_');
            this.cssClasses = 'field_' + this.name + '_' + status;
        }
    }
})
