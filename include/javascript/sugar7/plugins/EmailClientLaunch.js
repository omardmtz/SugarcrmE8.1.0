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

(function (app) {
    /**
     * Extract and return the email address from the recipient.
     *
     * @param {Object} recipient
     * @param {Data.Bean} [recipient.email] An EmailAddresses bean.
     * @param {Data.Bean} [recipient.bean] A bean with an email address (e.g.,
     * Contacts, Leads, Users, etc.).
     * @return {Data.Bean} An EmailAddresses bean.
     */
    function getEmailAddress(recipient) {
        var email = app.data.createBean('EmailAddresses');

        if (recipient.email) {
            if (_.isString(recipient.email) && !_.isEmpty(recipient.email)) {
                app.logger.warn(
                    'EmailClientLaunch Plugin: An email address string was provided. An EmailAddresses bean was ' +
                    'expected.'
                );
                email.set('email_address', recipient.email);
            } else if (recipient.email instanceof app.Bean && recipient.email.module === 'EmailAddresses') {
                // If there is no `id` or `email_address`, then fall back to
                // using `recipient.bean`, if available.
                if (!recipient.email.isNew() || recipient.email.get('email_address')) {
                    // The email address was specified, so use it.
                    return recipient.email;
                }
            } else {
                app.logger.warn(
                    'EmailClientLaunch Plugin: An unknown email address type was provided. An EmailAddresses bean ' +
                    'was expected.'
                );
            }
        }

        if (recipient.bean && recipient.bean instanceof app.Bean && !email.get('email_address')) {
            email.set('email_address', app.utils.getPrimaryEmailAddress(recipient.bean));
        }

        return email;
    }

    app.events.on("app:init", function () {
        app.plugins.register('EmailClientLaunch', ['view', 'field'], {

            events: {
                'click a[data-action="email"]': 'launchEmailClient'
            },

            /**
             * If Sugar Email Client used, launch email compose drawer
             *
             * @param event
             */
            launchEmailClient: function(event) {
                var $link = $(event.currentTarget);

                if (this.useSugarEmailClient()) {
                    this.launchSugarEmailClient(this._retrieveEmailOptions($link));
                }
            },

            /**
             * Open the email compose drawer, prepopulated with given options
             *
             * @fires emailclient:close on the component after the drawer is
             * closed to allow a custom action to be performed.
             * @param {Object} [options]
             */
            launchSugarEmailClient: function(options) {
                //clean the recipient fields before handing off to email compose
                _.each(['to', 'cc', 'bcc'], function(recipientType) {
                    var recipients;

                    if (options[recipientType]) {
                        recipients = this._retrieveValidRecipients(options[recipientType]);
                        options[recipientType] = _.map(recipients, function(recipient) {
                            recipient.set('_link', recipientType);

                            return recipient;
                        });
                    }
                }, this);

                app.utils.openEmailCreateDrawer(
                    'compose-email',
                    options,
                    _.bind(function(context, model) {
                        if (model) {
                            var controllerContext = app.controller.context;
                            var controllerContextModule = controllerContext.get('module');
                            var links;

                            this.trigger('emailclient:close');

                            if (controllerContextModule === 'Emails' && controllerContext.get('layout') === 'records') {
                                // Refresh the current list view if it is the
                                // Emails list view.
                                controllerContext.reloadData();
                            } else {
                                // Refresh Emails subpanels if there are any.
                                links = app.utils.getLinksBetweenModules(controllerContextModule, 'Emails');

                                _.each(links, function(link) {
                                    controllerContext.trigger('panel-top:refresh', link.name);
                                });
                            }
                        }
                    }, this)
                );
            },

            /**
             * Return recipient list for email compose drawer
             *
             * @param {Array|Object} recipients
             * @return {Array}
             * @private
             */
            _retrieveValidRecipients: function(recipients) {
                var validRecipients = [];

                recipients = recipients || [];

                if (!_.isArray(recipients)) {
                    recipients = [recipients];
                }

                _.each(recipients, function(recipient) {
                    var validRecipient = app.data.createBean('EmailParticipants');
                    var email = getEmailAddress(recipient);
                    var primary;
                    var isNameErased = false;
                    var isEmailErased = false;

                    // We can only use the email address if it has an `id`.
                    if (!email.isNew()) {
                        isEmailErased = _.contains(email.get('_erased_fields') || [], 'email_address');
                        validRecipient.set({
                            email_addresses: app.utils.deepCopy(email),
                            email_address_id: email.get('id'),
                            email_address: email.get('email_address'),
                            invalid_email: email.get('invalid_email'),
                            opt_out: email.get('opt_out')
                        });
                    }

                    if (recipient.bean) {
                        primary = app.utils.getPrimaryEmailAddress(recipient.bean);
                        isNameErased = app.utils.isNameErased(recipient.bean);

                        // Set the parent data if the email address is already
                        // defined. Otherwise, only set the parent data if the
                        // bean's primary email address is valid. We can't send
                        // an email to a bean without a valid email address.
                        if (validRecipient.get('email_address_id') || app.utils.isValidEmailAddress(primary)) {
                            validRecipient.set({
                                parent: _.extend({type: recipient.bean.module}, app.utils.deepCopy(recipient.bean)),
                                parent_type: recipient.bean.module,
                                parent_id: recipient.bean.get('id'),
                                parent_name: app.utils.getRecordName(recipient.bean)
                            });
                        }
                    }

                    // Remove the email address if it has been erased, but only
                    // if there is a person that the email can be sent to. If
                    // there isn't a person, then we want the email address to
                    // be seen as invalid when composing the email.
                    if (validRecipient.get('parent') && !isNameErased && isEmailErased) {
                        validRecipient.unset('email_addresses');
                        validRecipient.unset('email_address_id');
                        validRecipient.unset('email_address');
                        validRecipient.unset('invalid_email');
                        validRecipient.unset('opt_out');
                    }

                    // We must have a person or an email address to send to.
                    if (validRecipient.get('email_address_id') || validRecipient.get('parent')) {
                        validRecipients.push(validRecipient);
                    }
                }, this);

                return validRecipients;
            },

            /**
             * Has the user opted to use the Sugar Email Client
             *
             * @returns {boolean}
             */
            useSugarEmailClient: function() {
                var emailClientPreference = app.user.getPreference('email_client_preference');

                return (emailClientPreference && emailClientPreference.type === 'sugar' && app.acl.hasAccess('edit', 'Emails'));
            },

            /**
             * Adds email options to `this.emailOptions`. If any of the keys
             * already exist in `this.emailOptions`, then the value is
             * replaced.
             *
             * Any keys with undefined values are removed before they are
             * added.
             *
             * @param {Object} [options] Attributes to set on the email.
             * @param {Array} [options.outbound_email_id] The email account to
             * use to send the email.
             * @param {Array} [options.to] The recipients in the To field.
             * @param {Array} [options.cc] The recipients in the CC field.
             * @param {Array} [options.bcc] The recipients in the BCC field.
             * @param {string} [options.name] The email's subject.
             * @param {string} [options.description] The email's plain-text
             * body.
             * @param {string} [options.description_html] The email's HTML
             * body.
             * @param {Array} [options.attachments] The email's attachments.
             * @param {Data.Bean} [options.related] The record to which the
             * email is related. The model is cloned so the original model
             * is not modified.
             * @param {Array} [options.team_name] The teams assigned to the
             * email.
             * @param {string} [options.assigned_user_id] The ID of the
             * assigned user.
             * @param {string} [options.assigned_user_name] The name of the
             * assigned user.
             * @param {boolean} [options.skip_prepopulate_with_case] Prevent
             * prepopulating case data in the email.
             */
            addEmailOptions: function(options) {
                this.emailOptions = this.emailOptions || {};
                options = options || {};

                // Ignore the related bean if it doesn't have a module.
                if (options.related && !options.related.module) {
                    options.related = undefined;
                }

                this.emailOptions = _.extend({}, this.emailOptions, options);

                // Removes undefined key/value pairs.
                this.emailOptions = _.reduce(this.emailOptions, function(memo, value, key) {
                    if (!_.isUndefined(value)) {
                        memo[key] = value;
                    }

                    return memo;
                }, {});
            },

            /**
             * Returns a copy of the related model for adding to email options
             *
             * @param {Data.Bean} model
             */
            _cloneRelatedModel: function(model) {
                return app.data.createBean(model.module, app.utils.deepCopy(model));
            },

            /**
             * Get appropriate href value based on the email client
             *
             * @param options
             * @returns {String}
             * @private
             */
            _getEmailHref: function(options) {
                if (this.useSugarEmailClient()) {
                    return 'javascript:void(0)';
                } else {
                    return this._buildMailToURL(options);
                }
            },

            /**
             * Build a mailto: url using the given options
             *
             * @param {Object} [options] Optional email field values to pass to the email client
             * @param {Array} [options.to]
             * @param {Array} [options.cc]
             * @param {Array} [options.bcc]
             * @param {string} [options.name] Subject
             * @param {string} [options.description] Text Body
             */
            _buildMailToURL: function(options) {
                var mailToUrl = 'mailto:',
                    formattedOptions = {},
                    queryParams = [];

                if (options.to) {
                    mailToUrl += this._formatRecipientsToString(options.to);
                }

                formattedOptions.cc = this._formatRecipientsToString(options.cc);
                formattedOptions.bcc = this._formatRecipientsToString(options.bcc);
                formattedOptions.subject = options.name;
                formattedOptions.body = options.description;

                _.each(['cc', 'bcc', 'subject', 'body'], function(option) {
                    var param;
                    if (!_.isEmpty(formattedOptions[option])) {
                        param = option + '=' + encodeURIComponent(formattedOptions[option]);
                        queryParams.push(param);
                    }
                });

                if (!_.isEmpty(queryParams)) {
                    mailToUrl = mailToUrl + '?' + queryParams.join('&');
                }

                return mailToUrl;
            },

            /**
             * Turns a single recipient or list of recipients
             * into a comma separated list of recipient email addresses
             * Useful for producing string for mailto: recipients
             *
             * @param {string|Array} recipients
             * @returns {string}
             * @private
             */
            _formatRecipientsToString: function(recipients) {
                var emails = [];

                recipients = recipients || [];

                if (!_.isArray(recipients)) {
                    recipients = [recipients];
                }

                _.each(recipients, function(recipient) {
                    var email = getEmailAddress(recipient);

                    if (email.get('email_address')) {
                        emails.push(email.get('email_address'));
                    }
                }, this);

                return emails.join(',');
            },

            /**
             * Build email options object
             * Use options on controller as a base and lay link specific options on top
             *
             * @param $link jQuery selected link element
             * @returns {Object}
             * @private
             */
            _retrieveEmailOptions: function($link) {
                var optionsFromLink = $link.data() || {};
                var optionsFromController = this.emailOptions || {};
                var options = {};

                // allow the component implementing this plugin to override optionsFromLink
                // allows us to pass more complex data like models, which are not easily
                // passed via data- attributes.
                if (_.isFunction(this._retrieveEmailOptionsFromLink)) {
                    optionsFromLink = this._retrieveEmailOptionsFromLink($link);
                }

                options = _.extend(options, optionsFromController, optionsFromLink);

                if (options.related) {
                    options.related = this._cloneRelatedModel(options.related);
                }

                return options;
            },

            /**
             * Updates all the links in the view with the proper href from the current model
             */
            updateEmailLinks: function() {
                var self = this;
                var $emailLinks = this.$('a[data-action="email"]');

                $emailLinks.each(function() {
                    var options = self._retrieveEmailOptions($(this));
                    var href = self._getEmailHref(options);
                    $(this).attr('href', href);
                });
            },

            /**
             * @inheritdoc
             *
             * On render, set each email link's href attribute to a mailto for
             * users that use an external email client and javascript:void(0)
             * for users that use Sugar's email compose.
             *
             * On init, set up a listener for changes to the component's model
             * that updates the email options on those changes. Some components
             * are in child context's, like subpanels, and use the parent
             * context's model.
             *
             * A component can implement any of the following methods to
             * customize what data is provided to the plugin. Each method takes
             * a model as a parameter. That model is the same model that the
             * plugin is using to gather data. Components should make sure they
             * use this model when producing the value they give to the plugin.
             * Return `undefined` to prevent an email option from being set.
             *
             * @example
             * emailOptionTo
             * Returns an array of recipients to be added to the email's To
             * field.
             *
             * @example
             * emailOptionCc
             * Returns an array of recipients to be added to the email's CC
             * field.
             *
             * @example
             * emailOptionBcc
             * Returns an array of recipients to be added to the email's BCC
             * field.
             *
             * @example
             * emailOptionSubject
             * Returns a string to be used as the email's subject.
             *
             * @example
             * emailOptionDescription
             * Returns a string to be used as the email's plain-text body.
             *
             * @example
             * emailOptionDescriptionHtml
             * Returns a string to be used as the email's HTML body.
             *
             * @example
             * emailOptionAttachments
             * Returns an array of attachments to be attached to the email.
             *
             * @example
             * emailOptionRelated
             * Returns a bean to be used as the email's related record.
             *
             * @example
             * emailOptionTeams
             * Returns an array of teams to be used as the email's teams.
             */
            onAttach: function() {
                var updateEmailOptions = _.bind(function(model) {
                    var options = {};

                    if (_.isFunction(this.emailOptionTo)) {
                        options.to = this.emailOptionTo(model);
                    }

                    if (_.isFunction(this.emailOptionCc)) {
                        options.cc = this.emailOptionCc(model);
                    }

                    if (_.isFunction(this.emailOptionBcc)) {
                        options.bcc = this.emailOptionBcc(model);
                    }

                    if (_.isFunction(this.emailOptionSubject)) {
                        options.name = this.emailOptionSubject(model);
                    }

                    if (_.isFunction(this.emailOptionDescription)) {
                        options.description = this.emailOptionDescription(model);
                    }

                    if (_.isFunction(this.emailOptionDescriptionHtml)) {
                        options.description_html = this.emailOptionDescriptionHtml(model);
                    }

                    if (_.isFunction(this.emailOptionAttachments)) {
                        options.attachments = this.emailOptionAttachments(model);
                    }

                    if (_.isFunction(this.emailOptionRelated)) {
                        options.related = this.emailOptionRelated(model);
                    }

                    if (_.isFunction(this.emailOptionTeams)) {
                        options.team_name = this.emailOptionTeams(model);
                    }

                    this.addEmailOptions(options);
                }, this);

                this.on('init', function() {
                    var self = this;
                    var context = this.context.parent || this.context;
                    var model = context.get('model');
                    var events = [
                        'change',
                        'change:from_collection',
                        'change:to_collection',
                        'change:cc_collection',
                        'change:bcc_collection',
                        'change:attachments_collection'
                    ];
                    var onChange = _.debounce(function(model) {
                        updateEmailOptions(model);
                        self.render();
                    }, 200);

                    if (model instanceof app.Bean) {
                        this.listenTo(model, events.join(' '), onChange);
                    }

                    updateEmailOptions(model);
                }, this);

                this.on('render', this.updateEmailLinks, this);
            }
        });
    });
})(SUGAR.App);
