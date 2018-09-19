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
 * @class View.Views.Base.CreateView
 * @alias SUGAR.App.view.views.CreateView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',
    editAllMode: false,

    enableDuplicateCheck: false,
    dupecheckList: null, //duplicate list layout

    saveButtonName: 'save_button',
    cancelButtonName: 'cancel_button',
    restoreButtonName: 'restore_button',

    /**
     * If this create view has subpanel models to save
     */
    hasSubpanelModels: false,

    /**
     * A collection of alert messages to be used in this view. The alert methods
     * should be invoked by Function.prototype.call(), passing in an instance of
     * a sidecar view. For example:
     *
     *     // ...
     *     this.alerts.showInvalidModel.call(this);
     *     // ...
     *
     * FIXME: SC-3451 will refactor this `alerts` structure.
     * @property {Object}
     */
    alerts: {
        showInvalidModel: function() {
            if (!this instanceof app.view.View) {
                app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                return;
            }
            var name = 'invalid-data';
            this._viewAlerts.push(name);
            app.alert.show(name, {
                level: 'error',
                messages: 'ERR_RESOLVE_ERRORS'
            });
        },
        showServerError: function() {
            if (!this instanceof app.view.View) {
                app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                return;
            }
            var name = 'server-error';
            this._viewAlerts.push(name);
            app.alert.show(name, {
                level: 'error',
                messages: 'ERR_GENERIC_SERVER_ERROR'
            });
        },
        showSuccessButDeniedAccess: function() {
            if (!this instanceof app.view.View) {
                app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                return;
            }
            var name = 'invalid-data';
            this._viewAlerts.push(name);
            app.alert.show(name, {
                level: 'warning',
                messages: 'LBL_RECORD_SAVED_ACCESS_DENIED',
                autoClose: true,
                autoCloseDelay: 9000
            });
        }
    },

    /**
     * Initialize the view and prepare the model with default button metadata
     * for the current layout.
     */
    initialize: function (options) {
        this.plugins = _.union(this.plugins || [], [
            'FindDuplicates'
        ]);

        //add states for create view
        this.STATE = _.extend({}, this.STATE, {
            CREATE: 'create',
            SELECT: 'select',
            DUPLICATE: 'duplicate'
        });

        //inherit base create metadata for purpose of initialization
        options.meta = _.extend({}, app.metadata.getView(null, 'create'), options.meta);

        this._super("initialize", [options]);

        // FIXME: SC-3451 will refactor this `alerts` structure.
        this.alerts = _.extend({}, this.alerts, {
            showServerError: function() {
                if (!this instanceof app.view.View) {
                    app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                    return;
                }
                var name = 'server-error';
                this._viewAlerts.push(name);
                app.alert.show(name, {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR'
                });
            },
            showNoAccessError: function() {
                if (!this instanceof app.view.View) {
                    app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                    return;
                }
                var name = 'server-error';
                this._viewAlerts.push(name);
                this.cancel();
                app.alert.show(name, {
                    level: 'error',
                    messages: 'ERR_HTTP_404_TEXT_LINE1'
                });
            },
            showSuccessButDeniedAccess: function() {
                if (!this instanceof app.view.View) {
                    app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                    return;
                }
                var name = 'invalid-data';
                this._viewAlerts.push(name);
                app.alert.show(name, {
                    level: 'warning',
                    messages: 'LBL_RECORD_SAVED_ACCESS_DENIED',
                    autoClose: true,
                    autoCloseDelay: 9000
                });
            }
        });

        this.model.off("change", null, this);

        //keep track of what post-save action was chosen in case user chooses to ignore dupes
        this.context.lastSaveAction = null;

        //listen for the select and edit button
        this.context.on('list:dupecheck-list-select-edit:fire', this.editExisting, this);

        //enable buttons if there is an error
        this.model.on('error:validation', this.enableButtons, this);

        //extend the record view definition
        this.meta = _.extend({}, app.metadata.getView(this.module, 'record'), this.meta);

        //enable or disable duplicate check?
        var moduleMetadata = app.metadata.getModule(this.module);
        this.enableDuplicateCheck = (moduleMetadata && moduleMetadata.dupCheckEnabled) || false;

        // If user has no list acl it doesn't make sense to enable dupecheck
        if (!app.acl.hasAccess('list', this.module)) {
            this.enableDuplicateCheck = false;
        }

        var fields = (moduleMetadata && moduleMetadata.fields) ? moduleMetadata.fields : {};

        this.model.relatedAttributes = this.model.relatedAttributes || {};

        var assignedUserField = _.find(fields, function(field) {
            return field.type === 'relate' &&
                (field.name === 'assigned_user_id' || field.id_name === 'assigned_user_id');
        });
        if (assignedUserField) {
            // set the default assigned user as current user, unless we are copying another record
            var isDuplicate = this.model.has('assigned_user_id') && this.model.has('assigned_user_name');
            if (!isDuplicate) {
                this.model.setDefault({
                    'assigned_user_id': app.user.id,
                    'assigned_user_name': app.user.get('full_name')
                });
            }
            this.model.relatedAttributes.assigned_user_id = app.user.id;
            this.model.relatedAttributes.assigned_user_name = app.user.get('full_name');
        }

        // need to reset the default attributes because the plugin may have
        // calculated default values.
        this.on('sugarlogic:initialize', function() {
            this.model.setDefault(this.model.attributes);
        }, this);
    },

    /**
     * Extends in order to set the {@link #action} to `create` while the fields
     * are rendering.
     *
     * This is a temporary fix that will be reviewed in 7.8. The action should
     * be `create` at all times but doing the proper fix may have bad impacts on
     * ACLs/non editable fields. Follow up in SC-4511.
     *
     * @inheritdoc
     */
    _renderFields: function() {
        var current = this.action;
        this.action = 'create';
        this._super('_renderFields');
        this.action = current;
    },

    /**
     * @inheritdoc
     */
    /**
     * Check unsaved changes.
     * This method is called by {@link app.plugins.Editable}.
     *
     * @return {Boolean} `true` if current model contains unsaved changes,
     *  `false` otherwise.
     */
    hasUnsavedChanges: function() {
        var defaults,
            nonDefaultedAttributesChanged,
            defaultedAttributesChanged;

        if (this.resavingAfterMetadataSync) {
            return false;
        }

        defaults = this.model.getDefault() || {};
        nonDefaultedAttributesChanged = !_.isEqual(_.keys(defaults), _.keys(this.model.attributes));
        defaultedAttributesChanged = !_.isEmpty(this.model.changedAttributes(defaults));

        return (this.model.isNew() && (nonDefaultedAttributesChanged || defaultedAttributesChanged));
    },

    /**
     * @inheritdoc
     *
     * Wires up the save buttons.
     */
    delegateButtonEvents: function() {
        this.context.on('button:' + this.saveButtonName + ':click', this.save, this);
        this.context.on('button:' + this.cancelButtonName + ':click', this.cancel, this);
        this.context.on('button:' + this.restoreButtonName + ':click', this.restoreModel, this);
    },

    _render: function () {
        this._super("_render");

        this.setButtonStates(this.STATE.CREATE);

        // Don't need to add dupecheck layout if dupecheck disabled
        if (this.enableDuplicateCheck) {
            this.renderDupeCheckList();
        }

        //SP-1502: Broadcast model changes so quickcreate field can keep track of unsaved changes
        app.events.trigger('create:model:changed', false);
        this.model.on('change', function() {
            app.events.trigger('create:model:changed', this.hasUnsavedChanges());
        }, this);
    },

    /**
     * Defaults to {@link #saveAndClose}.
     */
    save: function() {
        this.saveAndClose();
    },

    /**
     * Save and close drawer
     */
    saveAndClose: function () {
        this.initiateSave(_.bind(function () {
            if (this.closestComponent('drawer')) {
                app.drawer.close(this.context, this.model);
            } else {
                app.navigate(this.context, this.model);
            }
        }, this));
    },

    /**
     * Handle click on the cancel link
     */
    cancel: function () {
        //Clear unsaved changes on cancel.
        app.events.trigger('create:model:changed', false);
        this.$el.off();
        if (app.drawer.count()) {
            app.drawer.close(this.context);
            this._dismissAllAlerts();
        } else {
            app.router.navigate(this.module, {trigger: true});
        }
    },

    /**
     * Handle click on restore to original link
     */
    restoreModel: function () {
        this.model.clear();
        if (this._origAttributes) {
            this.model.set(this._origAttributes);
            this.model.isCopied = true;
        }

        // reset subpanels
        if (this.hasSubpanelModels) {
            // loop through subpanels and call resetCollection on create subpanels
            _.each(this.context.children, function(child) {
                if (child.get('isCreateSubpanel')) {
                    this.context.trigger('subpanel:resetCollection:' + child.get('link'), true);
                }
            }, this);

            // reset the hasSubpanelModels flag
            this.hasSubpanelModels = false;
        }
        
        this.createMode = true;
        if (!this.disposed) {
            this.render();
        }
        this.setButtonStates(this.STATE.CREATE);
    },

    /**
     * Check for possible duplicates before creating a new record
     * @param callback
     */
    initiateSave: function (callback) {
        this.disableButtons();
        async.waterfall([
            _.bind(this.validateSubpanelModelsWaterfall, this),
            _.bind(this.validateModelWaterfall, this),
            _.bind(this.dupeCheckWaterfall, this),
            _.bind(this.createRecordWaterfall, this)
        ], _.bind(function (error) {
            this.enableButtons();
            if (error && error.status == 412 && !error.request.metadataRetry) {
                this.handleMetadataSyncError(error);
            } else if (!error && !this.disposed) {
                this.context.lastSaveAction = null;
                callback();
            }
        }, this));
    },
    /**
     * Check to see if all fields are valid
     * @param callback
     */
    validateModelWaterfall: function(callback) {
        this.model.doValidate(this.getFields(this.module), function(isValid) {
            callback(!isValid);
        });
    },

    /**
     * Check to see if there are subpanel create models on this view
     * And trigger an event to tell the subpanel to validate itself
     *
     * @param callback
     * @return {Mixed}
     */
    validateSubpanelModelsWaterfall: function(callback) {
        this.hasSubpanelModels = false;
        _.each(this.context.children, function(child) {
            if (child.get('isCreateSubpanel')) {
                this.hasSubpanelModels = true;
                this.context.trigger('subpanel:validateCollection:' + child.get('link'), callback, true);
            }
        }, this);

        // If there are no subpanel models, callback false so the waterfall can continue
        if (!this.hasSubpanelModels) {
            return callback(false);
        }
    },

    /**
     * Check for possible duplicate records
     * @param callback
     */
    dupeCheckWaterfall: function (callback) {
        var success = _.bind(function (collection) {
                if (this.disposed) {
                    callback(true);
                }
                if (collection.models.length > 0) {
                    this.handleDuplicateFound(collection);
                    callback(true);
                } else {
                    this.resetDuplicateState();
                    this.disableButtons();
                    callback(false);
                }
            }, this),
            error = _.bind(function(model, e) {
                if (e.status == 412 && !e.request.metadataRetry) {
                    this.handleMetadataSyncError(e);
                } else {
                    callback(true);
                }
            }, this);

        if (this.skipDupeCheck() || !this.enableDuplicateCheck) {
            callback(false);
        } else {
            this.checkForDuplicate(success, error);
        }
    },

    /**
     * Create new record
     * @param callback
     */
    createRecordWaterfall: function (callback) {
        var success = _.bind(function () {
                var acls = this.model.get('_acl');
                if (!_.isEmpty(acls) && acls.access === 'no' && acls.view === 'no') {
                    //This happens when the user creates a record he won't have access to.
                    //In this case the POST request returns a 200 code with empty response and acls set to no.
                    this.alerts.showSuccessButDeniedAccess.call(this);
                    callback(false);
                } else {
                    this._dismissAllAlerts();
                    app.alert.show('create-success', {
                        level: 'success',
                        messages: this.buildSuccessMessage(this.model),
                        autoClose: true,
                        autoCloseDelay: 10000,
                        onLinkClick: function() {
                            app.alert.dismiss('create-success');
                        }
                    });
                    callback(false);
                }
            }, this),
            error = _.bind(function(model, e) {
                if (e.status == 412 && !e.request.metadataRetry) {
                    this.handleMetadataSyncError(e);
                } else {
                    if (e.status == 403) {
                        this.alerts.showNoAccessError.call(this);
                    } else {
                        this.alerts.showServerError.call(this);
                    }
                    callback(true);
                }
            }, this);

        this.saveModel(success, error);
    },

    /**
     * Check the server to see if there are possible duplicate records.
     * @param success
     * @param error
     */
    checkForDuplicate: function (success, error) {
        var options = {
            //Show alerts for this request
            showAlerts: true,
            success: success,
            error: error
        };

        this.context.trigger("dupecheck:fetch:fire", this.model, options);
    },

    /**
     * Duplicate found: display duplicates and change buttons
     */
    handleDuplicateFound: function () {
        this.setButtonStates(this.STATE.DUPLICATE);
        this.dupecheckList.show();
    },

    /**
     * Clear out all things related to duplicate checks
     */
    resetDuplicateState: function () {
        this.setButtonStates(this.STATE.CREATE);
        this.hideDuplicates();
    },

    /**
     * Called when current record is being saved to allow customization of options and params
     * during save
     *
     * Override to return set of custom options
     *
     * @param {Object} options The current set of options that is going to be used.  This is hand for extending
     */
    getCustomSaveOptions: function (options) {
        return {};
    },

    /**
     * Create a new record
     * @param success
     * @param error
     */
    saveModel: function (success, error) {
        var self = this,
            options;
        options = {
            success: success,
            error: error,
            viewed: true,
            relate: (self.model.link) ? true : null,
            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': false,
                'error': false //error callback implements its own error handler
            },
            lastSaveAction: this.context.lastSaveAction
        };
        this.applyAfterCreateOptions(options);

        // Check if this has subpanel create models
        if (this.hasSubpanelModels) {
            _.each(this.context.children, function(child) {
                if (child.get('isCreateSubpanel')) {
                    // create the child collection JSON structure to save
                    var childCollection = {
                            create: []
                        },
                        linkName = child.get('link');
                    if (this.model.has(linkName)) {
                        // the model already has the link name, there must be rollup formulas
                        // on the create form between the model and the subpanel
                        childCollection = this.model.get(linkName);
                        // make sure there is a create key on the childCollection
                        if (!_.has(childCollection, 'create')) {
                            childCollection['create'] = [];
                        }
                    }
                    // loop through the models in the collection and push each model's JSON
                    // data to the 'create' array
                    _.each(child.get('collection').models, function(model) {
                        childCollection.create.push(model.toJSON());
                    }, this);

                    // set the child JSON collection data to the model
                    this.model.set(linkName, childCollection);
                }
            }, this);
        }

        options = _.extend({}, options, self.getCustomSaveOptions(options));
        self.model.save(null, options);
    },

    /**
     * Apply after_create parameters to the URL to specify operations to execute after creating a record.
     * @param options
     */
    applyAfterCreateOptions: function(options) {
        var copiedFromModelId = this.context.get('copiedFromModelId');

        if (copiedFromModelId && this.model.isCopy()) {
            options.params = options.params || {};
            options.params.after_create = {
                copy_rel_from: copiedFromModelId
            };
        }
    },

    /**
     * Using the model returned from the API call, build the success message
     * @param model
     * @return {string}
     */
    buildSuccessMessage: function(model) {
        var modelAttributes,
            successLabel = 'LBL_RECORD_SAVED_SUCCESS',
            successMessageContext;

        //if we have model attributes, use them to build the message, otherwise use a generic message
        if (model && model.attributes) {
            modelAttributes = model.attributes;
        } else {
            modelAttributes = {};
            successLabel = 'LBL_RECORD_SAVED';
        }

        //use the model attributes combined with data from the view to build the success message context
        successMessageContext = _.extend({
            module: this.module,
            moduleSingularLower: app.lang.getModuleName(this.module).toLowerCase()
        }, modelAttributes);

        return app.lang.get(successLabel, this.module, successMessageContext);
    },

    /**
     * Check to see if we should skip duplicate check.
     *
     * Duplicate check should be skipped if we are displaying duplicates or user
     * has switched over to editing an existing duplicate record.
     *
     * @return {boolean}
     */
    skipDupeCheck: function () {
        var skipStates = [this.STATE.DUPLICATE, this.STATE.SELECT];
        return (_.contains(skipStates, this.getCurrentButtonState()));
    },

    /**
     * Clears out field values
     */
    clear: function () {
        this.model.clear();
        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * Make the specified record as the data to be edited, and merge the existing data.
     * @param model
     */
    editExisting: function (model) {
        var origAttributes = this.saveFormData();

        this.model.clear();
        this.model.set(this.extendModel(model, origAttributes));

        if (this.model.link) {
            this.model.link.isNew = false;
        }

        this.createMode = false;
        if (!this.disposed) {
            this.render();
        }
        this.toggleEdit(true);

        this.hideDuplicates();
        this.setButtonStates(this.STATE.SELECT);
    },

    /**
     * Merge the selected record with the data entered in the form
     * @param newModel
     * @param origAttributes
     * @return {Object}
     */
    extendModel: function (newModel, origAttributes) {
        var modelAttributes = _.clone(newModel.attributes);

        _.each(modelAttributes, function (value, key) {
            if (_.isUndefined(value) || _.isNull(value) ||
                ((_.isObject(value) || _.isArray(value) || _.isString(value)) && _.isEmpty(value))) {
                delete modelAttributes[key];
            }
        });

        return _.extend({}, origAttributes, modelAttributes);
    },

    /**
     * Save the data entered in the form
     * @return {Object}
     */
    saveFormData: function () {
        this._origAttributes = _.clone(this.model.attributes);
        return this._origAttributes;
    },

    /**
     * Sets the dupecheck list type
     *
     * @param {String} type view to load
     */
    setDupeCheckType: function(type) {
        this.context.set('dupelisttype', type);
    },

    /**
     * Render duplicate check list table
     */
    renderDupeCheckList: function () {
        this.setDupeCheckType('dupecheck-list-edit');
        this.context.set('collection', this.createDuplicateCollection(this.model));

        if (_.isNull(this.dupecheckList)) {
            this.dupecheckList = app.view.createLayout({
                context: this.context,
                name: 'create-dupecheck',
                module: this.module
            });
            this.dupecheckList.initComponents();
            this.addToLayoutComponents(this.dupecheckList);
        }

        this.$('.headerpane').after(this.dupecheckList.$el);
        this.dupecheckList.hide();
        this.dupecheckList.render();
    },

    /**
     * Add component to layout's component list so it gets cleaned up properly on dispose
     *
     * FIXME: SC-6041 should handle deprecating this method.
     *
     * @param component
     */
    addToLayoutComponents: function (component) {
        this.layout._components.push(component);
    },

    /**
     * If initialized (depends on this.enableDuplicateCheck flag) hides the
     * duplicate list.
     */
    hideDuplicates: function () {
        if (this.dupecheckList) {
            this.dupecheckList.hide();
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (this.dupecheckList) {
            this.dupecheckList.dispose();
        }
        this._super('_dispose');
    },

    /**
     * Disable buttons
     */
    disableButtons: function () {
        this.toggleButtons(false);
    },

    /**
     * Enable buttons
     */
    enableButtons: function () {
        this.toggleButtons(true);
    },

    registerShortcuts: function() {
        this._super('registerShortcuts');

        app.shortcuts.register({
            id: 'Create:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_SAVE',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('a[name=' + this.saveButtonName + ']');
                if ($saveButton.is(':visible') && !$saveButton.hasClass('disabled')) {
                    $saveButton.get(0).click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Create:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('a[name=' + this.cancelButtonName + ']');
                if ($cancelButton.is(':visible') && !$cancelButton.hasClass('disabled')) {
                    $cancelButton.get(0).click();
                }
            }
        });
    },

    /**
     * We don't want the locked fields warning on create
     * @override
     */
    warnLockedFields: _.noop
})
