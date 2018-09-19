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
 * The SelectionListView provides an easy way to select a record from a list.
 * It's designed to be used in a drawer. The model attributes of the selected
 * record will be passed to the drawer callback.
 *
 * The SelectionListView has a generic implementation and can be overriden for
 * particular uses.
 *
 * It has to be opened passing the following data in the drawer's context:
 *
 * - `module` {String} The module the list is related to.
 * - `fields` {Array} The fields to be displayed.
 * - `filterOptions` {Object} the filter options for the list view.
 *
 *  Example of usage:
 *
 *     app.drawer.open({
 *              layout: 'selection-list',
 *               context: {
 *                   module: this.getSearchModule(),
 *                   fields: this.getSearchFields(),
 *                   filterOptions: this.getFilterOptions(),
 *               }
 *           }, _.bind(this.setValue, this));
 *     },
 *
 * @class View.Views.Base.SelectionListView
 * @alias SUGAR.App.view.views.BaseSelectionListView
 * @extends View.Views.Base.FlexListView
 */
({
    extendsFrom: 'FlexListView',

    dataView: 'selection-list',

    initialize: function(options) {
        // Since list.js only fetches list view metadata, we need to build our
        // own metadata to send to the parent.
        var viewMeta = app.metadata.getView(options.module, options.name) ||
                       app.metadata.getView(options.module, this.dataView) || {};
        this.plugins = _.union(this.plugins, ['ListColumnEllipsis', 'ListRemoveLinks']);
        //setting skipFetch to true so that loadData will not run on initial load and the filter load the view.
        options.context.set('skipFetch', true);
        options.meta = _.extend(viewMeta, options.meta || {});
        this.setSelectionMeta(options);
        this._super('initialize', [options]);

        // set list back to flex-list
        this.tplName = 'flex-list';

        this.events = _.extend({}, this.events, {
            'click .search-and-select .single': 'triggerCheck'
        });
        this.initializeEvents();
    },

    /**
     * Sets metadata proper to selection-list.
     *
     * @param {Object} options
     *
     * FIXME: SC-4075 will remove this method.
     */
    setSelectionMeta: function(options) {
        options.meta.selection = {
            type: 'single',
            label: 'LBL_LINK_SELECT',
            isSearchAndSelectAction: true
        };
    },

    /**
     * Checks the `[data-check=one]` element when the row is clicked.
     *
     * @param {Event} event The `click` event.
     */
    triggerCheck: function(event) {
        //Ignore inputs and links/icons, because those already have defined effects
        if (!($(event.target).is('a,i,input'))) {
            var checkbox = $(event.currentTarget).find('[data-check=one]');
            checkbox[0].click();
        }
    },

    /**
     * Sets up events.
     */
    initializeEvents: function() {
        this.context.on('change:selection_model selection-list:select', this._selectAndClose, this);
    },

    /**
     * Closes the drawer passing the selected model attributes to the callback.
     *
     * @param {object} context
     * @param {Data.Bean} selectedModel The selected record.
     *
     * @protected
     */
    _selectAndClose: function(context, selectedModel) {
        if (selectedModel) {
            this.context.unset('selection_model', {silent: true});
            app.drawer.close(this._getModelAttributes(selectedModel));
        }
    },

    /**
     * Returns attributes given a model with ACL check.
     *
     * @param {Data.bean} model
     * @return {object} attributes
     *
     * @private
     */
    _getModelAttributes: function(model) {
        var attributes = {
            id: model.id,
            value: model.get('name')
        };

        //only pass attributes if the user has view access
        _.each(model.attributes, function(value, field) {
            if (app.acl.hasAccessToModel('view', model, field)) {
                attributes[field] = attributes[field] || model.get(field);
            }
        }, this);

        return attributes;
    },

    /**
     * Adds Preview button on the actions column on the right.
     */
    addActions: function() {
        this._super('addActions');
        if (this.meta.showPreview !== false) {
            this.rightColumns.push({
                type: 'preview-button',
                css_class: 'btn',
                tooltip: 'LBL_PREVIEW',
                event: 'list:preview:fire',
                icon: 'fa-eye',
                acl_action: 'view'
            });
        } else {
            this.rightColumns.push({});
        }
    }
})
