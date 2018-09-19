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
 * @class View.Layouts.Base.PanelLayout
 * @alias SUGAR.App.view.layouts.BasePanelLayout
 * @extends View.Layout
 */
({
    /**
     * @inheritdoc
     */
    className: 'filtered tabbable tabs-left',

    // "Hide/Show" state per panel
    HIDE_SHOW_KEY: 'hide-show',
    HIDE_SHOW: {
        HIDE: 'hide',
        SHOW: 'show'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._initPanelState();
    },

    /**
     * Sets the `collapsed` state of the panel's context, depending on if
     * `app.config.collapseSubpanels` is configured or if the panel was
     * previously shown/hidden.
     *
     * @protected
     */
    _initPanelState: function() {
        var collapse;
        this.hideShowLastStateKey = app.user.lastState.key(this.HIDE_SHOW_KEY, this);

        if (app.config.collapseSubpanels) {
            collapse = true;
        } else {
            var hideShowLastState = app.user.lastState.get(this.hideShowLastStateKey);
            collapse = _.isUndefined(hideShowLastState) || hideShowLastState !== this.HIDE_SHOW.SHOW;
        }
        this.context.set('collapsed', collapse);
    },

    /**
     * @inheritdoc
     *
     * Decorate the subpanel based on if the collection is empty or not.
     *
     * When context is reloaded, we open the panel only if `skipFetch` is
     * `false`.
     *
     * When the context's collapse attribute changes, we confirm that the
     * panel's status is in sync with the flag (expanded/collapsed).
     */
    bindDataChange: function() {
        this.listenTo(this.collection, 'reset add remove', function() {
            this.$('.subpanel').toggleClass('empty', this.collection.length === 0);
        }, this);

        this.listenTo(this.context, 'refresh:count', function(hasAtLeast, properties) {
            this.$('.subpanel').toggleClass('empty', !properties.length);
        }, this);

        this.listenTo(this.context.parent, 'panel-top:refresh', function(link) {
            app.logger.warn('`panel-top:refresh` is deprecated. Use `context.reloadData()` to reload and expand.');
            if (this.context.get('link') === link) {
                this.context.resetLoadFlag();
                this.toggle(true);
            }
        });

        this.listenTo(this.context, 'reload', function() {
            if (!this.context.get('skipFetch')) {
                this.toggle(true);
            }
        });

        this.listenTo(this.context, 'change:collapsed', function(context, collapsed) {
            this.toggle(!collapsed);
        });
    },

    /**
     * Places layout component in the DOM.
     * @override
     * @param {Component} component
     */
    _placeComponent: function(component) {
        this.$(".subpanel").append(component.el);
    },

    /**
     * Renders the `panel-top` component if the subpanel is in a collapsed
     * state, otherwise renders the subpanel.
     */
    _render: function() {
        var collapsed = this.context.get('collapsed');
        if (collapsed) {
            // FIXME: We're assuming that the first component is always the
            // panel-top. This should be fixed when panel-top-create is removed
            // from core in SC-4535.
            this._components[0].render();
        } else {
            /**
             * Internal flag used to determine if we are rendering the
             * component(s) in the panel layout for the first time.
             *
             * @protected
             * @property {boolean}
             */
            this._canToggle = true;
            this._super('_render');
        }

        this.$el.attr({
            'data-subpanel-link': this.options.context.get('link')
        });
        this.$('.subpanel').toggleClass('closed', collapsed);
    },

    /**
     * Saves the collapsed/expanded state of the subpanel in localStorage.
     *
     * @private
     * @param {boolean} [show] `true` to expand, `false` to collapse. Collapses
     *   by default.
     */
    _setCollapsedState: function(show) {
        var state = show ? this.HIDE_SHOW.SHOW : this.HIDE_SHOW.HIDE;
        app.user.lastState.set(this.hideShowLastStateKey, state);
    },

    /**
     * Toggles the panel.
     *
     * @private
     * @param {boolean} [show] `true` to show, `false` to hide, `undefined` to
     *   toggle.
     */
    toggle: function(show) {
        if (this.context.get('isCreateSubpanel')) {
            // no toggle available on create
            return;
        }

        show = _.isUndefined(show) ? this.context.get('collapsed') : show;

        this.$('.subpanel').toggleClass('closed', !show);
        this.context.set('collapsed', !show);
        this._toggleComponents(show);

        // no longer need to skip
        this.context.set('skipFetch', false);
        this.context.loadData();

        this._setCollapsedState(show);
    },

    /**
     * Show or hide component except `panel-top`(subpanel-header) component.
     *
     * @private
     * @param {boolean} [show] `true` to show, `false` to hide. Defaults to
     *   `false`.
     */
    _toggleComponents: function(show) {
        _.each(this._components, function(component) {
            // FIXME: The layout should not be responsible for this. Will be
            // addressed as part of SC-4533.
            if (this._stopComponentToggle(component)) {
                return;
            }
            if (!this._canToggle) {
                component.render();
            } else if (show) {
                component.show();
            } else {
                component.hide();
            }
        }, this);
        this._canToggle = true;
    },

    /**
     * Extensible check to see if this component should be allowed to be toggled.
     * If this returns true: _toggleComponent will return without further render/show/hide checks
     * If this returns false: _toggleComponent will continue through render/show/hide checks
     *
     * @param component
     * @return {boolean}
     * @private
     */
    _stopComponentToggle: function(component) {
        return component.$el.hasClass('subpanel-header');
    }
})
