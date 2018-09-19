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
        app.plugins.register('MergeDuplicates', ['view'], {

            /**
             * Minimum number of records for merging.
             *
             * @property
             */
            _minRecordsToMerge: 2,

            /**
             * Maximum number of records for merging.
             *
             * @property
             */
            _maxRecordsToMerge: 5,

            /**
             * Merge records handler.
             *
             * @param {Backbone.Collection} mergeCollection Set of merging records.
             * @param {Data.Bean} primaryRecord (optional) Default Primary Model.
             */
            mergeDuplicates: function(mergeCollection, primaryRecord) {
                if (_.isEmpty(mergeCollection)) {
                    return;
                }

                if (!_.isEmpty(primaryRecord)) {
                    mergeCollection.add(primaryRecord, {at: 0, silent: true});
                }

                var models = this._validateModelsForMerge(mergeCollection.models);

                if (!this.triggerBefore('mergeduplicates', models)) {
                    return;
                }

                if (this._validateAcl(models, mergeCollection) === false) {
                    this._showAclAlert(models, mergeCollection, primaryRecord);
                    return;
                }

                if (this._validateIsAnyEditable(models) === false) {
                    this._showNoEditAlert();
                    return;
                }

                if (this._hasLockedFields(models) === true) {
                    this._showLockedAlert();
                    return;
                }

                if (this._validateSize(models) === false) {
                    this._showSizeAlert();
                    return;
                }

                this._openMergeDrawer(models, mergeCollection, primaryRecord);
            },

            /**
             * Check if user is allowed to merge all chosen models.
             *
             * @param {Data.Bean[]} models Models with access.
             * @param {Data.Collection} collection Merge Collection to check access for merge.
             * @return {Boolean} `true` only if there is access to all models, otherwise `false`.
             * @protected
             */
            _validateAcl: function(models, collection) {
                return models.length === collection.models.length;
            },

            /**
             * Display acl validation error message in alert.
             * Set up handler on confirm to continue validation and show drawer.
             *
             * @param {Data.Bean[]} models Models with access.
             * @param {Data.Collection} collection Merge Collection to check access for merge.
             * @param {Daba.Bean} primary (optional) Default Primary Model.
             * @protected
             */
            _showAclAlert: function(models, collection, primary) {
                var self = this;
                app.alert.show('invalid-record-access', {
                    level: 'confirmation',
                    messages: app.lang.get('LBL_MERGE_NO_ACCESS_TO_A_FEW_RECORDS', this.module),
                    onConfirm: function() {
                        if (self._validateIsAnyEditable(models) === false) {
                            self._showNoEditAlert();
                            return;
                        }
                        if (self._validateSize(models) === false) {
                            self._showSizeAlert();
                            return;
                        }
                        self._openMergeDrawer(models, collection, primary);
                    }
                });
            },

            /**
             * Check if there is at least one editable model.
             *
             * @param {Array} models Array of merging record set.
             * @return {Boolean} `true` if there is at least one editable model, `false` otherwise.
             * @protected
             */
            _validateIsAnyEditable: function(models) {
                return _.some(models, function(model) {
                    return app.acl.hasAccessToModel('edit', model);
                });
            },

            /**
             * Check if there is at least one model that contains locked fields.
             *
             * @param {Array} models Array of merging record set.
             * @return {boolean} `true` if non empty locked_fields is found, `false` otherwise.
             * @protected
             */
            _hasLockedFields: function(models) {
                return _.some(models, function(model) {
                    return !_.isEmpty(model.get('locked_fields'));
                });
            },

            /**
             * Display error message when there are no editable records.
             * @protected
             */
            _showNoEditAlert: function() {
                var msg = app.lang.get('LBL_MERGE_NO_ACCESS', this.module);
                app.alert.show('no-record-to-edit', {
                    level: 'error',
                    messages: msg
                });
            },

            /**
             * Display error message when there are locked fields.
             */
            _showLockedAlert: function() {
                var msg = app.lang.get('LBL_MERGE_LOCKED', this.module);
                app.alert.show('contains-locked-fields', {
                    level: 'warning',
                    autoclose: false,
                    messages: msg
                });
            },

            /**
             * Check if the total of chosen models is within the predefined limits of records to merge.
             *
             * @param {Array} models Array of merging record set.
             * @return {Boolean} `true` only if it contains valid size of collection, `false` otherwise.
             * @protected
             */
            _validateSize: function(models) {
                var isValidSize = models.length && models.length >= this._minRecordsToMerge &&
                    models.length <= this._maxRecordsToMerge;

                return isValidSize;
            },

            /**
             * Display error message when range of selected records is incorrect.
             *
             * @protected
             */
            _showSizeAlert: function() {
                var msg = app.lang.get('TPL_MERGE_INVALID_NUMBER_RECORDS',
                    this.module,
                    {
                        minRecords: this._minRecordsToMerge,
                        maxRecords: this._maxRecordsToMerge
                    }
                );
                app.alert.show('invalid-record-count', {
                    level: 'error',
                    messages: msg
                });
            },

            /**
             * Open drawer with merge duplicate view.
             *
             * @param {Array} models Models with access.
             * @param {Data.Collection} collection Collection of beans to merge.
             * @param {Data.Bean} primary (Optional) Default Primary Model.
             * @protected
             */
            _openMergeDrawer: function(models, collection, primary) {

                var primaryId = (primary && primary.id) || null;

                app.drawer.open({
                    layout: 'merge-duplicates',
                    context: {
                        primary: primary || null,
                        selectedDuplicates: models
                    }
                }, _.bind(function(refresh, primary) {
                    if (refresh) {
                        this.trigger('mergeduplicates:complete', primary);
                        collection.reset();
                    } else {
                        collection.remove(primaryId);
                    }
                }, this));
            },

            /**
             * Check access for models selected for merge.
             *
             * @param {Data.Bean[]} models Array of merging record set.
             * @return {Data.Bean[]} Models with access.
             * @protected
             */
            _validateModelsForMerge: function(models) {
                return _.filter(models, function(model) {
                    return _.every(['view', 'delete'], function(acl) {
                        return app.acl.hasAccessToModel(acl, model);
                    });
                }, this);
            }
        });
    });
})(SUGAR.App);
