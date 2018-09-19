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
 * @class View.Views.Base.SearchDashboardHeaderpaneView
 * @alias SUGAR.App.view.views.BaseSearchDashboardHeaderpaneView
 * @extends View.View
 */
({
    className: 'search-dashboard-headerpane',
    events: {
        'click a[name=collapse_button]' : 'collapseClicked',
        'click a[name=expand_button]' : 'expandClicked',
        'click a[name=reset_button]' : 'resetClicked'
    },

    /**
     * Collapses all the dashlets in the dashboard.
     */
    collapseClicked: function() {
        this.context.trigger('dashboard:collapse:fire', true);
    },

    /**
     * Expands all the dashlets in the dashboard.
     */
    expandClicked: function() {
        this.context.trigger('dashboard:collapse:fire', false);
    },

    /**
     * Triggers 'facets:reset' event to reset the facets applied on the search.
     */
    resetClicked: function() {
        this.context.parent.trigger('facets:reset', true);
    }
})
