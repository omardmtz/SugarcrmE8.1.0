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
 * View that displays edit view on a model
 * @class View.Views.Base.BaseeditView
 * @alias SUGAR.App.view.views.BaseBaseeditView
 * @extends View.View
 */
({
    /**
     * Resets the error messages for all fields that have been changed and sent on the models change event.
     * @param {object} model that was changed.
     * @param {object} object that holds the changed fields.
     */
    clearValidationError: function(model, fields) {
        var self = this;
        if(!_.isEmpty(fields.changes)){
            _.each(fields.changes, function (num, key) {
                var field = self.getField(key);

                if (field) {
                    var controlGroup = field.$el.parents('.control-group:first');

                    if (controlGroup) {
                        controlGroup.removeClass("error");
                        controlGroup.find('.add-on').remove();
                        controlGroup.find('.help-block').html("");
                    }
                }
            });
        }
    },

    /**
     * Highlights all fields that fails field validation during save.
     * @param {object} Object containing the fields that failed validation.
     */
    handleValidationError:function (errors) {
        var self = this;

        _.each(errors, function (fieldErrors, fieldName) {
            //retrieve the field by name
            var field = self.getField(fieldName);
            var ftag = this.fieldTag || '';

            if (field) {
                var controlGroup = field.$el.parents('.control-group:first');

                if (controlGroup) {
                    controlGroup.addClass("error");
                    controlGroup.find('.add-on').remove();
                    controlGroup.find('.help-block').html("");

                    if (field.$el.parent().parent().find('.input-append').length > 0) {
                        field.$el.unwrap()
                    }
                    // Add error styling
                    field.$el.wrap('<div class="input-append  '+ftag+'">');

                    _.each(fieldErrors, function (errorContext, errorName) {
                        controlGroup.find('.help-block').append(app.error.getErrorString(errorName, errorContext));
                    });

                    $('<span class="add-on"><i class="fa fa-exclamation-circle"></i></span>').insertBefore(controlGroup.find('.help-block'));
                }
            }
        });
    }
})


