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
 * List view for the {@link View.Layouts.Base.SearchLayout
 * Search layout}.
 *
 * @class View.Views.Base.SearchListView
 * @alias SUGAR.App.view.views.BaseSearchListView
 * @extends View.View
 */
({
    plugins: ['Pagination'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        /**
         * The fields metadata for this view per module.
         *
         * @property
         * @private
         */
        this._fieldsMeta = {};
        this.addPreviewEvents();
    },

    /**
     * Parses models when collection resets and renders the view.
     *
     * @override
     */
    bindDataChange: function() {
        this.collection.on('sync', function(collection) {
            if (this.disposed) {
                return;
            }
            var isCollection = (collection instanceof App.BeanCollection);
            if (!isCollection) {
                return;
            }
            this.parseModels(this.collection.models);
            if (this._previewed) {
                app.events.trigger('preview:close');
            }
            this.render();
        }, this);
    },

    /**
     * Parses models to generate primary fields and secondary fields based on
     * the metadata and data sent by the globalsearch API. This is used to
     * render them properly in the template.
     *
     * @param {Data.Bean[]} models The models to parse.
     */
    parseModels: function(models) {
        var gsUtils = app.utils.GlobalSearch;
        _.each(models, function(model) {
            var moduleMeta = this._fieldsMeta[model.module] || gsUtils.getFieldsMeta(model.module);
            this._fieldsMeta[model.module] = moduleMeta;

            model.primaryFields = gsUtils.highlightFields(model, moduleMeta.primaryFields);
            model.secondaryFields = gsUtils.highlightFields(model, moduleMeta.secondaryFields, true);
            model.viewAccess = app.acl.hasAccessToModel('view', model);

            this._rejectEmptyFields(model, model.secondaryFields);

            model.primaryFields = this._sortHighlights(model.primaryFields);
            model.secondaryFields = this._sortHighlights(model.secondaryFields);

            model.rowactions = moduleMeta.rowactions;
        }, this);
    },

    /**
     * Converts a hash of field names and their definitions into an array of
     * field definitions sorted such as:
     *
     *  - avatar field(s) is(are) first (in theory there should be only one),
     *  - highlighted fields are second,
     *  - non highlighted fields are third.
     *
     * @param {Object} fieldsObject The object to transform.
     * @return {Array} fieldsArray The sorted array of objects.
     * @private
     */
    _sortHighlights: function(fieldsObject) {
        var fieldsArray = _.values(fieldsObject);
        fieldsArray = _.sortBy(fieldsArray, function(field) {
            if (field.type === 'avatar') {
                return 0;
            }
            return field.highlighted ? 1 : 2;
        });
        return fieldsArray;
    },

    /**
     * Removes fields that have an empty value.
     *
     * @param {Data.Bean} model The model.
     * @param {Object} viewDefs The viewDefs of the fields.
     * @private
     */
    _rejectEmptyFields: function(model, viewDefs) {
        _.each(viewDefs, function(field) {
            var fieldValue = model.get(field.name);
            // _.isEmpty() returns true for any number, so checking for _.isNumber() as well
            if (_.isEmpty(fieldValue) && !_.isNumber(fieldValue)) {
                delete viewDefs[field.name];
            }
        });
    },

    /**
     * Adds event listeners related to preview.
     */
    addPreviewEvents: function() {
        this.context.on('list:preview:fire', function(model) {
            app.events.trigger('preview:render', model, this.collection, true);
        }, this);

        //When switching to next/previous record from the preview panel, we need
        //to update the highlighted row.
        app.events.on('list:preview:decorate', this.decorateRow, this);
        if (this.layout) {
            this.layout.on('list:paginate:success', function() {
                //When fetching more records, we need to update the preview
                //collection.
                app.events.trigger('preview:collection:change', this.collection);
                // If we have a model in preview, redecorate the row as previewed
                if (this._previewed) {
                    this.decorateRow(this._previewed);
                }
            }, this);
        }
    },

    /**
     * Decorates the row in the list that is being shown in Preview.
     *
     * @param {Data.Bean} model The model corresponding to the row to be
     *   decorated. Pass a falsy value to clear decoration.
     */
    decorateRow: function(model) {
        this._previewed = model;
        this.$('li.highlighted').removeClass('highlighted current');
        if (model) {
            var curr = this.$('[data-id="' + model.id + '"]');
            curr.addClass('current highlighted');
        }
    },

    /**
     * Add the tags and facets options to the paginate query.
     * Please see the {@link Pagination#getNextPagination} for detail.
     *
     * @return {Object} Pagination fetch options.
     */
    getPaginationOptions: function() {
        var selectedFacets = this.context.get('selectedFacets');
        var tagFilters = _.pluck(this.context.get('tags'), 'id');
        var options = null;
        if (selectedFacets || tagFilters) {
            options = {
                apiOptions: {
                    data: {},
                    fetchWithPost: true,
                    useNewApi: true
                }
            };
        }
        if (selectedFacets) {
            options.apiOptions.data.agg_filters = selectedFacets;
        }
        if (tagFilters) {
            options.apiOptions.data.tag_filters = tagFilters;
        }

        return options;
    }
})
