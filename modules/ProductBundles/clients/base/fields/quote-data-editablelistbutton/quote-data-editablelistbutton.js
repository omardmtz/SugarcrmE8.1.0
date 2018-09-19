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
 * @class View.Fields.Base.ProductBundles.EditablelistbuttonField
 * @alias SUGAR.App.view.fields.BaseProductBundlesEditablelistbuttonField
 * @extends View.Fields.Base.BaseEditablelistbuttonField
 */
({
    extendsFrom: 'BaseEditablelistbuttonField',

    /**
     * @inheritdoc
     */
    _render: function() {
        var syncedName;

        if (this.name === 'inline-save') {
            syncedName = this.model.getSynced('name');
            if (this.model.get('name') !== syncedName) {
                this.changed = true;
            }
        }

        this._super('_render');

        if (this.tplName === 'edit') {
            this.$el.closest('.left-column-save-cancel').addClass('higher');
        } else {
            this.$el.closest('.left-column-save-cancel').removeClass('higher');
        }
    },

    /**
     * Overriding and not calling parent _loadTemplate as those are based off view/actions and we
     * specifically need it based off the modelView set by the parent layout for this row model
     *
     * @inheritdoc
     */
    _loadTemplate: function() {
        this.tplName = this.model.modelView || 'list';

        if (this.view.action === 'list' && _.indexOf(['edit', 'disabled'], this.action) < 0) {
            this.template = app.template.empty;
        } else {
            this.template = app.template.getField(this.type, this.tplName, this.module);
        }
    },

    /**
     * Overriding cancelEdit so we can update the group name if this is coming from
     * the quote data group header
     *
     * @inheritdoc
     */
    cancelEdit: function() {
        var modelModule = this.model.module;
        var modelId = this.model.cid;
        var syncedAttribs = this.model.getSynced();
        if (this.isDisabled()) {
            this.setDisabled(false);
        }

        this.changed = false;

        if (this.view.name === 'quote-data-group-header') {
            // for cancel on group-header, revertAttributes doesn't reset the model
            if (this.model.get('name') !== syncedAttribs.name) {
                if (_.isUndefined(syncedAttribs.name)) {
                    // if name was undefined, unset name
                    this.model.unset('name');
                } else {
                    // if name was defined or '', set back to that
                    this.model.set('name', syncedAttribs.name);
                }
            }
        } else {
            this.model.revertAttributes();
        }

        this.view.clearValidationErrors();

        this.view.toggleRow(modelModule, modelId, false);

        // trigger a cancel event across the view layout so listening components
        // know the changes made in this row are being reverted
        if (this.view.layout) {
            this.view.layout.trigger('editablelist:' + this.view.name + ':cancel', this.model);
        }
    },

    /**
     * Overriding cancelClicked to trigger an event if this is a
     * create view or the group was just saved
     *
     * @inheritdoc
     */
    cancelClicked: function() {
        var syncedAttribs = this.model.getSynced();
        var itemsInGroup = this.model.get('product_bundle_items');

        if (itemsInGroup) {
            itemsInGroup = itemsInGroup.length;
        }

        if (this.view.isCreateView || (syncedAttribs._justSaved && itemsInGroup === 0)) {
            this.view.layout.trigger('editablelist:' + this.view.name + ':create:cancel', this.model);
        } else {
            this.cancelEdit();
        }
    },

    /**
     * Called after the save button is clicked and all the fields have been validated,
     * triggers an event for
     *
     * @inheritdoc
     */
    _save: function() {
        this.view.layout.trigger('editablelist:' + this.view.name + ':saving', true);
        this._saveRowModel();
    },

    /**
     * Saves the row's model
     *
     * @private
     */
    _saveRowModel: function() {
        var self = this;
        var oldModelId = this.model.cid;
        var quoteModel = this.context.get('parentModel');
        var successCallback = function(data, request) {
            self.changed = false;
            self.model.modelView = 'list';

            if (!_.isEmpty(data.related_record)) {
                self.model.setSyncedAttributes(data.related_record);
                self.model.set(data.related_record);
            }

            if (self.view.layout) {
                self.view.layout.trigger('editablelist:' + self.view.name + ':save', self.model, oldModelId);
            }
        };
        var options = {
            success: successCallback,
            error: function(error) {
                if (error.status === 409) {
                    app.utils.resolve409Conflict(error, self.model, function(model, isDatabaseData) {
                        if (model) {
                            if (isDatabaseData) {
                                successCallback(model);
                            } else {
                                self._save();
                            }
                        }
                    });
                }
            },
            complete: function() {
                // remove this model from the list if it has been unlinked
                if (self.model.get('_unlinked')) {
                    self.collection.remove(self.model, {silent: true});
                    self.collection.trigger('reset');
                    self.view.render();
                } else {
                    self.setDisabled(false);
                }
            },
            lastModified: self.model.get('date_modified'),
            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': {
                    messages: app.lang.get('LBL_RECORD_SAVED', self.module)
                }
            },
            relate: this.model.link ? true : false
        };

        options = _.extend({}, options, this.getCustomSaveOptions(options));

        app.api.relationships('update', 'Quotes', {
            id: quoteModel.get('id'),
            link: 'product_bundles',
            relatedId: this.model.get('id'),
            related: {
                name: this.model.get('name')
            }
        }, null, options);
    }
});
