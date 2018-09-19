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
 * @class View.Views.Base.PiiView
 * @alias SUGAR.App.view.views.BasePiiView
 * @extends View.Views.Base.FilteredListView
 */
({
    extendsFrom: 'FilteredListView',

    fallbackFieldTemplate: 'list-header',

    /**
     * @inheritdoc
     * Initialize and override the Pii collection.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.baseModule = this.context.get('pModule');
        this.baseRecord = this.context.get('pId');
        if (!this.collection) {
            this._initCollection();
        }
    },

    /**
     * Initialize the collection.
     *
     * @protected
     */
    _initCollection: function() {
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
                    data.records = self.mergePiiFields(data.fields);
                    self.applyDataToRecords(data);
                    return defaultSuccessCallback(data, request);
                };
                app.api.call(method, url, options.attributes, callbacks);
            }
        });
        this.collection = new PiiCollection();
    },

    /**
     * Combine module's fields with response data so we show all fields regardless of ACLs
     *
     * @param {Array} responseFields Fields from the PII endpoint
     * @return {Array} All PII fields for the baseModule
     */
    mergePiiFields: function(responseFields) {
        var piiFields = _.where(app.metadata.getModule(this.baseModule, 'fields'), {pii: true});

        var fields = [];
        _.each(piiFields, function(field) {
            var fieldName = field.name;
            var dataFields = _.where(responseFields, {field_name: fieldName});
            if (dataFields.length > 0) {
                _.each(dataFields, function(dataField) {
                    fields.push(_.extend({field_name: fieldName}, dataField));
                });
            } else {
                // We likely don't have ACL access but still want to show the field (without a value)
                fields.push({field_name: fieldName});
            }
        });
        return fields;
    },

    /**
     * @inheritdoc
     */
    loadData: function() {
        if (this.collection.dataFetched) {
            return;
        }
        this.collection.fetch();
    },

    /**
     * @inheritdoc
     *
     * Patch pii models fields with information of
     * original field available within parent model, in order to render
     * properly.
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
                app.metadata.getModule(this.module),
                model.fields
            );
        }, this);

        this._super('_renderData');
    },

    /**
     * @override
     *
     * Overriding to return Pii view metadata, so filteredListView
     * can properly initialize filter when vardef is not available.
     */
    getFields: function() {
        return this._super('getFields', ['Pii']);
    },

    /**
     * Apply ACL and erased field information to the given record data.
     *
     * @param {Object} data Record data.
     */
    applyDataToRecords: function(data) {
        if (data._acl && data._acl.fields) {
            this._applyACLToRecords(data._acl.fields, data.records);
        }
        this._applyErasedFieldsToRecords(data._erased_fields, data.records);
    },

    /**
     * Apply ACLs to the given records.
     *
     * @param {Object} aclFields ACL fields object.
     * @param {Object[]} records Records to which you want ACLs applied.
     *
     * @private
     */
    _applyACLToRecords: function(aclFields, records) {
        _.each(records, function(record) {
            var fieldName = record.field_name;
            if (fieldName in aclFields) {
                record._acl = {
                    fields: {
                        value: aclFields[fieldName]
                    }
                };
            }
        });
    },

    /**
     * Apply erased field list to records.
     *
     * @param {string[]} erasedFields List of erased fields.
     * @param {Object[]} records The records where you want erased fields
     *  applied.
     *
     * @private
     */
    _applyErasedFieldsToRecords: function(erasedFields, records) {
        if (_.isEmpty(erasedFields)) {
            return;
        }
        var erasedFieldsMap = _.reduce(erasedFields, function(map, field) {
            map[field] = true;
            return map;
        }, {});
        _.each(records, function(record) {
            var fieldName = record.field_name;
            if (fieldName in erasedFieldsMap) {
                record._erased_fields = ['value'];
            }
        });
    }
})
