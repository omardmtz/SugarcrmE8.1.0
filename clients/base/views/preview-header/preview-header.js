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
 * @class View.Views.Base.PreviewHeaderView
 * @alias SUGAR.App.view.views.BasePreviewHeaderView
 * @extends View.View
 */
({
    className: 'preview-headerbar',

    events: {
        'click [data-direction]': 'triggerPagination',
        'click .closeSubdetail': 'triggerClose'
    },

    initialize: function(options) {
        this._super('initialize', [options]);
        this.checkACL(this.model);

        this._delegateEvents();
    },

    /**
     * Set up event listeners
     *
     * @private
     */
    _delegateEvents: function() {
        if (this.layout) {
            this.layout.on('preview:pagination:update', this.render, this);
        }

        if (this.layout.previewEdit) {
            _.extend(this.events, {'click [data-action=edit]': 'triggerEdit'});
            this.layout.on('preview:edit:complete', this.toggleSaveAndCancel, this);
        }
    },

    triggerPagination: function(e) {
        var direction = this.$(e.currentTarget).data();
        this.layout.trigger('preview:pagination:fire', direction);
    },

    triggerClose: function() {
        app.events.trigger('list:preview:decorate', null, this);
        app.events.trigger('preview:close');
    },

    /**
     * Call preview view to turn on editing
     */
    triggerEdit: function() {
        this.toggleSaveAndCancel(true);
        this.layout.trigger('preview:edit');
    },

    /**
     * Toggle save, cancel, left, right and x buttons
     *
     * @param {boolean} edit `true` to show save and cancel and hide
     * left, right and X icons
     */
    toggleSaveAndCancel: function(edit) {
        if (edit) {
            this.getField('save_button').show();
            this.getField('cancel_button').show();
            this.$('[data-direction], [data-action=close]').hide();
        } else {
            this.getField('save_button').hide();
            this.getField('cancel_button').hide();
            this.$('[data-direction], [data-action=close]').show();
        }
    },

    /**
     * @inheritdoc
     *
     * @override Overriding to hide preview save/cancel buttons initially
     * @private
     */
    _renderFields: function() {
        this._super('_renderFields');

        if (this.layout.previewEdit) {
            this.getField('save_button').hide();
            this.getField('cancel_button').hide();
        }
    },

    /**
     *  @inheritdoc
     *
     *  @override Overiding render
     */
    _render: function() {
        this.layout.on('previewheader:ACLCheck', this.checkACL, this)
        this._super('_render');
    },

    /**
     * Check if the user has permission to edit the current record
     *
     * @param model Model for preview
     */
    checkACL: function(model) {
        if (app.config.previewEdit && this.layout.meta.editable === true &&
            app.acl.hasAccessToModel('edit', model)) {
            this.layout.previewEdit = true;
        } else {
            this.layout.previewEdit = false;
        }
    }
})
