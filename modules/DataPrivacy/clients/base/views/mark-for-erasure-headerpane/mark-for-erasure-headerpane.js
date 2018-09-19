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
 * @class View.Views.Base.MarkForErasureHeaderpaneView
 * @alias SUGAR.App.view.views.BaseMarkForErasureHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    events: {
        'click a[name=close_button]': 'close',
        'click a[name=mark_for_erasure_button]': 'markForErasure'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        app.shortcuts.register({
            id: 'MarkForErasureHeaderPanel:Close',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $closeButton = this.$('a[name=close_button]');
                if ($closeButton.is(':visible') && !$closeButton.hasClass('disabled')) {
                    $closeButton.click();
                }
            }
        });
    },

    /**
     * @inheritdoc
     *
     * Also bind mass collection events.
     */
    bindDataChange: function() {
        this._super('bindDataChange');
        this.context.once('change:mass_collection', this._addMassCollectionListener, this);
        this.context.on('markforerasure:masscollection:init', function(models) {
            this._initialModels = _.clone(models);
        }, this);
    },

    /**
     * Closes the drawer.
     */
    close: function() {
        app.drawer.close();
    },

    /**
     * Mark the currently selected fields for erasure.
     */
    markForErasure: function() {
        this.context.trigger('markforerasure:mark');
    },

    /**
     * Set up `add`, `remove` and `reset` listeners on the `mass_collection` so
     * we can enable/disable the merge button whenever the collection changes.
     *
     * @private
     */
    _addMassCollectionListener: function() {
        var massCollection = this.context.get('mass_collection');
        massCollection.on('add remove reset', this._toggleMarkForErasureButton, this);
    },

    /**
     * Check if we should disable the Mark for Erasure button.
     * We disable if the list of fields selected differs in any way from the
     * currently saved list.
     *
     * @return {boolean} `true` if we should disable the button; `false` if
     *   we should not.
     * @private
     */
    _shouldDisable: function() {
        var massCollection = this.context.get('mass_collection');
        if (!this._initialModels) {
            // No fields to erase were selected; disable by default
            return true;
        } else if (this._initialModels.length !== massCollection.length) {
            return false;
        }

        // Mass collection and initial mass collection have same number of models, no choice but
        // to compare them one-by-one
        return _.every(this._initialModels, function(model) {
            return massCollection.get(model.id);
        });
    },

    /**
     * Enable or disable the mark for erasure button as appropriate.
     */
    _toggleMarkForErasureButton: function() {
        this.$('[name="mark_for_erasure_button"]').toggleClass('disabled', this._shouldDisable());
    },

    /*
     * @override
     *
     * Overriding to show record name on title header if it is available;
     * if not, use the standard title.
     */
    _formatTitle: function(title) {
        var recordName;
        var model = this.context.get('modelForErase');

        // Special case for `Person` type modules
        if (model.fields && model.fields.name && model.fields.name.type == 'fullname') {
            recordName = app.utils.formatNameModel(model.module, model.attributes);
        } else {
            recordName = app.utils.getRecordName(model);
        }

        if (recordName) {
            return app.lang.get('TPL_DATAPRIVACY_PII_TITLE', model.module, {name: recordName});
        } else if (title) {
            return app.lang.get(title, this.module);
        } else {
            return '';
        }
    },
})
