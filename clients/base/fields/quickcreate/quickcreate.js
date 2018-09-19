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
/**
 * @class View.Fields.Base.QuickcreateField
 * @alias SUGAR.App.view.fields.BaseQuickcreateField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click .actionLink[data-event="true"]' : '_handleActionLink'
    },

    plugins: ['LinkedModel'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        //Listen to create view model changes to keep track of unsaved changes
        app.events.on('create:model:changed', this.createModelChanged, this);
        this.on('linked-model:create', this._prepareCtxForReload, this);
    },

    /**
     * Changes properties on the context so that its collection can be
     * re-fetched.
     *
     * FIXME: This will be removed when SC-4775 is implemented.
     *
     * @private
     */
    _prepareCtxForReload: function() {
        this.context.resetLoadFlag();
        this.context.set('skipFetch', false);
    },

    /**
     * Keeps track of if the create view's model has changed.
     * @param hasChanged
     */
    createHasChanges: false,
    createModelChanged: function(hasChanged) {
        this.createHasChanges = hasChanged;
    },
    /**
     * When menu item is clicked, warn if open drawers, reset drawers and open create
     * @param evt
     * @private
     */
    _handleActionLink: function(evt) {
        var $actionLink = $(evt.currentTarget),
            module = $actionLink.data('module'),
            moduleMeta = app.metadata.getModule(this.context.get('module'));
        this.actionLayout = $actionLink.data('layout');
        if (this.createHasChanges) {
            app.alert.show('send_confirmation', {
                level: 'confirmation',
                messages: 'LBL_WARN_UNSAVED_CHANGES',
                onConfirm: _.bind(function() {
                    app.drawer.reset(false);
                    this.createRelatedRecord(module);
                }, this)
            });
        } else {
            // TODO: SP-1568 - We don't yet deal with bwc model changed attributes so
            // this will navigate to new create page WITHOUT alert for unsaved changes
            this.createRelatedRecord(module);
        }
    },
    /**
     * Route to Create Related record UI for a BWC module.
     *
     * @param {String} module Module name.
     */
    routeToBwcCreate: function(module) {
        var context = this.getRelatedContext(module);
        if (context) {
            app.bwc.createRelatedRecord(module, this.context.get('model'), context.link);
        } else {
            var route = app.bwc.buildRoute(module, null, 'EditView');
            app.router.navigate(route, {trigger: true});
        }
    },

    /**
     * Returns context link and module name
     * if possible to create a record with context.
     *
     * @param {String} module Module name.
     * @return {Array|undefined}
     */
    getRelatedContext: function(module) {
        var meta = app.metadata.getModule(module),
            context;

        if (meta && meta.menu.quickcreate.meta.related) {
            var parentModel = this.context.get('model');

            if (parentModel.isNew()) {
                return;
            }

            context = _.find(
                meta.menu.quickcreate.meta.related,
                function(metadata) {
                    return metadata.module === parentModel.module;
                }
            );
        }

        return context;
    },

    /**
     * Open the appropriate quick create layout in a drawer
     *
     * @param {String} module Module name.
     */
    openCreateDrawer: function(module) {
        var relatedContext = this.getRelatedContext(module),
            model = null;

        if (relatedContext) {
            model = this.createLinkModel(this.context.get('model'), relatedContext.link);
        }
        app.drawer.open({
            layout: this.actionLayout || 'create',
            context: {
                create: true,
                module: module,
                model: model
            }
        }, _.bind(function (refresh, model) {
            if (refresh) {
                // When user quick creates a model he has no access, it loads the 404 page so we need to redirect him to
                // his previous page manually
                if (model && !model.id) {
                    app.router.refresh();
                    return;
                }
                if (model && relatedContext) {
                    // Refresh the subpanel.
                    this.context.trigger('panel-top:refresh', relatedContext.link);
                    return;
                }
                //Check main context to see if it needs to be updated
                this._loadContext(app.controller.context, module);
                //Also check child contexts for updates
                if (app.controller.context.children) {
                    _.each(app.controller.context.children, function(context){
                        this._loadContext(context, module);
                    }, this);
                }
            }
        }, this));
    },
    /**
     * Conditionally load context if it is for given module
     * @param context Context to load
     * @param module Module name to check
     * @private
     */
    _loadContext: function(context, module){
        var collection = context.get('collection');
        if (collection && collection.module === module) {
            var options = {
                //Don't show alerts for this request, background update
                showAlerts: false
            };
            context.resetLoadFlag({recursive: false});
            context.set('skipFetch', false);
            context.loadData(options);
        }
    }
})
