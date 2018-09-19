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
(function(app) {
    app.events.on('app:init', function() {
        /**
         * This plugin allows Phaser.io to be used inside a view as a way to render data on a Canvas element
         */
        app.plugins.register('CanvasDataRenderer', ['view'], {
            /**
             * The path of the script this Plugin loads
             */
            _scriptPath: 'include/javascript/phaser/phaser-sugar.min.js',

            /**
             * Is PhaserIO script loaded and ready to be embedded
             */
            _scriptReady: false,

            /**
             * Has PhaserIO script already been embedded yet
             */
            _scriptEmbedded: false,

            /**
             * Is the page ready for loading (during render)
             */
            _pageReady: false,

            /**
             * The JS Phaser.IO Script file
             */
            _scriptData: undefined,

            /**
             * Attach code for when the plugin is registered on a view or layout
             *
             * @param component
             * @param plugin
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    // check if Phaser is already loaded
                    if (window.Phaser) {
                        this._scriptEmbedded = true;
                        this._scriptReady = true;
                        // if another view has already loaded Phaser,
                        // trigger back to this context that phaser is good to go
                        this.context.trigger('phaserio:ready');
                        return;
                    }

                    // otherwise get the Phaser Lib
                    $.ajax({
                        type: 'GET',
                        url: this._scriptPath,
                        dataType: 'script',
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log('[CanvasDataRenderer] Error loading file: ', errorThrown);
                        },
                        success: _.bind(function(data) {
                            this._scriptReady = true;
                            this._scriptData = data;
                            if (this._pageReady && !this._scriptEmbedded) {
                                // if script has been loaded and not embedded, embed Phaser Lib
                                this.embedScript();
                            }
                        }, this)
                    });
                }, null, component);

                this.on('render', function() {
                    this._pageReady = true;
                    if (this._scriptReady && !this._scriptEmbedded) {
                        // if script has been loaded and not embedded, embed Phaser Lib
                        this.embedScript();
                    }
                }, null, component);
            },

            /**
             * Embeds the script into the dashlet or view
             */
            embedScript: function() {
                this._scriptEmbedded = true;

                if (!this.disposed) {
                    // if Phaser has not already been added by another view using this same plugin
                    if (!window.Phaser) {
                        this.$el.prepend('<script type="text/javascript">' + this._scriptData + '</script>');
                    }

                    // trigger back to this context that phaser is good to go
                    this.context.trigger('phaserio:ready');
                }
            }
        });
    });
})(SUGAR.App);
