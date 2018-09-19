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
 * Form for creating a filter.
 *
 * Part of {@link View.Layouts.Base.FilterpanelLayout}.
 *
 * @class View.Views.Base.FilterRowsView
 * @alias SUGAR.App.view.views.BaseFilterRowsView
 * @extends View.View
 */
({
    events: {
        'click [data-action=add]': 'addRow',
        'click [data-action=remove]': 'removeRow',
        'change [data-filter=field] input[type=hidden]': 'handleFieldSelected',
        'change [data-filter=operator] input[type=hidden]': 'handleOperatorSelected'
    },

    className: 'filter-definition-container',

    filterFields: [],

    lastFilterDef: [],

    /**
     * Map of fields types.
     *
     * Specifies correspondence between field types and field operator types.
     */
    fieldTypeMap: {
        'datetime' : 'date',
        'datetimecombo' : 'date'
    },

    /**
     * @override
     * @param {Object} opts
     */
    initialize: function(opts) {
        //Load partial
        this.formRowTemplate = app.template.get("filter-rows.filter-row-partial");

        this._super('initialize', [opts]);

        this.loadFilterOperators(this.module);

        /**
         * FIXME: we should consider moving it to metadata instead. (see TY-177).
         * Storage for operators that have no values associated with them
         *
         * @private
         * @property {Array}
         * */
        this._operatorsWithNoValues = ['$empty', '$not_empty'];

        this.listenTo(this.layout, "filterpanel:change:module", this.handleFilterChange);
        this.listenTo(this.layout, "filter:create:open", this.openForm);
        this.listenTo(this.layout, "filter:create:close", this.render);
        this.listenTo(this.context, "filter:create:save", this.saveFilter);
        this.listenTo(this.layout, "filter:create:delete", this.confirmDelete);
    },

    /**
     * Loads filterable fields and operators for supplied module.
     *
     * @param {string} module Selected module name.
     */
    handleFilterChange: function(module) {
        if (_.isEmpty(app.metadata.getModule(module, 'filters')) || this.moduleName === module) {
            return;
        }

        /**
         * Name of the selected module which triggered the filter change.
         *
         * @property {string}
         */
        this.moduleName = module;

        this.loadFilterFields(module);
        this.loadFilterOperators(module);
    },

    /**
     * Loads the list of filter fields for supplied module.
     *
     * @param {string} module The module to load the filter fields for.
     */
    loadFilterFields: function(module) {
        if (_.isEmpty(app.metadata.getModule(module, 'filters'))) {
            return;
        }

        this.fieldList = app.data.getBeanClass('Filters').prototype.getFilterableFields(module);
        this.filterFields = {};

        _.each(this.fieldList, function(value, key) {
            this.filterFields[key] = app.lang.get(value.vname, module);
        }, this);
    },

    /**
     * Loads the list of filter operators for supplied module.
     *
     * @param {string} [module] The module to load the filters for.
     */
    loadFilterOperators: function(module) {
        this.filterOperatorMap = app.metadata.getFilterOperators(module);
    },

    /**
     * Handler for filter:create:open event
     * @param {Bean} filterModel
     */
    openForm: function(filterModel) {
        var template = filterModel.get('filter_template') || filterModel.get('filter_definition');
        if (_.isEmpty(template)) {
            this.render();
            this.addRow();
        } else {
            this.populateFilter();
        }
        // After populating the form, save the current edit state
        this.saveFilterEditState();

        //shortcut keys
        app.shortcuts.register({
            id: 'Filter:Add',
            keys: '+',
            component: this,
            description: 'LBL_SHORTCUT_FILTER_ADD',
            handler: function() {
                this.$('[data-action=add]').last().click();
            }
        });
        app.shortcuts.register({
            id: 'Filter:Remove',
            keys: '-',
            component: this,
            description: 'LBL_SHORTCUT_FILTER_REMOVE',
            handler: function() {
                this.$('[data-action=remove]').last().click();
            }
        });
    },

    /**
     * Save the filter.
     *
     * @param {String} [name] The name of the filter.
     */
    saveFilter: function(name) {
        var self = this,
            obj = {
                filter_definition: this.buildFilterDef(true),
                filter_template: this.buildFilterDef(),
                name: name || this.context.editingFilter.get('name'),
                module_name: this.moduleName
            },
            message = app.lang.get('TPL_FILTER_SAVE', this.moduleName, {name: name});

        this.context.editingFilter.save(obj, {
            success: function(model) {
                self.context.trigger('filter:add', model);
                self.layout.trigger('filter:toggle:savestate', false);
            },
            showAlerts: {
                'success': {
                    title: app.lang.get('LBL_SUCCESS'),
                    messages: message
                }
            }
        });
    },


    /**
     * Popup alert to confirm delete action.
     */
    confirmDelete: function() {
        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_DELETE_FILTER_CONFIRMATION', this.moduleName),
            onConfirm: _.bind(this.deleteFilter, this)
        });
    },

    /**
     * Delete the filter.
     */
    deleteFilter: function() {
        var self = this,
            name = this.context.editingFilter.get('name'),
            message = app.lang.get('TPL_DELETE_FILTER_SUCCESS', this.moduleName, {name: name});

        this.context.editingFilter.destroy({
            success: function(model) {
                self.layout.trigger('filter:remove', model);
            },
            showAlerts: {
                'success': {
                    title: app.lang.get('LBL_SUCCESS'),
                    messages: message
                }
            }
        });
        this.layout.trigger('filter:create:close');
    },

    /**
     * Get filterable fields from the module metadata
     * @param {String} moduleName
     * @return {Object}
     */
    getFilterableFields: function(moduleName) {
        var moduleMeta = app.metadata.getModule(moduleName),
            fieldMeta = moduleMeta.fields,
            fields = {};
        if (moduleMeta.filters) {
            _.each(moduleMeta.filters, function(templateMeta) {
                if (templateMeta.meta && templateMeta.meta.fields) {
                    fields = _.extend(fields, templateMeta.meta.fields);
                }
            });
        }

        _.each(fields, function(fieldFilterDef, fieldName) {
            var fieldMetaData = app.utils.deepCopy(fieldMeta[fieldName]);
            if (_.isEmpty(fieldFilterDef)) {
                fields[fieldName] = fieldMetaData || {};
            } else {
                fields[fieldName] = _.extend({name: fieldName}, fieldMetaData, fieldFilterDef);
            }
            delete fields[fieldName]['readonly'];
        });

        return fields;
    },

    /**
     * Utility function that instantiates a field for this form.
     *
     * The field action is manually set to `detail` because we want to render
     * the `edit` template but the action remains `detail` (filtering).
     *
     * @param {Data.Bean} model A bean necessary to the field for storing the
     *   value(s).
     * @param {Object} def The field definition.
     * @return {View.Field} The field component.
     */
    createField: function(model, def) {
        var obj = {
            meta: {
                view: "edit"
            },
            def: def,
            model: model,
            context: app.controller.context,
            viewName: "edit",
            view: this
        };
        var field = app.view.createField(obj);
        field.action = 'detail';
        return field;
    },

    /**
     * Add a row to the next element of the event target that triggered it or
     * in the end of the list.
     *
     * @param {Event} [e] The event that triggered the row.
     * @return {Element} The new initialized appended row element.
     */
    addRow: function(e) {
        var $row;

        if (e) {
            // Triggered by clicking the plus sign. Add the row to that point.
            $row = this.$(e.currentTarget).closest('[data-filter=row]');
            $row.after(this.formRowTemplate());
            $row = $row.next();
            this.layout.trigger('filter:toggle:savestate', true);
        }
        return this.initRow($row);
    },

    /**
     * Initializes a row either with the retrieved field values or the
     * default field values.
     *
     * @param {jQuery} [$row] The related filter row.
     * @param {Object} [data] The values to set in the fields.
     * @return {jQuery} $row The initialized row element.
     */
    initRow: function($row, data) {
        $row = $row || $(this.formRowTemplate()).appendTo(this.$el);
        data = data || {};
        var model, field, $fieldValue, $fieldContainer;

        // Init the row with the data available.
        $row.data('name', data.name);
        $row.data('operator', data.operator);
        $row.data('value', data.value);

        // Create a blank model for the enum field, and set the field value if
        // we know it.
        model = app.data.createBean(this.moduleName);
        if (data.name) {
            model.set('filter_row_name', data.name);
        }
        field = this.createField(model, {
            name: 'filter_row_name',
            type: 'enum',
            options: this.filterFields
        });

        // Add the field to the dom.
        $fieldValue = $row.find('[data-filter=field]');
        $fieldContainer = $(field.getPlaceholder().string);
        $fieldContainer.appendTo($fieldValue);

        // Store the field in the data attributes.
        $row.data('nameField', field);

        this._renderField(field, $fieldContainer);

        if (data.name) {
            this.initOperatorField($row);
        }
        return $row;
    },


    /**
     * Remove a row
     * @param {Event} e
     */
    removeRow: function(e) {
        var $row = this.$(e.currentTarget).closest('[data-filter=row]'),
            fieldOpts = [
                {field: 'nameField', value: 'name'},
                {field: 'operatorField', value: 'operator'},
                {field: 'valueField', value: 'value'}
            ];

        this._disposeRowFields($row, fieldOpts);
        $row.remove();
        this.layout.trigger('filter:toggle:savestate', true);
        if (this.$('[data-filter=row]').length === 0) {
            this.addRow();
        }
    },

    /**
     * Validate all filter rows.
     *
     * @param {Array} rows A list of rows to validate.
     * @return {Boolean} `true` if all filter rows are valid, `false`
     *   otherwise.
     */
    validateRows: function(rows) {
        return _.every(rows, this.validateRow, this);
    },

    /**
     * Verify the value of the row is not empty.
     *
     * @param {Element} $row The row to validate.
     * @return {Boolean} `true` if valid, `false` otherwise.
     *
     * TODO we should receive the data only and be jQuery agnostic.
     */
    validateRow: function(row) {
        var $row = $(row),
            data = $row.data();

        if (_.contains(this._operatorsWithNoValues, data.operator)) {
            return true;
        }

        // for empty value in currency we dont want to validate
        if (!_.isUndefined(data.valueField) && !_.isArray(data.valueField) && data.valueField.type ==='currency'
            && (_.isEmpty(data.value) || (_.isObject(data.value) &&
            _.isEmpty(data.valueField.model.get(data.name))))) {
            return false;
        }

        //For date range and predefined filters there is no value
        if (data.isDateRange || data.isPredefinedFilter) {
            return true;
        } else if (data.isFlexRelate) {
            return data.value ?
                _.reduce(data.value, function(memo, val) {
                    return memo && !_.isEmpty(val);
                }, true) :
                false;
        }

        //Special case for between operators where 2 values are needed
        if (_.contains(['$between', '$dateBetween'], data.operator)) {

            if (!_.isArray(data.value) || data.value.length !== 2) {
                return false;
            }

            switch (data.operator) {
                case '$between':
                    // FIXME: the fields should set a true number (see SC-3138).
                    return !(_.isNaN(parseFloat(data.value[0])) || _.isNaN(parseFloat(data.value[1])));
                case '$dateBetween':
                    return !_.isEmpty(data.value[0]) && !_.isEmpty(data.value[1]);
                default:
                    return false;
            }
        }

        return _.isNumber(data.value) || !_.isEmpty(data.value);
    },

    /**
     * Rerender the view with selected filter
     */
    populateFilter: function() {
        var name = this.context.editingFilter.get('name'),
            filterOptions = this.context.get('filterOptions') || {},
            populate = this.context.editingFilter.get('is_template') && filterOptions.filter_populate,
            filterDef = this.context.editingFilter.get('filter_template') ||
                this.context.editingFilter.get('filter_definition');

        this.render();
        this.layout.trigger('filter:set:name', name);

        if (populate) {
            filterDef = app.data.getBeanClass('Filters').prototype.populateFilterDefinition(filterDef, populate);
        }
        _.each(filterDef, function(row) {
            this.populateRow(row);
        }, this);
        //Set lastFilterDef because the filter has already been applied and fireSearch is called in _disposeRowFields
        this.lastFilterDef = this.buildFilterDef(true);
        this.lastFilterTemplate = this.buildFilterDef();
    },

    /**
     * Populates row fields with the row filter definition.
     *
     * In case it is a template filter that gets populated by values passed in
     * the context/metadata, empty values will be replaced by populated
     * value(s).
     *
     * @param {Object} rowObj The filter definition of a row.
     */
    populateRow: function(rowObj) {
        var moduleMeta = app.metadata.getModule(this.layout.currentModule);
        var fieldMeta = moduleMeta.fields;

        _.each(rowObj, function(value, key) {
            var isPredefinedFilter = (this.fieldList[key] && this.fieldList[key].predefined_filter === true);

            if (key === '$or') {
                var keys = _.reduce(value, function(memo, obj) {
                    return memo.concat(_.keys(obj));
                }, []);

                key = _.find(_.keys(this.fieldList), function(key) {
                    if (_.has(this.fieldList[key], 'dbFields')) {
                        return _.isEqual(this.fieldList[key].dbFields.sort(), keys.sort());
                    }
                }, this);

                // Predicates are identical, so we just use the first.
                value = _.values(value[0])[0];
            } else if (key === '$and') {
                var values = _.reduce(value, function(memo, obj) {
                        return _.extend(memo, obj);
                    }, {});
                var def = _.find(this.fieldList, function(fieldDef) {
                        return _.has(values, fieldDef.id_name || fieldDef.name);
                    }, this);

                var operator = '$equals';
                key = def ? def.name : key;

                //  We want to get the operator from our values object only for currency fields
                if (def && !_.isString(values[def.name]) && def.type === 'currency') {
                    operator = _.keys(values[def.name])[0];
                    values[key] = values[key][operator];
                }
                value = {};
                value[operator] = values;
            } else if (!fieldMeta[key] && !isPredefinedFilter) {
                return;
            }

            if (!this.fieldList[key]) {
                //Make sure we use name for relate fields
                var relate = _.find(this.fieldList, function(field) { return field.id_name === key; });
                // field not found so don't create row for it.
                if (!relate) {
                    return;
                }
                key = relate.name;
                // for relate fields in version < 7.7 we used `$equals` and `$not_equals` operator so for version
                // compatibility & as per TY-159 needed to fix this since 7.7 & onwards we will be using `$in` &
                // `$not_in` operators for relate fields
                if (_.isString(value) || _.isNumber(value)) {
                    value = {$in: [value]};
                } else if (_.keys(value)[0] === '$not_equals') {
                    var val = _.values([value])[0];
                    value = {$not_in: val};
                }
            }

            if (_.isString(value) || _.isNumber(value)) {
                value = {$equals: value};
            }
            _.each(value, function(value, operator) {
                this.initRow(null, {name: key, operator: operator, value: value});
            }, this);
        }, this);
    },

    /**
     * Fired when a user selects a field to filter by
     * @param {Event} e
     */
    handleFieldSelected: function(e) {
        var $el = this.$(e.currentTarget);
        var $row = $el.parents('[data-filter=row]');
        var fieldOpts = [
            {field: 'operatorField', value: 'operator'},
            {field: 'valueField', value: 'value'}
        ];
        this._disposeRowFields($row, fieldOpts);
        this.initOperatorField($row);
    },

    /**
     * Initializes the operator field.
     *
     * @param {jQuery} $row The related filter row.
     */
    initOperatorField: function($row) {
        var $fieldWrapper = $row.find('[data-filter=operator]');
        var data = $row.data();
        var fieldName = data.nameField.model.get('filter_row_name');
        var previousOperator = data.operator;

        // Make sure the data attributes contain the right selected field.
        data['name'] = fieldName;

        if (!fieldName) {
            return;
        }

        // For relate fields
        data.id_name = this.fieldList[fieldName].id_name;
        // For flex-relate fields
        data.type_name = this.fieldList[fieldName].type_name;

        //Predefined filters don't need operators and value field
        if (this.fieldList[fieldName].predefined_filter === true) {
            data.isPredefinedFilter = true;
            this.fireSearch();
            return;
        }

        // Get operators for this filter type
        var fieldType = this.fieldTypeMap[this.fieldList[fieldName].type] || this.fieldList[fieldName].type,
            payload = {},
            types = _.keys(this.filterOperatorMap[fieldType]);

        // For parent field with the operator '$equals', the operator field is
        // hidden and we need to display the value field directly. So here we
        // need to assign 'previousOperator' and 'data.operator variables' to let
        // the value field initialize.
        //FIXME: We shouldn't have a condition on the parent field. TY-352 will
        // fix it.
        if (fieldType === 'parent' && _.isEqual(types, ['$equals'])) {
            previousOperator = data.operator = types[0];
        }

        fieldType === 'parent' ?
            $fieldWrapper.addClass('hide').empty() :
            $fieldWrapper.removeClass('hide').empty();
        $row.find('[data-filter=value]').addClass('hide').empty();

        _.each(types, function(operand) {
            payload[operand] = app.lang.get(
                this.filterOperatorMap[fieldType][operand],
                [this.moduleName, 'Filters']
            );
        }, this);

        // Render the operator field
        var model = app.data.createBean(this.moduleName);

        if (previousOperator) {
            model.set('filter_row_operator', data.operator === '$dateRange' ? data.value : data.operator);
        }

        var field = this.createField(model, {
                name: 'filter_row_operator',
                type: 'enum',
                // minimumResultsForSearch set to 9999 to hide the search field,
                // See: https://github.com/ivaynberg/select2/issues/414
                searchBarThreshold: 9999,
                options: payload
            }),
            $field = $(field.getPlaceholder().string);

        $field.appendTo($fieldWrapper);
        data['operatorField'] = field;

        this._renderField(field, $field);

        var hide = fieldType === 'parent';
        this._hideOperator(hide, $row);

        // We want to go into 'initValueField' only if the field value is known.
        // We need to check 'previousOperator' instead of 'data.operator'
        // because even if the default operator has been set, the field would
        // have set 'data.operator' when it rendered anyway.
        if (previousOperator) {
            this.initValueField($row);
        }
    },

    /**
     * Shows or hides the operator field of the filter row specified.
     *
     * Automatically populates the operator field to have value `$equals` if it
     * is not in midst of populating the row.
     *
     * @param {boolean} hide Set to `true` to hide the operator field.
     * @param {jQuery} $row The filter row of interest.
     * @private
     */
    _hideOperator: function(hide, $row) {
        $row.find('[data-filter=value]')
            .toggleClass('span4', !hide)
            .toggleClass('span8', hide);
    },

    /**
     * Fired when a user selects an operator to filter by
     * @param {Event} e
     */
    handleOperatorSelected: function(e) {
        var $el = this.$(e.currentTarget);
        var $row = $el.parents('[data-filter=row]');
        var fieldOpts = [
            {'field': 'valueField', 'value': 'value'}
        ];
        this._disposeRowFields($row, fieldOpts);
        this.initValueField($row);
    },

    /**
     * Initializes the value field.
     *
     * @param {jQuery} $row The related filter row.
     */
    initValueField: function($row) {
        var data = $row.data();
        var operation = data.operatorField.model.get('filter_row_operator');

        // Make sure the data attributes contain the right operator selected.
        data.operator = operation;
        if (!operation) {
            return;
        }

        if (_.contains(this._operatorsWithNoValues, operation)) {
            this.fireSearch();
            return;
        }

        // Patching fields metadata
        var moduleName = this.moduleName,
            module = app.metadata.getModule(moduleName),
            fields = app.metadata._patchFields(moduleName, module, app.utils.deepCopy(this.fieldList));

        // More patch for some field types
        var fieldName = $row.find('[data-filter=field] input[type=hidden]').select2('val'),
            fieldType = this.fieldTypeMap[this.fieldList[fieldName].type] || this.fieldList[fieldName].type,
            fieldDef = fields[fieldName];

        switch (fieldType) {
            case 'enum':
                fieldDef.isMultiSelect = true;
                // Set minimumResultsForSearch to a negative value to hide the search field,
                // See: https://github.com/ivaynberg/select2/issues/489#issuecomment-13535459
                fieldDef.searchBarThreshold = -1;
                break;
            case 'bool':
                fieldDef.type = 'enum';
                fieldDef.options = fieldDef.options || 'filter_checkbox_dom';
                break;
            case 'int':
                fieldDef.auto_increment = false;
                //For $in operator, we need to convert `['1','20','35']` to `1,20,35` to make it work in a varchar field
                if (operation === '$in') {
                    fieldDef.type = 'varchar';
                    fieldDef.len = 200;
                    if (_.isArray($row.data('value'))) {
                        $row.data('value', $row.data('value').join(','));
                    }
                }
                break;
            case 'teamset':
                fieldDef.type = 'relate';
                fieldDef.isMultiSelect = true;
                break;
            case 'datetimecombo':
            case 'date':
                fieldDef.type = 'date';
                //Flag to indicate the value needs to be formatted correctly
                data.isDate = true;
                if (operation.charAt(0) !== '$') {
                    //Flag to indicate we need to build the date filter definition based on the date operator
                    data.isDateRange = true;
                    this.fireSearch();
                    return;
                }
                break;
            case 'relate':
                fieldDef.auto_populate = true;
                fieldDef.isMultiSelect = true;
                break;
            case 'parent':
                data.isFlexRelate = true;
                break;
        }
        fieldDef.required = false;
        fieldDef.readonly = false;

        // Create new model with the value set
        var model = app.data.createBean(moduleName);

        var $fieldValue = $row.find('[data-filter=value]');
        $fieldValue.removeClass('hide').empty();

        //fire the change event as soon as the user start typing
        var _keyUpCallback = function(e) {
            if ($(e.currentTarget).is(".select2-input")) {
                return; //Skip select2. Select2 triggers other events.
            }
            this.value = $(e.currentTarget).val();
            // We use "silent" update because we don't need re-render the field.
            model.set(this.name, this.unformat($(e.currentTarget).val()), {silent: true});
            model.trigger('change');
        };

        //If the operation is $between we need to set two inputs.
        if (operation === '$between' || operation === '$dateBetween') {
            var minmax = [];
            var value = $row.data('value') || [];
            if (fieldType === 'currency' && $row.data('value')) {
                value = $row.data('value') || {};
                model.set(value);
                value = value[fieldName] || [];
                // FIXME: Change currency.js to retrieve correct unit for currency filters (see TY-156).
                model.set('id', 'not_new');
            }

            model.set(fieldName + '_min', value[0] || '');
            model.set(fieldName + '_max', value[1] || '');
            minmax.push(this.createField(model, _.extend({}, fieldDef, {name: fieldName + '_min'})));
            minmax.push(this.createField(model, _.extend({}, fieldDef, {name: fieldName + '_max'})));

            if(operation === '$dateBetween') {
                minmax[0].label = app.lang.get('LBL_FILTER_DATEBETWEEN_FROM');
                minmax[1].label = app.lang.get('LBL_FILTER_DATEBETWEEN_TO');
            } else {
                minmax[0].label = app.lang.get('LBL_FILTER_BETWEEN_FROM');
                minmax[1].label = app.lang.get('LBL_FILTER_BETWEEN_TO');
            }

            data['valueField'] = minmax;

            _.each(minmax, function(field) {
                var fieldContainer = $(field.getPlaceholder().string);
                $fieldValue.append(fieldContainer);
                this.listenTo(field, 'render', function() {
                    field.$('input, select, textarea').addClass('inherit-width');
                    field.$('.input-append').prepend('<span class="add-on">' + field.label + '</span>');
                    field.$('.input-append').addClass('input-prepend');
                    // .date makes .inherit-width on input have no effect so we need to remove it.
                    field.$('.input-append').removeClass('date');
                    field.$('input, textarea').on('keyup', _.debounce(_.bind(_keyUpCallback, field), 400));
                });
                this._renderField(field, fieldContainer);
            }, this);
        } else if (data.isFlexRelate) {
            _.each($row.data('value'), function(value, key) {
                model.set(key, value);
            }, this);

            var field = this.createField(model, _.extend({}, fieldDef, {name: fieldName})),
                fieldContainer = $(field.getPlaceholder().string),
                findRelatedName = app.data.createBeanCollection(model.get('parent_type'));
            data['valueField'] = field;
            $fieldValue.append(fieldContainer);

            if (model.get('parent_id')) {
                findRelatedName.fetch({
                    params: {filter: [{'id': model.get('parent_id')}]},
                    complete: _.bind(function() {
                        if (!this.disposed) {
                            if (findRelatedName.first()) {
                                model.set(fieldName,
                                    findRelatedName.first().get(field.getRelatedModuleField()),
                                    {silent: true});
                            }
                            if (!field.disposed) {
                                this._renderField(field, fieldContainer);
                            }
                        }
                    }, this)
                });
            } else {
                this._renderField(field, fieldContainer);
            }
        } else {
            // value is either an empty object OR an object containing `currency_id` and currency amount
            if (fieldType === 'currency' && $row.data('value')) {
                // for stickiness & to retrieve correct saved values, we need to set the model with data.value object
                model.set($row.data('value'));
                // FIXME: Change currency.js to retrieve correct unit for currency filters (see TY-156).
                // Mark this one as not_new so that model isn't treated as new
                model.set('id', 'not_new');
            } else {
                model.set(fieldDef.id_name || fieldName, $row.data('value'));
            }
            // Render the value field
            var field = this.createField(model, _.extend({}, fieldDef, {name: fieldName})),
                fieldContainer = $(field.getPlaceholder().string);
            $fieldValue.append(fieldContainer);
            data['valueField'] = field;

            this.listenTo(field, 'render', function() {
                field.$('input, select, textarea').addClass('inherit-width');
                // .date makes .inherit-width on input have no effect so we need to remove it.
                field.$('.input-append').removeClass('date');
                field.$('input, textarea').on('keyup',_.debounce(_.bind(_keyUpCallback, field), 400));
            });
            if ((fieldDef.type === 'relate' || fieldDef.type === 'nestedset') &&
                !_.isEmpty($row.data('value'))
            ) {
                var self = this,
                    findRelatedName = app.data.createBeanCollection(fieldDef.module);
                findRelatedName.fetch({fields: [fieldDef.rname], params: {filter: [{'id': {'$in': $row.data('value')}}]},
                    complete: function() {
                        if (!self.disposed) {
                            if (findRelatedName.length > 0) {
                                model.set(fieldDef.id_name, findRelatedName.pluck('id'), { silent: true });
                                model.set(fieldName, findRelatedName.pluck(fieldDef.rname), { silent: true });
                            }
                            if (!field.disposed) {
                                self._renderField(field, fieldContainer);
                            }
                        }
                    }
                });
            } else {
                this._renderField(field, fieldContainer);
            }
        }
        // When the value change a quicksearch should be fired to update the results
        this.listenTo(model, "change", function() {
            this._updateFilterData($row);
            this.fireSearch();
        });

        // Manually trigger the filter request if a value has been selected lately
        // This is the case for checkbox fields or enum fields that don't have empty values.
        var modelValue = model.get(fieldDef.id_name || fieldName);

        // To handle case: value is an object with 'currency_id' = 'xyz' and 'likely_case' = ''
        // For currency fields, when value becomes an object, trigger change
        if (!_.isEmpty(modelValue) && modelValue !== $row.data('value')) {
            model.trigger('change');
        }
    },

    /**
     * Update filter data for this row
     * @param $row Row to update
     * @private
     */
    _updateFilterData: function($row){
        var data = $row.data(),
            field = data['valueField'],
            name = data['name'],
            valueForFilter;

        //Make sure we use ID for relate fields
        if (this.fieldList[name] && this.fieldList[name].id_name) {
            name = this.fieldList[name].id_name;
        }

        //If we have multiple fields we have to build an array of values
        if (_.isArray(field)) {
            valueForFilter = [];
            _.each(field, function(field) {
                var value = !field.disposed && field.model.has(field.name) ? field.model.get(field.name) : '';
                value = $row.data('isDate') ? (app.date.stripIsoTimeDelimterAndTZ(value) || '') : value;
                valueForFilter.push(value);
            });
        } else {
            var value = !field.disposed && field.model.has(name) ? field.model.get(name) : '';
            valueForFilter = $row.data('isDate') ? (app.date.stripIsoTimeDelimterAndTZ(value) || '') : value;
        }
        $row.data("value", valueForFilter); // Update filter value once we've calculated final value
    },

    /**
     * Check each row, builds the filter definition and trigger the filtering
     */
    fireSearch: _.debounce(function() {
        var filterDef = this.buildFilterDef(true),
            filterTemplate = this.buildFilterDef(),
            defHasChanged = !_.isEqual(this.lastFilterDef, filterDef),
            templateHasChanged = !_.isEqual(this.lastFilterTemplate, filterTemplate);

        // Save the current edit state
        if (defHasChanged || templateHasChanged) {
            this.saveFilterEditState(filterDef, filterTemplate);
            this.lastFilterDef = filterDef;
            this.lastFilterTemplate = filterTemplate;
            this.layout.trigger('filter:toggle:savestate', true);
        }
        if (!defHasChanged) {
            return;
        }
        // Needed in order to prevent filtering a global context collection (see filter.js:applyFilter()).
        if (this.context.get('applyFilter') !== false) {
            this.layout.trigger('filter:apply', null, filterDef);
        }
    }, 400),

    /**
     * Saves the current edit state into the cache
     *
     * @param {Object} [filterDef] Filter Definition. Defaults to the
     *   {@link #builtFilderDef} with only valid rows.
     * @param {Object} [templateDef] Filter template definition. Defaults to
     *   the {@link #builtFilderDef} with all rows.
     */
    saveFilterEditState: function(filterDef, templateDef) {
        if (!this.context.editingFilter) {
            return;
        }
        this.context.editingFilter.set({
            'filter_definition': filterDef || this.buildFilterDef(true),
            'filter_template': templateDef || this.buildFilterDef()
        });
        var filter = this.context.editingFilter.toJSON();

        // Make sure the filter-actions view is rendered, otherwise it will override the name with an empty name.
        if (this.layout.getComponent('filter-actions') &&
            this.layout.getComponent('filter-actions').$('input').length === 1
        ) {
            filter.name = this.layout.getComponent('filter-actions').getFilterName();
        }
        this.layout.getComponent('filter').saveFilterEditState(filter);
    },

    /**
     * Build filter definition for all rows.
     *
     * @param {Boolean} onlyValidRows Set `true` to retrieve only filter
     *   definition of valid rows, `false` to retrieve the entire field
     *   template.
     * @return {Array} Filter definition.
     */
    buildFilterDef: function(onlyValidRows) {
        var $rows = this.$('[data-filter=row]'),
            filter = [];

        _.each($rows, function(row) {
            var rowFilter = this.buildRowFilterDef($(row), onlyValidRows);

            if (rowFilter) {
                filter.push(rowFilter);
            }
        }, this);

        return filter;
    },

    /**
     * Build filter definition for this row.
     *
     * @param {jQuery} $row The related row.
     * @param {Boolean} onlyIfValid Set `true` to validate the row and return
     *   `undefined` if not valid, or `false` to build the definition anyway.
     * @return {Object} Filter definition for this row.
     */
    buildRowFilterDef: function($row, onlyIfValid) {
        var data = $row.data();
        if (onlyIfValid && !this.validateRow($row)) {
            return;
        }
        var operator = data['operator'],
            value = data['value'] || '',
            name = data['id_name'] || data['name'],
            filter = {};

        if (_.isEmpty(name)) {
            return;
        }

        if (data.isPredefinedFilter || !this.fieldList) {
            filter[name] = '';
            return filter;
        } else {
            if (this.fieldList[name] && _.has(this.fieldList[name], 'dbFields')) {
                var subfilters = [];
                _.each(this.fieldList[name].dbFields, function(dbField) {
                    var filter = {};
                    filter[dbField] = {};
                    filter[dbField][operator] = value;
                    subfilters.push(filter);
                });
                filter['$or'] = subfilters;
            } else {
                if (data.isFlexRelate) {
                    var valueField = data['valueField'],
                        idFilter = {},
                        typeFilter = {};

                    idFilter[data.id_name] = valueField.model.get(data.id_name);
                    typeFilter[data.type_name] = valueField.model.get(data.type_name);
                    filter['$and'] = [idFilter, typeFilter];
                // Creating currency filter. For all but `$between` operators we use type property from data.valueField.
                // For `$between`, data.valueField is an array and therefore we check for type==='currency' from
                // either of the elements.
                } else if (data['valueField'] && (data['valueField'].type === 'currency' ||
                    (_.isArray(data.valueField) && data.valueField[0].type === 'currency'))
                    ) {
                    // initially value is an array which we later convert into an object for saving and retrieving
                    // purposes (stickiness structure constraints)
                    var amountValue;
                    if (_.isObject(value) && !_.isUndefined(value[name])) {
                        amountValue = value[name];
                    } else {
                        amountValue = value;
                    }

                    var amountFilter = {};
                    amountFilter[name] = {};
                    amountFilter[name][operator] = amountValue;

                    // for `$between`, we use first element to get dataField ('currency_id') since it is same
                    // for both elements and also because data.valueField is an array
                    var dataField;
                    if (_.isArray(data.valueField)) {
                        dataField = data.valueField[0];
                    } else {
                        dataField = data.valueField;
                    }

                    var currencyId;
                    currencyId = dataField.getCurrencyField().name;

                    var currencyFilter = {};
                    currencyFilter[currencyId] = dataField.model.get(currencyId);

                    filter['$and'] = [amountFilter, currencyFilter];
                } else if (operator === '$equals') {
                    filter[name] = value;
                } else if (data.isDateRange) {
                    //Once here the value is actually a key of date_range_selector_dom and we need to build a real
                    //filter definition on it.
                    filter[name] = {};
                    filter[name].$dateRange = operator;
                } else if (operator === '$in' || operator === '$not_in') {
                    // IN/NOT IN require an array
                    filter[name] = {};
                    //If value is not an array, we split the string by commas to make it an array of values
                    if (_.isArray(value)) {
                        filter[name][operator] = value;
                    } else if (!_.isEmpty(value)) {
                        filter[name][operator] = (value + '').split(',');
                    } else {
                        filter[name][operator] = [];
                    }
                } else {
                    filter[name] = {};
                    filter[name][operator] = value;
                }
            }

            return filter;
        }
    },

    /**
     * Reset filter values on filter form. Called after a click on `Reset` button
     */
    resetFilterValues: function() {
        var $rows = this.$('[data-filter=row]');
        _.each($rows, function(row) {
            var $row = $(row);
            var valueField = $row.data('valueField');

            if (!valueField || valueField.disposed) {
                return;
            }
            if (!_.isArray(valueField)) {
                valueField.model.clear();
                return;
            }
            _.each(valueField, function(field) {
                field.model.clear();
            });
        });
    },

    /**
     * Disposes fields stored in the data attributes of the row element.
     *
     *     @example of an `opts` object param:
     *      [
     *       {field: 'nameField', value: 'name'},
     *       {field: 'operatorField', value: 'operator'},
     *       {field: 'valueField', value: 'value'}
     *      ]
     *
     * @param  {jQuery} $row The row which fields are to be disposed.
     * @param  {Array} opts An array of objects containing the field object and
     *  value to the data attributes of the row.
     */
    _disposeRowFields: function($row, opts) {
        var data = $row.data(), model;

        if (_.isObject(data) && _.isArray(opts)) {
            _.each(opts, function(val) {
                if (data[val.field]) {
                    //For in between filter we have an array of fields so we need to cover all cases
                    var fields = _.isArray(data[val.field]) ? data[val.field] : [data[val.field]];
                    data[val.value] = '';
                    _.each(fields, function(field) {
                        model = field.model;
                        if (val.field === "valueField" && model) {
                            model.clear({silent: true});
                            this.stopListening(model);
                        }
                        field.dispose();
                        field = null;
                    }, this);
                    return;
                }
                if (data.isDateRange && val.value === 'value') {
                    data.value = '';
                }
            }, this);
        }
        //Reset flags
        data.isDate = false;
        data.isDateRange = false;
        data.isPredefinedFilter = false;
        data.isFlexRelate = false;
        $row.data(data);
        this.fireSearch();
    }
})
