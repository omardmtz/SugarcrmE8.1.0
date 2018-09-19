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
 * @class View.Views.Base.ForecastsListHeaderpaneView
 * @alias SUGAR.App.view.layouts.BaseForecastsListHeaderpaneView
 * @extends View.Views.Base.ListHeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    plugins: ['FieldErrorCollection'],

    /**
     * If the Save button should be disabled or not
     * @type Boolean
     */
    saveBtnDisabled: true,

    /**
     * If the Commit button should be disabled or not
     * @type Boolean
     */
    commitBtnDisabled: true,

    /**
     * If any fields in the view have errors or not
     * @type Boolean
     */
    fieldHasErrorState: false,

    /**
     * The Save Draft Button Field
     * @type View.Fields.Base.ButtonField
     */
    saveDraftBtnField: undefined,

    /**
     * The Commit Button Field
     * @type View.Fields.Base.ButtonField
     */
    commitBtnField: undefined,

    /**
     * If Forecasts' data sync is complete and we can render buttons
     * @type Boolean
     */
    forecastSyncComplete: false,

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.layout.context.on('forecasts:sync:start', function() {
            this.forecastSyncComplete = false;
            this.setButtonStates();
        }, this);
        this.layout.context.on('forecasts:sync:complete', function() {
            this.forecastSyncComplete = true;
            this.setButtonStates();
        }, this);

        this.on('render', function() {
            // switching from mgr to rep leaves $el null, so make sure we grab a fresh reference
            // to the field if it's there but $el is null in the current reference
            if (!this.saveDraftBtnField || (this.saveDraftBtnField && _.isNull(this.saveDraftBtnField.$el))) {
                // get reference to the Save Draft button Field
                this.saveDraftBtnField = this.getField('save_draft_button');
            }
            if (!this.commitBtnField || (this.commitBtnField && _.isNull(this.commitBtnField.$el))) {
                // get reference to the Commit button Field
                this.commitBtnField = this.getField('commit_button');
            }

            this.saveDraftBtnField.setDisabled();
            this.commitBtnField.setDisabled();
        }, this);

        this.context.on('change:selectedUser', function(model, changed) {
            this._title = changed.full_name;
            if (!this.disposed) {
                this.render();
            }
        }, this);

        this.context.on('plugin:fieldErrorCollection:hasFieldErrors', function(collection, hasErrors) {
            if(this.fieldHasErrorState !== hasErrors) {
                this.fieldHasErrorState = hasErrors;
                this.setButtonStates();
            }
        }, this)

        this.context.on('button:print_button:click', function() {
            window.print();
        }, this);

        this.context.on('forecasts:worksheet:is_dirty', function(worksheet_type, is_dirty) {
            is_dirty = !is_dirty;
            if (this.saveBtnDisabled !== is_dirty || this.commitBtnDisabled !== is_dirty) {
                this.saveBtnDisabled = is_dirty;
                this.commitBtnDisabled = is_dirty;
                this.setButtonStates();
            }
        }, this);

        this.context.on('button:commit_button:click button:save_draft_button:click', function() {
            if (!this.saveBtnDisabled || !this.commitBtnDisabled) {
                this.saveBtnDisabled = true;
                this.commitBtnDisabled = true;
                this.setButtonStates();
            }
        }, this);

        this.context.on('forecasts:worksheet:saved', function(totalSaved, worksheet_type, wasDraft) {
            if(wasDraft === true && this.commitBtnDisabled) {
                this.commitBtnDisabled = false;
                this.setButtonStates();
            }
        }, this);

        this.context.on('forecasts:worksheet:needs_commit', function(worksheet_type) {
            if (this.commitBtnDisabled) {
                this.commitBtnDisabled = false;
                this.setButtonStates();
            }
        }, this);

        this._super('bindDataChange');
    },

    /**
     * Sets the Save Button and Commit Button to enabled or disabled
     */
    setButtonStates: function() {
        // make sure all data sync has finished before updating button states
        if(this.forecastSyncComplete) {
            // fieldHasErrorState trumps the disabled flags, but when it's cleared
            // revert back to whatever states the buttons were in
            if (this.fieldHasErrorState) {
                this.saveDraftBtnField.setDisabled(true);
                this.commitBtnField.setDisabled(true);
                this.commitBtnField.$('.commit-button').tooltip();
            } else {
                this.saveDraftBtnField.setDisabled(this.saveBtnDisabled);
                this.commitBtnField.setDisabled(this.commitBtnDisabled);

                if (!this.commitBtnDisabled) {
                    this.commitBtnField.$('.commit-button').tooltip('destroy');
                } else {
                    this.commitBtnField.$('.commit-button').tooltip();
                }
            }
        } else {
            // disable buttons while syncing
            if(this.saveDraftBtnField) {
                this.saveDraftBtnField.setDisabled(true);
            }
            if(this.commitBtnField) {
                this.commitBtnField.setDisabled(true);
            }
        }
    },

    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        if(!this._title) {
            var user = this.context.get('selectedUser') || app.user.toJSON();
            this._title = user.full_name;
        }

        this._super('_renderHtml');
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if(this.layout.context) {
            this.layout.context.off('forecasts:sync:start', null, this);
            this.layout.context.off('forecasts:sync:complete', null, this);
        }
        this._super('_dispose');
    }
})
