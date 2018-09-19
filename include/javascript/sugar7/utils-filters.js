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

        app.utils = _.extend(app.utils, {

            'FilterOptions': Backbone.Model.extend({

                /**
                 * @inheritdoc
                 */
                initialize: function(options) {
                    this._filterPopulateNames = [];
                    this._filterRelate = null;
                },

                /**
                 * Sets relate fields.
                 *
                 * @param {Object} value The list of fields.
                 * @chainable
                 */
                setFilterRelate: function(value) {
                    if (!_.isObject(value) || _.isEmpty(value)) {
                        return this;
                    }
                    this._filterRelate = value;
                    this.set('stickiness', false);
                    return this;
                },

                /**
                 * Sets populate filter hash.
                 *
                 * @param {Object} hash The list of fields and their values.
                 * @chainable
                 */
                setFilterPopulate: function(value) {
                    if (!_.isObject(value) || _.isEmpty(value)) {
                        return this;
                    }
                    this.set('filter_populate', value);
                    this.set('stickiness', false);
                    return this;
                },

                /**
                 * Sets initial filter id.
                 *
                 * @param {String} id The id of the initial filter.
                 * @chainable
                 */
                setInitialFilter: function(id) {
                    this.set('initial_filter', id);
                    return this;
                },

                /**
                 * Sets initial filter name.
                 *
                 * @param {String} name The name of the initial filter.
                 * @chainable
                 */
                setInitialFilterLabel: function(name) {
                    this.set('initial_filter_label', name);
                    return this;
                },

                /**
                 * Sets the list of modules from which to look up the initial
                 * filter label string.
                 *
                 * @param {String[]} modules The list of modules.
                 */
                setLangModules: function(modules) {
                    if (!_.isArray(modules)) {
                        return;
                    }
                    this.set('initial_filter_lang_modules', modules);
                },

                /**
                 * Sets filter name based on values of populate fields.
                 *
                 * @chainable
                 */
                relateInitialFilterLabel: function() {
                    this.set('initial_filter_label', this._filterPopulateNames.join(', '));
                    return this;
                },

                /**
                 * Initializes filter options.
                 *
                 * @param {Object} options The list of fields.
                 * @chainable
                 */
                config: function(options) {
                    options = options || {};
                    this.setInitialFilter(options.initial_filter);
                    this.setInitialFilterLabel(options.initial_filter_label);
                    this.setFilterPopulate(options.filter_populate);
                    this.setFilterRelate(options.filter_relate);
                    return this;
                },

                /**
                 * Populates `filter_populate` with relate fields' values.
                 *
                 * @param {Backbone.Model} relateModel The related model.
                 * @chainable
                 */
                populateRelate: function(relateModel) {
                    if (_.isEmpty(this._filterRelate)) {
                        return this;
                    }
                    var filterPopulate = this.get('filter_populate') || {};
                    _.each(this._filterRelate, function(toField, fromField) {
                        filterPopulate[toField] = relateModel.get(fromField);

                        var relateNameField = _.find(relateModel.fields, function(field) {
                            return field.id_name === fromField;
                        });
                        if (relateNameField) {
                            this._filterPopulateNames.push(relateModel.get(relateNameField.name));
                        }
                    }, this);
                    this.set('filter_populate', filterPopulate);

                    if (!this.get('initial_filter_label')) {
                        this.relateInitialFilterLabel();
                    }
                    return this;
                },

                /**
                 * Outputs the filter options.
                 *
                 * @return {Object/undefined} The hash of filter options.
                 */
                format: function() {
                    if (!this.get('initial_filter') || !this.get('filter_populate')) {
                        return;
                    }
                    return this.toJSON();
                }
            })
        });
    });
})(SUGAR.App);
