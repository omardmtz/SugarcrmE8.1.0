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
 * The MultiSelectionListView extends SelectionListView and adds the ability to
 * select multiple records in the list and get the selected items as the output.
 * The way to use it is similar to the SelectionListView.
 *
 * It adds the following properties which have to be set in the context:
 *
 * - `maxSelectedRecords` The max number of records the user can select in the
 *    case of multiselect selection list.
 * - `independentMassCollection` {boolean} `true` if the selected records should
 *   be handled independently from the view collection. If `false` selected
 *   records are tied to the view collection, which means they are reset if the
 *   view collection is reset.
 *
 * @class View.Views.Base.MultiSelectionListView
 * @alias SUGAR.App.view.views.BaseMultiSelectionListView
 * @extends View.Views.Base.SelectionListView
 */
({
    extendsFrom: 'SelectionListView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins, ['MassCollection']);

        /**
         * Maximum number of records a user can select.
         *
         * @property {number}
         */
        this.maxSelectedRecords = options.context.get('maxSelectedRecords');

        /**
         * Boolean to know whether the selected records called `mass collection`
         * should be tied to the view collection or independent.
         *
         * If tied, selected records would have to be in the current view collection.
         * As soon as the view collection is reset, the mass collection would be
         * reset.
         *
         * @property {boolean} `true` for an independent mass collection. `false`
         *   for the mass collection to be tied to the view collection.
         */
        this.independentMassCollection = options.context.get('independentMassCollection') || true;
        // if no metadata defined for `multi-selection-list`, use `selection-list`
        options.meta = _.extend({}, app.metadata.getView(options.module, 'selection-list'), options.meta);
        this._super('initialize', [options]);
    },

    /**
     * @inheritdoc
     * FIXME: SC-4075 will remove this method.
     */
    setSelectionMeta: function(options) {
        options.meta.selection = {
            type: 'multi',
            isSearchAndSelectAction: true,
            disable_select_all_alert: !!this.maxSelectedRecords
        };
    },

    /**
     * Sets up events.
     *
     * @override
     */
    initializeEvents: function() {
        this.context.on('selection:select:fire', this._validateSelection, this);
    },

    /**
     * Closes the drawer passing the selected models attributes to the callback.
     *
     * @protected
     */
    _validateSelection: function() {
        var selectedModels = this.context.get('mass_collection');
        if (selectedModels.length > this.maxSelectedRecords) {
            this._showMaxSelectedRecordsAlert();
            return;
        }
        app.drawer.close(this._getCollectionAttributes(selectedModels));
    },

    /**
     * Displays error message since the number of selected records exceeds the
     * maximum allowed.
     *
     * @private
     */
    _showMaxSelectedRecordsAlert: function() {
        var msg = app.lang.get('TPL_FILTER_MAX_NUMBER_RECORDS', this.module, {
            maxRecords: this.maxSelectedRecords
        });
        app.alert.show('too-many-selected-records', {
            level: 'error',
            messages: msg,
            autoClose: false
        });
    },

    /**
     * Returns an array of attributes given a collection.
     *
     * @param {Data.BeanCollection} collection A collection of records.
     * @return {Object[]} attributes An array containing the attribute object of
     *   each model.
     *
     * @private
     */
    _getCollectionAttributes: function(collection) {
        var attributes = _.map(collection.models, this._getModelAttributes, this);
        return attributes;
    }
})
