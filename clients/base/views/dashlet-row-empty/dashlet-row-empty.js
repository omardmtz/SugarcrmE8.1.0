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
 * @class View.Views.Base.DashletRowEmptyView
 * @alias SUGAR.App.view.views.BaseDashletRowEmptyView
 * @extends View.View
 */
({
    events: {
        'click .add-dashlet' : 'layoutClicked',
        'click .add-row.empty' : 'addClicked'
    },
    originalTemplate: null,
    columnOptions: [],
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.model = this.layout.context.get("model");

        this.model.on("setMode", this.setMode, this);
        this.originalTemplate = this.template;
        this.setMode(this.model.mode);
        this.columnOptions = [];

        var parentLayoutWidth = 12,
            parentLayout = this;
        while (parentLayout) {
            if (parentLayout.type === 'dashlet-row') {
                parentLayoutWidth = parentLayout.meta.width;
            }
            parentLayout = parentLayout.layout;
        }
        var allowColumnSize = _.max([
            1, //should be at least one
            Math.floor(parentLayoutWidth / this.model.minColumnSpanSize)
        ]);
        _.times(allowColumnSize, function(index) {
            var n = index + 1;
            this.columnOptions.push({
                index: n,
                label: (n > 1) ?
                    app.lang.get('LBL_DASHBOARD_ADD_' + n + '_COLUMNS', this.module) :
                    app.lang.get('LBL_DASHBOARD_ADD_' + n + '_COLUMN', this.module)
            });
        }, this);
    },
    addClicked: function(evt) {
        var self = this;
        this._addRowTimer = setTimeout(function() {
            self.addRow(1);
        }, 100);
    },
    layoutClicked: function(evt) {
        var columns = $(evt.currentTarget).data('value');
        var addRow = _.bind(this.addRow, this);
        _.delay(addRow, 0, columns);
    },
    addRow: function(columns) {
        this.layout.addRow(columns);
        if(this._addRowTimer) {
            clearTimeout(this._addRowTimer);
        }
    },
    setMode: function(model) {
        if(model === 'edit') {
            this.template = this.originalTemplate;
        } else {
            this.template = app.template.empty;
        }
        this.render();
    },
    _dispose: function() {
        this.model.off("setMode", null, this);
        app.view.View.prototype._dispose.call(this);
    }
})
