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
 * @class View.Fields.Base.InvitationActionsField
 * @alias SUGAR.App.view.fields.BaseInvitationActionsField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click [data-action]': 'toggleStatus'
    },

    /**
     * Toggle invitation acceptance status.
     *
     * This will fire the save automatically to the server since it is a toggle
     * button and won't make sense to do save from the view (same as favorite).
     *
     * @param {Event} evt The click event that triggered the change.
     */
    toggleStatus: function(evt) {
        var attr = {};

        attr[this.name] = $(evt.currentTarget).data('action');

        this.model.save(attr, {relate: true});
    }
})
