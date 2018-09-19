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
 * Module menu provides a reusable and easy render of a module Menu.
 *
 * This also helps doing customization of the menu per module and provides more
 * metadata driven features.
 *
 * @class View.Views.Base.ModuleMenuView
 * @alias SUGAR.App.view.views.BaseModuleMenuView
 * @extends View.View
 */
({
    tagName: 'span',

    events: {
        'click [data-event]': 'handleMenuEvent',
        'click [data-route]': 'handleRouteEvent',
        'shown.bs.dropdown': 'populateMenu'
    },

    /**
     * The possible actions that this module menu provides.
     *
     * This comes from the metadata files, like:
     *
     * - {custom}/modules/&lt;Module&gt;/clients/base/menus/header/header.php
     */
    actions: [],

    /**
     * Default settings used when none are supplied through metadata.
     *
     * Supported settings:
     * - {Number} favorites Number of records to show on the favorites
     *   container. Pass 0 if you don't want to support favorites.
     * - {Number} recently_viewed Number of records to show on the recently
     *   viewed container. Pass 0 if you don't want to support recently viewed.
     *
     * Example:
     * ```
     * // ...
     * 'settings' => array(
     *     'favorites' => 5,
     *     'recently_viewed' => 9,
     *     //...
     * ),
     * //...
     * ```
     *
     * @protected
     */
    _defaultSettings: {
        favorites: 3,
        recently_viewed: 3
    },

    /**
     * Settings after applied metadata settings on top of
     * {@link View.Views.BaseModuleMenuView#_defaultSettings default settings}.
     *
     * @protected
     */
    _settings: {},

    /**
     * @inheritdoc
     *
     * Adds listener for bootstrap drop down show event (`shown.bs.dropdown`).
     * This will trigger menuOpen method.
     */
    initialize: function(options) {

        options.meta = _.extend(
            {},
            options.meta,
            app.metadata.getView(null, options.name),
            app.metadata.getView(options.module, options.name)
        );

        this._super('initialize', [options]);
        this._initSettings();

        /**
         * The internal array of collections for favorites and recent.
         * The collections shouldn't be reused with different filters when
         * fetching in parallel.
         *
         * @property {Object}
         * @private
         */
        this._collections = {};
    },


    /**
     * Initialize settings, default settings are used when none are supplied
     * through metadata.
     *
     * @return {View.Views.BaseModuleMenuView} Instance of this view.
     * @protected
     */
    _initSettings: function() {

        this._settings = _.extend({},
            this._defaultSettings,
            this.meta && this.meta.settings || {}
        );

        return this;
    },

    /**
     * @inheritdoc
     *
     * Retrieves possible menus from the metadata already inSync.
     * Filters all menu actions based on ACLs to prevent user to click them and
     * get a `403` after click.
     */
    _renderHtml: function() {
        var meta = app.metadata.getModule(this.module) || {};

        this.actions = this.filterByAccess(meta.menu && meta.menu.header && meta.menu.header.meta);

        this._super('_renderHtml');

        if (!this.meta.short) {
            this.$el.addClass('btn-group');
        }
    },

    /**
     * Filters menu actions by ACLs for the current user.
     *
     * @param {Array} meta The menu metadata to check access.
     * @return {Array} Returns only the list of actions the user has access.
     */
    filterByAccess: function(meta) {

        var result = [];

        _.each(meta, function(menuItem) {
            if (app.acl.hasAccess(menuItem.acl_action, menuItem.acl_module)) {
                result.push(menuItem);
            }
        });

        return result;
    },

    /**
     * Method called when a `show.bs.dropdown` event occurs.
     *
     * Populate the favorites and recently viewed records every time we open
     * the menu. This is only supported on modules that have fields.
     */
    populateMenu: function() {

        var meta = app.metadata.getModule(this.module) || {};

        if (_.isEmpty(_.omit(meta.fields, '_hash'))) {
            return;
        }

        if (meta.favoritesEnabled) {
            this.populate('favorites', [{
                '$favorite': ''
            }], this._settings.favorites);
        }

        this.populate('recently-viewed', [{
            '$tracker': '-7 DAY'
        }], this._settings.recently_viewed);
    },


    /**
     * Return `true` if this menu is open, `false` otherwise.
     * @return {Boolean} `true` if this menu is open, `false` otherwise.
     */
    isOpen: function() {
        return !!this.$el.hasClass('open');
    },

    /**
     * Populates records templates based on filter given.
     *
     * @param {String} tplName The template to use to populate data.
     * @param {String} filter The filter to be applied.
     * @param {Number} limit The number of records to populate. Needs to be an
     *   integer `> 0`.
     */
    populate: function(tplName, filter, limit) {
        if (limit <= 0) {
            return;
        }

        this.getCollection(tplName).fetch({
            'showAlerts': false,
            'fields': ['id', 'name'],
            // TODO SC-3696 this filter can be initialized once in the
            // getCollection() and be metadata driven.
            'filter': filter,
            'limit': limit,
            'success': _.bind(function() {
                this._renderPartial(tplName);
            }, this)
        });
    },

    /**
     * Get the collection for the partial (favorites or recently viewed).
     *
     * @param {string} tplName The name of the partial template that will use
     *   this collection.
     * @return {Data.BeanCollection} The collection of this module.
     */
    getCollection: function(tplName) {
        if (!this._collections[tplName]) {
            this._collections[tplName] =
                app.data.createBeanCollection(this.module, [], {params: {erased_fields: true}});
            // TODO SC-3696 create the initial filter based on metadata for the
            // partials
        }

        return this._collections[tplName];
    },

    /**
     * Renders the data in the partial template given.
     *
     * The partial template can receive more data from the options parameter.
     *
     * @param {String} tplName The template to use to render the partials.
     * @param {Object} [options] Other optional data to pass to the template.
     * @protected
     */
    _renderPartial: function(tplName, options) {
        if (this.disposed || !this.isOpen()) {
            return;
        }
        options = options || {};

        var tpl = app.template.getView(this.name + '.' + tplName, this.module) ||
            app.template.getView(this.name + '.' + tplName);

        var self = this;
        var collection = this.getCollection(tplName);
        _.each(collection.models, function(model) {
            if (app.utils.isNameErased(model)) {
                model.set('erased', true);
                model.set('erasedText', app.lang.get('LBL_VALUE_ERASED'));
            }
        });

        var $placeholder = this.$('[data-container="' + tplName + '"]');
        var $old = $placeholder.nextUntil('.divider');

        //grab the focused element's route (if exists) for later re-focusing
        var focusedRoute = $old.find(document.activeElement).data('route');

        //replace the partial using newly updated collection
        $old.remove();
        $placeholder.after(tpl(_.extend({'collection': collection}, options)));

        //if there was a focused element previously, restore its focus
        if (focusedRoute) {
            var $new = $placeholder.nextUntil('.divider');
            var focusSelector = '[data-route="' + focusedRoute + '"]';
            var $newFocus = $new.find(focusSelector);
            if ($newFocus.length > 0) {
                $newFocus.focus();
            }
        }
    },

    /**
     * This gives support to any events that might exist in the menu actions.
     *
     * Out of the box we don't have any use case for actions that are event
     * driven. Since it was already provided since 7.0.0 we will keep it util
     * further notice.
     *
     * @param {Event} evt The event that triggered this (normally a click
     *   event).
     */
    handleMenuEvent: function(evt) {
        var $currentTarget = this.$(evt.currentTarget);
        app.events.trigger($currentTarget.data('event'), this.module, evt);
    },

    /**
     * This triggers router navigation on both menu actions and module links.
     *
     * Since we normally trigger the drawer for some actions, we prevent it
     * when using the click with the `ctrlKey` (or `metaKey` in Mac OS).
     * We also prevent the routing to be fired when this happens.
     *
     * When we are triggering the same route that we already are in, we just
     * trigger a {@link Core.Routing#refresh}.
     *
     * @param {Event} event The event that triggered this (normally a click
     *   event).
     */
    handleRouteEvent: function(event) {
        var currentRoute,
            $currentTarget = this.$(event.currentTarget),
            route = $currentTarget.data('route');

        event.preventDefault();
        if ((!_.isUndefined(event.button) && event.button !== 0) || event.ctrlKey || event.metaKey || $currentTarget.data('openwindow') === true) {
            event.stopPropagation();
            window.open(route, '_blank');
            return false;
        }

        currentRoute = '#' + Backbone.history.getFragment();
        (currentRoute === route) ? app.router.refresh() : app.router.navigate(route, {trigger: true});
    }

})
