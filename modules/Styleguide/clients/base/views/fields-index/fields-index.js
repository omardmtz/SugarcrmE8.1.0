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
    className: 'container-fluid',
    section: {},
    useTable: true,
    parent_link: '',
    tempfields: [],

    initialize: function(options) {
        this._super('initialize', [options]);
        var request = this.context.get('request');
        this.keys = request.keys;
        this.page = request.page_details;
    },

    _render: function() {
        var self = this,
            fieldTypeReq = this.context.get('content_name'),
            fieldTypes = fieldTypeReq === 'index' ? ['text', 'bool', 'date', 'datetimecombo', 'currency', 'email'] : [fieldTypeReq],
            fieldStates = ['detail', 'edit', 'error', 'disabled'],
            fieldLayouts = ['base', 'record', 'list'],
            fieldMeta = {};

        this.useTable = fieldTypeReq === 'index' ? true : false;
        this.parent_link = fieldTypeReq === 'index' ? 'docs/forms-index' : 'fields/index';
        this.tempfields = [];

        _.each(fieldTypes, function(fieldType) {

            //build meta data for field examples from model fields
            fieldMeta = _.find(self.model.fields, function(field) {
                if (field.type === 'datetime' && fieldType.indexOf('date') === 0) {
                    field.type = fieldType;
                }
                return field.type === fieldType;
            }, self);

            //insert metadata into array for hbs template
            if (fieldMeta) {
                var metaData = self.meta['template_values'][fieldType];

                if (_.isObject(metaData) && !_.isArray(metaData)) {
                    _.each(metaData, function(value, name) {
                        self.model.set(name, value);
                    }, self);
                } else {
                    self.model.set(fieldMeta.name, metaData);
                }

                self.tempfields.push(fieldMeta);
            }
        });

        if (fieldTypeReq !== 'index') {
            self.title = fieldTypeReq + ' field';
            var descTpl = app.template.getView('fields-index.' + fieldTypeReq, self.module);
            if (descTpl) {
                this.documentation = descTpl();
            } else {
                this.page.description = 'Sugar7 ' + fieldTypeReq + ' field';
            }
        }

        this._super('_render');

        //render example fields into accordion grids
        //e.g., ['text','bool','date','datetimecombo','currency','email']
        _.each(fieldTypes, function(fieldType) {

            var fieldMeta = _.find(self.tempfields, function(field) {
                    return field.type === fieldType;
                }, self);

            //e.g., ['detail','edit','error','disabled']
            _.each(fieldStates, function(fieldState) {

                //e.g., ['base','record','list']
                _.each(fieldLayouts, function(fieldLayout) {
                    var fieldTemplate = fieldState;

                    //set field view template name
                    if (fieldLayout === 'list') {
                        if (fieldState === 'edit') {
                            fieldTemplate = 'list-edit';
                        } else {
                            fieldTemplate = 'list';
                        }
                    } else if (fieldState === 'error') {
                        fieldTemplate = 'edit';
                    }

                    var fieldSettings = {
                            view: self,
                            def: {
                                name: fieldMeta.name,
                                type: fieldType,
                                view: fieldTemplate,
                                default: true,
                                enabled: fieldState === 'disabled' ? false : true
                            },
                            viewName: fieldTemplate,
                            context: self.context,
                            module: self.module,
                            model: self.model,
                            meta: fieldMeta
                        };

                    var fieldObject = app.view.createField(fieldSettings),
                        fieldDivId = '#' + fieldType + '_' + fieldState + '_' + fieldLayout;

                    //pre render field setup
                    if (fieldState !== 'detail') {
                        if (!fieldObject.plugins || !_.contains(fieldObject.plugins, 'ListEditable') || fieldLayout !== 'list') {
                            fieldObject.setMode('edit');
                        } else {
                            fieldObject.setMode('list-edit');
                        }
                    }
                    if (fieldState === 'disabled') {
                        fieldObject.setDisabled(true);
                    }

                    //render field
                    self.$(fieldDivId).append(fieldObject.el);
                    fieldObject.render();

                    //post render field setup
                    if (fieldType === 'currency' && fieldState === 'edit') {
                        fieldObject.setMode('edit');
                    }
                    if (fieldState === 'error') {
                        if (fieldType === 'email') {
                            var errors = {email: ['primary@example.info']};
                            fieldObject.decorateError(errors);
                        } else {
                            fieldObject.setMode('edit');
                            fieldObject.decorateError('You did a bad, bad thing.');
                        }
                    }
                });

            });

        });
    }
})
