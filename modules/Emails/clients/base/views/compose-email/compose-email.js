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
 * @class View.Views.Base.Emails.ComposeEmailView
 * @alias SUGAR.App.view.views.BaseEmailsComposeEmailView
 * @extends View.Views.Base.Emails.CreateView
 */
({
    extendsFrom: 'EmailsCreateView',

    /**
     * Constant representing the state of an email when it is a draft.
     *
     * @property {string}
     */
    STATE_DRAFT: 'Draft',

    /**
     * Constant representing the state of an email when it is ready to be sent.
     *
     * @property {string}
     */
    STATE_READY: 'Ready',

    /**
     * The name of the send button.
     *
     * @property {string}
     */
    sendButtonName: 'send_button',

    /**
     * Used for determining if an email's content contains variables.
     *
     * @property {RegExp}
     */
    _hasVariablesRegex: /\$[a-zA-Z]+_[a-zA-Z0-9_]+/,

    /**
     * False when the email client reports a configuration issue.
     *
     * @property {boolean}
     */
    _userHasConfiguration: true,

    /**
     * The label to be used as the title of the page.
     *
     * @property {string}
     */
    _titleLabel: 'LBL_COMPOSE_MODULE_NAME_SINGULAR',

    /**
     * @inheritdoc
     *
     * Disables the send button if email has not been configured.
     */
    initialize: function(options) {
        var loadingRequests = 0;

        this._super('initialize', [options]);

        if (this.model.isNew()) {
            this.model.set('state', this.STATE_DRAFT);
        }

        this.on('email_not_configured', function() {
            var sendButton = this.getField('send_button');

            if (sendButton) {
                sendButton.setDisabled(true);
            }

            this._userHasConfiguration = false;
        }, this);

        this.on('loading_collection_field', function() {
            loadingRequests++;
            this.toggleButtons(false);
        }, this);

        this.on('loaded_collection_field', function() {
            loadingRequests--;

            if (loadingRequests === 0) {
                this.toggleButtons(true);
            }
        }, this);

        this.on('editable:toggle_fields', function(fields, viewName) {
            var field = this.getField('recipients');

            if (field) {
                field.setMode('detail');
            }
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Renders the recipients fieldset anytime there are changes to the `to`,
     * `cc`, or `bcc` fields.
     *
     * Disables the send button if the attachments exceed the
     * max_aggregate_email_attachments_bytes configuration. Enables the send
     * button if the attachments are under the
     * max_aggregate_email_attachments_bytes configuration configuration.
     */
    bindDataChange: function() {
        var self = this;
        var renderRecipientsField = _.debounce(function() {
            var field = self.getField('recipients');

            if (field) {
                field.render();
            }
        }, 200);

        if (this.model) {
            this.listenTo(
                this.model,
                'change:to_collection change:cc_collection change:bcc_collection',
                renderRecipientsField
            );
            this.listenTo(this.model, 'attachments_collection:over_max_total_bytes', function() {
                var sendButton = this.getField(this.sendButtonName);

                if (sendButton) {
                    sendButton.setDisabled(true);
                }
            });
            this.listenTo(this.model, 'attachments_collection:under_max_total_bytes', function() {
                var sendButton = this.getField(this.sendButtonName);

                if (sendButton) {
                    sendButton.setDisabled(!this._userHasConfiguration);
                }
            });
        }

        this._super('bindDataChange');
    },

    /**
     * @inheritdoc
     *
     * Registers a handler to send the email when the send button is clicked.
     */
    delegateButtonEvents: function() {
        this._super('delegateButtonEvents');
        this.listenTo(this.context, 'button:' + this.sendButtonName + ':click', function() {
            this.send();
        });
    },

    /**
     * @inheritdoc
     *
     * The send button cannot be enabled if email is not configured for the
     * user.
     */
    toggleButtons: function(enable) {
        this._super('toggleButtons', [enable]);

        if (enable && this.buttons[this.sendButtonName] && !this._userHasConfiguration) {
            this.buttons[this.sendButtonName].setDisabled(true);
        }
    },

    /**
     * @inheritdoc
     *
     * Implements the Compose:Send shortcut to send the email.
     */
    registerShortcuts: function() {
        this._super('registerShortcuts');

        app.shortcuts.register({
            id: 'Compose:Send',
            keys: ['mod+shift+s'],
            component: this,
            description: 'LBL_SHORTCUT_EMAIL_SEND',
            callOnFocus: true,
            handler: function() {
                var $sendButton = this.$('a[name=' + this.sendButtonName + ']');

                if ($sendButton.is(':visible') && !$sendButton.hasClass('disabled')) {
                    $sendButton.get(0).click();
                }
            }
        });
    },

    /**
     * @inheritdoc
     *
     * `BaseEmailsCreateView` is used when creating new emails and editing
     * existing drafts. The model is not new when editing drafts. In those
     * cases, {@link BaseEmailsRecordView#hasUnsavedChanges} is called to use
     * logic that checks for unsaved changes for existing records instead of
     * new records.
     */
    hasUnsavedChanges: function() {
        if (this.model.isNew()) {
            return this._super('hasUnsavedChanges');
        }

        return app.view.views.BaseEmailsRecordView.prototype.hasUnsavedChanges.call(this);
    },

    /**
     * Sends the email.
     *
     * Warns the user if the subject and/or body are empty. The user may still
     * send the email after confirming.
     *
     * Alerts the user if the email does not have any recipients.
     */
    send: function() {
        var confirmationMessages = [];
        var subject = this.model.get('name') || '';
        var text = this.model.get('description') || '';
        var html = this.model.get('description_html') || '';
        var fullContent = subject + ' ' + text + ' ' + html;
        var isSubjectEmpty = _.isEmpty($.trim(subject));
        // When fetching tinyMCE content, convert to jQuery Object
        // and return only if text is not empty. By wrapping the value
        // in <div> tags we remove the error if the value contains
        // no HTML markup
        var isContentEmpty = _.isEmpty($.trim($('<div>' + html + '</div>').text()));

        var sendEmail = _.bind(function() {
            this.model.set('state', this.STATE_READY);
            this.save();
        }, this);

        this.disableButtons();

        if (this.model.get('to_collection').length === 0 &&
            this.model.get('cc_collection').length === 0 &&
            this.model.get('bcc_collection').length === 0
        ) {
            this.model.trigger('error:validation:to_collection');
            app.alert.show('send_error', {
                level: 'error',
                messages: 'LBL_EMAIL_COMPOSE_ERR_NO_RECIPIENTS'
            });
            this.enableButtons();
        } else {
            // to/cc/bcc filled out, check other fields
            if (isSubjectEmpty && isContentEmpty) {
                confirmationMessages.push(app.lang.get('LBL_NO_SUBJECT_NO_BODY_SEND_ANYWAYS', this.module));
            } else if (isSubjectEmpty) {
                confirmationMessages.push(app.lang.get('LBL_SEND_ANYWAYS', this.module));
            } else if (isContentEmpty) {
                confirmationMessages.push(app.lang.get('LBL_NO_BODY_SEND_ANYWAYS', this.module));
            }

            if (_.isEmptyValue(this.model.get('parent_id')) && this._hasVariablesRegex.test(fullContent)) {
                confirmationMessages.push(app.lang.get('LBL_NO_RELATED_TO_WITH_TEMPLATE_SEND_ANYWAYS', this.module));
            }

            if (confirmationMessages.length > 0) {
                app.alert.show('send_confirmation', {
                    level: 'confirmation',
                    messages: confirmationMessages.join('<br />'),
                    onConfirm: sendEmail,
                    onCancel: _.bind(this.enableButtons, this)
                });
            } else {
                // All checks pass, send the email
                sendEmail();
            }
        }
    },

    /**
     * @inheritdoc
     *
     * Builds the appropriate success message based on the state of the email.
     */
    buildSuccessMessage: function() {
        var successLabel = this.model.get('state') === this.STATE_DRAFT ? 'LBL_DRAFT_SAVED' : 'LBL_EMAIL_SENT';

        return app.lang.get(successLabel, this.module);
    }
})
