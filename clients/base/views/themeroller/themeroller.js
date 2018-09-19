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
 * @class View.Views.Base.ThemerollerView
 * @alias SUGAR.App.view.views.BaseThemerollerView
 * @extends View.View
 */
({
    events: {
        'click [name=save_button]': 'saveTheme',
        'click [name=refresh_button]': 'loadTheme',
        'click [name=reset_button]': 'resetTheme',
        'blur input': 'previewTheme'
    },
    initialize: function(options) {
        this._super('initialize', [options]);
        this.context.set('skipFetch', true);
        this.customTheme = 'default';
        this.loadTheme();
    },
    parseLessVars: function() {
        if (this.lessVars && this.lessVars.rel && this.lessVars.rel.length > 0) {
            _.each(this.lessVars.rel, function(obj, key) {
                this.lessVars.rel[key].relname = this.lessVars.rel[key].value;
                this.lessVars.rel[key].relname = this.lessVars.rel[key].relname.replace('@', '');
            }, this);
        }
    },
    _renderHtml: function() {
        if (!app.acl.hasAccess('admin', 'Administration')) {
            return;
        }
        this.parseLessVars();
        app.view.View.prototype._renderHtml.call(this);
        _.each(this.$('.hexvar[rel=colorpicker]'), function(obj, key) {
            $(obj).blur(function() {
                $(this).parent().parent().find('.swatch-col').css('backgroundColor', $(this).val());
            });
        }, this);
        this.$('.hexvar[rel=colorpicker]').colorpicker();
        this.$('.rgbavar[rel=colorpicker]').colorpicker({format: 'rgba'});
    },
    loadTheme: function() {
        this.themeApi('read', {}, _.bind(function(data) {
            this.lessVars = data;
            if (this.disposed) {
                return;
            }
            this.render();
            this.previewTheme();
        }, this));
    },
    saveTheme: function() {
        var self = this;
        // get the value from each input
        var colors = this.getInputValues();

        this.showMessage('LBL_SAVE_THEME_PROCESS');
        this.themeApi('create', colors, function() {
            app.alert.dismissAll();
        });
    },
    resetTheme: function() {
        var self = this;
        app.alert.show('reset_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_RESET_THEME_MODAL_INFO'),
            onConfirm: function() {
                self.showMessage('LBL_RESET_THEME_PROCESS');
                self.themeApi('create', {'reset': true}, function(data) {
                    app.alert.dismissAll();
                    self.loadTheme();
                });
            }
        });
    },
    previewTheme: function() {
        var colors = this.getInputValues();
        this.context.set('colors', colors);
    },
    themeApi: function(method, params, successCallback) {
        var self = this;
        _.extend(params, {
            platform: 'portal',
            themeName: self.customTheme
        });
        var paramsGET = (method === 'read') ? params : {};
        var paramsPOST = (method === 'read') ? {} : params;
        var url = app.api.buildURL('theme', '', {}, paramsGET);
        app.api.call(method, url, paramsPOST,
            {
                success: successCallback,
                error: function(error) {
                    if (error.status === 412) {
                        self._handleMetadataSyncError(error, method, url, paramsPOST, successCallback);
                    } else {
                        app.error.handleHttpError(error);
                    }
                }
            },
            { context: self }
        );
    },
    getInputValues: function() {
        var colors = {};
        this.$('input').each(function() {
            var $this = $(this);
            colors[$this.attr('name')] = $this.hasClass('bgvar') ? '"' + $this.val() + '"' : $this.val();
        });
        return colors;
    },
    showMessage: function(messageKey) {
        app.alert.show('themeProcessing', {
            level: 'process',
            title: app.lang.get(messageKey),
            closeable: true,
            autoclose: true
        });
    },

    /**
     * Handles HTTP error 412 Metadata out of sync when saving the portal theme.
     * Syncs the metadata and tries to save the portal theme after the sync.
     *
     * @param {Object} error The error object.
     * @param {string} method The method used in the errored API call.
     * @param {string} url The URL used in the errored API call.
     * @param {Object} paramsPOST The POST params used in the errored API call.
     * @param {Function} successCallback The success callback used in the errored API call.
     * @private
     */
    _handleMetadataSyncError: function(error, method, url, paramsPOST, successCallback) {
        var self = this;
        app.metadata.sync(function() {
            app.api.call(method, url, paramsPOST,
                {
                    success: function() {
                        // The sync resets the colors displayed. We need to load
                        // the colors we just saved to display the correct
                        // colors.
                        self.loadTheme();
                        successCallback();
                    },
                    error: function(error) {
                        app.error.handleHttpError(error);
                    }
                },
                { context: self }
            );
        });
    }
})
