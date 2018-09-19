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
 * View for the module dropdown.
 *
 * Part of {@link View.Layouts.Base.FilterLayout}.
 *
 * @class View.Views.Base.FilterModuleDropdownView
 * @alias SUGAR.App.view.views.BaseFilterModuleDropdownView
 * @extends View.View
 */
({
    //Override default Backbone tagName
    tagName: "span",
    className: "table-cell",

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        //Load partials
        this._select2formatSelectionTemplate = app.template.get("filter-module-dropdown.selection-partial");
        this._select2formatResultTemplate = app.template.get("filter-module-dropdown.result-partial");

        this.listenTo(this.layout, "filter:change:module", this.handleChange);
        this.listenTo(this.layout, "filter:render:module", this._render);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this._renderDropdown();
    },

    /**
     * Render select2 dropdown
     * @private
     */
    _renderDropdown: function() {
        var self = this;

        this.filterList = this.getFilterList();

        // Hide the dropdown if filterList has not been specified.
        if (!this.filterList) {
            this.$el.hide();
            return;
        }

        this.filterNode = this.$(".related-filter");

        this.filterNode.select2({
            data: this.filterList,
            multiple: false,
            minimumResultsForSearch: 7,
            formatSelection: _.bind(this.formatSelection, this),
            formatResult: _.bind(this.formatResult, this),
            dropdownCss: {width: 'auto'},
            dropdownCssClass: 'search-related-dropdown',
            initSelection: _.bind(this.initSelection, this),
            escapeMarkup: function(m) {
                return m;
            },
            width: 'off'
        });

        // Disable the module filter dropdown.
        if (this.shouldDisableFilter()) {
            this.filterNode.select2("disable");
        }

        this.filterNode.off("change");
        this.filterNode.on("change", function(e) {
            /**
             * Called when the user selects a module in the dropdown
             * Triggers filter:change:module on filter layout
             * @param {Event} e
             */
            var linkModule = e.val;
            if (self.layout.layoutType === "record" && linkModule !== "all_modules") {
                linkModule = app.data.getRelatedModule(self.module, linkModule);
            }
            self.layout.trigger("filter:change:module", linkModule, e.val);
        });
    },

    /**
     * Get the list for filter module dropdown.
     * @return {Object}
     */
    getFilterList: function() {
        var filterList;

        if (this.layout.showingActivities) {
            filterList = this.getModuleListForActivities();
        } else if (this.layout.layoutType === "record") {
            filterList = this.getModuleListForSubpanels();
        }

        return filterList;
    },

    /**
     * Should the filter be disabled?
     * @return {boolean}
     */
    shouldDisableFilter: function() {
        return (this.layout.layoutType !== "record" || this.layout.showingActivities);
    },

    /**
     * Trigger events when a change happens
     * @param {String} linkModuleName
     * @param {String} linkName
     * @param {Boolean} silent
     */
    handleChange: function(linkModuleName, linkName, silent) {
        if (linkName === "all_modules") {
            this.layout.trigger("subpanel:change");
        } else if (linkName) {
            this.layout.trigger("subpanel:change", linkName);
        }

        // It is important to reset the `currentFilterId` in order to retrieve
        // the last filter from cache later.
        this.context.set('currentFilterId', null);

        if (this.filterNode) {
            this.filterNode.select2("val", linkName || linkModuleName);
        }
        if (!silent) {
            this.layout.layout.trigger("filter:change", linkModuleName, linkName);
            this.layout.trigger('filter:get', linkModuleName, linkName);
            //Clear the search input and apply filter
            this.layout.trigger('filter:clear:quicksearch');
        }
    },

    /**
     * For record layout,
     * Populate the module dropdown by reading the subpanel relationships
     */
    getModuleListForSubpanels: function() {
        var filters = [];
        filters.push({id: "all_modules", text: app.lang.get("LBL_MODULE_ALL")});

        var subpanels = this.pullSubpanelRelationships();
        subpanels = this._pruneHiddenModules(subpanels);
        if (subpanels) {
            _.each(subpanels, function(value, key) {
                var module = app.data.getRelatedModule(this.module, value);
                if (app.acl.hasAccess("list", module)) {
                    filters.push({id: value, text: app.lang.get(key, this.module)});
                }
            }, this);
        }
        return filters;
    },

    /**
     * For Activity Stream,
     * Populate the module dropdown with a single item
     */
    getModuleListForActivities: function() {
        var filters = [], label;
        if (this.module == "Activities") {
            label = app.lang.get("LBL_MODULE_ALL");
        } else {
            label = app.lang.getModuleName(this.module, {plural: true});
        }
        filters.push({id: 'Activities', text: label});
        return filters;
    },

    /**
     * Pull the list of related modules from the subpanel metadata
     * @return {Object}
     */
    pullSubpanelRelationships: function() {
        // Subpanels are retrieved from the global module and not the
        // subpanel module, therefore we use this.module instead of
        // this.currentModule.
        return app.utils.getSubpanelList(this.module);
    },

    /**
     * Prunes hidden modules from related dropdown list
     * @param {Object} subpanels List of candidate subpanels to display
     * @return {Object} pruned list of subpanels
     * @private
     */
    _pruneHiddenModules: function(subpanels){
        var hiddenSubpanels = _.map(app.metadata.getHiddenSubpanels(), function(subpanel) {
            return subpanel.toLowerCase();
        });
        var pruned = _.reduce(subpanels, function(obj, value, key) {
            var relatedModule = app.data.getRelatedModule(this.module, value);
            if (relatedModule && !_.contains(hiddenSubpanels, relatedModule.toLowerCase())) {
                obj[key] = value;
            }
            return obj;
        }, {}, this);
        return pruned;
    },

    /**
     * Get the dropdown labels for the module dropdown
     * @param {Object} el
     * @param {Function} callback
     */
    initSelection: function(el, callback) {
        var selection, label;
        if (el.val() === "all_modules") {
            label = (this.layout.layoutType === "record") ? app.lang.get("LBL_MODULE_ALL") : app.lang.getModuleName(this.module, {plural: true});
            selection = {id: "all_modules", text: label};
        } else if (_.findWhere(this.filterList, {id: el.val()})) {
            selection = _.findWhere(this.filterList, {id: el.val()});
        } else if(this.filterList && this.filterList.length > 0)  {
            selection = this.filterList[0];
        }
        callback(selection);
    },

    /**
     * Returns the label for the dropdown.
     *
     * @return {string}
     */
    getSelectionLabel: function() {
        var selectionLabel;

        if (this.layout.layoutType !== "record" || this.layout.showingActivities) {
            selectionLabel = app.lang.get("LBL_MODULE");
        } else {
            selectionLabel = app.lang.get("LBL_RELATED") + '<i class="fa fa-caret-down"></i>';
        }

        return selectionLabel;
    },

    /**
     * Update the text for the selected module and returns template
     *
     * @param {Object} item
     * @return {string}
     */
    formatSelection: function(item) {
        var safeString;

        //Escape string to prevent XSS injection
        safeString = Handlebars.Utils.escapeExpression(item.text);
        // Update the text for the selected module.
        this.$('.choice-filter-label').html(safeString);

        return this._select2formatSelectionTemplate(this.getSelectionLabel());
    },

    /**
     * Returns template
     * @param {Object} option
     * @return {string}
     */
    formatResult: function(option) {
        // TODO: Determine whether active filters should be highlighted in bold in this menu.
        return this._select2formatResultTemplate(option.text);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (!_.isEmpty(this.filterNode)) {
            this.filterNode.select2('destroy');
        }
        this._super('_dispose');
    }
})
