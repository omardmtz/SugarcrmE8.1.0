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
    extendsFrom: 'HeaderpaneView',

    events: {
        'click [name=select_button]':   '_select',
        'click [name=cancel_button]': '_cancel'
    },

    /**
     * Close the drawer and pass in the selected model
     *
     * @private
     */
    _select: function() {
        var selectedModel = this.context.get('selection_model');

        if (selectedModel) {
            app.drawer.close(selectedModel);
        } else {
            this._cancel();
        }
    },

    /**
     * Close the drawer
     *
     * @private
     */
    _cancel: function() {
        app.drawer.close();
    }
})
