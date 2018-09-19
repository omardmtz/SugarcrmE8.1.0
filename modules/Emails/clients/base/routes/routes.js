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
    var module = 'Emails';

    /**
     * Open the email compose view in either a drawer or full-page.
     *
     * The view will be opened in a drawer if the user is routing from a page
     * in the application. The view will be opened in full-page if the user is
     * routing from login or a location outside the application.
     *
     * @param {Data.Bean} model The model that is given to the layout.
     */
    function openEmailCompose(model) {
        var prevLayout = app.controller.context.get('layout');

        if (prevLayout && prevLayout !== 'login') {
            app.utils.openEmailCreateDrawer(
                'compose-email',
                {
                    model: model,
                    fromRouter: true
                }, function(context, model) {
                    if (model && model.module === app.controller.context.get('module')) {
                        app.controller.context.reloadData();
                    }
                }
            );
        } else {
            options = {
                module: module,
                layout: 'compose-email',
                action: model.isNew() ? 'create' : 'edit',
                model: model,
                create: true
            };
            app.controller.loadView(options);
        }
    }

    app.events.on('router:init', function() {
        var routes = [{
            name: 'email_compose',
            route: module + '(/:id)/compose',
            callback: function(id) {
                var model = app.data.createBean(module);

                if (_.isEmpty(id)) {
                    openEmailCompose(model);
                } else {
                    model.set('id', id);
                    model.fetch({
                        view: 'compose-email',
                        params: {
                            erased_fields: true
                        },
                        success: function(model) {
                            var route;

                            if (model.get('state') === 'Draft' && app.acl.hasAccessToModel('edit', model)) {
                                openEmailCompose(model);
                            } else {
                                // Handle routing for an email that used to be
                                // a draft or a draft the current user cannot
                                // edit.
                                route = '#' + app.router.buildRoute(model.module, model.get('id'));
                                app.router.redirect(route);
                            }
                        }
                    });
                }
            }
        }];

        app.router.addRoutes(routes);
    });
})(SUGAR.App);
