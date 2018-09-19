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
 * @class View.Views.Base.QuicksearchResultsView
 * @alias SUGAR.App.view.views.BaseQuicksearchResultsView
 * @extends View.View
 */
({
    events: {
        'click .view-all-results': 'viewAllResultsClicked'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * The fields metadata for this view per module.
         *
         * @property
         * @private
         */
        this._fieldsMeta = {};

        /**
         * The collection for executing searches and passing results.
         * This could be shared and used by other components.
         */
        // FIXME Sidecar should be modified to allow multiple top level contexts. When this happens, quick search
        // should use that context instead of layout.collection.
        this.collection = this.layout.collection || app.data.createMixedBeanCollection();

        this.selectedTags = this.layout.selectedTags || [];

        /**
         * Stores the index of the currently highlighted list element.
         * This is used for keyboard navigation.
         * @{number} {null}
         */
        this.activeIndex = null;

        // Listener for when a search is triggered. Show "Searching..."
        this.layout.on('quicksearch:search:underway', function() {
            this.close();
            this.render();
            this.open();
        }, this);

        // If the layout has `quicksearch:results:close` called on it, we
        // need to hide just the quicksearch results
        this.layout.on('quicksearch:close quicksearch:results:close', function() {
            this.close();
        }, this);

        //Listener for receiving focus for up/down arrow navigation:
        this.on('navigate:focus:receive', function(next) {
            if (next) {
                this.activeIndex = 0;
            } else {
                this.activeIndex = this.countRecordElements() - 1;
            }
            this._highlightActive();
            this.attachKeydownEvent();
        }, this);

        //Listener for losing focus for up/down arrow navigation:
        this.on('navigate:focus:lost', function() {
            this.activeIndex = null;
            this.$('.active').removeClass('active');
            this.disposeKeydownEvent();
        }, this);

        app.events.on('app:sync:complete', this._clearFieldsMeta, this);
    },

    /**
     * Clear the cached fields metadata
     * @private
     */
    _clearFieldsMeta: function() {
        this._fieldsMeta = {};
    },

    /**
     * Parses models when collection resets and renders the view.
     *
     * @override
     */
    bindDataChange: function() {
        // On a collection sync, format the search results and display
        this.collection.on('sync', function(collection) {
            if (this.disposed) {
                return;
            }
            var gsUtils = app.utils.GlobalSearch;

            gsUtils.formatRecords(collection, false);

            _.each(this.collection.models, function(model) {
                model.viewAccess = app.acl.hasAccessToModel('view', model);

                // FIXME: SC-4254 Remove this.layout.v2
                if (this.layout.v2) {
                    var moduleMeta = this._fieldsMeta[model.module] || gsUtils.getFieldsMeta(model.module, {linkablePrimary: false});
                    this._fieldsMeta[model.module] = moduleMeta;
                    model.primaryFields = gsUtils.highlightFields(model, moduleMeta.primaryFields);
                    model.secondaryFields = gsUtils.highlightFields(model, {}, true);

                    model.primaryFields = _.values(model.primaryFields);
                    model.secondaryFields = _.values(model.secondaryFields).slice(0, 3);
                } else {
                    if (model.searchInfo.highlighted) {
                        // Get the highlighted fields. If one is the name, highlight the name. Also, highlight the first
                        // non-name field. If there are multiple non-name highlighted fields, we only use the first.
                        _.find(model.searchInfo.highlighted, function(val, key) {
                            if (key === 'name') {
                                model.name = new Handlebars.SafeString(val.text);
                            } else { // found in a related field
                                model.field_name = app.lang.get(val.label, val.module);
                                model.field_value = new Handlebars.SafeString(val.text);
                                return true;
                            }
                        });
                    }
                }
            }, this);

            // build the link for View all results
            this.searchLink = app.utils.GlobalSearch.buildSearchRoute(collection.query, {
                modules: this.collection.selectedModules,
                tags: _.pluck(this.selectedTags, 'name')
            });
            this.activeIndex = null;
            this.render();
            this.open();

            // If the tags view is shown, move this one down
            var shownTags = _.pluck(collection.tags, 'name');
            var selectedTags = _.pluck(this.selectedTags, 'name');
            shownTags = _.difference(shownTags, selectedTags);
            this.$('.typeahead').toggleClass('tagsShown', shownTags.length > 0);
            this.layout.trigger('quicksearch:tag:' + shownTags.length > 0 ? 'open' : 'close');
        }, this);
    },

    /**
     * Show the quickresults dropdown
     */
    open: function() {
        this.$('.typeahead').show();
    },

    /**
     * Hide the quickresults dropdown
     */
    close: function() {
        this.clearActive();
        this.collection.reset();
        this.$('.typeahead').hide();
    },

    /**
     * Clear the active element and dispose key events
     */
    clearActive: function() {
        this.activeIndex = null;
        this.$('.active').removeClass('active');
        this.disposeKeydownEvent();
    },

    /**
     * If we have search results, the view is focusable.
     */
    isFocusable: function() {
        return this.collection.models.length > 0;
    },

    /**
     * Move to the next the active element.
     */
    moveForward: function() {
        // check to make sure we will be in bounds.
        if (this.activeIndex < this.countRecordElements() - 1) {
            // We're in bounds, just go to the next element in this view.
            this.activeIndex++;
            this._highlightActive();
        } else {
            // We're trying to move beyond the elements in this view. We need to try to move to the next view
            this._handleBoundary(true);

        }
    },

    /**
     * Move to the previous the active element.
     */
    moveBackward: function() {
        // check to make sure we will be in bounds.
        if (this.activeIndex > 0) {
            // We're in bounds, just go to the previous element in this view
            this.activeIndex--;
            this._highlightActive();
        } else {
            // We're trying to move beyond the elements in this view. We need to try to move to the previous view
            this._handleBoundary(false);
        }
    },

    /**
     * Highlight the active element and unhighlight the rest of the elements.
     */
    _highlightActive: function() {
        this.$('.active').removeClass('active');
        var nthChild = this.activeIndex + 1;
        this.$('li:nth-child(' + nthChild + ')')
            .addClass('active')
            .find('a').focus();
    },

    /**
     * Retrieve the count of record elements. This can be either the number of records or the number of records plus
     * a 'view all results' element.
     * @return {number}
     * @private
     */
    countRecordElements: function() {
        // If there is no next_offset, it means there are no "see more" option that we need to include.
        var hasMore = (this.collection.next_offset > -1) ? 1 : 0;
        return this.collection.models.length + hasMore;
    },

    /**
     * Handle when the user uses their keyboard to try to navigate outside of the view. This handles both the top and
     * bottom boundaries.
     * @param {boolean} next - If true, we are checking the next element. If false, we are checking the previous.
     * @private
     */
    _handleBoundary: function(next) {
        var event = 'navigate:next:component';
        if (!next) {
            event = 'navigate:previous:component';
        }
        if (this.layout.triggerBefore(event)) {
            this.clearActive();
            this.layout.trigger(event);
        }
    },

    /**
     * Attach the keydown events for the view.
     */
    attachKeydownEvent: function() {
        this.$el.on('keydown', _.bind(this.keydownHandler, this));
    },

    /**
     * Dispose the keydown events for the view.
     */
    disposeKeydownEvent: function() {
        this.$el.off();
    },

    /**
     * Handle the keydown events.
     * @param {event} e
     */
    keydownHandler: function(e) {
        switch (e.keyCode) {
            case 40: // down arrow
                this.moveForward();
                break;
            case 38: // up arrow
                this.moveBackward();
                break;
            case 9: // tab
                this.close();
                this.trigger('navigate:focus:lost');
        }
    },

    /**
     * click event handler for the view all results link
     * @param e
     */
    viewAllResultsClicked: function(e) {
        this.close();
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this.disposeKeydownEvent();
        this._super('unbind');
    }
})
