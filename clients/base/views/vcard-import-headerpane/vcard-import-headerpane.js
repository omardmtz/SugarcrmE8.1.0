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
 * @class View.Views.Base.VcardImportHeaderpaneView
 * @alias SUGAR.App.view.views.BaseVcardImportHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    events: {
        'click [name=vcard_cancel_button]': 'initiateCancel'
    },

    /**
     * Add listener for toggling the disabled state of the finish button
     *
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.context.on('vcard:import-finish-button:toggle', this._toggleFinishButton, this);
    },

    /**
     * Toggle the state of the finish button (enabled/disabled)
     *
     * @param {boolean} enabled Whether the button should be enabled
     * @private
     */
    _toggleFinishButton: function(enabled) {
        this.getField('vcard_finish_button').setDisabled(!enabled);
    },

    /**
     * Handle cancel action - closing the drawer
     */
    initiateCancel: function() {
        app.drawer.close();
    }
})
