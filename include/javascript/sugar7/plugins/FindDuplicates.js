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
        var createDuplicateCollection, duplicateCheckIsDisabledHandler;

        /**
         * Skips the Duplicate Check API request.
         *
         * Mimics the completion of fetching data to satisfy the expectations
         * of the caller. The collection's `dataFetched` property is set to
         * `true` and the optional success callback is immediately called if
         * one exists.
         *
         * Triggers an error event on the collection in case the caller wants
         * to be notified. The parameter is an `Error` with a message denoting
         * that Duplicate Check is disabled for the particular module.
         *
         * @fires duplicatecheck:error
         * @param {Data.BeanCollection} collection
         * @param {Object} [options]
         */
        duplicateCheckIsDisabledHandler = function(collection, options) {
            var errorMessage;

            options = options || {};
            collection.dataFetched = true;

            errorMessage = app.lang.get(
                'ERR_DUPLICATE_CHECK_IS_DISABLED',
                collection.module,
                {module: collection.module}
            );
            collection.trigger('duplicatecheck:error', new Error(errorMessage));

            if (options.success) {
                options.success(collection, undefined, {});
            }
        };

        createDuplicateCollection = function(dupeCheckModel, module) {
            var collection = app.data.createBeanCollection(module || this.module),
                collectionSync = collection.sync;
            _.extend(collection, {
                /**
                 * Duplicate check model.
                 *
                 * @property
                 */
                dupeCheckModel: dupeCheckModel,

                /**
                 * @inheritdoc
                 *
                 * Override endpoint in order to fetch custom api.
                 *
                 * Does not follow through with calling the DuplicateCheckApi
                 * if Duplicate Check is disabled for the particular module.
                 */
                sync: function(method, model, options) {
                    var checkForDuplicates, metadata;

                    options = options || {};
                    checkForDuplicates = _.isEmpty(model.filterDef);
                    metadata = app.metadata.getModule(this.module);

                    if (checkForDuplicates) {
                        options.endpoint = _.bind(this.endpoint, this);
                    }

                    if (checkForDuplicates && !metadata.dupCheckEnabled) {
                        duplicateCheckIsDisabledHandler(this, options);
                    } else {
                        collectionSync(method, model, options);
                    }
                },

                /**
                 * @inheritdoc
                 *
                 * Custom endpoint for duplicate check.
                 */
                endpoint: function(method, model, options, callbacks) {
                    //Dupe Check API requires POST
                    var url = app.api.buildURL(this.module, 'duplicateCheck');
                    var data = app.data.getEditableFields(this.dupeCheckModel);
                    return app.api.call('create', url, data, callbacks);
                }
            });
            return collection;
        };

        app.plugins.register('FindDuplicates', ['view'], {
            /**
             * @inheritdoc
             *
             * Bind the find duplicate button handler.
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    this.context.on('button:find_duplicates_button:click', this.findDuplicatesClicked, this);
                });
            },

            /**
             * Handles the click event, and open the duplicate list view in the drawer.
             */
            findDuplicatesClicked: function() {
                this.findDuplicates(this.model);
            },

            /**
             * Open duplicate list view on drawer with duplicates collection.
             *
             * @param {Backbone.Model} dupeCheckModel Duplicate check model.
             */
            findDuplicates: function(dupeCheckModel) {
                app.drawer.open({
                    layout: 'find-duplicates',
                    context: {
                        layoutName: 'records',
                        dupelisttype: 'dupecheck-list-multiselect',
                        collection: this.createDuplicateCollection(dupeCheckModel),
                        model: app.data.createBean(this.module)
                    }
                }, _.bind(function(refresh, primaryRecord) {
                    if (refresh && dupeCheckModel.id === primaryRecord.id) {
                        app.router.refresh();
                    } else if (refresh) {
                        app.navigate(this.context, primaryRecord);
                    }
                }, this));
            },

            /**
             * Create Duplicates list collection.
             *
             * @param {Backbone.Model} dupeCheckModel Duplicate check model.
             * @return {Backbone.Collection} Duplicate check collection.
             */
            createDuplicateCollection: createDuplicateCollection,

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                this.context.off('button:find_duplicates_button:click', this.findDuplicatesClicked, this);
            }
        });

        app.plugins.register('FindDuplicates', ['layout'], {
            /**
             * Create Duplicates list collection.
             *
             * @param {Backbone.Model} dupeCheckModel Duplicate check model.
             * @return {Backbone.Collection} Duplicate check collection.
             */
            createDuplicateCollection: createDuplicateCollection
        });
    });
})(SUGAR.App);
