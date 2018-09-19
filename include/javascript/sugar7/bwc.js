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
    /**
     * Backwards compatibility (Bwc) class manages all required methods for BWC
     * modules.
     *
     * A BWC module is defined in the metadata by the `isBwcEnabled` property.
     *
     * @class Sugar.Bwc
     * @singleton
     * @alias SUGAR.App.bwc
     */
    var Bwc = {
        /**
         * Performs backward compatibility login.
         *
         * The OAuth token is passed and we do automatic in bwc mode by
         * getting a cookie with the PHPSESSIONID.
         */

        /**
         * Logs into sugar in BWC mode. Allows for use of current OAuth token as
         * a session id for backward compatible modules.
         * 
         * @param  {String} redirectUrl A URL to redirect to after logging in
         * @param  {Function} callback A function to call after logging in
         * @return {Void}
         */
        login: function(redirectUrl, callback) {
            var url = app.api.buildURL('oauth2', 'bwc/login');
            return app.api.call('create', url, {}, {
                success: function(data) {
                    // Set the session name into the cache so that certain bwc
                    // modules can access it as needed (studio)
                    if (data && data.name) {
                        app.cache.set('SessionName', data.name);
                    }

                    // If there was a callback, call it. This will almost always
                    // be used exclusively by studio when trying to refresh the
                    // session after it expires.
                    if (callback) {
                        callback();
                    }

                    // If there was a redirectUrl passed, go there. This will
                    // almost always be the case, except in studio when a login
                    // is simply updating the session id
                    if (redirectUrl) {
                        //BR-4054: Try and prevent run away login loops
                        if (redirectUrl.indexOf('&bwcRedirect=1') > -1) {
                            app.error.handleInvalidClientError();
                        } else {
                            app.router.navigate('#bwc/' + redirectUrl + '&bwcRedirect=1', {trigger: true});
                        }
                    }
                }
            });
        },

        /**
         * Translates an action to a BWC action.
         *
         * If the action wasn't found to be translated, the given action is
         * returned.
         *
         * @param {String} action The action to translate to a BWC one.
         * @return {String} The BWC equivalent action.
         */
        getAction: function(action) {
            var bwcActions = {
                'create': 'EditView',
                'edit': 'EditView',
                'detail': 'DetailView'
            };

            return bwcActions[action] || action;
        },

        /**
         * Builds a backwards compatible route. For example:
         * bwc/index.php?module=MyModule&action=DetailView&record12345
         *
         * @param {String} module The name of the module.
         * @param {String} [id] The model's ID.
         * @param {String} [action] Backwards compatible action name.
         * @param {Object} [params] Extra params to be sent on the bwc link.
         * @return {String} The built route.
         */
        buildRoute: function(module, id, action, params) {

            /**
             * app.bwc.buildRoute is for internal use and we control its callers, so we're
             * assuming callers will provide the module param which is marked required!
             */
            var href = 'bwc/index.php?',
                params = _.extend({}, { module: module }, params);

            if (!action && !id || action==='DetailView' && !id) {
                params.action = 'index';
            } else {
                if (action) {
                    params.action = action;
                } else {
                    //no action but we do have id
                    params.action = 'DetailView';
                }
                if (id) {
                    params.record = id;
                }
            }
            return href + $.param(params);
        },
        /**
         * For BWC modules, we need to get URL params for creating the related record
         * @returns {Object} BWC URL parameters
         * @private
         */
        _createRelatedRecordUrlParams: function(parentModel, link) {
            var params = {
                parent_type: parentModel.module,
                parent_name: parentModel.get('name')
                                || parentModel.get('full_name')
                                || app.utils.formatNameLocale(parentModel.attributes),
                parent_id: parentModel.get("id"),
                return_module: parentModel.module,
                return_id: parentModel.get("id"),
                return_name: parentModel.get('name')
                                || parentModel.get('full_name')
                                || app.utils.formatNameLocale(parentModel.attributes)
            };

            // find relationship name
            var linkField = _.find(parentModel.fields, function(field) {
                return (field.type == 'link' && field.name == link)
            });

            if (linkField) {
                params['return_relationship'] = linkField.relationship;
            }

            //Handle special cases
            params = this._handleRelatedRecordSpecialCases(params, parentModel, link);

            //Set relate field values as part of URL so they get pre-filled
            var fields = app.data.getRelateFields(parentModel.module, link);
            _.each(fields, function(field){
                params[field.name] = parentModel.get(field.rname) || app.utils.formatNameLocale(parentModel.attributes);
                params[field.id_name] = parentModel.get("id");
                if(field.populate_list) {
                    // We need to populate fields from parent record into new related record
                    _.each(field.populate_list, function (target, source) {
                        source = _.isNumber(source) ? target : source;
                        if (!_.isUndefined(parentModel.get(source))) {
                            params[target] = parentModel.get(source);
                        }
                    }, this);
                }
            });
            return params;
        },
        /**
         * Handles special cases when building the related record URL.
         * @return {Object} BWC URL parameters taking edge cases in to consideration
         * @private
         */
        _handleRelatedRecordSpecialCases: function(params, parentModel, link) {
            // We should pull the value from the syncedAttributes as they are what comes back from the server on load
            // and after a save.  The reason for this is the following use case.
            // On an opportunity, change the account but *don't save* and then press the + button on the quote subpanel
            // it will take the unsaved value and use it when creating the quote, if you press confirm to the
            // "Are you sure" dialog.  By using the syncedAttributes, it won't take any unsaved values,
            // but on the off chance that the value doesn't exist in the synced attributes, it will fall back to the
            // parentModel
            var syncedAttributes = parentModel.getSynced();
            //Special case for Contacts->meetings. The parent should be the account rather than the contact
            if (parentModel.module == 'Contacts' &&
                    parentModel.has('account_id') &&
                    (link == 'meetings' || link == 'calls')
                ) {
                params = _.extend(params, {
                    parent_type: 'Accounts',
                    parent_id: syncedAttributes.account_id || parentModel.get('account_id'),
                    account_id: syncedAttributes.account_id || parentModel.get('account_id'),
                    account_name: syncedAttributes.account_name || parentModel.get('account_name'),
                    parent_name: syncedAttributes.account_name || parentModel.get('account_name'),
                    contact_id: syncedAttributes.id || parentModel.get('id'),
                    contact_name: syncedAttributes.full_name || parentModel.get('full_name')
                });
            }
            //SP-1600: Account information is not populated during Quote creation via Opportunity Quote Subpanel
            // any time we link to quotes and we have an account_id, we should always populate it.
            if (link == 'quotes' || link == 'contracts') {
                if (parentModel.has('account_id')) {
                    //Note that the bwc view will automagically give us billing/shipping and only
                    //expects us to set account_id and account_name here
                    params = _.extend(params, {
                        account_id: syncedAttributes.account_id || parentModel.get('account_id'),
                        account_name: syncedAttributes.account_name || parentModel.get('account_name')
                    });
                }

                if (parentModel.module === 'Contacts') {
                    // if we are coming from the contacts module, we need to get the id and set it
                    // we don't need the syncedAttribute here as id will never change on the front end.
                    params = _.extend(params, {
                        contact_id: parentModel.get('id')
                    });
                } else if (parentModel.has('contact_id')) {
                    params = _.extend(params, {
                        contact_id: syncedAttributes.contact_id || parentModel.get('contact_id')
                    });
                }
            }
            return params;
        },

        /**
         * Route to Create Related record UI for a BWC module
         */
        createRelatedRecord: function(module, parentModel, link, id) {
            var params = this._createRelatedRecordUrlParams(parentModel, link);
            var route = app.bwc.buildRoute(module, id || null, "EditView", params);
            app.router.navigate("#" + route, {trigger: true}); // Set route so that we switch over to BWC mode
        },

        /**
         * Enables the ability to share a record from a BWC module.
         *
         * This will trigger the sharing action already defined in the
         * {@link BaseShareactionField#share()}.
         *
         * @param {String} module The module that we are sharing.
         * @param {String} id The record id that we are sharing.
         * @param {String} name The record name that we are sharing.
         */
        shareRecord: function(module, id, name) {
            // Unfortunately we cannot create fields without views, so we need a
            // container view here.
            var containerView = app.view.createView({});
            var shareField = app.view.createField({
                    def: {
                        type: 'shareaction'
                    },
                    module: module,
                    model: app.data.createBean(module, {
                        id: id,
                        name: name
                    }),
                    view: containerView
                });

            if (shareField.useSugarEmailClient()) {
                shareField.shareWithSugarEmailClient();
            } else {
                this._launchExternalEmail(shareField.getShareMailtoUrl());
            }

            // Need to dispose these dynamically-created components.
            // Disposing the view will dispose the field as well.
            containerView.dispose();
        },

        /**
         * Launch a mailto via javascript
         * Yes, this is a hack, but strategically placed in BWC so it will go away.
         * If you have a better solution, please fix this.
         * Note: doing window.location.href = 'mailto:'; window.close(); has timing problems.
         * Also, reworking Smarty sugar_button function code has its own set of challenges.
         *
         * @param mailto
         * @private
         */
        _launchExternalEmail: function(mailto) {
            var tempMailTo = $('<a href="' + mailto + '"></a>').appendTo('body');
            tempMailTo.get(0).click();
            tempMailTo.remove();
        },

        /**
         * Revert bwc model attributes in order to skip warning unsaved changes.
         */
        revertAttributes: function() {
            var view = app.controller.layout.getComponent('bwc');
            if (!view) {
                return;
            }
            view.revertBwcModel();
        }
    };
    app.augment('bwc', _.extend(Bwc, Backbone.Events), false);
})(SUGAR.App);
