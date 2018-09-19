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
 * Actionbar for the email compose view
 *
 * @class View.Fields.Base.Emails.ComposeActionbarField
 * @alias SUGAR.App.view.fields.BaseEmailsComposeActionbarField
 * @extends View.Fields.Base.FieldsetField
 *
 * @deprecated Use {@link View.Fields.Base.Emails.Htmleditable_tinymceField}
 * instead to add buttons for email composition.
 */
({
    extendsFrom: 'FieldsetField',

    events: {
        'click a:not(.dropdown-toggle)': 'handleButtonClick'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Fields.Base.Emails.SenderField is deprecated. Use ' +
            'View.Fields.Base.Emails.Htmleditable_tinymceField instead.');

        this._super('initialize', [options]);
        this.type = 'fieldset';
    },

    /**
     * Fire an event when any of the buttons on the actionbar are clicked
     * Events could be set via the data-event attribute or an event is built using the button name
     *
     * @param evt
     */
    handleButtonClick: function(evt) {
        var triggerName, buttonName,
            $currentTarget = $(evt.currentTarget);
        if ($currentTarget.data('event')) {
            triggerName = $currentTarget.data('event');
        } else {
            buttonName = $currentTarget.attr('name') || 'button';
            triggerName = 'actionbar:' + buttonName + ':clicked';
        }
        this.view.context.trigger(triggerName);
    }
})
