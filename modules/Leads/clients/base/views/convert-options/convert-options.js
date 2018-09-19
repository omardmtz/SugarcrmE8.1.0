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
    /**
     * @inheritdoc
     *
     * Prevent render if transfer activities action is not move.
     */
    _render: function() {
        var transferActivitiesAction = app.metadata.getConfig().leadConvActivityOpt;
        if (transferActivitiesAction === 'move') {
            this.model.setDefault('transfer_activities', true);
            this._super('_render');
        }
    }
})
