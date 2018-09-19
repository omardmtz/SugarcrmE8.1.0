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
    _render: function() {
        this._super('_render');
        // button state demo
        this.$('#fat-btn').click(function () {
            var btn = $(this);
            btn.button('loading');
            setTimeout(function () {
              btn.button('reset');
            }, 3000);
        })
    }
})
