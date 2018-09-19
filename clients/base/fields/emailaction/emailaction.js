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
 * EmailactionField is a button that when selected will launch the appropriate
 * email client.
 *
 * @class View.Fields.Base.EmailactionField
 * @alias SUGAR.App.view.fields.BaseEmailactionField
 * @extends View.Fields.Base.ButtonField
 */
({
    extendsFrom: 'ButtonField',

    /**
     * @inheritdoc
     *
     * Adds the EmailClientLaunch plugin to enable the field to be used for
     * sending email.
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['EmailClientLaunch']);
        this._super('initialize', [options]);
    },

    /**
     * Set up email options, listening for parent model changes to update the
     * email options on change.
     *
     * @private
     * @deprecated The EmailClientLaunch plugin handles email options.
     */
    _initEmailOptions: function() {
        app.logger.warn('View.Fields.Base.EmailactionField#_initEmailOptions is deprecated. ' +
            'The EmailClientLaunch plugin handles email options.');
    },

    /**
     * Update email options based on field def settings
     *
     * @param {Object} parentModel
     * @private
     * @deprecated The EmailClientLaunch plugin handles email options.
     */
    _updateEmailOptions: function(parentModel) {
        app.logger.warn('View.Fields.Base.EmailactionField#_updateEmailOptions is deprecated. ' +
            'The EmailClientLaunch plugin handles email options.');
    }
})
