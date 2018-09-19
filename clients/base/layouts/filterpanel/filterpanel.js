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
 * @class View.Layouts.Base.FilterpanelLayout
 * @alias SUGAR.App.view.layouts.BaseFilterpanelLayout
 * @extends View.Layouts.Base.TogglepanelLayout
 */
({
    extendsFrom: 'TogglepanelLayout',

    /**
     * @inheritdoc
     *
     * Certain options can be set in the filterpanel metadata:
     *     - `auto_apply`: this will determine whether or not to apply the
     *       filter while completing filter rows. This is used mainly because
     *       getRelevantContextList may return the global context and will
     *       filter its collection automatically, and sometimes this is not
     *       desired (e.g. a drawer layout with a filterpanel embedded).
     *
     *     - `stickiness`: this will determine whether or not to save
     *       properties pertaining to filters in localstorage. This is needed
     *       for certain views that have filterpanels, do not require
     *       stickiness and do not want to affect already-stored values in
     *       localstorage (e.g. the filterpanel layout in dashboardconfiguration
     *       shouldn't affect the stickiness of filters on record/list views,
     *       so it should be set to false).
     *
     *     - `show_actions`: this will determine whether or not the
     *       `delete`, `reset`, and `cancel` action buttons will be rendered on
     *       the `filter-actions` view.
     *
     *     @example
     *     <pre><code>
     *         'layout' => array(
     *              'type' =>'filterpanel',
     *              'filter_options' => array(
     *                  'auto_apply' => false,
     *                  'stickiness' => false,
     *                  'show_actions' => false,
     *              ),
     *          ),
     *     </code></pre>
     */
    initialize: function(opts) {
        // The filter options default to true.
        var defaultOptions = {
            'auto_apply': true,
            'stickiness': true,
            'show_actions': true
        };

        this.events = _.extend({}, this.events, {
            'click [data-action=refreshList]': '_refreshList'
        });

        var moduleMeta = app.metadata.getModule(opts.module) || {};
        this.disableActivityStreamToggle(opts.module, moduleMeta, opts.meta || {});

        this.on("filterpanel:change:module", function(module, link) {
            this.currentModule = module;
            this.currentLink = link;
        }, this);

        this.on('filter:create:open', function() {
            this.$('.filter-options').removeClass('hide');

            // "filter:create:open" is triggered even when the edit drawer is
            // being closed, so protect against saving the shortcuts when that
            // happens
            var activeShortcutSession = app.shortcuts.getCurrentSession();
            if (_.isNull(activeShortcutSession)
                || (activeShortcutSession && activeShortcutSession.layout !== this)) {
                app.shortcuts.saveSession();
                app.shortcuts.createSession([
                    'Filter:Add',
                    'Filter:Remove',
                    'Filter:Close',
                    'Filter:Save',
                    'Filter:Delete',
                    'Filter:Reset'
                ], this);
            }
        }, this);

        this.on('filter:create:close', function() {
            this.$('.filter-options').addClass('hide');

            // "filter:create:close" is triggered even when filter:create:open has not been called
            var activeShortcutSession = app.shortcuts.getCurrentSession();
            if (activeShortcutSession && (activeShortcutSession.layout === this)) {
                app.shortcuts.restoreSession();
            }
        }, this);

        // This is required, for example, if we've disabled the subapanels panel so that app doesn't attempt to render later
        this.on('filterpanel:lastviewed:set', function(viewed) {
            this.toggleViewLastStateKey = this.toggleViewLastStateKey || app.user.lastState.key('toggle-view', this);
            var lastViewed = app.user.lastState.get(this.toggleViewLastStateKey);
            if (lastViewed !== viewed) {
                app.user.lastState.set(this.toggleViewLastStateKey, viewed);
            }
        }, this);

        this._super("initialize", [opts]);

        // Set the filter that's currently being edited.
        this.context.editingFilter = null;

        // Obtain any options set in the metadata and override the defaultOptions with them
        // to set on the context.
        var filterOptions = _.extend(defaultOptions, this.meta.filter_options, this.context.get('filterOptions'));
        this.context.set('filterOptions', filterOptions);

        // The `defaultModule` will either evaluate to the model's module (more
        // specific, and used on dashablelist filters), or the module on the
        // current context.
        var lastViewed = app.user.lastState.get(this.toggleViewLastStateKey),
            defaultModule = this.module || this.model.get('module') || this.context.get('module');

        this.trigger('filterpanel:change:module', (moduleMeta.activityStreamEnabled && lastViewed === 'activitystream') ? 'Activities' : defaultModule);
    },

    /**
     * Applies last filter
     * @param {Collection} collection the collection to retrieve the filter definition
     * @param {String} condition(optional) You can specify a condition in order to prevent applying filtering
     */
    applyLastFilter: function(collection, condition) {
        var triggerFilter = true;
        if (_.size(collection.origFilterDef)) {
            if (condition === 'favorite') {
                //Here we are verifying the filter applied contains $favorite otherwise we don't really care about
                //refreshing the listview
                triggerFilter = !_.isUndefined(_.find(collection.origFilterDef, function(value, key) {
                    return key === '$favorite' || (value && !_.isUndefined(value.$favorite));
                }));
            }
            if (triggerFilter) {
                var query = this.$('.search input.search-name').val();
                this.trigger('filter:apply', query, collection.origFilterDef);
            }
        }
    },

    /**
     * Refreshes the list view by applying filters.
     *
     * @private
     */
    _refreshList: function() {
        this.trigger('filter:apply');
    },
    
    /**
     * Disables the activity stream toggle if activity stream is not enabled for a module
     * @param {String} moduleName The name of the module
     * @param {Object} moduleMeta The metadata for the module
     * @param {Object} viewMeta The metadata for the component
     */
    disableActivityStreamToggle: function(moduleName, moduleMeta, viewMeta) {
        if (moduleName !== 'Activities' && !moduleMeta.activityStreamEnabled) {
            _.each(viewMeta.availableToggles, function(toggle) {
                if (toggle.name === 'activitystream') {
                    toggle.disabled = true;
                    toggle.label = 'LBL_ACTIVITY_STREAM_DISABLED';
                }
            });
        }
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        this._super('_render');
        // `filter-rows` view is outside of `filter` layout and is rendered after `filter` layout is rendered.
        // Now that we are able to preserve last search, we need to initialize filter only once all the filter
        // components rendered.
        this.trigger('filter:reinitialize');
    }
})
