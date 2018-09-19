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
 * @class View.Views.Base.QuicksearchBarView
 * @alias SUGAR.App.view.views.BaseQuicksearchBarView
 * @extends View.View
*/
({

    className: 'table-cell quicksearch-bar-wrapper',
    /**
     * The minimum number of characters before the search bar attempts to
     * retrieve results.
     *
     * @property {number}
     */
    minChars: 1,

    searchModules: [],
    events: {
        'focus input[data-action=search_bar]': 'requestFocus',
        'click input[data-action=search_bar]': 'searchBarClickHandler'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * The collection for executing searches and passing results.
         * This could be shared and used by other components.
         */
        // FIXME Sidecar should be modified to allow multiple top level contexts. When this happens, quick search
        // should use that context instead of layout.collection.
        this.collection = this.layout.collection || app.data.createMixedBeanCollection();

        this.selectedTags = this.layout.selectedTags || [];

        /**
         * The default number of maximum results to display.
         *
         * You can override this value by providing a `limit` property in the
         * metadata.
         *
         * @type {number}
         * @property
         */
        this.limit = 5;
        if (this.meta && this.meta.limit) {
            this.limit = this.meta.limit;
        }

        /**
         * Used for keyboard up/down arrow navigation between components of `globalsearch` layout
         *
         * @property {boolean}
         */
        this.isFocusable = true;


        /**
         * The current search term.
         * When a search term is typed, the term is immediately stored to this variable. After the 500ms debounce, the
         * term is used to execute a search.
         * @type {string}
         * @private
         */
        this._searchTerm = '';

        /**
         * The previous search term.
         * This is stored to check against `this._searchTerm`. If `this._searchTerm === this._oldSearchTerm`, we do
         * not need to retrieve new results. This protects us against keystrokes that do not change the search term.
         * @type {string}
         * @private
         */
        this._oldSearchTerm = '';

        /**
         * The previous query term.
         * This is the last search term used to get results, and as such, is the term that produced the currently
         * displayed results. If `this._searchTerm === this._currentQueryTerm` when the search is executed (after
         * the 500ms debounce), we do not need to execute a new search.
         * @type {string}
         * @private
         */
        this._currentQueryTerm = '';

        app.events.on('app:sync:complete', this.populateModules, this);

        // Listener for receiving focus for up/down arrow navigation:
        this.on('navigate:focus:receive', function() {
            // if the input doesn't have focus, give it focus.
            var inputBox = this.$input[0];
            if (inputBox !== $(document.activeElement)[0]) {
                inputBox.focus();
            } else {
                this.attachKeyEvents();
            }
        }, this);

        // Listener for losing focus for up/down arrow navigation:
        this.on('navigate:focus:lost', function() {
            this.disposeKeyEvents();
        }, this);

        // Listener for `quicksearch:close`. This aborts in progress
        // searches
        this.layout.on('quicksearch:close', function() {
            this._searchTerm = '';
            this._currentQueryTerm = '';
            this._oldSearchTerm = '';
            this.collection.abortFetchRequest();
            this.$input.blur();
        }, this);

        this.layout.on('quicksearch:bar:clear', this.clearSearch, this);

        this.layout.on('quicksearch:bar:clear:term', this.clearSearchTerm, this);

        this.layout.on('quicksearch:bar:search', this.goToSearchPage, this);

        this.layout.on('route:search', this.populateSearchTerm, this);

        this.layout.on('quicksearch:fire:search', function() {
            // In case we are already in the middle of a search
            this.collection.abortFetchRequest();
            this._oldSearchTerm = null;
            this._currentQueryTerm = null;
            this._validateAndSearch();
        }, this);
    },

    /**
     * Renders a view onto the page.
     *
     * @protected
     */
    _renderHtml: function() {
        this._super('_renderHtml');
        this.$input = this.$('input[data-action=search_bar]');
    },

    /**
     * Checks to see if we're in the search context. If we are, populate the search
     * bar with the search term.
     */
    populateSearchTerm: function() {
        var inputBar = this.$input;
        var searchTerm = this.context.get('searchTerm');
        if (inputBar.val() !== searchTerm) {
            inputBar.val(searchTerm);
        }
    },

    /**
     * Request focus from the layout. This is used primarily for mouse clicks.
     */
    requestFocus: function() {
        this.layout.trigger('navigate:to:component', this.name);
    },

    /**
     * Function to attach the keydown and keyup events.
     */
    attachKeyEvents: function() {
        var searchBarEl = this.$input;
        // for arrow key navigation
        searchBarEl.on('keydown', _.bind(this.keydownHandler, this));

        // for searchbar typeahead
        searchBarEl.on('keyup', _.bind(this.keyupHandler, this));
    },

    /**
     * Function to dispose the keydown and keyup events.
     */
    disposeKeyEvents: function() {
        this.$input.off('keydown keyup');
    },

    /**
     * Handles the keydown event for up, down, and ignores tab.
     *
     * @param {Event} e The `keydown` event
     * @private
     */
    keydownHandler: function(e) {
        switch (e.keyCode) {
            case 40: // down arrow
                this.moveForward();
                e.preventDefault();
                e.stopPropagation();
                break;
            case 38: // up arrow
                e.preventDefault();
                e.stopPropagation();
                break;
            case 37: // left arrow
            case 8:  //backspace
                // If there's text in the input bar, don't add any special handling
                var term = this.$input.val();
                if (term === '') {
                    this.moveBackward();
                    // Prevent double event calling when element to the left attaches its keydown handler
                    e.stopPropagation();
                    e.preventDefault();
                }
                break;
        }
    },

    /**
     * Handles the keyup event for typing, and ignores tab
     *
     * @param {Event} e The `keyup event
     */
    keyupHandler: function(e) {
        switch (e.keyCode) {
            case 40: // down arrow
                break;
            case 38: // up arrow
                break;
            case 9: // tab
                break;
            case 16: // shift
                break;
            case 13: // enter
                this.goToSearchPage();
                break;
            case 27: // esc
                this.layout.trigger('quicksearch:close');
                break;
            default:
                this._validateAndSearch();
        }
    },

    /**
     * Goes to the search page and displays results.
     */
    goToSearchPage: function() {
        // navigate to the search results page
        var term = this.$input.val();
        var route = '';
        this._searchTerm === this._currentQueryTerm;
        this._currentQueryTerm = term;
        if (this.layout.v2) {
            route = app.utils.GlobalSearch.buildSearchRoute(term, {
                modules: this.collection.selectedModules,
                tags: _.pluck(this.selectedTags, 'name')
            });
        } else {
            var moduleString = this.collection.selectedModules.join(',');
            route = 'bwc/index.php?module=Home&append_wildcard=true&action=spot&full=true' +
                '&q=' + term +
                '&m=' + moduleString;
        }
        this.collection.abortFetchRequest();
        app.router.navigate(route, {trigger: true});
    },
    /**
     * Handler for clicks on the search bar.
     *
     * Expands the bar and toggles the search icon.
     */
    searchBarClickHandler: function() {
        this.requestFocus();
        _.defer(_.bind(this.layout.expand, this.layout));
    },

    /**
     * Navigate to the next component
     */
    moveForward: function() {
        if (this.layout.triggerBefore('navigate:next:component')) {
            this.disposeKeyEvents();
            this.layout.trigger('navigate:next:component');
        }
    },

    /**
     * Navigate to the previous component
     */
    moveBackward: function() {
        if (this.layout.triggerBefore('navigate:previous:component')) {
            this.disposeKeyEvents();
            this.layout.trigger('navigate:previous:component');
        }
    },

    /**
     * Waits & debounces for 0.5 seconds before firing a search. This is primarily used on the
     * keydown event for the typeahead functionality.
     *
     * @param {string} term The search term.
     * @private
     * @method
     */
    _debounceSearch: _.debounce(function() {
        // Check if the search term is falsy (empty string)
        // or the search term is the same as the previously searched term
        // If either of those conditions are met, we do not need to execute a new search.
        if ((!this._searchTerm && this.selectedTags.length === 0)
            || this._searchTerm === this._currentQueryTerm) {
            return;
        }
        this._currentQueryTerm = this._searchTerm;
        this.fireSearchRequest();
    }, 500),


    /**
     * Collects the search term, validates that a search is appropriate, and executes a debounced search.
     * First, it checks the search term length, to ensure it meets the minimum length requirements.
     * Second, it checks the search term against the previously typed search term. If the search term hasn't changed
     * (for example, for keyboard shortcuts) then there is no need to rerun the search.
     * If the above conditions are met, `_validateAndSearch` runs a debounced search.
     *
     * @private
     */
    _validateAndSearch: function() {
        var term = this.$input.val();
        this._searchTerm = term;

        // if the term is too short, don't search
        if (term.length < this.minChars && this.selectedTags.length === 0) {
            this._searchTerm = '';
            this._currentQueryTerm = '';
            this._oldSearchTerm = '';
            // We trigger `quicksearch:results:close` instead of
            // `quicksearch:close` because we only want to close the dropdown
            // and keep the bar expanded. That means we only want the listener
            // in `quicksearch-results.js` to be called, not the other ones.
            this.collection.abortFetchRequest();
            this.layout.trigger('quicksearch:results:close');
            this.collection.abortFetchRequest();
            return;
        }

        // shortcuts might trigger multiple `keydown` events, to do some actions like blurring the input, but since the
        // input value didn't change we don't want to trigger a new search.
        var hasInputChanged = (this._searchTerm !== this._oldSearchTerm);
        if (hasInputChanged) {
            this.collection.dataFetched = false;
            this.layout.trigger('quicksearch:search:underway');
            this.layout.expand();
            this._oldSearchTerm = term;
            this._debounceSearch();
        }
    },

    /**
     * Executes a search using `this._searchTerm`.
     * TODO: Move this function into the layout so that we can sandbox out tags from the bar
     */
    fireSearchRequest: function() {
        var term = this._searchTerm;
        // FIXME: SC-4254 Remove this.layout.v2
        var limit = this.layout.v2 ? this.limit : 5;
        limit = app.config && app.config.maxSearchQueryResult || limit;
        var options = {
            query: term,
            module_list: this.collection.selectedModules,
            limit: limit,
            params: {
                tags: true
            },
            apiOptions: {
                useNewApi: true
            }
        };

        if (this.selectedTags.length > 0) {
            _.extend(options.apiOptions, {
                    data: {
                        tag_filters: _.pluck(this.selectedTags, 'id')
                    },
                    fetchWithPost: true
            });
        }

        // FIXME: SC-4254 Remove this.layout.v2
        if (!this.layout.v2) {
            options.fields = ['name', 'id'];
        }
        this.collection.query = term;
        this.collection.fetch(options);
    },

    /**
     * Clears out search upon user following search result link in menu
     */
    clearSearch: function() {
        this.$input.val('');
        this._searchTerm = '';
        this._oldSearchTerm = '';
        this._currentQueryTerm = '';
        this.layout.trigger('quicksearch:tags:remove');
        this.disposeKeyEvents();
    },

    clearSearchTerm: function() {
        this.$input.val('');
        this._searchTerm = '';
        this._oldSearchTerm = '';
        this._currentQueryTerm = '';
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this.disposeKeyEvents();
        this._super('unbind');
    }
})
