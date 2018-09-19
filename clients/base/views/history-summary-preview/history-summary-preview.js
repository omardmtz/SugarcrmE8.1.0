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
 * @class View.Views.Base.HistorySummaryPreviewView
 * @alias SUGAR.App.view.views.BaseHistorySummaryPreviewView
 * @extends View.Views.Base.PreviewView
 */
({
    extendsFrom: 'PreviewView',

    /**
     * @inheritdoc
     * @override
     *
     * Overridden to make custom calls by module to get activities
     */
    _renderPreview: function(model, collection, fetch, previewId) {
        var self = this,
            newModel;

        // If there are drawers there could be multiple previews, make sure we are only rendering preview for active drawer
        if(app.drawer && !app.drawer.isActive(this.$el)){
            return;  //This preview isn't on the active layout
        }

        // Close preview if we are already displaying this model
        if(this.model && model && (this.model.get("id") == model.get("id") && previewId == this.previewId)) {
            // Remove the decoration of the highlighted row
            app.events.trigger("list:preview:decorate", false);
            // Close the preview panel
            app.events.trigger('preview:close');
            return;
        }

        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // if module is in BWC mode, just return
            return;
        }

        if (model) {
            // Use preview view if available, otherwise fallback to record view
            var viewName = 'preview',
                previewMeta = app.metadata.getView(model.module, 'preview'),
                recordMeta = app.metadata.getView(model.module, 'record');
            if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
                viewName = 'record';
            }
            this.meta = this._previewifyMetadata(_.extend({}, recordMeta, previewMeta));

            newModel = app.data.createBean(model.module);
            newModel.set('id', model.id);

            if (fetch) {
                newModel.fetch({
                    //Show alerts for this request
                    showAlerts: true,
                    success: function(model) {
                        self.renderPreview(model, collection);
                    },
                    //The view parameter is used at the server end to construct field list
                    view: viewName
                });
            } else {
                newModel.copy(model);
                this.renderPreview(newModel, collection);
            }
        }

        this.previewId = previewId;
    }
})
