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
 * @class View.Views.Base.EditmodalView
 * @alias SUGAR.App.view.views.BaseEditmodalView
 * @extends View.Views.Base.BaseeditmodalView
 */
({
    extendsFrom:'BaseeditmodalView',
    fallbackFieldTemplate: 'edit',
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        if (this.layout) {
            this.layout.on('app:view:activity:editmodal', function() {
                this.context.set('createModel',
                    app.data.createRelatedBean(app.controller.context.get('model'), null, 'notes', {})
                );
                this.render();
                this.$('.modal').modal({backdrop: 'static'});
                this.$('.modal').modal('show');
                app.$contentEl.attr('aria-hidden', true);
                $('.modal-backdrop').insertAfter($('.modal'));
                this.context.get('createModel').on('error:validation', function() {
                    this.disableButtons(false);
                }, this);
            }, this);
        }
        this.bindDataChange();
    },
    cancelButton: function() {
        this._super('cancelButton');
        app.$contentEl.removeAttr('aria-hidden');
    },
    saveComplete: function() {
        this._super('saveComplete');
        app.$contentEl.removeAttr('aria-hidden');
    }
  })
