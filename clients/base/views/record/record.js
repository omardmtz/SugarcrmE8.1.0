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
 * @class View.Views.Base.RecordView
 * @alias SUGAR.App.view.views.BaseRecordView
 * @extends View.View
 */
({
    /**
     * @inheritdoc
     */
    dataView: 'record',

    inlineEditMode: false,

    createMode: false,

    plugins: [
        'SugarLogic',
        'ErrorDecoration',
        'GridBuilder',
        'Editable',
        'Audit',
        'Pii',
        'FindDuplicates',
        'ToggleMoreLess'
    ],

    enableHeaderButtons: true,

    enableHeaderPane: true,

    events: {
        'click .record-edit-link-wrapper': 'handleEdit',
        'click a[name=cancel_button]': '_deprecatedCancelClicked',
        'click [data-action=scroll]': 'paginateRecord',
        'click .record-panel-header': 'togglePanel',
        'click #recordTab > .tab > a:not(.dropdown-toggle)': 'setActiveTab',
        'click .tab .dropdown-menu a': 'triggerNavTab'
    },

    /**
     * Button fields defined in view definition.
     */
    buttons: null,

    /**
     * Button states.
     */
    STATE: {
        EDIT: 'edit',
        VIEW: 'view'
    },

    // current button states
    currentState: null,

    // fields that should not be editable
    noEditFields: null,

    // width of the layout that contains this view
    _containerWidth: 0,

    /**
     * Flag indicating if the model for this view contains fields that are locked.
     *
     * @private
     * @type {boolean}
     */
    _hasLockedFields: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        /**
         * @inheritdoc
         * @property {Object} meta
         * @property {boolean} meta.hashSync Set to `true` to update URL
         *   consistently with the view state (`edit` or `detail`)
         */
        options.meta = _.extend({}, app.metadata.getView(null, 'record'), options.meta);
        options.meta.hashSync = _.isUndefined(options.meta.hashSync) ? true : options.meta.hashSync;
        app.view.View.prototype.initialize.call(this, options);
        this.buttons = {};
        //Adding the favorite and follow fields.
        this.context.addFields(this._getDataFields());

        // FIXME: SC-5650 will handle removing these deprecation warnings in 7.10+
        _.each(this.meta.panels, function(panel) {
            _.each(panel.fields, function(field) {
                if (field.label_css_class) {
                    app.logger.warn('Warning: metadata property "label_css_class" found on field with name "' +
                        field.name + '" is deprecated and will be removed in a future release.');
                }
                if (field.cell_css_class) {
                    app.logger.warn('Warning: metadata property "cell_css_class" found on field with name "' +
                        field.name + '" is deprecated and will be removed in a future release.');
                }
            }, this);
        }, this);

        /**
         * An array of the {@link #alerts alert} names in this view.
         *
         * @property {Array}
         * @protected
         */
        this._viewAlerts = [];

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
        this.alerts = {
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
            showNoAccessError: function() {
                if (!this instanceof app.view.View) {
                    app.logger.error('This method should be invoked by Function.prototype.call(), passing in as argument' +
                    'an instance of this view.');
                    return;
                }
                // dismiss the default error
                app.alert.dismiss('data:sync:error');
                // display no access error
                app.alert.show('server-error', {
                    level: 'error',
                    messages: 'ERR_HTTP_404_TEXT_LINE1'
                });
                // discard any changes before redirect
                this.handleCancel();
                // redirect to list view
                var route = app.router.buildRoute(this.module);
                app.router.navigate(route, {trigger: true});
            }
        };
        this.createMode = this.context.get('create') ? true : false;

        this.action = this.context.get('action') || 'detail';

        this.context.on('change:record_label', this.setLabel, this);
        this.context.set('viewed', true);
        this.model.on('duplicate:before', this.setupDuplicateFields, this);
        // displays error msg when required field is missing
        this.model.on('error:validation', this.alerts.showInvalidModel, this);
        this.on('editable:keydown', this.handleKeyDown, this);
        this.on('editable:mousedown', this.handleMouseDown, this);
        this.on('field:error', this.handleFieldError, this);
        this.model.on('acl:change', this.handleAclChange, this);
        this.context.on('field:disabled', this._togglePencil, this);

        //event register for preventing actions
        // when user escapes the page without confirming deleting
        app.routing.before('route', this.beforeRouteDelete, this);
        $(window).on('beforeunload.delete' + this.cid, _.bind(this.warnDeleteOnRefresh, this));

        this.delegateButtonEvents();

        if (this.createMode) {
            this.model.isNotEmpty = true;
        }

        this.noEditFields = [];
        // properly namespace SHOW_MORE_KEY key
        this.MORE_LESS_KEY = app.user.lastState.key(this.MORE_LESS_KEY, this);

        this.adjustHeaderpane = _.bind(_.debounce(this.adjustHeaderpane, 50), this);
        $(window).on('resize.' + this.cid, this.adjustHeaderpane);
        $(window).on('resize.' + this.cid, _.bind(this.overflowTabs, this));

        // initialize tab view after the component is attached to DOM
        this.on('append', function() {
            this.overflowTabs();
            this.handleActiveTab();
        }, this);

        this.on('render', this.registerShortcuts, this);
    },

    /**
     * Handler for when the ACLs change on the model. Toggles the `hide` class
     * on the pencil wrapper for each of the fields on this view that had ACL
     * changes.
     * Hide the wrapper if no access to edit.
     *
     * @param {Object} diff The diff object of fields with ACL changes.
     */
    handleAclChange: function(diff) {
        var editAccess = app.acl.hasAccessToModel('edit', this.model);

        this._setNoEditFields();
        this.setEditableFields();

        var noEditFieldsMap = _.object(this.noEditFields, _.values(this.noEditFields));
        var $pencils = this.$('[data-wrapper=edit]');

        _.each($pencils, function(pencilEl) {
            var $pencilEl = $(pencilEl);
            var field = $pencilEl.data('name');

            if (editAccess && !diff[field]) {
                return;
            }

            var isEditable = _.isUndefined(noEditFieldsMap[field]);
            $pencilEl.toggleClass('hide', !isEditable);

            if (this.action === 'edit') {
                $pencilEl.closest('.record-cell').toggleClass('edit', isEditable);
            }
        }, this);
    },

    /**
     * Shows or hides the edit pencil icon for a field.
     *
     * @param {string} fieldName The field name.
     * @param {boolean} hide `true` to hide the pencil, `false` to show it.
     * @private
     */
    _togglePencil: function(fieldName, hide) {
        var isEditable = !_.contains(this.noEditFields, fieldName) && app.acl.hasAccessToModel('edit', this.model);

        if (!hide && !isEditable) {
            return;
        }

        this.$('span.record-edit-link-wrapper[data-name=' + fieldName + ']').toggleClass('hide', hide);
    },

    /**
     * Go through the field controllers and set the locked states accordingly.
     */
    handleLockedFields: function() {
        var self = this;

        // Reset the locked field state
        this._setLockedFieldFlag(false);

        var lockedFields = this.model.get('locked_fields');

        // Loop and check locked field state of each field
        _.each(this.$('.record-lock-link-wrapper[data-name]'), function(el) {
            var $el = $(el);
            var fieldName = $el.data('name');

            // No field name, nothing to do
            if (fieldName == '') {
                return;
            }

            // Get the field object
            var field = this.getField(fieldName);

            // Is the current field locked?
            var isLocked = _.contains(lockedFields, fieldName);

            // Special handling for fieldsets
            if (field.fields) {
                var hasLockedChildField = false;
                isLocked = true;
                _.each(field.fields, function(fieldSetField) {
                    // Some fieldsets have fields that are only for viewing, like the
                    // `copy` field on alternate addresses. Those should be filtered
                    // out of the fields list.
                    if (_.isUndefined(this.model.get(fieldSetField.name))) {
                        return;
                    }

                    var isChildLocked = _.contains(lockedFields, fieldSetField.name);
                    hasLockedChildField = hasLockedChildField || isChildLocked;

                    // A fieldset is locked when all of its actual fields are locked
                    if (!isChildLocked) {
                        isLocked = false;
                    }
                }, this);
            }

            // Set the flag that says if we have locked fields
            this._setLockedFieldFlag(this.hasLockedFields() || isLocked || hasLockedChildField);

            // Handle toggling the class
            $el.toggleClass('hide', !isLocked);
        }, this);

        // Show the locked field warning if there is one
        if (this.hasLockedFields()) {
            this.warnLockedFields();
        }
    },

    /**
     * Returns the flag that tells whether this object has locked fields or not
     * @return {boolean}
     */
    hasLockedFields: function() {
        return this._hasLockedFields;
    },

    /**
     * Sets the locked field flag
     * @param {boolean} setFlag
     * @private
     */
    _setLockedFieldFlag: function(setFlag) {
        this._hasLockedFields = setFlag;
    },

    /**
     * Alert warning if there are locked fields on the model.
     */
    warnLockedFields: function() {
        if (this.getCurrentButtonState() !== this.STATE.EDIT) {
            return;
        }

        if (this.context.get('lockedFieldsWarning') === false) {
            this.context.set('lockedFieldsWarning', true);
        } else {
            app.alert.show('record_locked_field_warning', {
                level: 'warning',
                messages: 'LBL_LOCKED_FIELD_RECORD_VIEW_WARNING',
                autoClose: true,
                autoCloseDelay: 5000
            });
        }
    },

    /**
     * Compare with last fetched data and return true if model contains changes.
     *
     * Check changes for fields that are editable only.
     *
     * {@link app.plugins.view.editable}
     *
     * @return {Boolean} `true` if current model contains unsaved changes, otherwise `false`.
     */
    hasUnsavedChanges: function() {
        var changedAttributes,
            editableFieldNames = [],
            unsavedFields,
            self = this,
            setAsEditable = function(fieldName) {
                if (fieldName && _.indexOf(self.noEditFields, fieldName) === -1) {
                    editableFieldNames.push(fieldName);
                }
            };

        if (this.resavingAfterMetadataSync)
            return false;

        changedAttributes = this.model.changedAttributes(this.model.getSynced());

        if (_.isEmpty(changedAttributes)) {
            return false;
        }

        // get names of all editable fields on the page including fields in a fieldset
        _.each(this.meta.panels, function(panel) {
            _.each(panel.fields, function(field) {
                if (!field.readonly) {
                    setAsEditable(field.name);
                    if (field.fields && _.isArray(field.fields)) {
                        _.each(field.fields, function(field) {
                            setAsEditable(field.name);
                        });
                    }
                }
            });
        });

        // check whether the changed attributes are among the editable fields
        unsavedFields = _.intersection(_.keys(changedAttributes), editableFieldNames);

        return !_.isEmpty(unsavedFields);
    },

    /**
     * Called when current record is being duplicated to allow customization of
     * fields that will be copied into new record.
     *
     * Override to setup the fields on this bean prior to being displayed in
     * Create dialog.
     *
     * @param {Object} prefill Bean that will be used for new record.
     * @template
     */
    setupDuplicateFields: function(prefill) {
    },

    setLabel: function(context, value) {
        this.$('.record-label[data-name="' + value.field + '"]').text(value.label);
    },

    /**
     * Called each time a validation pass is completed on the model.
     *
     * Enables the action button and calls {@link #handleSave} if the model is
     * valid.
     *
     * @param {boolean} isValid TRUE if model is valid.
     */
    validationComplete: function(isValid) {
        this.toggleButtons(true);
        if (isValid) {
            this.handleSave();
        }
    },

    delegateButtonEvents: function() {
        this.context.on('button:edit_button:click', this.editClicked, this);
        this.context.on('button:save_button:click', this.saveClicked, this);
        this.context.on('button:delete_button:click', this.deleteClicked, this);
        this.context.on('button:duplicate_button:click', this.duplicateClicked, this);
        this.context.on('button:cancel_button:click', this.cancelClicked, this);
    },

    _render: function() {
        this._buildGridsFromPanelsMetadata(this.meta.panels);
        if (this.meta && this.meta.panels) {
            this._initTabsAndPanels();
        }

        app.view.View.prototype._render.call(this);

        if (this.context.get('record_label')) {
            this.setLabel(this.context, this.context.get('record_label'));
        }

        // Field labels in headerpane should be hidden on view but displayed in edit and create
        _.each(this.fields, function(field) {
            // some fields like 'favorite' is readonly by default, so we need to remove edit-link-wrapper
            if (field.def.readonly && field.name && -1 == _.indexOf(this.noEditFields, field.name)) {
                this.$('.record-edit-link-wrapper[data-name=' + field.name + ']').remove();
            }
        }, this);

        if (this.action === 'edit') {
            this.setButtonStates(this.STATE.EDIT);
            this.toggleEdit(true);
        } else {
            this.setButtonStates(this.STATE.VIEW);
            if (this.createMode) {
                this.toggleEdit(true);
            }
        }

        // initialize tab view only if the component is attached to DOM,
        // otherwise it's initialized partially and cannot be properly
        // re-initialized after the component is attached to DOM
        if ($.contains(document.documentElement, this.$el[0])) {
            this.handleActiveTab();
            this.overflowTabs();
        }
    },

    _renderField: function(field, $fieldEl) {
        // When we render the view, we need to enforce `action`
        // to be 'detail' if the field is non editable.
        // This is due to how View.Field#_loadTemplate currently works.
        // FIXME SC-6037: Will remove this hack.
        if (!_.contains(this.editableFields, field)) {
            field.action = 'detail';
            // Set viewName to `detail` if it was set to `edit` (because the field is non-editable)
            // but if it is not `edit` (hardcoded e.g. preview template), we want to keep it as it was.
            if (field.options.viewName === 'edit') {
                field.options.viewName = 'detail';
            }
        }

        this._super('_renderField', [field, $fieldEl]);
    },

    /**
     * Handles initiation of Tabs and Panels view upon render
     * @private
     */
    _initTabsAndPanels: function() {
        this.meta.firstPanelIsTab = this.checkFirstPanel();
        this.meta.lastPanelIndex = this.meta.panels.length - 1;

        _.each(this.meta.panels, function(panel, i) {
            if (panel.header) {
                this.meta.firstNonHeaderPanelIndex = (i + 1);
            }
        }, this);

        // Tell the view to use Tabs and Panels view if either there exists a tab or if the number of panels isn't
        // equivalent to the amount expected for Business Card view (2 panels + possibly 1 if header exists)
        var headerExists = 0;
        if (_.first(this.meta.panels).header) {
            headerExists = 1;
        }

        this.meta.useTabsAndPanels = false;

        //Check if there are any newTabs
        for (i = headerExists; i < this.meta.panels.length; i++) {
            if (this.meta.panels[i].newTab) {
                this.meta.useTabsAndPanels = true;
            }
        }

        //Check for panel number
        if (this.meta.panels.length > (2 + headerExists)) {
            this.meta.useTabsAndPanels = true;
        }

        // set states
        _.each(this.meta.panels, function(panel){
            var panelKey = app.user.lastState.key(panel.name+':tabState', this);
            var panelState = app.user.lastState.get(panelKey);
            panel.panelState = panelState || panel.panelDefault;
        }, this);
    },
    /**
     * handles setting active tab
     */
    handleActiveTab: function() {
        var activeTabHref = this.getActiveTab(),
            activeTab = this.$('#recordTab > .tab > a[href="'+activeTabHref+'"]');

        // Always show first tab if we're on the create view
        if (this.createMode) {
            this.$('#recordTab a:first').tab('show');
            return;
        }

        if (activeTabHref && activeTab) {
            activeTab.tab('show');
        } else if (this.meta.useTabsAndPanels && this.checkFirstPanel()) {
            // If tabs and no last state set, show first tab on render
            this.$('#recordTab a:first').tab('show');
        }
    },
    /**
     * Gets the active tab in the user last state
     * @return {String} The active tab id in the user's last state.
     */
    getActiveTab: function() {
        var activeTabHref = app.user.lastState.get(app.user.lastState.key('activeTab', this));

        // Set to first tab by default
        if (!activeTabHref) {
            activeTabHref = this.$('#recordTab > .tab:first-child > a').attr('href') || '';
            app.user.lastState.set(
                app.user.lastState.key('activeTab', this),
                activeTabHref.substring(0, activeTabHref.indexOf(this.cid))
            );
        }
        else {
            activeTabHref += this.cid;
        }
        return activeTabHref;
    },
    /**
     * sets active tab in user last state
     * @param {Event} event
     */
    setActiveTab: function(event) {
        if (this.createMode) {
            return;
        }
        var tabTarget = this.$(event.currentTarget).attr('href'),
            tabKey = app.user.lastState.key('activeTab', this),
            cidIndex = tabTarget.indexOf(this.cid);

        tabTarget = tabTarget.substring(0, cidIndex);
        app.user.lastState.set(tabKey, tabTarget);
    },
    /**
     * saves panel state in user last state
     * @param {String} panelID
     * @param {String} state
     */
    savePanelState: function(panelID, state) {
        if (this.createMode) {
            return;
        }
        var panelKey = app.user.lastState.key(panelID+':tabState', this);
        app.user.lastState.set(panelKey, state);
    },

    /**
     * Parses through an array of panels metadata and sets some of them
     * as no edit fields.
     *
     * FIXME: SC-3940, remove this call to _setNoEditFields when we merge
     * master_platform into master, as this was fixed by SC-3908.
     *
     * @param {Array} [panels] The panels to parse. This default to
     *   `this.meta.panels`.
     * @private
     */
    _setNoEditFields: function(panels) {
        var self = this;
        panels = panels || this.meta.panels;

        delete this.noEditFields;
        this.noEditFields = [];

        _.each(panels, function(panel) {
            _.each(panel.fields, function(field, index) {
                var keys = _.keys(field);
                // Make filler fields readonly
                if (keys.length === 1 && keys[0] === 'span') {
                    field.readonly = true;
                }

                // disable the pencil icon if the user doesn't have ACLs
                if (field.fields) {
                    // Some fieldsets have fields that are only for viewing, like the
                    // `copy` field on alternate addresses. Those should be filtered
                    // out of the fields list.
                    var fieldSetFields = _.filter(field.fields, function(fieldSetField) {
                        return !_.isUndefined(self.model.get(fieldSetField.name));
                    });

                    if (field.readonly || _.every(fieldSetFields, function(f) {
                        return !app.acl.hasAccessToModel('edit', this.model, f.name);
                    }, this)) {
                        this.noEditFields.push(field.name);
                    }
                } else if (field.readonly || !app.acl.hasAccessToModel('edit', this.model, field.name)) {
                    this.noEditFields.push(field.name);
                }
            }, this);
        }, this);
    },

    /**
     * Returns a list of fields that are not buttons of the view.
     *
     * @private
     */
    _getNonButtonFields: function() {
        return _.filter(this.fields, _.bind(function(field) {
            if (field.name) {
                return !this.buttons[field.name];
            }

            return true;
        }, this));
    },

    /**
     * Uses {@link app.plugins.Editable} to
     * set the internal property of {@link #editableFields}.
     */
    setEditableFields: function() {
        this.editableFields = this.getEditableFields(this._getNonButtonFields(), this.noEditFields);
    },

    /**
     * Registers fields as buttons.
     *
     * @deprecated Since 7.10.
     */
    initButtons: function() {
        app.logger.warn('`BaseRecordView#initButtons` is deprecated since 7.10 and will be ' +
            'removed in a future release.');
        if (this.options.meta && this.options.meta.buttons) {
            _.each(this.options.meta.buttons, function(button) {
                this.registerFieldAsButton(button.name);
            }, this);
        }
    },

    /**
     * Registers fields as buttons.
     *
     * @protected
     */
    _initButtons: function() {
        buttons = this.meta.buttons;
        _.each(buttons, function(button) {
            this.registerFieldAsButton(button.name);
        }, this);
    },

    showPreviousNextBtnGroup: function() {
        var listCollection = this.context.get('listCollection') || new app.data.createBeanCollection(this.module);
        var recordIndex = listCollection.indexOf(listCollection.get(this.model.id));
        if (listCollection && listCollection.models && listCollection.models.length <= 1) {
            this.showPrevNextBtnGroup = false;
        } else {
            this.showPrevNextBtnGroup = true;
        }
        if (this.collection && listCollection.length !== 0) {
            this.showPrevious = listCollection.hasPreviousModel(this.model);
            this.showNext = listCollection.hasNextModel(this.model);
        }
    },

    registerFieldAsButton: function(buttonName) {
        var button = this.getField(buttonName);
        if (button) {
            this.buttons[buttonName] = button;
        }
    },

    _renderHtml: function() {
        this.showPreviousNextBtnGroup();
        app.view.View.prototype._renderHtml.call(this);
        this._initButtons();
        this.setEditableFields();
        this.adjustHeaderpane();
    },

    bindDataChange: function() {
        // Handle locked field changes
        this.model.on('change:locked_fields', this.handleLockedFields, this);
        this.model.on('change', function() {
            if (this.inlineEditMode) {
                this.setButtonStates(this.STATE.EDIT);
            }
        }, this);
    },

    /**
     * Enables or disables the action buttons that are currently shown on the
     * page. Toggles the `.disabled` class by default.
     *
     * @param {boolean} [enable=false] Whether to enable or disable the action
     *   buttons. Defaults to `false`.
     */
    toggleButtons: function(enable) {
        var state = !_.isUndefined(enable) ? !enable : false;

        _.each(this.buttons, function(button) {
            var showOn = button.def.showOn;
            if (_.isUndefined(showOn) || this.currentState === showOn) {
                button.setDisabled(state);
            }
        }, this);
    },

    duplicateClicked: function() {
        var self = this,
            prefill = app.data.createBean(this.model.module);

        prefill.copy(this.model);
        this._copyNestedCollections(this.model, prefill);
        self.model.trigger('duplicate:before', prefill);
        prefill.unset('id');
        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                model: prefill,
                copiedFromModelId: this.model.get('id')
            }
        }, function(context, newModel) {
            if (newModel && newModel.id) {
                app.router.navigate(self.model.module + '/' + newModel.id, {trigger: true});
            }
        });

        prefill.trigger('duplicate:field', self.model);
    },

    /**
     * Clones the attributes that are collections by way of the
     * {@link VirtualCollection} plugin.
     *
     * This guarantees that all related models in nested collection are copied
     * instead of only the ones that have already been fetched.
     *
     * All models of the collection on the source model are fetched
     * asynchronously and then added to the same collection on the target model
     * once there are no more models to retrieve. Note that this leaves open
     * the possibility for a race condition where the user clicks the Save
     * button on the Create View before all models have been received.
     *
     * @param {Data.Bean} source
     * @param {Data.Bean} target
     * @private
     */
    _copyNestedCollections: function(source, target) {
        var collections, view;

        // only model's that utilize the VirtualCollection plugin support this
        // functionality
        if (!_.isFunction(source.getCollectionFieldNames)) {
            return;
        }

        // avoid using the ambiguous `this` since there are references to many
        // objects in this method: view, field, model, collection, source,
        // target, etc.
        view = this;

        /**
         * Removes the `_action` attribute from a model when cloning it.
         *
         * @param {Data.Bean} model
         * @return {Data.Bean}
         */
        function cloneModel(model) {
            var attributes = _.chain(model.attributes).clone().omit('_action').value();
            return app.data.createBean(model.module, attributes);
        }

        /**
         * Copies all of the models from a collection to the same collection on
         * the target model.
         *
         * @param collection
         */
        function copyCollection(collection) {
            var field, relatedFields, options;

            /**
             * Adds all of the records from the source collection to the same
             * collection on the target model.
             *
             * @param {VirtualCollection} sourceCollection
             * @param {Object} [options]
             */
            function done(sourceCollection, options) {
                var targetCollection = target.get(collection.fieldName);

                if (!targetCollection) {
                    return;
                }

                targetCollection.add(sourceCollection.map(cloneModel));
            }

            field = view.getField(collection.fieldName, source);
            relatedFields = [];

            if (field.def.fields) {
                relatedFields = _.map(field.def.fields, function(def) {
                    return _.isObject(def) ? def.name : def;
                });
            }

            options = {success: done};

            // request the related fields from the field definition if possible
            if (relatedFields.length > 0) {
                options.fields = relatedFields;
            }

            collection.fetchAll(options);
        }

        // get all attributes from the source model that are collections
        collections = _.intersection(source.getCollectionFieldNames(), _.keys(source.attributes));

        _.each(collections, function(name) {
            copyCollection(source.get(name));
        });
    },

    editClicked: function() {
        this.setButtonStates(this.STATE.EDIT);
        this.action = 'edit';
        this.toggleEdit(true);
        this.setRoute('edit');
    },

    saveClicked: function() {
        // Disable the action buttons.
        this.toggleButtons(false);
        var allFields = this.getFields(this.module, this.model);
        var fieldsToValidate = {};
        var erasedFields = this.model.get('_erased_fields');
        for (var fieldKey in allFields) {
            if (app.acl.hasAccessToModel('edit', this.model, fieldKey) &&
                (!_.contains(erasedFields, fieldKey) || this.model.get(fieldKey) || allFields[fieldKey].id_name)) {
                _.extend(fieldsToValidate, _.pick(allFields, fieldKey));
            }
        }
        this.model.doValidate(fieldsToValidate, _.bind(this.validationComplete, this));
    },

    /**
     * Handles when the cancel_button view event is triggered.
     *
     * FIXME: This method will be removed as part of BR-3945
     *
     * @private
     *
     * @deprecated Since 7.7. Will be removed in 7.9.
     *   Use the `MetadataEventDriven` plugin events from the
     *   `record.php` button metadata instead.
     */
    _deprecatedCancelClicked: function() {
        var cancelBtn = this.getField('cancel_button');
        if (!cancelBtn || !cancelBtn.def || !cancelBtn.def.events) {
            app.logger.warn(this.module + ': Invoking the cancel_button from `this.events` has been deprecated' +
                ' since 7.7. This handler will be removed in 7.9. Please use the `MetadataEventDriven` plugin' +
                ' events from the \'record.php\' button metadata instead.');
            this.cancelClicked.apply(this, arguments);
        }
    },

    cancelClicked: function() {
        this.setButtonStates(this.STATE.VIEW);
        this.action = 'detail';
        this.handleCancel();
        this.clearValidationErrors(this.editableFields);
        this.setRoute();
        this.unsetContextAction();
    },

    deleteClicked: function(model) {
        this.warnDelete(model);
    },

    /**
     * Render fields into either edit or view mode.
     *
     * @param {Boolean} isEdit `true` to set the field in edit mode, `false`
     *   otherwise.
     */
    toggleEdit: function(isEdit) {
        var self = this;
        this.$('.record-lock-link').toggleClass('record-lock-link-on', isEdit);
        if (this.hasLockedFields()) {
            this.warnLockedFields();
        }
        this.toggleFields(this.editableFields, isEdit, function() {
            self.toggleViewButtons(isEdit);
            self.adjustHeaderpaneFields();
        });
    },

    /**
     * Handler for intent to edit. This handler is called both as a callback
     * from click events, and also triggered as part of tab focus event.
     *
     * @param {Event} e Event object (should be click event).
     * @param {jQuery} cell A jQuery node cell of the target node to edit.
     */
    handleEdit: function(e, cell) {
        var target,
            cellData,
            field;

        if (e) { // If result of click event, extract target and cell.
            target = this.$(e.target);
            cell = target.parents('.record-cell');
        }

        cellData = cell.data();
        field = this.getField(cellData.name);

        // Set Editing mode to on.
        this.inlineEditMode = true;

        this.setButtonStates(this.STATE.EDIT);

        this.toggleField(field);

        if (cell.closest('.headerpane').length > 0) {
            this.toggleViewButtons(true);
            this.adjustHeaderpaneFields();
        }
    },

    /**
     * Hide view specific button during edit.
     *
     * @param {Boolean} isEdit `true` to hide some specific buttons, `false`
     *   otherwise.
     *
     * FIXME this should be done in a more generic way (field or metadata
     * property).
     */
    toggleViewButtons: function(isEdit) {
        this.$('.headerpane .btn-group-previous-next').toggleClass('hide', isEdit);
    },

    handleSave: function() {
        if (this.disposed) {
            return;
        }
        this._saveModel();
        this.$('.record-save-prompt').hide();

        if (!this.disposed) {
            this.setButtonStates(this.STATE.VIEW);
            this.action = 'detail';
            this.setRoute();
            this.unsetContextAction();
            this.toggleEdit(false);
            this.inlineEditMode = false;
        }
    },

    _saveModel: function() {
        var options,
            successCallback = _.bind(function() {
                // Loop through the visible subpanels and have them sync. This is to update any related
                // fields to the record that may have been changed on the server on save.
                _.each(this.context.children, function(child) {
                    if (child.get('isSubpanel') && !child.get('hidden')) {
                        if (child.get('collapsed')) {
                            child.resetLoadFlag({recursive: false});
                        } else {
                            child.reloadData({recursive: false});
                        }
                    }
                });
                if (this.createMode) {
                    app.navigate(this.context, this.model);
                } else if (!this.disposed && !app.acl.hasAccessToModel('edit', this.model)) {
                    //re-render the view if the user does not have edit access after save.
                    this.render();
                }
            }, this);

        //Call editable to turn off key and mouse events before fields are disposed (SP-1873)
        this.turnOffEvents(this.fields);

        options = {
            showAlerts: true,
            success: successCallback,
            error: _.bind(function(model, error) {
                if (error.status === 412 && !error.request.metadataRetry) {
                    this.handleMetadataSyncError(error);
                } else if (error.status === 409) {
                    app.utils.resolve409Conflict(error, this.model, _.bind(function(model, isDatabaseData) {
                        if (model) {
                            if (isDatabaseData) {
                                successCallback();
                            } else {
                                this._saveModel();
                            }
                        }
                    }, this));
                } else if (error.status === 403 || error.status === 404) {
                    this.alerts.showNoAccessError.call(this);
                } else {
                    this.editClicked();
                }
            }, this),
            lastModified: this.model.get('date_modified'),
            viewed: true
        };

        options = _.extend({}, options, this.getCustomSaveOptions(options));

        this.model.save({}, options);
    },

    handleMetadataSyncError: function(error) {
        var self = this;
        //On a metadata sync error, retry the save after the app is synced
        self.resavingAfterMetadataSync = true;
        app.once('app:sync:complete', function() {
            error.request.metadataRetry = true;
            self.model.once('sync', function() {
                self.resavingAfterMetadataSync = false;
                //self.model.changed = {};
                app.router.refresh();
            });
            //add a new success callback to refresh the page after the save completes
            error.request.execute(null, app.api.getMetadataHash());
        });
    },

    getCustomSaveOptions: function(options) {
        return {};
    },

    handleCancel: function() {
        this.inlineEditMode = false;
        this.model.revertAttributes();
        this.toggleEdit(false);
        this._dismissAllAlerts();
    },

    /**
     * Pre-event handler before current router is changed.
     *
     * @return {Boolean} `true` to continue routing, `false` otherwise.
     */
    beforeRouteDelete: function() {
        if (this._modelToDelete) {
            this.warnDelete(this._modelToDelete);
            return false;
        }
        return true;
    },

    /**
     * Formats the messages to display in the alerts when deleting a record.
     *
     * @return {Object} The list of messages.
     * @return {string} return.confirmation Confirmation message.
     * @return {string} return.success Success message.
     */
    getDeleteMessages: function() {
        var messages = {};
        var model = this.model;
        var name = Handlebars.Utils.escapeExpression(this._getNameForMessage(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' "' + name + '"';

        messages.confirmation = app.utils.formatString(
            app.lang.get('NTC_DELETE_CONFIRMATION_FORMATTED', this.module),
            [context]
        );
        messages.success = app.utils.formatString(app.lang.get('NTC_DELETE_SUCCESS'), [context]);
        return messages;
    },

    /**
     * Retrieves the name of a record
     *
     * @param {Data.Bean} model The model concerned.
     * @return {string} name of the record.
     */
    _getNameForMessage: function(model) {
        return app.utils.getRecordName(model);
    },

    /**
     * Popup dialog message to confirm delete action
     */
    warnDelete: function(model) {
        var self = this;
        this._modelToDelete = model;

        self._targetUrl = Backbone.history.getFragment();
        //Replace the url hash back to the current staying page
        if (self._targetUrl !== self._currentUrl) {
            app.router.navigate(self._currentUrl, {trigger: false, replace: true});
        }

        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: self.getDeleteMessages().confirmation,
            onConfirm: _.bind(self.deleteModel, self),
            onCancel: function() {
                self._modelToDelete = false;
            }
        });
    },

    /**
     * Popup browser dialog message to confirm delete action
     *
     * @return {String} The message to be displayed in the browser dialog.
     */
    warnDeleteOnRefresh: function() {
        if (this._modelToDelete) {
            return this.getDeleteMessages().confirmation;
        }
    },

    /**
     * Delete the model once the user confirms the action
     */
    deleteModel: function() {
        var self = this;

        self.model.destroy({
            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': {
                    messages: self.getDeleteMessages().success
                }
            },
            success: function() {
                var redirect = self._targetUrl !== self._currentUrl;

                self.context.trigger('record:deleted', self._modelToDelete);

                self._modelToDelete = false;

                if (redirect) {
                    self.unbindBeforeRouteDelete();
                    //Replace the url hash back to the current staying page
                    app.router.navigate(self._targetUrl, {trigger: true});
                    return;
                }

                app.router.navigate(self.module, {trigger: true});
            }
        });

    },

    /**
     * Key handlers for inline edit mode.
     *
     * Jump into the next or prev target field if `tab` key is pressed.
     * Calls {@link app.plugins.Editable#nextField} to go to next/prev field.
     *
     * @param {Event} e Event object.
     * @param {View.Field} field Current focused field (field in inline-edit mode).
     */
    handleKeyDown: function(e, field) {
        var whichField = e.shiftKey ? 'prevField' : 'nextField';

        if (e.which === 9) { // If tab
            e.preventDefault();
            this.nextField(field, whichField);
            if (field.$el.closest('.headerpane').length > 0) {
                this.toggleViewButtons(false);
                this.adjustHeaderpaneFields();
            }
            if (field[whichField] && field[whichField].$el.closest('.headerpane').length > 0) {
                this.toggleViewButtons(true);
                this.adjustHeaderpaneFields();
            }
        }
    },

    /**
     * Adjust headerpane fields when they change to view mode
     */
    handleMouseDown: function() {
        this.toggleViewButtons(false);
        this.adjustHeaderpaneFields();
    },

    /**
     * Handles a field validation error for record views.
     * @param field
     * @param {Boolean} hasError True if a field has an error, false if the field is clearing it's error state
     */
    handleFieldError: function(field, hasError) {
        if(!hasError) {
            return;
        }

        var tabLink,
            fieldTab   = field.$el.closest('.tab-pane'),
            fieldPanel = field.$el.closest('.record-panel-content');

        if (field.view.meta && field.view.meta.useTabsAndPanels) {
            // If field's panel is a tab, switch to the tab that contains the field with the error
            if (fieldTab.length > 0) {
                tabLink = this.$('[href="#' + fieldTab.attr('id') + '"][data-toggle="tab"]');
                tabLink.tab('show');
                // Put a ! next to the tab if one doesn't already exist
                if (tabLink.find('.fa-exclamation-circle').length === 0) {
                    tabLink.append(' <i class="fa fa-exclamation-circle tab-warning"></i>');
                }
            }

            // If field's panel is a panel that is closed, open it and change arrow
            if (fieldPanel && fieldPanel.is(':hidden')) {
                fieldPanel.toggle();
                var fieldPanelArrow = fieldPanel.prev().find('i');
                fieldPanelArrow.toggleClass('fa-chevron-up fa-chevron-down');
            }
        } else if (field.$el.is(':hidden')) {
            this.$('.more[data-moreless]').trigger('click');
            app.user.lastState.set(this.SHOW_MORE_KEY, this.$('.less[data-moreless]'));
        }
        else if (field.$el.closest('.panel_hidden.hide').length > 0) {
            this.toggleMoreLess(this.MORE_LESS_STATUS.MORE, true);
        }
    },

    /**
     * Show/hide buttons depending on the state defined for each buttons in the
     * metadata.
     *
     * @param {String} state The {@link #STATE} of the current view.
     */
    setButtonStates: function(state) {
        this.currentState = state;

        _.each(this.buttons, function(field) {
            var showOn = field.def.showOn;
            if (_.isUndefined(showOn) || (showOn === state)) {
                field.show();
            } else {
                field.hide();
            }
        });

        this.toggleButtons(true);
    },

    /**
     * Get the current button state.
     * @return {string} The current button state
     */
    getCurrentButtonState: function() {
        return this.currentState;
    },

    /**
     * Set the title in the header pane.
     *
     * @param {String} title The new title to set on the headerpane.
     *
     * FIXME this should be done with the header pane view + re-render it.
     */
    setTitle: function(title) {
        var $title = this.$('.headerpane .module-title');
        if ($title.length > 0) {
            $title.text(title);
        } else {
            this.$('.headerpane h1').prepend('<div class="record-cell"><span class="module-title">' + title + '</span></div>');
        }
    },

    /**
     * Detach the event handlers for warning delete
     */
    unbindBeforeRouteDelete: function() {
        app.routing.offBefore('route', this.beforeRouteDelete, this);
        $(window).off('beforeunload.delete' + this.cid);
    },

    _dispose: function() {
        this.unbindBeforeRouteDelete();
        _.each(this.editableFields, function(field) {
            field.nextField = null;
            field.prevField = null;
        });
        this.buttons = null;
        this.editableFields = null;
        this.off('editable:keydown', this.handleKeyDown, this);
        $(window).off('resize.' + this.cid);
        app.view.View.prototype._dispose.call(this);
    },

    _buildGridsFromPanelsMetadata: function(panels) {
        var lastTabIndex = 0;
        this.noEditFields = [];

        _.each(panels, function(panel) {
            // it is assumed that a field is an object but it can also be a string
            // while working with the fields, might as well take the opportunity to check the user's ACLs for the field
            _.each(panel.fields, function(field, index) {
                if (_.isString(field)) {
                    panel.fields[index] = field = {name: field};
                }

                var keys = _.keys(field);

                // Make filler fields readonly
                if (keys.length === 1 && keys[0] === 'span') {
                    field.readonly = true;
                }

                // disable the pencil icon if the user doesn't have ACLs
                if (field.fields && _.isArray(field.fields)) {
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
     * Handles click event on next/previous button of record.
     * @param {Event} evt
     */
    paginateRecord: function(evt) {
        var el = $(evt.currentTarget),
            data = el.data();
        if (data.id) {
            var list = this.context.get('listCollection'),
                model = list.get(data.id);
            this._doPaginate(model, data.actionType);
        }
    },
    /**
     * paginates record view depeding on direction and model
     * @param {Object} model
     * @param {String} actionType
     * @private
     */
    _doPaginate: function(model, actionType) {
        var list = this.context.get('listCollection');
        switch (actionType) {
            case 'next':
                list.getNext(model, this.navigateModel);
                break;
            case 'prev':
                list.getPrev(model, this.navigateModel);
                break;
            default:
                this._disablePagination(el);
        }
    },
    /**
     * Callback for navigate to new model.
     *
     * @param {Data.Bean} model model New model to navigate.
     * @param {String} actionType actionType Side of navigation (prev/next).
     */
    navigateModel: function(model, actionType) {
        if (model && model.id) {
            if (app.acl.hasAccessToModel('view', model)) {
                app.router.navigate(app.router.buildRoute(this.module, model.id), {trigger: true});
            } else {
                this._doPaginate(model, actionType);
            }
        } else {
            var el = this.$el.find('[data-action=scroll][data-action-type=' + actionType + ']');
            this._disablePagination(el);
        }
    },

    /**
     * Updates url without triggering the router.
     *
     * @param {string} action Action to pass when building the route
     *   with {@link Core.Router#buildRoute}.
     */
    setRoute: function(action) {
        if (!this.meta.hashSync) {
            return;
        }
        app.router.navigate(app.router.buildRoute(this.module, this.model.id, action), {trigger: false});
    },

    /**
     * Unsets the `action` attribute from the current context.
     *
     * Once 'action' is unset, the action is 'detail' and the view will render
     * next in detail mode.
     */
    unsetContextAction: function() {
            this.context.unset('action');
    },

    /**
     * Disabling pagination if we can't paginate.
     * @param {Object} el Element to disable pagination on.
     */
    _disablePagination: function(el) {
        app.logger.error('Wrong data for record pagination. Pagination is disabled.');
        el.addClass('disabled');
        el.data('id', '');
    },

    /**
     * Adjust headerpane such that certain fields can be shown with ellipsis
     */
    adjustHeaderpane: function() {
        this.setContainerWidth();
        this.adjustHeaderpaneFields();
    },

    /**
     * Get the width of the layout container
     */
    getContainerWidth: function() {
        return this._containerWidth;
    },

    /**
     * Set the width of the layout container
     */
    setContainerWidth: function() {
        this._containerWidth = this._getParentLayoutWidth(this.layout);
    },

    /**
     * Get the width of the parent layout that contains `getPaneWidth()`
     * method.
     *
     * @param {View.Layout} layout The parent layout.
     * @return {Number} The parent layout width.
     * @private
     */
    _getParentLayoutWidth: function(layout) {
        if (!layout) {
            return 0;
        } else if (_.isFunction(layout.getPaneWidth)) {
            return layout.getPaneWidth(this);
        }

        return this._getParentLayoutWidth(layout.layout);
    },

    /**
     * Adjust headerpane fields such that the first field is ellipsified and the last field
     * is set to 100% on view.  On edit, the first field is set to 100%.
     */
    adjustHeaderpaneFields: function() {
        var $ellipsisCell,
            ellipsisCellWidth,
            $recordCells;

        if (this.disposed) {
            return;
        }

        $recordCells = this.$('.headerpane h1').children('.record-cell, .btn-toolbar');

        if (($recordCells.length > 0) && (this.getContainerWidth() > 0)) {
            $ellipsisCell = $(this._getCellToEllipsify($recordCells));

            if ($ellipsisCell.length > 0) {
                if ($ellipsisCell.hasClass('edit')) {
                    // make the ellipsis cell widen to 100% on edit
                    $ellipsisCell.css({'width': '100%'});
                } else {
                    ellipsisCellWidth = this._calculateEllipsifiedCellWidth($recordCells, $ellipsisCell);
                    this._setMaxWidthForEllipsifiedCell($ellipsisCell, ellipsisCellWidth);
                }
            }
        }

        if (this.layout) {
            this.layout.trigger('headerpane:adjust_fields');
        }
    },

    /**
     * Get the first cell for the field that can be ellipsified.
     * @param {jQuery} $cells
     * @return {jQuery}
     * @private
     */
    _getCellToEllipsify: function($cells) {
        var fieldTypesToEllipsify = ['fullname', 'name', 'text', 'base', 'enum', 'url', 'dashboardtitle'];

        return _.find($cells, function(cell) {
            return (_.indexOf(fieldTypesToEllipsify, $(cell).data('type')) !== -1);
        });
    },

    /**
     * Calculate the width for the cell that needs to be ellipsified.
     * @param {jQuery} $cells
     * @param {jQuery} $ellipsisCell
     * @return {Number}
     * @private
     */
    _calculateEllipsifiedCellWidth: function($cells, $ellipsisCell) {
        var width = this.getContainerWidth();

        _.each($cells, function(cell) {
            var $cell = $(cell);

            if ($cell.is($ellipsisCell)) {
                width -= (parseInt($ellipsisCell.css('padding-left'), 10) +
                         parseInt($ellipsisCell.css('padding-right'), 10));
            } else if ($cell.is(':visible')) {
                $cell.css({'width': 'auto'});
                width -= $cell.outerWidth();
            }
            $cell.css({'width': ''});
        });

        return width;
    },

    /**
     * Set the max-width for the specified cell.
     * @param {jQuery} $ellipsisCell
     * @param {number} width
     * @private
     */
    _setMaxWidthForEllipsifiedCell: function($ellipsisCell, width) {
        var ellipsifiedCell,
            fieldType = $ellipsisCell.data('type');
        if (fieldType === 'fullname' || fieldType === 'dashboardtitle') {
            ellipsifiedCell = this.getField($ellipsisCell.data('name'));
            width -= ellipsifiedCell.getCellPadding();
            ellipsifiedCell.setMaxWidth(width);
        } else {
            $ellipsisCell.css({'width': width}).children().css({'max-width': (width - 2) + 'px'});
        }
    },

    /**
     * Returns some fields to be used with app.view.View.getFieldNames() if their corresponding
     * meta attribute is true
     *
     * @private
     */
    _getDataFields: function() {
        var fields = [];

        var favorite = _.find(this.meta.panels, function(panel) {
            return _.find(panel.fields, function(field) {
                return field.type === 'favorite';
            });
        });

        var follow = _.find(this.meta.panels, function(panel) {
            return _.find(panel.fields, function(field) {
                return field.type === 'follow';
            });
        });

        if (favorite) {
            fields.push('my_favorite');
        }

        if (follow) {
            fields.push('following');
        }

        return fields;
    },

    /**
     * Extracts the field names from the metadata for directly related views/panels.
     * @param {string} [module] Module name.
     */
    getFieldNames: function(module) {
        return _.union(this._super('getFieldNames', arguments), this._getDataFields());
    },

    /**
     * Hide or show panel based on click to the panel header
     * @param {Event} e
     */
    togglePanel: function(e) {
        var $panelHeader = this.$(e.currentTarget);
        if ($panelHeader && $panelHeader.next()) {
            $panelHeader.next().toggle();
            $panelHeader.toggleClass('panel-inactive panel-active');
        }
        if ($panelHeader && $panelHeader.find('i')) {
            $panelHeader.find('i').toggleClass('fa-chevron-up fa-chevron-down');
        }
        var panelName = this.$(e.currentTarget).parent().data('panelname');
        var state = 'collapsed';
        if (this.$(e.currentTarget).next().is(":visible")) {
            state = 'expanded';
        }
        this.savePanelState(panelName, state);
    },

    /**
     * Returns true if the first non-header panel has useTabs set to true
     */
    checkFirstPanel: function() {
        if (this.meta && this.meta.panels) {
            if (this.meta.panels[0] && this.meta.panels[0].newTab && !this.meta.panels[0].header) {
                return true;
            }
            if (this.meta.panels[1] && this.meta.panels[1].newTab) {
                return true;
            }
        }
        return false;
    },

    /**
     * Moves overflowing tabs into a dropdown
     */
    overflowTabs: function() {
        var $tabs = this.$('#recordTab > .tab:not(.dropdown)'),
            $dropdownList = this.$('#recordTab .dropdown'),
            $dropdownTabs = this.$('#recordTab .dropdown-menu li'),
            navWidth = this.$('#recordTab').width(),
            activeTabHref = this.getActiveTab(),
            $activeTab = this.$('#recordTab > .tab > a[href="'+activeTabHref+'"]').parent(),
            // Calculate available width for items in navbar
            // Includes the activetab to ensure it is displayed
            width = $activeTab.outerWidth() + $dropdownList.outerWidth();

        $tabs.each(_.bind(function (index, elem) {
            var $tab = $(elem),
                overflow;

            // Always include the active tab
            if ($tab.hasClass('active')) {
                overflow = false;
            }
            else {
                width += $tab.outerWidth();
                // Check if the tab fits in the navbar
                overflow = width >= navWidth;
            }

            // Toggle tabs in the navbar
            $tab.toggleClass('hidden', overflow);
            // Toggle items in the dropdown
            this.$($dropdownTabs[index]).toggleClass('hidden', !overflow);
        }, this));
        // Toggle the dropdown arrow
        $dropdownList.toggleClass('hidden', !$tabs.is(':hidden'));
    },

    /**
     * Takes a tab dropdown link and triggers the corresponding tab
     * @param {Event} e
     */
    triggerNavTab: function(e) {
        var tabTarget = e.currentTarget.hash,
            activeTab = this.$('#recordTab > .tab > a[href="'+tabTarget+'"]');

        e.preventDefault();
        activeTab.trigger('click');
        this.overflowTabs();
    },

    /**
     * Register keyboard shortcuts.
     */
    registerShortcuts: function() {
        app.shortcuts.register({
            id: 'Record:Edit',
            keys: ['e','mod+alt+i'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_EDIT',
            handler: function() {
                var $editButton = this.$('.headerpane [name=edit_button]');
                if ($editButton.is(':visible') && !$editButton.hasClass('disabled')) {
                    $editButton.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Record:Delete',
            keys: ['d','mod+alt+d'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_DELETE',
            handler: function() {
                this.$('.headerpane [data-toggle=dropdown]:visible').click().blur();
                this.$('.headerpane [name=delete_button]:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'Record:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_SAVE',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('a[name=save_button]');
                if ($saveButton.is(':visible') && !$saveButton.hasClass('disabled')) {
                    $saveButton.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Record:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_CANCEL',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('a[name=cancel_button]');
                if ($cancelButton.is(':visible') && !$cancelButton.hasClass('disabled')) {
                    $cancelButton.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Record:Previous',
            keys: 'h',
            component: this,
            description: 'LBL_SHORTCUT_RECORD_PREVIOUS',
            handler: function() {
                var $previous = this.$('.btn.previous-row');
                if ($previous.is(':visible') && !$previous.hasClass('disabled')) {
                    $previous.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Record:Next',
            keys: 'l',
            component: this,
            description: 'LBL_SHORTCUT_RECORD_NEXT',
            handler: function() {
                var $next = this.$('.btn.next-row');
                if ($next.is(':visible') && !$next.hasClass('disabled')) {
                    $next.click();
                }
            }
        });

        app.shortcuts.register({
            id: 'Record:Favorite',
            keys: 'f a',
            component: this,
            description: 'LBL_SHORTCUT_FAVORITE_RECORD',
            handler: function() {
                this.$('.headerpane .fa-favorite:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'Record:Follow',
            keys: 'f o',
            component: this,
            description: 'LBL_SHORTCUT_FOLLOW_RECORD',
            handler: function() {
                this.$('.headerpane [name=follow]:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'Record:Copy',
            keys: ['shift+c','mod+alt+u'],
            component: this,
            description: 'LBL_SHORTCUT_COPY_RECORD',
            handler: function() {
                this.$('.headerpane [data-toggle=dropdown]:visible').click().blur();
                this.$('.headerpane [name=duplicate_button]:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'Record:Action:More',
            keys: 'm',
            component: this,
            description: 'LBL_SHORTCUT_OPEN_MORE_ACTION',
            handler: function() {
                var $primaryDropdown = this.$('.headerpane .btn-primary[data-toggle=dropdown]:visible');
                if (($primaryDropdown.length > 0) && !$primaryDropdown.hasClass('disabled')) {
                    $primaryDropdown.click();
                }
            }
        });
    },

    /**
     * Dismisses all {@link #_viewAlerts alerts} defined in this view.
     *
     * @protected
     */
    _dismissAllAlerts: function() {
        if (_.isEmpty(this._viewAlerts)) {
            return;
        }
        _.each(_.uniq(this._viewAlerts), function(alert) {
            app.alert.dismiss(alert);
        });
        this._viewAlerts = [];
    }
})
