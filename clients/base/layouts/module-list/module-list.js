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
 * Module list sets all the user defined modules visible in the main menu.
 *
 * This layout is responsible to keep the all the menus (created using
 * {@link View.Views.BaseModuleMenuView}) in a valid state.
 * It shows the menu in the main bar as well as in the dropdown (show more)
 * and keeps it in sync to provide the best user experience possible, while
 * keeping the DOM changes to a minimum.
 *
 * @class View.Layouts.Base.ModuleListLayout
 * @alias SUGAR.App.view.layouts.BaseModuleListLayout
 * @extends View.Layout
 */
({
    className: 'module-list',
    plugins: ['Dropdown'],

    /**
     * The catalog of modules linked to their menus (short and long).
     *
     * The menu element is to the partial created at {@link #_placeComponent}
     * method.
     *
     * @property {Object} A hash of module name with each short and long menus:
     * <pre><code>
     *     {
     *         'Home': {long: el1, short: el2},
     *         'Accounts': {long: el3, short: el4},
     *         //...
     *     }
     * </code></pre>
     *
     * @protected
     */
    _catalog: {},

    /**
     * The cached `[data-action=more-modules]` since this view can be quite
     * big.
     *
     * @property {jQuery} The jQuery element pointing to our
     *   `[data-action=more-modules]` element.
     *
     * @protected
     */
    _$moreModulesDD: undefined,

    /**
     * @inheritdoc
     *
     * Hooks to `app:sync:complete` to handle the refresh of the menu items
     * that are available after a complete sync.
     * Hooks to `app:view:change` to keep the active module highlighted.
     */
    initialize: function(options) {

        app.events.on('app:sync:complete', this._resetMenu, this);
        app.events.on('app:view:change', this.handleViewChange, this);

        this._super('initialize', [options]);

        if (this.layout) {
            this.toggleResize(true);
        }
    },

    /**
     * Toggles the resize listener on or off.
     * Pass `true` to turn the listener on, or `false` to turn the listener off.
     * @param {boolean} resize
     */
    toggleResize: function(resize) {
        this.layout.off('view:resize');
        if (resize) {
            this.layout.on('view:resize', this.resize, this);
        }
    },

    /**
     * Method called on `app:view:change` hooked in
     * {@link BaseModuleListLayout#initialize}.
     *
     * It sets the active module to the one set in the context and fires a
     * `header:update:route` event to it's parent layout.
     */
    handleViewChange: function() {
        var module = app.controller.context.get('module');
        var component = app.drawer.getActive();
        if (component && component.context.get('fromRouter')) {
            module = component.context.get('module');
        }

        this._setActiveModule(module);
        this.layout.trigger('header:update:route');
    },

    /**
     * @inheritdoc
     *
     * If it is a `module-menu` component, we wrap it with our `list` template
     * and place it before the `more-modules` drop down or inside the drop down
     * if we are handling a short version of the menu.
     * The short version is always hidden, since it will be toggled on the
     * first resize call (when it overflows the existing width).
     *
     * @param {View.View/View.Layout} component View or layout component.
     * @protected
     */
    _placeComponent: function(component) {
        if (component.name !== 'module-menu') {
            this.$el.append(component.el);
            return;
        }

        var tpl = app.template.getLayout(this.name + '.list', component.module) ||
            app.template.getLayout(this.name + '.list'),
            $content = $(tpl({module: component.module})).append(component.el);

        // initialize catalog if isn't initialized
        this._catalog[component.module] = this._catalog[component.module] || {};

        if (component.meta && component.meta.short) {
            $content.addClass('hidden');
            this._catalog[component.module].short = $content;
            this._$moreModulesDD.find('[data-container="overflow"]').append($content);
        } else {
            this._catalog[component.module].long = $content;
            this.$('[data-action="more-modules"]').before($content);
        }
    },

    /**
     * Resets the menu based on new metadata information.
     *
     * It resets components, catalog and template (html), and calls
     * {@link #resize} with the last known space available for this view.
     *
     * @protected
     */
    _resetMenu: function() {

        this._components = [];
        this._catalog = {};
        this.$el.html(this.template(this, this.options));

        // cache the more-dropdown now
        this._$moreModulesDD = this.$('[data-action="more-modules"]');

        this._addDefaultMenus();
        this._setActiveModule(app.controller.context.get('module'));
        this.render();
        this.resize(this._width);
    },

    /**
     * Adds all default menu views as components in both full and short
     * version.
     *
     * This will set the menu as sticky to differentiate from the others that
     * are added based on navigation/reference only.
     *
     * @private
     */
    _addDefaultMenus: function() {

        var moduleList = app.metadata.getModuleNames({filter: 'display_tab', access: 'read'});

        _.each(moduleList, function(module) {
            this._addMenu(module, true);
        }, this);
    },

    /**
     * Adds a menu as a component. Sticky menus aren't added to `more-modules`
     * list.
     *
     * @param {String} module The module
     * @param {Boolean} [sticky=false] Set to `true` if this is a menu that is
     *   part of user preferences.
     * @return {Object} The `menu.long` and `menu.short` components created.
     *   If `sticky` param is `false`, then no `menu.short` is provided.
     * @private
     */
    _addMenu: function(module, sticky) {
        var menu = {};

        var def = {
            view: {
                type: 'module-menu',
                sticky: sticky,
                short: false
            }
        };
        menu.long = this.createComponentFromDef(def, null, module);
        this.addComponent(menu.long, def);

        if (!sticky) {
            return menu;
        }

        def = {
            view: {
                type: 'module-menu',
                short: true
            }
        };
        menu.short = this.createComponentFromDef(def, null, module);
        this.addComponent(menu.short, def);

        return menu;
    },

    /**
     * Resize the module list to the specified width and move the extra module
     * names to the `more-modules` drop down.
     *
     * @param {number} width The width that we have available.
     */
    resize: function(width) {
        /**
         * Cached version of last width available for this view.
         *
         * @type {number}
         * @private
         */
        this._width = width;

        if (width <= 0 || _.isEmpty(this._components)) {
            return;
        }

        var $moduleList = this.$('[data-container=module-list]'),
            $dropdown = this._$moreModulesDD.find('[data-container=overflow]');

        if ($moduleList.outerWidth(true) >= width) {
            this.removeModulesFromList($moduleList, width);
        } else {
            this.addModulesToList($moduleList, width);
        }
        this._$moreModulesDD.toggleClass('hidden', $dropdown.children('li').not('.hidden').length === 0);
    },

    /**
     * Computes the minimum width required for the module list.
     * This includes: the cube, the current module, and the more modules drop down.
     * @return {number}
     */
    computeMinWidth: function() {
        var minWidth = 0;
        var $moduleChildren = this.$('[data-container=module-list]').children();

        // The cube
        var $first = $moduleChildren.first();
        minWidth += $first.outerWidth() + 1;

        // The current active module
        var firstModule = $moduleChildren.filter('.active').not($first);
        if (firstModule.length) {
            minWidth += firstModule.outerWidth() + 1;
        } else {
            // or the first module
            minWidth += $moduleChildren.eq(1).outerWidth() + 1;
        }

        // More Modules dropdown
        minWidth += $moduleChildren.last().outerWidth() + 1;
        return minWidth;
    },

    /**
     * Move modules from the dropdown to the list to fit the specified width.
     * @param {jQuery} $modules The jQuery element that contains all the
     *   modules.
     * @param {Number} width The current width we have available.
     */
    addModulesToList: function($modules, width) {
        var $dropdown = this._$moreModulesDD.find('[data-container=overflow]'),
            $toHide = $dropdown.children('li').not('.hidden').first(),
            currentWidth = $modules.outerWidth(true);

        while (currentWidth < width && $toHide.length > 0) {
            this.toggleModule($toHide.data('module'), true);

            $toHide = $dropdown.children('li').not('.hidden').first();

            currentWidth = $modules.outerWidth(true);
        }

        if (currentWidth >= width) {
            this.removeModulesFromList($modules, width);
        }
    },

    /**
     * Move modules from the list to the dropdown to fit the specified width
     * @param {jQuery} $modules The jQuery element that contains all the
     *   modules.
     * @param {Number} width The current width we have available.
     */
    removeModulesFromList: function($modules, width) {

        var $toHide = this._$moreModulesDD.prev();

        while ($modules.outerWidth(true) > width && $toHide.length > 0) {
            if (!this.isRemovableModule($toHide.data('module'))) {
                $toHide = $toHide.prev();
                continue;
            }

            this.toggleModule($toHide.data('module'), false);

            $toHide = $toHide.prev();
        }
    },

    /**
     * Toggle module menu given. This will make sure it will be always in sync.
     *
     * We decided to assume that the `more-modules` drop down is the master of
     * the information to keep in sync.
     *
     * If we don't have a short menu version (on `more-modules` drop down),
     * it means that we don't need to keep it in sync and just show/hide based
     * on the module name. Think at this as a cached menu until we get another
     * `app:sync:complete` event.
     *
     * @param {String} module The module you want to turn on/off.
     * @param {Boolean} [state] `true` to show it on mega menu, `false`
     *   otherwise. If no state given, will toggle.
     *
     * @chainable
     */
    toggleModule: function(module, state) {
        // cache version only
        if (!this._catalog[module].short) {
            state = !_.isUndefined(state) ? !state : undefined;
            this._catalog[module].long.toggleClass('hidden', state);
            return this;
        }

        // keep it in sync
        var newState = this._catalog[module].short.toggleClass('hidden', state).hasClass('hidden');
        this._catalog[module].long.toggleClass('hidden', !newState);

        return this;
    },

    /**
     * Sets the module given as active and shown in the main nav bar.
     *
     * This waits for the full `this._components` to be set first. If we fail
     * to do that, we will see the current module context as the first menu.
     *
     * The module to be shown as active in the main nav bar is mapped by
     * {@link Core.MetadataManager#getTabMappedModule} to be displayed.
     *
     * Cached versions of the modules are always hidden if not active.
     *
     * @param {String} module the Module to set as Active on the menu.
     *
     * @protected
     * @chainable
     */
    _setActiveModule: function(module) {

        if (_.isEmpty(this._components)) {
            // wait until we have the mega menu in place
            return this;
        }

        var tabMap = app.metadata.getModuleTabMap(),
            mappedModule = _.isUndefined(tabMap[module]) ? module : tabMap[module],
            $activeModule = this.$('[data-container=module-list]').children('.active').removeClass('active'),
            activeModule = $activeModule.data('module'),
            moduleList = app.metadata.getFullModuleList(),
            inModuleList = !_.isUndefined(moduleList[mappedModule]);

        if (this._catalog[activeModule] && !this._catalog[activeModule].short) {
            // hide the cached version only module
            this.toggleModule(activeModule, false);
        }

        // If this is a tab-mapped module, but not mapped to anything
        // or invalid mapping, don't continue execution.
        if (!mappedModule || !inModuleList) {
            return this;
        }

        if (!this._catalog[mappedModule]) {
            this._addMenu(mappedModule, false).long.render();
        }

        this._catalog[mappedModule].long.addClass('active');
        this.toggleModule(mappedModule, true);

        return this;
    },

    /**
     * Returns `true` if a certain module can be removed from the main nav bar,
     * `false` otherwise.
     *
     * Currently we can't remove the Home module (sugar cube) neither the
     * current active module.
     *
     * @param {String} module The module to check.
     *
     * @return {Boolean} `true` if the module is safe to be removed.
     */
    isRemovableModule: function(module) {
        return !(module === 'Home' || this.isActiveModule(module));
    },

    /**
     * Returns `true` when the module is active in main nav bar, `false`
     * otherwise.
     *
     * This is normally based on the `App.controller.context` current module
     * and then sets a fallback mechanism to determine which module it is,
     * that you can see described in {@link #_setActiveModule}.
     *
     * @param {String} module The module to check.
     *
     * @return {Boolean} `true` if the module is safe to be removed.
     */
    isActiveModule: function(module) {
        return this._catalog[module].long.hasClass('active');
    }

})
