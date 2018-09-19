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
 * Header section for Subpanel layouts.
 *
 * @class View.Views.Base.PanelTopView
 * @alias SUGAR.App.view.views.BasePanelTopView
 * @extends View.View
 */
({
    /**
     * @inheritdoc
     */
    className: 'subpanel-header',

    /**
     * @inheritdoc
     */
    attributes: {
        'data-sortable-subpanel': 'true'
    },

    /**
     * @inheritdoc
     */
    events: {
        'click': 'togglePanel',
        'click a[name=create_button]:not(".disabled")': 'createRelatedClicked',
        'keydown [data-a11y=toggle]': '_handleKeyClick'
    },

    plugins: ['LinkedModel'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // FIXME: SC-3594 will address having child views extending metadata
        // from its parent.
        options.meta = _.extend(
            {},
            app.metadata.getView(null, 'panel-top'),
            app.metadata.getView(options.module, 'panel-top'),
            options.meta
        );

        this._super('initialize', [options]);

        // This is in place to get the lang strings from the right module. See
        // if there is a better way to do this later.
        this.parentModule = this.context.parent.get('module');

        // Listen to the context for collapsed attribute to change
        // and toggle the aria-expanded attribute on the button element
        this.listenTo(this.context, 'change:collapsed', this._toggleAria);

        // FIXME: Revisit with SC-4775.
        this.on('linked-model:create', function() {
            this.context.set('skipFetch', false);
            this.context.reloadData();
        }, this);
    },

    /**
     * Event handler for the create button.
     *
     * @param {Event} event The click event.
     */
    createRelatedClicked: function(event) {
        this.createRelatedRecord(this.module);
    },

    /**
    * Event handler that toggles the subpanel layout when the SubpanelHeader is
    * clicked.
    *
    * Triggers the `panel:toggle` event to toggle the subpanel.
    *
    * @param {Event} The `click` event.
    */
    togglePanel: function(evt) {
        if (_.isNull(this.$el)) {
            return;
        }

        var $target = this.$(evt.target),
            isLink = $target.closest('a, button').length;

        if (isLink) {
            return;
        }

        this.context.set('collapsed', !this.context.get('collapsed'));
    },

    /**
     * Sets the subpanel header accessibility class 'aria-expanded' to true or false
     * depending on if the subpanel is open or closed.
     *
     * @private
     */
    _toggleAria: function(context, collapsed) {
        this.$('[data-a11y=toggle]')
            .attr('aria-expanded', !collapsed);
    },

    /**
     * Triggers the click event when the data-a11y toggle element has focus
     * and the spacebar or enter keydown event occurs
     *
     * @private
     */
    _handleKeyClick: function(evt) {
        app.accessibility.handleKeyClick(evt, this.$el);
    }
})
