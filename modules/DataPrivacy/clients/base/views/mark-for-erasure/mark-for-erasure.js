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
 * @class View.Views.Base.DataPrivacy.MarkForErasureView
 * @alias SUGAR.App.view.views.BaseDataPrivacyMarkForErasureView
 * @extends View.Views.Base.PiiView
 *
 * `MarkForErasureView` handles selecting fields for erasure from a particular record
 * that contains PII.
 *
 * The fields_to_erase field is a JSON field that looks something like this:
 *
 *  ```
 *  {
 *      leads: {
 *          lead_id1: [
 *              'first_name',
 *              'last_name',
 *              'phone'
 *          ],
 *          lead_id2: [
 *              'first_name',
 *              'phone'
 *          ],
 *      ],
 *      contacts: {
 *          contact_id1: [
 *              'first_name',
 *              'last_name',
 *              'phone'
 *          ],
 *          'contact_id2': [
 *              'first_name',
 *              'phone'
 *          ]
 *       }
 *  }
 */
({
    extendsFrom: 'PiiView',

    className: 'flex-list-view left-actions',

    /**
     * @inheritdoc
     *
     * Initialize and override the Pii collection.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        var modelForErase = this.context.get('modelForErase');
        this.baseModule = modelForErase.module;
        this.baseRecord = modelForErase.id;
        this.context.set('piiModule', this.baseModule);
        this.collection.baseModule = this.baseModule;
        this.collection.baseRecordId = this.baseRecord;

        this.massCollection = app.data.createBeanCollection('MarkForErasureView');
        this.context.set('mass_collection', this.massCollection);
        this._bindMassCollectionEvents();

        this.context.on('markforerasure:mark', this._markForErasure, this);

        this._setColumnActions();
    },

    /**
     * Save just the fields_to_erase for the relevant DataPrivacy record.
     *
     * @param {Object} attributes Attributes from the record to save.
     * @param {Object} attributes.fields_to_erase Fields from related models to
     *   erase.
     */
    saveRecord: function(attributes) {
        // sync just the fields_to_erase (and the ID to make sure we save the right record)
        // to ensure we don't save in-progress changes from the DataPrivacy record view
        var parentModel = this.context.parent.get('model');
        var cloneModel = parentModel.clone();
        cloneModel.set('fields_to_erase', attributes.fields_to_erase);

        cloneModel.sync = function(method, model, options) {
            var callbacks = app.data.getSyncCallbacks(method, model, options);
            options = app.data.parseOptionsForSync(method, model, options);
            app.data.trigger('data:sync:start', method, model, options);
            model.trigger('data:sync:start', method, options);

            // Only actually save the fields_to_erase (and the ID)
            app.api.records(method, model.module, attributes, options.params, callbacks);
        };

        cloneModel.save(attributes, {
            showAlerts: false, // FIXME PX-30: enable custom alert here
            success: function() {
                parentModel.set('fields_to_erase', attributes.fields_to_erase);
                app.drawer.close();
            },
            error: _.bind(function(model, error) {
                if (error.status === 412 && !error.request.metadataRetry) {
                    var self = this;
                    // On a metadata sync error, retry the save after the app is synced
                    self.resavingAfterMetadataSync = true;
                    app.once('app:sync:complete', function() {
                        error.request.metadataRetry = true;
                        model.once('sync', function() {
                            self.resavingAfterMetadataSync = false;
                            app.router.refresh();
                        });
                        //add a new success callback to refresh the page after the save completes
                        error.request.execute(null, app.api.getMetadataHash());
                    });
                } else {
                    // FIXME: Not handling 409's at this time
                    app.alert.show('error_while_save', {
                        level: 'error',
                        title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
                        messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
                    });
                }
            }, this)
        });
    },

    /**
     * Initialize the collection.
     *
     * @private
     */
    _initCollection: function() {
        // FIXME TY-2169: move this code into the PII view and stop overriding here.
        var self = this;
        var PiiCollection = app.BeanCollection.extend({
            baseModule: this.baseModule,
            baseRecordId: this.baseRecord,
            sync: function(method, model, options) {
                options.params = _.extend(options.params || {}, {erased_fields: true});
                var url = app.api.buildURL(this.baseModule, 'pii', {id: this.baseRecordId}, options.params);
                var callbacks = app.data.getSyncCallbacks(method, model, options);
                var defaultSuccessCallback = app.data.getSyncSuccessCallback(method, model, options);
                callbacks.success = function(data, request) {
                    data.records = _.map(data.fields, function(field) {
                        // Each field having a unique ID is required for using the MassCollection
                        field.id = _.uniqueId();
                        return field;
                    });
                    data.records = self.mergePiiFields(data.records);
                    self.applyDataToRecords(data);
                    return defaultSuccessCallback(data, request);
                };
                app.api.call(method, url, options.attributes, callbacks);
            }
        });
        this.collection = new PiiCollection();
        this.collection.on('sync', this._initMassCollection, this);
        this.context.set('collection', this.collection);
    },

    /**
     * Get the list of names of fields to erase corresponding to the module and
     * id of a linked record.
     *
     * @return {string[]} List of field names to erase.
     * @private
     */
    _getFieldsToErase: function() {
        if (this.context && this.context.parent) {
            var parentModel = this.context.parent.get('model');
            var modelForErase = this.context.get('modelForErase');
            if (!parentModel || !modelForErase) {
                return [];
            }

            var fieldsToErase = parentModel.get('fields_to_erase');
            var link = modelForErase.link;
            if (!fieldsToErase || !link || !fieldsToErase[link.name]) {
                return [];
            }

            var modelId = modelForErase.get('id');
            var eraseFieldList = fieldsToErase[link.name];
            return eraseFieldList[modelId] || [];
        }
        return [];
    },

    /**
     * Retrieve models from the collection given a list of field names.
     *
     * @param {string[]} fieldNames List of fields to pick from the collection.
     * @return {Data.Bean[]} List of field beans.
     * @private
     */
    _getModelsByName: function(fieldNames) {
        if (this.collection && this.collection.models) {
            var models = [];
            _.each(fieldNames, function(field) {
                _.each(this.collection.models, function(model) {
                    var fieldName = model.get('field_name');
                    if (field === fieldName || (!_.isUndefined(field.id) && field.id === model.get('value').id)) {
                        models.push(model);
                    }
                });
            }, this);
            return models;
        }
        return [];
    },

    /**
     * @inheritdoc
     *
     * Patch pii models fields with fielddefs from related module
     * in order to render properly.
     */
    _renderData: function() {
        var fields = app.metadata.getModule(this.baseModule).fields;

        _.each(this.collection.models, function(model) {
            model.fields = app.utils.deepCopy(this.metaFields);

            var value = _.findWhere(model.fields, {name: 'value'});
            _.extend(value, fields[model.get('field_name')], {name: 'value'});

            if (_.contains(['multienum', 'enum'], value.type) && value.function) {
                value.type = 'base';
            }

            model.fields = app.metadata._patchFields(
                this.module,
                app.metadata.getModule(this.baseModule),
                model.fields
            );
        }, this);

        this._super('_renderData');
    },

    /**
     * Add previously marked fields for erasure to the mass collection.
     *
     * @private
     */
    _initMassCollection: function() {
        var fieldsToErase = this._getFieldsToErase();
        var preselectedModels = this._getModelsByName(fieldsToErase);
        this._addModel(preselectedModels);
        this.context.trigger('markforerasure:masscollection:init', preselectedModels);
    },

    /**
     * Mark the selected fields from the drawer to erase.
     *
     * @private
     */
    _markForErasure: function() {
        var selectedModels = this.massCollection.models;
        var selectedFields;
        try {
            selectedFields = _.map(selectedModels, function(model) {
                var fieldName = model.get('field_name');
                // Email addresses and other related fields will have an object containing
                // the name and id of the record
                var value = model.get('value');
                if (_.isObject(value) && !_.isArray(value)) {
                    if (!fieldName || !value.id) {
                        throw new Error('Unable to mark field ' + fieldName + ' to erase.');
                    }
                    return {
                        field_name: fieldName,
                        id: value.id
                    };
                }
                return fieldName;
            });
        } catch (e) {
            app.alert.show('invalid_pii_field', {
                level: 'error',
                messages: [e.message],
            });
            return;
        }

        var modelForErase = this.context.get('modelForErase');
        var link = modelForErase.link;
        if (!link) {
            throw new Error('Cannot erase fields on an unlinked record');
        }
        var linkName = link.name;
        var modelId = modelForErase.get('id');

        var parentModel = this.context.parent.get('model');
        var fieldsToErase = app.utils.deepCopy(parentModel.get('fields_to_erase'));
        if (_.isEmpty(fieldsToErase)) {
            fieldsToErase = {};
        }
        fieldsToErase[linkName] = fieldsToErase[linkName] || {};

        if (_.isEmpty(selectedFields)) {
            fieldsToErase = this._cleanupFieldsToErase(fieldsToErase, linkName, modelId);
        } else {
            fieldsToErase[linkName][modelId] = selectedFields;
        }

        var attributesToSave = {
            id: parentModel.id,
            fields_to_erase: fieldsToErase
        };
        this.saveRecord(attributesToSave);
    },

    /**
     * Clean up the fields_to_erase so we don't leave empty keys floating in it
     *
     * @param {Object} fieldsToErase The fields_to_erase data structure.
     * @param {string} linkName Name of the linked subpanel to which this view corresponds.
     * @param {string} modelId ID of the model from which we were erasing fields.
     * @return {Object} The cleaned fields_to_erase.
     * @private
     */
    _cleanupFieldsToErase: function(fieldsToErase, linkName, modelId) {
        // if the list of fields is now empty, wipe out this linked record
        delete fieldsToErase[linkName][modelId];

        // if there are now no fields_to_erase from *any* record from this link type,
        // wipe out this link
        if (_.isEmpty(fieldsToErase[linkName])) {
            delete fieldsToErase[linkName];

            // NOTE: do NOT null out this one more level
            // doing so means sending fields_to_erase as NULL, which is ignored
        }

        return fieldsToErase;
    },

    /**
     * Binds mass collection event listeners.
     *
     * @private
     */
    _bindMassCollectionEvents: function() {
        this.context.on('mass_collection:add', this._addModel, this);
        this.context.on('mass_collection:add:all', this._addAllModels, this);
        this.context.on('mass_collection:remove', this._removeModel, this);
        this.context.on('mass_collection:remove:all mass_collection:clear', this._clearMassCollection, this);
    },

    /**
     * Adds a model or a list of models to the mass collection.
     *
     * @param {Data.Bean|Data.Bean[]} models The model or the list of models
     *   to add.
     * @private
     */
    _addModel: function(models) {
        models = _.isArray(models) ? models : [models];
        this.massCollection.add(models);
        if (this._isAllChecked()) {
            this.massCollection.trigger('all:checked');
        }
    },

    /**
     * Adds all models of the view collection to the mass collection.
     *
     * @private
     */
    _addAllModels: function() {
        this.massCollection.reset(this.collection.models);
        this.massCollection.trigger('all:checked');
    },

    /**
     * Removes a model or a list of models from the mass collection.
     *
     * @param {Data.Bean|Data.Bean[]} models The model or the list of models
     *   to remove.
     * @private
     */
    _removeModel: function(models) {
        models = _.isArray(models) ? models : [models];
        this.massCollection.remove(models);
        this.massCollection.trigger('not:all:checked');
    },

    /**
     * Clears the mass collection.
     *
     * @private
     */
    _clearMassCollection: function() {
        this.massCollection.entire = false;
        this.massCollection.reset();
        this.massCollection.trigger('not:all:checked');
    },

    /**
     * Checks if all models of the view collection are in the mass
     * collection.
     *
     * @return {boolean} allChecked `true` if all models of the view
     *   collection are in the mass collection.
     * @private
     */
    _isAllChecked: function() {
        if (this.massCollection.length < this.collection.length) {
            return false;
        }
        return _.every(this.collection.models, function(model) {
            return this.massCollection.get(model.id);
        }, this);
    },

    /**
     * Set the checkbox column metadata.
     *
     * @private
     */
    _setColumnActions: function() {
        this.leftColumns = [{
            type: 'fieldset',
            fields: [
                {
                    type: 'actionmenu',
                    buttons: [],
                    disable_select_all_alert: true
                }
            ],
            value: false,
            sortable: false
        }];
    }
})
