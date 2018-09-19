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
 * @class View.Layouts.Base.Emails.ComposeAddressbookLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsComposeAddressbookLayout
 * @extends View.Layout
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.view.Layout.prototype.initialize.call(this, options);
        this.collection.sync = this.sync;
        this.collection.allowed_modules = ['Accounts', 'Contacts', 'Leads', 'Prospects', 'Users'];
        this.context.on('compose:addressbook:search', this.search, this);
    },
    /**
     * Calls the custom Mail API endpoint to search for email addresses.
     *
     * @param {string} method
     * @param {Data.Bean} model
     * @param {Object} options
     */
    sync: function(method, model, options) {
        var callbacks;
        var url;
        var success;

        options = options || {};

        // only fetch from the approved modules
        if (_.isEmpty(options.module_list)) {
            options.module_list = ['all'];
        } else {
            options.module_list = _.intersection(this.allowed_modules, options.module_list);
        }

        // this is a hack to make pagination work while trying to minimize the affect on existing configurations
        // there is a bug that needs to be fixed before the correct approach (config.maxQueryResult vs. options.limit)
        // can be determined
        app.config.maxQueryResult = app.config.maxQueryResult || 20;
        options.limit = options.limit || app.config.maxQueryResult;

        // Is there already a success callback?
        if (options.success) {
            success = options.success;
        }

        // Map the response so that the email field data is packaged as an
        // array of objects. The email field component expects the data to be
        // in that format.
        options.success = function(data) {
            if (_.isArray(data)) {
                data = _.map(data, function(row) {
                    row.email = [{
                        email_address: row.email,
                        email_address_id: row.email_address_id,
                        opt_out: row.opt_out,
                        // The email address must be seen as the primary email
                        // address to be shown in a list view.
                        primary_address: true
                    }];

                    // Remove the properties that are now stored in the nested
                    // email array.
                    delete row.opt_out;
                    delete row.email_address_id;

                    return row;
                });
            }

            // Call the original success callback.
            if (success) {
                success(data);
            }
        };

        options = app.data.parseOptionsForSync(method, model, options);
        options.params.erased_fields = true;

        callbacks = app.data.getSyncCallbacks(method, model, options);
        this.trigger('data:sync:start', method, model, options);

        url = app.api.buildURL('Mail', 'recipients/find', null, options.params);
        app.api.call('read', url, null, callbacks);
    },
    /**
     * Adds the set of modules and term that should be used to search for recipients.
     *
     * @param {Array} modules
     * @param {String} term
     */
    search: function(modules, term) {
        // reset offset to 0 on a search. make sure that it resets and does not update.
        this.collection.fetch({query: term, module_list: modules, offset: 0, update: false});
    }
})
