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
 * @class View.Views.Base.Styleguide.CreateView
 * @alias SUGAR.App.view.views.StyleguideCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',
    showHelpText: false,
    showErrorDecoration: false,
    showFormHorizontal: false,
    events: {
        'click a[name=show_help_text]:not(.disabled)': 'toggleHelpText',
        'click a[name=display_error_state]:not(.disabled)': 'toggleErrorDecoration',
        'click a[name=display_form_horizontal]:not(.disabled)': 'toggleFormHorizontal'
    },

    _render: function() {
        var error_string = 'You did a bad, bad thing.';
        _.each(this.meta.panels, function(panel) {
            if (!panel.header) {
                panel.labelsOnTop = !this.showFormHorizontal;
            }
        }, this);
        if (this.showErrorDecoration) {
            _.each(this.fields, function(field) {
                if (!_.contains(['button', 'rowaction', 'actiondropdown'], field.type)) {
                    field.setMode('edit');
                    field._errors = error_string;
                    if (field.type === 'email') {
                        var errors = {email: ['primary@example.info']};
                        field.handleValidationError([errors]);
                    } else {
                        if (_.contains(['image', 'picture', 'avatar'], field.type)) {
                            field.handleValidationError(error_string);
                        } else {
                            field.decorateError(error_string);
                        }
                    }
                }
            }, this);
        }
        this._super('_render');
    },

    _renderField: function(field) {
        app.view.View.prototype._renderField.call(this, field);
        var error_string = 'You did a bad, bad thing.';
        if (!this.showHelpText) {
            field.def.help = null;
            field.options.def.help = null;
        }
    },

    toggleHelpText: function(e) {
        this.showHelpText = !this.showHelpText;
        this.render();
        e.preventDefault();
        e.stopPropagation();
    },

    toggleErrorDecoration: function(e) {
        this.showErrorDecoration = !this.showErrorDecoration;
        this.render();
        e.preventDefault();
        e.stopPropagation();
    },

    toggleFormHorizontal: function(e) {
        this.showFormHorizontal = !this.showFormHorizontal;
        this.render();
        e.preventDefault();
        e.stopPropagation();
    }
})
