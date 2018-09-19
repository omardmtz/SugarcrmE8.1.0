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
         * QuotesViewSaveHelper is a helper function for multiple Quotes views.
         */
        app.plugins.register('QuotesViewSaveHelper', ['view'], {

            /**
             * Extends and wraps existing RecordView/CreateView hasUnsavedChanges functionality
             * to check and include the Quote's Product Bundles and their related items
             *
             * @return {boolean}
             */
            hasUnsavedQuoteChanges: function() {
                var modelDefaults;
                var differenceKeys;
                var hasUnsavedChanges;
                // keep track of the current values
                var currentNoEditFields = this.noEditFields;

                // need to ignore the calculated fields because they haven't been updated from the server
                // and also ignore the bundles collection field since we are going to check it below manually
                this.noEditFields = this.noEditFields.concat(this.calculatedFields, ['bundles']);

                hasUnsavedChanges = this._super('hasUnsavedChanges');

                if (hasUnsavedChanges) {
                    // get the Quotes bean defaults
                    modelDefaults = this.model.getDefault() || {};
                    // get the diff between the attribs that exist on the model and the model defaults
                    differenceKeys = _.difference(_.keys(this.model.attributes), _.keys(modelDefaults));

                    if (differenceKeys.length) {
                        // if there is a differences between the model default fields and the attributes
                        // currently on the model, check those keys vs the calculated noEditFields
                        differenceKeys = _.difference(differenceKeys, this.noEditFields);

                        if (!differenceKeys.length) {
                            // if the fields that were different are no longer different,
                            // there are no unsaved changes from the main model
                            hasUnsavedChanges = false;
                        }
                    }
                }

                // if we don't have unsaved changes on the quote, check the bundles and their items,
                // but only do this if we aren't on the create view since the new bundle is always
                // going to flag as unsaved.
                if (hasUnsavedChanges === false && this.type != 'create') {
                    hasUnsavedChanges = this.hasUnsavedBundleChanges();
                }

                // set the noEditFields back to the way they were before this check
                this.noEditFields = currentNoEditFields;

                return hasUnsavedChanges;
            },

            /**
             * Checks the Quote's ProductBundles and items for changes
             *
             * @return {boolean}
             */
            hasUnsavedBundleChanges: function() {
                var bundleCalculatedFields;
                var changedBundle;
                var itemCalculatedFields = {};
                var bundles = this.model.get('bundles');
                var changedFields = function(model) {
                    return model.changedAttributes(model.getSynced());
                };
                var unsavedBundles = _.find(bundles.models, function(model) {
                    return model.isNew();
                });

                // if this is the create view and there are more than one bundle, it has unsaved changes
                if (this.context.get('create') === true && bundles.length > 1 && unsavedBundles) {
                    return true;
                }

                changedBundle = bundles.find(function(bundle) {
                    var bundleChanged;
                    var items;
                    var changedItem;
                    var keysToOmit;
                    var changedFieldsList;

                    if (_.isUndefined(bundleCalculatedFields)) {
                        bundleCalculatedFields = _.chain(bundle.fields)
                            .where({calculated: true})
                            .pluck('name')
                            .value();
                    }

                    keysToOmit = ['product_bundle_items', '_justSaved'].concat(bundleCalculatedFields);
                    // VirtualCollection will return the collection as changed if something was added and saved
                    // or canceled, just ignore it since all the items have checked the models to see if they changed
                    bundleChanged = !_.isEmpty(_.omit(changedFields(bundle), keysToOmit));

                    if (bundleChanged === false && bundle.has('product_bundle_items')) {
                        // the bundle hasn't changed, so check the items on it
                        items = bundle.get('product_bundle_items');
                        changedItem = items.find(function(item) {
                            // we have an new item that is not saved yet.
                            if (item.isNew()) {
                                return true;
                            }
                            if (_.isUndefined(itemCalculatedFields[item.module])) {
                                itemCalculatedFields[item.module] =
                                    _.chain(item.fields)
                                        .where({calculated: true})
                                        .pluck('name')
                                        .value();
                            }
                            changedFieldsList = changedFields(item);
                            // omit any sugarlogic items that might be flagged as changed
                            changedFieldsList = _.omit(changedFieldsList, '_-rel_exp_values', 'position');
                            return !_.isEmpty(
                                _.omit(changedFieldsList, itemCalculatedFields[item.module])
                            );
                        });

                        bundleChanged = !_.isUndefined(changedItem);
                    }

                    return bundleChanged;
                }, this);

                return !_.isUndefined(changedBundle);
            }
        });
    });
})(SUGAR.App);
