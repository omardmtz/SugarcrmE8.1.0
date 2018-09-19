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
 * The base layout for the help component.
 *
 * @class View.Layouts.Base.HelpLayout
 * @alias SUGAR.App.view.layouts.BaseHelpLayout
 * @extends View.Layout
 */
({
    events: {
        'click [data-action=close]': 'close'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * The internal state of this layout.
         * By default this layout is closed ({@link #toggle} will call render).
         *
         * FIXME TY-1798/TY-1800 This is needed due to the bad popover plugin.
         *
         * @type {boolean}
         * @private
         */
        this._isOpen = false;

        /**
         * This is the Help button in the footer.
         * Needed to render the modal by calling `popover` on the button.
         *
         * @type {jQuery}
         */
        this.button = options.button;

        /**
         * `True` if the helpObject has been created for the current view,
         * `false` otherwise.
         *
         * @type {boolean}
         * @private
         */
        this._helpObjectCreated = false;

        /**
         * Each view requires its own help object.
         * On view change, the helpObject needs to be recreated.
         */
        app.events.on('app:view:change', _.bind(function() {
            this._helpObjectCreated = false;
            if (this.button) {
                this.button.popover('destroy');
            }
        }, this));
    },

    /**
     * Initializes the popover plugin for the button given.
     *
     * @param {jQuery} button The jQuery button.
     * @private
     */
    _initPopover: function(button) {
        button.popover({
            title: this._getTitle('LBL_HELP_' + app.controller.context.get('layout').toUpperCase() + '_TITLE'),
            content: _.bind(function() {
                return this.$el;
            }, this),
            html: true,
            placement: 'top',
            trigger: 'manual',
            template: '<div class="popover footer-modal feedback helpmodal" data-modal="help">' +
                '<div class="arrow"></div><h3 class="popover-title"></h3>' +
                '<div class="popover-content"></div></div>'
        });

        // Reposition the modal so all of its contents are within the window.
        button.on('shown.bs.popover', _.bind(this._positionPopover, this));
    },

    /**
     * Fetches the title of the help modal.
     * If none exists, returns a default help title.
     *
     * @param {string} titleKey The modal title label.
     * @return {string} The converted title.
     * @private
     */
    _getTitle: function(titleKey) {
        var title = app.lang.get(titleKey, app.controller.context.get('module'), app.controller.context);
        return title === titleKey ? app.lang.get('LBL_HELP') : title;
    },

    /**
     * Toggles the help on the side pane.
     *
     * @param {boolean} show `true` to show the help, `false` to hide it.
     * @param {View.Component} [comp] Component that triggered
     * \'app:help:toggle\' event.
     * @deprecated Since 7.9. Will be removed in 7.11.
     *   Please use the `toggle` method instead.
     */
    toggleHelp: function(show, comp) {
        app.logger.warn('The function `View.Layouts.Base.HelpLayout.toggleHelp` is deprecated in 7.9.0.0' +
            ' and will be removed in 7.11.0.0. Please use `View.Layouts.Base.HelpLayout.toggle` instead.');

        if (!app.drawer.isActive(this.$el)) {
            return;
        }
        if (show || this.module === 'Home') {
            var defaultLayout = this.closestComponent('sidebar');
            if (defaultLayout) {
                defaultLayout.toggleSidePane(show);
            }
        }

        this._toggleHelpPane(show);

        var event = show ? 'app:help:shown' : 'app:help:hidden';
        app.events.trigger(event);
    },

    /**
     * Shows the help pane and hides the other panes in the RHS.
     *
     * FIXME: SC-4915 will remove this method.
     *
     * @param {boolean} show `true` to show the help pane, `false` to hide it.
     * @private
     */
    _toggleHelpPane: function(show) {
        var toggle = show ? 'hide' : 'show';
        var $layout = this.$el.closest('[data-component=sidebar]');
        $layout.find('[data-component=dashboard-pane]')[toggle]();
        $layout.find('[data-component=side-pane]').toggleClass('active', !show);
        $layout.find('[data-component=preview-pane]').removeClass('active');
    },

    /**
     * Toggle this view (by re-rendering).
     *
     * @param {boolean} [show] `true` to show, `false` to hide, `undefined`
     *   to toggle the current state.
     */
    toggle: function(show) {
        if (!this.button) {
            return;
        }

        if (_.isUndefined(show)) {
            this._isOpen = !this._isOpen;
        } else {
            this._isOpen = show;
        }

        this.button.popover('destroy');

        if (this._isOpen) {
            this._initHelpObject();
            this.render();
            this._initPopover(this.button);
            this.button.popover('show');
            this.bindOutsideClick();
        } else {
            this.unbindOutsideClick();
        }

        this.trigger(this._isOpen ? 'show' : 'hide', this, this._isOpen);
    },

    /**
     * Creates the helpObject if it has not yet been created for this.
     *
     * @private
     */
    _initHelpObject: function() {
        if (!this._helpObjectCreated) {
            this.getComponent('base').getComponent('helplet').createHelpObject();
            this._helpObjectCreated = true;
        }
    },

    /**
     * Sets the horizontal position of the modal.
     *
     * @private
     */
    _positionPopover: function() {
        var $popoverContainer = this.button.data()['bs.popover'].tip();
        var left;
        if (app.lang.direction === 'rtl') {
            // Leave 16px of space between lhs edge of popover and the screen.
            left = 16;
        } else {
            // Leave 16px of space between rhs edge of popover and the screen.
            left = $(window).width() - $popoverContainer.width() - 16;
        }
        $popoverContainer.css('left', left);
    },

    /**
     * Closes the Help modal if event target is outside of the Help modal.
     *
     * param {Object} evt jQuery event.
     */
    closeOnOutsideClick: function(evt) {
        if ($(evt.target).closest('[data-modal=help]').length === 0) {
            this.toggle(false);
        }
    },

    /**
     * Binds the outside `click` event.
     */
    bindOutsideClick: function() {
        $('body').bind('click.' + this.cid, _.bind(this.closeOnOutsideClick, this));
    },

    /**
     * Unbinds the outside `click` event.
     */
    unbindOutsideClick: function() {
        $('body').unbind('click.' + this.cid);
    },

    /**
     * Triggered when the close button on the help-header view is pressed.
     */
    close: function() {
        this.toggle(false);
    },

    /**
     * @inheritdoc
     *
     * During dispose destroy the popover.
     */
    _dispose: function() {
        this.unbindOutsideClick();
        if (this.button) {
            this.button.popover('destroy');
        }
        this._super('_dispose');
    }
})
