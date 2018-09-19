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
 * Recipients field group for handling expand to edit
 *
 * @class View.Fields.Base.Emails.RecipientsFieldsetField
 * @alias SUGAR.App.view.fields.BaseEmailsRecipientsFieldsetField
 * @extends View.Fields.Base.FieldsetField
 */
({
    extendsFrom: 'FieldsetField',

    _addressBookState: 'closed',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.events = _.extend({}, this.events, {
            'click [data-toggle-field]': '_handleToggleButtonClick',
            'click .fieldset-field': '_focus'
        });
        this._super('initialize', [options]);

        this.listenTo(this.view, 'address-book-state', function(state) {
            this._addressBookState = state;
        });
        this.listenTo(this.view, 'tinymce:focus', this._blur);
        $(document).on('click.email-recipients', _.bind(this._blur, this));
    },

    /**
     * Adds the CC and BCC toggle buttons to the From field and sets the
     * visibility of those fields. Switches the field to edit mode when there
     * are no recipients and the user is creating an email.
     *
     * @inheritdoc
     */
    _render: function() {
        var cc = this.model.get('cc_collection');
        var bcc = this.model.get('bcc_collection');

        this._super('_render');

        this._addToggleButtons('outbound_email_id');
        this._toggleFieldVisibility('cc_collection', !!cc.length);
        this._toggleFieldVisibility('bcc_collection', !!bcc.length);
    },

    /**
     * @inheritdoc
     * @example
     * // Only the To field has recipients.
     * a@b.com, b@c.com
     * @example
     * // All fields have recipients.
     * a@b.com; CC: c@d.com; BCC: e@f.com
     * @example
     * // CC does not have recipients.
     * a@b.com; BCC: e@f.com
     * @example
     * Only the CC field has recipients.
     * CC: c@d.com
     */
    format: function(value) {
        return _.chain(this.fields)
            // The from field is not used for calculating the value.
            .where({type: 'email-recipients'})
            // Construct each field's string from it's formatted value.
            .reduce(function(fields, field) {
                var models = field.getFormattedValue();
                var str = _.map(models, function(model) {
                    var name = model.get('parent_name') || '';
                    var email = model.get('email_address') || '';

                    // The name was erased, so let's use the label.
                    if (_.isEmpty(name) && model.isNameErased()) {
                        name = app.lang.get('LBL_VALUE_ERASED', model.module);
                    }

                    if (!_.isEmpty(name)) {
                        return name;
                    }

                    // The email was erased, so let's use the label.
                    if (_.isEmpty(email) && model.isEmailErased()) {
                        email = app.lang.get('LBL_VALUE_ERASED', model.module);
                    }

                    return email;
                }).join(', ');

                if (!_.isEmpty(str)) {
                    fields[field.name] = str;
                }

                return fields;
            }, {})
            // Add the label for each field's string.
            .map(function(field, fieldName) {
                var label = '';

                if (fieldName === 'cc_collection') {
                    label = app.lang.get('LBL_CC', this.module) + ': ';
                } else if (fieldName === 'bcc_collection') {
                    label = app.lang.get('LBL_BCC', this.module) + ': ';
                }

                return label + field;
            }, this)
            .value()
            // Separate each field's string by a semi-colon.
            .join('; ');
    },

    /**
     * Cannot switch to detail mode if in create mode and there are no
     * recipients. The mode is set to edit.
     *
     * @inheritdoc
     */
    setMode: function(name) {
        var to = this.model.get('to_collection');
        var cc = this.model.get('cc_collection');
        var bcc = this.model.get('bcc_collection');
        var hasRecipients = to.length > 0 || cc.length > 0 || bcc.length > 0;

        if (this.view.createMode && name === 'detail' && !hasRecipients) {
            name = 'edit';
        }

        this._super('setMode', [name]);
    },

    /**
     * Switches the field to edit mode when the user clicks on the fieldset.
     *
     * @param {Event} [event]
     * @public
     */
    _focus: function(event) {
        if (this.disposed) {
            return;
        }

        // Stop the event from triggering _blur to be called.
        if (event) {
            event.stopPropagation();
        }

        if (this.action !== 'edit') {
            this.setMode('edit');
        }
    },

    /**
     * Switches the field to detail mode when the user clicks outside the
     * fieldset.
     *
     * @param {Event} [event]
     * @public
     */
    _blur: function(event) {
        if (this.disposed) {
            return;
        }

        // Don't change modes if the address book is open.
        if (this._addressBookState === 'open') {
            return;
        }

        if (this.action !== 'detail') {
            this.setMode('detail');
        }
    },

    /**
     * Add CC and BCC toggle buttons to the field.
     *
     * @param {string} fieldName The name of the field where the buttons are
     * added.
     * @private
     */
    _addToggleButtons: function(fieldName) {
        var field = this.view.getField(fieldName);
        var $field;
        var template;
        var html;

        if (!field) {
            return;
        }

        $field = field.$el.closest('.fieldset-field');

        if ($field.length > 0) {
            template = app.template.getField(this.type, 'recipient-options', this.module);
            html = template({module: this.module});
            $(html).appendTo($field);
        }
    },

    /**
     * Toggle the visibility of the field associated with the button that was
     * clicked.
     *
     * @param {Event} event
     * @private
     */
    _handleToggleButtonClick: function(event) {
        var $toggleButton = $(event.currentTarget);
        var fieldName = $toggleButton.data('toggle-field');

        this._toggleFieldVisibility(fieldName);
    },

    /**
     * Toggles the visibility of the field and the toggle state of its
     * associated button.
     *
     * @param {string} fieldName The name of the field to toggle.
     * @param {boolean} [show] True when the button should be inactive and the
     * field should be shown. The toggle is flipped when undefined.
     * @private
     */
    _toggleFieldVisibility: function(fieldName, show) {
        var toggleButtonSelector = '[data-toggle-field="' + fieldName + '"]';
        var $toggleButton = this.$(toggleButtonSelector);
        var field = this.view.getField(fieldName);

        // if explicit active state not set, toggle to opposite
        if (_.isUndefined(show)) {
            show = !$toggleButton.hasClass('active');
        }

        $toggleButton.toggleClass('active', show);

        if (field) {
            field.$el.closest('.fieldset-group').toggleClass('hide', !show);
        }

        this.view.trigger('email-recipients:toggled');
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        $(document).off('click.email-recipients');
        this._super('_dispose');
    }
})
