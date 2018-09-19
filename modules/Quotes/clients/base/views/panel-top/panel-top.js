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
    extendsFrom: 'PanelTopView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['MassQuote']);
        this._super('initialize', [options]);
    },

    /**
     * Overriding to create a Quote from a Subpanel using the Quotes create view not a drawer
     *
     * @inheritdoc
     */
    createRelatedClicked: function(event) {
        var massCollection = this.context.get('mass_collection');
        var module = this.context.parent.get('module');
        if (!massCollection) {
            massCollection = this.context.get('collection').clone();
            if (!_.contains(['Accounts', 'Opportunities', 'Contacts'], module)) {
                massCollection.fromSubpanel = true;
            }
            this.context.set('mass_collection', massCollection);
        }
        this.layout.trigger('list:massquote:fire');
    }
})
