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
 * Layout for the global search results page.
 *
 * @class View.Layouts.Base.SearchLayout
 * @alias SUGAR.App.view.layouts.BaseSearchLayout
 * @extends View.Layout
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.context.set('search', true);
        this.collection.query = this.context.get('searchTerm') || '';

        /**
         * Flag to indicate if the search has been filtered using facets or not.
         *
         * @property {boolean} `true` if the search has been filtered.
         */
        this.filteredSearch = false;
        /**
         * Object containing the selected facets in the current search.
         *
         * @property {Object} selectedFacets
         */
        this.selectedFacets = {};

        this.context.on('search:fire:new', function() {
            this.search();
        }, this);

        this.context.on('facet:apply', this.filter, this);

        this.collection.on('sync', function(collection) {
            var isCollection = (collection instanceof App.BeanCollection);
            if (!isCollection) {
                return;
            }
            app.utils.GlobalSearch.formatRecords(collection, true);

            if (!_.isEmpty(collection.xmod_aggs)) {
                if (!this.filteredSearch) {
                    this._initializeSelectedFacets(collection.xmod_aggs);
                }

                this.context.set('selectedFacets', this.selectedFacets);
                this.context.set('facets', collection.xmod_aggs, {silent: true});
                this.context.trigger('facets:change', collection.xmod_aggs);
            }

        }, this);

        this.context.on('facets:reset', this.search, this);

        this.collection.setOption('params', {xmod_aggs: true});
    },

    /**
     * Builds the selected facets object to be sent to the server.
     *
     * @param {Object} facets The facets object that comes from the server.
     * @private
     */
    _initializeSelectedFacets: function(facets) {
        _.each(facets, function(facet, key) {
            if (key === 'modules') {
                this.selectedFacets[key] = [];
            } else {
                this.selectedFacets[key] = false;
            }
        }, this);
    },

    /**
     * Updates {@link #selectedFacets} with the facet change.
     *
     * @param {String} facetId The facet type.
     * @param {String} facetCriteriaId The id of the facet criteria.
     * @param {boolean} isSingleItem `true` if it's a single item facet.
     * @private
     */
    _updateSelectedFacets: function(facetId, facetCriteriaId, isSingleItem) {
        if (isSingleItem) {
            this.selectedFacets[facetId] = !this.selectedFacets[facetId];
            return;
        }
        var index;
        if (this.selectedFacets[facetId]) {
            index = this.selectedFacets[facetId].indexOf(facetCriteriaId);
        } else {
            this.selectedFacets[facetId] = [];
        }
        if (_.isUndefined(index) || index === -1) {
            this.selectedFacets[facetId].splice(0, 0, facetCriteriaId);
        } else {
            this.selectedFacets[facetId].splice(index, 1);
            if (this.selectedFacets[facetId].length === 0) {
                delete this.selectedFacets[facetId];
            }
        }
    },

    /**
     * Searches on a term and a module list.
     *
     * @param {boolean} reset `true` if we reset the filters.
     */
    search: function(reset) {
        // Prevents to trigger a new fetch if the user clicks on
        if (reset && !this.filteredSearch) {
            return;
        }
        var searchTerm = this.context.get('searchTerm');
        var moduleList = this.context.get('module_list') || [];
        this.filteredSearch = false;

        var tagFilters = _.pluck(this.context.get('tags'), 'id');

        this.collection.fetch({query: searchTerm, module_list: moduleList,
            apiOptions:
            {
                data: {
                    tag_filters: tagFilters
                },
                fetchWithPost: true,
                useNewApi: true
            }
        });
    },

    /**
     * Refines the search applying a facet change.
     *
     * @param facetId The facet id.
     * @param facetCriteriaId The facet criteria id.
     * @param isSingleItem `true` if it's a single criteria facet.
     */
    filter: function(facetId, facetCriteriaId, isSingleItem) {
        this._updateSelectedFacets(facetId, facetCriteriaId, isSingleItem);

        var searchTerm = this.context.get('searchTerm');
        var moduleList = this.context.get('module_list') || [];
        this.filteredSearch = true;
        var tagFilters = _.pluck(this.context.get('tags'), 'id');
        this.collection.fetch({query: searchTerm, module_list: moduleList,
            apiOptions:
            {
                data: {
                    agg_filters: this.selectedFacets,
                    tag_filters: tagFilters
                },
                fetchWithPost: true,
                useNewApi: true
            }
        });
    },

    /**
     * We override `loadData` to not send the `fields` param in the
     * request, so it's consistent with the request sent by
     * {@link View.Views.Base.QuicksearchBarView#fireSearchRequest fireSearchRequest}
     * method in the quicksearch bar.
     * Note that the `fields` param is not used anymore by the globalsearch API.
     *
     * @inheritdoc
     */
    loadData: function(options) {
        options = options || {};

        options.module_list = this.context.get('module_list') || [];

        // pull tag ids out of context and pass them into our options to filter
        var tagFilters = _.pluck(this.context.get('tags'), 'id');
        if (tagFilters) {
            options.apiOptions = options.apiOptions || {};
            options.apiOptions.data = {tag_filters: tagFilters};
            options.apiOptions.fetchWithPost = true;
            options.apiOptions.useNewApi = true;
        }

        this._super('loadData', [options]);
    }
})
