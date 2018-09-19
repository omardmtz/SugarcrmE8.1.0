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
({
    sidebarClosed: false,

    fallbackFieldTemplate: "detail",

    events: {
        'click [name=name]': 'gotoDetail',
        'click .fa-eye': 'loadPreview',
        'click [name=show_more_button]': 'showMoreResults'
    },

    /**
     * Uses MixedBeanCollection to fetch search results.
     */
    fireSearchRequest: function (cb, offset) {
        var self = this, options;
        var mlist = app.metadata.getModuleNames({filter: 'visible'}); // visible
        options = {
            //Show alerts for this request
            showAlerts: true,
            query: self.lastQuery,
            success:function(collection) {
                cb(collection);
            },
            module_list: mlist,
            error: function() {
                cb(null); // lets callback know to dismiss the alert
            }
        };
        if (offset) options.offset = offset;
        this.collection.fetch(options);
    },

    /**
     * Show more search results
     */
    showMoreResults: function() {
        var self = this, options = {};
        options.add = true;
        //Show alerts for this request
        options.showAlerts = true;
        options.success = function() {
            app.view.View.prototype._render.call(self);
            window.scrollTo(0, document.body.scrollHeight);
        };
        this.collection.paginate(options);
    },

    gotoDetail: function(evt) {
        var href = this.$(evt.currentTarget).parent().parent().attr('href');
        window.location = href;
    },

    closeSidebar: function() {
        if (!this.sidebarClosed) {
            app.controller.context.trigger('toggleSidebar');
            this.sidebarClosed = true;
        }
    },
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this._addPreviewEvents();
    },
    _render: function() {
        var self = this;
        self.setHeaderpaneTitle(" "); //Clear the default "Module Name" title
        self.lastQuery = self.context.get('query');
        self.fireSearchRequest(function(collection) {
            // Bug 57853: Will brute force dismiss search dropdown if still present.
            $('.search-query').searchahead('hide');
            if (self.disposed) {
                return;
            }
            // Add the records to context's collection
            if(collection && collection.length) {
                app.view.View.prototype._render.call(self);
                self.setHeaderpaneTitle();
            } else {
                self.setHeaderpaneTitle(app.lang.get('LNK_SEARCH_NO_RESULTS'));
            }
        });
    },
    _addPreviewEvents: function() {
        app.events.on("list:preview:decorate", this.decorateRow, this);
        this.collection.on("reset", function() {
            //When fetching more records, we need to update the preview collection
            app.events.trigger("preview:collection:change", this.collection);
            if (this._previewed) {
                this.decorateRow(this._previewed);
            }
        }, this);
    },

    /**
     * Changes the {@link View.Views.Base.HeaderpaneView} title.
     *
     * @param {string} [title] The title to set in the headerpane view. If not
     *   passed, will default to the label `LBL_PORTAL_SEARCH_RESULTS_TITLE`.
     */
    setHeaderpaneTitle: function(title) {
        if (!title) {
            var context = {
                'query': Handlebars.Utils.escapeExpression(this.lastQuery)
            };
            title = app.utils.formatString(app.lang.get('LBL_PORTAL_SEARCH_RESULTS_TITLE'), context);
        }

        this.context.trigger('headerpane:title', title);
    },
    // Highlights current result row. Also, executed when preview view fires an
    // preview:decorate event (e.g. user clicks previous/next arrows on side preview)
    decorateRow: function(model) {
        this._previewed = model;
        this.$("li.search").removeClass("on");
        if (model) {
            this.$("li.search[data-id=" + model.get("id") + "]").addClass("on");
        }
    },

    /**
     * Loads the right side preview view when clicking icon for a particular search result.
     */
    loadPreview: function(e) {
        var searchRow, selectedResultId, model;
        if (this.sidebarClosed) {
            app.controller.context.trigger('toggleSidebar');
            this.sidebarClosed = false;
        }
        searchRow = this.$(e.currentTarget).closest('li.search');
        // Grab search result model corresponding to preview icon clicked
        selectedResultId = $(searchRow).data("id");
        model = this.collection.get(selectedResultId);
        this.decorateRow(model);
        // This will result in result's data being displayed on preview
        app.events.trigger("preview:render", model, this.collection, false);
    }
})
