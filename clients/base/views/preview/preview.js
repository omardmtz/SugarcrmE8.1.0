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
 * @class View.Views.Base.PreviewView
 * @alias SUGAR.App.view.views.BasePreviewView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',
    plugins: ['ToggleMoreLess', 'Editable', 'ErrorDecoration', 'SugarLogic'],
    fallbackFieldTemplate: 'detail',
    /**
     * Events related to the preview view:
     *  - preview:open                  indicate we must show the preview panel
     *  - preview:render                indicate we must load the preview with a model/collection
     *  - preview:collection:change     indicate we want to update the preview with the new collection
     *  - preview:close                 indicate we must hide the preview panel
     *  - preview:pagination:fire       (on layout) indicate we must switch to previous/next record
     *  - preview:pagination:update     (on layout) indicate the preview header needs to be refreshed
     *  - list:preview:fire             indicate the user clicked on the preview icon
     *  - list:preview:decorate         indicate we need to update the highlighted row in list view
     */

    // "binary semaphore" for the pagination click event, this is needed for async changes to the preview model
    switching: false,

    hiddenPanelExists: false,

    initialize: function(options) {
        // Use preview view if available, otherwise fallback to record view
        this.dataView = 'preview';
        var previewMeta = app.metadata.getView(options.module, 'preview');
        var recordMeta = app.metadata.getView(options.module, 'record');

        if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
            this.dataView = 'record';
        }

        this._super('initialize', [options]);
        this.meta = _.extend(this.meta, this._previewifyMetadata(_.extend({}, recordMeta, previewMeta)));
        this.action = 'detail';
        this._delegateEvents();
        this.delegateButtonEvents();

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
    },

    /**
     * @inheritdoc
     *
     * @override Overriding to get preview specific buttons
     */
    toggleButtons: function(enable) {
        if (this.layout.previewEdit) {
            var previewLayout = this.layout.getComponent('preview-header');
            previewLayout.getField('save_button').setDisabled(!enable);
            previewLayout.getField('cancel_button').setDisabled(!enable);
        }
    },

    /**
     * Runs when validation is successful
     * Returns the preview to detail view
     *
     * @override Overriding because we need to trigger 'preview:edit:complete'
     * and not do record view specific actions like: this.inlineEditMode = false;
     */
    handleSave: function() {
        if (this.disposed) {
            return;
        }
        this._saveModel();
        this.layout.trigger('preview:edit:complete');
        this.unsetContextAction();
        this.toggleFields(this.editableFields, false);
        this.toggleLocks(false);
    },

    /**
     * When clicking cancel, return the preview view to detail state
     * and revert the model
     *
     * @override Overriding in order to trigger 'preview:edit:complete'
     */
    cancelClicked: function() {
        this.model.revertAttributes();
        this.toggleFields(this.editableFields, false);
        this.toggleLocks(false);
        this._dismissAllAlerts();
        this.clearValidationErrors(this.editableFields);
        this.unsetContextAction();
        this.layout.trigger('preview:edit:complete');
    },

    /**
     * Add event listeners
     *
     * @private
     */
    _delegateEvents: function() {
        app.events.on('preview:collection:change', this.showPreviousNextBtnGroup, this);
        //FIXME: SC-4915 will delete this listener.
        app.events.on('app:help:shown', function() {
            app.events.trigger('list:preview:decorate', false);
            this.closePreview();
        }, this);

        // TODO: Remove when pagination on activity streams is fixed.
        app.events.on('preview:module:update', this.updatePreviewModule, this);

        if (this.layout) {
            this.layout.on('preview:pagination:fire', this.switchPreview, this);
        }
    },

    /**
     * Setup event listeners for buttons
     *
     * @override Override because we only want to set events if
     * previewEdit is enabled
     */
    delegateButtonEvents: function() {
        if (this.layout && this.layout.previewEdit) {
            this.context.on('button:save_button:click', this.saveClicked, this);
            this.context.on('button:cancel_button:click', this.cancelClicked, this);
            this.layout.on('preview:edit', this.handleEdit, this);
        }
    },

    /**
     * Calls `View.Views.Base.PreviewView#showPreviousNextBtnGroup`.
     *
     * @deprecated since 7.8, will be removed in 7.9.
     * @param {Data.BeanCollection} collection the given collection (unused)
     */
    updateCollection: function(collection) {
        app.logger.warn('View.Views.Base.PreviewView#updateCollection is deprecated since 7.8 and will be' +
            ' removed in 7.9. Since the preview layout now share the view collection, this method is obsolete.');

        this.showPreviousNextBtnGroup();
    },

    // TODO: Remove when pagination on activity streams is fixed.
    updatePreviewModule: function(module) {
        this.previewModule = module;
    },

    filterCollection: function() {
        this.collection.remove(_.filter(this.collection.models, function(model){
            return !app.acl.hasAccessToModel('view', model);
        }, this), { silent: true });
    },

    _renderHtml: function(){
        this.showPreviousNextBtnGroup();
        app.view.View.prototype._renderHtml.call(this);
    },

    /**
     * Show previous and next buttons groups on the view.
     *
     * This gets called everytime the collection gets updated. It also depends
     * if we have a current model or layout.
     *
     * TODO we should check if we have the preview open instead of doing a bunch
     * of if statements.
     */
    showPreviousNextBtnGroup: function () {
        if (!this.model || !this.layout) {
            return;
        }

        var collection = this.collection;
        if (!collection || !collection.size()) {
            this.layout.hideNextPrevious = true;
            // Need to rerender the preview header
            this.layout.trigger('preview:pagination:update');
            return;
        }

        var recordIndex = collection.indexOf(collection.get(this.model.id));
        this.layout.previous = collection.models[recordIndex-1] ? collection.models[recordIndex-1] : undefined;
        this.layout.next = collection.models[recordIndex+1] ? collection.models[recordIndex+1] : undefined;
        this.layout.hideNextPrevious = _.isUndefined(this.layout.previous) && _.isUndefined(this.layout.next);

        // Need to rerender the preview header
        this.layout.trigger('preview:pagination:update');
    },

    /**
     * Renders the preview dialog with the data from the current model and collection.
     *
     * @deprecated Deprecated since 7.8.0. Will be removed in 7.10.0.
     * @param model Model for the object to preview
     * @param collection Collection of related objects to the current model
     * @param {Boolean} fetch Optional Indicates if model needs to be synched with server to populate with latest data
     * @param {Number|String} previewId Optional identifier use to determine event origin. If event origin is not the same
     * but the model id is the same, preview should still render the same model.
     * @private
     */
    _renderPreview: function(model, collection, fetch, previewId) {
        app.logger.warn('`Base.PreviewView#_renderPreview` has been deprecated since 7.8.0 and' +
            'will be removed in 7.10.0.');

        var self = this;

        // If there are drawers there could be multiple previews, make sure we are only rendering preview for active drawer
        if(app.drawer && !app.drawer.isActive(this.$el)){
            return;  //This preview isn't on the active layout
        }

        // Close preview if we are already displaying this model
        if (this.model && model && (this.model.get('id') == model.get('id') && previewId == this.previewId)) {
            // Remove the decoration of the highlighted row
            app.events.trigger('list:preview:decorate', false);
            // Close the preview panel
            app.events.trigger('preview:close');
            return;
        }

        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // if module is in BWC mode, just return
            return;
        }

        if (model) {
            // Use preview view if available, otherwise fallback to record view
            var viewName = 'preview',
                previewMeta = app.metadata.getView(model.module, 'preview'),
                recordMeta = app.metadata.getView(model.module, 'record');
            if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
                viewName = 'record';
            }
            this.meta = this._previewifyMetadata(_.extend({}, recordMeta, previewMeta));
            this.renderPreview(model, collection);
            fetch && model.fetch({
                showAlerts: true,
                view: viewName
            });
        }

        this.previewId = previewId;
    },
    /**
     * Use the given model to render preview.
     * @param {Bean} model Model to render preview
     */
    switchModel: function(model) {
        this.model && this.model.abortFetchRequest();
        this.stopListening(this.model);
        this.model = model;

        // Close preview when model destroyed by deleting the record
        this.listenTo(this.model, 'destroy', function() {
            // Remove the decoration of the highlighted row
            app.events.trigger('list:preview:decorate', false);
            // Close the preview panel
            app.events.trigger('preview:close');
        });
    },
    /**
     * Renders the preview dialog with the data from the current model and collection
     *
     * @deprecated Deprecated since 7.8.0. Will be removed in 7.10.0.
     * @param model Model for the object to preview
     * @param collection Collection of related objects to the current model
     */
    renderPreview: function(model, newCollection) {
        app.logger.warn('`Base.PreviewView#renderPreview` has been deprecated since 7.8.0 and' +
            'will be removed in 7.10.0.');

        if(newCollection) {
            this.collection.reset(newCollection.models);
        }

        if (model) {
            this.switchModel(model);
            if (this.layout) {
                this.layout.trigger('previewheader:ACLCheck', model);
            }

            // TODO: Remove when pagination on activity streams is fixed.
            if (this.previewModule && this.previewModule === 'Activities') {
                // We need to set previewEdit to false before render but set
                // hideNextPreview and trigger 'preview:pagination:update' after
                this.layout.previewEdit = false;
                this.render();
                this.layout.hideNextPrevious = true;
                this.layout.trigger('preview:pagination:update');
            } else {
                // If we aren't on activitystream, then just render
                this.render();
            }
            // Open the preview panel
            app.events.trigger('preview:open', this);
            // Highlight the row
            app.events.trigger('list:preview:decorate', this.model, this);
        }
    },

    /**
     * Normalizes the metadata, and removes favorite/follow fields that gets
     * shown in Preview dialog.
     *
     * @param meta Layout metadata to be trimmed
     * @return Returns trimmed metadata
     * @private
     */
    _previewifyMetadata: function(meta){
        this.hiddenPanelExists = false; // reset
        _.each(meta.panels, function(panel){
            if(panel.header){
                panel.header = false;
                panel.fields = _.filter(panel.fields, function(field){
                    //Don't show favorite or follow in Preview, it's already on list view row
                    return field.type != 'favorite' && field.type != 'follow';
                });
            }
            //Keep track if a hidden panel exists
            if(!this.hiddenPanelExists && panel.hide){
                this.hiddenPanelExists = true;
            }
        }, this);
        return meta;
    },
    /**
     * Switches preview to left/right model in collection.
     * @param {Object} data
     * @param {String} data.direction Direction that we are switching to, either 'left' or 'right'.
     * @param index Optional current index in list
     * @param id Optional
     * @param module Optional
     */
    switchPreview: function(data, index, id, module) {
        var currID = id || this.model.get('id'),
            currIndex = index || _.indexOf(this.collection.models, this.collection.get(currID));

        if( this.switching || this.collection.models.length < 2) {
            // We're currently switching previews or we don't have enough models, so ignore any pagination click events.
            return;
        }
        this.switching = true;

        if (data.direction === 'left' && (currID === _.first(this.collection.models).get('id')) ||
            data.direction === 'right' && (currID === _.last(this.collection.models).get('id'))) {
            this.switching = false;
            return;
        } else {
            // We can increment/decrement
            data.direction === 'left' ? currIndex -= 1 : currIndex += 1;

            //Reset the preview
            app.events.trigger('preview:render', this.collection.models[currIndex], this.collection, true);
            this.switching = false;
        }
    },

    /**
     * @deprecated Deprecated since 7.8.0. Will be removed in 7.10.0.
     */
    closePreview: function() {
        app.logger.warn('`Base.PreviewView#closePreview` has been deprecated since 7.8.0 and' +
            ' will be removed in 7.10.0.');

        if(_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)){
            this.switching = false;
            delete this.model;
            this.collection.reset();
        }
    },

    bindDataChange: function() {
        if(this.collection) {
            this.collection.on('reset', this.filterCollection, this);
            // when remove active model from collection then close preview
            this.collection.on('remove', function(model) {
                if (model && this.model && (this.model.get('id') == model.get('id'))) {
                    // Remove the decoration of the highlighted row
                    app.events.trigger('list:preview:decorate', false);
                    // Close the preview panel
                    app.events.trigger('preview:close');
                }
            }, this);
        }
        // When the preview layout sets the new model in the context, the view
        // needs to switch the model and render for the fields to listen to the new
        // model changes.
        // Since the layout calls loadData, the fields will rerender when the data comes back
        // from the sever.
        this.context.on('change:model', function(ctx, model) {
            this.switchModel(model);
            this.render();
        }, this);
    },

    /**
     * When clicking on the pencil icon, toggle all editable fields
     * to edit mode
     */
    handleEdit: function() {
        this.setEditableFields();
        this.toggleFields(this.editableFields, true);
        this.toggleButtons(true);
        this.setButtonStates(this.STATE.EDIT);
        this.toggleLocks(true);
    },

    /**
     * Show or hide lock icons for locked fields
     *
     * @param {boolean} activate `true` to show lock icon on locked fields
     */
    toggleLocks: function(activate) {
        // Get the locked fields from the model
        var lockedFields = this.model.get('locked_fields') || [];

        if (!this._hasLockedFields) {
            return;
        }

        if (activate) {
            this.warnLockedFields();
        }
        _.each(this.fields, function(field) {
            if (_.contains(lockedFields, field.name)) {
                this.$('.preview-lock-link-wrapper[data-name=' + field.name + ']').toggleClass('hide', !activate);
            }
        }, this);
    },

    /**
     * Set a list of editable fields
     *
     * @override Overriding to checking field def if preview edit
     * is allowed
     */
    setEditableFields: function() {
        // Get the locked fields from the model
        var lockedFields = this.model.get('locked_fields') || [];

        // Clear any old locked fields that may have been set
        this._hasLockedFields = false;

        // we only want to edit non readonly fields
        this.editableFields = _.reject(this.fields, function(field) {
            // Locked fields should not be editable
            if (_.contains(lockedFields, field.name)) {
                this._hasLockedFields = true;
                return true;
            }
            return field.def.readOnly || field.def.calculated ||
                //Added for SugarLogic fields since they are not supported
                //Fixme: PAT-2241 will remove this
                field.def.previewEdit === false ||
                !app.acl.hasAccessToModel('edit', this.model, field.name);
        }, this);
    },

    /**
     * @inheritdoc
     */
    hasUnsavedChanges: function() {
        if (_.isUndefined(this.model)) {
            return false;
        }
        return this._super('hasUnsavedChanges');
    }
})
