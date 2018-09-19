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
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.convertPanels = {};
        this.associatedModels = {};
        this.dependentModules = {};
        this.noAccessRequiredModules = [];

        app.view.Layout.prototype.initialize.call(this, options);

        this.meta.modules = this.filterModulesByACL(this.meta.modules);

        this.initializeOptions(this.meta.modules);

        //create and place all the accordion panels
        this.initializePanels(this.meta.modules);

        //listen for panel status updates
        this.context.on('lead:convert-panel:complete', this.handlePanelComplete, this);
        this.context.on('lead:convert-panel:reset', this.handlePanelReset, this);

        //listen for Save button click in headerpane
        this.context.on('lead:convert:save', this.handleSave, this);

        this.before('render', this.checkRequiredAccess);
    },

    /**
     * Create a new object with only modules the user has create access to and
     * build list of required modules the user does not have create access to.
     *
     * @param {Object} modulesMetadata
     * @return {Object}
     */
    filterModulesByACL: function(modulesMetadata) {
        var filteredModulesMetadata = {};

        _.each(modulesMetadata, function(moduleMeta, key) {
            //strip out modules that user does not have create access to
            if (app.acl.hasAccess('create', moduleMeta.module)) {
                filteredModulesMetadata[key] = moduleMeta;
            } else if (moduleMeta.required === true) {
                this.noAccessRequiredModules.push(moduleMeta.module);
            }
        }, this);

        return filteredModulesMetadata;
    },

    /**
     * Create an options section on top of convert panels that presents options
     * when converting a lead (specifically, which modules to copy/move
     * activities to).
     *
     * @param {Object} modulesMetadata
     */
    initializeOptions: function(modulesMetadata) {
        var view,
            convertModuleList = [];

        _.each(modulesMetadata, function(moduleMeta) {
            var moduleSingular = this.getModuleSingular(moduleMeta.module);
            convertModuleList.push({
                id: moduleMeta.module,
                text: moduleSingular,
                required: moduleMeta.required
            });
        }, this);

        this.context.set('convertModuleList', convertModuleList);
        view = app.view.createView({
            context: this.context,
            layout: this,
            type: 'convert-options',
            platform: this.options.platform
        });

        this.addComponent(view);
    },

    /**
     * Iterate over the modules defined in convert-main.php
     * Create a convert panel for each module defined there
     *
     * @param {Object} modulesMetadata
     */
    initializePanels: function(modulesMetadata) {
        var moduleNumber = 1;

        _.each(modulesMetadata, function(moduleMeta) {
            moduleMeta.moduleNumber = moduleNumber++;
            var view = app.view.createLayout({
                context: this.context,
                type: 'convert-panel',
                layout: this,
                meta: moduleMeta,
                platform: this.options.platform
            });
            view.initComponents();

            //This is because backbone injects a wrapper element.
            view.$el.addClass('accordion-group');
            view.$el.data('module', moduleMeta.module);

            this.addComponent(view);
            this.convertPanels[moduleMeta.module] = view;
            if (moduleMeta.dependentModules) {
                this.dependentModules[moduleMeta.module] = moduleMeta.dependentModules;
            }
        }, this);
    },

    /**
     * Check if user is missing access to any required modules
     * @return {boolean}
     */
    checkRequiredAccess: function() {
        //user is missing access to required modules - kick them out
        if (this.noAccessRequiredModules.length > 0) {
            this.denyUserAccess(this.noAccessRequiredModules);
            return false;
        }
        return true;
    },

    /**
     * Close lead convert and notify the user that they are missing required access
     * @param {Array} noAccessRequiredModules
     */
    denyUserAccess: function(noAccessRequiredModules) {
        var translatedModuleNames = [];

        _.each(noAccessRequiredModules, function(module) {
            translatedModuleNames.push(this.getModuleSingular(module));
        }, this);

        app.alert.show('convert_access_denied', {
            level: 'error',
            messages: app.lang.get(
                'LBL_CONVERT_ACCESS_DENIED',
                this.module,
                {requiredModulesMissing: translatedModuleNames.join(', ')}
            )
        });
        app.drawer.close();
    },

    /**
     * Retrieve the translated module name
     * @param {string} module
     * @return {string}
     */
    getModuleSingular: function(module) {
        var modulePlural = app.lang.getAppListStrings('moduleList')[module] || module;
        return (app.lang.getAppListStrings('moduleListSingular')[module] || modulePlural);
    },

    _render: function() {
        app.view.Layout.prototype._render.call(this);

        //This is because backbone injects a wrapper element.
        this.$el.addClass('accordion');
        this.$el.attr('id', 'convert-accordion');

        //apply the accordion to this layout
        this.$('.collapse').collapse({toggle: false, parent: '#convert-accordion'});
        this.$('.collapse').on('shown hidden', _.bind(this.handlePanelCollapseEvent, this));

        //copy lead data down to each module when we get the lead data
        this.context.get('leadsModel').fetch({
            success: _.bind(function(model) {
                if (this.context) {
                    this.context.trigger('lead:convert:populate', model);
                }
            }, this)
        });
    },

    /**
     * Catch collapse shown/hidden events and notify the panels via the context
     * @param {Event} event
     */
    handlePanelCollapseEvent: function(event) {
        //only respond to the events directly on the collapse (was getting events from tooltip propagated up
        if (event.target !== event.currentTarget) {
            return;
        }
        var module = $(event.currentTarget).data('module');
        this.context.trigger('lead:convert:' + module + ':' + event.type);
    },

    /**
     * When a panel is complete, add the model to the associatedModels array and notify any dependent modules
     * @param {string} module that was completed
     * @param {Data.Bean} model
     */
    handlePanelComplete: function(module, model) {
        this.associatedModels[module] = model;
        this.handlePanelUpdate();
        this.context.trigger('lead:convert:' + module + ':complete', module, model);
    },

    /**
     * When a panel is reset, remove the model from the associatedModels array and notify any dependent modules
     * @param {string} module
     */
    handlePanelReset: function(module) {
        delete this.associatedModels[module];
        this.handlePanelUpdate();
        this.context.trigger('lead:convert:' + module + ':reset', module);
    },

    /**
     * When a panel has been updated, check if any module's dependencies are met
     * and/or if all required modules have been completed
     */
    handlePanelUpdate: function() {
        this.checkDependentModules();
        this.checkRequired();
    },

    /**
     * Check if each module's dependencies are met and enable the panel if they are.
     * Dependencies are defined in the convert-main.php
     */
    checkDependentModules: function() {
        _.each(this.dependentModules, function(dependencies, dependentModuleName) {
            var isEnabled = _.all(dependencies, function(module, moduleName) {
                return (this.associatedModels[moduleName]);
            }, this);
            this.context.trigger('lead:convert:' + dependentModuleName + ':enable', isEnabled);
        }, this);
    },

    /**
     * Checks if all required modules have been completed
     * Enables the Save button if all are complete
     */
    checkRequired: function() {
        var showSave = _.all(this.meta.modules, function(module) {
            if (module.required) {
                if (!this.associatedModels[module.module]) {
                    return false;
                }
            }
            return true;
        }, this);

        this.context.trigger('lead:convert-save:toggle', showSave);
    },

    /**
     * When save button is clicked, call the Lead Convert API
     */
    handleSave: function() {
        var convertModel, myURL;

        //disable the save button to prevent double click
        this.context.trigger('lead:convert-save:toggle', false);

        app.alert.show('processing_convert', {level: 'process', title: app.lang.get('LBL_SAVING')});

        convertModel = new Backbone.Model(_.extend(
            {'modules' : this.parseEditableFields(this.associatedModels)},
            this.getTransferActivitiesAttributes()
        ));

        myURL = app.api.buildURL('Leads', 'convert', {id: this.context.get('leadsModel').id});

        // Set field_duplicateBeanId for fields implementing FieldDuplicate
        _.each(this.convertPanels, function(view, module) {
            if (view && view.createView && convertModel.get('modules')[module]) {
                view.createView.model.trigger('duplicate:field:prepare:save', convertModel.get('modules')[module]);
            }
        }, this);

        app.api.call('create', myURL, convertModel, {
            success: _.bind(this.convertSuccess, this),
            error: _.bind(this.convertError, this)
        });
    },

    /**
     * Retrieve the attributes to be added to the convert model to support the
     * transfer activities functionality.
     *
     * @return {Object}
     */
    getTransferActivitiesAttributes: function() {
        var action = app.metadata.getConfig().leadConvActivityOpt,
            optedInToTransfer = this.model.get('transfer_activities');

        return {
            transfer_activities_action: (action === 'move' && optedInToTransfer) ? 'move' : 'donothing'
        };
    },

    /**
     * Returns only the fields for the models that the user is allowed to edit.
     * This method is run in the sync method of data-manager for creating records.
     *
     * @param {Object} models to get fields from.
     * @return {Object} Hash of models with editable fields.
     */
    parseEditableFields: function(models) {
        var filteredModels = {};
        _.each(models, function(associatedModel, associatedModule) {
            filteredModels[associatedModule] = app.data.getEditableFields(associatedModel);
        }, this);

        return filteredModels;
    },


    /**
     * Lead was successfully converted
     */
    convertSuccess: function() {
        this.convertComplete('success', 'LBL_CONVERTLEAD_SUCCESS', true);
    },

    /**
     * There was a problem converting the lead
     */
    convertError: function() {
        this.convertComplete('error', 'LBL_CONVERTLEAD_ERROR', false);

        if (!this.disposed) {
            this.context.trigger('lead:convert-save:toggle', true);
        }
    },

    /**
     * Based on success of lead conversion, display the appropriate messages and optionally close the drawer
     * @param {string} level
     * @param {string} message
     * @param {boolean} doClose
     */
    convertComplete: function(level, message, doClose) {
        var leadsModel = this.context.get('leadsModel');
        app.alert.dismiss('processing_convert');
        app.alert.show('convert_complete', {
            level: level,
            messages: app.lang.get(message, this.module, {leadName: app.utils.getRecordName(leadsModel)}),
            autoClose: (level === 'success')
        });
        if (!this.disposed && doClose) {
            this.context.trigger('lead:convert:exit');
            app.drawer.close();
            app.router.record('Leads', leadsModel.id);
        }
    },

    /**
     * Clean up the jquery events that were added
     * @private
     */
    _dispose: function() {
        this.$('.collapse').off();
        app.view.Layout.prototype._dispose.call(this);
    }
})
