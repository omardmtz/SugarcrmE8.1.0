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
 * @class View.Views.Base.pmse_Project.CreateView
 * @alias SUGAR.App.view.views.pmse_ProjectCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',

    saveOpenDesignName: 'save_open_design',

    SAVEACTIONS: {
        SAVE_OPEN_DESIGN: 'saveOpenDesign'
    },

    initialize: function(options) {
        options.meta = _.extend({}, app.metadata.getView(null, 'create'), options.meta);
        this._super('initialize', [options]);
        this.context.on('button:' + this.saveOpenDesignName + ':click', this.saveOpenDesign, this);
    },

    save: function () {
        switch (this.context.lastSaveAction) {
            case this.SAVEACTIONS.SAVE_OPEN_DESIGN:
                this.saveOpenDesign();
                break;
            default:
                this.saveAndClose();
        }
    },

    saveOpenDesign: function() {
        this.context.lastSaveAction = this.SAVEACTIONS.SAVE_OPEN_DESIGN;
        this.initiateSave(_.bind(function () {
            app.navigate(this.context, this.model, 'layout/designer');
        }, this));
    }
})
