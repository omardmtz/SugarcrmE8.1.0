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
    app.events.on("app:init", function() {

        /**
         * This plugin enables mass-quoting for RevenueLineItems (for use in Opps and QLIs)
         */
        app.plugins.register('MassQuote', ['view'], {
            /**
             * Fields to remove off the RLI model before converting to QLI
             */
            blacklistRLIFields: ['_acl', '_module', 'id'],

            /**
             * Attach code for when the plugin is registered on a view
             *
             * @param component
             * @param plugin
             */
            onAttach: function(component, plugin) {
                this.once('init', function() {
                    this.layout.on('list:massquote:fire', this.massQuote, this);
                }, this);
            },

            /**
             * Logic to convert multiple Revenue Line Items into a Quote
             */
            massQuote: function() {
                var qliModels = [];
                var rliObj;
                var loadViewObj;
                var parentModel;

                if (_.isFunction(this.hideAll)) {
                    this.hideAll();
                }

                var massQuote = this.context.get('mass_collection');
                var errors = {
                    'LBL_CONVERT_INVALID_RLI_PRODUCT_PLURAL': [],
                    'LBL_CONVERT_INVALID_RLI_ALREADYQUOTED_PLURAL': []
                };
                var messageTpl;

                // find any blockers
                var invalidItems = massQuote.filter(function(model) {
                    // if product template is empty, but category is not, this RLI can not be converted to a quote
                    if (_.isEmpty(model.get('product_template_id')) && !_.isEmpty(model.get('category_id'))) {
                        errors['LBL_CONVERT_INVALID_RLI_PRODUCT_PLURAL'].push(model);
                        return true;
                    } else if (!_.isEmpty(model.get('quote_id'))) {
                        errors['LBL_CONVERT_INVALID_RLI_ALREADYQUOTED_PLURAL'].push(model);
                        return true;
                    }

                    // we don't want valid items in this array
                    return false;
                }, this);

                if (!_.isEmpty(invalidItems)) {
                    messageTpl = app.template.getView('massupdate.invalid_link', this.module);

                    _.each(errors, function(val, key) {
                        if(val.length != 0) {
                            var messages = [];

                            messages.push(app.lang.get(key, this.module) + '<br>');

                            _.each(val, function(item) {
                                messages.push(messageTpl(item.attributes));
                            });

                            app.alert.show(('invalid_items_' + key), {
                                level: 'error',
                                title: app.lang.get('LBL_ALERT_TITLE_ERROR', this.module) + ':',
                                messages: messages,
                                onLinkClick: function() {
                                    app.alert.dismiss('invalid_items_' + key);
                                }
                            });
                        }
                    }, this);

                    return;
                }

                if (massQuote) {
                    // remove unnecessary fields
                    _.each(massQuote.models, function(rliModel) {
                        rliObj = rliModel.toJSON();
                        rliObj.revenuelineitem_id = rliObj.id;

                        _.each(this.blacklistRLIFields, function(fieldName) {
                            delete rliObj[fieldName];
                        }, this);

                        if (_.isEmpty(rliObj.product_template_name)) {
                            // if product_template_name is empty, use the RLI's name
                            rliObj.product_template_name = rliObj.name;
                        } else {
                            // if product_template_name is not empty, set that to the RLI's name
                            rliObj.name = rliObj.product_template_name;
                        }

                        if (_.isEmpty(rliObj.discount_price)) {
                            // if discount_price is empty, make it likely_Case
                            rliObj.discount_price = rliObj.likely_case;
                        }

                        rliObj.discount_select = false;
                        if (_.isEmpty(rliObj.discount_amount)) {
                            rliObj.discount_amount = 0.00;
                            // if discount_amount is '0' or '', set discount_select true
                            rliObj.discount_select = true;
                        }

                        qliModels.push(app.data.createBean('Products', rliObj));
                    }, this);

                    loadViewObj = {
                        module: 'Quotes',
                        layout: 'create',
                        action: 'edit',
                        convert: true,
                        create: true,
                        relatedRecords: qliModels,
                        fromSubpanel: massQuote.fromSubpanel || false,
                        subpanelLink: massQuote.link
                    };

                    // for Opps->RLI subpanel, context.parent model contains the Opps record model
                    // for RLI Record view, context model contains the RLI record model
                    parentModel = this.context.parent ? this.context.parent.get('model') : this.context.get('model');
                    // set the record model to be the parentModel
                    loadViewObj.parentModel = parentModel;

                    // if the context has a link param 'quotes', 'quotes_shipto', etc
                    // set it on the view object so the create view knows where this is coming from
                    loadViewObj.fromLink = this.context.get('link');

                    // Load the Quotes create view
                    app.controller.loadView(loadViewObj);
                    // update the browser URL with the proper
                    app.router.navigate('#Quotes/create', {trigger: false});
                }
            }
        });
    });
})(SUGAR.App);
