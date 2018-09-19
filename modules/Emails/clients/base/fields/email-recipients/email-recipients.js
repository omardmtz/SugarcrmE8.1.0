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
 * @class View.Fields.Base.Emails.EmailRecipientsField
 * @alias SUGAR.App.view.fields.BaseEmailsEmailRecipientsField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    /**
     * The selector for accessing the Select2 field when in edit mode. The
     * Select2 field is where the recipients are displayed.
     *
     * @property {string}
     */
    fieldTag: 'input.select2',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var plugins = [
            'CollectionFieldLoadAll',
            'EmailParticipants',
            'DragdropSelect2',
            'ListEditable'
        ];

        this.plugins = _.union(this.plugins || [], plugins);
        this.events = _.extend({}, this.events, {
            'click .btn': '_showAddressBook'
        });
        this._super('initialize', [options]);

        // Specify the error label for when any recipient's email address is
        // invalid.
        app.error.errorName2Keys[this.type] = app.lang.get('ERR_INVALID_RECIPIENTS', this.module);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (this.model) {
            // Avoids a full re-rendering when editing. The current value of
            // the field is formatted and passed directly to Select2 when in
            // edit mode.
            this.listenTo(this.model, 'change:' + this.name, _.bind(function() {
                var $el = this.$(this.fieldTag);

                if (_.isEmpty($el.data('select2'))) {
                    this.render();
                } else {
                    $el.select2('data', this.getFormattedValue());
                    this._decorateRecipients();
                    this._enableDragDrop();
                }
            }, this));
        }
    },

    /**
     * @inheritdoc
     */
    bindDomChange: function() {
        var $el = this.$(this.fieldTag);

        $el.on('select2-selecting', _.bind(function(event) {
            // Don't add the choice if it duplicates an existing recipient.
            var duplicate = this.model.get(this.name).find(function(model) {
                if (event.choice.get('parent_id')) {
                    return event.choice.get('parent_type') === model.get('parent_type') &&
                        event.choice.get('parent_id') === model.get('parent_id');
                }

                return event.choice.get('email_address_id') === model.get('email_address_id') ||
                    event.choice.get('email_address') === model.get('email_address');
            });

            if (this.disposed || duplicate) {
                event.preventDefault();
            }
        }, this));

        $el.on('change', _.bind(function(event) {
            var collection;

            if (this.model && !this.disposed) {
                collection = this.model.get(this.name);

                if (!_.isEmpty(event.added)) {
                    collection.add(event.added);
                }

                if (!_.isEmpty(event.removed)) {
                    collection.remove(event.removed);
                }
            }
        }, this));
    },

    /**
     * @inheritdoc
     *
     * Destroys the Select2 element.
     */
    unbindDom: function() {
        this.$(this.fieldTag).select2('destroy');
        this._super('unbindDom');
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var $el;
        var options;

        this._super('_render');

        $el = this.$(this.fieldTag);

        if ($el.length > 0) {
            options = this.getSelect2Options();
            options = _.extend(options, {
                allowClear: true,
                multiple: true,
                containerCssClass: 'select2-choices-pills-close',

                /**
                 * Constructs a representation for a selected recipient to be
                 * displayed in the field.
                 *
                 * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                 *
                 * @param {Data.Bean} recipient
                 * @return {string}
                 * @private
                 */
                formatSelection: _.bind(function(recipient) {
                    var template = app.template.getField(this.type, 'select2-selection', this.module);
                    var name = recipient.get('parent_name') || '';
                    var email = recipient.get('email_address') || '';

                    // The name was erased, so let's use the label.
                    if (_.isEmpty(name) && recipient.nameIsErased) {
                        name = app.lang.get('LBL_VALUE_ERASED', recipient.module);
                    }

                    // The email was erased, so let's use the label.
                    if (_.isEmpty(email) && recipient.emailIsErased) {
                        email = app.lang.get('LBL_VALUE_ERASED', recipient.module);
                    }

                    return template({
                        cid: recipient.cid,
                        name: name || email,
                        email_address: email,
                        invalid: recipient.invalid,
                        opt_out: !!recipient.get('opt_out'),
                        name_is_erased: recipient.nameIsErased,
                        email_is_erased: recipient.emailIsErased
                    });
                }, this),

                /**
                 * Constructs a representation for the recipient to be
                 * displayed in the dropdown options after a query.
                 *
                 * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                 *
                 * @param {Data.Bean} recipient
                 * @return {string}
                 */
                formatResult: _.bind(function(recipient) {
                    var template = app.template.getField(this.type, 'select2-result', this.module);

                    return template({
                        value: recipient.toHeaderString({quote_name: true}),
                        module: recipient.get('parent_type')
                    });
                }, this),

                /**
                 * Don't escape a choice's markup since we built the HTML.
                 *
                 * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                 *
                 * @param {string} markup
                 * @return {string}
                 */
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
            $el.select2(options).select2('val', []);

            if (this.isDisabled()) {
                $el.select2('disable');
            }

            this._decorateRecipients();
            this._enableDragDrop();
        }
    },

    /**
     * @inheritdoc
     * @return {Array}
     */
    format: function(value) {
        // Reset the tooltip.
        this.tooltip = '';

        if (value instanceof app.BeanCollection) {
            value = value.map(this.prepareModel, this);

            // Must wrap the callback in a function or else the collection's
            // index will be passed, causing the second parameter of
            // EmailParticipantsPlugin#formatForHeader to unintentionally
            // receive a value.
            this.tooltip = _.map(value, function(model) {
                return model.toHeaderString();
            }, this).join(', ');
        }

        return value;
    },

    /**
     * Decorates recipients that need it.
     *
     * @private
     */
    _decorateRecipients: function() {
        this._decorateOptedOutRecipients();
        this._decorateInvalidRecipients();
    },

    /**
     * Decorate any invalid recipients.
     *
     * @private
     */
    _decorateInvalidRecipients: function() {
        var self = this;
        var $invalidRecipients = this.$('.select2-search-choice [data-invalid="true"]');

        $invalidRecipients.each(function() {
            var $choice = $(this).closest('.select2-search-choice');
            $choice.addClass('select2-choice-danger');

            // Don't change the tooltip if the email address has been erased.
            if (!$(this).data('email-is-erased')) {
                $(this).attr('data-title', app.lang.get('ERR_INVALID_EMAIL_ADDRESS', self.module));
            }
        });
    },

    /**
     * Decorate any opted out email addresses.
     *
     * Email addresses that are opted out and invalid are not decorated by this
     * method. This preserves the invalid recipient decoration, since users
     * will need that decoration to correct their email before saving or
     * sending.
     *
     * @private
     */
    _decorateOptedOutRecipients: function() {
        var self = this;
        var $optedOutRecipients = this.$('.select2-search-choice [data-optout="true"]:not([data-invalid="true"])');

        $optedOutRecipients.each(function() {
            var $choice = $(this).closest('.select2-search-choice');
            $choice.addClass('select2-choice-optout');
            $(this).attr('data-title', app.lang.get('LBL_EMAIL_ADDRESS_OPTED_OUT', self.module));
        });
    },

    /**
     * Enable the user to drag and drop recipients between recipient fields.
     *
     * @private
     */
    _enableDragDrop: function() {
        var $el = this.$(this.fieldTag);

        if (!this.def.readonly) {
            this.setDragDropPluginEvents($el);
        }
    },

    /**
     * When in edit mode, the field includes an icon button for opening an
     * address book. Clicking the button will trigger an event to open the
     * address book, which calls this method does. The selected recipients are
     * added to this field upon closing the address book.
     *
     * @private
     */
    _showAddressBook: function() {
        app.drawer.open(
            {
                layout: 'compose-addressbook',
                context: {
                    module: 'Emails',
                    mixed: true
                }
            },
            _.bind(function(recipients) {
                if (recipients && recipients.length > 0) {
                    // Set the correct link for the field where these
                    // recipients are being added.
                    var eps = recipients.map(function(recipient) {
                        recipient.set('_link', this.getLinkName());

                        return recipient;
                    }, this);

                    this.model.get(this.name).add(eps);
                }

                this.view.trigger('address-book-state', 'closed');
            }, this)
        );

        this.view.trigger('address-book-state', 'open');
    },

    /**
     * Moves the recipients to the target collection.
     *
     * @param {Data.BeanCollection} source The collection from which the
     *   recipients are removed.
     * @param {Data.BeanCollection} target The collection to which the
     *   items are added.
     * @param {Array} draggedItems The recipients that are to be removed from
     *   the source collection.
     * @param {Array} droppedItems The recipients that are to be added to
     *   the target collection.
     */
    dropDraggedItems: function(source, target, draggedItems, droppedItems) {
        source.remove(draggedItems);

        _.each(droppedItems, function(item) {
            // The `id` must be unset because we're effectively creating
            // a brand new model to be linked.
            item.unset('id');
            item.set('_link', this.getLinkName());
        }, this);

        target.add(droppedItems);
    }
})
