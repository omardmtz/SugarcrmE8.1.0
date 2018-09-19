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
 * @class View.Views.Base.DataPrivacy.RecordView
 * @alias SUGAR.App.view.views.BaseDataPrivacyRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',

    /**
     * @inheritdoc
     *
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.context.on('button:erase_complete_button:click', this.showConfirmEraseAlert, this);
        this.context.on('button:reject_button:click', this.showRejectEraseAlert, this);
        this.context.on('button:complete_button:click', this.showConfirmCompleteAlert, this);
    },

    /**
     * Save status.
     *
     * @private
     */
    _setStatus: function(status) {
        this.model.set('status', status);
        this.handleSave();
    },

    /**
     * Calculates and returns the number of fields on all related records marked for erasure.
     *
     * @return {number} The number of records marked for erasure.
     * @private
     */
    _getNumberOfFieldsToErased: function() {
        var fieldsNumber = 0;
        var fieldsToErase = this.model.get('fields_to_erase');

        _.each(fieldsToErase, function(module) {
            fieldsNumber += _.reduce(module, function(memo, fields) {
                return memo + fields.length;
            }, 0);
        });

        return fieldsNumber;
    },

    /**
     * Displays a confirmation warning for erasing all field values for the fields marked for erasure.
     */
    showConfirmEraseAlert: function() {
        var self = this;
        var alertText = app.lang.get('LBL_WARNING_ERASE_CONFIRM', 'DataPrivacy');
        app.alert.show('confirm_complete:' + this.model.get('id'), {
            level: 'confirmation',
            messages: app.utils.formatString(alertText, [this._getNumberOfFieldsToErased()]),
            onConfirm: function() {
                self._setStatus('Closed');
            }
        });
    },

    /**
     * Displays a confirmation warning for closing the request.
     */
    showConfirmCompleteAlert: function() {
        var self = this;
        app.alert.show('confirm_erase_and_complete:' + this.model.get('id'), {
            level: 'confirmation',
            messages: app.lang.get('LBL_WARNING_COMPLETE_CONFIRM', 'DataPrivacy'),
            onConfirm: function() {
                self._setStatus('Closed');
            }
        });
    },

    /**
     * Displays a confirmation warning for rejecting the erasure of field values for all fields marked for erasure.
     */
    showRejectEraseAlert: function() {
        var self = this;
        var alertText;
        if (this.model.get('type') == 'Request to Erase Information') {
            alertText = app.utils.formatString(
                app.lang.get('LBL_WARNING_REJECT_ERASURE_CONFIRM', 'DataPrivacy'),
                [this._getNumberOfFieldsToErased()]
            );
        } else {
            alertText = app.lang.get('LBL_WARNING_REJECT_REQUEST_CONFIRM', 'DataPrivacy');
        }
        app.alert.show('confirm_reject_erase:' + this.model.get('id'), {
            level: 'confirmation',
            messages: alertText,
            onConfirm: function() {
                self._setStatus('Rejected');
            }
        });
    },

    /**
     * @inheritdoc
     *
     */
    bindDataChange: function() {
        this._super('bindDataChange');
        this.model.on('change', function() {
            if (!this.inlineEditMode &&
                this.action !== 'edit') {
                this.setButtonStates(this.STATE.VIEW);
            }
        }, this);
    },

    /**
     * @inheritdoc
     *
     */
    setButtonStates: function(state) {
        this._super('setButtonStates', [state]);
        this.setButtons(state);
    },

    /**
     * @inheritdoc
     *
     *  Depending on the type index, we either show or hide
     *  complete_button, erase_complete_button & reject_button
     */
    setButtons: function(state) {
        var open = (this.model.get('status') === 'Open');
        var erase = (this.model.get('type') === 'Request to Erase Information');
        if (state === this.STATE.VIEW && app.acl.hasAccess('admin', this.module)) {
            this.currentState = state;
            _.each(this.buttons, function(field) {
                if (this.shouldHide(open, erase, field)) {
                    field.hide();
                }
            }, this);
            this.toggleButtons(true);
        }
    },

    /**
     * @inheritdoc
     *
     * Check whether the button should be hidden
     */
    shouldHide: function(open, erase, field) {
        var DPActions = [
            'complete_button',
            'erase_complete_button',
            'reject_button'
        ];
        if ((!open && DPActions.indexOf(field.name) !== -1) ||
            (erase && field.name === 'complete_button') ||
            (!erase && field.name === 'erase_complete_button')) {
            return true;
        }
        return false;
    },
})
