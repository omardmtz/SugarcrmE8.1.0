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
 * @class View.Views.Base.pmse_Emails_Templates.CreateView
 * @alias SUGAR.App.view.views.pmse_Emails_TemplatesCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',

    saveOpenEmailsTemplatesName: 'save_open_emailstemplates',

    SAVEACTIONS: {
        SAVE_OPEN_EMAILS_TEMPLATES: 'saveOpenEmailsTemplates'
    },

    initialize: function(options) {
        options.meta = _.extend({}, app.metadata.getView(null, 'create'), options.meta);
        this._super('initialize', [options]);
        this.context.on('button:' + this.saveOpenEmailsTemplatesName + ':click', this.saveOpenEmailsTemplates, this);
    },

    save: function () {
        switch (this.context.lastSaveAction) {
            case this.SAVEACTIONS.SAVE_OPEN_EMAILS_TEMPLATES:
                this.saveOpenEmailsTemplates();
                break;
            default:
                this.saveAndClose();
        }
    },

    saveOpenEmailsTemplates: function() {
        this.context.lastSaveAction = this.SAVEACTIONS.SAVE_OPEN_EMAILS_TEMPLATES;
        this.initiateSave(_.bind(function () {
            app.navigate(this.context, this.model, 'layout/emailtemplates');
        }, this));
    }
})
