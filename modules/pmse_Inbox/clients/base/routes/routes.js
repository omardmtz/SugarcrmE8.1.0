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
    app.events.on('router:init', function(router) {
        var module = 'pmse_Inbox';
        var routes = [
            {
                name: 'show-case_layout_record_action',
                route: module + '/:id/layout/:layout/:record(/:action)',
                callback: function(id, layout, record, action) {
                    if (!this._moduleExists(module)) {
                        return;
                    }

                    var opts = {
                        module: module,
                        modelId: id,
                        layout: layout || 'record',
                        action: record,
                        record: action || 'detail'
                    };

                    app.controller.loadView(opts);
                }
            }
        ];

        app.router.addRoutes(routes);
    });
})(SUGAR.App);
