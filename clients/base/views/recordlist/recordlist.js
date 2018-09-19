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
 * @class View.Views.Base.RecordlistView
 * @alias SUGAR.App.view.views.BaseRecordlistView
 * @extends View.Views.Base.FlexListView
 */
({
    extendsFrom: 'FlexListView',
    plugins: [
        'SugarLogic',
        'ReorderableColumns',
        'ResizableColumns',
        'ListColumnEllipsis',
        'ErrorDecoration',
        'Editable',
        'MergeDuplicates',
        'Pagination',
        'MassCollection'
    ],

    /**
     * List of current inline edit models.
     *
     * @property
     */
    toggledModels: null,

    rowFields: {},

    contextEvents: {
        "list:editall:fire": "toggleEdit",
        "list:editrow:fire": "editClicked",
        "list:deleterow:fire": "warnDelete"
    },

    /**
     * @override
     * @param {Object} options
     */
    initialize: function(options) {
        //Grab the record list of fields to display from the base metadata
        var recordListMeta = this._initializeMetadata(options);
        //Allows sub-views to override and use different view metadata if desired
        options.meta = this._filterMeta(_.extend({}, recordListMeta, options.meta || {}), options);
        this._super("initialize", [options]);

        //Extend the prototype's events object to setup additional events for this controller
        this.events = _.extend({}, this.events, {
            'click [name=inline-cancel]' : 'resize',
            'keydown': '_setScrollPosition'
        });

        this.toggledModels = {};

        this._addAdditionalFields();

        this._currentUrl = Backbone.history.getFragment();

        this._bindEvents();
    },

    /**
     * Bind various events that are associated with this view.
     *
     * @protected
     */
    _bindEvents: function() {
        this.on('render render:rows', this._setRowFields, this);

        //fire resize scroll-width on column add/remove
        this.on('list:toggle:column', this.resize, this);
        this.on('mergeduplicates:complete', this.refreshCollection, this);
        this.on('field:focus:location', this.setPanelPosition, this);

        if (this.layout) {
            this.layout.on('list:mergeduplicates:fire', this.mergeDuplicatesClicked, this);

            // We listen for if the search filters are opened or not. If so, when
            // user clicks show more button, we treat this as a search, otherwise,
            // normal show more for list view.
            this.layout.on('list:filter:toggled', this.filterToggled, this);

            // The `MassCollection` plugin triggers these events when it shows an
            // alert and the table height changes.
            this.layout.on('list:alert:show list:alert:hide', this._refreshReorderableColumns, this);
        }

        //event register for preventing actions
        // when user escapes the page without confirming deleting
        app.routing.before('route', this.beforeRouteDelete, this);
        $(window).on('beforeunload.delete' + this.cid, _.bind(this.warnDeleteOnRefresh, this));
    },

    /**
     * Update the filter enable status.
     *
     * @param {Boolean} isOpened Value whether the filter is opened.
     */
    filterToggled: function(isOpened) {
        this.context.set('filterOpened', isOpened);
    },

    /**
     * Add the opened filter options to the paginate query.
     * Please see the {@link Pagination#getNextPagination} for detail.
     *
     * @return {Object} Pagination fetch options.
     */
    getPaginationOptions: function() {
        // If in "search mode" (the search filter is toggled open) set q:term param
        var options = this.context.get('filterOpened') ? this.getSearchOptions() : {};

        return options;
    },

    /**
     * Add the previous typed search term.
     *
     * @return {Object} Pagination fetch options.
     */
    getSearchOptions: function() {
        var collection, options, previousTerms, term = '';
        collection = this.context.get('collection');

        // If we've made a previous search for this module grab from cache
        if (app.cache.has('previousTerms')) {
            previousTerms = app.cache.get('previousTerms');
            if (previousTerms) {
                term = previousTerms[this.module];
            }
        }
        // build search-specific options and return
        options = {
            params: {
                q: term
            },
            fields: collection.fields ? collection.fields : this.collection
        };
        return options;
    },

    /**
     * Retrieve the metadata of the recordlist view
     *
     * @return {Object}
     * @private
     */
    _initializeMetadata: function() {
        return app.metadata.getView(null, 'recordlist') || {};
    },

    /**
     * Filters the given meta removing non-applicable portions
     * @param {Object} meta data to be filtered
     * @return {Object}
     * @private
     */
    _filterMeta : function(meta, options) {
        // Don't show the update calc field option if the module has no calculated
        // fields or the user is not a dev for that module
        var context = options.context;
        var isDeveloper = app.acl.hasAccess("developer", context.get("module"));
        var hasCalcFields = context && context.get("model") && !!_.find(context.get("model").fields, function(def) {
            return def && def.calculated && def.calculated != "false";
        });
        // Used in sanitizing subpanel row actions for Tags module
        var isTagsParent = options.context.get('parentModule') === 'Tags';

        if ((!isDeveloper || !hasCalcFields) && meta.selection && meta.selection.actions) {
            meta.selection.actions = _.reject(meta.selection.actions, function(action) {
                return action.name == "calc_field_button";
            });
        }

        // Handle Tags module specific rules. Yes, this is dirty, but given how
        // Subpanels on Tags need to be treated, this is just about the only way
        // to do this
        if (isTagsParent && meta.rowactions && meta.rowactions.actions) {
            // Tags does not support Unlinking of records in subpanels, so we
            // need to traverse the row actions array of options.meta and, if
            // any of the rowactions is a type unlink-action we need to remove
            // it from the rowactions array
            meta.rowactions.actions = _.reject(meta.rowactions.actions, function(row) {
                return row.type === 'unlink-action';
            });
        }

        return meta;
    },

    /**
     * Refresh the current collection set.
     */
    refreshCollection: function() {
        this.collection.fetch();
    },

    addActions:function () {
        if (this.actionsAdded) return;
        this._super("addActions");
        if(_.isUndefined(this.leftColumns[0])){
            //Add blank left column to contain favorite and inline-cancel buttons
            this.leftColumns.push({
                'type':'fieldset',
                'label': '',
                'sortable': false,
                'fields': []
            });
        }
        //Add Favorite to left
        this.addFavorite();

        //Add Save & Cancel
        var firstLeftColumn = this.leftColumns[0];
        if (firstLeftColumn && _.isArray(firstLeftColumn.fields)) {
            //Add Cancel button to left
            firstLeftColumn.fields.push({
                type:'editablelistbutton',
                label:'LBL_CANCEL_BUTTON_LABEL',
                name:'inline-cancel',
                css_class:'btn-link btn-invisible inline-cancel'
            });
            this.leftColumns[0] = firstLeftColumn;
        }
        var firstRightColumn = this.rightColumns[0];
        if (firstRightColumn && _.isArray(firstRightColumn.fields)) {
            //Add Save button to right
            firstRightColumn.css_class = 'overflow-visible';
            firstRightColumn.fields.push({
                type:'editablelistbutton',
                label:'LBL_SAVE_BUTTON_LABEL',
                name:'inline-save',
                css_class:'btn-primary'
            });
            this.rightColumns[0] = firstRightColumn;
        }
        this.actionsAdded = true;
    },

    /**
     * Add favorite column
     */
    addFavorite: function() {
        var favoritesEnabled = app.metadata.getModule(this.module, "favoritesEnabled");
        if (favoritesEnabled !== false
            && this.meta.favorite && this.leftColumns[0] && _.isArray(this.leftColumns[0].fields)) {
            this.leftColumns[0].fields.push({type:'favorite'});
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
            if (field.model && field.model.id && _.isUndefined(field.parent)) {
                this.rowFields[field.model.id] = this.rowFields[field.model.id] || [];
                this.rowFields[field.model.id].push(field);
            }
        }, this);
    },

    /**
     * Stores the current scrolling position of the list content when tab key is
     * pressed.
     *
     * @param {Event} event The keydown event.
     * @private
     */
    _setScrollPosition: function(event) {
        if (event.keyCode === 9) {
            var $flexListContent = this.$('.flex-list-view-content');
            $flexListContent.data('previousScrollLeftValue', $flexListContent.scrollLeft());
        }
    },

    /**
     * Retrieves the location of the edges of the list viewport and caches it
     * to `this._bordersPosition`.
     *
     * @return {Object} Object with properties:
     * @return {number} return.left the position left edge.
     * @return {number} return.right the position right edge.
     * @private
     */
    _getBordersPosition: function() {
        if (!this._bordersPosition) {

            /**
             * Object containing the location of left and right edges of the
             * list viewport.
             *
             * @property {Object} _bordersPosition
             * @property {number} _bordersPosition.left The left offset of the
             *   left edge of the viewport.
             * @property {number} _bordersPosition.right The left offset of the
             *   right edge of the viewport.
             * @private
             */
            this._bordersPosition = {};
            var thSelector = {};
            var $scrollPanel = this.$('.flex-list-view-content');
            var rtl = app.lang.direction === 'rtl';

            thSelector.left = rtl ? 'last' : 'first';
            thSelector.right = rtl ? 'first' : 'last';
            this._bordersPosition.left = $scrollPanel.find('thead tr:first th:' + thSelector.left).outerWidth();
            this._bordersPosition.right = $scrollPanel.find(
                'thead tr:first th:' + thSelector.right).children().position().left;
        }
        return this._bordersPosition;
    },

    /**
     * Sets the position of the current list panel.
     *
     * Doesn't adjust panel position if the focused field is fully visible in
     * the viewport.
     *
     * @param {Object} location Position of the focused element relative to its
     *   viewport.
     * @param {number} location.left The distance between the left
     *   border of the focused field and the left border of the viewport.
     * @param {number} location.right The distance between the right
     *   side of the focused field and the left border of the viewport.
     */
    setPanelPosition: function(location) {
        var bordersPosition = this._getBordersPosition();
        var fieldLeft = location.left;
        var fieldRight = location.right;
        if (fieldRight <= bordersPosition.right && fieldLeft >= bordersPosition.left) {
            return;
        }
        this._scrollToMakeFieldVisible(bordersPosition.left, bordersPosition.right, location);
    },

    /**
     * Scrolls the list horizontally to make the clicked field fully visible.
     *
     * @param {number} leftBorderPosition Position of the left edge of the
     *   list viewport.
     * @param {number} rightBorderPosition Position of the right edge of the
     *   list viewport.
     * @param {Object} location Position of the focused element relative to its
     *   viewport.
     * @param {number} location.left The distance between the left
     *   border of the focused field and the left border of the viewport.
     * @param {number} location.right The distance between the right
     *   side of the focused field and the left border of the viewport.
     * @private
     */
    _scrollToMakeFieldVisible: function(leftBorderPosition, rightBorderPosition, location) {
        var $scrollPanel = this.$('.flex-list-view-content');
        var scrollPosition = $scrollPanel.scrollLeft();
        var fieldLeft = location.left;
        var fieldRight = location.right;
        var fieldPadding = location.fieldPadding;
        var distanceToScroll;

        if (fieldLeft < leftBorderPosition) {
            distanceToScroll = fieldLeft - leftBorderPosition - fieldPadding;
        } else if (rightBorderPosition < fieldRight) {
            distanceToScroll = fieldRight - rightBorderPosition + fieldPadding;
        } else {
            return;
        }
        if (app.lang.direction === 'rtl' && $.support.rtlScrollType === 'reverse') {
            distanceToScroll = - distanceToScroll;
        }
        $scrollPanel.scrollLeft(scrollPosition + distanceToScroll);
    },

    /**
     * Delete the model once the user confirms the action
     */
    deleteModel: function() {
        var self = this,
            model = this._modelToDelete;

        model.destroy({
            //Show alerts for this request
            showAlerts: {
                'process': true,
                'success': {
                    messages: self.getDeleteMessages(self._modelToDelete).success
                }
            },
            success: function() {
                var redirect = self._targetUrl !== self._currentUrl;
                self._modelToDelete = null;
                self.collection.remove(model, { silent: redirect });
                if (redirect) {
                    self.unbindBeforeRouteDelete();
                    //Replace the url hash back to the current staying page
                    app.router.navigate(self._targetUrl, {trigger: true});
                    return;
                }
                app.events.trigger("preview:close");
                if (!self.disposed) {
                    self.render();
                }

                self.layout.trigger("list:record:deleted", model);
            }
        });
    },

    /**
     * Pre-event handler before current router is changed
     *
     * @return {Boolean} true to continue routing, false otherwise
     */
    beforeRouteDelete: function () {
        if (this._modelToDelete) {
            this.warnDelete(this._modelToDelete);
            return false;
        }
        return true;
    },

    /**
     * Formats the messages to display in the alerts when deleting a record.
     *
     * @param {Data.Bean} model The model concerned.
     * @return {Object} The list of messages.
     * @return {string} return.confirmation Confirmation message.
     * @return {string} return.success Success message.
     */
    getDeleteMessages: function(model) {
        var messages = {};
        var name = Handlebars.Utils.escapeExpression(this._getNameForMessage(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' "' + name + '"';

        messages.confirmation = app.utils.formatString(
            app.lang.get('NTC_DELETE_CONFIRMATION_FORMATTED', this.module),
            [context]
        );
        messages.success = app.utils.formatString(app.lang.get('NTC_DELETE_SUCCESS'), [context]);
        return messages;
    },

    /**
     * Retrieves the name of a record
     *
     * @param {Data.Bean} model The model concerned.
     * @return {string} name of the record.
     */
    _getNameForMessage: function(model) {
        return app.utils.getRecordName(model);
    },

    /**
     * Popup dialog message to confirm delete action
     *
     * @param {Backbone.Model} model the bean to delete
     */
    warnDelete: function(model) {
        var self = this;
        this._modelToDelete = model;

        self._targetUrl = Backbone.history.getFragment();
        //Replace the url hash back to the current staying page
        if (self._targetUrl !== self._currentUrl) {
            app.router.navigate(self._currentUrl, {trigger: false, replace: true});
        }

        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: self.getDeleteMessages(model).confirmation,
            onConfirm: _.bind(self.deleteModel, self),
            onCancel: function() {
                self._modelToDelete = null;
            }
        });
    },

    /**
     * Popup browser dialog message to confirm delete action
     *
     * @return {String} the message to be displayed in the browser dialog
     */
    warnDeleteOnRefresh: function() {
        if (this._modelToDelete) {
            return this.getDeleteMessages(this._modelToDelete).confirmation;
        }
    },

    /**
     * {@link app.plugins.view.editable}
     * Compare with last fetched data and return true if model contains changes.
     * if model contains changed attributes,
     * check whether those are among the editable fields or not.
     *
     * @return {Boolean} True if current inline edit model contains unsaved changes.
     */
    hasUnsavedChanges: function() {
        var firstKey = _.first(_.keys(this.rowFields)),
            formFields = [];

        _.each(this.rowFields[firstKey], function(field) {
            if (field.name) {
                formFields.push(field.name);
            }
            //Inspect fieldset children fields
            if (field.def.fields) {
                formFields = _.chain(field.def.fields)
                    .pluck('name')
                    .compact()
                    .union(formFields)
                    .value();
            }
        }, this);
        return _.some(_.values(this.toggledModels), function(model) {
            var changedAttributes = model.changedAttributes(model.getSynced());

            if (_.isEmpty(changedAttributes)) {
                return false;
            }
            var unsavedFields = _.intersection(_.keys(changedAttributes), formFields);
            return !_.isEmpty(unsavedFields);
        }, this);
    },

    /**
     * Handles merge button.
     */
    mergeDuplicatesClicked: function() {
        this.mergeDuplicates(this.context.get('mass_collection'));
    },

    /**
     * Toggle the selected model's fields when edit is clicked.
     *
     * @param {Backbone.Model} model Selected row's model.
     */
    editClicked: function(model, field) {
        // If a field is locked, we don't allow inline editing. Instead show an alert that links
        // to the record view or editview to make changes there.
        if (!_.isEmpty(model.get('locked_fields'))) {
            this._showLockedFieldWarning(model);
            return;
        }
        if (field.def.full_form) {
            var parentModel = this.context.parent.get('model');
            var link = this.context.get('link');

            // `app.bwc.createRelatedRecord` navigates to the BWC EditView if an
            // id is passed to it.
            app.bwc.createRelatedRecord(this.module, parentModel, link, model.id);
        } else {
            this.toggleRow(model.id, true);
            //check to see if horizontal scrolling needs to be enabled
            this.resize();
        }
        if (!_.isEqual(model.attributes, model._syncedAttributes)) {
            model.setSyncedAttributes(model.attributes);
        }
    },

    /**
     * Show a warning alert about locked fields on the model. The warning will
     * link to the Sidecar record view in edit mode or BWC edit view
     *
     * @param {Backbone.Model} model the model for the row we are editing
     * @private
     */
    _showLockedFieldWarning: function(model) {
        var route = app.router.buildRoute(model.module, model.id, 'edit');
        var recordName = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model));
        var message = app.lang.get(
            'LBL_LOCKED_FIELD_INLINE_EDIT',
            model.module,
            {link: new Handlebars.SafeString('<a href="javascript:void(0);">' + recordName + '</a>')}
        );
        var module = app.metadata.getModule(model.module);
        app.alert.show('locked_field_inline_edit', {
            level: 'warning',
            messages: message,
            autoClose: false,
            onLinkClick: function() {
                app.alert.dismiss('locked_field_inline_edit');
                var trigger = module.isBwcEnabled;
                if (!trigger) {
                    // We need to load the view here to add lockedFieldWarning to the context
                    // for sidecar modules
                    app.controller.loadView({
                        layout: 'record',
                        module: model.module,
                        modelId: model.id,
                        action: 'edit',
                        lockedFieldsWarning: false
                    });
                }
                app.router.navigate(route, {trigger: trigger});
            }
        });
    },

    /**
     * Toggle editable selected row's model fields.
     *
     * @param {String} modelId Model Id.
     * @param {Boolean} isEdit True for edit mode, otherwise toggle back to list mode.
     */
    toggleRow: function(modelId, isEdit) {
        if (isEdit) {
            this.toggledModels[modelId] = this.collection.get(modelId);
        } else {
            delete this.toggledModels[modelId];
        }
        this.$('tr[name=' + this.module + '_' + modelId + ']').toggleClass('tr-inline-edit', isEdit);
        this.toggleFields(this.rowFields[modelId], isEdit);
    },

    /**
     * Detach the event handlers for warning delete
     */
    unbindBeforeRouteDelete: function() {
        app.routing.offBefore("route", this.beforeRouteDelete, this);
        $(window).off("beforeunload.delete" + this.cid);
    },

    /**
     * @override
     * @private
     */
    _dispose: function(){
        this.unbindBeforeRouteDelete();
        this._super('_dispose');
        this.rowFields = null;
    },

    /**
     * Adds additional fields to the context.
     *
     * @private
     */
    _addAdditionalFields: function() {
        if (this.meta.favorite) {
            this.context.addFields(['my_favorite']);
        }

        if (this.meta.following) {
            this.context.addFields(['following']);
        }
    },

    /**
     * Register keyboard shortcuts.
     */
    registerShortcuts: function() {
        var clickButton = function($button) {
            if ($button.is(':visible') && !$button.hasClass('disabled')) {
                $button.click();
            }
        };

        this._super('registerShortcuts');

        app.shortcuts.register({
            id: 'List:Inline:Edit',
            keys: 'e',
            component: this,
            description: 'LBL_SHORTCUT_EDIT_SELECTED',
            handler: function() {
                var self = this;
                if (this.$('.selected [name=inline-cancel]:visible').length === 0) {
                    this.$('.selected [data-toggle=dropdown]:visible').click();
                    this.$('.selected [name=edit_button]:visible').click();
                    _.defer(function() {
                        self.$('.selected input:first').focus();
                    });
                }
            }
        });

        app.shortcuts.register({
            id: 'List:Delete',
            keys: 'd',
            component: this,
            description: 'LBL_SHORTCUT_RECORD_DELETE',
            handler: function() {
                if (this.$('.selected [name=inline-cancel]:visible').length === 0) {
                    this.$('.selected [data-toggle=dropdown]:visible').click().blur();
                    this.$('.selected [name=delete_button]:visible').click();
                }
            }
        });

        app.shortcuts.register({
            id: 'List:Inline:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CANCEL_INLINE_EDIT',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('.selected [name=inline-cancel]'),
                    $focusedInlineEditRow = $(event.target).closest('.tr-inline-edit');

                if ($cancelButton.length > 0) {
                    clickButton($cancelButton);
                } else if ($focusedInlineEditRow.length > 0) {
                    clickButton($focusedInlineEditRow.find('[name=inline-cancel]'));
                }
            }
        });

        app.shortcuts.register({
            id: 'List:Inline:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_SAVE',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('.selected [name=inline-save]'),
                    $focusedInlineEditRow = $(event.target).closest('.tr-inline-edit');

                if ($saveButton.length > 0) {
                    clickButton($saveButton);
                } else if ($focusedInlineEditRow.length > 0) {
                    clickButton($focusedInlineEditRow.find('[name=inline-save]'));
                }
            }
        });

        app.shortcuts.register({
            id: 'List:Favorite',
            keys: 'f a',
            component: this,
            description: 'LBL_SHORTCUT_FAVORITE_RECORD',
            handler: function() {
                this.$('.selected .fa-favorite:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'List:Follow',
            keys: 'f o',
            component: this,
            description: 'LBL_SHORTCUT_FOLLOW_RECORD',
            handler: function() {
                this.$('.selected [data-toggle=dropdown]:visible').click().blur();
                this.$('.selected [name=follow_button]:visible').click();
            }
        });

        app.shortcuts.register({
            id: 'List:Preview',
            keys: 'p',
            component: this,
            description: 'LBL_SHORTCUT_PREVIEW_SELECTED',
            handler: function() {
                clickButton(this.$('.selected [data-event="list:preview:fire"]:visible'));
            }
        });

        app.shortcuts.register({
            id: 'List:Select',
            keys: 'x',
            component: this,
            description: 'LBL_SHORTCUT_MARK_SELECTED',
            handler: function() {
                var $checkbox = this.$('.selected input[type=checkbox]:first');
                if ($checkbox.is(':visible') && !$checkbox.hasClass('disabled')) {
                    $checkbox.get(0).click();
                }
            }
        });
    },

    /**
     * @inheritdoc
     *
     * Unsets `_bordersPosition` because the value changes on resize and will
     * have to be recalculated if the user toggles inline edit mode.
     */
    resize: function() {
        this._super('resize');
        this._bordersPosition = null;
    },

    /**
     * Refreshes the `ReorderableColumns` when the table height changes.
     *
     * The `ReorderableColumns` plugin listens to the window `resize` event to
     * update and position the handlers correctly.
     *
     * @private
     */
    _refreshReorderableColumns: function() {
        $(window).resize();
    }
})
