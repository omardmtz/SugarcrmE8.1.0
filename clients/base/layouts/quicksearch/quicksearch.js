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
 * @class View.Views.Base.QuicksearchLayout
 * @alias SUGAR.App.view.views.BaseQuicksearchLayout
 * @extends View.Layout
 */
({
    className: 'search',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * The collection for executing searches and passing results.
         * This is to be shared and used by components.
         */
        // FIXME Sidecar should be modified to allow multiple top level contexts. When this happens, quick search
        // should use that context instead of this.collection.
        this.collection = app.data.createMixedBeanCollection();

        this.selectedTags = [];

        /**
         * Key to indicate version 2 search (new global search). This is used by the component views to determine
         * what version of the API to use. Default is false.
         * @type {boolean}
         */
        // FIXME SC-4254 Remove this.v2
        this.v2 = this.meta.v2 || false;

        /**
         * Index of the focused component. Only the focused component should have keyboard listeners.
         * @type {number}
         */
        this.compOnFocusIndex = 0;

        /**
         * Maximum viewport width of responsive mode. Under this width we should
         * be in responsive mode, and above in desktop mode.
         * @type {number}
         */
        this.maxResponsiveWidth = 540;

        /**
         * Tells if we're in responsive mode or not.
         *
         * In responsive mode, the search bar is hidden when collapsed and only
         * the magnifying glass is shown. When it's expanded, it takes the full
         * width of the screen.
         *
         * @type {boolean}
         */
        this.isResponsiveMode = false;

        /**
         * Indicates if the search bar is expanded
         * @type {boolean}
         */
        this.expanded = false;

        // shortcut keys
        // Focus the search bar
        app.shortcuts.registerGlobal({
            id: 'Search:Focus',
            keys: ['s', 'mod+alt+0'],
            component: this,
            description: 'LBL_SHORTCUT_SEARCH',
            handler: function() {
                this.trigger('navigate:to:component', 'quicksearch-bar');
                this.triggerExpand();
            }
        });

        // When a component is trying to navigate from its last element to the next component,
        // Check to make sure there is a next navigable component. If it exists, set it to the component to focus
        this.before('navigate:next:component', function() {
            var i = this.compOnFocusIndex, comp;
            while (comp = this._components[++i]) {
                if (_.result(comp, 'isFocusable')) {
                    this.compOnFocusIndex = i;
                    return true;
                }
            }
            return false;
        }, this);

        // When a component is trying to navigate from its first element to the previous component,
        // Check to make sure there is a previous navigable component. If it exists, set it to the component to focus
        this.before('navigate:previous:component', function() {
            var i = this.compOnFocusIndex, comp;
            while (comp = this._components[--i]) {
                if (_.result(comp, 'isFocusable')) {
                    this.compOnFocusIndex = i;
                    return true;
                }
            }
            return false;
        }, this);

        // Navigate to the next component. We have already set this.compOnFocusIndex in the before function.
        this.on('navigate:next:component', function() {
            this._components[this.compOnFocusIndex].trigger('navigate:focus:receive', true);
        }, this);

        // Navigate to the previous component. We have already set this.compOnFocusIndex in the previous function.
        this.on('navigate:previous:component', function() {
            this._components[this.compOnFocusIndex].trigger('navigate:focus:receive', false);
        }, this);

        // Navigate to a specific component. This bypasses the previous/next logic.
        this.on('navigate:to:component', function(componentName) {
            var newIndex = this.compOnFocusIndex;
            // Find the index of the component that is requesting focus.
            // We cannot use `layout.getComponent()` because that only returns the component, not the index.
            _.each(this._components, function(component, index) {
                if (componentName === component.name) {
                    newIndex = index;
                    return;
                }
            });
            // Unfocus the old component and focus on the new component.
            this._components[this.compOnFocusIndex].trigger('navigate:focus:lost');
            this.compOnFocusIndex = newIndex;
            this._components[this.compOnFocusIndex].trigger('navigate:focus:receive');
        }, this);

        // Reset navigation
        this.on('quicksearch:close', function(keepExpanded) {
            this.removeFocus();
            if (!this.expanded) {
                return;
            }
            this.collection.abortFetchRequest();
            if (keepExpanded) {
                return;
            }
            this.collapse();
        }, this);

        this.on('quicksearch:expand', this.expand);

        // Listener for app:view:change to expand or collapse the search bar
        app.events.on('app:view:change', function() {
            if (this.context.get('search')) {
                this.triggerExpand();
            } else {
                _.bind(this.collapse, this);
            }
        }, this);

        this.$el.focusin(_.bind(function() {
            this.$el.off('focusout');
            this.$el.focusout(_.bind(function() {
                this.$el.off('focusout');
                _.defer(_.bind(function() {
                    // We use `has(':focus')` instead of `is(':focus')` to check
                    // if the focused element is or is inside `this.$el`.
                    if (this.$el.has(':focus').length === 0) {
                        this.trigger('quicksearch:close', this.context.get('search'));
                    }
                }, this));
            }, this));
        }, this));

        // Listener for display of tags ribbon menu
        this.on('quicksearch:tag:open', function() {
            this.$el.addClass('quicksearch-tags-open');
        }, this);

        // Listener for hiding of tags ribbon menu
        this.on('quicksearch:tag:close', function() {
            this.$el.removeClass('quicksearch-tags-open');
        }, this);

        // On window resize, if expanded, recalculate expansion
        $(window)
            .off('resize.quicksearch')
            .on('resize.quicksearch', _.debounce(_.bind(this.resizeHandler, this), 10));
    },

    /**
     * @inheritdoc
     */
    _placeComponent: function(component) {
        if (component.name === 'quicksearch-modulelist' ||
            component.name === 'quicksearch-taglist' ||
            component.name === 'quicksearch-bar'
        ) {
            this.$('[data-component=searchbar]').append(component.el);
        } else {
            this._super('_placeComponent', [component]);
        }
    },

    /**
     * Removes the current focus and resets the focused index
     */
    removeFocus: function() {
        this._components[this.compOnFocusIndex].trigger('navigate:focus:lost');
        this.compOnFocusIndex = 0;
    },

    /**
     * Expand quicksearch bar asynchronously.
     * @param {boolean} update `true` means the expansion is to update the width.
     *                  `false` means the expansion is new and needs animation.
     */
    triggerExpand: function(update) {
        _.defer(_.bind(this.expand, this, update));
    },

    /**
     * Expands the quicksearch.
     *
     * @param {boolean} update `true` means the expansion is to update the width.
     *                  `false` means the expansion is new and needs animation.
     */
    expand: function(update) {
        // if the search bar is already expanded and it is not an update,
        // do nothing.
        if (this.expanded && !update) {
            return;
        }

        this.expanded = true;
        this.$el.addClass('expanded');

        // On route, call the router handler.
        app.router
            .off('route', this.routerHandler)
            .on('route', this.routerHandler, this);

        this.trigger('quicksearch:expanded');
        this.trigger('quicksearch:button:toggle', false);

        if (this.isResponsiveMode) {
            this.trigger('navigate:to:component', 'quicksearch-bar');
            return;
        }

        // Calculate the target searchbox width
        var newWidth = this._calculateExpansion();

        // if the newWidth is not defined, then the menu hasn't completely
        // loaded, and we should do nothing.
        if (_.isUndefined(newWidth)) {
            return;
        }

        // For new expansions, we need to clear out the modules.
        var headerLayout = this.closestComponent('header');
        if (_.isUndefined(headerLayout)) {
            return;
        }
        headerLayout.trigger('view:resize', headerLayout.getModuleListMinWidth());

        // Now that there is space for the search bar to expand, animate the
        // expansion.
        if (update) {
            this.$('[data-component=searchbar]').width(newWidth);
        } else {
            this.$('[data-component=searchbar]').animate({width: newWidth}, {duration: 100});
        }

        // Turn off the default header resize listener
        headerLayout.setModuleListResize(false);
    },

    /**
     * Resizes the expanded search bar when the window is resized.
     * @private
     */
    resizeHandler: function() {
        this._toggleResponsiveMode();
        if (this.expanded && !this.isResponsiveMode) {
            this.triggerExpand(true);
        }
    },

    /**
     * Renders the layout and toggles the responsive class.
     *
     * @private
     */
    _render: function() {
        this._super('_render');
        this._toggleResponsiveMode();
    },

    /**
     * Toggles the responsive mode according to the viewport width.
     *
     * @private
     */
    _toggleResponsiveMode: function() {
        this.isResponsiveMode = $(window).width() < this.maxResponsiveWidth;
    },

    /**
     * Handles the route event on the router.
     *
     * This simple function allows us to reuse a function pointer to the router
     * handler. The router does not allow namespaced events such as
     * `route.quicksearch`. So, this function pointer is necessary to
     * properly dispose the event handler.
     */
    routerHandler: function() {
        this.trigger('quicksearch:close', this.context.get('search'));
    },

    /**
     * Calculates the target width for the search bar expansion based off the current state of the megamenu.
     *
     * @return {number} The target width for expansion.
     * @private
     */
    _calculateExpansion: function() {
        var headerLayout = this.closestComponent('header');
        if (_.isUndefined(headerLayout)) {
            return;
        }

        // The starting width of the input box
        var searchbarStartingWidth = this.$('[data-component=searchbar]').outerWidth();

        // The total width of the module list header
        var totalModuleWidth = headerLayout.getModuleListWidth();

        // The minimum width necessary for module list header
        var minimumModuleWidth = headerLayout.getModuleListMinWidth();

        // The target width is most of the module list, saving room for the
        // minimum module list width.
        return searchbarStartingWidth +
            totalModuleWidth -
            minimumModuleWidth;
    },

    /**
     * Collapses the quicksearch.
     */
    collapse: function() {
        this.expanded = false;
        this.$el.removeClass('expanded');

        this.trigger('quicksearch:collapse');
        app.router.off('route', this.routerHandler);
        this.trigger('quicksearch:button:toggle', true);

        if (this.isResponsiveMode) {
            return;
        }
        var headerLayout = this.closestComponent('header');
        if (_.isUndefined(headerLayout)) {
            return;
        }

        // Turn on the default header resize listener
        headerLayout.setModuleListResize(true);

        // jQuery `width` function with no arguments (or null arguments) only
        // returns the current width. Calling `width('')` with the empty string
        // sets the width to an empty value, which the browser ignores and
        // uses the css width.
        this.$('[data-component=searchbar]').width('');
        headerLayout.resize();
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        app.router.off('route', null, this);
        this.$el.off();
        $(window).off('resize.quicksearch');
        this._super('unbind');
    }
})
