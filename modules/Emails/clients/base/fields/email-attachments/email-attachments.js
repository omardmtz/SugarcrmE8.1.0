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
 * @class View.Fields.Base.Emails.EmailAttachmentsField
 * @alias SUGAR.App.view.fields.BaseEmailsEmailAttachmentsField
 * @extends View.Fields.Base.EmailAttachmentsField
 */
({
    extendsFrom: 'BaseEmailAttachmentsField',

    /**
     * @inheritdoc
     *
     * Adds a listener for the `email_attachments:template` event, which is
     * triggered on the view to add attachments. The handler will fetch the
     * attachments from a template, so that they can be copied to the email.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.listenTo(this.view, 'email_attachments:template', this._fetchTemplateAttachments);
    },

    /**
     * Retrieves all of an email template's attachments so they can be added to
     * the email.
     *
     * @param {Data.Bean} template The email template whose attachments are to
     * be added.
     * @private
     */
    _fetchTemplateAttachments: function(template) {
        var def;
        var notes = app.data.createBeanCollection('Notes');
        var request;

        if (this.disposed === true) {
            return;
        }

        def = [{
            //FIXME: email_type should be EmailTemplates
            email_id: {
                '$equals': template.get('id')
            }
        }];
        request = notes.fetch({
            filter: {
                filter: def
            },
            success: _.bind(this._handleTemplateAttachmentsFetchSuccess, this),
            complete: _.bind(function(request) {
                if (request && request.uid) {
                    delete this._requests[request.uid];
                }
            }, this)
        });

        // This request is not associated with a placeholder because
        // placeholders aren't used when handling templates.
        if (request && request.uid) {
            this._requests[request.uid] = request;
        }
    },

    /**
     * Handles a successful response from the API for retrieving an email
     * template's attachments.
     *
     * The relevant data is taken from each record and added as an attachment.
     * Before adding the new attachments, all existing attachments that came
     * from another email template are removed.
     *
     * @param {Data.BeanCollection} notes The collection of attachments from
     * the template.
     * @private
     */
    _handleTemplateAttachmentsFetchSuccess: function(notes) {
        var attachments;
        var existingTemplateAttachments;
        var newTemplateAttachments;

        if (this.disposed === true) {
            return;
        }

        // Remove all existing attachments that came from an email template.
        attachments = this.model.get(this.name);
        existingTemplateAttachments = attachments.where({file_source: 'EmailTemplates'});
        attachments.remove(existingTemplateAttachments);

        // Add the attachments from the new email template.
        newTemplateAttachments = notes.map(function(model) {
            return {
                _link: 'attachments',
                upload_id: model.get('id'),
                name: model.get('filename') || model.get('name'),
                filename: model.get('filename') || model.get('name'),
                file_mime_type: model.get('file_mime_type'),
                file_size: model.get('file_size'),
                file_ext: model.get('file_ext'),
                file_source: 'EmailTemplates'
            };
        });
        attachments.add(newTemplateAttachments, {merge: true});
    }
})
