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
 * @class View.Views.Base.FooterActionsView
 * @alias SUGAR.App.view.views.BaseFooterActionsView
 * @extends View.View
 */
({
    events: {
        'click [data-action=shortcuts]': 'shortcuts',
        'click [data-action=feedback]': 'feedback',
        'click [data-action=help]': 'help'
    },
    tagName: 'span',
    layoutName: '',

    /**
     * Flag to indicate if the footer is currently watching for the help status.
     *
     * @property {boolean}
     */
    watchingForHelp: false,

    /**
     * Array of layout names where the help button should be disabled
     */
    helpBtnDisabledLayouts: [
        'about',
        'first-login-wizard'
    ],

    /**
     * Enable or disable buttons on the footer depending on view.
     * @param {string|Object} layout The type of the layout we are changing to.
     * @param {Object} params Additional parameters.
     */
    handleViewChange: function(layout, params) {
        this.module = params && params.module ? params.module : app.controller.context.get('module');
        // should we disable the help button or not, this only happens when layout is 'bwc'
        this.layoutName = _.isObject(layout) ? layout.name : layout;
        this.toggleHelpButton(this._shouldHelpBeActive(params.drawer));
        this.disableHelpButton(true);
    },

    handleRouteChange: function(route, params) {
        this.routeParams = {'route': route, 'params': params};
    },

    /**
     * Enable the (now non-existent) tour button.
     *
     * @deprecated 7.9 Will be removed in 7.11.
     *   Please use `HelpletView.toggleTourLink` instead.
     */
    enableTourButton: function() {
        app.logger.warn('The function `View.Views.Base.FooterActionsView.enableTourButton`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0. ' +
            'Please use `View.Views.Base.HelpletView.toggleTourLink` instead.');
        this.$('[data-action=tour]').removeClass('disabled');
        this.events['click [data-action=tour]'] = 'showTutorialClick';
        this.undelegateEvents();
        this.delegateEvents();
    },

    /**
     * Disable the (now non-existent) tour button.
     *
     * @deprecated 7.9 Will be removed in 7.11.
     *   Please use `HelpletView.toggleTourLink` instead.
     */
    disableTourButton: function() {
        app.logger.warn('The function `View.Views.Base.FooterActionsView.disableTourButton`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0. ' +
            'Please use `View.Views.Base.HelpletView.toggleTourLink` instead.');
        this.$('[data-action=tour]').addClass('disabled');
        delete this.events['click [data-action=tour]'];
        this.undelegateEvents();
        this.delegateEvents();
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        app.events.on('app:view:change', this.handleViewChange, this);
        var self = this;
        app.utils.doWhen(function() {
            return !_.isUndefined(app.router);
        }, function() {
            self.listenTo(app.router, 'route', self.handleRouteChange);
        });

        app.events.on('app:help', function() {
            this.help();
        }, this);

        app.events.on('app:help:shown', function() {
            this.toggleHelpButton(true);
            this.disableHelpButton(false);
        }, this);

        app.events.on('app:help:hidden', function() {
            this.toggleHelpButton(false);
            this.disableHelpButton(true);
        }, this);

        app.events.on('alert:cancel:clicked', function() {
            // re-check if help should be disabled or not and set accordingly
            this.disableHelpButton(this.shouldHelpBeDisabled());
        }, this);

        // Create a doWhen to update the help button in the footer.
        this._watchForHelpActive();

        app.shortcuts.registerGlobal({
            id: 'Shortcut:Help',
            keys: '?',
            component: this,
            description: 'LBL_SHORTCUT_HELP',
            handler: this.shortcuts
        });

        app.user.lastState.preserve(app.user.lastState.key('toggle-show-tutorial', this));

        this.before('render', function() {
            if (this._feedbackView) {
                this._feedbackView.dispose();
            }

            if (this._helpLayout) {
                this._helpLayout.dispose();
            }
        }, this);
    },

    /**
     * Watch the app state for when the help button needs to be active.
     * @private
     */
    _watchForHelpActive: function() {
        if (this.watchingForHelp) {
            return;
        }
        this.watchingForHelp = true;

        app.utils.doWhen(this.helpActiveCheck, _.bind(function() {
            // If the layout check passes, enable the help button
            this.watchingForHelp = false;
            this.disableHelpButton(false);
        }, this));
    },

    /**
     * Check to see if the help button should be active based on the current layout.
     *
     * @return {boolean} Returns true if the app state should always highlight
     *  the help button.
     */
    helpActiveCheck: function() {
        if (app.drawer && !app.drawer.isActive(this.$el)) {
            return false;
        }

        return true;
    },

    /**
     * Checks any criteria to see if help button should be disabled
     * @return {boolean}
     */
    shouldHelpBeDisabled: function() {
        return (_.indexOf(this.helpBtnDisabledLayouts, this.layoutName) !== -1);
    },

    /**
     * Checks if the help button should be set active.
     *
     * @param {boolean} drawer `true` should be passed when this method is called when
     * a drawer is closing or opening.
     * @return {boolean}
     * @private
     */
    _shouldHelpBeActive: function(drawer) {
        return drawer ? this.helpButton && this.helpButton.hasClass('active') : false;
    },

    _renderHtml: function() {
        this.isAuthenticated = app.api.isAuthenticated();
        this.isShortcutsEnabled = (this.isAuthenticated && app.shortcuts.isEnabled());
        this._super('_renderHtml');
        this.helpButton = this.$('[data-action=help]');
    },

    /**
     * Toggles feedback popup on click (open or close).
     * TODO move this to a feedback field
     *
     * This currently sets and uses the internal `_feedbackIsOpen` flag to
     * create and dispose the {@link FeedbackView}.
     * FIXME this shouldn't work that way and should trigger an event that the
     * additionalComponent (the feedback layout) is listening to and the toggle
     * will simply trigger the event for the layout to show and hide.
     * This will improve performance (no more layout being disposed and created
     * on click).
     *
     * If the app isn't yet in sync (all metadata loaded to create the view)
     * the button doesn't do anything.
     *
     * @param {Event} evt the `click` event.
     */
    feedback: function(evt) {
        if (!app.isSynced) {
            return;
        }

        if (!this._feedbackView || this._feedbackView.disposed) {
            this._feedbackView = app.view.createView({
                module: 'Feedbacks',
                type: 'feedback',
                button: this.$('[data-action="feedback"]')
            });

            this.listenTo(this._feedbackView, 'show hide', function(view, active) {
                this.$('[data-action="feedback"]').toggleClass('active', active);
            });
        }
        this._feedbackView.toggle();
    },

    /**
     * Open the SugarCRM support website in another tab.
     *
     * @deprecated 7.9. Will be removed in 7.11.
     */
    support: function() {
        app.logger.warn('The function `View.Views.Base.FooterActionsView.support`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0.');
        window.open('http://support.sugarcrm.com', '_blank');
    },

    /**
     * Help button click event listener.
     */
    help: function() {
        if (!app.isSynced) {
            return;
        }

        if (this.helpButton.hasClass('disabled')) {
            return;
        }

        // For bwc modules and the About page, handle the help click differently.
        if (this.layoutName === 'bwc' || this.layoutName === 'about') {
            this.bwcHelpClicked();
            return;
        }

        if (!this._helpLayout || this._helpLayout.disposed) {
            this._createHelpLayout();
        }

        this._helpLayout.toggle();
    },

    /**
     * Creates the help layout.
     *
     * @param {jQuery} button The Help button.
     * @private
     */
    _createHelpLayout: function() {
        this._helpLayout = app.view.createLayout({
            module: app.controller.context.get('module'),
            type: 'help',
            button: this.helpButton
        });

        this._helpLayout.initComponents();

        this.listenTo(this._helpLayout, 'show hide', function(view, active) {
            this.helpButton.toggleClass('active', active);
        });
    },

    /**
     * Disable the help button
     *
     * @param {boolean} [disable=true]      Should we disable it or enable it, if not passed will default to true
     */
    disableHelpButton: function(disable) {
        disable = _.isUndefined(disable) ? true : disable;
        if (this.helpButton) {
            this.helpButton.toggleClass('disabled', disable);
        }

        if (disable) {
            this._watchForHelpActive();
        }

        return disable;
    },

    /**
     * Utility Method to toggle the help button on and off.
     *
     * @param {Boolean} active      Set or remove the active state of the button
     * @param {Object} (button)     Button Object (optional), will be found if not passed in
     */
    toggleHelpButton: function(active, button) {
        if (_.isUndefined(button)) {
            button = this.helpButton;
        }

        if (button) {
            button
                .toggleClass('active', active)
                .attr('aria-pressed', active);
        }
    },

    /**
     * Open shortcut help.
     * @param event
     */
    shortcuts: function(event) {
        var activeDrawerLayout = app.drawer.getActive(),
            $shortcutButton = this.$('[data-action=shortcuts]');

        if (!activeDrawerLayout || activeDrawerLayout.type !== 'shortcuts') {
            $shortcutButton.addClass('active');
            app.drawer.open({
                layout: 'shortcuts'
            }, function() {
                $shortcutButton.removeClass('active');
            });
        } else {
            app.drawer.close();
        }
    },

    /**
     * Click event for the (now non-existent) show tour icon.
     *
     * @param {Object} e click event.
     * @deprecated 7.9. Will be removed in 7.11.
     *   Please use `HelpletView.showTour` instead.
     */
    showTutorialClick: function(e) {
        app.logger.warn('The function `View.Views.Base.FooterActionsView.showTutorialClick`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0.');
        if (!app.tutorial.instance) {
            this.showTutorial();
            e.currentTarget.blur();
        }
    },

    /**
     * Show tour overlay.
     *
     * @param {Object} prefs preferences to preserve.
     * @deprecated 7.9. Will be removed in 7.11.
     */
    showTutorial: function(prefs) {
        app.logger.warn('The function `View.Views.Base.FooterActionsView.showTutorial`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0. '  +
            'Please use `View.Views.Base.HelpletView.showTour` instead.');
        app.tutorial.resetPrefs(prefs);
        app.tutorial.show(app.controller.context.get('layout'), {module: app.controller.context.get('module')});
    },

    /**
     * Calls the old Help Docs if in BWC mode
     */
    bwcHelpClicked: function() {
        var serverInfo = app.metadata.getServerInfo(),
            lang = app.lang.getLanguage(),
            module = app.controller.context.get('module'),
            route = this.routeParams.route,
            url = 'http://www.sugarcrm.com/crm/product_doc.php?edition=' + serverInfo.flavor +
                '&version=' + serverInfo.version + '&lang=' + lang + '&module=' + module + '&route=' + route;
        if (route == 'bwc') {
            var action = window.location.hash.match(/#bwc.*action=(\w*)/i);
            if (action && !_.isUndefined(action[1])) {
                url += '&action=' + action[1];
            }
        }
        app.logger.info("help URL: " + url);
        window.open(url);
    }
})
