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
 * @class View.Views.Base.ConvertResultsView
 * @alias SUGAR.App.view.views.BaseConvertResultsView
 * @extends View.View
 */
({
    associatedModels: null,

    events:{
        'click .preview-list-item':'previewRecord'
    },

    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        app.events.on("list:preview:decorate", this.decorateRow, this);
        this.associatedModels = app.data.createMixedBeanCollection();
    },

    bindDataChange: function() {
        this.model.on("change", this.populateResults, this);
    },

    /**
     * Build a collection of associated models and re-render the view.
     * Override this method for module specific functionality.
     */
    populateResults: function() {
        this.associatedModels.reset();
        app.view.View.prototype.render.call(this);
    },

    /**
     * Handle firing of the preview render request for selected row
     *
     * @param e
     */
    previewRecord: function(e) {
        var $el = this.$(e.currentTarget),
            data = $el.data(),
            model = app.data.createBean(data.module, {id:data.id});

        model.fetch({
            //Show alerts for this request
            showAlerts: true,
            success: _.bind(function(model) {
                model.module = data.module;
                app.events.trigger("preview:render", model, this.associatedModels);
            }, this)
        });
    },

    /**
     * Decorate a row in the list that is being shown in Preview
     * @param model Model for row to be decorated.  Pass a falsy value to clear decoration.
     */
    decorateRow: function(model){
        this.$("tr.highlighted").removeClass("highlighted current above below");
        if(model){
            var rowName = model.module+"_"+ model.get("id");
            var curr = this.$("tr[name='"+rowName+"']");
            curr.addClass("current highlighted");
            curr.prev("tr").addClass("highlighted above");
            curr.next("tr").addClass("highlighted below");
        }
    }
})
