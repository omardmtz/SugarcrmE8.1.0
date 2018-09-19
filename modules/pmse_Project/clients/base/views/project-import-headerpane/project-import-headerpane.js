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
    events:{
        'click [name=project_finish_button]': 'initiateFinish',
        'click [name=project_cancel_button]': 'initiateCancel'
    },

    initiateFinish: function() {
        var that = this;
        if (app.cache.get("show_project_import_warning")) {
            app.alert.show('project-import-confirmation',  {
                level: 'confirmation',
                messages: app.lang.get('LBL_PMSE_IMPORT_EXPORT_WARNING') + "<br/><br/>"
                    + app.lang.get('LBL_PMSE_IMPORT_CONFIRMATION'),
                onConfirm: function () {
                    app.cache.set("show_project_import_warning", false);
                    that.context.trigger('project:import:finish');
                },
                onCancel: function () {
                    app.router.goBack();
                }
            });
        } else {
            that.context.trigger('project:import:finish');
        }
    },

    initiateCancel : function() {
        app.router.navigate(app.router.buildRoute(this.module), {trigger: true});
    }
})
