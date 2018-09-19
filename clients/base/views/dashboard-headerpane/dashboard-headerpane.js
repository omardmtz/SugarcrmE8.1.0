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
 * @class View.Views.Base.DashboardHeaderpaneView
 * @alias SUGAR.App.view.views.BaseDashboardHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 * @deprecated 7.9 It will be removed in 7.11.
 *   Please use {@link View.Views.Dashboards.DashboardHeaderpaneView} instead.
 */
({
    extendsFrom: 'HeaderpaneView',
    buttons: null,
    editableFields: null,
    className: 'preview-headerbar',
    events: {
        'click [name=edit_button]': 'editClicked',
        'click [name=cancel_button]': 'cancelClicked',
        'click [name=create_cancel_button]': 'createCancelClicked',
        'click [name=delete_button]': 'deleteClicked',
        'click [name=add_button]': 'addClicked',
        'click [name=collapse_button]': 'collapseClicked',
        'click [name=expand_button]': 'expandClicked'
    },
    initialize: function(options) {
        app.logger.warn('The class `View.Views.Base.DashboardHeaderpaneView`' +
            'has been deprecated since 7.9.0.0 and will be removed in 7.11.0.0. ' +
            'Please use `View.Views.Dashboards.DashboardHeaderpaneView` instead.');
        if(options.context.parent) {
            options.meta = app.metadata.getView(options.context.parent.get("module"), options.name);
            options.template = app.template.getView(options.name);
        }
        this._super("initialize", [options]);
        this.model.on("change change:layout change:metadata", function() {
            if (this.inlineEditMode) {
                this.changed = true;
            }
        }, this);
        this.model.on("error:validation", this.handleValidationError, this);

        if(this.context.get("create")) {
            this.changed = true;
            this.action = 'edit';
            this.inlineEditMode = true;
        } else {
            this.action = 'detail';
        }
        this.buttons = {};

        this._bindEvents();
    },

    /**
     * Binds the events that are necessary for this view.
     *
     * @protected
     */
    _bindEvents: function() {
        this.context.on('record:set:state', this.setRecordState, this);
    },

    /**
     * Handles the logic done when the state changes in the record.
     * This is the callback for the `record:set:state` event.
     *
     * @param {string} state The state that the record is set to.
     */
    setRecordState: function(state) {
        this.model.trigger('setMode', state);
        this.setButtonStates(state);
        this.inlineEditMode = state === 'edit';
        this.toggleEdit(this.inlineEditMode);
    },

    editClicked: function(evt) {
        this.previousModelState = app.utils.deepCopy(this.model.attributes);
        this.inlineEditMode = true;
        this.setButtonStates('edit');
        this.toggleEdit(true);
        this.model.trigger("setMode", "edit");
    },
    cancelClicked: function(evt) {
        this.changed = false;
        this.model.unset('updated');
        this.clearValidationErrors();
        this.setButtonStates('view');
        this.handleCancel();
        this.model.trigger("setMode", "view");
    },

    /**
     * Compare with last fetched data and return true if model contains changes
     *
     * See {@link app.plugins.view.editable}.
     *
     * @return true if current model contains unsaved changes
     */
    hasUnsavedChanges: function() {
        if (this.model.get('updated')) {
            return true;
        }
        if (this.model.isNew()) {
            return this.model.hasChanged();
        }
        return !_.isEmpty(this.model.changedAttributes(this.model.getSynced()));
    },

    /**
     * The save function is handled by {@link View.Layouts.Base.DashboardLayout#handleSave}.
     *
     * @override
     */
    saveClicked: $.noop,

    createCancelClicked: function(evt) {
        if(this.context.parent) {
            this.layout.navigateLayout('list');
        } else {
            app.navigate(this.context);
        }
    },
    deleteClicked: function(evt) {
        this.handleDelete();
    },
    addClicked: function(evt) {
        if(this.context.parent) {
            this.layout.navigateLayout('create');
        } else {
            var route = app.router.buildRoute(this.module, null, 'create');
            app.router.navigate(route, {trigger: true});
        }
    },
    collapseClicked: function(evt) {
        this.context.trigger("dashboard:collapse:fire", true);
    },
    expandClicked: function(evt) {
        this.context.trigger("dashboard:collapse:fire", false);
    },
    _render: function() {
        app.view.View.prototype._render.call(this);

        this._setButtons();
        this.setButtonStates(this.context.get("create") ? 'create' : 'view');
        this.setEditableFields();
    },
    handleCancel: function() {
        this.inlineEditMode = false;
        if (!_.isEmpty(this.previousModelState)) {
            this.model.set(this.previousModelState);
        }
        this.toggleEdit(false);
    },
    handleDelete: function() {
        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_DELETE_DASHBOARD_CONFIRM', this.module),
            onConfirm: _.bind(function() {
                var message = app.lang.get('LBL_DELETE_DASHBOARD_SUCCESS', this.module, {
                    name: app.lang.get(this.model.get('name'), this.module)
                });
                this.model.destroy({
                    success: _.bind(function() {
                        //dispose safe
                        if (this.disposed) {
                            return;
                        }
                        if (this.context.parent) {
                            var contextBro = this.context.parent.getChildContext({module: 'Home'});
                            contextBro.get('collection').remove(this.model);
                            this.layout.navigateLayout('list');
                        } else {
                            var route = app.router.buildRoute(this.module);
                            app.router.navigate(route, {trigger: true});
                        }
                    }, this),
                    error: function() {
                        app.alert.show('error_while_save', {
                            level: 'error',
                            title: app.lang.get('ERR_INTERNAL_ERR_MSG'),
                            messages: ['ERR_HTTP_500_TEXT_LINE1', 'ERR_HTTP_500_TEXT_LINE2']
                        });
                    },
                    //Show alerts for this request
                    showAlerts: {
                        'process': true,
                        'success': {
                            messages: message
                        }
                    }
                });
            }, this)
        });
    },

    bindDataChange: function() {
        //empty out because dashboard header does not need to switch the button sets while model is changed
    },
    toggleEdit: function(isEdit) {
        this.toggleFields(this.editableFields, isEdit);
    }
})
