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
 * @class View.Views.Base.ForecastsConfigWorksheetColumnsView
 * @alias SUGAR.App.view.layouts.BaseForecastsConfigWorksheetColumnsView
 * @extends View.Views.Base.ConfigPanelView
 */
({
    extendsFrom: 'ConfigPanelView',

    /**
     * Holds the select2 reference to the #wkstColumnSelect element
     */
    wkstColumnsSelect2: undefined,

    /**
     * Holds the default/selected items
     */
    selectedOptions: [],

    /**
     * Holds all items
     */
    allOptions: [],

    /**
     * The field object id/label for likely_case
     */
    likelyFieldObj: {},

    /**
     * The field object id/label for best_case
     */
    bestFieldObj: {},

    /**
     * The field object id/label for worst_case
     */
    worstFieldObj: {},

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // patch metadata for if opps_view_by is Opportunities, not RLIs
        if (app.metadata.getModule('Opportunities', 'config').opps_view_by === 'Opportunities') {
            _.each(_.first(options.meta.panels).fields, function(field) {
                if (field.label_module && field.label_module === 'RevenueLineItems') {
                    field.label_module = 'Opportunities';
                }
            });
        }

        this._super('initialize', [options]);

        this.allOptions = [];
        this.selectedOptions = [];

        var cfgFields = this.model.get('worksheet_columns'),
            index = 0;

        // set up scenarioOptions
        _.each(this.meta.panels[0].fields, function(field) {
            var obj = {
                    id: field.name,
                    text: app.lang.get(field.label, this._getLabelModule(field.name, field.label_module)),
                    index: index,
                    locked: field.locked || false
                },
                cField = _.find(cfgFields, function(cfgField) {
                    return cfgField == field.name;
                }, this),
                addFieldToFullList = true;

            // save the field objects
            if (field.name == 'best_case') {
                this.bestFieldObj = obj;
                addFieldToFullList = (this.model.get('show_worksheet_best') === 1);
            } else if (field.name == 'likely_case') {
                this.likelyFieldObj = obj;
                addFieldToFullList = (this.model.get('show_worksheet_likely') === 1);
            } else if (field.name == 'worst_case') {
                this.worstFieldObj = obj;
                addFieldToFullList = (this.model.get('show_worksheet_worst') === 1);
            }

            if (addFieldToFullList) {
                this.allOptions.push(obj);
            }

            // If the current field being processed was found in the config fields,
            if (!_.isUndefined(cField)) {
                // push field to defaults
                this.selectedOptions.push(obj);
            }

            index++;
        }, this);
    },

    /**
     * Empty function as the title values have already been set properly
     * with the change:worksheet_columns event handler
     *
     * @inheritdoc
     */
    _updateTitleValues: function() {
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:worksheet_columns', function() {
            var arr = [],
                cfgFields = this.model.get('worksheet_columns'),
                metaFields = this.meta.panels[0].fields;

            _.each(metaFields, function(metaField) {
                _.each(cfgFields, function(field) {
                    if (metaField.name == field) {
                        arr.push(
                            app.lang.get(
                                metaField.label,
                                this._getLabelModule(metaField.name, metaField.label_module)
                            )
                        );
                    }
                }, this);
            }, this);
            this.titleSelectedValues = arr.join(', ');

            // Handle truncating the title string and adding "..."
            this.titleSelectedValues = this.titleSelectedValues.slice(0, 50) + '...';

            this.updateTitle();
        }, this);

        // trigger the change event to set the title when this gets added
        this.model.trigger('change:worksheet_columns');

        this.model.on('change:scenarios', function() {
            // check model settings and update select2 options
            if (this.model.get('show_worksheet_best')) {
                this.addOption(this.bestFieldObj);
            } else {
                this.removeOption(this.bestFieldObj);
            }

            if (this.model.get('show_worksheet_likely')) {
                this.addOption(this.likelyFieldObj);
            } else {
                this.removeOption(this.likelyFieldObj);
            }

            if (this.model.get('show_worksheet_worst')) {
                this.addOption(this.worstFieldObj);
            } else {
                this.removeOption(this.worstFieldObj);
            }

            // force render
            this._render();

            // update the model, since a field was added or removed
            var arr = [];
            _.each(this.selectedOptions, function(field) {
                arr.push(field.id);
            }, this);

            this.model.set('worksheet_columns', arr);
        }, this);
    },

    /**
     * Adds a field object to allOptions & selectedOptions if it is not found in those arrays
     *
     * @param {Object} fieldObj
     */
    addOption: function(fieldObj) {
        if (!_.contains(this.allOptions, fieldObj)) {
            this.allOptions.splice(fieldObj.index, 0, fieldObj);
            this.selectedOptions.splice(fieldObj.index, 0, fieldObj);
        }
    },

    /**
     * Removes a field object to allOptions & selectedOptions if it is not found in those arrays
     *
     * @param {Object} fieldObj
     */
    removeOption: function(fieldObj) {
        this.allOptions = _.without(this.allOptions, fieldObj);
        this.selectedOptions = _.without(this.selectedOptions, fieldObj);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        // handle setting up select2 options
        this.wkstColumnsSelect2 = this.$('#wkstColumnsSelect').select2({
            data: this.allOptions,
            multiple: true,
            containerCssClass: 'select2-choices-pills-close',
            initSelection: _.bind(function(element, callback) {
                callback(this.selectedOptions);
            }, this)
        });
        this.wkstColumnsSelect2.select2('val', this.selectedOptions);

        this.wkstColumnsSelect2.on('change', _.bind(this.handleColumnModelChange, this));
    },

    /**
     * Handles the select2 adding/removing columns
     *
     * @param {Object} evt change event from the select2 selected values
     */
    handleColumnModelChange: function(evt) {
        // did we add something?  if so, lets add it to the selectedOptions
        if (!_.isUndefined(evt.added)) {
            this.selectedOptions.push(evt.added);
        }

        // did we remove something? if so, lets remove it from the selectedOptions
        if (!_.isUndefined(evt.removed)) {
            this.selectedOptions = _.without(this.selectedOptions, evt.removed);
        }

        this.model.set('worksheet_columns', evt.val);
    },

    /**
     * @inheritdoc
     *
     * Remove custom listeners off select2 instances
     */
    _dispose: function() {
        if (this.wkstColumnsSelect2) {
            this.wkstColumnsSelect2.off();
            this.wkstColumnsSelect2.select2('destroy');
            this.wkstColumnsSelect2 = null;
        }
        this._super('_dispose');
    },

    /**
     * Re-usable method to get the module label for the column list
     *
     * @param {String} fieldName The field we are currently looking at
     * @param {String} setModule If the metadata has a module set it will be passed in here
     * @return {string}
     * @private
     */
    _getLabelModule: function(fieldName, setModule) {
        var labelModule = setModule || 'Forecasts';
        if (fieldName === 'parent_name') {
            // when we have the parent_name, pull the label from the module we are forecasting by
            labelModule = this.model.get('forecast_by');
        }

        return labelModule;
    }
})
