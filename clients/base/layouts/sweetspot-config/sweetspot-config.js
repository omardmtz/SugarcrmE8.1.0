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
 * @class View.Layouts.Base.SweetspotConfigLayout
 * @alias SUGAR.App.view.layouts.BaseSweetspotConfigLayout
 * @extends View.Layout
 */
({
    plugins: ['ShortcutSession'],

    shortcuts: [
        'SweetSpot:Config:Save',
        'SweetSpot:Config:Cancel'
    ],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._bindEvents();
    },

    /**
     * @override
     */
    _placeComponent: function(component) {
        this.$('.main-pane').append(component.el);
    },

    /**
     * Binds the events that this layout uses.
     *
     * @protected
     */
    _bindEvents: function() {
        this.context.on('sweetspot:cancel:config', this.cancelConfig, this);

        // Button events
        this.context.on('button:save_button:click', this.saveConfig, this);
        this.context.on('button:cancel_button:click', this.cancelConfig, this);
    },


    /**
     * This method prepares the attributes payload for the call to
     * {@link Core.User#updatePreferences}.
     *
     * @protected
     * @param {Object} data The unprepared configuration data.
     * @return {Object} The prepared configuration data.
     */
    _formatForUserPrefs: function(data) {
        return {sweetspot: data};
    },

    /**
     * Receives all the configuration models from the subcomponents, to be
     * saved in user preferences.
     *
     * @protected
     */
    _getAllConfigs: function() {
        var config = {};
        this.context.off('sweetspot:receive:configs');
        this.context.on('sweetspot:receive:configs', function(data) {
            _.extend(config, data);
        });
        this.context.trigger('sweetspot:ask:configs');
        return config;
    },

    /**
     * Saves the sanitized Sweet Spot settings in user preferences and closes
     * the drawer.
     */
    saveConfig: function() {
        var data = this._getAllConfigs();
        data = this._formatForUserPrefs(data);

        this.context.trigger('sweetspot:config:enableButtons', false);
        app.alert.show('sweetspot', {
            level: 'process',
            title: app.lang.get('LBL_SAVING'),
            autoClose: false
        });

        app.user.updatePreferences(data, _.bind(this._saveConfigCallback, this));
    },

    /**
     * Callback for the call to {@link Core.User#updatePreferences}.
     *
     * @param {string} err Error message returned by the server.
     */
    _saveConfigCallback: function(err) {
        app.alert.dismiss('sweetspot');
        if (err) {
            var errorMsg = app.lang.get('LBL_SWEETSPOT_CONFIG_ERR', this.module, {errorMsg: err});
            this.context.trigger('sweetspot:config:enableButtons', true);
            app.alert.show('config-failed', {
                level: 'error',
                title: 'LBL_SWEETSPOT',
                messages: errorMsg
            });
            return;
        }
        app.drawer.close(this.collection);
        app.events.trigger('sweetspot:reset');
    },

    /**
     * Closes the config drawer without saving changes.
     */
    cancelConfig: function() {
        app.drawer.close();
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.context.off('sweetspot:receive:configs');
        this._super('_dispose');
    }
})
