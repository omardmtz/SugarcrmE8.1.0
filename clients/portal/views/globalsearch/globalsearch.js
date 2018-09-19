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
 * View that displays a Bar with module name and filter toggles for per module
 * search and module creation.
 *
 * @class View.Views.GlobalsearchView
 * @alias SUGAR.App.layout.GlobalsearchView
 * @extends View.View
 */
({
    // FIXME this needs to be removed so that we can be able to reuse this view
    /**
     * Identifier used by CSS to display the search bar and the searchahead results
     *
     * @property {string}
     */
    id: 'searchForm',

    /**
     * Used when formatting the search results. It is prepended to the
     * highlighted string.
     *
     * @property {string}
     */
    preTag: '<strong>',

    /**
     * Used when formatting the search results. It is appended to the
     * highlighted string.
     *
     * @property {string}
     */
    postTag: '</strong>',

    plugins: ['Dropdown'],

    /**
     * Used by Dropdown plugin to determine which items to select when using the arrow keys
     *
     * @property{string}
     */
    dropdownItemSelector: '[data-action="select-module"]',

    searchModules: [],
    events: {
        'click .typeahead a': 'clearSearch',
        'click [data-action=search]': 'showResults',
        'click [data-advanced=options]': 'persistMenu',
        'click [data-action="select-module"]': 'selectModule'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        app.events.on('app:sync:complete', this.populateModules, this);

        //shortcut keys
        app.shortcuts.register({
            id: 'Search:Focus',
            keys: ['s', 'mod+alt+0'],
            component: this,
            description: 'LBL_SHORTCUT_SEARCH',
            handler: function() {
                this.$('input.search-query').focus();
            }
        });
    },
    /**
     * Handle module 'select/unselect' event.
     * @param event
     */
    selectModule: function(event) {
        var module = this.$(event.currentTarget).data('module'),
            searchAll = this.$('input:checkbox[data-module="all"]'),
            searchAllLabel = searchAll.closest('label'),
            checkedModules = this.$('input:checkbox:checked[data-module!="all"]');

        if (module === 'all') {
            searchAll.attr('checked', true);
            searchAllLabel.addClass('active');
            checkedModules.removeAttr('checked');
            checkedModules.closest('label').removeClass('active');
        } else {
            var currentTarget = this.$(event.currentTarget),
                currentTargetLabel = currentTarget.closest('label');

            currentTarget.attr('checked') ? currentTargetLabel.addClass('active') : currentTargetLabel.removeClass('active');

            if (checkedModules.length) {
                searchAll.removeAttr('checked');
                searchAllLabel.removeClass('active');
            }
            else {
                searchAll.attr('checked', true);
                searchAllLabel.addClass('active');
            }
        }
        // This will prevent the module selection dropdown from disappearing.
        event.stopPropagation();
    },

    /**
     * Helper that can be called from here in base, or, from derived globalsearch views. Called internally,
     * so please ensure that you have passed in any required options or results may be undefined
     *
     * @param {object} options An object literal with the following properties:
     * - modules: our current modules (required)
     * - acl: app.acl that has the hasAccess function (required) (we DI this for testability)
     * - moduleNames: displayed modules; an array of white listed string names. If used, only modules within
     * this white list will be added (optional)
     * - checkFtsEnabled: whether we should check meta.ftsEnabled (optional defaults to false)
     * - checkGlobalSearchEnabled: whether we should check meta.globalSearchEnabled (optional defaults to false)
     * @return {array} An array of searchable modules
     */
    populateSearchableModules: function(options) {
        var modules = options.modules,
            moduleNames = options.moduleNames || null,
            acl = options.acl,
            searchModules = [];

        _.each(modules, function(meta, module) {
            var goodToAdd = true;
            // First check if we have a "white list" of displayed module names (e.g. portal)
            // If so, check if it contains the current module we're checking
            if (moduleNames && !_.contains(moduleNames, module)) {
                goodToAdd = false;
            }
            // First check access the, conditionally, check fts and global search enabled properties
            if (goodToAdd && acl.hasAccess.call(acl, 'view', module)) {
                // Check global search enabled if relevant to caller
                if (options.checkGlobalSearchEnabled && !meta.globalSearchEnabled) {
                    goodToAdd = false;
                }
                // Check global search enabled if relevant to caller
                if (goodToAdd && options.checkFtsEnabled && !meta.ftsEnabled) {
                    goodToAdd = false;
                }
                // If we got here we've passed all checks so push module to search modules
                if (goodToAdd) {
                    searchModules.push(module);
                }
            }
        }, this);
        return searchModules;
    },

    /**
     * Escapes the highlighted result from Elasticsearch for any potential XSS.
     *
     * @param  {string} html
     * @return {Handlebars.SafeString}
     */
    _escapeSearchResults: function(html) {
        // Change this regex if server-side preTag and postTag change.
        var highlightedSpanRe = /<strong>.*?<\/strong>/g,
            higlightSpanTagsRe = /(<strong>)|(<\/strong>)/g,
            escape = Handlebars.Utils.escapeExpression,
        // First, all of the HTML is escaped.
            result = escape(html),
        // Then, we find all pieces highlighted by the server.
            highlightedSpan = html.match(highlightedSpanRe),
            highlightedContent,
            self = this;

        // For each highlighted part:
        _.each(highlightedSpan, function(part){
            highlightedContent = part.replace(higlightSpanTagsRe, '');
            // We escape the content of each highlight returned from Elastic.
            highlightedContent = escape(highlightedContent);
            // And then, we inject the escaped content with our own unescaped
            // highlighting tags (self.preTag/self.postTag).
            result = result.replace(escape(part), self.preTag + highlightedContent + self.postTag);
        });

        return new Handlebars.SafeString(result);
    },

    /**
     * Get the modules that current user selected for search.
     * Empty array for all.
     *
     * @return {Array}
     */
    _getSearchModuleNames: function() {
        if (this.$('input:checkbox[data-module="all"]').attr('checked')) {
            return [];
        }
        else {
            var searchModuleNames = [],
                checkedModules = this.$('input:checkbox:checked[data-module!="all"]');
            _.each(checkedModules, function(val,index) {
                searchModuleNames.push(val.getAttribute('data-module'));
            }, this);
            return searchModuleNames;
        }
    },

    /**
     * Show results when the search button is clicked.
     *
     * @param {event} evt The event that triggered the search.
     */
    showResults: function(evt) {

        var $searchBox = this.$('[data-provide=typeahead]');

        if (!$searchBox.is(':visible')) {
            var body = $('body');
            this.$el.addClass('active');
            body.on('click.globalsearch.data-api', _.bind(function(event) {
                if (!$.contains(this.el, event.target)) {
                    this.$el.removeClass('active');
                    body.off('click.globalsearch.data-api');
                }
            }, this));
            app.accessibility.run(body, 'click');
            $searchBox.focus();
            return;
        }

        // Simulate 'enter' keyed so we can show searchahead results
        var e = jQuery.Event('keyup', { keyCode: $.ui.keyCode.ENTER });
        $searchBox.focus();
        $searchBox.trigger(e);
    },

    /**
     * Clears out search upon user following search result link in menu
     */
    clearSearch: function() {
        this.$('.search-query').val('');
    },

    /**
     * This will prevent the dropup menu from closing when clicking anywhere on it
     */
    persistMenu: function(e) {
        e.stopPropagation();
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        $('body').off('click.globalsearch.data-api');
        this._super('unbind');
    },

    /**
     * @override
     */
    _renderHtml: function() {
        if (!app.api.isAuthenticated() || app.config.appStatus == 'offline') {
            return;
        }

        app.view.View.prototype._renderHtml.call(this);

        // Search ahead drop down menu stuff
        var self = this,
            menuTemplate = app.template.getView(this.name + '.result');

        this.$('.search-query').searchahead({
            context: self,
            request: function(term) {
                self.fireSearchRequest(term);
            },
            compiler: menuTemplate,
            throttleMillis: (app.config.requiredElapsed || 500),
            throttle: function(callback, millis) {
                if (!self.debounceFunction) {
                    self.debounceFunction = _.debounce(function() {
                        callback();
                    }, millis || 500);
                }
                self.debounceFunction();
            },
            onEnterFn: function(hrefOrTerm, isHref) {
                if (isHref) {
                   window.location = hrefOrTerm;
                } else {
                    // It's the term only (user didn't select from drop down
                    // so this is essentially the term typed
                    var term = $.trim(self.$('.search-query').attr('value'));
                    if (!_.isEmpty(term)) {
                        self.fireSearchRequest(term, this);
                    }
                }
            }
        });
        // Prevent the form from being submitted
        this.$('.navbar-search').submit(function() {
            return false;
        });
    },
    /**
     * Populates search modules from displayable modules, taking acls and globalSearchEnabled in to account
     */
    populateModules: function() {
        if (this.disposed) {
            return;
        }
        var modules = app.metadata.getModules() || {};
        var moduleNames = app.metadata.getModuleNames({filter: 'display_tab'}); // visible
        this.searchModules = this.populateSearchableModules({
            modules: modules,
            moduleNames: moduleNames,
            acl: app.acl,
            // Unlike sugar7, today, portal doesn't use ftsEnabled but instead any visible modules that
            // are also globalSearchEnabled (e.g. Home should have not be global search enabled)
            checkFtsEnabled: false,
            checkGlobalSearchEnabled: true
        });
        this.render();
    },

    /**
     * Callback for the searchahead plugin .. note that
     * 'this' points to the plugin (not the header view!)
     *
     * @param term
     */
    fireSearchRequest: function(term) {
        var self = this,
            searchModuleNames = this._getSearchModuleNames(),
            maxNum = app.config && app.config.maxSearchQueryResult ? app.config.maxSearchQueryResult : 5,
            params = {
                q: term,
                fields: 'name, id',
                module_list: searchModuleNames.join(','),
                max_num: maxNum
            };


        app.api.search(params, {
            success: function(data) {
                var formattedRecords = [],
                    modList = app.metadata.getModuleNames({filter: 'quick_create', action: 'create'}),
                    moduleIntersection = _.intersection(modList, self.searchModules);
                _.each(data.records, function(record) {
                    if (!record.id) {
                        return; // Elastic Search may return records without id and record names.
                    }
                    var formattedRecord = {
                        id: record.id,
                        name: record.name,
                        module: record._module,
                        link: '#' + app.router.buildRoute(record._module, record.id)
                    };

                    if ((record._search.highlighted)) { // full text search
                        _.each(record._search.highlighted, function(val, key) {
                            if (key !== 'name') { // found in a related field
                               formattedRecord.field_name = app.lang.get(val.label, val.module);
                               formattedRecord.field_value = val.text;
                            }
                        });
                    }
                    formattedRecords.push(formattedRecord);
                });
                self.$('.search-query').searchahead('provide', {module_list: moduleIntersection, next_offset: data.next_offset, records: formattedRecords});
            },
            error: function(error) {
                app.error.handleHttpError(error, plugin);
                app.logger.error('Failed to fetch search results in search ahead. ' + error);
            }
        });
    },
    /**
     * Show full search results when the search button is clicked
     * (Show searchahead results for sugarcon because we don't have full results page yet)
     */
    gotoFullSearchResultsPage: function(evt) {
        var term;
        // Force navigation to full results page and don't let plugin get control
        evt.preventDefault();
        evt.stopPropagation();
        // URI encode search query string so that it can be safely
        // decoded by search handler (bug55572)
        term = encodeURIComponent(this.$('.search-query').val());
        if (term && term.length) {
            // Bug 57853 Shouldn't show the search result pop up window after click the global search button.
            // This prevents anymore dropdowns (note we re-init if/when _renderHtml gets called again)
            this.$('.search-query').searchahead('disable', 1000);
            app.router.navigate('#search/' + term, {trigger: true});
        }
    }
})
