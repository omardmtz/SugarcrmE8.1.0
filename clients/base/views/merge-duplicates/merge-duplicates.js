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
 * View for merge duplicates.
 *
 * @class View.Views.Base.MergeDuplicatesView
 * @alias SUGAR.App.view.views.BaseMergeDuplicatesView
 * @extends View.Views.Base.ListView
 */
({
    extendsFrom: 'ListView',

    plugins: ['Editable', 'ErrorDecoration', 'MergeDuplicates'],

    events: {
        'click [data-mode=preview]' : 'togglePreview',
        'click [data-action=copy]' : 'triggerCopy',
        'click [data-action=delete]' : 'triggerDelete'
    },

    dataView: '',

    /**
     * Default settings used when none are supplied through metadata.
     *
     * Supported settings:
     * - {Number} merge_relate_fetch_concurrency Determining how many worker
     *   functions should be run in parallel for fetch.
     * - {Number} merge_relate_fetch_timeout Timeout for fetch related records
     *   call (milliseconds).
     * - {Number} merge_relate_fetch_limit Max number of records to fetch
     *   for related collection at a time.
     * - {Number} merge_relate_update_concurrency Determining how many worker
     *   functions should be run in parallel for update beans.
     * - {Number} merge_relate_update_timeout Timeout for update
     *   beans (milliseconds).
     * - {Number} merge_relate_max_attempt Max number of attemps for
     *   merge related.
     *
     * Example:
     * <pre><code>
     * // ...
     * 'settings' => array(
     *      'merge_relate_fetch_concurrency' => 2,
     *      'merge_relate_fetch_timeout' => 90000,
     *      'merge_relate_fetch_limit' => 20,
     *      'merge_relate_update_concurrency' => 4,
     *      'merge_relate_update_timeout' => 90000,
     *      'merge_relate_max_attempt' => 3,
     *      //...
     * ),
     * // ...
     * </code></pre>
     *
     * @property {Object}
     * @protected
     */
    _defaultSettings: {
        merge_relate_fetch_concurrency: 2,
        merge_relate_fetch_timeout: 90000,
        merge_relate_fetch_limit: 20,
        merge_relate_update_concurrency: 4,
        merge_relate_update_timeout: 90000,
        merge_relate_max_attempt: 3
    },

    /**
     * List of fields to generate the metadata on the fly.
     *
     * @property {Array} mergeFields
     */
    mergeFields: [],

    /**
     * @property {Object} rowFields
     */
    rowFields: {},

    /**
     * @property {Data.Bean} primaryRecord
     */
    primaryRecord: {},

    /**
     * @property {Boolean} [toggled=false]
     */
    toggled: false,

    /**
     * @property {Boolean} [isPreviewOpen=false]
     */
    isPreviewOpen: false,

    /**
     * Array of field defs keys that contain fields to populate.
     *
     * For some types of field we should populate additional fields
     * that can be determined from fields defs. E.g.
     * 1. if field type is 'relate' and 'parent'
     *     - def.id_name contains field name for id of related
     * 2. if field type is 'parent'
     *     - def.type_name contains field name for type of related
     *
     * @property {Array} relatedFieldsMap
     */
    relatedFieldsMap: ['id_name', 'type_name'],

    /**
     * Field names won't be mergeable.
     *
     * @property {Array} fieldNameBlacklist
     */
    fieldNameBlacklist: [
        'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'deleted'
    ],

    /**
     * Field types won't be mergeable.
     *
     * @property {Array} fieldTypesBlacklist
     *
     * TODO: remove types that have properly implementation for merge interface
     */
    fieldTypesBlacklist: ['team_list', 'link', 'id', 'password'],

    /**
     * Links names won't be mergeable.
     * Those links will be not used in merge related records.
     *
     * @property {Array} relatesBlacklist
     */
    relatesBlacklist: [
        'assigned_user_link', 'modified_user_link', 'created_by_link',
        'teams', 'team_link', 'team_count_link',
        'archived_emails', 'email_addresses', 'email_addresses_primary',
        'forecastworksheets',
        'currencies'
    ],

    /**
     * Links names for certain module won't be mergeable.
     * Those links will be not used in merge related records for certain module.
     *
     * @property {Object} relatesBlacklistForModule
     */
    relatesBlacklistForModule: {
        Accounts: ['revenuelineitems'],
        Opportunities: ['accounts'],
        Leads: ['meetings_parent', 'calls_parent'],
        Prospects: ['tasks'],
        Bugs: ['project'],
        RevenueLineItems: ['campaign_revenuelineitems']
    },

    /**
     * @property {Object} mergeStat Contains stat after merging.
     */
    mergeStat: null,

    /**
     * Object used as context for merge duplicates view.
     *
     * {Backbone.Model} mergeProgressModel
     */
    mergeProgressModel: null,

    /**
     * @property {Backbone.Model} mergeRelatedCollection Contains related records to merge.
     */
    mergeRelatedCollection: null,

    /**
     * Attribute combos allowed to merge.
     *
     * @property {Array} validArrayAttributes
     */
    validArrayAttributes: [
        { type: 'datetimecombo', source: 'db' },
        { type: 'datetime', source: 'db' },
        { type: 'varchar', source: 'db' },
        { type: 'enum', source: 'db' },
        { type: 'multienum', source: 'db' },
        { type: 'text', source: 'db' },
        { type: 'date', source: 'db' },
        { type: 'time', source: 'db' },
        { type: 'currency', source: 'db', calculated: false },
        { type: 'int', source: 'db' },
        { type: 'long', source: 'db' },
        { type: 'double', source: 'db' },
        { type: 'float', source: 'db' },
        { type: 'short', source: 'db' },
        { dbType: 'varchar', source: 'db' },
        { dbType: 'double', source: 'db' },
        { type: 'relate' },
        { type: 'parent' },
        { type: 'image' },
        { type: 'teamset' },
        { type: 'email' },
        { type: 'tag' }
    ],

    /**
     * Types of fields that can be processed
     * in {@link View.Views.BaseMergeDuplicatesView#flattenFieldsets}.
     * @property {Array} flattenFieldTypes
     */
    flattenFieldTypes: ['fieldset', 'fullname'],

    /**
     * Variable to store generated values for some types of fields (e.g. teamset).
     * @property {Object} generatedValues
     */
    generatedValues: null,

    /**
     * Variable to store a copy of primary record's model for showing it
     * on preview panel
     * @property {Backbone.Model} previewModel Contains copy of primary record.
     */
    previewModel: {},

    /**
     * @inheritdoc
     *
     * Initialize merge collection as collection of selected records and
     * initialise fields that can be used in merge.
     */
    initialize: function(options) {
        this._initializeMergeFields(options.module);
        this._super('initialize', [options]);
        this._initSettings();
        this._initializeMergeCollection(this._prepareRecords());

        this.action = 'list';
        this._delegateEvents();
    },

    /**
     * Add event listeners
     *
     * @private
     */
    _delegateEvents: function() {
        this.layout.on('mergeduplicates:save:fire', this.triggerSave, this);

        app.events.on('preview:open', _.bind(this._onPreviewToggle, this, true), this);
        app.events.on('preview:close', _.bind(this._onPreviewToggle, this, false), this);
        this.on('render', this._showAlertIfIdentical, this);
    },

    /**
     * Initialize settings, default settings are used when none are supplied
     * through metadata.
     *
     * @return {View.Views.BaseMergeDuplicatesView} Instance of this view.
     * @protected
     */
    _initSettings: function() {

        var configSettings = app.config.mergeDuplicates && {
            merge_relate_fetch_concurrency: app.config.mergeDuplicates.mergeRelateFetchConcurrency,
            merge_relate_fetch_timeout: app.config.mergeDuplicates.mergeRelateFetchTimeout,
            merge_relate_fetch_limit: app.config.mergeDuplicates.mergeRelateFetchLimit,
            merge_relate_update_concurrency: app.config.mergeDuplicates.mergeRelateUpdateConcurrency,
            merge_relate_update_timeout: app.config.mergeDuplicates.mergeRelateUpdateTimeout,
            merge_relate_max_attempt: app.config.mergeDuplicates.mergeRelateMaxAttempt
        };

        this._settings = _.extend(
            this._defaultSettings,
            configSettings,
            this.meta && this.meta.settings || {}
        );
        return this;
    },

    /**
     * Standardize primary record from list of records.
     *
     * Put primary at the beginning of records.
     * This is useful primarily to know which record will be the primary
     * in the collection to be pulled later. We do not use the input models.
     *
     * @return {Array} records.
     * @private
     */
    _prepareRecords: function() {
        var records = this._validateModelsForMerge(this.context.get('selectedDuplicates'));

        this.setPrimaryRecord(this._findPrimary(records));
        return records;
    },

    /**
     * Find primary model from models chosen for merge.
     *
     * If primary model has no access to edit it finds first model that can
     * be edited and sets it as primary.
     *
     * @param {Data.Bean[]} models Set of models to merge.
     * @return {Data.Bean} Primary model
     * @protected
     */
    _findPrimary: function(models) {
        var primary = this.context.has('primaryRecord') &&
            _.findWhere(models, {id: this.context.get('primaryRecord').id});

        return primary || _.find(models, function(model) {
            return app.acl.hasAccessToModel('edit', model);
        });
    },

    /**
     * Initialize fields for merge.
     *
     * Creates filtered set of model's fields that can be merged.
     *
     * @param {string} module Module to retrieve metadata from.
     * @protected
     */
    _initializeMergeFields: function(module) {
        var meta = app.metadata.getView(module, 'record');
        var fieldDefs = app.metadata.getModule(module).fields;

        this.mergeFields = _.chain(meta.panels)
            .map(function(panel) {
                return this.flattenFieldsets(panel.fields);
            }, this)
            .flatten()
            .filter(function(field) {
                return field.name && this.validMergeField(fieldDefs[field.name]);
            }, this)
            .value();
    },

    /**
     * Initialize collection for merge.
     *
     * Enforce the order of the ids so that primaryRecord always appears first
     * and only retrieve the records specified.
     * @param {Array} records
     * @private
     */
    _initializeMergeCollection: function(records) {
        var ids = (_.pluck(records, 'id'));

        if (this.collection) {
            this.collection.filterDef = [];
            this.collection.filterDef.push({ 'id': { '$in' : ids}});
            this.collection.comparator = function(model) {
                return _.indexOf(ids, model.get('id'));
            };
        }
    },

    /**
     * Handler for save merged records event.
     *
     * Shows confirmation message and calls
     * {@link View.Views.BaseMergeDuplicatesView#_savePrimary} on confirm.
     */
    triggerSave: function() {
        var self = this,
            alternativeModels = _.without(this.collection.models, this.primaryRecord),
            alternativeModelNames = [];

        _.each(alternativeModels, function(model) {
            alternativeModelNames.push(app.utils.getRecordName(model));
        }, this);

        this.clearValidationErrors(this.getFieldNames());

        app.alert.show('merge_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_MERGE_DUPLICATES_CONFIRM') + ' ' +
                // FIXME needs to be removed on SC-4494.
                Handlebars.Utils.escapeExpression(alternativeModelNames.join(', ')) + '. ' +
                app.lang.get('LBL_MERGE_DUPLICATES_PROCEED'),
            onConfirm: _.bind(this._savePrimary, this)
        });
    },

    /**
     * Saves primary record and triggers `mergeduplicates:primary:saved` event on success.
     * Before saving triggers also `duplicate:unformat:field` event.
     *
     * @private
     */
    _savePrimary: function() {
        var self = this;
        var fields = this.getFieldNames().filter(function(field) {
            return app.acl.hasAccessToModel('edit', this.primaryRecord, field);
        }, this);
        var headerpaneView = this.layout.getComponent('merge-duplicates-headerpane');

        headerpaneView.getField('cancel_button').setDisabled(true);

        this.primaryRecord.trigger('duplicate:unformat:field');

        this.primaryRecord.save({}, {
            fieldsToValidate: fields,
            success: function() {
                // Trigger format fields again, because they can come different
                // from the server (e.g: only teams checked will be in the
                // response, and we still want to display unchecked teams on the
                // view)
                self.primaryRecord.trigger('duplicate:format:field');
                self.primaryRecord.trigger('mergeduplicates:primary:saved');
            },
            error: function(model, error) {
                if (error.status === 409) {
                    app.utils.resolve409Conflict(error, self.primaryRecord, function(model, isDatabaseData) {
                        if (model) {
                            if (isDatabaseData) {
                                self.resetRadioSelection(model.id);
                                headerpaneView.getField('cancel_button').setDisabled(false);
                            } else {
                                self._savePrimary();
                            }
                        }
                    });
                } else {
                    headerpaneView.getField('cancel_button').setDisabled(false);
                }
            },
            lastModified: this.primaryRecord.get('date_modified'),
            showAlerts: true,
            viewed: true
        });
    },

    /**
     * Removes merged models and triggers `mergeduplicates:primary:merged` on success.
     *
     * We need to wait until all models are removed from server
     * to properly reload records view. Runs destroy methods in parallel
     * and triggers event after all requests have finished.
     *
     * @private
     */
    _removeMerged: function() {
        var self = this,
            models = _.without(this.collection.models, this.primaryRecord);

        async.forEach(models, function(model, callback) {
            self.collection.remove(model);
            model.destroy({success: function() {
                callback.call();
            }});
        }, function() {
            self.primaryRecord.trigger('mergeduplicates:primary:merged');
            self.hideMainPreviewPanel();
        });
    },

    /**
     * Hide the preview panel, from the main drawer
     */
    hideMainPreviewPanel: function() {
        //Get main drawer
        var $main_drawer = app.$contentEl.children().first();
        if (!_.isUndefined($main_drawer) && $main_drawer.hasClass('drawer inactive')) {
            var layout = $main_drawer.find('.sidebar-content');
            layout.find('.side-pane').addClass('active');
            layout.find('.dashboard-pane').show();
            layout.find('.preview-pane').removeClass('active');
        }
    },
    /**
     * @inheritdoc
     *
     * Override fetching fields names. Use fields that are allowed to merge only.
     *
     * Add additional fields for cases:
     * 1. field type is 'relate' and 'parent' (def.id_name)
     *     - def.id_name contains field name for id of related
     * 2. field type is 'parent' (def.type_name)
     *     - def.type_name contains field name for type of related
     *
     * @return {Array} array of field names.
     */
    getFieldNames: function() {
        var fields = [],
            fieldDefs = app.metadata.getModule(this.module).fields;

        _.each(this.mergeFields, function(mergeField) {
            var def = fieldDefs[mergeField.name];
            _.each(this.relatedFieldsMap, function(relatedField) {
                if (!_.isUndefined(def[relatedField]) && !_.isUndefined((fieldDefs[def[relatedField]].name))) {
                    fields.push(fieldDefs[def[relatedField]].name);
                }
            });
            var related_fields = mergeField.related_fields || def.related_fields || undefined;
            if (!_.isUndefined(related_fields) && _.isArray(related_fields)) {
                _.each(related_fields, function(rField) {
                    fields.push(rField);
                });
            }
            fields.push(def.name);
        }, this);
        return _.unique(fields);
    },

    /**
     * Create metadata for panels.
     *
     * Team sets will have a special metadata setup to match the height in all
     * records shown (match height).
     *
     * The fields are sorted by difference of values, showing first the ones
     * that are different among all records and then the ones that are equal.
     *
     * @param {Array} fields The list of fields for the module.
     * @return {Object} The metadata for the view template.
     * @private
     */
    _generateMetadata: function(fields) {
        this.generatedValues = {
            teamsets: []
        };

        _.each(fields, function(field) {
            if (field.type === 'teamset') {

                var teams = {};
                this.collection.each(function(model) {
                    _.each(model.get(field.name), function(team) {
                        teams[team.id] = team;
                    });
                });

                this.generatedValues.teamsets[field.name] = _.values(teams);
                field.maxHeight = _.size(teams);
                field.noRadioBox = true;
            }
        }, this);

        var models = this.collection.without(this.primaryRecord);
        fields = _.sortBy(fields, function(field) {
            return _.every(models, function(model) {
                return _.isEqual(this.primaryRecord.get(field.name), model.get(field.name));
            }, this);
        }, this);

        return {
            type: 'list',
            panels: [
                {
                    fields: fields
                }
            ]
        };
    },

    /**
     * Checks if all values are the same among all models.
     *
     * Compares the field value from primary model with values from other
     * models and returns `false` if it finds 1 field that isn't equal across
     * all models.
     *
     * @param {Data.Bean} primary The model chosen as primary.
     * @param {Data.Bean[]} models The array of models to compare with.
     * @return {Boolean} Is field value the same among all models.
     *
     * @private
     */
    _isSimilar: function(primary, models) {
        return _.every(models, function(model) {
            var modelFields = this.rowFields[model.id],
                primaryFields = this.rowFields[primary.id];

            return _.every(modelFields, function(field, index) {
                return field.equals(primaryFields[index]);
            }, this);
        }, this);
    },

    /**
     * Utility method for determining if a field is mergeable from its def.
     *
     * @param {Object} fieldDef Defs of validated field.
     * @return {Boolean} Is this field a valid field to merge?
     */
    validMergeField: function(fieldDef) {

        if (!fieldDef ||
            fieldDef.auto_increment === true ||
            !this._validMergeFieldName(fieldDef) ||
            !this._validMergeFieldType(fieldDef) ||
            this._isDuplicateMergeDisabled(fieldDef)
        ) {
            return false;
        }

        if (this._isDuplicateMergeEnabled(fieldDef)) {
            return true;
        }

        return this._validMergeFieldAttributes(fieldDef);
    },

    /**
     * Validate field to merge by name.
     *
     * @param {Object} defs Defs of validated field.
     * @return {Boolean}
     * @private
     */
    _validMergeFieldName: function(defs) {
        return !_.contains(this.fieldNameBlacklist, defs.name);
    },

    /**
     * Validate field to merge by type.
     *
     * @param {Object} defs Defs of validated field.
     * @return {Boolean}
     * @private
     */
    _validMergeFieldType: function(defs) {
        return !_.contains(this.fieldTypesBlacklist, defs.type);
    },

    /**
     * Checks if duplicate_merge is disabled in field's defs.
     *
     * @param {Object} defs Defs of validated field.
     * @return {Boolean}
     * @private
     */
    _isDuplicateMergeDisabled: function(defs) {
        if (!_.isUndefined(defs.duplicate_merge) &&
            (defs.duplicate_merge === 'disabled' ||
                defs.duplicate_merge === false)
        ) {
            return true;
        }
        return false;
    },

    /**
     * Checks if duplicate_merge is enabled in field's defs.
     *
     * @param {Object} defs Defs of validated field.
     * @return {Boolean}
     * @private
     */
    _isDuplicateMergeEnabled: function(defs) {
        if (!_.isUndefined(defs.duplicate_merge) &&
            (defs.duplicate_merge === 'enabled' ||
                defs.duplicate_merge === true)
        ) {
            return true;
        }
        return false;
    },

    /**
     * Validate field to merge by attributes.
     *
     * Uses `this.validArrayAttributes` hash to validate attributes.
     * Also checks if field is calculated and if it is returns `false`.
     *
     * @param {Object} defs Defs of validated field.
     * @return {Boolean}
     * @private
     */
    _validMergeFieldAttributes: function(defs) {
        // normalize fields that might not be there
        defs.dbType = defs.dbType || defs.type;
        defs.source = defs.source || 'db';
        defs.calculated = defs.calculated || false;

        if (defs.calculated !== false) {
            return false;
        }

        // compare to values in the list of acceptable attributes
        return _.some(this.validArrayAttributes, function(o) {
            return _.chain(o)
                .keys()
                .every(function(key) {
                    return o[key] === defs[key];
                })
                .value();
        });
    },

    /**
     * Utility method for taking a fieldlist with possible nested fields,
     * and returning a flat array of fields.
     *
     * @param {Array} defs Unprocessed list of fields from metadata.
     * @return {Array} Fields flat list of fields.
     */
    flattenFieldsets: function(defs) {
        var fieldsetFilter = function(field) {
                return (field.type &&
                    _.isArray(field.fields) &&
                    _.contains(this.flattenFieldTypes, field.type));
            },
            fields = _.reject(defs, fieldsetFilter, this),
            fieldsets = _.filter(defs, fieldsetFilter, this),
            sort = _.chain(defs).pluck('name').value() || [],
            sortTemp = [];

        while (fieldsets.length) {
            //collect fields' names from fieldset
            var fieldsNames = _.chain(fieldsets)
                .pluck('fields')
                .flatten()
                .pluck('name')
                .value();
            sortTemp = [];
            // create new sort sequence
            _.each(sort, function(value) {
                if (value === _.first(fieldsets).name) {
                    sortTemp = sortTemp.concat(fieldsNames);
                } else {
                    sortTemp = sortTemp.concat(value);
                }
            }, this);
            sort = sortTemp;
            // fieldsets need to be broken into component fields
            fieldsets = _.chain(fieldsets)
                .pluck('fields')
                .flatten()
                .value();

            // now collect the raw fields from the press
            fields = fields.concat(_.reject(fieldsets, fieldsetFilter));

            // do we have any more fieldsets to squash?
            fieldsets = _.filter(fieldsets, fieldsetFilter);
        }
        // sorting fields acording to sequence
        fields = _.sortBy(fields, function(value, index) {
            var result = index,
                name = value;
            if (!_.isUndefined(value.name)) {
                name = value.name;
                _.each(sort, function(valueSort, indexSort) {
                    if (valueSort == name) {
                        result = indexSort;
                    }
                });
            }
            return result;
        });
        return fields;
    },

    /**
     * Event handler for `preview:open` and `preview:close` events.
     *
     * @param {boolean} open Flag indicating the desired state of the preview
     * @deprecated Since 7.8. Will be removed in 7.9.
     */
    onPreviewToggle: function(open) {
        app.logger.warn('`View.Views.Base.MergeDuplicatesView#onPreviewToggle` has been deprecated since 7.8 and' +
            ' will be removed in 7.9.');
        this.isPreviewOpen = open;
        this.$('[data-mode=preview]').toggleClass('on', open);
    },

    /**
     * Event handler for `preview:open` and `preview:close` events.
     *
     * @param {boolean} open Flag indicating the desired state of the preview
     * @deprecated Since 7.8. Will be removed in 7.9.
     * @private
     */
    _onPreviewToggle: function(open) {
        this.isPreviewOpen = open;
        this.$('[data-mode=preview]').toggleClass('on', open);
    },

    /**
     * Toggles a Preview for the primary record.
     *
     * @param {Event} evt Mouse click event.
     */
    togglePreview: function(evt) {
        if (this.isPreviewOpen) {
            app.events.trigger('preview:close');
        } else {
            this.updatePreviewRecord(this.primaryRecord);
        }
    },

    /**
     * Creates the preview panel for the model in question.
     *
     * @param {Data.Bean} model Model to preview.
     */
    updatePreviewRecord: function(model) {
        var module = model.module || model.get('module');
        var previewCollection;

        // FIXME PX-15: Hack-fix for re-enabling sync on preview panel launch
        // We had to get the delta for primary record from current state until
        // now and reapply all of them after preview is synced
        if (_.isEmpty(this.previewModel) || this.previewModel.get('id') !== model.get('id')) {
            this.previewModel = app.data.createBean(module, model.toJSON());
            previewCollection = app.data.createBeanCollection(module, [this.previewModel]);

            this.previewModel.setOption({
                success: function(changedModel) {
                    var changedAttributes = model.changedAttributes(model.getSynced());
                    changedModel.set(_.mapObject(changedAttributes, function(value, fieldName) {
                        return model.get(fieldName);
                    }, this));
                }
            });
        }

        app.events.trigger('preview:render', this.previewModel, previewCollection, false);
        app.events.trigger('preview:open', true);
    },

    /**
     * Updates the view's title.
     *
     * @param {String} title
     */
    updatePrimaryTitle: function(title) {
        this.$('[data-container=primary-title]').text(title);
    },

    /**
     * @inheritdoc
     *
     * Add additional fields for specific types like 'parent' and 'relate'.
     * Setup primary model editable.
     * Setup drag-n-drop functionality.
     */
    _renderHtml: function() {
        this.meta = this._generateMetadata(this.mergeFields);

        this._super("_renderHtml");

        this.rowFields = {};
        _.each(this.fields, function(field) {
            if (field.model.id && _.isUndefined(field.parent)) {
                this.rowFields[field.model.id] = this.rowFields[field.model.id] || [];
                this.rowFields[field.model.id].push(field);
            }
        }, this);
        this.setPrimaryEditable(this.primaryRecord.id);
        this.setDraggable();
    },

    /**
     * Shows confirmation message if records are identical.
     * @protected
     */
    _showAlertIfIdentical: function() {
        if (!this.collection.length) {
            return;
        }

        var self = this,
            alternatives = this.collection.without(this.primaryRecord);

        if (this._isSimilar(this.primaryRecord, alternatives)) {
            app.alert.show('merge_confirmation_identical', {
                level: 'confirmation',
                messages: app.lang.get('TPL_MERGE_DUPLICATES_IDENTICAL', this.module),
                onConfirm: function() {
                    self.layout.trigger('mergeduplicates:save:fire');
                }
            });
        }
    },

    /**
     * Set ups label of primary record as draggable using jQuery UI Sortable plugin.
     */
    setDraggable: function() {
        var self = this,
        mergeContainer = this.$('[data-container=merge-container]');
        mergeContainer.find('[data-container=primary-label]').sortable({
            connectWith: self.$('[data-container=primary-label]'),
            appendTo: mergeContainer,
            axis: 'x',
            disableSelection: true,
            cursor: 'move',
            placeholder: 'primary-lbl-placeholder-span',
            start: function(event, ui) {
                self.$('[data-container=primary-label]').addClass('primary-lbl-placeholder');
            },
            stop: _.bind(self._onStopSorting, self)
        });

        mergeContainer.find('[data-container=primary-label].disabled').sortable(
            'option', 'disabled', true
        );
    },

    /**
     * Handler for jQuery UI Sortable plugin event triggered when sorting has stopped.
     *
     * Set ups choosed record as primary and make it editable.
     * If old primary record is changed shows confirmation message to confirm action.
     *
     * @param {Event} event
     * @param {Object} ui
     */
    _onStopSorting: function(event, ui) {
        var self = this,
            droppedTo = ui.item.parents('[data-record-id]');

        self.$('[data-container=primary-label]').removeClass('primary-lbl-placeholder');
        // short circuit if we didn't land on anything
        if (droppedTo.length === 0) {
            self.$('[data-container=primary-label]').sortable('cancel');
            return;
        }

        if (self.primaryRecord && self.primaryRecord.id !== droppedTo.data('record-id')) {
            var changedAttributes = self.primaryRecord.changedAttributes(
                self.primaryRecord.getSynced()
            );
            if (!_.isEmpty(changedAttributes)) {
                app.alert.show('change_primary_confirmation', {
                    level: 'confirmation',
                    messages: app.lang.get('LBL_MERGE_UNSAVED_CHANGES'),
                    onConfirm: function() {
                        self.primaryRecord.revertAttributes();
                        self.setPrimaryEditable(droppedTo.data('record-id'));
                    },
                    onCancel: function() {
                        self.$('[data-record-id=' + self.primaryRecord.get('id') + '] ' +
                            '[data-container=primary-label]').sortable('cancel');
                    }
                });
                return;
            }
            self.setPrimaryEditable(droppedTo.data('record-id'));
        }
    },

    /**
     * Enable/disable radio buttons according to ACL access to fields for all models.
     */
    checkCopyRadioButtons: function() {
        if (!this.primaryRecord) {
            return;
        }
        _.each(this.mergeFields, function(field) {
            var model = this.primaryRecord,
                element = this.$('[data-field-name=' + field.name + '][data-record-id=' + model.id + ']'),
                others = this.$('[data-field-name=' + field.name + '][data-record-id!=' + model.id + ']'),
                editAccess = app.acl.hasAccessToModel('edit', model, field.name);

            element.prop('disabled', !editAccess || field.readonly);

            if (!editAccess || field.readonly) {
                others.prop('disabled', true);
                return;
            }

            _.each(others, function(domElement) {
                var el = $(domElement),
                    readAccess = app.acl.hasAccessToModel(
                        'read',
                        this.collection.get(el.data('record-id')),
                        field.name
                    );
                el.prop('disabled', !readAccess);
            }, this);
        }, this);
    },

    /**
     * Prepare primary record for edit mode.
     *
     * Toggle primary record in edit mode, setup panel title and
     * update preview panel if it is opened. Make sure we get the model in
     * the collection, with all fields in it. If id parameter is provided
     * switch primary record to new model before and revert old primary record
     * to standard record. If new model is same as primary no action is taken.
     * Triggers `duplicate:format:field` before toggle fields.
     *
     * @param {String} [id] The record representing the new primary model.
     */
    setPrimaryEditable: function(id) {

        var oldPrimaryRecord = this.primaryRecord,
            newPrimaryRecord = this.collection.get(id || null);

        if (!_.isUndefined(newPrimaryRecord) && newPrimaryRecord !== oldPrimaryRecord) {
            this.setPrimaryRecord(newPrimaryRecord);
        }

        if (!this.primaryRecord) {
            return;
        }

        if (oldPrimaryRecord && oldPrimaryRecord !== this.primaryRecord) {
            this.toggleFields(this.rowFields[oldPrimaryRecord.id], false);
        }

        this.primaryRecord.trigger('duplicate:format:field');

        this.toggleFields(this.rowFields[this.primaryRecord.id], true);
        this.updatePrimaryTitle(app.utils.getRecordName(this.primaryRecord));
        if (this.isPreviewOpen) {
            this.updatePreviewRecord(this.primaryRecord);
        }
        this.$('.primary-edit-mode').removeClass('primary-edit-mode');
        this.$('[data-record-id=' + this.primaryRecord.id + ']').addClass('primary-edit-mode');
        this.resetRadioSelection(this.primaryRecord.id);
        this.checkCopyRadioButtons();
        this._disableRemovePrimary();

        if (this.collection.length <= 2) {
            this.$('[data-action=delete]').css('visibility', 'hidden');
        }
    },

    /**
     * Checks can primary record be removed or not and if not hides remove control.
     *
     * Primary record cannot be removed is there is not other model with edit access.
     *
     * @protected
     */
    _disableRemovePrimary: function() {
        var disableRemovePrimary = !_.some(this.collection.models, function(model) {
            return model !== this.primaryRecord && app.acl.hasAccessToModel('edit', model);
        }, this);

        this.$('[data-record-id=' + this.primaryRecord.get('id') + ']')
            .find('[data-action=delete]')
            .css('visibility', (disableRemovePrimary ? 'hidden' : 'visible'));
    },

    resetRadioSelection: function(modelId) {
        this.$('[data-record-id=' + modelId + '] input[type=radio]').attr('checked', true);
    },

    /**
     * Set a given model as primary.
     *
     * If the given module is already the primary record no action will be taken.
     * This will toggle off all the events of the old primary record and
     * setup the events for the new model. It will also setup primary record
     * 'change' event handler to updates title of panel,
     * 'mergeduplicates:primary:saved' to remove others models and
     * 'mergeduplicates:primary:merged' event handler to close drawer.
     *
     * @param {Data.Bean} model Primary model.
     */
    setPrimaryRecord: function(model) {
        if (this.primaryRecord === model) {
            return;
        }

        if (this.primaryRecord instanceof Backbone.Model) {
            this.primaryRecord.off(null, null, this);
        }

        this.primaryRecord = model;

        this.primaryRecord.on('change', function(model) {
            // Reapply every change on preview model
            if (!_.isEmpty(this.previewModel)) {
                var changedAttributes = this.primaryRecord.changedAttributes();

                this.previewModel.set(_.mapObject(changedAttributes, function(value, fieldName) {
                    return this.primaryRecord.get(fieldName);
                }, this));
            }

            this.updatePrimaryTitle(app.utils.getRecordName(this.primaryRecord));
        }, this);

        this.primaryRecord.on('mergeduplicates:primary:saved', function() {
            this._mergeRelatedRecords();
        }, this);

        this.primaryRecord.on('mergeduplicates:related:merged', function() {
            this._onRelatedMerged();
        }, this);

        this.primaryRecord.on('mergeduplicates:primary:merged', function() {
            app.alert.dismiss('mergeduplicates_merging');
            this._showSuccessMessage();
            app.drawer.close(true, this.primaryRecord);
        }, this);

        this.primaryRecord.on('validation:complete', function(isValid) {
            if (!isValid) {
                var headerpaneView = this.layout.getComponent('merge-duplicates-headerpane');
                headerpaneView.getField('cancel_button').setDisabled(false);
            }
        }, this);
    },

    /**
     * Event handler for radio box controls.
     *
     * Before copy value from model or restore value
     * triggers `before duplicate:field` event.
     *
     * @param {Event} evt Mouse click event.
     */
    triggerCopy: function(evt) {
        var currentTarget = this.$(evt.currentTarget),
            recordId = currentTarget.data('record-id'),
            fieldName = currentTarget.data('field-name'),
            fieldDefs = app.metadata.getModule(this.module).fields,
            model;

        if (_.isUndefined(this.primaryRecord) ||
            _.isUndefined(this.primaryRecord.id) ||
            _.isUndefined(recordId) ||
            _.isUndefined(fieldName) ||
            _.isUndefined(fieldDefs[fieldName])
        ) {
            return;
        }

        model = this.collection.get(recordId);
        if (_.isUndefined(model)) {
            return;
        }

        if (!app.acl.hasAccessToModel('edit', this.primaryRecord, fieldName) ||
            !app.acl.hasAccessToModel('read', model, fieldName)) {
            return;
        }

        var data = currentTarget.data();
        // Unlike data(), attr() doesn't perform type conversions if possible.
        // This is good because recordItemId can sometimes be numeric but must be type of string always.
        data.recordItemId = currentTarget.attr('data-record-item-id');
        data = _.extend({}, data, {
            checked: currentTarget.prop('type') === 'checkbox' ?
                currentTarget.prop('checked') : true
        });
        if (this.triggerBefore('duplicate:field', {model: model, data: data}) === false) {
            return;
        }

        if (model === this.primaryRecord) {
            this.revert(fieldName);
        } else {
            this.copy(fieldName, model);
        }
    },

    /**
     * Copy value from selected field to primary record.
     *
     * Setups new value current field and additional fields.
     * Also triggers `duplicate:field` event on the primary model.
     *
     * @param {String} fieldName Name of field to copy.
     * @param {Data.Bean} model Model to copy from.
     */
    copy: function(fieldName, model) {
        this._setRelatedFields(fieldName, model);
        this.primaryRecord.set(fieldName, model.get(fieldName));

        this.primaryRecord.trigger(
            'duplicate:field:' + fieldName,
            model !== this.primaryRecord ? model : null,
            model !== this.primaryRecord ? model.get(fieldName) : null
        );
    },

    /**
     * Revert value of field to latest sync state.
     *
     * Revert original values.
     * Also triggers `duplicate:field` event on the primary model.
     *
     * @param {String} fieldName Name of field to revert.
     */
    revert: function(fieldName) {
        var syncedAttributes = this.primaryRecord.getSynced();

        this._setRelatedFields(fieldName, this.primaryRecord, true);
        this.primaryRecord.set(
            fieldName,
            !_.isUndefined(syncedAttributes[fieldName]) ?
                syncedAttributes[fieldName] :
                this.primaryRecord.get(fieldName)
        );

        this.primaryRecord.trigger(
            'duplicate:field:' + fieldName,
            this.primaryRecord,
            this.primaryRecord.get(fieldName)
        );
    },

    /**
     * Event handler for model delete button.
     *
     * Shows alert message to confirm model removing.
     *
     * @param {Event} evt Mouse click event.
     */
    triggerDelete: function(evt) {
        var recordId = this.$(evt.currentTarget).closest('[data-record-id]').data('recordId'),
            model = this.collection.get(recordId),
            self = this;
        if (this.collection.length <= 2 || !recordId || !model) {
            return;
        }

        if (model === this.primaryRecord) {
            var allow = _.some(this.collection.models, function(model) {
                return model !== this.primaryRecord && app.acl.hasAccessToModel('edit', model);
            }, this);
            if (!allow) {
                return;
            }
        }
        app.alert.show('record-delete-confirm', {
            level: 'confirmation',
            messages: app.lang.get('LBL_MERGE_DUPLICATES_REMOVE', this.module),
            onConfirm: function() {
                self.deleteFromMerge(model);
                self.$('[data-container="merge-container"]').attr('class', function(){
                    return $(this).attr('class').replace(
                        /\b(num\-cols\-)(\d+)\b/g,
                        '$1' + self.collection.length
                    );
                });
            }
        });
    },

    /**
     * Delete model from collection to merge.
     *
     * If removed model is primary find first model in
     * collection an setup it as primary.
     *
     * @param {Data.Bean} model Model to remove.
     */
    deleteFromMerge: function(model) {

        this.collection.remove(model, {silent: true});

        var selModelEl = '[data-container=merge-record][data-record-id=' + model.get('id') + ']';

        if (model === this.primaryRecord) {
            var primary = this._findPrimary(this.collection.models),
                selNewPrimaryEl = '[data-container=merge-record][data-record-id=' + primary.get('id') + ']',
                primaryEl = this.$(selNewPrimaryEl).find('[data-container=primary-label]'),
                primaryLabel = this.$(selModelEl).find('[data-container=primary-label-span]');

            primaryEl.append(primaryLabel);
            this.setPrimaryEditable(primary.get('id'));

        }

        this.$(selModelEl).remove();

        if (this.collection.length <= 2) {
            this.$('[data-action=delete]').css('visibility', 'hidden');
        }
    },

    /**
     * Copy additional fields to primary model.
     *
     * Cases:
     * 1. field type is 'relate' and 'parent' (def.id_name)
     *     - def.id_name contains field name for id of related.
     * 2. field type is 'parent' (def.type_name)
     *     - def.type_name contains field name for type of related.
     *
     * @param {String} fieldName Name of main field to copy.
     * @param {Data.Bean} model Model from which values should be coped.
     * @param {Boolean} synced Use last synced attributes of model for copy or not.
     * @protected
     */
    _setRelatedFields: function(fieldName, model, synced) {
        synced = synced || false;

        var fieldDefs = app.metadata.getModule(this.module).fields;
            defs = fieldDefs[fieldName],
            syncedAttributes = synced ? model.getSynced() : {},
            fields = _.union(defs.populate_list, defs.related_fields);

        _.each(this.relatedFieldsMap, function(field) {
            if (!_.isUndefined(defs[field]) && !_.isUndefined(fieldDefs[defs[field]].name)) {
                fields.push(fieldDefs[defs[field]].name);
            };
        });

        // FIXME: populate_list is only available on related fields plus
        // related_fields is only available on fieldsets, so this logic should
        // be implemented on field side thus calling a method like
        // this.fields[fieldName].revertTo(model); here SC-3467
        _.each(fields, function(relatedField) {
            if (_.isUndefined(fieldDefs[relatedField])) {
                return;
            }

            this.primaryRecord.set(
                relatedField,
                !_.isUndefined(syncedAttributes[relatedField]) ?
                    syncedAttributes[relatedField] :
                    model.get(relatedField)
            );
        }, this);
    },

    /**
     * Returns defs of bean fields that are valid link for merge related records.
     *
     * @return {Object[]} Defs of fields.
     * @protected
     */
    _getRelatedLinks: function() {
        var fieldDefs = app.metadata.getModule(this.module).fields,
            excludedLinks = this._getExcludedRelatedLinks();

        return _.filter(fieldDefs, function(field) {
            return !_.isUndefined(field.type) && field.type === 'link' &&
                !_.contains(excludedLinks, field.name) &&
                this._isValidRelateLink(field) &&
                this._isValidRelateLinkType(field);
        }, this);
    },

    /**
     * Returns names of links that has been processed using `relate` fields on UI.
     *
     * @return {String[]} Names of links.
     * @protected
     */
    _getExcludedRelatedLinks: function() {
        var excludedLinks = [],
            fieldDefs = app.metadata.getModule(this.module).fields;

        _.each(this.mergeFields, function(mergeField) {
            var def = fieldDefs[mergeField.name];
            if (def.type === 'relate' && !_.isUndefined(def.link)) {
                excludedLinks.push(def.link);
            }
        }, this);

        return excludedLinks;
    },

    /**
     * Check is certain link valid for merge related records.
     *
     * Returns false in cases:
     * 1. link name isn't defined
     * 2. link module doesn't exist in our metadata
     * 3. link is in global black list
     * 4. link is in black list for current module
     * 5. merge is disabled in link defs
     *
     * @param {Object} link Defenition of link field.
     * @return {boolean} Is link valid for merge related.
     *
     * @protected
     */
    _isValidRelateLink: function(link) {
        if (!link || !link.name) {
            return false;
        }

        var module = app.data.getRelatedModule(this.module, link.name);
        if (_.isEmpty(app.metadata.getModule(module))) {
            return false;
        }

        if (_.contains(this.relatesBlacklist, link.name)) {
            return false;
        }

        if (!_.isUndefined(this.relatesBlacklistForModule[this.module]) &&
            _.contains(this.relatesBlacklistForModule[this.module], link.name)
        ) {
            return false;
        }

        if (!_.isUndefined(link.duplicate_merge) &&
            (link.duplicate_merge === 'disabled' ||
                link.duplicate_merge === 'false' ||
                link.duplicate_merge === false)
        ) {
            return false;
        }

        return true;
    },

    /**
     * Check is certain link valid for merge related records by link type.
     *
     * Returns false for cases:
     * 1. type of link is `one`
     *
     * @param {Object} link Defenition of link field.
     * @return {boolean} Is link valid for merge related by link type.
     * @protected
     */
    _isValidRelateLinkType: function(link) {
        if (!_.isUndefined(link.link_type) && link.link_type === 'one') {
            return false;
        }
        return true;
    },

    /**
     * Merge related records using queue.
     * Triggers `mergeduplicates:related:merged` event on finish.
     * @protected
     */
    _mergeRelatedRecords: function() {
        var self = this,
            alternativeModels = _.without(this.collection.models, this.primaryRecord),
            relatedLinks = _.pluck(this._getRelatedLinks(), 'name'),
            progressView,
            queue,
            tasks = [];

        this.mergeStat = {
            records: this.collection.models.length,
            total: 0, total_errors: 0, total_fetch_errors: 0
        };

        this.mergeProgressModel = new Backbone.Model({
            isStopped: false
        });

        this.mergeRelatedCollection = this.getMergeRelatedCollection();

        if (!alternativeModels || !alternativeModels.length) {
            self.primaryRecord.trigger('mergeduplicates:related:merged');
            return;
        }

        if (!relatedLinks || !_.isArray(relatedLinks) || !relatedLinks.length) {
            self.primaryRecord.trigger('mergeduplicates:related:merged');
            return;
        }

        progressView = this._getProgressView();
        progressView.reset();
        progressView.setTotalRecords(alternativeModels.length * relatedLinks.length);

        this.mergeProgressModel.trigger('massupdate:start');

        _.each(relatedLinks, function(link) {
            _.each(alternativeModels, function(model) {
                tasks.push({
                    collection: self._createRelatedCollection(model, link)
                });
            });
        });
        queue = async.queue(function(task, callback) {
            if (self.mergeProgressModel.get('isStopped')) {
                callback.call();
                return;
            }
            self._mergeRelatedCollection(task.collection, callback);
        }, this._settings.merge_relate_fetch_concurrency);
        queue.drain = function() {
            var finishMerge = function() {
                self.mergeProgressModel.trigger('massupdate:end');
                if (!self.mergeProgressModel.get('isStopped')) {
                    self.primaryRecord.trigger('mergeduplicates:related:merged');
                }

                if (self.mergeStat.total_fetch_errors > 0 || self.mergeStat.total_errors > 0) {
                    var headerpaneView = self.layout.getComponent('merge-duplicates-headerpane');
                    headerpaneView.getField('cancel_button').setDisabled(false);
                }
            };
            // Wait until all related records be merged or finish merge.
            self.mergeRelatedCollection.queue.running() ?
                self.mergeRelatedCollection.queue.drain = finishMerge :
                finishMerge();
        };
        queue.push(tasks, function(err) {});
    },

    /**
     * Creates collection against the parent model to merge related records.
     *
     * @return {Backbone.Collection} Merge collection.
     */
    getMergeRelatedCollection: function() {
        var constructor = Backbone.Collection.extend({
            method: 'create',
            queue: null,
            view: null,

            /**
             * @property {String} id Primary record's ID.
             */
            id: this.primaryRecord.id,

            /**
             * @property {String} module Primary record's module name.
             */
            module: this.primaryRecord.module,

            /**
             * @property {Number} attempt Current trial attempt number.
             */
            attempt: 0,

            /**
             * @inheritdoc
             *
             * Sync added set of records and clear collection.
             */
            initialize: function(models, options) {
                this.view = options.view;
                this.queue = async.queue(
                    _.bind(function(task, callback) {
                        this.sync('update', this, {
                            chunk: task,
                            queueSuccess: callback
                        });
                    }, this),
                    this.view._settings.merge_relate_update_concurrency
                );
                this.on('add', function(model, options) {
                    this.queue.push(
                        {
                            link_name: model.link.name,
                            ids: _.pluck(this.models, 'id')
                        },
                        function(err) {}
                    );
                    this.reset();
                }, this);
            },

            /**
             * @inheritdoc
             *
             * Overrides default behaviour to use related API and send related
             * records into chunks.
             */
            sync: function(method, model, options) {
                var apiMethod = options.method || this.method,
                    url = app.api.buildURL(this.module, method, {link: true, id: this.id}, options.params),
                    callbacks = {
                        success: function(data, response) {
                            model.view.mergeStat.total = model.view.mergeStat.total + options.chunk.ids.length;
                            options.queueSuccess();
                            if (_.isFunction(options.success)) {
                                options.success(data);
                            }
                        },
                        error: function(xhr) {
                            model.attempt = model.attempt + 1;
                            model.view.mergeProgressModel.trigger('massupdate:item:attempt', model);
                            if (model.attempt <= (model.view._settings.merge_relate_max_attempt)) {
                                app.api.call(apiMethod, url, options.chunk, callbacks);
                            } else {
                                model.attempt = 0;
                                model.view.mergeStat.total_errors = model.view.mergeStat.total_errors + 1;
                                model.view.mergeProgressModel.trigger('massupdate:item:fail', model);
                            }
                        },
                        complete: function(xhr) {
                            if (_.isFunction(options.complete)) {
                                options.complete(xhr);
                            }
                        }
                    };
                app.api.call(apiMethod, url, options.chunk, callbacks);
            }
        }),
        collection = new constructor([], {view: this});
        return collection;
    },

    /**
     * Called when merge related records process is finished.
     *
     * @protected
     */
    _onRelatedMerged: function() {
        var self = this;

        if (this.mergeStat.total_fetch_errors > 0 ||
            this.mergeStat.total_errors > 0
        ) {
            app.alert.show('final_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_MERGE_DUPLICATES_FAIL_PROCESS', this.module),
                onConfirm: function() {
                    self._onMergeRelatedCompleted();
                },
                onCancel: function() {
                    self.mergeProgressModel.trigger('massupdate:end');
                },
                autoClose: false
            });
            return;
        }

        this._onMergeRelatedCompleted();
    },

    /**
     * Starts removing models and shows process message.
     * @protected
     */
    _onMergeRelatedCompleted: function() {
        app.alert.show('mergeduplicates_merging', {
            level: 'process',
            title: app.lang.get('LBL_SAVING', this.module)
        });
        this._removeMerged();
    },

    /**
     * Creates related collection.
     * Setup additional parameters for merge process.
     *
     * @param {Data.Bean} model Model to create related collection.
     * @param {String} link Relationship link name.
     * @return {Data.BeanCollection} Created collection.
     * @protected
     */
    _createRelatedCollection: function(model, link) {
        var relatedCollection = app.data.createRelatedCollection(model, link);

        return _.extend(relatedCollection, {
            attempt: 0,
            maxAllowAttempt: this._settings.merge_relate_max_attempt,
            objectName: app.data.getRelatedModule(this.primaryRecord.module, link)
        });
    },

    /**
     * Recursively merge related collection.
     *
     * Recursively fetch data from link collection and creates (links) beans
     * to primary record.
     *
     * @param {Data.BeanCollection} collection Collection to merge.
     * @param {Function} callback Function called on end.
     * @param {Number} offset Offset to fetch data.
     * @protected
     */
    _mergeRelatedCollection: function(collection, callback, offset) {

        if (this.mergeProgressModel.get('isStopped')) {
            callback.call();
            return;
        }

        offset = offset || 0;

        var self = this,
            onCollectionMerged = function() {
                self.mergeProgressModel.trigger('massupdate:item:processed');
                callback.call();
            };

        collection.fetch({
            relate: true,
            limit: this._settings.merge_relate_fetch_limit,
            offset: offset,
            fields: ['id'],
            apiOptions: {
                timeout: this._settings.merge_relate_fetch_timeout,
                skipMetadataHash: true
            },
            success: function(data, response, options) {
                if (!data || !data.models || !data.models.length) {
                    onCollectionMerged.call();
                    return;
                }

                self.mergeRelatedCollection.add(data.models);

                if (!_.isUndefined(data.next_offset) && data.next_offset !== -1) {
                    self._mergeRelatedCollection(collection, callback, data.next_offset);
                } else {
                    onCollectionMerged.call();
                }
            },
            error: function() {
                collection.attempt = collection.attempt + 1;
                self.mergeProgressModel.trigger('massupdate:item:attempt', collection);
                if (collection.attempt <= collection.maxAllowAttempt) {
                    self._mergeRelatedCollection(collection, callback, offset);
                } else {
                    self.mergeStat.total_fetch_errors = self.mergeStat.total_fetch_errors + 1;
                    self.mergeProgressModel.trigger('massupdate:item:fail', collection);
                    onCollectionMerged.call();
                }
            }
        });
    },

    /**
     * Create the Progress view unless it is initialized.
     * Return the progress view component in the same layout.
     *
     * @return {Backbone.View} MergeDuplicatesProgress view component.
     * @protected
     */
    _getProgressView: function() {
        var progressView = this.layout.getComponent('merge-duplicates-progress');
        if (!progressView) {
            progressView = app.view.createView({
                context: this.context,
                type: 'merge-duplicates-progress',
                layout: this.layout,
                model: this.mergeProgressModel
            });
            this.layout._components.push(progressView);
            this.layout.$el.append(progressView.$el);
        }
        progressView.render();
        return progressView;
    },

    /**
     * Displays alert message with last merge related records stat.
     *
     * @protected
     */
    _showSuccessMessage: function() {
        app.alert.show('mergerelated_final_notice', {
            level: 'success',
            messages: app.lang.get('TPL_MERGE_DUPLICATES_STAT', this.module, {
                stat: this.mergeStat
            }),
            autoClose: true,
            autoCloseDelay: 8000
        });
    },

    /**
     * @inheritdoc
     *
     * Override 'reset' event for collection to setup first model ar primary.
     */
    bindDataChange: function() {
        if (!this.collection) {
            return;
        }
        this.collection.on('reset', function(coll) {
            if (coll.length) {
                _.each(coll.models, function(model) {
                    model.readonly = !app.acl.hasAccessToModel('edit', model);
                }, this);
                this.setPrimaryRecord(this._findPrimary(coll.models));
            }
            if (this.disposed) {
                return;
            }
            this.render();
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Off all events on primary model.
     */
    _dispose: function() {
        if (!_.isEmpty(this.primaryRecord)) {
            this.primaryRecord.off(null, null, this);
        }
        this._super('_dispose');
    }
})
