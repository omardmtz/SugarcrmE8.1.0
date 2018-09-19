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
    extendsFrom: 'RecordlistView',
    contextEvents: {
        "list:reassign:fire": "reassignCase"
    },

    _render: function() {
        if (app.acl.hasAccessToAny('developer')) {
            this._super('_render');
        } else {
            app.controller.loadView({
                layout: 'access-denied'
            });
        }
    },
    reassignCase: function (model) {
        var self=this;
        app.drawer.open({
            layout: 'reassignCases',
            context: {
                module: 'pmse_Inbox',
                parent: this.context,
                cas_id: model.get('cas_id'),
                unattended: true
            }

        },
            function(variables) {
                if(variables==='saving'){
                    self.reloadList();
                }
            });
    },
    reloadList: function() {
        this.context.reloadData({
            recursive:false,
        });
    }
})
