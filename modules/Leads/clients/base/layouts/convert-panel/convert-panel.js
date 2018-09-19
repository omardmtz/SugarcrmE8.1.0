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
    extendsFrom: 'ToggleLayout',

    TOGGLE_DUPECHECK: 'dupecheck',
    TOGGLE_CREATE: 'create',

    availableToggles: {
        'dupecheck': {},
        'create': {}
    },

    //selectors
    accordionHeading: '.accordion-heading',
    accordionBody: '.accordion-body',

    //turned on, but could be turned into a setting later
    autoCompleteEnabled: true,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var convertPanelEvents;

        this.meta = options.meta;
        this._setModuleSpecificValues();

        convertPanelEvents = {};
        convertPanelEvents['click .accordion-heading.enabled'] = 'togglePanel';
        convertPanelEvents['click [name="associate_button"]'] = 'handleAssociateClick';
        convertPanelEvents['click [name="reset_button"]'] = 'handleResetClick';
        this.events = _.extend({}, this.events, convertPanelEvents);
        this.plugins = _.union(this.plugins || [], [
            'FindDuplicates'
        ]);

        this.currentState = {
            complete: false,
            dupeSelected: false
        };
        this.toggledOffDupes = false;

        this._super('initialize', [options]);

        this.addSubComponents();

        this.context.on('lead:convert:populate', this.handlePopulateRecords, this);
        this.context.on('lead:convert:' + this.meta.module + ':enable', this.handleEnablePanel, this);
        this.context.on('lead:convert:' + this.meta.moduleNumber + ':open', this.handleOpenRequest, this);
        this.context.on('lead:convert:exit', this.turnOffUnsavedChanges, this);
        this.context.on('lead:convert:' + this.meta.module + ':shown', this.handleShowComponent, this);

        //if this panel is dependent on others - listen for changes and react accordingly
        this.addDependencyListeners();

        //open the first module upon the first autocomplete check completion
        if (this.meta.moduleNumber === 1) {
            this.once('lead:autocomplete-check:complete', this.handleOpenRequest, this);
        }
    },

    /**
     * Retrieve module specific values (like modular singular name and whether
     * dupe check is enabled at a module level).
     * @private
     */
    _setModuleSpecificValues: function() {
        var module = this.meta.module;
        this.meta.modulePlural = app.lang.getAppListStrings('moduleList')[module] || module;
        this.meta.moduleSingular = app.lang.getAppListStrings('moduleListSingular')[module] ||
            this.meta.modulePlural;

        //enable or disable duplicate check
        var moduleMetadata = app.metadata.getModule(module);
        this.meta.enableDuplicateCheck = (moduleMetadata && moduleMetadata.dupCheckEnabled) ||
            this.meta.enableDuplicateCheck ||
            false;
        this.meta.duplicateCheckOnStart = this.meta.enableDuplicateCheck && this.meta.duplicateCheckOnStart;
    },

    /**
     * Used by toggle layout to determine where to place sub-components.
     *
     * @param {Object} component
     * @return {jQuery}
     */
    getContainer: function(component) {
        if (component.name === 'convert-panel-header') {
            return this.$('[data-container="header"]');
        } else {
            return this.$('[data-container="inner"]');
        }
    },

    /**
     * Add all sub-components of the panel.
     */
    addSubComponents: function() {
        this.addHeaderComponent();
        this.addDupeCheckComponent();
        this.addRecordCreateComponent();
    },

    /**
     * Add the panel header view.
     */
    addHeaderComponent: function() {
        var header = app.view.createView({
            context: this.context,
            type: 'convert-panel-header',
            layout: this,
            meta: this.meta
        });
        this.addComponent(header);
    },

    /**
     * Add the duplicate check layout along with events to listen for changes to
     * the duplicate view.
     */
    addDupeCheckComponent: function() {
        var leadsModel = this.context.get('leadsModel'),
            context = this.context.getChildContext({
                'module': this.meta.module,
                'forceNew': true,
                'skipFetch': true,
                'dupelisttype': 'dupecheck-list-select',
                'collection': this.createDuplicateCollection(leadsModel, this.meta.module),
                'layoutName': 'records',
                'dataView': 'selection-list'
            });
        context.prepare();

        this.duplicateView = app.view.createLayout({
            context: context,
            type: this.TOGGLE_DUPECHECK,
            layout: this,
            module: context.get('module')
        });
        this.duplicateView.context.on('change:selection_model', this.handleDupeSelectedChange, this);
        this.duplicateView.collection.on('reset', this.dupeCheckComplete, this);
        this.addComponent(this.duplicateView);
    },

    /**
     * Add the create view.
     */
    addRecordCreateComponent: function() {
        var context = this.context.getChildContext({
            'module': this.meta.module,
            forceNew: true,
            create: true
        });
        context.prepare();

        this.createView = app.view.createView({
            context: context,
            type: this.TOGGLE_CREATE,
            module: context.module,
            layout: this
        });

        this.createView.meta = this.removeFieldsFromMeta(this.createView.meta, this.meta);
        this.createView.enableHeaderButtons = false;
        this.addComponent(this.createView);
    },

    /**
     * Sets the listeners for changes to the dependent modules.
     */
    addDependencyListeners: function() {
        _.each(this.meta.dependentModules, function(details, module) {
            this.context.on('lead:convert:' + module + ':complete', this.updateFromDependentModuleChanges, this);
            this.context.on('lead:convert:' + module + ':reset', this.resetFromDependentModuleChanges, this);
        }, this);
    },

    /**
     * When duplicate results are received (or dupe check did not need to be
     * run) toggle to the appropriate view.
     *
     * If duplicates were found for a required module, auto select the first
     * duplicate.
     */
    dupeCheckComplete: function() {
        if (this.disposed) {
            return;
        }

        this.currentState.dupeCount = this.duplicateView.collection.length;
        this.runAutoCompleteCheck();
        if (this.currentState.dupeCount !== 0) {
            this.showComponent(this.TOGGLE_DUPECHECK);
            if (this.meta.required) {
                this.selectFirstDuplicate();
            }
        } else if (!this.toggledOffDupes) {
            this.showComponent(this.TOGGLE_CREATE);
        }

        this.toggledOffDupes = true; //flag so we only toggle once
        this.trigger('lead:convert-dupecheck:complete', this.currentState.dupeCount);
    },

    /**
     * Check to see if the panel should be automatically marked as complete
     *
     * Required panels are marked complete when there are no duplicates and
     * the create form passes validation.
     */
    runAutoCompleteCheck: function() {
        //Bail out if we've already completed the check
        if (this.autoCompleteCheckComplete) {
            return;
        }

        if (this.autoCompleteEnabled && this.meta.required && this.currentState.dupeCount === 0) {
            this.createView.once('render', this.runAutoCompleteValidation, this);
        } else {
            this.markAutoCompleteCheckComplete();
        }
    },

    /**
     * Run validation, mark panel complete if valid without any alerts
     */
    runAutoCompleteValidation: function() {
        var view = this.createView,
            model = view.model;

        model.isValidAsync(view.getFields(view.module), _.bind(function(isValid) {
            if (isValid) {
                this.markPanelComplete(model);
            }
            this.markAutoCompleteCheckComplete();
        }, this));
    },

    /**
     * Set autocomplete check complete flag and trigger event
     */
    markAutoCompleteCheckComplete: function() {
        this.autoCompleteCheckComplete = true;
        this.trigger('lead:autocomplete-check:complete');
    },

    /**
     * Select the first item in the duplicate check list.
     */
    selectFirstDuplicate: function() {
        var list = this.duplicateView.getComponent('dupecheck-list-select');
        if (list) {
            list.once('render', function() {
                var radio = this.$('input[type=radio]:first');
                if (radio) {
                    radio.prop('checked', true);
                    radio.click();
                }
            }, this);
        }
    },

    /**
     * Removes fields from the meta and replaces with empty html container
     * based on the modules config option - hiddenFields.
     *
     * Example: Account name drop-down should not be available on contact
     * and opportunity module.
     *
     * @param {Object} meta The original metadata
     * @param {Object} moduleMeta Metadata defining fields to hide
     * @return {Object} The metadata after hidden fields removed
     */
    removeFieldsFromMeta: function(meta, moduleMeta) {
        if (moduleMeta.hiddenFields) {
            _.each(meta.panels, function(panel) {
                _.each(panel.fields, function(field, index, list) {
                    if (_.isString(field)) {
                        field = {name: field};
                    }
                    if (moduleMeta.hiddenFields[field.name]) {
                        field.readonly = true;
                        field.required = false;
                        list[index] = field;
                    }
                });
            }, this);
        }
        return meta;
    },

    /**
     * Toggle the accordion body for this panel.
     */
    togglePanel: function() {
        this.$(this.accordionBody).collapse('toggle');
    },

    /**
     * When one panel is completed it notifies the next panel to open
     * This function handles that request and will...
     * - wait for auto complete check to finish before doing anything
     * - pass along request to the next if already complete or not enabled
     * - open the panel otherwise
     */
    handleOpenRequest: function() {
        if (this.autoCompleteCheckComplete !== true) {
            this.once('lead:autocomplete-check:complete', this.handleOpenRequest, this);
        } else {
            if (this.currentState.complete || !this.isPanelEnabled()) {
                this.requestNextPanelOpen();
            } else {
                this.openPanel();
            }
        }
    },

    /**
     * Check if the the current panel is enabled.
     *
     * @return {boolean}
     */
    isPanelEnabled: function() {
        return this.$(this.accordionHeading).hasClass('enabled');
    },

    /**
     * Check if the current panel is open.
     *
     * @return {boolean}
     */
    isPanelOpen: function() {
        return this.$(this.accordionBody).hasClass('in');
    },

    /**
     * Open the body of the panel if enabled (and not already open).
     */
    openPanel: function() {
        if (this.isPanelEnabled()) {
            if (this.isPanelOpen()) {
                this.context.trigger('lead:convert:' + this.meta.module + ':shown');
            } else {
                this.$(this.accordionBody).collapse('show');
            }
        }
    },

    /**
     * When showing create view, render the view, trigger duplication
     * of fields with special handling (like image fields).
     *
     * @inheritdoc
     */
    showComponent: function(name) {
        this._super('showComponent', [name]);
        if (this.currentToggle === this.TOGGLE_CREATE) {
            this.createViewRendered = true;
        }
        this.handleShowComponent();
    },

    /**
     * Render the create view.
     */
    handleShowComponent: function() {
        if (this.currentToggle === this.TOGGLE_CREATE && this.createView.meta.useTabsAndPanels && !this.createViewRendered) {
            this.createView.render();
            this.createViewRendered = true;
        }
    },

    /**
     * Close the body of the panel (if not already closed)
     */
    closePanel: function() {
        this.$(this.accordionBody).collapse('hide');
    },

    /**
     * Handle click of Associate button - running validation if on create view
     * or marking complete if on dupe view.
     *
     * @param {Event} event
     */
    handleAssociateClick: function(event) {
        //ignore clicks if button is disabled
        if (!$(event.currentTarget).hasClass('disabled')) {
            if (this.currentToggle === this.TOGGLE_CREATE) {
                this.runCreateValidation({
                    valid: _.bind(this.markPanelComplete, this),
                    invalid: _.bind(this.resetPanel, this)
                });
            } else {
                this.markPanelComplete(this.duplicateView.context.get('selection_model'));
            }
        }
        event.stopPropagation();
    },

    /**
     * Run validation on the create model and perform specified callbacks based
     * on the validity of the model.
     *
     * @param {Object} callbacks Callbacks to be run after validation is performed.
     * @param {Function} callbacks.valid Run if model is valid.
     * @param {Function} callbacks.invalid Run if model is invalid.
     */
    runCreateValidation: function(callbacks) {
        var view = this.createView,
            model = view.model;

        model.doValidate(view.getFields(view.module), _.bind(function(isValid) {
            if (isValid) {
                callbacks.valid(model);
            } else {
                callbacks.invalid(model);
            }
        }, this));
    },

    /**
     * Mark the panel as complete, close the panel body, and tell the next panel
     * to open.
     *
     * @param {Data.Bean} model
     */
    markPanelComplete: function(model) {
        this.currentState.associatedName = app.utils.getRecordName(model);
        this.currentState.complete = true;
        this.context.trigger('lead:convert-panel:complete', this.meta.module, model);
        this.trigger('lead:convert-panel:complete', this.currentState.associatedName);

        app.alert.dismissAll('error');

        //re-run validation if create model changes after completion
        if (!model.id) {
            model.on('change', this.runPostCompletionValidation, this);
        }

        //if this panel was open, close & tell the next panel to open
        if (this.isPanelOpen()) {
            this.closePanel();
            this.requestNextPanelOpen();
        }
    },

    /**
     * Re-run create model validation after a panel has been marked completed
     */
    runPostCompletionValidation: function() {
        this.runCreateValidation({
            valid: $.noop,
            invalid: _.bind(this.resetPanel, this)
        });
    },

    /**
     * Trigger event to open the next panel in the list
     */
    requestNextPanelOpen: function() {
        this.context.trigger('lead:convert:' + (this.meta.moduleNumber + 1) + ':open');
    },

    /**
     * When reset button is clicked - reset this panel and open it
     * @param {Event} event
     */
    handleResetClick: function(event) {
        this.resetPanel();
        this.openPanel();
        event.stopPropagation();
    },

    /**
     * Reset the panel back to a state the user can modify associated values
     */
    resetPanel: function() {
        this.createView.model.off('change', this.runPostCompletionValidation, this);
        this.currentState.complete = false;
        this.context.trigger('lead:convert-panel:reset', this.meta.module);
        this.trigger('lead:convert-panel:reset');
    },

    /**
     * Track when a duplicate has been selected and notify the panel so it can
     * enable the Associate button
     */
    handleDupeSelectedChange: function() {
        this.currentState.dupeSelected = this.duplicateView.context.has('selection_model');
        this.trigger('lead:convert:duplicate-selection:change');
    },

     /**
     * Wrapper to check whether to fire the duplicate check event
     */
    triggerDuplicateCheck: function() {
        if (this.shouldDupeCheckBePerformed(this.createView.model)) {
            this.trigger('lead:convert-dupecheck:pending');
            this.duplicateView.context.trigger('dupecheck:fetch:fire', this.createView.model, {
                //Show alerts for this request
                showAlerts: true
            });
        } else {
            this.dupeCheckComplete();
        }
    },

    /**
     * Check if duplicate check should be performed
     * dependent on enableDuplicateCheck setting and required dupe check fields
     * @param {Object} model
     */
    shouldDupeCheckBePerformed: function(model) {
        var performDuplicateCheck = this.meta.enableDuplicateCheck;

        if (this.meta.duplicateCheckRequiredFields) {
            _.each(this.meta.duplicateCheckRequiredFields, function(field) {
                if (_.isEmpty(model.get(field))) {
                    performDuplicateCheck = false;
                }
            });
        }
        return performDuplicateCheck;
    },

    /**
     * Populates the record view from the passed in model and then kick off the
     * dupe check
     *
     * @param {Object} model
     */
    handlePopulateRecords: function(model) {
        var fieldMapping = {};

        // if copyData is not set or false, no need to run duplicate check, bail out
        if (!this.meta.copyData) {
            this.dupeCheckComplete();
            return;
        }

        if (!_.isEmpty(this.meta.fieldMapping)) {
            fieldMapping = app.utils.deepCopy(this.meta.fieldMapping);
        }
        var sourceFields = app.metadata.getModule(model.attributes._module, 'fields');
        var targetFields = app.metadata.getModule(this.meta.module, 'fields');

        _.each(model.attributes, function(fieldValue, fieldName) {
            if (app.acl.hasAccessToModel('edit', this.createView.model, fieldName) &&
                !_.isUndefined(sourceFields[fieldName]) &&
                !_.isUndefined(targetFields[fieldName]) &&
                sourceFields[fieldName].type === targetFields[fieldName].type &&
                (_.isUndefined(sourceFields[fieldName]['duplicate_on_record_copy']) ||
                    sourceFields[fieldName]['duplicate_on_record_copy'] !== 'no') &&
                model.has(fieldName) &&
                model.get(fieldName) !== this.createView.model.get(fieldName) &&
                _.isUndefined(fieldMapping[fieldName])) {
                        fieldMapping[fieldName] = fieldName;
                    }
        }, this);

        this.populateRecords(model, fieldMapping);
        if (this.meta.duplicateCheckOnStart) {
            this.triggerDuplicateCheck();
        } else if (!this.meta.dependentModules || this.meta.dependentModules.length == 0) {
            //not waiting on other modules before running dupe check, so mark as complete
            this.dupeCheckComplete();
        }
    },

    /**
     * Use the convert metadata to determine how to map the lead fields to
     * module fields
     *
     * @param {Object} model
     * @param {Object} fieldMapping
     * @return {boolean} whether the create view model has changed
     */
    populateRecords: function(model, fieldMapping) {
        var hasChanged = false;

        _.each(fieldMapping, function(sourceField, targetField) {
            if (model.has(sourceField) && this.shouldSourceValueBeCopied(model.get(sourceField)) &&
                model.get(sourceField) !== this.createView.model.get(targetField)) {
                    this.createView.model.setDefault(targetField, model.get(sourceField));
                    this.createView.model.set(targetField, model.get(sourceField));
                    hasChanged = true;
            }
        }, this);

        //mark the model as copied so that the currency field doesn't set currency_id to user's default value
        if (hasChanged) {
            this.createView.once('render', function() {
                this.createView.model.trigger('duplicate:field', model);
            }, this);

            if (model.has('currency_id')) {
                this.createView.model.isCopied = true;
            }
        }

        return hasChanged;
    },

    /**
     * Enable the panel
     *
     * @param {boolean} isEnabled add/remove the enabled flag on the header
     */
    handleEnablePanel: function(isEnabled) {
        var $header = this.$(this.accordionHeading);
        if (isEnabled) {
            if (!this.currentState.complete) {
                this.triggerDuplicateCheck();
            }
            $header.addClass('enabled');
        } else {
            $header.removeClass('enabled');
        }
    },

    /**
     * Updates the attributes on the model based on the changes from dependent
     * modules duplicate view.
     * Uses dependentModules property - fieldMappings
     *
     * @param {string} moduleName
     * @param {Object} model
     */
    updateFromDependentModuleChanges: function(moduleName, model) {
        var dependencies = this.meta.dependentModules,
            modelChanged = false;
        if (dependencies && dependencies[moduleName] && dependencies[moduleName].fieldMapping) {
            modelChanged = this.populateRecords(model, dependencies[moduleName].fieldMapping);
            if (modelChanged) {
                this.triggerDuplicateCheck();
            }
        }
    },

    /**
     * Resets the state of the panel based on a dependent module being reset
     */
    resetFromDependentModuleChanges: function(moduleName) {
        var dependencies = this.meta.dependentModules;
        if (dependencies && dependencies[moduleName]) {
            //if dupe check has already been run, reset but don't run again yet - just update status
            if (this.currentState.dupeCount && this.currentState.dupeCount > 0) {
                this.duplicateView.collection.reset();
                this.currentState.dupeCount = 0;
            }
            //undo any dependency field mapping that was done previously
            if (dependencies && dependencies[moduleName] && dependencies[moduleName].fieldMapping) {
                _.each(dependencies[moduleName].fieldMapping, function(sourceField, targetField) {
                    this.createView.model.unset(targetField);
                }, this);
            }
            //make sure if we re-trigger dupe check again we handle as if it never happened before
            this.toggledOffDupes = false;
            this.resetPanel();
        }
    },

    /**
     * Resets the model to the default values so that unsaved warning prompt
     * will not be displayed.
     */
    turnOffUnsavedChanges: function() {
        var defaults = _.extend({}, this.createView.model._defaults, this.createView.model.getDefault());

        this.createView.model.attributes = defaults;
    },

    /**
     * Determine whether to copy the the supplied value when it appears in the Source module during conversion
     */
    shouldSourceValueBeCopied: function(val) {
        return _.isNumber(val) || _.isBoolean(val) || !_.isEmpty(val);
    },

    /**
     * Stop listening to events on duplicate view collection
     * @inheritdoc
     */
    _dispose: function() {
        this.createView.model.off('change', this.runPostCompletionValidation, this);
        this.createView.off(null, null, this);
        this.duplicateView.off(null, null, this);
        this.duplicateView.context.off(null, null, this);
        this.duplicateView.collection.off(null, null, this);
        this._super('_dispose');
    }
})
