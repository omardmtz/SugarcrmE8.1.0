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
    events: {
        'keyup input[name=name]': 'handleKeyup',
        'click .btn': '_showVarBook'
    },

    fieldTag: 'input.inherit-width',

    _render: function() {
        if (this.view.name === 'record') {
            this.def.link = false;
        } else if (this.view.name === 'preview') {
            this.def.link = true;
        }
        this._super('_render');
    },
    /**
     * Gets the recipients DOM field
     *
     * @returns {Object} DOM Element
     */
    getFieldElement: function() {
        return this.$(this.fieldTag);
    },

    /**
     * When in edit mode, the field includes an icon button for opening an address book. Clicking the button will
     * trigger an event to open the address book, which calls this method to do the dirty work. The selected recipients
     * are added to this field upon closing the address book.
     *
     * @private
     */
    _showVarBook: function() {
        /**
         * Callback to add recipients, from a closing drawer, to the target Recipients field.
         * @param {undefined|Backbone.Collection} recipients
         */
        var addVariables = _.bind(function(variables) {
            if (variables && variables.length > 0) {
                this.model.set(this.name, this.buildVariablesString(variables));
            }
        }, this);
        app.drawer.open(
            {
                layout:  "compose-varbook",
                context: {
                    module: "pmse_Emails_Templates",
                    mixed:  true
                }
            },
            function(variables) {
                addVariables(variables);
            }
        );
    },
    buildVariablesString: function(recipients) {
        var result = '' , newExpression = '', currentValue, i, aux, aux2;
        _.each(recipients.models, function(model) {
            newExpression += '{::' + model.attributes.rhs_module + '::' + model.attributes.id + '::}';
        });

        var input = this.getFieldElement().get(0);;
        currentValue = input.value;

        i = input.selectionStart;
        result = currentValue.substr(0, i) + newExpression + currentValue.substr(i);
        return result;
    },
    /**
     * Handles the keyup event in the account create page
     */
    handleKeyup: _.throttle(function() {
        var searchedValue = this.$('input.inherit-width').val();
        if (searchedValue && searchedValue.length >= 3) {
            this.context.trigger('input:name:keyup', searchedValue);
        }
    }, 1000, {leading: false})

})
