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
 * @class View.Layouts.Base.ASDashletFilterLayout
 * @alias SUGAR.App.view.layouts.BaseASDashletFilterLayout
 * @extends View.Layout
 */
({
    className: 'dashablelist-filter',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        //Set up a listener for the configuration save
        this.listenTo(this.layout, 'asdashlet:config:save', this.saveFilterToDashlet);
    },

    /**
     * Set the current filter ID and def to be seen on the dashlet
     *
     * @private
     */
    saveFilterToDashlet: function() {
        var filterPanelLayout = this.getComponent('filterpanel');
        if (!filterPanelLayout) {
            return;
        }

        this.model.set('currentFilterId', filterPanelLayout.context.get('currentFilterId'));
    }
})
