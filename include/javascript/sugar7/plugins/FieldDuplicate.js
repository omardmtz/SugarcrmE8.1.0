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
        app.plugins.register('FieldDuplicate', ['field'], {
            /**
             * Contains id of {Data.Bean} from which field should be duplicated.
             *
             * @property {String} _duplicateBeanId
             * @protected
             */
            _duplicateBeanId: null,

            _duplicateBeanModule: null,

            /**
             * Setup id of {Data.Bean} from which field should be duplicated.
             *
             * @param {String} id Id of model.
             * @param {String} module Module of model.
             */
            duplicateFromModel: function(id, module) {
                this._duplicateBeanId = id;

                /**
                 * Contains module of {@link Data.Bean} from which field should be duplicated.
                 *
                 * @property {String}
                 * @protected
                 */
                this._duplicateBeanModule = module;
            },

            /**
             * Handler for `duplicate:field` event triggered on model. Setup id of
             * model from which field should be duplicated.
             *
             * @param {Data.Bean/null} model Model from which field should be duplicated.
             * @private
             */
            _onFieldDuplicate: function(model) {
                var id = null, module = null;

                if (model instanceof Backbone.Model) {
                    id = model.get('id');
                    module = model.module;
                }

                this.duplicateFromModel(
                    (this.model && this.model.get('id') === id) ? null : id,
                    (this.module && this.module === module) ? null : module
                );

                if (_.isFunction(this.onFieldDuplicate)) {
                    this.onFieldDuplicate.call(this, model);
                }
            },

            /**
             * Handler for `before duplicate:field` event triggered on model.
             *
             * Event `duplicate:field` is triggered in method
             * {@link View.Views.BaseMergeDuplicatesView#triggerCopy}.
             *
             * Calls `beforeFieldDuplicate` method if it is implemented in field.
             *
             * @params {Object} params Params to pass to method call.
             * @param {Data.Bean} params.model Model from which value should be duplicated.
             * @param {Object} params.data Data attributes of DOM element (radio or checkbox).
             * @return {Boolean} 'true' to continue or `false` to stop.
             * @private
             */
            _beforeFieldDuplicate: function(params) {
                if (_.isFunction(this.beforeFieldDuplicate)) {
                    return this.beforeFieldDuplicate.call(this, params);
                }
                return true;
            },

            /**
             * Handler for `duplicate:format:field` event triggered on model.
             *
             * Event `duplicate:format:field` is triggered in method
             * {@link View.Views.BaseMergeDuplicatesView#setPrimaryEditable}.
             *
             * Calls `formatFieldForDuplicate` method if it is implemented in field.
             *
             * @private
             */
            _formatFieldForDuplicate: function() {
                if (!this.disposed && _.isFunction(this.formatFieldForDuplicate)) {
                    this.formatFieldForDuplicate.call(this);
                    this.render();
                }

            },

            /**
             * Handler for `duplicate:unformat:field` event triggered on model.
             *
             * Event `duplicate:unformat:field` is triggered in method
             * {@link View.Views.BaseMergeDuplicatesView#_savePrimary}.
             *
             * Calls `unformatFieldForDuplicate` method if it is implemented in field.
             *
             * @private
             */
            _unformatFieldForDuplicate: function() {
                if (_.isFunction(this.unformatFieldForDuplicate)) {
                    this.unformatFieldForDuplicate.call(this);
                }
            },

            /**
             * Bind handlers for field duplication.
             *
             * @param {View.Component} component Component to attach plugin.
             * @param {Object} plugin Object of plugin to attach.
             * @return {void}
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    if (this.model) {
                        this.model.on('change:' + this.name, function() {
                            this._onFieldDuplicate();
                        }, this);
                        this.model.on('duplicate:field', this._onFieldDuplicate, this);
                        this.model.on('duplicate:field:' + this.name, this._onFieldDuplicate, this);
                        this.model.on('data:sync:start', function(method, options) {
                            if (!_.isNull(this._duplicateBeanId) &&
                                (method == 'update' || method == 'create')
                            ) {
                                options.params = options.params || {};
                                options.params[this.name + '_duplicateBeanId'] = this._duplicateBeanId;
                            }
                        }, this);
                        this.model.on('duplicate:field:prepare:save', function(model) {
                            if (this.model.get(this.name)) {
                                model[this.name + '_duplicateBeanId'] = this._duplicateBeanId;
                            }
                        }, this);
                        this.model.on('duplicate:format:field', this._formatFieldForDuplicate, this);
                        this.model.on('duplicate:unformat:field', this._unformatFieldForDuplicate, this);
                    }
                    if (this.view) {
                        this.view.before('duplicate:field', this._beforeFieldDuplicate, this);
                    }
                });
            },

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                if (this.view) {
                    this.view.offBefore('duplicate:field');
                }
            }
        });
    });
})(SUGAR.App);
