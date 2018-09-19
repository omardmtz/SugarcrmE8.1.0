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
 * Configure keyboard shortcuts.
 *
 * @class View.Views.Base.ShortcutsConfigView
 * @alias SUGAR.App.view.views.BaseShortcutsConfigView
 * @extends View.View
 */
({
    /**
     * Handle button actions from the headerpane.
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.context.on('button:cancel_button:click', function() {
            app.drawer.close();
        }, this);
        this.context.on('button:save_button:click', this.saveShortcuts, this);
        this.context.on('button:restore_button:click', this.removeCustomizations, this);
    },

    /**
     * Render global and contextual shortcut keys.
     * @inheritdoc
     */
    _renderHtml: function(ctx, options) {
        var shortcutSession = this.context.get('shortcutSession');

        if (shortcutSession) {
            this.shortcuts = shortcutSession.getRegisteredShortcuts();
        }

        this.global = app.shortcuts.getRegisteredGlobalShortcuts();

        this._super('_renderHtml', [ctx, options]);
    },

    /**
     * Save custom shortcut keys to user preferences.
     */
    saveShortcuts: function() {
        var self = this,
            shortcutsToSave = [],
            findShortcut = function(shortcuts, id) {
                return _.find(shortcuts, function(shortcut) {
                    return (shortcut.id === id);
                });
            };

        this.$('[data-id]').each(function() {
            var $row = $(this),
                id = $row.data('id'),
                keys = $row.find('input').val().trim().split(','),
                shortcut = findShortcut(self.shortcuts, id);

            _.each(keys, function (key, index){
                keys[index] = $.trim(key);
            });

            if (shortcut && !_.isEqual(shortcut.keys, keys)) {
                shortcutsToSave.push({
                    id: id,
                    keys: keys
                });
            }
        });

        app.shortcuts.saveCustomShortcutKey(shortcutsToSave, this._savePreferencesCallback);
    },

    /**
     * Clear custom shortcut keys in user preferences.
     */
    removeCustomizations: function() {
        var customShortcutsToDelete = [];

        this.$('[data-id]').each(function() {
            customShortcutsToDelete.push($(this).data('id'));
        });

        app.shortcuts.removeCustomShortcutKeys(customShortcutsToDelete, this._savePreferencesCallback);
    },

    /**
     * Close drawer once custom shortcut keys are saved.
     * @param {Object} error Error data from the server.
     * @private
     */
    _savePreferencesCallback: function(error) {
        var refreshHelp;

        if (error) {
            app.alert.show('preference-save-error', {
                level: 'error',
                messages: 'LBL_SHORTCUT_CONFIG_ERROR'
            });
        } else {
            refreshHelp = true;
        }

        app.drawer.close(refreshHelp);
    }
})
