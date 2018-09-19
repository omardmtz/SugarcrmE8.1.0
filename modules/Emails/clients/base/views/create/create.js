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
 * @class View.Views.Base.Emails.CreateView
 * @alias SUGAR.App.view.views.BaseEmailsCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',

    /**
     * The editor's height can never be smaller than this constant.
     *
     * @property {number}
     */
    MIN_EDITOR_HEIGHT: 200,

    /**
     * The padding that needs to be accounted for to prevent the scroll bar
     * from appearing when the editor is resized.
     *
     * @property {number}
     */
    EDITOR_RESIZE_PADDING: 5,

    /**
     * @inheritdoc
     */
    _titleLabel: 'LNK_NEW_ARCHIVE_EMAIL',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // batch queued calls to editor resize function
        this.resizeEditor = _.debounce(_.bind(this._resizeEditor, this), 100);

        this.listenTo(this.context, 'tinymce:oninit', function() {
            this.resizeEditor();
        });
        this.listenTo(app.drawer, 'drawer:resize', function() {
            this.resizeEditor();
        });
        this.on('more-less:toggled', function() {
            this.resizeEditor();
        }, this);
        this.on('email-recipients:toggled', function() {
            this.resizeEditor();
        }, this);
        $(window).on('resize.' + this.cid, this.resizeEditor);
    },

    /**
     * @inheritdoc
     *
     * Hides or shows the attachments field based on whether or not there are
     * attachments when changes to the attachments are detected.
     *
     * Disables the save button if the attachments exceed the
     * max_aggregate_email_attachments_bytes configuration. Alerts the user, as
     * well. Enables the save button and dismisses the alert if the attachments
     * are under the max_aggregate_email_attachments_bytes configuration
     * configuration.
     */
    bindDataChange: function() {
        if (this.model) {
            this.listenTo(this.model, 'change:attachments_collection', this._hideOrShowTheAttachmentsField);
            this.listenTo(this.model, 'attachments_collection:over_max_total_bytes', function(totalBytes, maxBytes) {
                var readableMax = app.utils.getReadableFileSize(maxBytes);
                var label = app.lang.get('LBL_TOTAL_ATTACHMENT_MAX_SIZE', this.module);
                var saveButton = this.getField(this.saveButtonName);

                app.alert.show('email-attachment-status', {
                    level: 'warning',
                    messages: app.utils.formatString(label, [readableMax])
                });

                if (saveButton) {
                    saveButton.setDisabled(true);
                }
            });
            this.listenTo(this.model, 'attachments_collection:under_max_total_bytes', function() {
                var saveButton = this.getField(this.saveButtonName);

                app.alert.dismiss('email-attachment-status');

                if (saveButton) {
                    saveButton.setDisabled(false);
                }
            });
        }

        this._super('bindDataChange');
    },

    /**
     * @inheritdoc
     *
     * EmailsApi responds with a 451 HTTP status code to report custom errors
     * related to sending email. Anytime a 451 code is encountered, the error
     * is alerted to the user, which should provide more useful information
     * than a standard HTTP error. Other errors in the 400-499 range are
     * handled normally in core.
     */
    saveModel: function(success, error) {
        var onError = _.bind(function(model, e) {
            if (e && e.status == 451) {
                // Mark the error as having been handled
                e.handled = true;
                this.enableButtons();
                app.alert.show(e.error, {
                    level: 'error',
                    autoClose: false,
                    messages: e.message
                });
            } else if (error) {
                error(model, e);
            }
        }, this);

        this._super('saveModel', [success, onError]);
    },

    /**
     * @inheritdoc
     *
     * Adds the view parameter. It must be added to `options.params` because
     * the `options.view` is only added as a parameter if the request method is
     * "read".
     */
    getCustomSaveOptions: function(options) {
        options = options || {};
        options.params = options.params || {};
        options.params.view = this.name;

        return options;
    },

    /**
     * @inheritdoc
     *
     * Sets the title of the page. Hides or shows the attachments field.
     */
    _render: function() {
        this._super('_render');

        this.setTitle(app.lang.get(this._titleLabel, this.module));
        this._hideOrShowTheAttachmentsField();

        this._resizeEditor();
    },

    /**
     * Hides the attachments field if there are no attachments and shows the
     * field if there are attachments.
     */
    _hideOrShowTheAttachmentsField: function() {
        var field = this.getField('attachments_collection');
        var $el;
        var $row;

        if (!field) {
            return;
        }

        $el = field.getFieldElement();
        $row = $el.closest('.row-fluid');

        if (field.isEmpty()) {
            $row.addClass('hidden');
            $row.removeClass('single');
        } else {
            $row.removeClass('hidden');
            $row.addClass('single');
        }
    },

    /**
     * @inheritdoc
     *
     * Builds the appropriate success message for saving an archived email.
     */
    buildSuccessMessage: function() {
        return app.lang.get('LBL_EMAIL_ARCHIVED', this.module);
    },

    /**
     * Resize the editor based on the height of the layout container.
     *
     * @private
     */
    _resizeEditor: function() {
        var $editor;
        var layoutHeight;
        var recordHeight;
        var showToggleHeight;
        var editorHeight;
        // The difference in height between the current editor and the actual
        // available height of the space available to it.
        var diffHeight;
        var newEditorHeight;

        if (this.disposed) {
            return;
        }

        $editor = this.$('.mce-stack-layout .mce-stack-layout-item iframe');
        // Cannot resize it if the editor is not already rendered.
        if ($editor.length === 0) {
            return;
        }

        layoutHeight = this.layout.$el.outerHeight(true);
        // This is the total height including the html editor and other
        // record fields. It does not include the show-hide toggle.
        recordHeight = this.$('.record').outerHeight(true);

        // Don't include the negative top margin on show-hide toggle because it
        // has no affect on the layout because the .record has no bottom margin
        showToggleHeight = this.$('.show-hide-toggle').outerHeight(false);
        editorHeight = $editor.height();
        // Calculate the difference between the current editor height and
        // maximum available height. Subtracts padding to prevent the scrollbar.
        diffHeight = layoutHeight - recordHeight - showToggleHeight - this.EDITOR_RESIZE_PADDING;
        // Add the space left to fill to the current height of the editor to
        // get the new height.
        newEditorHeight = editorHeight + diffHeight;

        // Don't drop below the minimum height.
        if (newEditorHeight < this.MIN_EDITOR_HEIGHT) {
            newEditorHeight = this.MIN_EDITOR_HEIGHT;
        }

        // Set the new height for the editor.
        $editor.height(newEditorHeight);
    },

    /**
     * Stop listening to the window resize event.
     * @inheritdoc
     */
    _dispose: function() {
        $(window).off('resize.' + this.cid);
        this._super('_dispose');
    }
})
