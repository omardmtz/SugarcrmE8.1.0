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
(function(app) {
    app.events.on('app:init', function() {
        /**
         * Generate a unique, but consistent, ID for a validation task for the
         * component.
         *
         * @param {View.Component} component The component to which the
         * validation task is being added.
         * @return {string} email_participants_validator_<component.cid>
         */
        function getValidationTaskName(component) {
            return 'email_participants_validator_' + component.cid;
        }

        app.plugins.register('EmailParticipants', ['field'], {
            /**
             * @inheritdoc
             */
            onAttach: function(component, plugin) {
                /**
                 * Searches for more participants and loads them into Select2
                 * for selection.
                 *
                 * @param {Object} query See [Select2 Documentation of `query` parameter](http://ivaynberg.github.io/select2/#doc-query).
                 */
                var search = _.debounce(function(query) {
                    var data = {
                        results: [],
                        // Only show one page of results.
                        more: false
                    };
                    var options = {
                        // Add the search term to the URL params.
                        q: query.term,
                        // The first 10 results should be enough.
                        max_num: 10,
                        // Ask for erased fields.
                        erased_fields: true
                    };
                    var callbacks = {};
                    var url = app.api.buildURL('Mail', 'recipients/find', null, options);
                    var linkName = component.getLinkName();

                    /**
                     * Format the data in the response to be added to the set
                     * of options.
                     */
                    callbacks.success = function(result) {
                        var records = _.map(result.records, function(record) {
                            record.email_address = record.email;
                            delete record.email;

                            return record;
                        });

                        records = app.data.createMixedBeanCollection(records);
                        data.results = records.map(function(record) {
                            var ep;
                            var parentName = app.utils.getRecordName(record);

                            if (linkName) {
                                ep = app.data.createBean('EmailParticipants', {
                                    _link: linkName,
                                    parent: {
                                        _acl: record.get('_acl') || {},
                                        _erased_fields: record.get('_erased_fields') || [],
                                        type: record.module,
                                        id: record.get('id'),
                                        name: parentName
                                    },
                                    parent_type: record.module,
                                    parent_id: record.get('id'),
                                    parent_name: parentName
                                });

                                component.prepareModel(ep);
                            }

                            return ep;
                        });
                    };

                    /**
                     * Don't add any options.
                     */
                    callbacks.error = function() {
                        data.results = [];
                    };

                    /**
                     * Execute the query callback to add the results of the
                     * query as options the user can select.
                     */
                    callbacks.complete = function() {
                        query.callback(data);
                    };

                    app.api.call('read', url, null, callbacks);
                }, 300);

                this.on('init', function() {
                    var task = getValidationTaskName(this);

                    /**
                     * Verify that there are not any invalid participants.
                     */
                    this.model.addValidationTask(task, _.bind(function(fields, errors, callback) {
                        var participants = this.model.get(this.name);
                        var hasInvalidParticipants = _.some(participants.models, function(participant) {
                            return !!participant.invalid;
                        });

                        if (hasInvalidParticipants) {
                            errors[this.name] = errors[this.name] || {};
                            errors[this.name][this.type] = true;
                        }

                        callback(null, fields, errors);
                    }, this));
                });

                /**
                 * Remove the validation task when disposing the component.
                 */
                this.unbindData = _.wrap(this.unbindData, function(_super) {
                    var task = getValidationTaskName(this);

                    if (this.model) {
                        this.model.removeValidationTask(task);
                    }

                    _super.call(this);
                });

                /**
                 * Returns the link used for this relationship between Emails
                 * and EmailParticipants.
                 *
                 * @return {string}
                 */
                this.getLinkName = function() {
                    // There should only be one link in the collection field.
                    var link = this.def.links[0];

                    return _.has(link, 'name') ? link.name : link;
                };

                /**
                 * Adds properties to the model that the templates require.
                 *
                 * Select2 needs the `locked` property to determine if an item
                 * can be removed. This property is set to `true` if the field
                 * is in readonly mode.
                 *
                 * The `invalid` property is set on the model to indicate
                 * whether the email address is valid or invalid. This detail
                 * can be shared with the user and an email cannot be saved
                 * with invalid participants.
                 *
                 * The `href` property is set on the model as a pre-calculated
                 * URL for navigating to the parent record. This property is
                 * only defined if the user has access to the parent record.
                 *
                 * @param {Data.Bean} model
                 * @return {Data.Bean} The model is mutated. But it is also
                 * returned so that the caller can specify this function as a
                 * callback to a function that iterates over a collection of
                 * models.
                 */
                this.prepareModel = function(model) {
                    var hasParent = model.hasParent();
                    var parent;

                    if (hasParent) {
                        parent = model.getParent();
                    }

                    model.nameIsErased = model.isNameErased();
                    model.emailIsErased = model.isEmailErased();

                    // Select2 needs the locked property directly on the object.
                    model.locked = !!this.def.readonly;

                    /**
                     * The model is invalid if:
                     *
                     * - It doesn't have a `parent` and `email_address_id` is
                     * empty.
                     * - It has an `email_address_id` and `email_address`, and
                     * `email_address` is invalid.
                     * - The email address has been erased.
                     * - The server tells us that the email address is invalid.
                     */
                    if (!hasParent && !model.get('email_address_id')) {
                        model.invalid = true;
                    } else if (!model.has('invalid_email') &&
                        model.get('email_address_id') &&
                        model.get('email_address') &&
                        !model.emailIsErased
                    ) {
                        model.invalid = !app.utils.isValidEmailAddress(model.get('email_address'));
                    } else {
                        model.invalid = model.emailIsErased || !!model.get('invalid_email');
                    }

                    if (hasParent && parent && app.acl.hasAccessToModel('view', parent)) {
                        model.href = '#' + app.router.buildRoute(parent.module, parent.get('id'));
                    }

                    return model;
                };

                /**
                 * Returns a string representing the email participant in the
                 * format that would be used for an address in an email address
                 * header. Note that the name is not surrounded by quotes
                 * unless the `surroundNameWithQuotes` parameter is `true`.
                 *
                 * @example
                 * // With name and email address.
                 * Will Westin <will@example.com>
                 * @example
                 * // Without name.
                 * will@example.com
                 * @example
                 * // Surround name with quotes.
                 * "Will Westin" <will@example.com>
                 * @example
                 * // Name has been erased via a data privacy request.
                 * Value erased <will@example.com>
                 * @example
                 * // Email address has been erased via a data privacy request.
                 * Will Westin <Value erased>
                 * @deprecated Use
                 * {@link Model.Datas.Base.EmailParticipantsModel#toHeaderString}
                 * instead.
                 * @param {Data.Bean} model
                 * @param {boolean} [surroundNameWithQuotes=false]
                 * @return {string}
                 */
                this.formatForHeader = function(model, surroundNameWithQuotes) {
                    app.logger.warn('Plugins.EmailParticipants#formatForHeader is deprecated. Use ' +
                        'Model.Datas.Base.EmailParticipantsModel#toHeaderString instead.');

                    return model.toHeaderString({quote_name: surroundNameWithQuotes});
                };

                /**
                 * Get the base options for initializing Select2.
                 *
                 * @return {Object}
                 */
                this.getSelect2Options = function() {
                    var module = this.module;

                    return {
                        containerCss: {
                            width: '100%'
                        },
                        width: 'off',
                        minimumInputLength: 1,
                        selectOnBlur: true,
                        data: this.getFormattedValue(),

                        /**
                         * Begin with current value of the field.
                         *
                         * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                         *
                         * @param {jQuery} element
                         * @param {Function} callback
                         */
                        initSelection: _.bind(function(element, callback) {
                            callback(this.getFormattedValue());
                        }, this),

                        /**
                         * Cannot call the debounced function directly or else
                         * it will not be debounced.
                         *
                         * @param {Object} query See [Select2 Documentation of `query` parameter](http://ivaynberg.github.io/select2/#doc-query).
                         */
                        query: function(query) {
                            search(query);
                        },

                        /**
                         * Use `cid` as a choice's ID. Some models are not yet
                         * synchronized and can only be identified by their
                         * `cid`. All models have a `cid`.
                         *
                         * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                         *
                         * @param {Object} choice
                         * @return {null|string|number}
                         */
                        id: function(choice) {
                            return _.isEmpty(choice) ? null : choice.cid;
                        },

                        /**
                         * Create an additional option for the email address
                         * when the query returns no matches for the search
                         * term.
                         *
                         * An attempt is made to create an email address from
                         * the term, provided that the term is considered a
                         * valid email address. If the email address already
                         * exists, then its data is returned. Upon a successful
                         * request, the new choice is patched with the email
                         * address' ID so that we can link the email address.
                         * Until success, or in the event of a failure, the new
                         * choice will be seen as invalid. The user will not be
                         * able to save or send the email until the request has
                         * succeeded or the user has made a correction.
                         *
                         * If the email address already exists and is invalid
                         * or opted out, then the email address cannot be used
                         * and the new choice will not be updated.
                         *
                         * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                         *
                         * @fires change:<field> Indicates that the collection
                         * changed, when the email address is successfully
                         * created, to initiate the field's logic for rendering
                         * the pills and reapplying decoration for invalid
                         * participants. Only triggered if the choice has
                         * already been selected by the user to avoid rendering
                         * the Select2 data unnecessarily. If the choice is
                         * updated before the user selects it, then the field
                         * will handle rendering the change to the collection
                         * and decorating the pills at that time.
                         * @param {string} term
                         * @param {Array} data The options in the dropdown after the query
                         * callback has been executed.
                         * @return {undefined|Data.Bean}
                         */
                        createSearchChoice: _.bind(function(term, data) {
                            var choice;
                            var address;

                            if (data.length === 0 && app.utils.isValidEmailAddress(term)) {
                                choice = app.data.createBean('EmailParticipants', {
                                    _link: this.getLinkName(),
                                    email_address: term
                                });

                                address = app.data.createBean('EmailAddresses', {
                                    email_address: term
                                });
                                address.save({}, {
                                    success: _.bind(function(model) {
                                        var collection = this.model.get(this.name);

                                        choice.set({
                                            email_address_id: model.get('id'),
                                            invalid_email: model.get('invalid_email'),
                                            opt_out: model.get('opt_out')
                                        });
                                        this.prepareModel(choice);

                                        if (collection.get(choice)) {
                                            this.model.trigger('change:' + this.name, this.model, collection);
                                        }
                                    }, this)
                                });

                                return this.prepareModel(choice);
                            }
                        }, this),

                        /**
                         * Returns the localized message indicating that a
                         * search is in progress.
                         *
                         * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                         *
                         * @return {string}
                         */
                        formatSearching: function() {
                            return app.lang.get('LBL_LOADING', module);
                        },

                        /**
                         * Suppresses the message indicating the number of
                         * characters remaining before a search will trigger.
                         *
                         * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                         *
                         * @param {string} term
                         * @param {number} min
                         * @return {string}
                         */
                        formatInputTooShort: function(term, min) {
                            return '';
                        }
                    };
                };
            }
        });
    });
})(SUGAR.App);
