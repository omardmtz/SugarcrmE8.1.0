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
//    extendsFrom :'RecordView',
//    className: 'settings',

    events: {
        //'click .sugar-cube': 'spinCube'
    },
    initialize: function(options) {
        if (app.acl.hasAccessToAny('developer')) {
            var self=this;
            var url = app.api.buildURL('pmse_Inbox', 'settings', null, options.params);
            app.api.call('READ', url, options.attributes, {
                success: function (data) {
                    self.model.set(data);
                }
            });
            this._super('initialize', [options]);
        } else {
            app.controller.loadView({
                layout: 'access-denied'
            });
        }
    }
})
