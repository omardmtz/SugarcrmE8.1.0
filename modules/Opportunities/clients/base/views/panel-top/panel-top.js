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
* @class View.Fields.Base.Opportunities.PanelTopField
* @alias App.view.fields.BaseOpportunitiesPanelTopField
* @extends View.Fields.Base.PanelTopField
*/
({
    extendsFrom: "PanelTopView",

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.on('linked-model:create', this._reloadRevenueLineItems, this);
    },

    /**
     * Refreshes the RevenueLineItems subpanel when a new Opportunity is added
     * @private
     */
    _reloadRevenueLineItems: function() {
        if (app.metadata.getModule('Opportunities', 'config').opps_view_by == 'RevenueLineItems') {
            var $rliSubpanel = $('div[data-subpanel-link="revenuelineitems"]');
            // only reload RLI subpanel if it is opened
            if (!$('li.subpanel', $rliSubpanel).hasClass('closed')) {
                this.context.parent.trigger('subpanel:reload', {links: ['revenuelineitems']});
            } else {
                // RLI Panel is closed, filter components to find the RLI panel and update count
                var rliComponent = _.find(this.layout.layout._components, function(component) {
                    return component.module === 'RevenueLineItems';
                });

                var cc_field = rliComponent.getComponent('panel-top').getField('collection-count');

                app.api.count('Accounts', {
                    id: this.context.parent.get('modelId'),
                    link:'revenuelineitems'
                }, {
                    success: function(data) {
                        cc_field.updateCount({ length: data.record_count });
                    }
                });
            }
        }
    }
})
