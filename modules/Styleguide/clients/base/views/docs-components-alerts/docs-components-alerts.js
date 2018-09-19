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
({
    // components dropdowns
    _renderHtml: function () {
        this._super('_renderHtml');

        this.$('[data-alert]').on('click', function() {

            var $button = $(this),
                level = $button.data('alert'),
                state = $button.text(),
                auto_close = ['info','success'].indexOf(level) > -1;

            app.alert.dismiss('core_meltdown_' + level);

            if (state !== 'Example') {
                $button.text('Example');
            } else {
                app.alert.show('core_meltdown_' + level, {
                    level: level,
                    messages: 'The core is in meltdown!!',
                    autoClose: auto_close,
                    onClose: function () {
                        $button.text('Example');
                    }
                });
                $button.text('Dismiss');
            }
        });
    },

    _dispose: function() {
        this.$('[data-alert]').off('click');
        app.alert.dismissAll();

        this._super('_dispose');
    }
})
