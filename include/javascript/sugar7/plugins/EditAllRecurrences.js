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
(function(app) {

    app.events.on('app:init', function() {

        /**
         * Edit All Recurrences plugin sets up a standard way to enable/disable
         * an 'All Recurrences' mode for record view. When the mode is off,
         * only the single event record is updated. When the mode is on, all
         * event records in the series are updated.
         *
         * This plugin also handles switching from a child record in the series
         * to the parent record (since we require the parent record to control
         * the recurrence information).
         *
         * This plugin is built to enhance {@link View.Views.Base.RecordView}
         * and its descendants.
         */
        app.plugins.register('EditAllRecurrences', ['view'], {
            onAttach: function() {
                this.on('init', function() {
                    // listen for edit all recurrences event on the context
                    this.context.on('all_recurrences:edit', this.editAllRecurrences, this);

                    // coming from a /edit/all_recurrences route
                    if (this.context.get('all_recurrences') === true) {
                        this.toggleAllRecurrencesMode(true);
                        this.context.unset('all_recurrences');
                    } else {
                        this.toggleAllRecurrencesMode(false); // default to off
                    }

                    /**
                     * When the data is loaded, force 'all recurrence' mode if not recurring
                     *
                     * When coming in from an edit route, fields were already toggled to
                     * edit prior to sync, so we need to toggle again
                     */
                    this.model.on('sync', function(model, data, options) {
                        if (options && options.refetch_list_collection) {
                            this._refetchListCollection();
                        }
                        if (!this._isRecurringEvent(this.model)) {
                            this.toggleAllRecurrencesMode(true);
                            if (this.context.get('action') === 'edit') {
                                this.toggleEdit(true);
                            }
                        }
                    }, this);
                });

                /**
                 * override {@link View.Views.Base.RecordView#cancelClicked}
                 *
                 * turn off all recurrences mode on cancel
                 */
                this.cancelClicked = _.wrap(this.cancelClicked, function(_super, event) {
                    _super.call(this, event);
                    this.toggleAllRecurrencesMode(false);
                });

                /**
                 * override {@link View.Views.Base.RecordView#setEditableFields}
                 *
                 * check flag to determine if recurrence fields should be made editable
                 */
                this.setEditableFields = _.wrap(this.setEditableFields, function(_super) {
                    this.toggleEditRecurrenceFields(this.allRecurrencesMode);
                    _super.call(this);
                });

                /**
                 * override {@link View.Views.Base.RecordView#setRoute}
                 *
                 * add all_recurrences to action if in 'all recurrences' mode
                 */
                this.setRoute = _.wrap(this.setRoute, function(_super, action) {
                    if (this.allRecurrencesMode && this._isRecurringEvent(this.model) && action === 'edit') {
                        action = 'edit/all_recurrences';
                    }
                    _super.call(this, action);
                });

                /**
                 * override {@link View.Views.Base.RecordView#handleSave}
                 */
                this.handleSave = _.wrap(this.handleSave, function(_super) {
                    _super.call(this);
                    this._doAfterSave();
                });

                /**
                 * override {@link View.Views.Base.RecordView#getCustomSaveOptions}
                 *
                 * Add recurrence options and set flag to refresh list collection
                 * if editing all recurring meetings.
                 */
                this.getCustomSaveOptions = _.wrap(this.getCustomSaveOptions,
                    _.bind(function(_super, options) {
                        return _.extend(
                            _super.call(this, options),
                            this.addRecurrenceOptionsForSave(options)
                        );
                    }, this)
                );
            },

            /**
             * Puts the record view in edit mode for all event records in
             * the series. If launching from a child record, we switch over to
             * the parent record since that is what controls the recurrence
             * information.
             */
            editAllRecurrences: function() {
                var parentId = this.model.get('repeat_parent_id');
                if (!_.isEmpty(parentId) && parentId !== this.model.id) {
                    this.editAllRecurrencesFromParent(parentId);
                } else {
                    this.toggleAllRecurrencesMode(true);
                    this.context.trigger('button:edit_button:click');
                }
            },

            /**
             * Toggle edit all recurrences mode on/off
             * If event is not recurring, it will remain in all recurrence mode
             * since there is only one meeting to edit
             *
             * @param {Boolean} enabled True turns edit all recurrence mode on
             */
            toggleAllRecurrencesMode: function(enabled) {
                if (!this._isRecurringEvent(this.model)) {
                    enabled = true;
                }

                this.allRecurrencesMode = enabled;
                this.setEditableFields();
            },

            /**
             * Toggle the the recurrence fields between edit/readonly
             *
             * @param {Boolean} editable
             */
            toggleEditRecurrenceFields: function(editable) {
                _.each([
                    'repeat_type',
                    'recurrence',
                    'repeat_interval',
                    'repeat_dow',
                    'repeat_until',
                    'repeat_count',
                    'repeat_days'
                ], function(field) {
                    var $editWrapper = this.$('span.record-edit-link-wrapper[data-name="' + field + '"]');

                    if (editable) {
                        // allow recurrence fields to be editable
                        this.noEditFields = _.without(this.noEditFields, field);
                        $editWrapper.removeClass('hide');
                    } else if (!_.contains(this.noEditFields, field)) {
                        // make all recurrence fields read only
                        this.noEditFields.push(field);
                        $editWrapper.addClass('hide');
                    }
                }, this);
            },

            /**
             * Route to parent record in edit all recurrences mode
             *
             * @param {String} parentId
             */
            editAllRecurrencesFromParent: function(parentId) {
                var route = app.router.buildRoute(this.module, parentId, 'edit/all_recurrences');
                app.router.navigate('#' + route, {trigger: true});
            },

            /**
             * Set the `all_recurrences` to true if in all recurrences mode on save
             * This is used by the API to determine whether to update an individual
             * event or all recurrences of the event.
             *
             * @param {Object} options
             * @return {Object} Options to be added on top of other save options
             */
            addRecurrenceOptionsForSave: function(options) {
                options = options || {};

                if (this.allRecurrencesMode) {
                    options.params = options.params || {};
                    options.params.all_recurrences = true;
                    options.refetch_list_collection = true;
                }

                return options;
            },

            /**
             * Check to see if event is recurring
             * Event is recurring when the repeat type is not blank
             *
             * @param {Data.Bean} model
             * @return {Boolean}
             * @private
             */
            _isRecurringEvent: function(model) {
                return (model.get('repeat_type') !== '');
            },

            /**
             * After saving:
             *
             * 1. Turn off all recurrences mode
             *
             * 2. Disable next/prev buttons if editing all recurrences
             * because this will delete/recreate recurring meetings
             * which may be surrounding the meeting being saved
             */
            _doAfterSave: function() {
                if (this.allRecurrencesMode) {
                    this._disableNextPrevButtons();
                }
                this.toggleAllRecurrencesMode(false);
            },

            /**
             * Disable the next/prev buttons
             *
             * @private
             */
            _disableNextPrevButtons: function() {
                var $nextPrevButtons = this.$('.btn-group-previous-next');
                $nextPrevButtons.find('.next-row').addClass('disabled');
                $nextPrevButtons.find('.previous-row').addClass('disabled');
            },

            /**
             * Enable the next/prev buttons if there are next & previous records
             *
             * @param {Data.BeanCollection} listCollection
             * @private
             */
            _enableNextPrevButtons: function(listCollection) {
                var $nextPrevButtons = this.$('.btn-group-previous-next');

                if (listCollection.hasNextModel(this.model)) {
                    $nextPrevButtons.find('.next-row').removeClass('disabled');
                }
                if (listCollection.hasPreviousModel(this.model)) {
                    $nextPrevButtons.find('.previous-row').removeClass('disabled');
                }
            },

            /**
             * Re-fetch the list collection that is used for the next/previous buttons
             * in the header of the record view. This is needed because updating all
             * recurrences of a meeting will delete and recreate all child meetings, so
             * the list now needs to be updated.
             *
             * @private
             */
            _refetchListCollection: function() {
                var self = this,
                    listCollection = this.context.get('listCollection');

                if (listCollection && listCollection.models && listCollection.models.length > 1) {
                    listCollection.fetch({
                        success: function() {
                            self._enableNextPrevButtons(listCollection);
                        }
                    });
                }
            }
        });
    });
})(SUGAR.App);
