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
 * @class View.Layouts.Base.DashablelistFilterLayout
 * @alias SUGAR.App.view.layouts.BaseDashablelistFilterLayout
 * @extends View.Layout
 */
({
    className: 'dashablelist-filter',

    /**
     * @inheritdoc
     */
    initComponents: function(components, context, module) {
        this._super('initComponents', [components, context, module]);

        // We need to initialize the filterpanel with the filter and module
        // saved on the dashlet.
        var filterPanelLayout = this.getComponent('filterpanel');
        if (filterPanelLayout) {
            filterPanelLayout.before('render', this._reinitializeFilterPanel, this);
            this.listenTo(this.layout, 'dashlet:filter:reinitialize', filterPanelLayout.render);
        }
    },

    /**
     * This function sets the `currentModule` on the filterpanel layout, and
     * the `currentFilterId` on its context. It is invoked before
     * `filter:reinitialize` is triggered from `_render` on the filterpanel
     * layout.
     *
     * @private
     */
    _reinitializeFilterPanel: function() {
        var filterPanelLayout = this.getComponent('filterpanel');
        if (!filterPanelLayout) {
            return;
        }

        var moduleName = this.model.get('module'),
            id = this.model.get('filter_id');

        filterPanelLayout.currentModule = moduleName;
        this.context.set('currentFilterId', id);
    }
})
