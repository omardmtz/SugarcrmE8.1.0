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
    className: 'designer',

    events: {
        'click .btn-close-designer': 'closeDesigner'
    },

    closeDesigner: function() {
        var route = app.router.buildRoute(this.module, this.prj_uid);
        app.router.navigate(route, {trigger: true});
    },

    loadData: function (options) {
        this.prj_uid = this.options.context.attributes.modelId;
        this.cacheKiller = (new Date()).getTime();
    },

    initialize: function (options) {
        this._super('initialize', [options]);
        app.routing.before('route', this.beforeRouteChange, this);
    },

    render: function () {
        app.view.View.prototype.render.call(this);
        renderProject(this.prj_uid);
    },

    beforeRouteChange: function(params) {
        var self = this,
            resp = false;
        if (project.isDirty){
            project.showWarning = true;
            var targetUrl = Backbone.history.getFragment();
            //Replace the url hash back to the current staying page
            app.router.navigate(targetUrl, {trigger: false, replace: true});
            app.alert.show('leave_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_WARN_UNSAVED_CHANGES', this.module),
                onConfirm: function () {
                    var targetUrl = Backbone.history.getFragment();
                    project.dispose();
                    app.router.navigate(targetUrl , {trigger: true, replace: true });
                    window.location.reload()
                },
                onCancel: function () {
                    app.router.navigate('' , {trigger: false, replace: false })
                }
            });
            return false;
        }
        project.dispose();
        return true;
    },

    _dispose: function () {
        app.routing.offBefore('route', this.beforeRouteChange);
        this._super("_dispose", arguments);
    }
})
