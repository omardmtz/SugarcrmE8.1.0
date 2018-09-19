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
 * @class View.Views.Base.Quotes.CreateView
 * @alias SUGAR.App.view.views.BaseQuotesCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',

    /**
     * Holds the ProductBundles/Products/ProductBundleNotes fields meta for different views
     */
    moduleFieldsMeta: undefined,

    /**
     * Field map for where Opp/RLI fields (values) should map to Quote fields (keys)
     */
    convertToQuoteFieldMap: {
        Opportunities: {
            opportunity_id: 'id',
            opportunity_name: 'name'
        },
        RevenueLineItems: {
            name: 'name',
            opportunity_id: 'opportunity_id',
            opportunity_name: 'opportunity_name'
        },
        defaultBilling: {
            billing_account_id: 'account_id',
            billing_account_name: 'account_name'
        },
        defaultShipping: {
            shipping_account_id: 'account_id',
            shipping_account_name: 'account_name'
        }
    },

    /**
     * A list of billing field names to pull from the Account model to the Quote model
     */
    acctBillingToQuoteConvertFields: [
        'billing_address_city',
        'billing_address_country',
        'billing_address_postalcode',
        'billing_address_state',
        'billing_address_street'
    ],

    /**
     * A list of shiping field names to pull from the Account model to the Quote model
     */
    acctShippingToQuoteConvertFields: [
        'shipping_address_city',
        'shipping_address_country',
        'shipping_address_postalcode',
        'shipping_address_state',
        'shipping_address_street'
    ],

    /**
     * If this Create view is from converting items from other modules to Quotes, is this
     * converting from a 'shipping' or 'billing' subpanel, or undefined if neither.
     */
    isConvertFromShippingOrBilling: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['QuotesViewSaveHelper', 'LinkedModel']);
        var fromSubpanel = options.context.get('fromSubpanel');

        this._super('initialize', [options]);
        if (options.context.get('convert') && !fromSubpanel) {
            this._prepopulateQuote(options);
        } else if (fromSubpanel) {
            options.context.get('model').link = options.context.get('subpanelLink');
        }

        this.moduleFieldsMeta = {};

        this._buildMeta('ProductBundleNotes', 'quote-data-group-list');
        this._buildMeta('ProductBundles', 'quote-data-group-header');
        this._buildMeta('Products', 'quote-data-group-list');

        // gets the name of any field where calculated is true
        this.calculatedFields = _.chain(this.model.fields)
            .where({calculated: true})
            .pluck('name')
            .value();

        // Set the bundles as a separate model validation task so the Quote Record can validate by itself
        // then it calls the bundles validation
        this.model.addValidationTask('quote_bundles_' + this.cid, _.bind(this.validateBundleModels, this));
    },

    /**
     * Prepopulates the Quote context model with related module fields
     *
     * @param {Object} options The initialize options Object
     * @protected
     */
    _prepopulateQuote: function(options) {
        var parentModel = options.context.get('parentModel');
        var ctxModel = options.context.get('model');
        var parentModule = parentModel.module;
        var parentModelAcctIdFieldName = parentModule === 'Accounts' ? 'id' : 'account_id';
        var linkModel;
        var quoteData = {};
        var fieldMap;

        this.isConvertFromShippingOrBilling = undefined;

        if (ctxModel && parentModel) {
            linkModel = this.createLinkModel(parentModel, options.context.get('fromLink'));
            // get the JSON attributes of the linked model
            quoteData = linkModel.toJSON();

            // create a field map from the default fields and module-specific fields
            fieldMap = _.extend({}, this.convertToQuoteFieldMap[parentModule]);

            if (quoteData.shipping_account_id || quoteData.shipping_contact_id) {
                // if the linked model had any shipping_ fields, set it to 'shipping'
                this.isConvertFromShippingOrBilling = 'shipping';
                quoteData.copy = false;
            } else if (quoteData.billing_account_id || quoteData.billing_contact_id) {
                // if the linked model had any billing_ fields, set it to 'billing'
                this.isConvertFromShippingOrBilling = 'billing';
            }

            if (parentModule !== 'Accounts') {
                // since its not from an Acct shipping/billing link, add in the default Acct field mappings
                if (this.isConvertFromShippingOrBilling === 'shipping') {
                    fieldMap = _.extend(fieldMap, this.convertToQuoteFieldMap.defaultShipping);
                } else if (this.isConvertFromShippingOrBilling === 'billing') {
                    fieldMap = _.extend(fieldMap, this.convertToQuoteFieldMap.defaultBilling);
                } else {
                    fieldMap = _.extend(
                        fieldMap,
                        this.convertToQuoteFieldMap.defaultShipping,
                        this.convertToQuoteFieldMap.defaultBilling
                    );
                }
            }

            // copy field data from the parentModel to the quoteData object
            _.each(fieldMap, function(otherModuleField, quoteField) {
                quoteData[quoteField] = parentModel.get(otherModuleField);
            }, this);

            // make an api call to get related Account data
            app.api.call('read', app.api.buildURL('Accounts/' + parentModel.get(parentModelAcctIdFieldName)), null, {
                success: _.bind(this._setAccountInfo, this)
            });

            // set new quoteData attributes onto the create model
            ctxModel.set(quoteData);
        }
    },

    /**
     * Sets the related Account info on the Quote bean
     *
     * @param {Object} accountInfoData The Account info returned from the Accounts/:id endpoint
     * @protected
     */
    _setAccountInfo: function(accountInfoData) {
        var acctData = {};
        var fields = [];

        if (this.isConvertFromShippingOrBilling === 'shipping') {
            // if this is a shipping conversion, set the Account shipping fields
            fields = this.acctShippingToQuoteConvertFields;
        } else if (this.isConvertFromShippingOrBilling === 'billing') {
            // if this is a billing conversion, set the Account billing fields
            fields = this.acctBillingToQuoteConvertFields;
        } else {
            // if this is neither a shipping nor billing conversion,
            // set both Account shipping & billing fields
            fields = fields.concat(
                this.acctBillingToQuoteConvertFields,
                this.acctShippingToQuoteConvertFields
            );
        }

        _.each(fields, function(fieldName) {
            acctData[fieldName] = accountInfoData[fieldName];
        }, this);

        this.model.set(acctData);
    },

    /**
     * Builds the `this.moduleFieldsMeta` object
     *
     * @param {string} moduleName The module name to get meta for
     * @param {string} viewName The view name from the module to get view defs for
     * @private
     */
    _buildMeta: function(moduleName, viewName) {
        var viewMeta;
        var modMeta;
        var metaFields = {};
        var modMetaField;

        modMeta = app.metadata.getModule(moduleName);
        viewMeta = app.metadata.getView(moduleName, viewName);

        if (modMeta && viewMeta) {
            _.each(viewMeta.panels, function(panel) {
                _.each(panel.fields, function(field) {
                    modMetaField = modMeta.fields[field.name];
                    metaFields[field.name] = _.extend({}, modMetaField, field);
                }, this);
            }, this);

            this.moduleFieldsMeta[moduleName] = metaFields;
        }
    },

    /**
     * Validates the models in the Quote's ProductBundles
     *
     * @param {Object} fields The list of fields to validate.
     * @param {Object} recordErrors The errors object during this validation task.
     * @param {Function} callback The callback function to continue validation.
     */
    validateBundleModels: function(fields, recordErrors, callback) {
        var returnCt = 0;
        var totalItemsToValidate = 0;
        var bundles = this.model.get('bundles');
        var productBundleItems;
        var pbModelsAsyncCt = 0;

        recordErrors = recordErrors || {};

        if (bundles && bundles.length) {
            //Check to see if we have only the default group
            if (bundles.length === 1) {
                productBundleItems = bundles.models[0].get('product_bundle_items');
                //check to see if that group is empty, if so, return the valid status of the parent.
                if (productBundleItems.length === 0) {
                    callback(null, fields, recordErrors);
                    return;
                }
            }

            totalItemsToValidate += bundles.length;

            // get the count of items
            totalItemsToValidate = _.reduce(bundles.models, function(memo, bundle) {
                return memo + bundle.get('product_bundle_items').length;
            }, totalItemsToValidate);

            // loop through each ProductBundles bean
            _.each(bundles.models, function(bundleModel) {
                // call validate on the ProductBundle model (if group name were required or some other field)
                bundleModel.isValidAsync(this.moduleFieldsMeta[bundleModel.module], _.bind(function(isValid, errors) {
                    // increment the validate count
                    returnCt++;

                    // get the bundle items for this bundle to validate later
                    productBundleItems = bundleModel.get('product_bundle_items');

                    // add any errors returned to the main record errors
                    recordErrors = _.extend(recordErrors, errors);

                    if (!isValid) {
                        // if the bundleModel has bad fields,
                        // trigger the error on the bundle model
                        bundleModel.trigger('error:validation');
                    }

                    // add any product bundle items to the async count
                    pbModelsAsyncCt += productBundleItems.length;

                    if (productBundleItems.length === 0) {
                        // only try to use the callback here if this bundle is empty and
                        // there are no other bundle items async waiting to validate
                        if (pbModelsAsyncCt === 0 && returnCt === totalItemsToValidate) {
                            // if we've validated the correct number of models, call the callback fn
                            callback(null, fields, recordErrors);
                        }
                    }

                    // loop through each product_bundle_items Products/ProductBundleNotes bean
                    _.each(productBundleItems.models, function(pbModel) {
                        // call validate on the Product/ProductBundleNote model
                        pbModel.isValidAsync(this.moduleFieldsMeta[pbModel.module], _.bind(function(isValid, errors) {
                            // increment the validate count
                            returnCt++;
                            pbModelsAsyncCt--;

                            // add any errors returned to the main record errors
                            recordErrors = _.extend(recordErrors, errors);

                            if (!isValid) {
                                // if the qli/pbn has bad fields,
                                // trigger the error on the bundle model
                                pbModel.trigger('error:validation');
                            }

                            // trigger validation complete and process the errors for this model
                            pbModel.trigger('validation:complete', pbModel._processValidationErrors(errors));

                            if (errors.description) {
                                // if this is a ProductBundleNotes model where "description" field is required
                                // we have already triggered to process validation errors on the PBN model to show
                                // description is required, now we need to delete it off the error object
                                // so that the Quote record "description" field doesn't show as required since
                                // they have the same field name. So if errors.description (specifically checking
                                // if this model validation threw the error) then remove it off the recordErrors
                                // object that we're passing back
                                delete recordErrors.description;
                            }

                            if (returnCt === totalItemsToValidate) {
                                // if we've validated the correct number of models, call the callback fn
                                callback(null, fields, recordErrors);
                            }
                        }, this));
                    }, this);

                    bundleModel.trigger('validation:complete', bundleModel._processValidationErrors(errors));
                }, this));
            }, this);
        } else {
            // if there are no bundles to validate then just return
            callback(null, fields, recordErrors);
        }
    },

    /**
     * Overriding to make the router go back to previous view, not Quotes module list
     *
     * @inheritdoc
     */
    cancel: function() {
        //Clear unsaved changes on cancel.
        app.events.trigger('create:model:changed', false);
        this.$el.off();

        app.router.goBack();
    },

    /**
     * @inheritdoc
     */
    hasUnsavedChanges: function() {
        return this.hasUnsavedQuoteChanges();
    },

    /**
     * @inheritdoc
     */
    getCustomSaveOptions: function(options) {
        var parentSuccessCallback;
        var config = app.metadata.getModule('Opportunities', 'config');
        var bundles = this.model.get('bundles');
        var isConvert = this.context.get('convert');
        var hasItems = 0;

        if (isConvert) {
            _.each(bundles.models, function(bundle) {
                var pbItems = bundle.get('product_bundle_items');
                _.each(pbItems.models, function(itemModel) {
                    if (itemModel.module === 'Products' && itemModel.get('revenuelineitem_id')) {
                        hasItems++;
                    }
                }, this);
            }, this);
        }

        if (config && config.opps_view_by === 'RevenueLineItems' && isConvert && hasItems) {
            parentSuccessCallback = options.success;
            options.success = _.bind(this._customQuotesCreateSave, this, parentSuccessCallback);
        }

        return options;
    },

    /**
     * Checks all Products in bundles to make sure each Product has quote_id set
     * then calls the main success function that was passed in from base Create view
     *
     * @private
     */
    _customQuotesCreateSave: function(parentSuccessCallback, model) {
        var quoteId = model.get('id');
        var bundles = model.get('bundles');
        var rliId;
        var pbItems;
        var bulkRequest;
        var bulkUrl;
        var bulkCalls = [];

        _.each(bundles.models, function(pbModel) {
            pbItems = pbModel.get('product_bundle_items');

            _.each(pbItems.models, function(itemModel) {
                if (itemModel.module === 'Products') {
                    rliId = itemModel.get('revenuelineitem_id');

                    if (rliId) {
                        bulkUrl = app.api.buildURL('RevenueLineItems/' + rliId + '/link/quotes/' + quoteId);
                        bulkRequest = {
                            url: bulkUrl.substr(4),
                            method: 'POST',
                            data: {
                                id: rliId,
                                link: 'quotes',
                                relatedId: quoteId,
                                related: {
                                    quote_id: quoteId
                                }
                            }
                        };
                        bulkCalls.push(bulkRequest);
                    }
                }
            }, this);
        }, this);

        if (bulkCalls.length) {
            app.api.call('create', app.api.buildURL(null, 'bulk'), {
                requests: bulkCalls
            }, {
                success: parentSuccessCallback
            });
        }
    }
})
