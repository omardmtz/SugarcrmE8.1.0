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
 * @class View.Views.Base.FilteredListView
 * @alias SUGAR.App.view.views.BaseFilteredListView
 * @extends View.Views.Base.ListView
 */
({
    extendsFrom: 'ListView',

    /**
     * Filtered and sorted collection set.
     * @property
     */
    filteredCollection: [],

    /**
     * Typed search keyword.
     * @property
     */
    searchTerm: '',

    /**
     * Convert the available filter to the regular expression.
     * @property
     */
    _patternToReg: {
        startsWith: '^(term)',
        endsWith: '(term)$',
        contains: '(term)'
    },

    /**
     * Filter the metadata in order to initiate the searchable fields.
     * @protected
     */
    _initFilter: function() {
        var filter = this._filter || _.chain(this.getFields())
            .filter(function(field) {
                return field.filter;
            })
            .map(function(field) {
                return {
                    name: field.name,
                    label: app.lang.get(field.label, this.module),
                    filter: field.filter
                };
            }, this)
            .value();
        this.context.trigger('filteredlist:filter:set', _.pluck(filter, 'label'));

        if (_.isEmpty(filter)) {
            return;
        }
        this._filter = filter;
    },

    /**
     * Filtering collection that matches with search term.
     * In order to activate filtering on the field,
     * the filter term should be defined in the metadata.
     * There are three type of filter type (startsWith, contains, endsWith).
     *
     * Examples:
     *
     * <pre><code>
     * array(
     *     'type' => 'base',
     *     'name' => 'field_name',
     *     'filter' => 'startsWith',
     *     ),
     * array(
     *     'type' => 'base',
     *     'name' => 'before_field_value',
     *     'filter' => 'contains',
     *     ),
     * array(
     *     'type' => 'base',
     *     'name' => 'datetime',
     *     'filter' => 'endsWith',
     *     ),
     * </code></pre>
     */
    filterCollection: function() {
        var term = this.searchTerm,
            filter = this._filter;

        if (!_.isEmpty(term) && _.isString(term)) {
            this.filteredCollection = this.collection.filter(function(model) {
                return _.some(filter, function(params) {
                    var pattern = this._patternToReg[params.filter].replace('term', term),
                        tester = new RegExp(pattern, 'i');
                    return tester.test(model.get(params.name));
                }, this);
            }, this);
        }
    },

    /**
     * Set the current search term and then reload the table.
     * @param {String} term Search term.
     */
    setSearchTerm: function(term) {
        this.searchTerm = term;
        this._renderData();
    },

    /**
     * @inheritdoc
     *
     * Sort the collection based on user input.
     * In order to activate sorting on the field,
     * 'sortable' should be defined in the metadata.
     *
     * <pre><code>
     * array(
     *     'type' => 'base',
     *     'name' => 'field_name',
     *     'sortable' => true,
     *     ),
     * </code></pre>
     */
    setOrderBy: function(event) {
        this._super('setOrderBy', [event]);
        this.collection.comparator = function(model) {
            return model.get(this.orderBy.field);
        };
        if (this.orderBy.direction === 'desc') {
            this.collection.sort({silent: true});
            this.collection.models.reverse();
            this.collection.trigger('sort', this.collection);
        } else {
            this.collection.sort();
        }
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.on('render', this._initFilter, this);
        if (this.collection) {
            this.collection.on('reset sort', this._renderData, this);
        }
        this.context.on('filteredlist:search:fired', this.setSearchTerm, this);
    },

    /**
     * Refresh the filtered collection and then refresh the html.
     * @protected
     */
    _renderData: function() {
        this.filteredCollection = this.collection.models;
        this.filterCollection();
        this.render();
    }
})
