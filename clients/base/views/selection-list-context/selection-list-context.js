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
 *
 * This view displays the selected records at the top of a selection list. It
 * also allows to unselect them.
 *
 * @class View.Views.Base.SelectionListContextView
 * @alias SUGAR.App.view.views.BaseSelectionListContextView
 * @extends View.View
 */

({
    className: 'selection-context',
    events: {
        'click [data-close-pill]': 'handleClosePillEvent',
        'click .reset_button': 'removeAllPills'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.pills = [];
        /**
         * The maximum number of pills that can be displayed.
         *
         * @property {number}
         */
        this.maxPillsDisplayed = 50;
        this._super('initialize', [options]);
     },

    /**
     * Adds a pill in the template.
     *
     * @param {Data.Bean|Object|Array} models The model, set of model attributes
     * or array of those corresponding to the pills to add.
     */
    addPill: function(models) {

        models = _.isArray(models) ? models : [models];

        if (_.isEmpty(models)) {
            return;
        }

        var pillsAttrs = [];
        var pillsIds = _.pluck(this.pills, 'id');

        _.each(models, function(model) {
            //FIXME : SC-4196 will remove this.
            var modelName = model.get('name') || model.get('full_name') ||
                app.utils.formatNameLocale(model.attributes) ||
                model.get('document_name');

            if (modelName && !_.contains(pillsIds, model.id)) {
                pillsAttrs.push({id: model.id, name: modelName});
            }
        });

        this.pills.push.apply(this.pills, pillsAttrs);

        this._debounceRender();
    },

    /**
     * Removes a pill from the template.
     *
     * @param {Data.Bean|Object|Array} models The model or array of models
     * corresponding to the pills to remove. It can also be an object or array
     * of objects containing the 'id' of the pills to remove.
     *
     */
    removePill: function(models) {
        models = _.isArray(models) ? models : [models];
        var ids = _.pluck(models, 'id');
        this.pills = _.reject(this.pills, function(pill) {
            return _.contains(ids, pill.id);
        });
        this._debounceRender();
    },

    /**
     * Removes all the pills and sends an event to clear the mass collection.
     *
     * @param {Event} The click event.
     */
    removeAllPills: function(event) {
        if (event) {
            if (this.$(event.target).hasClass('disabled')) {
                return;
            }
        }
        this.pills = [];
        this.render();
        this.context.trigger('mass_collection:clear');
    },

    /**
     * Resets the pills to match the mass collection. Useful to update pills
     * on mass collection reset.
     *
     * @param {Data.BeanCollection} collection The collection that has been reset.
     */
    resetPills: function(collection) {
        if (!collection.length) {
            this.pills = [];
        }
        this.render();
    },

    /**
     * Click handler for the `close` button on a pill.
     *
     * @param {Event} event The click event.
     */
    handleClosePillEvent: function(event) {
        var id = this.$(event.target).closest('.select2-search-choice').data('id').toString();
        this.closePill(id);
    },

    /**
     * Removes the pill and triggers an event to remove it the model from the
     * mass collection.
     *
     * @param {string} modelId The id of the model to remove.
     */
    closePill: function(modelId) {
        this.removePill({id: modelId});
        var model = this.massCollection.get(modelId);
        this.context.trigger('mass_collection:remove', model);
    },

    /**
     * Debounced version of render.
     *
     * @private
     */
    _debounceRender: _.debounce(function() {
        this.render();
    }, 50),

    /**
     * @inheritdoc
     */
    _render: function() {
        this.massCollection = this.context.get('mass_collection');
        if (!this.massCollection) {
            return;
        }

        if (this.pills.length > this.maxPillsDisplayed) {
            this.displayedPills = this.pills.slice(0, this.maxPillsDisplayed);
            this.tooManySelectedRecords = true;
            this.msgMaxPillsDisplayed = app.lang.get('TPL_MAX_PILLS_DISPLAYED', this.module, {
                maxPillsDisplayed: this.maxPillsDisplayed
            });
        } else {
            this.tooManySelectedRecords = false;
            this.displayedPills = this.pills;
        }

        var recordsLeft = this.massCollection.length - this.displayedPills.length;
        if (recordsLeft) {
            this.moreRecords = true;
            var label = this.displayedPills.length ? 'TPL_MORE_RECORDS' : 'TPL_RECORDS_SELECTED';
            this.msgMoreRecords = app.lang.get(label, this.module, {
                recordsLeft: recordsLeft
            });
        } else {
            this.moreRecords = false;
        }

        this._super('_render');
        this.stopListening(this.massCollection);

        this.listenTo(this.massCollection, 'add', this.addPill);
        this.listenTo(this.massCollection, 'remove', this.removePill);
        this.listenTo(this.massCollection, 'reset', this.resetPills);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.collection.on('sync', function() {

            var recordsToAdd = this.collection.filter(_.bind(function(model) {
                return this.massCollection.get(model.id);
            }, this));

            this.addPill(recordsToAdd);

        }, this);
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this.stopListening(this.massCollection);
        this._super('unbind');
    }
})
