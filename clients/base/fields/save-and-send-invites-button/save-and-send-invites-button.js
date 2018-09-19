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
 * SaveAndSendInvitesButtonField field is a field for Meetings/Calls that handles setting the flag for sending emails to guests
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.SaveAndSendInvitesButtonField
 * @alias SUGAR.App.view.fields.BaseSaveAndSendInvitesButtonField
 * @extends View.Fields.Base.RowactionField
 *
 */
({
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     *
     * Sets the type to "rowaction" so that the templates are loaded from
     * super.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'rowaction';
    },

    /**
     * Setting model event to allow unsetting of send_invites after validation error or data sync completed.
     * @inheritdoc
     */
    bindDataChange: function() {
        if (!this.model) {
            return;
        }

        this.model.on('error:validation data:sync:complete', function() {
            this.model.unset('send_invites');
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Silently sets `send_invites` to true on the model before saving.
     */
    rowActionSelect: function(event) {
        this.model.set('send_invites', true, {silent: true});
        this._super('rowActionSelect', [event]);
    }
})
