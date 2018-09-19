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
 * Actionmenu is an {@link View.Fields.Base.ActiondropdownField actiondropdown}
 * with a checkbox as the default action.
 *
 * Supported Properties:
 *
 * - {Boolean} disable_select_all_alert Boolean telling if we should show the
 *   'select all' and 'clear all' alerts when all checkboxes are checked.
 *   `true` to hide alerts. `false` to display them. Defaults to `false`.
 *
 * @class View.Fields.Base.ActionmenuField
 * @alias SUGAR.App.view.fields.BaseActionmenuField
 * @extends View.Fields.Base.ActiondropdownField
 */
({
    extendsFrom: 'ActiondropdownField',

    /** Initializes the actionmenu field.
     *
     * Sets the property no_default_action to `true` because the checkbox will
     * always be the default action and it's handled separately.
     * See {@link View.Fields.Base.ActiondropdownField} for properties
     * documentation.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.events = _.extend({}, this.events, {
            'click [data-check=all]': 'checkAll',
            'click [data-check=one]': 'check'
        });

        this.def.no_default_action = true;
        this.def.css_class = this.def.css_class + ' actionmenu';
        /**
         * The checkbox tag.
         *
         * @property {string}
         */
        this.fieldTag = 'input[type=checkbox]';

        /**
         * The mass collection the actionmenu field is related to.
         *
         * @property {Data.BeanCollection} massCollection
         */
        this.massCollection = this.context.get('mass_collection');

        this.def.disable_select_all_alert = !!this.def.disable_select_all_alert;

        if (this.options.viewName === 'list-header') {
            this.isCheckAllCheckbox = true;
        }

        if (this.isCheckAllCheckbox) {
            app.shortcuts.register({
                id: 'SelectAll:Checkbox',
                keys: 'mod+a',
                component: this,
                description: 'LBL_SHORTCUT_SELECT_ALL',
                handler: function() {
                    if (!this.isDisabled()) {
                        this.$('[data-check=all]:visible').click();
                    }
                }
            });
            app.shortcuts.register({
                id: 'SelectAll:Dropdown',
                keys: 'm',
                component: this,
                description: 'LBL_SHORTCUT_OPEN_MASS_ACTION',
                handler: function() {
                    var $dropdown = this.$(this.actionDropDownTag);
                    if ($dropdown.is(':visible') && !$dropdown.hasClass('disabled')) {
                        $dropdown.click();
                    }
                }
            });
        }
    },

    /**
     * Calls {@link #toggleSelect} to help pass the information to the context.
     */
    check: function() {
        var $checkbox = this.$(this.fieldTag);
        var isChecked = $checkbox.is(':checked');
        this.toggleSelect(isChecked);
    },

    /**
     * Sends an event to the context to add or remove the model from the mass
     * collection.
     *
     * @param {boolean} checked `true` to pass the model to the mass collection,
     *   `false` to remove it.
     */
    toggleSelect: function(checked) {
        var event = !!checked ? 'mass_collection:add' : 'mass_collection:remove';
        this.context.trigger(event, this.model);
    },

    /**
     * Selects or unselects all records that are in the current collection.
     *
     * @param {Event} The `click` or `keydown` event.
     */
    checkAll: function(event) {
        var $checkbox = this.$(this.fieldTag);
        if ($(event.target).hasClass('checkall') || event.type === 'keydown') {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
        }
        var isChecked = $checkbox.is(':checked');
        this.toggleAll(isChecked);
    },

    /**
     * Sends an event to the context to add or remove all models from the mass
     * collection.
     *
     * @param {boolean} checked `true` to pass all models in the collection to
     *   the mass collection, `false` to remove them.
     *
     *
     * FIXME : Doing this way is slow to check all checkboxes when there
     * are more than 20. We should check checkboxes before adding records to
     * the mass collection SC-4079 will address this problem.
     */
    toggleAll: function(checked) {
        var event = checked ? 'mass_collection:add:all' : 'mass_collection:remove:all';
        this.context.trigger(event, this.model);
    },

    /**
     * Binds mass collection events to a record row checkbox.
     *
     * @private
     */
    _bindModelChangeEvents: function() {
        this.massCollection.on('add', function(model) {
            if (this.model && model.id === this.model.id) {
                this.$(this.fieldTag).prop('checked', true);
            }
        }, this);

        this.massCollection.on('remove', function(model) {
            if (this.model && model.id === this.model.id) {
                this.$(this.fieldTag).prop('checked', false);
            }
        }, this);

        this.massCollection.on('reset', function() {
            this.$(this.fieldTag).prop('checked', !!this.massCollection.get(this.model.id));
        }, this);
    },

    /**
     * Binds mass collection events to 'select-all' checkbox, the one used to check/
     * uncheck all record row checkboxes.
     *
     * @private
     */
    _bindAllModelChangeEvents: function() {
        // Checks/selects the actionmenu checkbox if the checkboxes of each
        // row are all checked.
        this.massCollection.on('all:checked', function() {
            if (this.collection.length !== 0) {
                this.$(this.fieldTag).prop('checked', true);
            }
        }, this);

        // Unchecks/deselects the actionmenu checkbox if the checkboxes of
        // each row are NOT all checked.
        this.massCollection.on('not:all:checked', function() {
            this.$(this.fieldTag).prop('checked', false);
        }, this);

        this.massCollection.on('add', this._onMassCollectionAddAll, this);
        this.massCollection.on('remove reset', this._onMassCollectionRemoveResetAll, this);
    },

    /**
     * Handler for the {@link Bean.Collection massCollection} `add` event.
     *
     * @private
     */
    _onMassCollectionAddAll: function() {
        this.setDropdownDisabled(false);
        if (!this.def.disable_select_all_alert) {
            this.context.trigger('toggleSelectAllAlert');
            this.setButtonsDisabled(this.dropdownFields);
        }
    },

    /**
     * Handler for the {@link Bean.Collection massCollection} `remove` and
     * `reset` events.
     *
     * @private
     */
    _onMassCollectionRemoveResetAll: function() {
        var massCollectionIds = _.pluck(this.massCollection.models, 'id');
        var viewCollectionIds = _.pluck(this.collection.models, 'id');
        if (this.massCollection.length === 0) {
            this.setDropdownDisabled(true);
            //massCollection.models could only have 'id' as an attribute,
            //so we need to compare ids instead of models directly.
        } else if (_.intersection(massCollectionIds, viewCollectionIds).length !== 0) {
            this.setDropdownDisabled(false);
            this.$(this.fieldTag).prop('checked', true);
        }
        if (!this.def.disable_select_all_alert) {
            this.context.trigger('toggleSelectAllAlert');
            this.setButtonsDisabled(this.dropdownFields);
        }
    },

    /**
     * @override
     *
     * Binds events on the collection, and updates the checkboxes
     * consequently.
     */
    bindDataChange: function() {
        if (this.isCheckAllCheckbox) {
            // Listeners on the checkAll/uncheckAll checkbox.
            this._bindAllModelChangeEvents();
            this.action_enabled = this.massCollection.length > 0;
            this.tabIndex = this.action_enabled ? 0 : -1;
        } else {
            // Listeners for each record selection.
            this._bindModelChangeEvents();
        }

        this.massCollection.on('massupdate:estimate', this.onTotalEstimate, this);
    },

    /**
     * Toggles the actionmenu buttons according to the buttons definition and
     * the number of selected records.
     *
     * @param {Object} fields List of the view's fields.
     * @param {number} massCollectionLength The number of selected records.
     */
    setButtonsDisabled: function(fields) {
        _.each(fields, function(field) {
            if (field.def.minSelection || field.def.maxSelection) {
                var min = field.def.minSelection || 0,
                    max = field.def.maxSelection || this.massCollection.length;
                if (this.massCollection.length < min || this.massCollection.length > max) {
                    field.setDisabled(true);
                } else {
                    field.setDisabled(false);
                }
            }
        }, this);
    },

    /**
     * @inheritdoc
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');
        if (this.view.action === 'list' && this.action === 'edit') {
            this.template = app.template.empty;
        }
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        if (!this.isCheckAllCheckbox) {
            // If the model is in the mass collection, make sure the checkbox
            // is checked.
            if (this.massCollection.get(this.model.id)) {
                this.selected = true;
            } else {
                delete this.selected;
            }
        }

        this._super('_render');

        if (this.isCheckAllCheckbox && !this.def.disable_select_all_alert) {
            this.setButtonsDisabled(this.dropdownFields);
            this.setDropdownDisabled(this.massCollection.length === 0);
        }
    },

    /**
     * Since we don't have a default action button we don't need
     * to render anything here. See {@link View.Fields.Base.ActiondropdownField#_renderFields}.
     *
     * @override
     * @protected
     */
    _renderFields: $.noop,

    /**
     * Update the dropdown usability while the total count is estimating.
     */
    onTotalEstimate: function() {
        this.setDropdownDisabled(!this.massCollection.fetched);
    },

    /**
     * Disable the dropdown action.
     *
     * @param {Boolean} [disable] `true` to disable the dropdown action, `false`
     * to enable it.
     */
    setDropdownDisabled: function(disable) {
        this.$(this.actionDropDownTag)
            .toggleClass('disabled', disable)
            .attr('aria-haspopup', !disable)
            .attr('tabindex', disable ? -1 : 0);
    },

    /**
     * @inheritdoc
     */
    _getChildFieldsMeta: function() {
        // We only get the fields (the dropdown actions) metadata for the
        // checkAll/uncheckAll checkbox. Actionmenu fields tied to a model are
        // a simple checkbox and don't have metadata.
        if (this.model.id) {
            return;
        }
        return this._super('_getChildFieldsMeta');
    },

    /**
     * @inheritdoc
     */
    unbindData: function() {
        if (this.massCollection) {
            var modelId = this.model.cid,
                cid = this.view.cid;
            this.massCollection.off(null, null, this);
            if (modelId) {
                this.massCollection.off(null, null, modelId);
            }
            if (cid) {
                this.massCollection.off(null, null, cid);
            }
        }
        this._super('unbindData');
    }
})
