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
 * @class View.Fields.Base.Emails.RecipientsField
 * @alias SUGAR.App.view.fields.BaseEmailsRecipientsField
 * @extends View.Fields.Base.BaseField
 * @deprecated Use {@link View.Fields.Base.Emails.EmailRecipientsField}
 * instead.
 */
({
    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    events: {
        'click .btn': '_showAddressBook'
    },

    fieldTag: 'input.select2',

    plugins: ['DragdropSelect2'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Fields.Base.Emails.RecipientsField is deprecated. Use ' +
            'View.Fields.Base.Emails.EmailRecipientsField instead.');

        this._super('initialize', [options]);
        // initialize the value to an empty collection
        this.model.setDefault(this.name, new Backbone.Collection);
    },

    /**
     * Sets up event handlers for syncing between the model and the recipients field.
     *
     * See {@link #format} for the acceptable formats for recipients.
     */
    bindDataChange: function() {
        /**
         * Sets up event handlers that allow external forces to manipulate the contents of the collection, while
         * maintaining the requirement for storing formatted recipients.
         */
        var bindCollectionChange = _.bind(function() {
            var value = this.model.get(this.name);
            if (value instanceof Backbone.Collection) {
                // on "add" we want to force the collection to be reset to guarantee that all models in the collection
                // have been properly formatted for use in this field
                value.on('add', function(models, collection) {
                    // Backbone destroys the models currently in the collection on reset, so we must clone the
                    // collection in order to add the same models again
                    collection.reset(collection.clone().models);
                }, this);
                // on "remove" the requisite models have already been removed, so we only need to bother updating the
                // value in the DOM
                value.on('remove', function(models, collection) {
                    // format the recipients and put them in the DOM
                    this._updateTheDom(this.format(this.model.get(this.name)));
                }, this);
                // on "reset" we want to replace all models in the collection with their formatted versions
                value.on('reset', function(collection) {
                    var recipients = this.format(collection.models);
                    // do this silently so we don't trigger another reset event and end up in an infinite loop
                    collection.reset(recipients, {silent: true});
                    // put the newly formatted recipients in the DOM
                    this._updateTheDom(recipients);
                }, this);
            }
        }, this);

        // set up collection event handlers for the initial collection (initialized during this.initialize)
        bindCollectionChange();

        // handle the value on the model being changed to something other than the initial collection
        this.model.on('change:' + this.name, function(model, recipients) {
            var value = this.model.get(this.name);
            if (!(value instanceof Backbone.Collection)) {
                // whoa! someone changed the value to be something other than a collection
                // stick that new value inside a collection and reset the value, so we're always dealing with a
                // collection... another change event will be triggered, so we'll end up in the else block right after
                // this
                this.model.set(this.name, new Backbone.Collection(value));
            } else {
                // phew! the value is a collection
                // but it's not the initial collection, so we'll have to set up collection event handlers for this
                // instance
                bindCollectionChange();
                // you never know what data someone sticks on the field, so we better reset the values in the collection
                // so that the recipients become formatted as we expect
                value.reset(recipients.clone().models);
            }
        }, this);
    },

    /**
     * Sets the value of the Select2 element, decorates any invalid recipients,
     * and rebuilds the tooltips for all recipients.
     *
     * @param {Array} recipients the return value for {@link #format}.
     */
    _updateTheDom: function(recipients) {
        // put the formatted recipients in the DOM
        this.getFieldElement().select2('data', recipients);
        this._decorateInvalidRecipients();
        if (!this.def.readonly) {
            this.setDragDropPluginEvents(this.getFieldElement());
        }
    },

    /**
     * Remove events from the field value if it is a collection
     */
    unbindData: function() {
        var value = this.model.get(this.name);
        if (value instanceof Backbone.Collection) {
            value.off(null, null, this);
        }

        this._super('unbindData');
    },

    /**
     * Render field with select2 widget
     *
     * @private
     */
    _render: function() {
        var $controlsEl;
        var $recipientsField;

        if (this.$el) {
            $controlsEl = this.$el.closest('.controls');
            if ($controlsEl.length) {
                $controlsEl.addClass('controls-one btn-fit');
            }
        }
        this._super('_render');

        $recipientsField = this.getFieldElement();

        if ($recipientsField.length > 0) {
            $recipientsField.select2({
                allowClear: true,
                multiple: true,
                width: 'off',
                containerCssClass: 'select2-choices-pills-close',
                containerCss: {'width': '100%'},
                minimumInputLength: 1,
                query: _.bind(function(query) {
                    this.loadOptions(query);
                }, this),
                createSearchChoice: _.bind(this.createOption, this),
                formatSelection: _.bind(this.formatSelection, this),
                formatResult: _.bind(this.formatResult, this),
                formatSearching: _.bind(this.formatSearching, this),
                formatInputTooShort: _.bind(this.formatInputTooShort, this),
                selectOnBlur: true
            });

            if (!!this.def.disabled) {
                $recipientsField.select2('disable');
            }

            if (!this.def.readonly) {
                this.setDragDropPluginEvents(this.getFieldElement());
            }
        }
    },

    /**
     * Fetches additional recipients from the server.
     *
     * See [Select2 Documentation of `query` parameter](http://ivaynberg.github.io/select2/#doc-query).
     *
     * @param {Object} query Possible attributes can be found in select2's
     *   documentation.
     */
    loadOptions: _.debounce(function(query) {
        var self = this,
            data = {
                results: [],
                // only show one page of results
                // if more results are needed, then the address book should be used
                more: false
            },
            options = {},
            callbacks = {},
            url;

        // add the search term to the URL params
        options.q = query.term;
        // the first 10 results should be enough
        // if more results are needed, then the address book should be used
        options.max_num = 10;
        // build the URL for fetching recipients that match the search term
        url = app.api.buildURL('Mail', 'recipients/find', null, options);
        // create the callbacks
        callbacks.success = function(result) {
            // the api returns objects formatted such that sidecar can convert them to beans
            // we need the records to be in a standard object format (@see RecipientsField::format) and the records
            // need to be converted into beans before we can format them
            var records = app.data.createMixedBeanCollection(result.records);
            // format and add the recipients that were found via the select2 callback
            data.results = self.format(records);
        };
        callbacks.error = function() {
            // don't add any recipients via the select2 callback
            data.results = [];
        };
        callbacks.complete = function() {
            // execute the select2 callback to add any new recipients
            query.callback(data);
        };
        app.api.call('read', url, null, callbacks);
    }, 300),

    /**
     * Create additional select2 options when loadOptions returns no matches for the search term.
     *
     * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
     *
     * @param {String} term
     * @param {Array} data The options in the select2 drop-down after the query callback has been executed.
     * @return {Object}
     */
    createOption: function(term, data) {
        if (data.length === 0) {
            return {id: term, email: term};
        }
    },

    /**
     * Formats a recipient object for displaying selected recipients.
     *
     * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
     *
     * @param {Object} recipient
     * @return {String}
     */
    formatSelection: function(recipient) {
        var value = recipient.name || recipient.email,
            template = app.template.getField(this.type, 'select2-selection', this.module);
        if (template) {
            return template({
                id: recipient.id,
                name: value,
                email: recipient.email,
                invalid: recipient._invalid
            });
        }
        return value;
    },

    /**
     * Formats a recipient object for displaying items in the recipient options list.
     *
     * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
     *
     * @param {Object} recipient
     * @return {String}
     */
    formatResult: function(recipient) {
        var format,
            email = Handlebars.Utils.escapeExpression(recipient.email);

        if (recipient.name) {
            format = '"' + Handlebars.Utils.escapeExpression(recipient.name) + '" &lt;' + email + '&gt;';
        } else {
            format = email;
        }

        return format;
    },

    /**
     * Returns the localized message indicating that a search is in progress
     *
     * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
     *
     * @return {string}
     */
    formatSearching: function() {
        return app.lang.get('LBL_LOADING', this.module);
    },

    /**
     * Suppresses the message indicating the number of characters remaining before a search will trigger
     *
     * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
     *
     * @param {string} term Search string entered by user.
     * @param {number} min Minimum required term length.
     * @return {string}
     */
    formatInputTooShort: function(term, min) {
        return '';
    },

    /**
     * Formats a set of recipients into an array of objects that select2 understands.
     *
     * See {@link #_formatRecipient} for the acceptable/expected attributes to
     * be found on each recipient.
     *
     * @param {Mixed} data A Backbone collection, a single Backbone model or standard JavaScript object, or an array of
     *   Backbone models or standard JavaScript objects.
     * @return {Array}
     */
    format: function(data) {
        var formattedRecipients = [];
        // the lowest common denominator of potential inputs is an array of objects
        // force the parameter to be an array of either objects or Backbone models so that we're always dealing with
        // one data-structure type
        if (data instanceof Backbone.Collection) {
            // get the raw array of models
            data = data.models;
        } else if (data instanceof Backbone.Model || (_.isObject(data) && !_.isArray(data))) {
            // wrap the single model in an array so the code below behaves the same whether it's a model or a collection
            data = [data];
        }
        if (_.isArray(data)) {
            _.each(data, function(recipient) {
                var formattedRecipient;
                if (!(recipient instanceof Backbone.Model)) {
                    // force the object to be a Backbone.Model to allow for certain assumptions to be made
                    // there is no harm in this because the recipient will not be added to the return value if no email
                    // address is found on the model
                    recipient = new Backbone.Model(recipient);
                }
                formattedRecipient = this._formatRecipient(recipient);
                // only add the recipient if there is an email address
                if (!_.isEmpty(formattedRecipient.email)) {
                    formattedRecipients.push(formattedRecipient);
                }
            }, this);
        }
        return formattedRecipients;
    },

    /**
     * Determine whether or not the recipient pills should be locked.
     * @return {boolean}
     */
    recipientsLocked: function() {
        return this.def.readonly || false;
    },

    /**
     * Synchronize the recipient field value with the model and setup tooltips for email pills.
     */
    bindDomChange: function() {
        var self = this;
        this.getFieldElement()
            .on('change', function(event) {
                var value = $(this).select2('data');
                if (event.removed) {
                    value = _.filter(value, function(d) {
                        return d.id !== event.removed.id;
                    });
                }
                self.model.get(self.name).reset(value);
            })
            .on('select2-selecting', _.bind(this._handleEventOnSelected, this));
    },

    /**
     * Event handler for the Select2 "select2-selecting" event.
     *
     * @param {Event} event
     * @return {boolean}
     * @private
     */
    _handleEventOnSelected: function(event) {
        // only allow the user to select an option if it is determined to be a valid email address
        // returning true will select the option; false will prevent the option from being selected
        var isValidChoice = false;

        // since this event is fired twice, we only want to perform validation on the first event
        // event.object is not available on the second event
        if (event.object) {
            // the id and email address will not match when the email address came from the database and
            // we are assuming that email addresses stored in the database have already been validated
            if (event.object.id == event.object.email) {
                // this option must be a new email address that the application does not recognize
                // so mark it as valid and kick off an async validation
                isValidChoice = true;
                this._validateEmailAddress(event.object);
            } else {
                // the application should recognize the email address, so no need to validate it again
                // just assume it's a valid choice and we'll deal with the consequences later (server-side)
                isValidChoice = true;
            }
        }

        return isValidChoice;
    },

    /**
     * Destroy all select2 and tooltip plugins
     */
    unbindDom: function() {
        this.getFieldElement().select2('destroy');
        this._super('unbindDom');
    },

    /**
     * When in edit mode, the field includes an icon button for opening an address book. Clicking the button will
     * trigger an event to open the address book, which calls this method to do the dirty work. The selected recipients
     * are added to this field upon closing the address book.
     *
     * @private
     */
    _showAddressBook: function() {
        /**
         * Callback to add recipients, from a closing drawer, to the target Recipients field.
         * @param {undefined|Backbone.Collection} recipients
         */
        var addRecipients = _.bind(function(recipients) {
            if (recipients && recipients.length > 0) {
                this.model.get(this.name).add(recipients.models);
            }
        }, this);
        app.drawer.open(
            {
                layout: 'compose-addressbook',
                context: {
                    module: 'Emails',
                    mixed: true
                }
            },
            function(recipients) {
                addRecipients(recipients);
            }
        );
    },

    /**
     * update ul.select2-choices data attribute which prevents underrun of pills by
     * using a css definition for :before {content:''} set to float right
     *
     * @param {string} content
     */
    setContentBefore: function(content) {
        this.$('.select2-choices').attr('data-content-before', content);
    },

    /**
     * Gets the recipients DOM field
     *
     * @return {Object} DOM Element
     */
    getFieldElement: function() {
        return this.$(this.fieldTag);
    },

    /**
     * Format a recipient from a Backbone.Model to a standard JavaScript object with id, module, email, and name
     * attributes. Only id and email are required for the recipient to be considered valid
     * {@link #format}.
     *
     * All attributes are optional. However, if the email attribute is not present, then a primary email address should
     * exist on the bean. Without an email address that can be resolved, the recipient is considered to be invalid. The
     * bean attribute must be a Backbone.Model and it is likely to be a Bean. Data found in the bean is considered to be
     * secondary to the attributes found on its parent model. The bean is a mechanism for collecting additional
     * information about the recipient that may not have been explicitly set when the recipient was passed in.
     * @param {Backbone.Model} recipient
     * @return {Object}
     * @private
     */
    _formatRecipient: function(recipient) {
        var formattedRecipient = {};
        if (recipient instanceof Backbone.Model) {
            var bean = recipient.get('bean');
            // if there is a bean attribute, then more data can be extracted about the recipient to fill in any holes if
            // attributes are missing amongst the primary attributes
            // so follow the trail using recursion
            if (bean) {
                formattedRecipient = this._formatRecipient(bean);
            }
            // prioritize any values found on recipient over those already extracted from bean
            formattedRecipient = {
                id: recipient.get('id') || formattedRecipient.id || recipient.get('email'),
                module: recipient.get('module') || recipient.module || recipient.get('_module') || formattedRecipient.module,
                email: recipient.get('email') || formattedRecipient.email,
                locked: this.recipientsLocked(),
                name: recipient.get('name') || recipient.get('full_name') || formattedRecipient.name,
                _invalid: recipient.get('_invalid')
            };
            // don't bother with the recipient unless an id is present
            if (!_.isEmpty(formattedRecipient.id)) {
                // extract the primary email address for the recipient
                if (_.isArray(formattedRecipient.email)) {
                    var primaryEmailAddress = _.findWhere(formattedRecipient.email, {primary_address: true});

                    if (!_.isUndefined(primaryEmailAddress) && !_.isEmpty(primaryEmailAddress.email_address)) {
                        formattedRecipient.email = primaryEmailAddress.email_address;
                    }
                }
                // drop any values that are empty or non-compliant
                _.each(formattedRecipient, function(val, key) {
                    if ((_.isEmpty(formattedRecipient[key]) || !_.isString(formattedRecipient[key])) && !_.isBoolean(formattedRecipient[key])) {
                        delete formattedRecipient[key];
                    }
                });
            } else {
                // drop all values if an id isn't present
                formattedRecipient = {};
            }
        }
        return formattedRecipient;
    },

    /**
     * Validates an email address on the server asynchronously.
     *
     * Marks the recipient as invalid if it is not a valid email address.
     *
     * @param {Object} recipient
     * @param {string} recipient.id
     * @param {string} recipient.email
     * @private
     */
    _validateEmailAddress: function(recipient) {
        var callbacks = {};
        var url = app.api.buildURL('Mail', 'address/validate');

        callbacks.success = _.bind(function(result) {
            if (!result[recipient.email] && !this.disposed) {
                this._markRecipientInvalid(recipient.id);
            }
        }, this);
        callbacks.error = _.bind(function() {
            if (!this.disposed) {
                this._markRecipientInvalid(recipient.id);
            }
        }, this);

        app.api.call('create', url, [recipient.email], callbacks);
    },

    /**
     * Mark the given recipient as invalid in the collection and update select2.
     *
     * @param {string} recipientId
     * @private
     */
    _markRecipientInvalid: function(recipientId) {
        var recipients = this.model.get(this.name);
        var recipient = recipients.get(recipientId);
        recipient.set('_invalid', true);
        this._updateTheDom(this.format(recipients));
    },

    /**
     * Decorate any invalid recipients in this field.
     * @private
     */
    _decorateInvalidRecipients: function() {
        var self = this;
        var $invalidRecipients = this.$('.select2-search-choice [data-invalid="true"]');
        $invalidRecipients.each(function() {
            var $choice = $(this).closest('.select2-search-choice');
            $choice.addClass('select2-choice-danger');
            $(this).attr('data-title', app.lang.get('ERR_INVALID_EMAIL_ADDRESS', self.module));
        });
    }
})
