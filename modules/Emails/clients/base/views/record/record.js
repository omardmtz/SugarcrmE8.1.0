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
 * @class View.Views.Base.Emails.RecordView
 * @alias SUGAR.App.view.views.BaseEmailsRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',

    /**
     * Constant representing the state of an email when it is a draft.
     *
     * @property {string}
     */
    STATE_DRAFT: 'Draft',

    /**
     * @inheritdoc
     *
     * Alerts the user if the email is a draft, so that user can switch to
     * composing the email instead of simply viewing it.
     */
    initialize: function(options) {
        var loadingRequests = 0;

        this._super('initialize', [options]);

        this._alertUserDraftState();

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
    },

    /**
     * @inheritdoc
     *
     * Alerts the user if the email becomes a draft -- most likely due to
     * asynchronous data patching -- so that user can switch to composing the
     * email instead of simply viewing it.
     *
     * Renders the recipients fieldset anytime there are changes to the From,
     * To, CC, or BCC fields.
     */
    bindDataChange: function() {
        var self = this;

        /**
         * Render the specified recipients field.
         *
         * @param {string} fieldName
         */
        function renderRecipientsField(fieldName) {
            var field = self.getField(fieldName);

            if (field) {
                field.render();
            }
        }

        if (this.model) {
            this.listenTo(this.model, 'change:state', function() {
                this._alertUserDraftState();
                this.setButtonStates(this.getCurrentButtonState());
            });

            this.listenTo(this.model, 'change:from_collection', function() {
                renderRecipientsField('from_collection');
            });
            this.listenTo(this.model, 'change:to_collection', function() {
                renderRecipientsField('to_collection');
            });
            this.listenTo(this.model, 'change:cc_collection', function() {
                renderRecipientsField('cc_collection');
            });
            this.listenTo(this.model, 'change:bcc_collection', function() {
                renderRecipientsField('bcc_collection');
            });
        }

        this._super('bindDataChange');
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
     * Switches to the email compose route if the email is a draft.
     */
    editClicked: function() {
        if (this._isEditableDraft()) {
            this._navigateToEmailCompose();
        } else {
            this._super('editClicked');
        }
    },

    /**
     * @inheritdoc
     *
     * Hides the Forward, Reply, and Reply All buttons if the email is a draft.
     */
    setButtonStates: function(state) {
        var buttons;
        var buttonsToHideOnDrafts;

        /**
         * Some buttons contain other buttons, like ActiondropdownField. This
         * function recursively finds all buttons starting at the root.
         *
         * @param {Array} allButtons The set of buttons that have been found so
         * far. Begin with an empty array to prime the set.
         * @param {Object|Array} root A collection of button fields.
         * @return {Array}
         */
        function getAllButtons(allButtons, root) {
            var nestedButtons = _.flatten(_.compact(_.pluck(root, 'fields')));

            if (nestedButtons.length > 0) {
                allButtons = allButtons.concat(nestedButtons);
                allButtons = getAllButtons(allButtons, nestedButtons);
            }

            return allButtons;
        }

        this._super('setButtonStates', [state]);

        if (this.model.get('state') === this.STATE_DRAFT) {
            buttons = getAllButtons([], this.buttons);
            buttonsToHideOnDrafts = _.filter(buttons, function(field) {
                return _.contains(['reply_button', 'reply_all_button', 'forward_button'], field.name);
            });

            _.each(buttonsToHideOnDrafts, function(field) {
                field.hide();
            });
        }
    },

    /**
     * Alerts the user if a draft was opened in the record view, so the user
     * can switch to composing the email instead of simply viewing it.
     *
     * @private
     */
    _alertUserDraftState: function() {
        app.alert.dismiss('email-draft-alert');

        if (this._isEditableDraft()) {
            app.alert.show('email-draft-alert', {
                level: 'warning',
                autoClose: false,
                title: ' ',
                messages: app.lang.get('LBL_OPEN_DRAFT_ALERT', this.module, {subject: this.model.get('name')}),
                onLinkClick: _.bind(function(event) {
                    app.alert.dismiss('email-draft-alert');
                    this._navigateToEmailCompose();
                }, this)
            });
        }
    },

    /**
     * @inheritdoc
     *
     * @return {string} Returns (no subject) when the record name is empty.
     */
    _getNameForMessage: function(model) {
        var name = this._super('_getNameForMessage', [model]);

        if (_.isEmpty(name)) {
            return app.lang.get('LBL_NO_SUBJECT', this.module);
        }

        return name;
    },

    /**
     * Determines the email is a draft and the user can edit it.
     *
     * @return {boolean}
     * @private
     */
    _isEditableDraft: function() {
        return this.model.get('state') === this.STATE_DRAFT && app.acl.hasAccessToModel('edit', this.model);
    },

    /**
     * Switches to the email compose route for the email. This method should
     * only be used if the email is a draft.
     *
     * @private
     */
    _navigateToEmailCompose: function() {
        var route;

        if (this._isEditableDraft()) {
            route = '#' + app.router.buildRoute(this.model.module, this.model.get('id'), 'compose');
            app.router.navigate(route, {trigger: true});
        }
    }
})
