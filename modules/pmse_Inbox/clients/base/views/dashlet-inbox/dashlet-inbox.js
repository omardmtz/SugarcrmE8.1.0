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
    extendsFrom: 'HistoryView',

    /**
     * @inheritdoc
     *
     * @property {Number} _defaultSettings.limit Maximum number of records to
     *   load per request, defaults to '10'.
     * @property {String} _defaultSettings.visibility Records visibility
     *   regarding current user, supported values are 'user' and 'group',
     *   defaults to 'user'.
     */
    _defaultSettings: {
        date:'true',
        limit: 10,
        visibility: 'user'
    },

    thresholdRelativeTime: 2, //Show relative time for 2 days and then date time after

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta = options.meta || {};
        options.meta.template = 'tabbed-dashlet';

        this._super('initialize', [options]);
    },

    /**
     * Besides defining new DOM events that will be later bound to methods
     * through {@link #delegateEvents, the events method also makes sure parent
     * classes events are explicitly inherited.
     *
     * @property {Function}
     */
    events: function() {
        var prototype = Object.getPrototypeOf(this);
        var parentEvents = _.result(prototype, 'events');

        return _.extend({}, parentEvents, {
            'click [data-action=date-switcher]': 'dateSwitcher'
        });
    },

    /**
     * Event handler for date switcher.
     *
     * @param {Event} event Click event.
     */
    dateSwitcher: function(event) {
        var date = this.$(event.currentTarget).val();
        if (date === this.getDate()) {
            return;
        }
        this.settings.set('date', date);
        this.loadData();
    },

    /**
     * Get current date state.
     * Returns default value if can't find in last state or settings.
     *
     * @return {String} Date state.
     */
    getDate: function() {
        var date = app.user.lastState.get(
            app.user.lastState.key('date', this),
            this
        );
        return date || this.settings.get('date') || this._defaultSettings.date;
    },

    /**
     * @inheritdoc
     *
     * On load of new data, make sure we reload invitations related data, if
     * it is defined for the current tab.
     */
    loadData: function(options) {
        if (this.disposed || this.meta.config) {
            return;
        }
        var tab = this.tabs[this.settings.get('activeTab')];
        if (tab.invitations) {
            tab.invitations.dataFetched = false;
        }
        this._super('loadData', [options]);
    },

    /**
     * @inheritdoc
     *
     * FIXME: This should be removed when metadata supports date operators to
     * allow one to define relative dates for date filters.
     */
    _initTabs: function() {
        this._super('_initTabs');
    },

    /**
     * @inheritdoc
     */
    _getFilters: function(index) {
          var  tab = this.tabs[index],
            filter = {},
            filters = [],
            defaultFilters = {
                'true': {$equal: 'true'},
                'false': {$equal: 'false'}
            };

        filter[tab.filter_applied_to] = defaultFilters[this.getDate()];

        filters.push(filter);

        return filters;
    },

    /**
     * Updating in fields delete removed
     * @return {Function} complete callback
     * @private
     */
    _getRemoveRecord: function() {
        return _.bind(function(model){
            if (this.disposed) {
                return;
            }
            this.collection.remove(model);
            this.render();
            this.context.trigger("tabbed-dashlet:refresh", model.module);
        }, this);
    },

    /**
     * Method view alert in process with text modify
     * show and hide alert
     */
    _refresh: function(model, status) {
        app.alert.show(model.id + ':refresh', {
            level:"process",
            title: status,
            autoclose: false
        });
        return _.bind(function(model){
            var options = {};
            this.layout.reloadDashlet(options);
            app.alert.dismiss(model.id + ':refresh');
        }, this);
    },

    /**
     * Sets property useRelativeTime to show date created as a relative time or as date time.
     *
     * @private
     */
    _setRelativeTimeAvailable: function(date) {
        var diffInDays = Math.abs(app.date().diff(date, 'days', true));
        var useRelativeTime = (diffInDays <= this.thresholdRelativeTime);
        return useRelativeTime;
    },

    /**
     * @inheritdoc
     *
     * New model related properties are injected into each model:
     *
     * - {Boolean} overdue True if record is prior to now.
     * - {String} picture_url Picture url for model's assigned user.
     */
    _renderHtml: function() {
        var self = this;
        if (this.meta.config) {
            this._super('_renderHtml');
            return;
        }

        var tab = this.tabs[this.settings.get('activeTab')];

        if (tab.overdue_badge) {
            this.overdueBadge = tab.overdue_badge;
        }
        _.each(this.collection.models, function(model){
            var pictureUrl = App.api.buildFileURL({
                module: 'Users',
                id: model.get('assigned_user_id'),
                field: 'picture'
            });
            var ShowCaseUrl = 'pmse_Inbox/' +  model.get('id2') + '/layout/show-case/' +  model.get('flow_id');
            var ShowCaseUrlBwc = App.bwc.buildRoute('pmse_Inbox', '', 'showCase', {id:model.get('flow_id')});
            var SugarModule = model.get('cas_sugar_module');
            if (app.metadata.getModule(SugarModule).isBwcEnabled) {
                model.set('show_case_url', ShowCaseUrlBwc);
            } else {
                model.set('show_case_url', ShowCaseUrl);
            }
            model.set('picture_url', pictureUrl);
            model.set('is_assigned', this.isAssigned(model));
            if (model.attributes.cas_due_date) {
                var useRelativeTime = this._setRelativeTimeAvailable(model.attributes.cas_due_date);
                if (useRelativeTime) {
                    model.useRelativeTime = true;
                } else {
                    model.useAbsoluteTime = true;
                }
            }
            if (model.attributes.is_a_person) {
                model.set('name', app.utils.formatNameModel(model.attributes.cas_sugar_module, model.attributes));
            }
        }, this);
        this._super('_renderHtml');
    },

    isAssigned: function(model) {
        return model.get('cas_assignment_method') != 'selfservice';
    }
})
