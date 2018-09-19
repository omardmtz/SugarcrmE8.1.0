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
(function(app) {
    app.events.on('app:init', function() {
        /**
         * The FieldErrorCollection Plugin listens for any field:error events
         * being triggered on a View's context from any Field. This allows a View
         * to keep track of any Fields currently in an error state.
         *
         * Triggered Events:
         *      event: plugin:fieldErrorCollection:hasFieldErrors
         *      params:
         *          - {Backbone.Collection} The _errorCollection from the plugin in case any addt'l logic is needed
         *          - {Boolean} True if there are currently errors, false if not
         *
         */
        app.plugins.register('FieldErrorCollection', ['view'], {

            /**
             * Holds the plugin's fields currently in error state
             */
            _errorCollection: undefined,

            /**
             * Attach code for when the plugin is registered on a view
             *
             * @param {App.view.Component} component The component to which plugin is attaching
             * @param {Object} plugin The plugin being attached
             */
            onAttach: function(component, plugin) {
                this._errorCollection = new Backbone.Collection();
                this.once('init', function() {
                    this.context.on('field:error', this.handleErrorEvent, this);
                }, this);
            },

            /**
             * Detach code for when the plugin is removed from a view
             *
             * @param {App.view.Component} component The component to which plugin is attaching
             * @param {Object} plugin The plugin being detached
             */
            onDetach: function(component, plugin) {
                this.context.off('field:error', null, this);
            },

            /**
             * Handles adding and removing field errors from the _errorCollection
             *
             * @param {App.view.Field} field The field triggering an error event
             * @param {Boolean} [addError=true] If true, add to _errorCollection. If false, remove
             */
            handleErrorEvent: function(field, addError) {
                // if addError was not passed in, set to true
                addError = _.isUndefined(addError) ? true : addError;
                if(addError) {
                    this._errorCollection.add(field.model);
                } else {
                    this._errorCollection.remove(field.model);
                }

                this.context.trigger('plugin:fieldErrorCollection:hasFieldErrors', this._errorCollection, this.hasFieldErrors());
            },

            /**
             * Helper method returns Boolean if there are currently field errors
             *
             * @return {boolean}
             */
            hasFieldErrors: function() {
                return (this._errorCollection.length > 0);
            }
        });
    });
})(SUGAR.App);
