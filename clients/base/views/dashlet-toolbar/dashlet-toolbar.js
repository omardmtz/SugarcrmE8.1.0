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
 * @class View.Views.Base.DashletToolbarView
 * @alias SUGAR.App.view.views.BaseDashletToolbarView
 * @extends View.View
 */
({
    className: 'dashlet-header',
    cssIconDefault: 'fa-cog',
    cssIconRefresh: 'fa-refresh fa-spin',
    defaultActions: {
        'dashlet:edit:clicked' : 'editClicked',
        'dashlet:viewReport:clicked': 'viewReportClicked',
        'dashlet:refresh:clicked' : 'refreshClicked',
        'dashlet:delete:clicked' : 'removeClicked',
        'dashlet:toggle:clicked' : 'toggleMinify'
    },
    events: {
        'shown.bs.dropdown': '_toggleAria',
        'hidden.bs.dropdown': '_toggleAria'
    },

    initialize: function(options) {
        _.extend(options.meta, app.metadata.getView(null, 'dashlet-toolbar'), options.meta.toolbar);
        app.view.View.prototype.initialize.call(this, options);
        var model = this.closestComponent('dashboard') ?
            this.closestComponent('dashboard').model : this.model;

        /**
         * A flag to indicate if the dashlet is editable.
         *
         * @type {boolean}
         */
        this.canEdit = app.acl.hasAccessToModel('edit', model) || false;
    },

    /**
     * Change to the spinning icon to indicate that loading process is triggered
     */
    refreshClicked: function() {
        var $el = this.$('[data-action=loading]');
        var self = this;
        var options = {};
        if ($el.length > 0) {
            $el.removeClass(this.cssIconDefault).addClass(this.cssIconRefresh);
            options.complete = function() {
                if (self.disposed) {
                    return;
                }
                $el.removeClass(self.cssIconRefresh).addClass(self.cssIconDefault);
            };
        }
        this.layout.reloadDashlet(options);
    },

    /**
     * Remove a dashlet.
     *
     * @param {Event} evt Mouse event.
     */
    removeClicked: function(evt) {
        app.alert.show('delete_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_REMOVE_DASHLET_CONFIRM', this.module),
            onConfirm: _.bind(function() {
                this.layout.removeDashlet();
            }, this)
        });
    },

    /**
     * View report.
     *
     * @param {Event} evt Mouse event.
     */
    viewReportClicked: function(evt) {
        this.layout.viewReport();
    },

    editClicked: function(evt) {
        this.layout.editDashlet();
    },
    /**
     * Toggle current dashlet frame when user clicks the toolbar action
     *
     * @param {Event} mouse event.
     */
    toggleClicked: function(evt) {
        var $btn = $(evt.currentTarget);
        var expanded = _.isUndefined($btn.data('expanded')) ? true : $btn.data('expanded');
        var label = expanded ? 'LBL_DASHLET_MAXIMIZE' : 'LBL_DASHLET_MINIMIZE';

        $btn.html(app.lang.get(label, this.module));
        this.layout.collapse(expanded);
        $btn.data('expanded', !expanded);
    },

    /**
     * Toggle current dashlet frame when user clicks chevron icon
     *
     * @param {Window.Event} mouse event.
     */
    toggleMinify: function(evt) {
        var $el = this.$('.dashlet-toggle > i');
        var collapsed = $el.is('.fa-chevron-up');
        this.layout.collapse(collapsed);
        //firing an event to notify dashlet expand / collapse
        this.layout.trigger('dashlet:collapse', collapsed);
    },

    /**
     * Sets a button accessibility class 'aria-expanded' to true or false
     * depending on if the dropdown menu is open or closed.
     *
     * @private
     */
    _toggleAria: function() {
        var $button = this.$('[data-toggle=dropdown]');
        var $group = $button.parent();
        $button.attr('aria-expanded', $group.hasClass('open'));
    }

})
