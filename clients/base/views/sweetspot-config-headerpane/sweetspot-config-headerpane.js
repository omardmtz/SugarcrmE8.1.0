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
 * @class View.Views.Base.SweetspotConfigHeaderpaneView
 * @alias SUGAR.App.view.views.BaseSweetspotConfigHeaderpaneView
 * @extends View.View
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._bindEvents();
    },

    /**
     * Binds events that this view uses.
     */
    _bindEvents: function() {
        this.context.on('sweetspot:config:enableButtons', this.toggleButtons, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this.registerShortcuts();
    },

    /**
     * Toggles the buttons in this view between enabled/disabled states.
     *
     * FIXME: This method should be updated to use `this.buttons` instead of
     * looping over all the fields. Update this when SC-3909 is merged.
     *
     * @param {boolean} [enable=false] Whether to enable or disable the action
     *   buttons. Defaults to `false`.
     */
    toggleButtons: function(enable) {
        var state = !_.isUndefined(enable) ? !enable : false;

        _.each(this.fields, function(field) {
            if (field instanceof app.view.fields.BaseButtonField) {
                field.setDisabled(state);
            }
        });
    },

    /**
     * Register keyboard shortcuts for various headerpane buttons.
     */
    registerShortcuts: function() {
        app.shortcuts.register({
            id: 'SweetSpot:Config:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_SAVE_CONFIG',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('a[name=save_button]');
                if ($saveButton.is(':visible') && !$saveButton.hasClass('disabled')) {
                    $saveButton.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'SweetSpot:Config:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('a[name=cancel_button]');
                if ($cancelButton.is(':visible') && !$cancelButton.hasClass('disabled')) {
                    $cancelButton.get(0).click();
                }
            }
        });
    }
})
