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
 * Attachment button is a label that is styled like a button and will trigger a
 * given file input field.
 *
 * @class View.Fields.Base.Emails.AttachmentButtonField
 * @alias SUGAR.App.view.fields.BaseEmailsAttachmentButtonField
 * @extends View.Fields.Base.ButtonField
 * @deprecated Use {@link View.Fields.Base.Emails.EmailAttachmentsField}
 * instead.
 */
({
    extendsFrom: 'ButtonField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Fields.Base.Emails.AttachmentButtonField is deprecated. Use ' +
            'View.Fields.Base.Emails.EmailAttachmentsField instead.');

        this._super('initialize',[options]);
        this.fileInputId = this.context.get('attachment_field_email_attachment');
    }
})
