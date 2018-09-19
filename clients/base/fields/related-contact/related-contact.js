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
 * RelatedContactField is needed because BaseField specifically gets values from specific keys
 * on the model, and for the HistorySummaryView list, we need to set values on the model
 * that are different from what BaseField uses so it doesn't conflict with the other values
 * on BaseField's model
 *
 * @class View.Fields.Base.RelatedContactField
 * @alias SUGAR.App.view.fields.BaseRelatedContactField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click a': 'onLinkClicked'
    },

    /**
     * Holds the href for the field link
     */
    linkRoute: '',

    /**
     * @inheritdoc
     * @override
     *
     * Overriding since the parent buildHref would use 'id' not 'contact_id'
     * to create the href link
     */
    buildHref: function() {
        var defRoute = this.def.route ? this.def.route : {},
            module = this.model.module || this.context.get('module'),
            id = this.model.get('contact_id');
        this.linkRoute = '#' + app.router.buildRoute(module, id, defRoute.action);
        return this.linkRoute;
    },

    /**
     * Intercepts the clicked link, if the user clicked on the Contact
     * that the user was already viewing, refresh the page, because otherwise
     * the url will not change
     *
     * @param {jQuery.Event} evt The click event from the link
     */
    onLinkClicked: function(evt) {
        var currentRoute = '#' + Backbone.history.getFragment();
        if (currentRoute === this.linkRoute) {
            app.router.refresh();
        }
    }
})
