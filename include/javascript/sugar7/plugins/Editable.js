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
         * Editable plug-in will help the view controller's fields switching in edit mode.
         *
         * This plugin register two main features.
         *
         * - toggleFields: switching mode within array of fields.
         * - toggleField: switching mode a single field.
         *                In this case, key and mouse listener will be enabled.
         *                This plugin automatically back from the editable mode
         *                when user clicks escape key or mouse key in out of the field area
         *                (editableHandleMouseDown, editableHandleKeyDown will take care of this feature).
         * To override more key event handler, bind this.on("editable:keydown", function(evt, field)).
         * The trigger will pass two parameters([mouse event], [field]).
         *
         * Once the attached view contains unsaved changes, it will warn the message to user for confirming
         * (this.hasUnsavedChanges must return true when the view contains unsaved changes).
         */
        app.plugins.register('Editable', ['view'], {
            onAttach: function(component, plugin) {
                this.editableKeyDowned = _.bind(function(evt) {
                    this.editableHandleKeyDown.call(this, evt, evt.data.field);
                }, this);

                this.editableMouseClicked = _.bind(function(evt) {
                    this.editableHandleMouseDown.call(this, evt, evt.data.field);
                }, this);

                this.on('init', function() {
                    //event register for preventing actions
                    // when user escapes the page without saving unsaved changes

                    app.routing.before('route', this.beforeRouteChange, this);
                    $(window).on('beforeunload.' + this.cid, _.bind(this.warnUnsavedChangesOnRefresh, this));

                    this.before('unsavedchange', this.beforeViewChange, this);
                    //If drawer is initialized, bind addtional before handler to prevent closing creation view
                    if (_.isEmpty(app.additionalComponents['drawer'])) {
                        return;
                    }

                    //when user confirms exit with unsaved changes, unbind all listeners - no multiple warnings
                    app.events.on('editable:beforehandlers:off', this.unbindBeforeHandler, this);

                    this._currentUrl = Backbone.history.getFragment();
                });
            },

            /**
             * Pre-event handler before current router is changed.
             *
             * Pass `onConfirmRoute` as callback to continue navigating after confirmation.
             *
             * @param {Object} params Parameters that is passed from caller.
             * @return {Boolean} True only if it contains unsaved changes.
             */
            beforeRouteChange: function(params) {
                var onConfirm = _.bind(this.onConfirmRoute, this);
                return this.warnUnsavedChanges(onConfirm);
            },


            /**
             * Pre-event handler before custom unsaved logic is passed.
             *
             * Pass custom callback to continue the following logic after confirmation.
             *
             * @param {Object} param Parameters that is passed from caller. Must contains 'callback'.
             * @return {Boolean} true only if it contains unsaved changes.
             */
            beforeViewChange: function(param) {
                if (!(param && _.isFunction(param.callback))) {
                    app.logger.error('Custom unsavedchange must contain callback function.');
                    return true;
                }
                var onConfirm = _.bind(function() {
                    if (param.callback && _.isFunction(param.callback)) {
                        param.callback.call(this);
                    }
                }, this);
                return this.warnUnsavedChanges(onConfirm, param.message);
            },

            /**
             * Popup dialog message to confirm the unsaved changes.
             *
             * View must override `hasUnsavedChanges` and return true to active the warning dialog.
             *
             * @param {Function} onConfirm Callback function which is executed once the user clicks "ok".
             * @param {String} (customMessage) Custom warning message.
             * @param {Function} (onCancel) Callback function which is executed once the users clicks "cancel".
             * @return {Boolean} True only if it contains unsaved changes.
             */
            warnUnsavedChanges: function(onConfirm, customMessage, onCancel) {
                //When we reload the page after retrying a save, never block it
                if (this.resavingAfterMetadataSync) {
                    return false;
                }
                this.$(':focus').trigger('change');
                if (_.isFunction(this.hasUnsavedChanges) && this.hasUnsavedChanges()) {
                    this._targetUrl = Backbone.history.getFragment();
                    //Replace the url hash back to the current staying page
                    app.router.navigate(this._currentUrl, {trigger: false, replace: true});

                    app.alert.show('leave_confirmation', {
                        level: 'confirmation',
                        messages: app.lang.get(customMessage || 'LBL_WARN_UNSAVED_CHANGES', this.module),
                        onConfirm: onConfirm,
                        onCancel: onCancel || $.noop
                    });
                    return false;
                }
                return true;
            },

            /**
             * Popup browser dialog message to confirm the unsaved changes.
             */
            warnUnsavedChangesOnRefresh: function() {
                //After a 412, prevent navigating away until after the save, but don't show a warning.
                if (this.resavingAfterMetadataSync) {
                    return false;
                }
                if (_.isFunction(this.hasUnsavedChanges) && this.hasUnsavedChanges()) {
                    return app.lang.get('LBL_WARN_UNSAVED_CHANGES', this.module);
                }
                return;
            },

            /**
             * Continue navigating target location once user confirms the discard changes.
             */
            onConfirmRoute: function() {
                //user has confirmed, now turn off all unsaved changes listeners - prevent multiple warnings
                app.events.trigger('editable:beforehandlers:off');
                //if we're in a quick create drawer, it is possible to navigate to same URL
                if (this._currentUrl === this._targetUrl) {
                    app.router.refresh();
                } else {
                    app.router.navigate(this._targetUrl, {trigger: true});
                }
            },

            /**
             * Switches entire fields between detail and edit modes.
             *
             * @param {Array} fields Fields that needs to be toggled.
             * @param {Boolean} isEdit True if it force into edit mode.
             * @param {Function} [callback] Function that is called once all fields are toggled.
             */
            toggleFields: function(fields, isEdit, callback) {
                if (!_.isArray(fields)) {
                    return;
                }

                var viewName = !!isEdit ? 'edit' : this.action;
                var numOfToggledFields = fields.length;
                var view = this;

                _.each(fields, function(field) {
                    if (field.disposed) {
                        // if a field is disposed, skip this field
                        return;
                    }

                    if (field.action === viewName) {
                        field.$el.closest('.record-cell').toggleClass('edit', (viewName === 'edit'));
                        numOfToggledFields--;
                        return; //don't toggle if it's the same
                    }
                    var meta = field.def;
                    if (meta && isEdit && meta.readonly) {
                        numOfToggledFields--;
                        return;
                    }

                    //defer the rendering entire toggling fields asynchronized to enhance the performace.
                    //If it executes the process synchronized, the browser is stuck until all performance is complete.
                    _.defer(function(field) {
                        if (field.disposed !== true) {
                            field.setMode(viewName);
                            field.$el.closest('.record-cell')
                                .toggleClass('edit', (viewName === 'edit'));

                            numOfToggledFields--;

                            if (numOfToggledFields === 0) {
                                if (_.isFunction(callback)) {
                                    callback();
                                }

                                view.trigger('editable:toggle_fields', fields, viewName);
                            }
                        }
                    }, field);

                    this.turnOffFieldEvents(field);
                }, this);
            },

           /**
             * Turns off key and mouse events for a field; useful before containing view is disposed.
             *
             * @param {Object} field A field
             */
            turnOffFieldEvents: function(field) {
                if (_.isFunction(field.unbindKeyDown)) {
                    field.unbindKeyDown(this.editableKeyDowned);
                } else {
                    field.$(field.fieldTag).off('keydown.record', this.editableKeyDowned);
                }
                $(document).off('mousedown.record' + field.name, this.editableMouseClicked);
            },

           /**
             * Turns off key and mouse events for all fields in this Editable view.
             *
             * @param {Object} fields List of fields for an Editable
             */
            turnOffEvents: function(fields) {
                _.each(fields, function(field) {
                    this.turnOffFieldEvents(field);
                }, this);
            },

            /**
             * Switches each individual field between detail and edit modes.
             *
             * It is specially designed for inline edit.
             * Bind default escape key handler for cancelling inline edit mode.
             *
             * @param {View.Field} field Field that needs to be toggled.
             * @param {Boolean} isEdit True if it force into edit mode.
             */
            toggleField: function(field, isEdit) {
                var viewName;

                if (_.isUndefined(isEdit)) {
                    if (field.tplName === this.action || field.tplName === 'erased') {
                        viewName = 'edit';
                    } else {
                        viewName = this.action;
                    }
                } else {
                    viewName = !!isEdit ? 'edit' : this.action;
                }

                if (!field.triggerBefore('toggleField', viewName)) {
                    return false;
                }

                if (field.hasChanged() && viewName === 'detail') {
                    return;
                }

                field.setMode(viewName);

                if (viewName === 'edit') {
                    if (_.isFunction(field.focus)) {
                        field.focus();
                    } else {
                        var $el = field.$(field.fieldTag + ':first');
                        $el.focus().val($el.val());
                    }
                    if (_.isFunction(field.bindKeyDown)) {
                        field.bindKeyDown(this.editableKeyDowned);
                    } else {
                        field.$(field.fieldTag).on('keydown.record', {field: field}, this.editableKeyDowned);
                    }
                    if (_.isFunction(field.bindDocumentMouseDown)) {
                        field.bindDocumentMouseDown(this.editableMouseClicked);
                    } else {
                        $(document).on('mousedown.record' + field.name, {field: field}, this.editableMouseClicked);
                    }
                    field.$el.closest('.record-cell')
                        .toggleClass('edit', true);
                } else {
                    if (_.isFunction(field.unbindKeyDown)) {
                        field.unbindKeyDown();
                    } else {
                        field.$(field.fieldTag).off('keydown.record');
                    }
                    $(document).off('mousedown.record' + field.name);
                    field.$el.closest('.record-cell')
                        .toggleClass('edit', false);
                }
            },

            /**
             * Move focus to next or prev field.
             *
             * Toggles old field to detail mode and next or prev field to edit mode.
             * Calls {@link app.plugins.ToggleMoreLess#toggleMoreLess} to show hidden panel.
             *
             * @param {View.Field} field Current focused field (field in inline-edit mode).
             * @param {String|nextField} direction Determinate which field
             *        should be activated next or prev.
             */
            nextField: function(field, direction) {
                if (!field) {
                    return;
                }

                field.$(field.fieldTag).trigger('change');

                direction = _.contains(['nextField', 'prevField'], direction) ?
                    direction : 'nextField';

                var nextField = field[direction];

                if (!nextField) {
                    return;
                }

                if (_.isFunction(this.toggleMoreLess) &&
                    nextField.$el.closest('.panel_hidden').hasClass('hide')
                ) {
                    this.toggleMoreLess('more');
                }

                this.toggleField(field, false);

                while (nextField.isDisabled()) {
                    if (nextField[direction]) {
                        nextField = nextField[direction];
                    } else {
                        break;
                    }
                }

                if (!nextField.isDisabled()) {
                    this.toggleField(nextField, true);
                }
            },

            /**
             * Returns the editable fields of the view.
             * Forms doubly linked list between elements in array.
             *
             * @param {Object[]} fields Fields of the view.
             * @param {string[]} noEditFields List of non-editable field names.
             * @return {Object[]} Array of editable fields of the view.
             */
            getEditableFields: function(fields, noEditFields) {
                var editableFields = [];

                _.each(fields, function(field) {
                    var readonlyField = this._isReadOnly(field, noEditFields);
                    if (!readonlyField) {
                        editableFields.push(field);
                    }
                }, this);

                this._formDoublyLinkedList(editableFields);

                return editableFields;
            },

            /**
             * Forms a doubly linked list with fields.
             *
             * @param {Object[]} fields Array of fields.
             * @private
             **/
            _formDoublyLinkedList: function(fields) {
                if (fields.length <= 1) {
                    return;
                }

                var firstField;
                var previousField;

                _.each(fields, function(field) {
                    if (previousField) {
                        previousField.nextField = field;
                        field.prevField = previousField;
                    } else {
                        firstField = field;
                    }

                    previousField = field;
                });

                if (previousField) {
                    previousField.nextField = firstField;
                    firstField.prevField = previousField;
                }
            },

            /**
             * Returns true if field is read-only, and false otherwise.
             *
             * @param {Object} field The Field object.
             * @param {string[]} noEditFields List of non-editable field names.
             * @return {boolean} `true` if the field is readonly
             * @private
             */
            _isReadOnly: function(field, noEditFields) {
                var isLocked = _.contains(this.model.get('locked_fields'), field.def.name);

                if (field.def.readonly ||
                    (field.def.type !== 'fieldset' && isLocked) ||
                    _.indexOf(noEditFields, field.name) >= 0) {
                    return true;
                }

                return false;
            },

            /**
             * Bind default mouse click handler for inline edit mode.
             *
             * Once user clicks the out of the field area, it will cancel the inilne edit mode.
             *
             * @param {Window.Event} evt Mouse event.
             * @param {View.Field} field Field that is in inline edit mode.
             */
            editableHandleMouseDown: function(evt, field) {
                if (field.tplName === this.action) {
                    return;
                }

                var currFieldParent = field.$el,
                    targetPlaceHolder = this.$(evt.target).parents("span[sfuuid='" + field.sfId + "']"),
                    preventPlaceholder = this.$(evt.target).closest('.prevent-mousedown');

                // When mouse clicks the document, it should maintain the edit mode within the following cases
                // - Some fields (like email) may have buttons and the mousedown event will fire before the one
                //   attached to the button is fired. As a workaround we wrap the buttons with .prevent-mousedown
                var inPreventPlaceholder = (preventPlaceholder.length > 0);
                // - If mouse is clicked within the same field placeholder area
                var inTargetPlaceholder = (targetPlaceHolder.length > 0);
                // - If cursor is focused among the field's input elements
                var isFocusInField = (currFieldParent.find(':focus').length > 0);
                var drawerOpened = !_.isEmpty(app.drawer._components);
                if (inPreventPlaceholder || inTargetPlaceholder || isFocusInField || drawerOpened) {
                    return;
                }
                this.toggleField(field, false);
                this.trigger('editable:mousedown', evt, field);
            },

            /**
             * Bind key handlers for inline edit mode.
             *
             * Attach default escape key handler for cancelling inline edit mode.
             * Custom handlers that is attached on current view's `editable:keydown` will be triggered in order.
             *
             * @param {Window.Event} evt Mouse event.
             * @param {View.Field} field Field that is in inline edit mode.
             */
            editableHandleKeyDown: function(evt, field) {
                if (evt.which == 27) { // If esc
                    this.toggleField(field, false);
                }
                this.trigger('editable:keydown', evt, field);
            },

            /**
             * Detach the event handlers for warning unsaved changes.
             */
            unbindBeforeHandler: function() {

                app.routing.offBefore('route', this.beforeRouteChange, this);
                $(window).off('beforeunload.' + this.cid);

                if (_.isEmpty(app.additionalComponents['drawer'])) {
                    return;
                }
                app.drawer.offBefore('reset', this.beforeRouteChange, this);
                this.offBefore('unsavedchange');
            },

            /**
             * @inheritdoc
             * Unbind anonymous functions for key and mouse handlers.
             * Unbind beforeHandlers.
             */
            onDetach: function() {
                $(document).off('mousedown', this.editableMouseClicked);
                this.editableKeyDowned = null;
                this.editableMouseClicked = null;
                app.events.off('editable:beforehandlers:off', null, this);
                this.unbindBeforeHandler();
            }
        });
    });
})(SUGAR.App);
