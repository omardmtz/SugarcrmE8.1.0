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
 * @class View.Views.Base.BaseeditmodalView
 * @alias SUGAR.App.view.views.BaseBaseeditmodalView
 * @extends View.View
 */
({
    events: {
        'click [name=save_button]': 'saveButton',
        'click [name=cancel_button]': 'cancelButton'
    },
    // Delegate events
    saveButton: function() {
        var createModel = this.context.get('createModel');

        this.$('[name=save_button]').attr('data-loading-text', app.lang.get('LBL_LOADING'));
        this.$('[name=save_button]').button('loading');

        // Disable the buttons during save.
        this.disableButtons(true);
        this.processModel(createModel);

        // saves the related bean
        createModel.save(null, {
            relate: true,
            fieldsToValidate: this.getFields(this.module),
            success: _.bind(function() {
                this.saveComplete();
            }, this),
            error: _.bind(function() {
                this.disableButtons(false);
            }, this)

        });
    },
    /**
     * Preprocess the model before saving. This function is here so that children may override it as necessary.
     * @param {object} model
     */
    processModel: function(model) {

    },
    cancelButton: function() {
        if (Modernizr.touch) {
            app.$contentEl.removeClass('content-overflow-visible');
        }
        this.$('.modal').modal('hide').find('form').get(0).reset();
        if (this.context.has('createModel')) {
            this.context.get('createModel').clear();
        }
    },
    saveComplete: function() {
        //reset the form
        this.$('.modal').modal('hide').find('form').get(0).reset();
        //reset the `Save` button
        this.disableButtons(false);
        //add the new model to the collection
        this.collection.fetch({relate: true});
    },

    /**
     * Enables or disables the buttons from the metadata. Disables them by
     * default.
     *
     * @param {boolean} [disable=true] Whether to enable or disable the buttons.
     *   Defaults to `true`.
     */
    disableButtons: function(disable) {
        var state = _.isUndefined(disable) ? true : disable;

        _.each(this.meta.buttons, function(button) {
            var btn = this.getField(button.name);
            btn.setDisabled(state);
        }, this);
    }
})
