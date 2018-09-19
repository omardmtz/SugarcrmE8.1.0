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
 * @class View.Views.Base.FilteredSearchView
 * @alias SUGAR.App.view.views.BaseFilteredSearchView
 * @extends View.View
 */
({
    events: {
        'keyup [data-searchfield]': 'searchFired'
    },

    /**
     * @inheritdoc
     *
     * Update searchable fields.
     */
    bindDataChange: function() {
        this.context.on('filteredlist:filter:set', this.setFilter, this);
    },

    /**
     * Update quick search placeholder to display searchable fields.
     * @param {Array} filter List of field name.
     */
    setFilter: function(filter) {
        var label = app.lang.get('LBL_SEARCH_BY') + ' ' + filter.join(', ') + '...';
        this.$('[data-searchfield]').attr('placeholder', label);
    },

    /**
     * Updated current typed search term.
     */
    searchFired: _.debounce(function(evt) {
        var value = $(evt.currentTarget).val();
        this.context.trigger('filteredlist:search:fired', value);
    }, 100)
})
