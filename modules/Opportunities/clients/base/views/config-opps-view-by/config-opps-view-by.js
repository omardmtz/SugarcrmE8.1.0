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
 * @class View.Views.Base.OpportunitiesConfigOppsViewByView
 * @alias SUGAR.App.view.views.BaseOpportunitiesConfigOppsViewByView
 * @extends View.Views.Base.ConfigPanelView
 */
({
    extendsFrom: 'ConfigPanelView',

    /**
     * The current opps_view_by config setting when the view is initialized
     */
    currentOppsViewBySetting: undefined,

    /**
     * Are we currently waiting for the field items?
     */
    waitingForFieldItems: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // get the initial opps_view_by setting
        this.currentOppsViewBySetting = this.model.get('opps_view_by');
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:opps_view_by', function() {
            this.showRollupOptions();
        }, this);
    },

    /**
     * Displays the Latest/Earliest Date toggle
     */
    showRollupOptions: function() {
        if (this.currentOppsViewBySetting === 'RevenueLineItems' &&
            this.model.get('opps_view_by') === 'Opportunities') {
            this.getField('opps_closedate_rollup').show();
            this.$('[for=opps_closedate_rollup]').show();
            this.$('#sales-stage-text').show();

            // if there's no value here yet, set to latest
            if (!this.model.has('opps_closedate_rollup')) {
                this.$('input[value="latest"]').prop('checked', true);
            }
        } else {
            this.getField('opps_closedate_rollup').hide();
            this.$('[for=opps_closedate_rollup]').hide();
            this.$('#sales-stage-text').hide();
        }

        // update the title based on settings
        this.updateTitle();
    },

    /**
     * @inheritdoc
     */
    _render: function(options) {
        this._super('_render', [options]);

        this.showRollupOptions();
    },

    /**
     * @inheritdoc
     */
    _updateTitleValues: function() {
        var items = this._getFieldOptions();
        if (items) {
            // defensive coding in case user removed this options dom
            var title = '';
            if (items && _.isObject(items)) {
                title = items[this.model.get('opps_view_by')];
            }

            this.titleSelectedValues = title;
        }
    },

    /**
     * Get the options from the field, vs form the dom, since it's
     * customized to show the correct module names by the end point
     *
     * @return {boolean|Object}
     * @private
     */
    _getFieldOptions: function() {
        var f = this.getField('opps_view_by');

        if (_.isUndefined(f.items)) {
            if (this.waitingForFieldItems === false) {
                this.waitingForFieldItems = true;
                f.once('render', function() {
                    this.waitingForFieldItems = false;
                    this.updateTitle();
                }, this);
            }

            return false;
        } else {
            return f.items;
        }
    }
})
