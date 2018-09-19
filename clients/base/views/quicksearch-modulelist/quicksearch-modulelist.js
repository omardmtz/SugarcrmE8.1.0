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
 * @class View.Views.Base.QuicksearchModuleListView
 * @alias SUGAR.App.view.views.BaseQuicksearchModuleListView
 * @extends View.View
 */
({
    className: 'table-cell quicksearch-modulelist-wrapper',
    plugins: ['Dropdown'],
    dropdownItemSelector: '[data-action=select-module], [data-action=select-all]',

    events: {
        'click [data-action=select-all]': 'selectAllModules',
        'click [data-action=select-module]': 'selectModule',
        'keydown [data-action=select-all]': 'allModulesKeydownHandler',
        'keydown [data-action=select-module]': 'moduleKeydownHandler',
        'click [data-toggle=dropdown]': 'moduleDropdownClick'
    },

    // List of modules that should not be included in the module list
    blacklistModules: ['Tags'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.hide();

        this.collection = this.layout.collection || app.data.createMixedBeanCollection();

        /**
         * A collection of the available modules.
         *
         * @type {Backbone.Collection}
         */
        this.searchModuleFilter = new Backbone.Collection(null, {
            //adds models in alphabetical order of model's id's module name translation
            comparator: function(model) {
                return app.lang.getModuleName(model.id, {plural: true});
            }
        });

        /**
         * The lastState key for local storage.
         *
         * @type {String}
         */
        this.stateKey = app.user.lastState.buildKey('quicksearch', 'modulelist', this.name);

        /**
         * Template for the module icons in the search bar.
         *
         * @type {Handlebars.Template}
         * @private
         */
        this._moduleIconTemplate = app.template.getView(this.name + '.module-avatar');

        /**
         * Data structure for the display of the module icons.
         *
         * @type {Object}
         */
        this.moduleIcons = {};

        // When the app is ready, fetch the searchable modules and put them in
        // the module list dropdown. This cannot be in the initialize because
        // when initialize is called, the only module available is login.
        app.events.on('app:sync:complete', function() {
            this.populateModules();
            // If there is a module preference stored in local storage,
            // default selection to those modules.
            var previousModuleSelection = app.user.lastState.get(this.stateKey);
            if (_.isEmpty(previousModuleSelection)) {
                this.searchModuleFilter.allSelected = true;
            } else {
                _.each(previousModuleSelection, function(module) {
                    this.searchModuleFilter.get(module).set('selected', true);
                }, this);
            }
            this._setSelectedModules();
            // Prepare the module icons for display
            var moduleIconObj = this._buildModuleIconList();
            this.moduleIcons = {icon: moduleIconObj};
            this.render();
            this.layout.off('route:search', this.populateModuleSelectionFromContext);
            this.layout.on('route:search', this.populateModuleSelectionFromContext, this);

            // We need to call `populateModuleSelectionFromContext` here to
            // update the module icons if the user navigates directly to the
            // search page from outside Sugar. In this use case,
            // 'quicksearch-modulelist.js' is initialized and needs to update
            // the module icons to be in sync with the ones in the url.
            this.populateModuleSelectionFromContext();
        }, this);

        // On expansion of quicksearch, show the module dropdown & buttons.
        this.layout.on('quicksearch:expanded', this.show, this);

        // On collapse of quicksearch, hide the module dropdown & buttons.
        this.layout.on('quicksearch:collapse', this.hide, this);

        // Whenever anything happens within the quicksearch layout navigation,
        // close the module list dropdown.
        this.layout.on('navigate:next:component navigate:previous:component navigate:to:component', function() {
            this.$el.removeClass('open');
        }, this);
    },

    /**
     * Populate the module selection from the search context.
     */
    populateModuleSelectionFromContext: function() {
        // Reset all the selections
        var previousModuleSelection = this.context.get('module_list');
        this.searchModuleFilter.invoke('set', {selected: false});
        this.$('[data-action=select-module]').removeClass('selected');

        // Handle the 'all selected' case
        if (_.isEmpty(previousModuleSelection)) {
            this.searchModuleFilter.allSelected = true;
            this.$('[data-action=select-all]').addClass('selected');
            // A specific set of modules have been selected.
        } else {
            this.searchModuleFilter.allSelected = false;
            this.$('[data-action=select-all]').removeClass('selected');
            _.each(previousModuleSelection, function(module) {
                var moduleFilter = this.searchModuleFilter.get(module);
                if (moduleFilter) {
                    moduleFilter.set('selected', true);
                    this.$('[data-action=select-module][data-module=' + module + ']').addClass('selected');
                }
            }, this);
        }
        this._setSelectedModules();
        this._updateModuleIcons();
    },

    /**
     * Handle module 'select/unselect' event.
     *
     * @param {Event} event
     */
    selectModule: function(event) {
        // We need to stop propagation for two reasons:
        // 1) Stop scrolling when using the spacebar.
        // 2) Prevent collapse of the `quicksearch` layout. The module list is
        // considered inside the dropdown plugin, and not in the layout. Clicks
        // outside the layout normally collapse the layout.
        event.stopImmediatePropagation();
        var $li = $(event.currentTarget);
        var module = $li.data('module');
        var moduleModel = this.searchModuleFilter.get(module);

        // If all the modules were selected, we unselect all of them first.
        if (this.searchModuleFilter.allSelected) {
            this.$('[data-action=select-all]').removeClass('selected', false);
            this.searchModuleFilter.allSelected = false;
        }

        // Then we select the clicked module.
        var checkModule = !moduleModel.get('selected');
        moduleModel.set('selected', checkModule);
        $li.toggleClass('selected', checkModule);

        // Check to see if all the modules are now all selected or unselected.
        var selectedLength = this.searchModuleFilter.where({'selected': true}).length;

        // All modules are selected, set them all to unselected.
        if (selectedLength === this.searchModuleFilter.length) {
            this.searchModuleFilter.invoke('set', {selected: false});
            selectedLength = 0;
        }

        // If all modules are now unselected, update checkboxes and set the
        // `allSelected` property of the filter.
        if (selectedLength === 0) {
            this.searchModuleFilter.allSelected = true;
            this.$('[data-action=select-all]').addClass('selected');
            this.$('[data-action=select-module]').removeClass('selected');
        }

        this._setSelectedModules();
        this._updateModuleIcons();
    },

    /**
     * Handle clicks on the "Search all" list item.
     * @param {event} event
     */
    selectAllModules: function(event) {
        // We need to stop propagation for two reasons:
        // 1) Stop scrolling when using the spacebar.
        // 2) Prevent collapse of the `quicksearch` layout. The module list is
        // considered inside the dropdown plugin, and not in the layout. Clicks
        // outside the layout normally collapse the layout.
        event.stopImmediatePropagation();

        // Selects all modules.
        this.$('[data-action=select-all]').addClass('selected');
        this.$('[data-action=select-module]').removeClass('selected');
        this.searchModuleFilter.invoke('set', {selected: false});
        this.searchModuleFilter.allSelected = true;

        this._setSelectedModules();
        this._updateModuleIcons();
    },

    /**
     * Handles the keydown events on the "All Modules" checkbox.
     * On spacebar, trigger the same functionality as a click.
     *
     * @param {Event} event The `keydown` event
     */
    allModulesKeydownHandler: function(event) {
        if (event.keyCode === 32) { // space bar
            this.selectAllModules(event);
            event.preventDefault();
        }
    },

    /**
     * Handles the keydown events on the module list items.
     * On spacebar, trigger the same functionality as a click.
     *
     * @param {Event} event The `keydown` event
     */
    moduleKeydownHandler: function(event) {
        if (event.keyCode === 32) { // space bar
            this.selectModule(event);
            event.preventDefault();
        }
    },

    /**
     * Trigger `quicksearch:results:close` when the module dropdown is clicked.
     */
    moduleDropdownClick: function() {
        this.layout.trigger('quicksearch:results:close');
    },

    /**
     * Updates the modules icons in the search bar, based on the currently
     * selected modules.
     *
     * @private
     */
    _updateModuleIcons: function() {
        // Update the module icons in the search bar.
        var $moduleIconContainer = this.$('[data-label=module-icons]');
        $moduleIconContainer.empty();
        var moduleIconObj = this._buildModuleIconList();

        $moduleIconContainer.append(this._moduleIconTemplate({icon: moduleIconObj}));
    },

    /**
     * Builds an array of objects for displaying the module icons.
     * @return {Array}
     * @private
     */
    _buildModuleIconList: function() {
        var moduleIconObj = [];
        // If all modules are selected, display "all" icon.
        if (this.collection.selectedModules.length === 0) {
            moduleIconObj.push({});
            // If 3 or fewer selected, display the module icons that are selected.
        } else if (this.collection.selectedModules.length <= 3) {
            _.each(this.collection.selectedModules, function(module) {
                moduleIconObj.push({module: module});
            }, this);
            // If there are more than 3 modules selected, display the
            // "Multiple Modules" icon
        } else {
            moduleIconObj.push({multiple: true});
        }
        return moduleIconObj;
    },

    /**
     * Populate `this.searchModuleFilter` with the searchable modules, using
     * acls and the metadata attribute `checkGlobalSearchEnabled`.
     */
    populateModules: function() {
        if (this.disposed) {
            return;
        }

        //Reset the collection of module filters so we don't add duplicate
        //elements to the original collection.
        this.searchModuleFilter.reset();
        var modules = app.metadata.getModules() || {};

        //filter the module names out based on global search enabled, has
        //access to acl, and is not a blacklisted module
        _.each(modules, function(meta, module) {
            if (meta.globalSearchEnabled &&
                app.acl.hasAccess.call(app.acl, 'view', module) &&
                !_.contains(this.blacklistModules, module)
            ) {
                var moduleModel = new Backbone.Model({id: module, selected: false});
                this.searchModuleFilter.add(moduleModel);
            }
        }, this);
    },

    /**
     * Store the selected modules on the collection and in local storage.
     *
     * @private
     */
    _setSelectedModules: function() {
        var selectedModules = [];
        if (!this.searchModuleFilter.allSelected) {
            this.searchModuleFilter.each(function(model) {
                if (model.get('selected')) {
                    selectedModules.push(model.id);
                }
            });
        }

        this.collection.selectedModules = selectedModules;
        app.user.lastState.set(this.stateKey, this.collection.selectedModules);
    }
})
