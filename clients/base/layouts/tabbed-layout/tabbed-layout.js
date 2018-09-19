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
 * @class View.Layouts.Base.TabbedLayoutLayout
 * @alias SUGAR.App.view.layouts.BaseTabbedLayoutLayout
 * @extends View.Layout
 */
({
    initialize: function(options) {
        this.firstIsActive = false;
        this.template = app.template.get("l.tabbed-layout");
        this.renderHtml();

        app.view.Layout.prototype.initialize.call(this, options);
    },

    renderHtml: function() {
        this.$el.html(this.template(this));
    },

    // Assign the tabs
    _placeComponent: function(comp, def) {
        var id = _.uniqueId('record-bottom'),
            nav = $('<li/>').html('<a href="#' + id + '" onclick="return false;" data-toggle="tab">' + app.lang.get(def.layout.name, this.module) + '</a>'),
            content = $('<div/>').addClass('tab-pane').attr('id', id).html(comp.el);

        if (!this.firstIsActive) {
            nav.addClass('active');
            content.addClass('active');
        }

        this.firstIsActive = true;
        this.$('.tab-content').append(content);
        this.$('.nav').append(nav);
    }
})
