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
 * @class View.Layouts.Base.pmse_Inbox.ShowCaseLayout
 * @alias SUGAR.App.view.layouts.Basepmse_InboxShowCaseLayout
 * @extends View.Layout
 */
({
    plugins: ['ProcessActions'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.inboxId = options.context.get('modelId');
        this.flowId = options.context.get('action');
        this.recordAction = options.context.get('record') || 'detail';
        this._super('initialize', [options]);
        this.context.set('skipFetch', true);
    },

    /**
     * Request case data to find record id and module
     *
     * @param {Object} [options] Options that are passed to
     *   collection/model's fetch method.
     */
    loadData: function(options) {
        var self = this,
            pmseInboxUrl = app.api.buildURL(this.module + '/case/' + this.inboxId + '/' + this.flowId);

        app.api.call('read', pmseInboxUrl, {}, {
            success: function (data) {
                // Make sure we have an options object to work with
                var options = options || {};

                // This allows us to define our own endpoint setter, which is needed
                // for case view to force consuming our own endpoint
                options.endpoint = function(method, model, opts, callbacks) {
                    var casModule = data.case.flow.cas_sugar_module;
                    var casModuleId = data.case.flow.cas_sugar_object_id;

                    // This is the endpoint URL we want to consume
                    var resourcePath = 'pmse_Inbox/caseRecord/' + casModule + '/' + casModuleId;
                    var url = app.api.buildURL(resourcePath, null, null, {view: 'record', erased_fields: true});
                    // For some reason, options contains a method property that
                    // is causing the subsequent success call to be a READ HTTP
                    // Request Type. So delete the method property of options to
                    // force a GET request to be made.
                    delete opts.method;

                    // Send back the data from our own endpoint
                    return app.api.call('read', url, {}, callbacks, opts);
                };

                self.initCaseView(data, [options]);
            },

            error: function (error) {
                app.error.handleNotFoundError();
            }
        });
    },

    /**
     * Call loadChildLayout to create the module base record view.
     * Show error messages if process is closed or unavailable.
     *
     * @param data Case information
     * @param loadDataParams
     */
    initCaseView: function(data, loadDataParams){
        if (data.case.flow.cas_flow_status === 'FORM') {
            this.loadChildLayout(data, loadDataParams);
        } else if (data.case.flow.cas_flow_status === 'CLOSED') {
            app.alert.show('message-id', {
                level: 'warning',
                messages: app.lang.get('LBL_PA_PROCESS_CLOSED','pmse_Inbox'),
                autoClose: false
            });
            app.router.goBack();
        } else {
            app.alert.show('message-id', {
                level: 'warning',
                messages: app.lang.get('LBL_PA_PROCESS_UNAVAILABLE','pmse_Inbox'),
                autoClose: false
            });
        }
    },

    /**
     * Get the module specific record layout and view and override
     * the view metadata to use PA specific buttons (like Approve/Reject).
     * Update this layout's metadata with the modified record view metadata
     * and load all the new components.
     *
     * @param data Case information
     * @param loadDataParams
     */
    loadChildLayout: function(data, loadDataParams) {
        this.case = data.case;

        //dispose of anything currently here
        app.plugins.detach(this, 'layout');
        _.each(this._components, function(component) {
            component.dispose();
        });
        this._components = [];

        this.recordModule = data.case.flow.cas_sugar_module;

        // Create the context for the record view
        var context = this.context.getChildContext({
            module: this.recordModule,
            modelId: data.case.flow.cas_sugar_object_id
        });

        // to display due date in browser time zone we need to fix it before
        // context is set since we're using raw values set in context inside template
        this.case.flow.cas_due_date = this.fixDateToLocale(this.case.flow.cas_due_date);

        context.prepare();
        context.set('case', this.case);
        context.set('layout', 'record');
        context.set('action', this.recordAction);

        this.recordContext = context;
        this.recordModel = context.get('model');

        // Get the current module specific record layout and view
        var origRecordLayout = app.metadata.getLayout(this.recordModule, 'record');

        var record = this._getChildComponent('view', 'record', origRecordLayout);
        if (!record) {
            app.logger.fatal('Record not found.');
        }

        // Override the templates and buttons to use Advanced Workflow's templates and buttons
        record.xmeta = {
            template: 'pmse-case',
            buttons: data.case.buttons
        };

        // Set this layout's meta to create a record layout inside it
        this.meta = {
            'components': [{
                'layout': origRecordLayout,
                'context': this.recordContext
            }]
        };
        this.initComponents();

        this.recordComponent = this._getNestedComponent(this._components, 'record');

        // Override functions on the record view
        _.extend(this.recordComponent, this.caseViewOverrides());

        // Swap out the event handler so we can override the error message.
        this.recordComponent.model.off('error:validation');
        var showInvalidModel = function() {
                var name = 'invalid-data';
                this._viewAlerts.push(name);
                var msg = this.formAction == 'approve' ?
                    'ERR_AWF_APPROVE_VALIDATION_ERROR' :
                    'ERR_AWF_REJECT_VALIDATION_ERROR';
                app.alert.show(name, {
                    level: 'error',
                    messages: msg
                });
            };
        this.recordComponent.model.on('error:validation', showInvalidModel, this.recordComponent);

        this._super('loadData', loadDataParams);
        this._render();
        this._delegateEvents();
    },

    /**
     * Returns a string containing only date and time for a specified date as
     * per user preferences
     * @param date A date String
     * @return String
     * @private
     */

    fixDateToLocale: function(date) {
        // get local date time for the given utc datetime
        var local = app.date.utc(date).toDate();
        var dateObj = app.date(local);
        // get date and time based on user preferences
        var fixedDate = dateObj.format(app.date.getUserDateFormat());
        var fixedTime = dateObj.format(app.date.getUserTimeFormat());

        return fixedDate + ' ' + fixedTime;
    },

    /**
     * Search the meta recursively to find to find the component
     * by the passed in name
     *
     * @param type Component type (view, layout)
     * @param name Name of the component
     * @param meta The meta for the component
     * @return {*} The component meta for the passed in name
     * @private
     */
    _getChildComponent: function(type, name, meta) {

        if (meta.name === name) {
            return meta;
        }

        if (!meta.components) {
            return;
        }

        for (var i = 0, l = meta.components.length; i < l; i++) {
            var comp = meta.components[i];

            if (comp[type] === name) {
                return comp;
            }

            var next = comp.view || comp.layout;

            if (_.isObject(next)) {
                var child = this._getChildComponent(type, name, next);
                if (child) {
                    return child;
                }
            }
        }
    },

    /**
     * Get the component from inside the layout by looking through _components
     *
     * @param components The _components inside the layout
     * @param name The name of the component we are looking for
     * @return {*} The actual component
     * @private
     */
    _getNestedComponent: function(components, name) {

        if (components.name === name) {
            return components;
        }
        for (var i = 0; i < components.length; i++) {

            if (components[i].name === name) {
                return components[i];
            }
            if (components[i]._components) {
                return this._getNestedComponent(components[i]._components, name);
            }
        }
    },

    /**
     * Set up event listeners on the record view's context
     * @private
     */
    _delegateEvents: function() {
        this.recordContext.on('case:cancel', this.cancelCase, this);
        this.recordContext.on('case:claim', this.caseClaim, this);
        this.recordContext.on('case:approve', _.bind(this.caseAction, this, 'Approve'));
        this.recordContext.on('case:reject', _.bind(this.caseAction, this, 'Reject'));
        this.recordContext.on('case:route', _.bind(this.caseAction, this, 'Route'));
        this.recordContext.on('case:history', this.caseHistory, this);
        this.recordContext.on('case:status', this.caseStatus, this);
        this.recordContext.on('case:add:notes', this.caseAddNotes, this);
        this.recordContext.on('case:change:owner', this.caseChangeOwner, this);
        this.recordContext.on('case:reassign', this.caseReassign, this);
    },

    /**
     * When clicking cancel, the case is redirected
     */
    cancelCase: function () {
        this.redirectCase();
    },

    caseClaim: function () {
        app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        var frm_action = 'Claim';
        var value = this.recordModel.attributes;
        value.moduleName = this.case.flow.cas_sugar_module;
        value.beanId = this.case.flow.cas_sugar_object_id;
        value.cas_id = this.case.flow.cas_id;
        value.cas_index = this.case.flow.cas_index;
        value.taskName = this.case.title.activity;
        var self = this;
        var pmseInboxUrl = app.api.buildURL('pmse_Inbox/engine_claim','',{},{});
        app.api.call('update', pmseInboxUrl, value,{
            success: function (){
                app.alert.dismiss('upload');
                self.redirectCase(frm_action);
            },
            error: function(error) {
                app.alert.dismiss('upload');
                var message = (error && error.message) ? error.message : 'EXCEPTION_FATAL_ERROR';
                app.alert.show('error_claim', {
                    level: 'error',
                    messages: message
                });
            }
        });
    },

    /**
     * Validate the model when trying to approve/reject/route the case
     */
    caseAction: function (action) {
        var allFields = this.recordComponent.getFields(this.recordModule, this.recordModel);
        var fieldsToValidate = {};
        if (action == 'Reject' || action == 'Route') {
            this.recordComponent.formAction = 'reject';
            var erasedFields = this.recordModel.get('_erased_fields');
            for (var fieldKey in allFields) {
                if (app.acl.hasAccessToModel('edit', this.recordModel, fieldKey) &&
                    (!_.contains(erasedFields, fieldKey) || this.recordModel.get(fieldKey))) {
                    _.extend(fieldsToValidate, _.pick(allFields, fieldKey));
                }
            }
        } else {
            this.recordComponent.formAction = 'approve';
            for (var fieldKey in allFields) {
                if (app.acl.hasAccessToModel('edit', this.recordModel, fieldKey)) {
                    _.extend(fieldsToValidate, _.pick(allFields, fieldKey));
                }
            }
        }
        this.recordModel.doValidate(fieldsToValidate, _.bind(this.validationComplete, this, action));
    },

    /**
     * Shows a window with current history of the record
     */
    caseHistory: function () {
        this.getHistory(this.case.flow.cas_id);
    },

    /**
     * Shows window with picture of current status of the process
     */
    caseStatus: function() {
        this.showStatus(this.case.flow.cas_id);
    },

    /**
     * Shows window with notes of current process
     */
    caseAddNotes: function () {
        this.showNotes(this.case.flow.cas_id, this.case.flow.cas_index);
    },

    /**
     * Allow changing owner
     */
    caseChangeOwner: function () {
        var value = this.recordModel.attributes;
        value.moduleName = this.case.flow.cas_sugar_module;
        value.beanId = this.case.flow.cas_sugar_object_id;
        this.showForm(this.case.flow.cas_id, this.case.flow.cas_index, 'adhoc', this.case.flowId, this.case.inboxId, this.case.title.activity, value);
    },

    /**
     * Reassign the case
     */
    caseReassign: function () {
        var value = this.recordModel.attributes;
        value.moduleName = this.case.flow.cas_sugar_module;
        value.beanId = this.case.flow.cas_sugar_object_id;
        this.showForm(this.case.flow.cas_id, this.case.flow.cas_index, 'reassign', this.case.flowId, this.case.inboxId, this.case.title.activity, value);
    },

    /**
     * If validation is valid, save the model and approve the case
     *
     * @param {string} action Either Approve, Reject or Route
     * @param {boolean} isValid `true` if valid, false if validation failed
     */
    validationComplete: function (action, isValid) {
        var buttonLangStrings = {
            'Approve': {
                confirm: 'LBL_PA_PROCESS_APPROVE_QUESTION',
                success: 'LBL_PA_PROCESS_APPROVED_SUCCESS'
            },
            'Reject': {
                confirm: 'LBL_PA_PROCESS_REJECT_QUESTION',
                success: 'LBL_PA_PROCESS_REJECTED_SUCCESS'
            },
            'Route': {
                confirm: 'LBL_PA_PROCESS_ROUTE_QUESTION',
                success: 'LBL_PA_PROCESS_ROUTED_SUCCESS'
            }
        };

        if (isValid) {
            app.alert.show('confirm_save_process', {
                level: 'confirmation',
                messages: app.lang.get(buttonLangStrings[action].confirm, 'pmse_Inbox'),
                onConfirm: _.bind(function () {
                    app.alert.show('upload', {
                        level: 'process',
                        title: app.lang.get('LBL_LOADING'),
                        autoclose: false
                    });
                    var data = app.data.getEditableFields(this.recordModel);
                    data = _.extend(data, {
                        frm_action: action,
                        idFlow: this.case.flowId,
                        idInbox: this.case.inboxId,
                        cas_id: this.case.flow.cas_id,
                        cas_index: this.case.flow.cas_index,
                        moduleName: this.case.flow.cas_sugar_module,
                        beanId: this.case.flow.cas_sugar_object_id,
                        taskName: this.case.title.activity
                    });

                    if (action === 'Route' && this.case.taskContinue) {
                        data.taskContinue = true;
                    }

                    var self = this;
                    var pmseInboxUrl = app.api.buildURL('pmse_Inbox/engine_route', '', {}, {});

                    app.api.call('update', pmseInboxUrl, data, {
                        success: function () {
                            app.alert.show('success_save_process', {
                                level: 'success',
                                messages: app.lang.get(buttonLangStrings[action].success, 'pmse_Inbox'),
                                autoClose: true
                            });
                            self.recordModel.setSyncedAttributes(data);
                            self.redirectCase();
                        },
                        error: function(error) {
                            app.alert.dismiss('upload');
                            var message = (error && error.message) ? error.message : 'EXCEPTION_FATAL_ERROR';
                            app.alert.show('error_save_process', {
                                level: 'error',
                                messages: message
                            });
                        }
                    });
                }, this),
                onCancel: $.noop
            });
        }
    },

    /**
     * Leave the case record view
     *
     * @param isRoute
     */
    redirectCase: function(isRoute){
        app.alert.dismiss('upload');
        switch(isRoute){
            case 'Claim':
                window.location.reload();
                break;
            default:
                app.router.list("Home");
                break;
        };
    },

    /**
     * Defines and returns functions that will override the case module's record view.
     * Add functions to the return object anytime you want to change record view behavior.
     *
     * @return Object of functions
     */
    caseViewOverrides: function() {
        return {
            /**
             * @override
             *
             * Allows checking Advanced Workflow readonly and required fields
             */
            setEditableFields: function() {
                delete this.editableFields;
                this.editableFields = [];
                var previousField, firstField;
                _.each(this.fields, function(field) {
                    if (this.checkReadonly(field)) {
                        field.def.readonly = true;
                    }
                    if (field.def.fields && _.isArray(field.def.fields)) {
                        var that = this;
                        var basefield = field;
                        _.each(field.def.fields, function(field) {
                            if (that.checkReadonly(field)) {
                                field.readonly = true;
                                field.action = 'detail';
                                // Some fields use shouldDisable to enable readonly property,
                                // like 'body' in KBContents
                                if (!_.isUndefined(field.shouldDisable)) {
                                    field.setDisabled(true);
                                    basefield.def.readonly = true;
                                }
                            }
                        });
                    }
                    if (field.fields && _.isArray(field.fields)) {
                        var self = this;
                        _.each(field.fields, function(field) {
                            if (self.checkRequired(field)) {
                                field.def.required = true;
                            }
                        });
                    }
                    var readonlyField = field.def.readonly ||
                        _.indexOf(this.noEditFields, field.def.name) >= 0 ||
                        field.parent || (field.name && this.buttons[field.name]);

                    if (readonlyField) {
                        // exclude read only fields
                        return;
                    }
                    if (this.checkRequired(field)) {
                        field.def.required = true;
                    }
                    if (previousField) {
                        previousField.nextField = field;
                        field.prevField = previousField;
                    } else {
                        firstField = field;
                    }
                    previousField = field;
                    this.editableFields.push(field);

                }, this);

                if (previousField) {
                    previousField.nextField = firstField;
                    firstField.prevField = previousField;
                }

            },

            /**
             * @override
             * Toggle more fields than on base record view
             * @param isEdit
             */
            toggleViewButtons: function(isEdit) {
                this.$('.headerpane span[data-type="badge"]').toggleClass('hide', isEdit);
                this.$('.headerpane span[data-type="favorite"]').toggleClass('hide', isEdit);
                this.$('.headerpane span[data-type="follow"]').toggleClass('hide', isEdit);
                this.$('.headerpane .btn-group-previous-next').toggleClass('hide', isEdit);
            },

            /**
             * @override
             * We want to set field metadata here if a field is readonly
             * @param panels
             * @private
             */
            _buildGridsFromPanelsMetadata: function(panels) {
                var lastTabIndex = 0;
                this.noEditFields = [];

                _.each(panels, function(panel) {
                    // it is assumed that a field is an object but it can also be a string
                    // while working with the fields, might as well take the opportunity to check the user's ACLs for the field
                    _.each(panel.fields, function(field, index) {
                        if (this.checkReadonly(field)) {
                            field.readonly = true;
                        }
                        if (_.isString(field)) {
                            panel.fields[index] = field = {name: field};
                        }

                        var keys = _.keys(field);

                        // Make filler fields readonly
                        if (keys.length === 1 && keys[0] === 'span') {
                            field.readonly = true;
                        }

                        // disable the pencil icon if the user doesn't have ACLs
                        if (field.type === 'fieldset') {
                            if (field.readonly || _.every(field.fields, function(field) {
                                    return !app.acl.hasAccessToModel('edit', this.model, field.name);
                                }, this)) {
                                this.noEditFields.push(field.name);
                            }
                        } else if (field.readonly || !app.acl.hasAccessToModel('edit', this.model, field.name)) {
                            this.noEditFields.push(field.name);
                        }
                    }, this);

                    // Set flag so that show more link can be displayed to show hidden panel.
                    if (panel.hide) {
                        this.hiddenPanelExists = true;
                    }

                    // labels: visibility for the label
                    if (_.isUndefined(panel.labels)) {
                        panel.labels = true;
                    }

                    if (_.isFunction(this.getGridBuilder)) {
                        var options = {
                                fields: panel.fields,
                                columns: panel.columns,
                                labels: panel.labels,
                                labelsOnTop: panel.labelsOnTop,
                                tabIndex: lastTabIndex
                            },
                            gridResults = this.getGridBuilder(options).build();

                        panel.grid = gridResults.grid;
                        lastTabIndex = gridResults.lastTabIndex;
                    }
                }, this);
            },

            /**
             * Check if the field is set to readonly by Advanced Workflow
             * @param field The field
             * @returns {boolean} `true` or `false`
             */
            checkReadonly: function(field){
                var isReadonly = false;
                _.each(this.context.get('case').readonly, function(caseField) {
                    if (field.name === caseField) {
                        isReadonly = true;
                    }
                }, this);
                return isReadonly;
            },

            /**
             * Check if the field is set to required by Advanced Workflow
             * @param field The field
             * @returns {boolean} `true` or `false`
             */
            checkRequired: function(field){
                var isRequired = false;
                _.each(this.context.get('case').required, function(caseField) {
                    if (field.name === caseField) {
                        isRequired = true;
                    }
                }, this);
                return isRequired;
            }
        }
    }
})
