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
 * @class View.Views.Base.Emails.ComposeView
 * @alias SUGAR.App.view.views.BaseEmailsComposeView
 * @extends View.Views.Base.RecordView
 * @deprecated Use {@link View.Views.Base.Emails.ComposeEmailView} instead.
 */
({
    extendsFrom: 'RecordView',

    _lastSelectedSignature: null,
    ATTACH_TYPE_SUGAR_DOCUMENT: 'document',
    ATTACH_TYPE_TEMPLATE: 'template',
    MIN_EDITOR_HEIGHT: 300,
    EDITOR_RESIZE_PADDING: 5,
    ATTACHMENT_FIELD_HEIGHT: 44,
    FIELD_PANEL_BODY_SELECTOR: '.row-fluid.panel_body',

    sendButtonName: 'send_button',
    cancelButtonName: 'cancel_button',
    saveAsDraftButtonName: 'draft_button',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Views.Base.Emails.ComposeView is deprecated. Use ' +
            'View.Views.Base.Emails.ComposeEmailView instead.');

        this._super('initialize', [options]);
        this.events = _.extend({}, this.events, {
            'click [data-toggle-field]': '_handleSenderOptionClick'
        });
        this.context.on('email_attachments:file', this.launchFilePicker, this);
        this.context.on('email_attachments:document', this.documentDrawerCallback, this);
        this.context.on('attachments:updated', this.toggleAttachmentVisibility, this);
        this.context.on('tinymce:oninit', this.handleTinyMceInit, this);
        this.on('more-less:toggled', this.handleMoreLessToggled, this);
        app.drawer.on('drawer:resize', this.resizeEditor, this);

        this._lastSelectedSignature = app.user.getPreference('signature_default');
    },

    /**
     * @inheritdoc
     */
    delegateButtonEvents: function() {
        this.context.on('button:' + this.sendButtonName + ':click', this.send, this);
        this.context.on('button:' + this.saveAsDraftButtonName + ':click', this.saveAsDraft, this);
        this.context.on('button:' + this.cancelButtonName + ':click', this.cancel, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var prepopulateValues;
        var $controls;

        this._super('_render');

        $controls = this.$('.control-group:not(.hide) .control-label');
        if ($controls.length) {
            $controls.first().addClass('begin-fieldgroup');
            $controls.last().addClass('end-fieldgroup');
        }

        this.setTitle(app.lang.get('LBL_COMPOSEEMAIL', this.module));

        prepopulateValues = this.context.get('prepopulate');
        if (!_.isEmpty(prepopulateValues)) {
            this.prepopulate(prepopulateValues);
        }
        this.addSenderOptions();

        this.notifyConfigurationStatus();
    },

    /**
     * Notifies the user of configuration issues and disables send button
     */
    notifyConfigurationStatus: function() {
        var sendButton,
            emailClientPrefence = app.user.getPreference('email_client_preference');

        if (_.isObject(emailClientPrefence) && _.isObject(emailClientPrefence.error)) {
            app.alert.show('email-client-status', {
                level: 'warning',
                messages: app.lang.get(emailClientPrefence.error.message, this.module),
                autoClose: false,
                onLinkClick: function() {
                    app.alert.dismiss('email-client-status');
                }
            });

            sendButton = this.getField('send_button');
            if (sendButton) {
                sendButton.setDisabled(true);
            }
        }
    },

    /**
     * Prepopulate fields on the email compose screen that are passed in on the context when opening this view
     * TODO: Refactor once we have custom module specific models
     * @param {Object} values
     */
    prepopulate: function(values) {
        var self = this;
        _.defer(function() {
            _.each(values, function(value, fieldName) {
                switch (fieldName) {
                    case 'related':
                        self._populateForModules(value);
                        self.populateRelated(value);
                        break;
                    default:
                        self.model.set(fieldName, value);
                }
            });
        });
    },

    /**
     * Populates email compose with module specific data.
     * TODO: Refactor once we have custom module specific models
     * @param {Data.Bean} relatedModel
     */
    _populateForModules: function(relatedModel) {
        if (relatedModel.module === 'Cases') {
            this._populateForCases(relatedModel);
        }
    },


    /**
     * Populates email compose with cases specific data.
     * TODO: Refactor once we have custom module specific models
     * @param {Data.Bean} relatedModel
     */
    _populateForCases: function(relatedModel) {
        var config = app.metadata.getConfig(),
            keyMacro = '%1',
            caseMacro = config.inboundEmailCaseSubjectMacro,
            subject = caseMacro + ' ' + relatedModel.get('name');

        subject = subject.replace(keyMacro, relatedModel.get('case_number'));
        this.model.set('name', subject);
        if (!this.isFieldPopulated('to_addresses')) {
            // no addresses, attempt to populate from contacts relationship
            var contacts = relatedModel.getRelatedCollection('contacts');

            contacts.fetch({
                relate: true,
                success: _.bind(function(data) {
                    var toAddresses = _.map(data.models, function(model) {
                        return {bean: model};
                    }, this);

                    this.model.set('to_addresses', toAddresses);
                }, this),
                fields: ['id', 'full_name', 'email']
            });
        }
    },

    /**
     * Populate the parent_name (type: parent) with the related record passed in
     *
     * @param {Data.Bean} relatedModel
     */
    populateRelated: function(relatedModel) {
        var setParent = _.bind(function(model) {
            var parentNameField = this.getField('parent_name');
            if (model.module && parentNameField.isAvailableParentType(model.module)) {
                model.value = model.get('name');
                parentNameField.setValue(model);
            }
        }, this);

        if (!_.isEmpty(relatedModel.get('id')) && !_.isEmpty(relatedModel.get('name'))) {
            setParent(relatedModel);
        } else if (!_.isEmpty(relatedModel.get('id'))) {
            relatedModel.fetch({
                showAlerts: false,
                success: _.bind(function(relatedModel) {
                    setParent(relatedModel);
                }, this),
                fields: ['name']
            });
        }
    },

    /**
     * Enable/disable the page action dropdown menu based on whether email is sendable
     * @param {boolean} disabled
     */
    setMainButtonsDisabled: function(disabled) {
        this.getField('main_dropdown').setDisabled(disabled);
    },

    /**
     * Add Cc/Bcc toggle buttons
     * Initialize whether to show/hide fields and toggle show/hide buttons appropriately
     */
    addSenderOptions: function() {
        this._renderSenderOptions('to_addresses');
        this._initSenderOption('cc_addresses');
        this._initSenderOption('bcc_addresses');
    },

    /**
     * Render the sender option buttons and place them in the given container
     *
     * @param {string} container Name of field that will contain the sender option buttons
     * @private
     */
    _renderSenderOptions: function(container) {
        var field = this.getField(container),
            $panelBody,
            senderOptionTemplate;

        if (field) {
            $panelBody = field.$el.closest(this.FIELD_PANEL_BODY_SELECTOR);
            senderOptionTemplate = app.template.getView('compose-senderoptions', this.module);

            $(senderOptionTemplate({'module' : this.module}))
                .insertAfter($panelBody.find('div span.normal'));
        }
    },

    /**
     * Check if the given field has a value
     * Hide the field if there is no value prepopulated
     *
     * @param {string} fieldName Name of the field to initialize active state on
     * @private
     */
    _initSenderOption: function(fieldName) {
        var fieldValue = this.model.get(fieldName) || [];
        this.toggleSenderOption(fieldName, (fieldValue.length > 0));
    },

    /**
     * Toggle the state of the given field
     * Sets toggle button state and visibility of the field
     *
     * @param {string} fieldName Name of the field to toggle
     * @param {boolean} [active] Whether toggle button active and field shown
     */
    toggleSenderOption: function(fieldName, active) {
        var toggleButtonSelector = '[data-toggle-field="' + fieldName + '"]',
            $toggleButton = this.$(toggleButtonSelector);

        // if explicit active state not set, toggle to opposite
        if (_.isUndefined(active)) {
            active = !$toggleButton.hasClass('active');
        }

        $toggleButton.toggleClass('active', active);
        this._toggleFieldVisibility(fieldName, active);
    },

    /**
     * Event Handler for toggling the Cc/Bcc options on the page.
     *
     * @param {Event} event click event
     * @private
     */
    _handleSenderOptionClick: function(event) {
        var $toggleButton = $(event.currentTarget),
            fieldName = $toggleButton.data('toggle-field');

        this.toggleSenderOption(fieldName);
        this.resizeEditor();
    },

    /**
     * Show/hide a field section on the form
     *
     * @param {string} fieldName Name of the field to show/hide
     * @param {boolean} show Whether to show or hide the field
     * @private
     */
    _toggleFieldVisibility: function(fieldName, show) {
        var field = this.getField(fieldName);
        if (field) {
            field.$el.closest(this.FIELD_PANEL_BODY_SELECTOR).toggleClass('hide', !show);
        }
    },

    /**
     * Cancel and close the drawer
     */
    cancel: function() {
        app.drawer.close();
    },

    /**
     * Get the attachments from the model and format for the API
     *
     * @return {Array} array of attachments or empty array if none found
     */
    getAttachmentsForApi: function() {
        var attachments = this.model.get('attachments') || [];

        if (!_.isArray(attachments)) {
            attachments = [attachments];
        }

        return attachments;
    },

    /**
     * Get the individual related object fields from the model and format for the API
     *
     * @return {Object} API related argument as array with appropriate fields set
     */
    getRelatedForApi: function() {
        var related = {};
        var id = this.model.get('parent_id');
        var type;

        if (!_.isUndefined(id)) {
            id = id.toString();
            if (id.length > 0) {
                related['id'] = id;
                type = this.model.get('parent_type');
                if (!_.isUndefined(type)) {
                    type = type.toString();
                }
                related.type = type;
            }
        }

        return related;
    },

    /**
     * Get the team information from the model and format for the API
     *
     * @return {Object} API teams argument as array with appropriate fields set
     */
    getTeamsForApi: function() {
        var teamName = this.model.get('team_name') || [];
        var teams = {};
        teams.others = [];

        if (!_.isArray(teamName)) {
            teamName = [teamName];
        }

        _.each(teamName, function(team) {
            if (team.primary) {
                teams.primary = team.id.toString();
            } else if (!_.isUndefined(team.id)) {
                teams.others.push(team.id.toString());
            }
        }, this);

        if (teams.others.length == 0) {
            delete teams.others;
        }

        return teams;
    },

    /**
     * Build a backbone model that will be sent to the Mail API
     */
    initializeSendEmailModel: function() {
        var sendModel = new Backbone.Model(_.extend({}, this.model.attributes, {
            to_addresses: this.model.get('to_addresses'),
            cc_addresses: this.model.get('cc_addresses'),
            bcc_addresses: this.model.get('bcc_addresses'),
            subject: this.model.get('name'),
            html_body: this.model.get('description_html'),
            attachments: this.getAttachmentsForApi(),
            related: this.getRelatedForApi(),
            teams: this.getTeamsForApi()
        }));
        return sendModel;
    },

    /**
     * Save the email as a draft for later sending
     */
    saveAsDraft: function() {
        this.saveModel(
            'draft',
            app.lang.get('LBL_DRAFT_SAVING', this.module),
            app.lang.get('LBL_DRAFT_SAVED', this.module),
            app.lang.get('LBL_ERROR_SAVING_DRAFT', this.module)
        );
    },

    /**
     * Send the email immediately or warn if user did not provide subject or body
     */
    send: function() {
        var sendEmail = _.bind(function() {
            this.saveModel(
                'ready',
                app.lang.get('LBL_EMAIL_SENDING', this.module),
                app.lang.get('LBL_EMAIL_SENT', this.module),
                app.lang.get('LBL_ERROR_SENDING_EMAIL', this.module)
            );
        }, this);

        if (!this.isFieldPopulated('to_addresses') &&
            !this.isFieldPopulated('cc_addresses') &&
            !this.isFieldPopulated('bcc_addresses')
        ) {
            this.model.trigger('error:validation:to_addresses');
            app.alert.show('send_error', {
                level: 'error',
                messages: 'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS'
            });
        } else if (!this.isFieldPopulated('name') && !this.isFieldPopulated('description_html')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_SUBJECT_NO_BODY_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else if (!this.isFieldPopulated('name')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else if (!this.isFieldPopulated('description_html')) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_NO_BODY_SEND_ANYWAYS', this.module),
                onConfirm: sendEmail
            });
        } else {
            sendEmail();
        }
    },

    /**
     * Build the backbone model to be sent to the Mail API with the appropriate status
     * Also display the appropriate alerts to give user indication of what is happening.
     *
     * @param {string} status (draft or ready)
     * @param {string} pendingMessage message to display while Mail API is being called
     * @param {string} successMessage message to display when a successful Mail API response has been received
     * @param {string} errorMessage message to display when Mail API call fails
     */
    saveModel: function(status, pendingMessage, successMessage, errorMessage) {
        var myURL,
            sendModel = this.initializeSendEmailModel();

        if (this._hasInvalidRecipients(sendModel)) {
            app.alert.show('mail_invalid_recipients', {
                level: 'error',
                messages: app.lang.get('ERR_INVALID_RECIPIENTS', this.module)
            });
            this.setMainButtonsDisabled(false);
            return;
        }

        this.setMainButtonsDisabled(true);
        app.alert.show('mail_call_status', {level: 'process', title: pendingMessage});

        sendModel.set('status', status);
        myURL = app.api.buildURL('Mail');
        app.api.call('create', myURL, sendModel, {
            success: function() {
                app.alert.dismiss('mail_call_status');
                app.alert.show('mail_call_status', {autoClose: true, level: 'success', messages: successMessage});
                app.drawer.close(sendModel);
            },
            error: function(error) {
                var msg = {level: 'error'};
                if (error && _.isString(error.message)) {
                    msg.messages = error.message;
                }
                app.alert.dismiss('mail_call_status');
                app.alert.show('mail_call_status', msg);
            },
            complete: _.bind(function() {
                if (!this.disposed) {
                    this.setMainButtonsDisabled(false);
                }
            }, this)
        });
    },

    /**
     * Is this field populated?
     * @param {string} fieldName
     * @return {boolean}
     */
    isFieldPopulated: function(fieldName) {
        var value = this.model.get(fieldName);

        if (value instanceof Backbone.Collection) {
            return value.length !== 0;
        } else {
            return !_.isEmpty($.trim(value));
        }
    },

    /**
     * Check if the recipients in any of the recipient fields are invalid.
     *
     * @param {Backbone.Model} model
     * @return {boolean} Return true if there are invalid recipients in any of
     *   the fields. Return false otherwise.
     * @private
     */
    _hasInvalidRecipients: function(model) {
        return _.some(['to_addresses', 'cc_addresses', 'bcc_addresses'], function(fieldName) {
            var recipients = model.get(fieldName);
            if (!recipients) {
                return false;
            }
            return _.some(recipients.models, function(recipient) {
                return recipient.get('_invalid');
            });
        }, this);
    },

    /**
     * Open the drawer with the EmailTemplates selection list layout. The callback should take the data passed to it
     * and replace the existing editor contents with the selected template.
     */
    launchTemplateDrawer: function() {
        app.drawer.open({
                layout: 'selection-list',
                context: {
                    module: 'EmailTemplates'
                }
            },
            _.bind(this.templateDrawerCallback, this)
        );
    },

    /**
     * Receives the selected template to insert and begins the process of confirming the operation and inserting the
     * template into the editor.
     *
     * @param {Data.Bean} model
     */
    templateDrawerCallback: function(model) {
        if (model) {
            var emailTemplate = app.data.createBean('EmailTemplates', { id: model.id });
            emailTemplate.fetch({
                success: _.bind(this.confirmTemplate, this),
                error: _.bind(function(model, error) {
                    this._showServerError(error);
                }, this)
            });
        }
    },

    /**
     * Presents the user with a confirmation prompt indicating that inserting the template will replace all content
     * in the editor. If the user confirms "yes" then the template will inserted.
     *
     * @param {Data.Bean} template
     */
    confirmTemplate: function(template) {
        if (this.disposed === true) return; //if view is already disposed, bail out
        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_EMAILTEMPLATE_MESSAGE_SHOW_MSG', this.module),
            onConfirm: _.bind(this.insertTemplate, this, template)
        });
    },

    /**
     * Inserts the template into the editor.
     *
     * @param {Data.Bean} template
     */
    insertTemplate: function(template) {
        var subject,
            notes;

        if (_.isObject(template)) {
            subject = template.get('subject');

            if (subject) {
                this.model.set('name', subject);
            }

            //TODO: May need to move over replaces special characters.
            if (template.get('text_only') === 1) {
                this.model.set('description_html', template.get('body'));
            } else {
                this.model.set('description_html', template.get('body_html'));
            }

            notes = app.data.createBeanCollection('Notes');

            notes.fetch({
                'filter': {
                    'filter': [
                        //FIXME: email_type should be EmailTemplates
                        {'email_id': {'$equals': template.id}}
                    ]
                },
                success: _.bind(function(data) {
                    if (this.disposed === true) return; //if view is already disposed, bail out
                    if (!_.isEmpty(data.models)) {
                        this.insertTemplateAttachments(data.models);
                    }
                }, this),
                error: _.bind(function(collection, error) {
                    this._showServerError(error);
                }, this)
            });

            // currently adds the html signature even when the template is text-only
            this._updateEditorWithSignature(this._lastSelectedSignature);
        }
    },

    /**
     * Inserts attachments associated with the template by triggering an "add" event for each attachment to add to the
     * attachments field.
     *
     * @param {Array} attachments
     */
    insertTemplateAttachments: function(attachments) {
        this.context.trigger('attachments:remove-by-tag', 'template');
        _.each(attachments, function(attachment) {
            var filename = attachment.get('filename');
            this.context.trigger('attachment:add', {
                id: attachment.id,
                name: filename,
                nameForDisplay: filename,
                tag: 'template',
                type: this.ATTACH_TYPE_TEMPLATE
            });
        }, this);
    },

    /**
     * Launch the file upload picker on the attachments field.
     */
    launchFilePicker: function() {
        this.context.trigger('attachment:filepicker:launch');
    },

    /**
     * Open the drawer with the SugarDocuments attachment selection list layout. The callback should take the data
     * passed to it and add the document as an attachment.
     */
    launchDocumentDrawer: function() {
        app.drawer.open({
                layout: 'selection-list',
                context: {module: 'Documents'}
            },
            _.bind(this.documentDrawerCallback, this)
        );
    },

    /**
     * Fetches the selected SugarDocument using its ID and triggers an "add" event to add the attachment to the
     * attachments field.
     *
     * @param {Data.Bean} model
     */
    documentDrawerCallback: function(model) {
        if (model) {
            var sugarDocument = app.data.createBean('Documents', { id: model.id });
            sugarDocument.fetch({
                success: _.bind(function(model) {
                    if (this.disposed === true) return; //if view is already disposed, bail out
                    this.context.trigger('attachment:add', {
                        id: model.id,
                        name: model.get('filename'),
                        nameForDisplay: model.get('filename'),
                        type: this.ATTACH_TYPE_SUGAR_DOCUMENT
                    });
                }, this),
                error: _.bind(function(model, error) {
                    this._showServerError(error);
                }, this)
            });
        }
    },

    /**
     * Hide attachment field row if no attachments, show when added
     *
     * @param {Array} attachments
     */
    toggleAttachmentVisibility: function(attachments) {
        var $row = this.$('.attachments').closest('.row-fluid');
        if (attachments.length > 0) {
            $row.removeClass('hidden');
            $row.addClass('single');
        } else {
            $row.addClass('hidden');
            $row.removeClass('single');
        }
        this.resizeEditor();
    },

    /**
     * Open the drawer with the signature selection layout. The callback should take the data passed to it and insert
     * the signature in the correct place.
     *
     * @private
     */
    launchSignatureDrawer: function() {
        app.drawer.open(
            {
                layout: 'selection-list',
                context: {
                    module: 'UserSignatures'
                }
            },
            _.bind(this._updateEditorWithSignature, this)
        );
    },

    /**
     * Fetches the signature content using its ID and updates the editor with the content.
     *
     * @param {Data.Bean} model
     */
    _updateEditorWithSignature: function(model) {
        if (model && model.id) {
            var signature = app.data.createBean('UserSignatures', { id: model.id });

            signature.fetch({
                success: _.bind(function(model) {
                    if (this.disposed === true) return; //if view is already disposed, bail out
                    if (this._insertSignature(model)) {
                        this._lastSelectedSignature = model;
                    }
                }, this),
                error: _.bind(function(model, error) {
                    this._showServerError(error);
                }, this)
            });
        }
    },

    /**
     * Inserts the signature into the editor.
     *
     * @param {Data.Bean} signature
     * @return {Boolean}
     * @private
     */
    _insertSignature: function(signature) {
        if (_.isObject(signature) && signature.get('signature_html')) {
            var signatureContent = this._formatSignature(signature.get('signature_html')),
                emailBody = this.model.get('description_html') || '',
                signatureOpenTag = '<br class="signature-begin" />',
                signatureCloseTag = '<br class="signature-end" />',
                signatureOpenTagForRegex = '(<br\ class=[\'"]signature\-begin[\'"].*?\/?>)',
                signatureCloseTagForRegex = '(<br\ class=[\'"]signature\-end[\'"].*?\/?>)',
                signatureOpenTagMatches = emailBody.match(new RegExp(signatureOpenTagForRegex, 'gi')),
                signatureCloseTagMatches = emailBody.match(new RegExp(signatureCloseTagForRegex, 'gi')),
                regex = new RegExp(signatureOpenTagForRegex + '[\\s\\S]*?' + signatureCloseTagForRegex, 'g');

            if (signatureOpenTagMatches && !signatureCloseTagMatches) {
                // there is a signature, but no close tag; so the signature runs from open tag until EOF
                emailBody = this._insertSignatureTag(emailBody, signatureCloseTag, false); // append the close tag
            } else if (!signatureOpenTagMatches && signatureCloseTagMatches) {
                // there is a signature, but no open tag; so the signature runs from BOF until close tag
                emailBody = this._insertSignatureTag(emailBody, signatureOpenTag, true); // prepend the open tag
            } else if (!signatureOpenTagMatches && !signatureCloseTagMatches) {
                // there is no signature, so add the tag to the correct location
                emailBody = this._insertSignatureTag(
                    emailBody,
                    signatureOpenTag + signatureCloseTag, // insert both tags as one
                    (app.user.getPreference('signature_prepend') == 'true'));
            }

            this.model.set('description_html', emailBody.replace(regex, '$1' + signatureContent + '$2'));

            return true;
        }

        return false;
    },

    /**
     * Inserts a tag into the editor to surround the signature so the signature can be identified again.
     *
     * @param {string} body
     * @param {string} tag
     * @param {string} prepend
     * @return {string}
     * @private
     */
    _insertSignatureTag: function(body, tag, prepend) {
        var preSignature = '',
            postSignature = '';

        prepend = prepend || false;

        if (prepend) {
            var bodyOpenTag = '<body>',
                bodyOpenTagLoc = body.indexOf(bodyOpenTag);

            if (bodyOpenTagLoc > -1) {
                preSignature = body.substr(0, bodyOpenTagLoc + bodyOpenTag.length);
                postSignature = body.substr(bodyOpenTagLoc + bodyOpenTag.length, body.length);
            } else {
                postSignature = body;
            }
        } else {
            var bodyCloseTag = '</body>',
                bodyCloseTagLoc = body.indexOf(bodyCloseTag);

            if (bodyCloseTagLoc > -1) {
                preSignature = body.substr(0, bodyCloseTagLoc);
                postSignature = body.substr(bodyCloseTagLoc, body.length);
            } else {
                preSignature = body;
            }
        }

        return preSignature + tag + postSignature;
    },

    /**
     * Formats HTML signatures to replace select HTML-entities with their true characters.
     *
     * @param {string} signature
     */
    _formatSignature: function(signature) {
        signature = signature.replace(/&lt;/gi, '<');
        signature = signature.replace(/&gt;/gi, '>');

        return signature;
    },

    /**
     * Show a generic alert for server errors resulting from custom API calls during Email Compose workflows. Logs
     * the error message for system administrators as well.
     *
     * @param {SUGAR.HttpError} error
     * @private
     */
    _showServerError: function(error) {
        app.alert.show('server-error', {
            level: 'error',
            messages: 'ERR_GENERIC_SERVER_ERROR'
        });
        app.error.handleHttpError(error);
    },

    /**
     * When toggling to show/hide hidden panel, resize editor accordingly
     */
    handleMoreLessToggled: function() {
        this.resizeEditor();
    },

    /**
     * When TinyMCE has been completely initialized, go ahead and resize the editor
     */
    handleTinyMceInit: function() {
        this.resizeEditor();
    },

    _dispose: function() {
        if (app.drawer) {
            app.drawer.off(null, null, this);
        }
        app.alert.dismiss('email-client-status');
        this._super('_dispose');
    },

    /**
     * Register keyboard shortcuts.
     */
    registerShortcuts: function() {
        app.shortcuts.register({
            id: 'Compose:Action:More',
            keys: 'm',
            component: this,
            description: 'LBL_SHORTCUT_OPEN_MORE_ACTION',
            handler: function() {
                var $primaryDropdown = this.$('.btn-primary[data-toggle=dropdown]');
                if ($primaryDropdown.is(':visible') && !$primaryDropdown.hasClass('disabled')) {
                    $primaryDropdown.click();
                }
            }
        });
        this._super('registerShortcuts');
    },

    /**
     * Resize the html editor based on height of the drawer it is in
     *
     * @param {number} [drawerHeight] current height of the drawer or height the drawer will be after animations
     */
    resizeEditor: function(drawerHeight) {
        var $editor, headerHeight, recordHeight, showHideHeight, diffHeight, editorHeight, newEditorHeight;

        $editor = this.$('.mce-stack-layout .mce-stack-layout-item iframe');
        //if editor not already rendered, cannot resize
        if ($editor.length === 0) {
            return;
        }

        drawerHeight = drawerHeight || app.drawer.getHeight();
        headerHeight = this.$('.headerpane').outerHeight(true);
        recordHeight = this.$('.record').outerHeight(true);
        showHideHeight = this.$('.show-hide-toggle').outerHeight(true);
        editorHeight = $editor.height();

        //calculate the space left to fill - subtracting padding to prevent scrollbar
        diffHeight = drawerHeight - headerHeight - recordHeight - showHideHeight -
            this.ATTACHMENT_FIELD_HEIGHT - this.EDITOR_RESIZE_PADDING;

        //add the space left to fill to the current height of the editor to get a new height
        newEditorHeight = editorHeight + diffHeight;

        //maintain min height
        if (newEditorHeight < this.MIN_EDITOR_HEIGHT) {
            newEditorHeight = this.MIN_EDITOR_HEIGHT;
        }

        //set the new height for the editor
        $editor.height(newEditorHeight);
    },

    /**
     * Turn off logic from record view which handles clicking the cancel button
     * as it causes issues for email compose.
     *
     * TODO: Remove this when record view changes to use button events instead
     * of DOM based events
     */
    cancelClicked: $.noop
})
