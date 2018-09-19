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
 * View that displays a list of models pulled from the context's collection.
 *
 * @class View.Views.Base.ListView
 * @alias SUGAR.App.view.views.BaseListView
 * @extends View.View
 */
({
    className: 'list-view',

    plugins: ['Pagination'],

    events: {
        'click [class*="orderBy"]':'setOrderBy'
    },

    defaultLayoutEvents: {
        "list:search:fire": "fireSearch",
        "list:filter:toggled": "filterToggled",
        "list:alert:show": "showAlert",
        "list:alert:hide": "hideAlert",
        "list:sort:fire": "sort"
    },

    /**
     * @inheritdoc
     */
    dataView: 'list',

    defaultContextEvents: {},

    // Model being previewed (if any)
    _previewed: null,
    //Store left column fields
    _leftActions: [],
    //Store right column fields
    _rowActions: [],
    //Store default and available(+visible) field names
    _fields: {},

    initialize: function(options) {
        //Grab the list of fields to display from the main list view (assuming initialize is being called from a subclass)
        var listViewMeta = app.metadata.getView(options.module, 'list') || {};
        //Extend from an empty object to prevent polution of the base metadata
        options.meta = _.extend({}, listViewMeta, options.meta || {});
        // FIXME: SC-5622 we shouldn't manipulate metadata this way.
        options.meta.type = options.meta.type || 'list';
        options.meta.action = 'list';
        options = this.parseFieldMetadata(options);

        app.view.View.prototype.initialize.call(this, options);

        this.viewName = 'list';

        /**
         * View name that corresponds to the list of fields API should retrieve.
         * @property {string} dataViewName
         * @deprecated Use {@link #dataView} instead
         */
        if (this.dataViewName) {
            app.logger.warn('`dataViewName` is deprecated, please use `dataView`.');
            this.context.set('dataView', this.dataViewName);
        }

        this.attachEvents();
        this.orderByLastStateKey = app.user.lastState.key('order-by', this);
        this.orderBy = this._initOrderBy();
        if(this.collection) {
            this.collection.orderBy = this.orderBy;
        }
        // Dashboard layout injects shared context with limit: 5.
        // Otherwise, we don't set so fetches will use max query in config.
        this.limit = this.context.has('limit') ? this.context.get('limit') : null;
        this.metaFields = this.meta.panels ? _.first(this.meta.panels).fields : [];

        this.registerShortcuts();
    },

    /**
     * Initializes the {@link #orderBy} property.
     *
     * Retrieves the last state from the local storage and verifies the field
     * is still sortable.
     *
     * @return {Object}
     * @return {string} return.field The field name to sort by.
     * @return {string} return.direction The direction to sort by (either `asc`
     *   or `desc`).
     * @protected
     */
    _initOrderBy: function() {
        var lastStateOrderBy = app.user.lastState.get(this.orderByLastStateKey) || {},
            lastOrderedFieldMeta = this.getFieldMeta(lastStateOrderBy.field);

        if (_.isEmpty(lastOrderedFieldMeta) || !app.utils.isSortable(this.module, lastOrderedFieldMeta)) {
            lastStateOrderBy = {};
        }

        // if no access to the field, don't use it
        if (!_.isEmpty(lastStateOrderBy.field) && !app.acl.hasAccess('read', this.module, app.user.get('id'), lastStateOrderBy.field)) {
            lastStateOrderBy = {};
        }

        return _.extend({
                field : '',
                direction : 'desc'
            },
            this.meta.orderBy,
            lastStateOrderBy
        );
    },

    /**
     * @override
     * @private
     */
    _render: function () {
        app.view.View.prototype._render.call(this);
        //If user has no `list` access, render `noaccess.hbs` template
        if (!app.acl.hasAccessToModel(this.action, this.model)) {
            this._noAccessTemplate = this._noAccessTemplate || app.template.get("list.noaccess");
            this.$el.html(this._noAccessTemplate());
        }
    },

    /**
     * Parses the field's metadata to make sure that the following attributes
     * respect specific standards:
     *
     *  - `align`: accepted values are `left`, `center` and `right`.
     *  - `width`: the value can be a default width (e.g. `small` or `large`) or
     *  a number in pixels. Percentage widths are ignored.
     *
     * The method will add (or append to) two properties to each field's
     * metadata:
     *
     * - `classes`: css classes that should be set on the column header.
     * - `styles`: inline style that should be set on the column header.
     *
     * To render properly, make sure that the template sets them on the
     * column headers.
     *
     * @param {Object} options The `options` object passed in
     *   {@link #initialize}.
     * @param {Object} options.meta The metadata that we want to parse.
     * @return {Object} The `options` object with the metadata parsed and
     *   patched.
     */
    parseFieldMetadata: function(options) {
        // standardize the align and width param in the defs if they exist
        _.each(options.meta.panels, function(panel, panelIdx) {
            _.each(panel.fields, function(field, fieldIdx) {
                var fieldFromMeta = options.meta.panels[panelIdx].fields[fieldIdx];
                // FIXME align should be handled by the field directly - SC-3588
                if (!_.isUndefined(field.align)) {
                    if (_.contains(['left', 'center', 'right'], field.align)) {
                        fieldFromMeta.align = 't' + field.align;
                    } else {
                        delete fieldFromMeta.align;
                    }
                }

                // The width field in Studio is defined as a percentage which is
                // deprecated for Sugar7 modules. Check to see if module list
                // view metadata has been defined as percentage and if so,
                // ignore.
                if (!_.isUndefined(field.width)) {
                    // check to see if it's a percentage
                    // match beginning, decimal of 0 to 3 places, percent sign, end
                    var percent = field.width.toString().match(/^(\d{0,3})\%$/);
                    // ignore if defined as percent
                    if (!percent && !_.isEmpty(field.width+'')) {
                        var width = parseInt(field.width, 10);
                        if (!_.isNaN(width) && _.isNumber(width)) {
                            var styles = 'max-width:' + width + 'px;min-width:' + width + 'px';
                            fieldFromMeta.styles = styles;
                            fieldFromMeta.expectedWidth = width;
                        } else {
                            fieldFromMeta.widthClass = 'cell-' + field.width;
                            fieldFromMeta.expectedWidth = field.width;
                        }
                    }
                }
            }, this);
        }, this);

        return options;
    },

    /**
     * Takes the defaultListEventMap and listEventMap and binds the events. This is to allow views that
     * extend ListView to specify their own events.
     */
    attachEvents: function() {
        this.layoutEventsMap = _.extend(this.defaultLayoutEvents, this.layoutEvents); // If undefined nothing will be added.
        this.contextEventsMap = _.extend(this.defaultContextEvents, this.contextEvents);

        if (this.layout) {
            _.each(this.layoutEventsMap, function(callback, event) {
                this.layout.on(event, this[callback], this);
            }, this);
        }

        if (this.context) {
            _.each(this.contextEventsMap, function(callback, event) {
                this.context.on(event, this[callback], this);
            }, this);
        }
    },

    sort: function() {
        //When sorting the list view, we need to close the preview panel
        app.events.trigger("preview:close");
    },

    showAlert: function(message) {
        this.$("[data-target=alert]").html(message);
        this.$("[data-target=alert-container]").removeClass("hide");
    },

    hideAlert: function() {
        this.$("[data-target=alert-container]").addClass("hide");
        this.$("[data-target=alert]").empty();
    },
    filterToggled:function (isOpened) {
        this.filterOpened = isOpened;
    },
    fireSearch:function (term) {
        term = term || "";
        var options = {
            limit: this.limit || null,
            query: term
        };
        this.context.get("collection").resetPagination();
        this.context.resetLoadFlag({recursive: false});
        this.context.set('skipFetch', false);
        this.context.loadData(options);
    },

    /**
     * Sets order by on collection and view.
     *
     * The event is canceled if an element being dragged is found.
     *
     * @param {Event} event jQuery event object.
     */
    setOrderBy: function(event) {
        if ($(event.currentTarget).find('ui-draggable-dragging').length) {
            return;
        }
        var collection, options, eventTarget, orderBy;
        var self = this;

        collection = self.collection;
        eventTarget = self.$(event.currentTarget);

        // first check if alternate orderby is set for column
        orderBy = eventTarget.data('orderby');
        // if no alternate orderby, use the field name
        if (!orderBy) {
            orderBy = eventTarget.data('fieldname');
        }
        if (!_.isEmpty(orderBy) && !app.acl.hasAccess('read', this.module, app.user.get('id'), orderBy)) {
            // no read access to the orderBy field, don't bother to reload data
            return;
        }

        // if same field just flip
        if (orderBy === self.orderBy.field) {
            self.orderBy.direction = self.orderBy.direction === 'desc' ? 'asc' : 'desc';
        } else {
            self.orderBy.field = orderBy;
            self.orderBy.direction = 'desc';
        }

        collection.orderBy = self.orderBy;

        collection.resetPagination();

        options = self.getSortOptions(collection);

        if(this.triggerBefore('list:orderby', options)) {
            self._setOrderBy(options);
        }
    },

    /**
     * Run the order by on the collection
     *
     * @param {Object} options
     * @private
     */
    _setOrderBy: function(options) {
        if(this.orderByLastStateKey) {
            app.user.lastState.set(this.orderByLastStateKey, this.orderBy);
        }
        // refetch the collection
        this.context.resetLoadFlag({recursive: false});
        this.context.set('skipFetch', false);
        this.context.loadData(options);
    },
    /**
     * Gets options for fetch call for list sorting
     * @param collection
     * @return {Object}
     */
    getSortOptions: function(collection) {
        var self = this, options = {};
        // Treat as a "sorted search" if the filter is toggled open
        options = self.filterOpened ? self.getSearchOptions() : {};

        //Show alerts for this request
        options.showAlerts = true;

        // If injected context with a limit (dashboard) then fetch only that
        // amount. Also, add true will make it append to already loaded records.
        options.limit = self.limit || null;
        options.success = function (collection, response, options) {
            self.layout.trigger("list:sort:fire", collection, self);
        };

        // if we have a bunch of models already fetch at least that many
        if (collection.offset) {
            options.limit = collection.offset;
            options.offset = 0;
        }

        return options;
    },
    getSearchOptions:function () {
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
            params:{},
            fields:collection.fields ? collection.fields : this.collection
        };
        if (term) {
            options.params.q = term;
        }
        if (this.context.get('link')) {
            options.relate = true;
        }
        return options;
    },
    bindDataChange:function () {
        if (this.collection) {
            this.collection.on("reset", this.render, this);
        }
    },

    _dispose: function() {
        this._fields = null;
        app.view.View.prototype._dispose.call(this);
    },

    /**
     * Select next or previous row.
     * @param {Boolean} down
     */
    selectRow: function(down) {
        var $rows = this.$('.dataTable tbody tr'),
            $selected,
            $next;

        if ($rows.hasClass('selected')) {
            $selected = $rows.filter('.selected');
            $next = down ? $selected.next() : $selected.prev();
            if($next.length > 0) {
                $selected.removeClass('selected');
                $next.addClass('selected');
                this.makeRowVisible($next);
            }
        } else {
            $rows.first().addClass('selected');
            this.makeRowVisible();
        }
    },

    /**
     * Scroll list view such that the selected row is visible.
     * @param {jQuery} $selected
     */
    makeRowVisible: function($selected) {
        var $mainpane = this.$el.closest('.main-pane'),
            mainpaneHeight,
            selectedHeight,
            selectedTopPosition,
            selectedOffsetParent;

        if (_.isUndefined($selected)) {
            $mainpane.scrollTop(0);
            return;
        }

        mainpaneHeight = $mainpane.height();
        selectedHeight = $selected.height();
        selectedOffsetParent = $selected.offsetParent();
        selectedTopPosition = $selected.position().top + selectedOffsetParent.position().top;

        if ((selectedTopPosition + selectedHeight) > mainpaneHeight) {
            $mainpane.scrollTop($mainpane.scrollTop() + mainpaneHeight/2);
        }

        if (selectedTopPosition < 0) {
            $mainpane.scrollTop($mainpane.scrollTop() - mainpaneHeight/2);
        }
    },

    /**
     * Scroll list view either right or left.
     * @param {Boolean} right
     */
    scrollHorizontally: function(right) {
        var $scrollableDiv = this.$('.flex-list-view-content'),
            scrollEnabled = this.$el.hasClass('scroll-width'),
            nextScrollPosition,
            increment = 60;

        if (scrollEnabled) {
            if (right) {
                nextScrollPosition = $scrollableDiv.scrollLeft() + increment;
            } else {
                nextScrollPosition = $scrollableDiv.scrollLeft() - increment;
            }

            $scrollableDiv.scrollLeft(nextScrollPosition);
        }
    },

    /**
     * Register shortcut keys.
     */
    registerShortcuts: function() {
        app.shortcuts.register({
            id: 'List:Select:Down',
            keys: 'j',
            component: this,
            description: 'LBL_SHORTCUT_NAVIGATE_DOWN',
            handler: function() {
                this.selectRow(true);
            }
        });

        app.shortcuts.register({
            id: 'List:Select:Up',
            keys: 'k',
            component: this,
            description: 'LBL_SHORTCUT_NAVIGATE_UP',
            handler: function() {
                this.selectRow(false);
            }
        });

        app.shortcuts.register({
            id: 'List:Scroll:Left',
            keys: 'h',
            component: this,
            description: 'LBL_SHORTCUT_SCROLL_LEFT',
            handler: function() {
                this.scrollHorizontally(false);
            }
        });

        app.shortcuts.register({
            id: 'List:Scroll:Right',
            keys: 'l',
            component: this,
            description: 'LBL_SHORTCUT_SCROLL_RIGHT',
            handler: function() {
                this.scrollHorizontally(true);
            }
        });

        app.shortcuts.register({
            id: 'List:Select:Open',
            keys: 'o',
            component: this,
            description: 'LBL_SHORTCUT_OPEN',
            handler: function() {
                if (this.$('.selected [data-type=name] a:visible').length > 0) {
                    this.$('.selected [data-type=name] a:visible').get(0).click();
                } else if (this.$('.selected [data-type=fullname] a:visible').length > 0) {
                    this.$('.selected [data-type=fullname] a:visible').get(0).click();
                }
            }
        });
    }
})
