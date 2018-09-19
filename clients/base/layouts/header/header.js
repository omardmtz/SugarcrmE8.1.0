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
 * @class View.Layouts.Base.HeaderLayout
 * @alias SUGAR.App.view.layouts.BaseHeaderLayout
 * @extends View.Layout
 */
({
    /**
     * Listen to events to resize the header to fit the browser width
     * @param options
     */
    initialize: function(options) {
        app.view.Layout.prototype.initialize.call(this, options);
        this.on('header:update:route', this.resize, this);
        app.events.on('app:view:change', this.resize, this);
        // Event listeners for showing and hiding the megamenu on auth expiration
        app.events.on('app:login', this.hide, this);
        app.events.on('app:login:success', this.show, this);

        var resize = _.bind(this.resize, this);
        $(window)
            .off('resize.header')
            .on('resize.header', resize);
    },

    /**
     * Places all components within this layout inside nav-collapse div
     * @param component
     * @private
     */
    _placeComponent: function(component) {
        this.$el.find('.nav-collapse').append(component.$el);
    },

    /**
     * Calculates the width that the module list should resize to and triggers an event
     * that tells the module list to resize
     */
    resize: function() {
        var resizeWidth = this.getModuleListWidth();
        this.trigger('view:resize', resizeWidth);
    },

    /**
     * Returns the calculated module list width.
     * @return {number}
     */
    getModuleListWidth: function() {
        var maxMenuWidth = $(window).width();
        var totalWidth = 0;

        _.each(this._components, function(component) {
            if (component.name !== 'module-list') {
                // only calculate width for visible components
                if (component.$el.is(':visible')) {
                    totalWidth += component.$el.outerWidth(true);
                }
            }
        });
        return maxMenuWidth - totalWidth;
    },

    /**
     * Returns the minimum module list width.
     * @return {number}
     */
    getModuleListMinWidth: function() {
        var moduleListView = this.getComponent('module-list');
        if (moduleListView) {
            return moduleListView.computeMinWidth();
        }
    },

    /**
     * Sets whether or not the module-list should listen to the window resize.
     * @param {boolean} resize
     */
    setModuleListResize: function(resize) {
        this.getComponent('module-list').toggleResize(resize);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        // If we are authenticated show the megamenu
        if (app.api.isAuthenticated()) {
            this.show();
        } else {
            this.hide();
        }
    },

    /**
     * @inheritdoc
     */
    show: function() {
        this._super('show');
        this.resize();
    }
})
