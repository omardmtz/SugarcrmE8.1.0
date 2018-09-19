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
 * @class View.Views.Base.ConfigHeaderButtonsView
 * @alias SUGAR.App.view.views.BaseConfigHeaderButtonsView
 * @extends  View.View
 */
({
    events: {
        'click a[name="cancel_button"]': 'cancelConfig',
        'click a[name="save_button"]:not(.disabled)': 'saveConfig'
    },

    /**
     * Holds an object with the current module in it for parsing language strings
     *
     * <pre><code>
     *  { module: this.module }
     * </pre></code>
     */
    moduleLangObj: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.moduleLangObj = {
            // get the actual plural module name
            module: app.lang.getModuleName(this.module, { plural: true })
        };

        this.before('save', this._beforeSaveConfig, this);
        this.before('cancel', this._beforeCancelConfig, this);
    },

    /**
     * Click handler for the save button, triggers save event
     */
    saveConfig: function() {
        if (this.triggerBefore('save')) {
            this.getField('save_button').setDisabled(true);
            this._saveConfig();
        }
    },

    /**
     * Calls the context model save and saves the config model in case
     * the default model save needs to be overwritten
     *
     * @protected
     */
    _saveConfig: function() {
        var url = app.api.buildURL(this.module, 'config');
        app.api.call('create', url, this.model.attributes, {
                success: _.bind(function() {
                    this.showSavedConfirmation();
                    if (app.drawer.count()) {
                        // close the drawer and return to Opportunities
                        app.drawer.close(this.context, this.context.get('model'));
                        // Config changed... reload metadata
                        app.sync();
                    } else {
                        app.router.navigate(this.module, {trigger: true});
                    }
                }, this),
                error: _.bind(function() {
                    this.getField('save_button').setDisabled(false);
                }, this)
            }
        );
    },

    /**
     * Noop for use if model needs updating before save.
     * Gets called before the model actually saves.
     *
     * Override this method to provide custom logic.
     *
     * @private
     * @template
     * @return {boolean} The default implementation returns `true` allowing the save.
     */
    _beforeSaveConfig: function() {
        return true;
    },

    /**
     * Show the saved confirmation alert
     *
     * @param {Object|Undefined} [onClose] the function fired upon closing.
     */
    showSavedConfirmation: function(onClose) {
        onClose = onClose || function() {};
        var alert = app.alert.show('module_config_success', {
            level: 'success',
            title: app.lang.get('LBL_CONFIG_TITLE_MODULE_SETTINGS', this.module, this.moduleLangObj) + ':',
            messages: app.lang.get('LBL_CONFIG_MODULE_SETTINGS_SAVED', this.module, this.moduleLangObj),
            autoClose: true,
            autoCloseDelay: 10000,
            onAutoClose: _.bind(function() {
                alert.getCloseSelector().off();
                onClose();
            })
        });
        var $close = alert.getCloseSelector();
        $close.on('click', onClose);
        app.accessibility.run($close, 'click');
    },

    /**
     * Cancels the config setup process and redirects back
     */
    cancelConfig: function() {
        if (this.triggerBefore('cancel')) {
            // If we're inside a drawer
            if (app.drawer.count()) {
                // close the drawer
                app.drawer.close(this.context, this.context.get('model'));
            } else {
                app.router.navigate(this.module, {trigger: true});
            }
        }
    },

    /**
     * Noop for use if model needs updating before cancel
     * Gets called before the model actually cancels
     *
     * Override this method to provide custom logic.
     *
     * @private
     * @template
     * @return {boolean} The default implementation returns `true` allowing the cancel.
     */
    _beforeCancelConfig: function() {
        return true;
    }
})
