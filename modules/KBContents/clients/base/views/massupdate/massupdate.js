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
    extendsFrom: 'MassupdateView',
    
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['CommittedDeleteWarning', 'KBContent', 'KBNotify']);
        this._super('initialize', [options]);
    },

    /**
     * @inheritdoc
     */
    saveClicked: function(evt) {
        var massUpdateModels = this.getMassUpdateModel(this.module).models,
            fieldsToValidate = this._getFieldsToValidate(),
            emptyValues = [];

        this._restoreInitialState(massUpdateModels);

        this._doValidateMassUpdate(massUpdateModels, fieldsToValidate, _.bind(function(fields, errors) {
            if (_.isEmpty(errors)) {
                this.trigger('massupdate:validation:complete', {
                    errors: errors,
                    emptyValues: emptyValues
                });
                if(this.$('.btn[name=update_button]').hasClass('disabled') === false) {
                    this.listenTo(this.collection, 'data:sync:complete', _.bind(function() {
                        this.notifyAll('kb:collection:updated');
                        this.stopListening(this.collection);
                    }, this));
                    this.save();
                }
            } else {
                this.handleValidationError(errors);
            }
        }, this));
    },

    /**
     * Restore models state.
     *
     * @param {Array} models
     * @private
     */
    _restoreInitialState: function(models) {
        _.each(models, function(model) {
            model.revertAttributes();
        });
    },

    /**
     * Custom MassUpdate validation.
     *
     * @param {Object} models
     * @param {Object} fields
     * @param {Function} callback
     * @private
     */
    _doValidateMassUpdate: function(models, fields, callback) {
        var checkField = 'status',
            errorFields = [],
            messages = [],
            errors = {},
            updatedValues = {};
        _.each(fields, function(field) {
            updatedValues[field.name] = this.model.get(field.name);
            if (undefined !== field.id_name && this.model.has(field.id_name)) {
                updatedValues[field.id_name] = this.model.get(field.id_name);
            }
        }, this);
        _.each(models, function(model) {
            var values = _.extend({}, model.toJSON(), updatedValues),
                newModel = app.data.createBean(model.module, values);
            if (undefined !== updatedValues[checkField] && updatedValues[checkField] === 'approved') {
                this._doValidateActiveDateField(newModel, fields, errors, function(model, fields, errors) {
                    var fieldName = 'active_date';
                    if (!_.isEmpty(errors[fieldName])) {
                        errors[checkField] = errors[fieldName];
                        errorFields.push(fieldName);
                        messages.push(app.lang.get('LBL_SPECIFY_PUBLISH_DATE', 'KBContents'));
                    }
                });
            }
            this._doValidateExpDateField(newModel, fields, errors, function(model, fields, errors) {
                var fieldName = 'exp_date';
                if (!_.isEmpty(errors[fieldName])) {
                    errors[checkField] = errors[fieldName];
                    errorFields.push(fieldName);
                    messages.push(app.lang.get('LBL_MODIFY_EXP_DATE_LOW', 'KBContents'));
                }
            });
        }, this);

        if (!_.isEmpty(errorFields)) {
            if (!_.isUndefined(errors.active_date) && errors.active_date.activeDateLow ||
                !_.isUndefined(errors.exp_date) && errors.exp_date.expDateLow) {
                callback(fields, errors);
                return;
            }
            errorFields.push(checkField);
            app.alert.show('save_without_publish_date_confirmation', {
                level: 'confirmation',
                messages: _.uniq(messages),
                confirm: {
                    label: app.lang.get('LBL_YES')
                },
                cancel: {
                    label: app.lang.get('LBL_NO')
                },
                onConfirm: function() {
                    errors = _.filter(errors, function(error, key) {
                        _.indexOf(errorFields, key) === -1;
                    });
                    callback(fields, errors);
                }
            });
        } else {
            callback(fields, errors);
        }
    },

    /**
     * We don't need to initialize KB listeners.
     * @override.
     * @private
     */
    _initKBListeners: function() {},
    
    /**
     * @inheritdoc
     */
    cancelClicked: function(evt) {
        this._restoreInitialState(this.getMassUpdateModel(this.module).models);
        this._super('cancelClicked', [evt]);
    }
})
