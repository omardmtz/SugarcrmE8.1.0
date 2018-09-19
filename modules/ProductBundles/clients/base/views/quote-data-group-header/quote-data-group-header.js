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
 * @class View.Views.Base.ProductBundles.QuoteDataGroupHeaderView
 * @alias SUGAR.App.view.views.BaseProductBundlesQuoteDataGroupHeaderView
 * @extends View.Views.Base.View
 */
({
    /**
     * @inheritdoc
     */
    events: {
        'click [name="create_qli_button"]': '_onCreateQLIBtnClicked',
        'click [name="create_comment_button"]': '_onCreateCommentBtnClicked',
        'click [name="edit_bundle_button"]': '_onEditBundleBtnClicked',
        'click [name="delete_bundle_button"]': '_onDeleteBundleBtnClicked'
    },

    /**
     * @inheritdoc
     */
    plugins: [
        'MassCollection',
        'Editable',
        'ErrorDecoration'
    ],

    /**
     * Array of fields to use in the template
     */
    _fields: undefined,

    /**
     * The colspan value for the list
     */
    listColSpan: 0,

    /**
     * The CSS class for the save icon
     */
    saveIconCssClass: '.group-loading-icon',

    /**
     * How many times the group has been called to start or stop saving
     */
    groupSaveCt: undefined,

    /**
     * Object containing the row's fields
     */
    rowFields: {},

    /**
     * Array of left column fields
     */
    leftColumns: undefined,

    /**
     * Array of left column fields
     */
    leftSaveCancelColumn: undefined,

    /**
     * If this is the first time the view has rendered or not
     */
    isFirstRender: undefined,

    /**
     * If this layout is currently in the /create view or not
     */
    isCreateView: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // make sure we're using the layout's model
        options.model = options.model || options.layout.model;

        this.listColSpan = options.layout.listColSpan;

        // use the same massCollection from the Quotes QuoteDataListHeaderView
        var quoteDataListHeaderComp;
        if (options.layout && options.layout.layout) {
            quoteDataListHeaderComp =  options.layout.layout.getComponent('quote-data-list-header');
            if (quoteDataListHeaderComp) {
                options.context.set('mass_collection', quoteDataListHeaderComp.massCollection);
            }
        }

        this._super('initialize', [options]);

        this.isCreateView = this.context.parent.get('create') || false;

        this.isFirstRender = true;

        this.viewName = 'list';
        this.action = 'list';
        this._fields = _.flatten(_.pluck(this.meta.panels, 'fields'));

        this.toggledModels = {};
        this.leftColumns = [];
        this.leftSaveCancelColumn = [];
        this.addMultiSelectionAction();

        // ninjastuff
        this.el = this.layout.el;
        this.setElement(this.el);

        this.groupSaveCt = 0;
        this.layout.on('quotes:group:save:start', this._onGroupSaveStart, this);
        this.layout.on('quotes:group:save:stop', this._onGroupSaveStop, this);
        this.layout.on('editablelist:' + this.name + ':save', this.onSaveRowEdit, this);
        this.layout.on('editablelist:' + this.name + ':saving', this.onSavingRow, this);
        this.layout.on('editablelist:' + this.name + ':create:cancel', this._onDeleteBundleBtnClicked, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        // set row fields after rendering to prep if we need to toggle rows
        this._setRowFields();

        if (!_.isEmpty(this.toggledModels)) {
            _.each(this.toggledModels, function(model, modelId) {
                this.toggleRow(model.module, modelId, true);
            }, this);
        }

        // on the first header row render, if this model was _justSaved
        // we want to toggle the row to edit mode adding this to toggledModels
        if (this.isFirstRender && this.model.has('_justSaved')) {
            this.model.unset('_justSaved');
            this.isFirstRender = false;
            this.toggleRow(this.model.module, this.model.cid, true);
        }
    },

    /**
     * Handles displaying the loading icon when a group starts saving
     *
     * @private
     */
    _onGroupSaveStart: function() {
        this.groupSaveCt++;
        this.$(this.saveIconCssClass).show();
    },

    /**
     * Handles hiding the loading icon when a group save is complete
     *
     * @private
     */
    _onGroupSaveStop: function() {
        this.groupSaveCt--;
        if (this.groupSaveCt === 0) {
            this.$(this.saveIconCssClass).hide();
        }

        if (this.groupSaveCt < 0) {
            this.groupSaveCt = 0;
        }
    },

    /**
     * Handles when the create Quoted Line Item button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onCreateQLIBtnClicked: function(evt) {
        this.layout.trigger('quotes:group:create:qli', 'products');
    },

    /**
     * Handles when the create Comment button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onCreateCommentBtnClicked: function(evt) {
        this.layout.trigger('quotes:group:create:note', 'product_bundle_notes');
    },

    /**
     * Handles when the edit Group button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onEditBundleBtnClicked: function(evt) {
        var $tbodyEl = $(evt.target).closest('tbody');
        var bundleId = $tbodyEl.data('group-id');

        this.toggleRow(this.model.module, bundleId, true);
    },

    /**
     * Handles when the delete Group button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onDeleteBundleBtnClicked: function(evt) {
        this.context.parent.trigger('quotes:group:delete', this.layout);
    },

    /**
     * Toggle editable selected row's model fields.
     *
     * @param {string} rowModule The row model's module.
     * @param {string} rowModelId The row model's ID
     * @param {boolean} isEdit True for edit mode, otherwise toggle back to list mode.
     */
    toggleRow: function(rowModule, rowModelId, isEdit) {
        var toggleModel;
        var row;
        if (isEdit) {
            toggleModel = this.model;
            toggleModel.modelView = 'edit';
            this.toggledModels[rowModelId] = toggleModel;
        } else {
            if (this.toggledModels[rowModelId]) {
                this.toggledModels[rowModelId].modelView = 'list';
            }
            delete this.toggledModels[rowModelId];
        }

        row = this.$('tr[name=' + rowModule + '_' + rowModelId + ']');
        row.toggleClass('tr-inline-edit', isEdit);
        this.toggleFields(this.rowFields[rowModelId], isEdit);

        if (isEdit) {
            // make sure row is not sortable on edit
            row
                .addClass('not-sortable')
                .removeClass('sortable ui-sortable');

            this.context.trigger('list:editgroup:fire');
        }
    },

    /**
     * Set, or reset, the collection of fields that contains each row.
     *
     * This function is invoked when the view renders. It will update the row
     * fields once the `Pagination` plugin successfully fetches new records.
     *
     * @private
     */
    _setRowFields: function() {
        this.rowFields = {};
        _.each(this.fields, function(field) {
            if (field.model && field.model.cid && _.isUndefined(field.parent)) {
                this.rowFields[field.model.cid] = this.rowFields[field.model.cid] || [];
                this.rowFields[field.model.cid].push(field);
            }
        }, this);
    },

    /**
     * Adds the left column fields
     */
    addMultiSelectionAction: function() {
        _.each(this.meta.buttons, function(button) {
            this.leftColumns.push(button);
        }, this);

        this.leftSaveCancelColumn.push({
            'type': 'fieldset',
            'label': '',
            'sortable': false,
            'fields': [{
                type: 'quote-data-editablelistbutton',
                label: '',
                tooltip: 'LBL_CANCEL_BUTTON_LABEL',
                name: 'inline-cancel',
                icon: 'fa-close',
                css_class: 'btn-link btn-invisible inline-cancel ellipsis_inline'
            }]
        });

        // if this is the create view, do not add a save button
        if (this.isCreateView) {
            this.leftSaveCancelColumn[0].fields.push({
                type: 'quote-data-actiondropdown',
                label: '',
                tooltip: 'LBL_SAVE_BUTTON_LABEL',
                name: 'create-dropdown-editmode',
                icon: 'fa-plus',
                css_class: 'ellipsis_inline',
                no_default_action: true,
                buttons: [{
                    type: 'button',
                    icon: 'fa-plus',
                    name: 'create_qli_button',
                    label: 'LBL_CREATE_QLI_BUTTON_LABEL',
                    acl_action: 'create',
                    tooltip: 'LBL_CREATE_QLI_BUTTON_TOOLTIP'
                }, {
                    type: 'button',
                    icon: 'fa-plus',
                    name: 'create_comment_button',
                    label: 'LBL_CREATE_COMMENT_BUTTON_LABEL',
                    acl_action: 'create',
                    tooltip: 'LBL_CREATE_COMMENT_BUTTON_TOOLTIP'
                }]
            });
        } else {
            this.leftSaveCancelColumn[0].fields.push({
                type: 'quote-data-editablelistbutton',
                label: '',
                tooltip: 'LBL_SAVE_BUTTON_LABEL',
                name: 'inline-save',
                icon: 'fa-check-circle',
                css_class: 'btn-link btn-invisible inline-save ellipsis_inline'
            });
        }
    },

    /**
     * Handles when a row is saved.
     *
     * @param {Data.Bean} rowModel
     */
    onSaveRowEdit: function(rowModel) {
        // Quote groups always use the cid of the model
        var modelId = rowModel.cid;
        var modelModule = rowModel.module;

        this.toggleCancelButton(false);
        this.toggleRow(modelModule, modelId, false);
    },

    /**
     * Toggles the cancel button disabled or not
     *
     * @param {boolean} disable If we should disable the button or not
     */
    toggleCancelButton: function(disable) {
        var cancelBtn = _.find(this.fields, function(field) {
            return field.name == 'inline-cancel';
        });
        if (cancelBtn) {
            cancelBtn.setDisabled(disable);
        }
    },

    /**
     * Handles when the row is being saved but has not been saved fully yet
     *
     * @param {boolean} disableCancelBtn If we should disable the button or not
     */
    onSavingRow: function(disableCancelBtn) {
        // todo: SFA-4541 needs to add code in here to toggle fields to readonly
        this.toggleCancelButton(disableCancelBtn);
    }
})
