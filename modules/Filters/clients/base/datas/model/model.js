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
 * @class Data.Base.FiltersBean
 * @extends Data.Bean
 */
({
    /**
     * @inheritdoc
     */
    defaults: {
        editable: true
    },

    /**
     * Maps field types and field operator types.
     *
     * @property {Object}
     */
    fieldTypeMap: {
        'datetime': 'date',
        'datetimecombo': 'date'
    },

    /**
     * Gets the filter definition based on quick search metadata.
     *
     * The filter definition that is built is based on the `basic` filter
     * metadata. By default, modules will make a search on the `name` field, but
     * this is configurable. For instance, the `person` type modules
     * (e.g. Contacts or Leads) will perform a search on the first name and the
     * last name (`first_name` and `last_name` fields).
     *
     * For these modules whom the search is performed on two fields, you can
     * also configure to split the terms. In this case, the terms are split such
     * that different combinations of the terms are search against each search
     * field.
     *
     * There is a special case if the `moduleName` is `all_modules`: the
     * function will always return an empty filter definition (empty `array`).
     *
     * There is another special case with the `Users` and `Employees` module:
     * the filter will be augmented to retrieve only the records with the
     * `status` set to `Active`.
     *
     * @param {string} moduleName The filtered module.
     * @param {string} searchTerm The search term.
     * @return {Array} This search term filter.
     * @static
     */
    buildSearchTermFilter: function(moduleName, searchTerm) {
        if (moduleName === 'all_modules' || !searchTerm) {
            return [];
        }

        searchTerm = searchTerm.trim();

        var splitTermFilter;
        var filterList = [];
        var searchMeta = app.data.getBeanClass('Filters').prototype.getModuleQuickSearchMeta(moduleName);
        var fieldNames = searchMeta.fieldNames;

        // Iterate through each field and check if the field is a simple
        // or complex field, and build the filter object accordingly
        _.each(fieldNames, function(name) {
            if (!_.isArray(name)) {
                var filter = this._buildFilterDef(name, '$starts', searchTerm);
                if (filter) {
                    // Simple filters are pushed to `filterList`
                    filterList.push(filter);
                }
                return;
            }

            if (splitTermFilter) {
                app.logger.error('Cannot have more than 1 split term filter');
                return;
            }
            splitTermFilter = this._buildSplitTermFilterDef(name, '$starts', searchTerm);
        }, this);

        // Push the split term filter
        if (splitTermFilter) {
            filterList.push(splitTermFilter);
        }

        // If more than 1 filter was created, wrap them in `$or`
        if (filterList.length > 1) {
            var filter = this._joinFilterDefs('$or', filterList);
            if (filter) {
                filterList = [filter];
            }
        }

        // FIXME [SC-3560]: This should be moved to the metadata
        if (moduleName === 'Users' || moduleName === 'Employees') {
            filterList = this._simplifyFilterDef(filterList);
            filterList = [{
                '$and': [
                    {'status': {'$not_equals': 'Inactive'}},
                    filterList
                ]
            }];
        }

        return filterList;
    },

    /**
     * Combines two filters into a single filter definition.
     *
     * @param {Array|Object} [baseFilter] The selected filter definition.
     * @param {Array} [searchTermFilter] The filter for the quick search terms.
     * @return {Array} The filter definition.
     * @static
     */
    combineFilterDefinitions: function(baseFilter, searchTermFilter) {
        var isBaseFilter = _.size(baseFilter) > 0,
            isSearchTermFilter = _.size(searchTermFilter) > 0;

        baseFilter = _.isArray(baseFilter) ? baseFilter : [baseFilter];

        if (isBaseFilter && isSearchTermFilter) {
            baseFilter.push(searchTermFilter[0]);
            return [
                {'$and': baseFilter }
            ];
        } else if (isBaseFilter) {
            return baseFilter;
        } else if (isSearchTermFilter) {
            return searchTermFilter;
        }

        return [];
    },

    /**
     * Gets filterable fields from the module metadata.
     *
     * The list of fields comes from the metadata but is also filtered by
     * user acls (`detail`/`read` action).
     *
     * @param {string} moduleName The name of the module.
     * @return {Object} The filterable fields.
     * @static
     */
    getFilterableFields: function(moduleName) {
        var moduleMeta = app.metadata.getModule(moduleName),
            operatorMap = app.metadata.getFilterOperators(moduleName),
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

        var validFields = {};
        _.each(fields, function(value, key) {
            // Check if we support this field type.
            var type = this.fieldTypeMap[value.type] || value.type;
            var hasAccess = app.acl.hasAccess('detail', moduleName, null, key);
            // Predefined filters don't have operators defined.
            if (hasAccess && (operatorMap[type] || value.predefined_filter === true)) {
                validFields[key] = value;
            }
        }, this);

        return validFields;
    },

    /**
     * Retrieves and caches the quick search metadata.
     *
     * @param {string} [moduleName] The filtered module. Only required when the
     *   function is called statically.
     * @return {Object} Quick search metadata (with highest priority).
     * @return {string[]} return.fieldNames The fields to be used in quick search.
     * @return {boolean} return.splitTerms Whether to split the search terms
     *   when there are multiple search fields.
     * @static
     */
    getModuleQuickSearchMeta: function(moduleName) {
        moduleName = moduleName || this.get('module_name');

        var prototype = app.data.getBeanClass('Filters').prototype;
        prototype._moduleQuickSearchMeta = prototype._moduleQuickSearchMeta || {};

        prototype._moduleQuickSearchMeta[moduleName] = prototype._moduleQuickSearchMeta[moduleName] ||
            this._getQuickSearchMetaByPriority(moduleName);
        return prototype._moduleQuickSearchMeta[moduleName];
    },

    /**
     * Populates empty values of a filter definition.
     *
     * @param {Object} filterDef The filter definition.
     * @param {Object} populateObj Populate object containing the
     *   `filter_populate` metadata definition.
     * @return {Object} The filter definition.
     * @static
     */
    populateFilterDefinition: function(filterDef, populateObj) {
        if (!populateObj) {
            return filterDef;
        }
        filterDef = app.utils.deepCopy(filterDef);
        _.each(filterDef, function(row) {
            _.each(row, function(filter, field) {
                var hasNoOperator = (_.isString(filter) || _.isNumber(filter));
                if (hasNoOperator) {
                    filter = {'$equals': filter};
                }
                var operator = _.keys(filter)[0],
                    value = filter[operator],
                    isValueEmpty = !_.isNumber(value) && _.isEmpty(value);

                if (isValueEmpty && populateObj && !_.isUndefined(populateObj[field])) {
                    value = populateObj[field];
                }

                if (hasNoOperator) {
                    row[field] = value;
                } else {
                    row[field][operator] = value;
                }
            });
        });
        return filterDef;
    },

    /**
     * Retrieves the quick search metadata.
     *
     * The metadata returned is the one that has the highest
     * `quicksearch_priority` property.
     *
     * @param {string} searchModule The filtered module.
     * @return {Object}
     * @return {string[]} return.fieldNames The list of field names.
     * @return {boolean} return.splitTerms Whether to split search terms or not.
     * @private
     * @static
     */
    _getQuickSearchMetaByPriority: function(searchModule) {
        var meta = app.metadata.getModule(searchModule),
            filters = meta ? meta.filters : [],
            fieldNames = [],
            priority = 0,
            splitTerms = false;

        _.each(filters, function(value) {
            if (value && value.meta && value.meta.quicksearch_field &&
                priority < value.meta.quicksearch_priority) {
                fieldNames = value.meta.quicksearch_field;
                priority = value.meta.quicksearch_priority;
                if (_.isBoolean(value.meta.quicksearch_split_terms)) {
                    splitTerms = value.meta.quicksearch_split_terms;
                }
            }
        });

        return {
            fieldNames: fieldNames,
            splitTerms: splitTerms
        };
    },

    /**
     * Returns the first filter from `filterList`, if the length of
     * `filterList` is 1.
     *
     * The *simplified* filter is in the form of the one returned by
     * {@link #_buildFilterDef} or {@link #_joinFilterDefs}.
     *
     * @param {Array} filterList An array of filter definitions.
     *
     * @return {Array|Object} First element of `filterList`, if the
     *   length of the array is 1, otherwise, the original `filterList`.
     * @private
     */
    _simplifyFilterDef: function(filterList) {
        return filterList.length > 1 ? filterList : filterList[0];
    },

    /**
     * Builds a filter definition object.
     *
     * A filter definition object is in the form of:
     *
     *     { fieldName: { operator: searchTerm } }
     *
     * @param {string} fieldName Name of the field to search by.
     * @param {string} operator Operator to search by. As found in `FilterApi#addFilters`.
     * @param {string} searchTerm Search input entered.
     *
     * @return {Object} The search filter definition for quick search.
     * @private
     */
    _buildFilterDef: function(fieldName, operator, searchTerm) {
        var def = {};
        var filter = {};
        filter[operator] = searchTerm;
        def[fieldName] = filter;
        return def;
    },

    /**
     * Joins a list of filter definitions under a logical operator.
     *
     * Supports logical operators such as `$or` and `$and`. Ultimately producing
     * a filter definition structured as:
     *
     *     { operator: filterDefs }
     *
     * @param {string} operator Logical operator to join the filter definitions by.
     * @param {Array|Object} filterDefs Array of filter definitions or individual
     *   filter definition objects.
     *
     * @return {Object|Array} Filter definitions joined under a logical operator,
     *   or a simple filter definition if `filterDefs` is of length 1,
     *   otherwise an empty `Array`.
     * @private
     */
    _joinFilterDefs: function(operator) {
        var filterDefs = Array.prototype.slice.call(arguments, 1);

        if (_.isEmpty(filterDefs)) {
            return [];
        }

        if (_.isArray(filterDefs[0])) {
            filterDefs = filterDefs[0];
        }

        // if the length of the `filterList` is less than 2, then just return the simple filter
        if (filterDefs.length < 2) {
            return filterDefs[0];
        }

        var filter = {};
        filter[operator] = filterDefs;
        return filter;
    },

    /**
     * Builds a filter object by using unique combination of the
     * searchTerm delimited by spaces.
     *
     * @param {Array} fieldNames Field within `quicksearch_field`
     *   in the metadata to perform split term filtering.
     * @param {string} operator Operator to search by. As found in `FilterApi#addFilters`.
     * @param {string} searchTerm Search input entered.
     *
     * @return {Object|undefined} The search filter definition for
     *   quick search or `undefined` if no filter to apply or supported.
     * @private
     */
    _buildSplitTermFilterDef: function(fieldNames, operator, searchTerm) {
        if (fieldNames.length > 2) {
            app.logger.error('Cannot have more than 2 fields in a complex filter');
            return;
        }

        // If the field is a split-term field, but only composed of single item
        // return the simple filter
        if (fieldNames.length === 1) {
            return this._buildFilterDef(fieldNames[0], operator, searchTerm);
        }

        var filterList = [];
        var tokens = searchTerm.split(' ');

        // When the searchTerm is composed of at least 2 terms delimited by a space character,
        // Divide the searchTerm in 2 unique sets
        // e.g. For the name "Jean Paul Durand",
        // first = "Jean", rest = "Paul Durand" (1st iteration)
        // first = "Jean Paul", rest = "Durand" (2nd iteration)
        for (var i = 1; i < tokens.length; ++i) {
            var first = _.first(tokens, i).join(' ');
            var rest = _.rest(tokens, i).join(' ');

            // FIXME the order of the filters need to be reviewed (TY-547)
            var tokenFilter = [
                this._buildFilterDef(fieldNames[0], operator, first),
                this._buildFilterDef(fieldNames[1], operator, rest)
            ];
            filterList.push(this._joinFilterDefs('$and', tokenFilter));
        }

        // Try with full search term in each field
        // e.g. `first_name: Sangyoun Kim` or `last_name: Sangyoun Kim`
        _.each(fieldNames, function(name) {
            filterList.push(this._buildFilterDef(name, operator, searchTerm));
        }, this);

        return this._joinFilterDefs('$or', filterList);
    }
})
