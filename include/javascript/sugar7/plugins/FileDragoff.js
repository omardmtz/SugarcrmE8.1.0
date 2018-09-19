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
(function (app) {
    app.events.on("app:init", function () {
        app.plugins.register('FileDragoff', ['view'], {
            events: {
                'dragstart .dragoff': 'saveAttachment'
            },

            /**
             * Handles dragging an attachment off the page.
             * @param  {Event} event
             */
            saveAttachment: function(event) {
                // The following is only true for Chrome.
                if (event.dataTransfer && event.dataTransfer.constructor == Clipboard &&
                    event.dataTransfer.setData('DownloadURL', 'http://www.sugarcrm.com')) {
                    var el = $(event.currentTarget), mime, name, file;

                    while (el !== this.$el && !el.data("url")) {
                        el = el.parent();
                    }

                    mime = el.data("mime");
                    name = el.data("filename");
                    file = el.data("url");

                    event.dataTransfer.setData("DownloadURL", mime + ":" + name + ":" + file);
                }
            }
        });
    });
})(SUGAR.App);
