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
    extendsFrom: 'RecordView',

    delegateButtonEvents: function() {
        this.context.on('button:export_button:click', this.exportListMembers, this);
        this._super("delegateButtonEvents");
    },

    /**
     * Event to trigger the Export page level action
     */
    exportListMembers: function() {
        app.alert.show('export_loading', {level: 'process', title: app.lang.get('LBL_LOADING')});
        app.api.exportRecords(
            {
                module: this.module,
                uid: [this.model.id],
                members: true
            },
            this.$el,
            {
                complete: function() {
                    app.alert.dismiss('export_loading');
                }
            }
        );
    }
})
