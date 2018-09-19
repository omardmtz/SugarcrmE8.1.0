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
    extendsFrom: 'BaseKBContentsUsefulnessField',

    initialize: function(options) {
        this._super('initialize', [options]);

        //RS-1445 - Need to wait for model to be loaded to get value of usefulness_user_vote
        this.model.on('data:sync:complete', function() {
            if (!this.disposed) {
                this.render();
            }
        }, this);
    }
})
