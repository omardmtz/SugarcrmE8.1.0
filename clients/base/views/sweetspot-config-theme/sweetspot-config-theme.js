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
 * @class View.Views.Base.SweetspotConfigThemeView
 * @alias SUGAR.App.view.views.BaseSweetspotConfigThemeView
 * @extends View.View
 */
({
    className: 'columns',

    // FIXME: Change this to 'UnsavedChanges' when SC-4167 gets merged.
    plugins: ['Editable'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta = this._getMeta(options);
        this._super('initialize', [options]);
        this._bindEvents();
    },

    /**
     * Merges the base metadata with custom view metadata.
     *
     * @protected
     * @param {Object} options The options hash containing the custom
     *   metadata.
     * @return {Object} The metadata this view should use.
     */
    _getMeta: function(options) {
        return  _.extend({},
            app.metadata.getView(null, 'sweetspot-config-theme'),
            app.metadata.getView(this.module, 'sweetspot-config-theme'),
            options.meta
        );
    },

    /**
     * Binds the events that this layout uses.
     *
     * @protected
     */
    _bindEvents: function() {
        this.context.on('sweetspot:ask:configs', this.generateConfig, this);
    },


    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        this._super('_renderHtml');
        this._initTheme();
    },

    /**
     * Initializer function that ensures the correct theme is checked when the
     * view is rendered.
     *
     * @protected
     */
    _initTheme: function() {
        var prefs = app.user.getPreference('sweetspot');
        var theme = prefs && prefs.theme;

        this.model.set('theme', theme);
    },

    /**
     * Generates an object that the
     * {@link View.Layouts.Base.SweetspotConfigLayout config layout} uses to
     * save configurations to the user preferences.
     *
     * @return {undefined} Returns `undefined` if the default theme is selected.
     */
    generateConfig: function() {
        var theme = this._getSelectedTheme();

        // The default configuration should not be defined in user prefs.
        if (!theme) {
            return;
        }
        var data = this._formatForUserPrefs(theme);
        this.context.trigger('sweetspot:receive:configs', data);
    },

    /**
     * This method prepares the attributes payload for the call to
     * {@link Core.User#updatePreferences}.
     *
     * @protected
     * @param {string} theme The configured theme name.
     * @return {Object} The prepared configuration data.
     */
    _formatForUserPrefs: function(theme) {
        return {theme: theme};
    },

    /**
     * Returns the currently selected theme from this view.
     *
     * @protected
     * @return {string|undefined} The currently selected theme. Returns
     *   `undefined` if the default theme is selected.
     */
    _getSelectedTheme: function() {
        var theme = this.model.get('theme');

        // The default configuration should be empty in user prefs.
        if (theme === 'default') {
            return;
        }

        return theme;
    },

    /**
     * Compare with the user preferences and return true if the checkbox
     * contains changes.
     *
     * This method is called by {@link app.plugins.Editable}.
     *
     * @return {boolean} `true` if current collection contains unsaved changes,
     *   `false` otherwise.
     */
    hasUnsavedChanges: function() {
        var prefs = app.user.getPreference('sweetspot');
        var oldConfig = prefs && prefs.theme;
        var newConfig = this._getSelectedTheme();
        var isChanged = !_.isEqual(oldConfig, newConfig);

        return isChanged;
    }
})
