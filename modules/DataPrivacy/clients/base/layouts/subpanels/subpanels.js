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
 * @class View.Layouts.Base.DataPrivacy.SubpanelsLayout
 * @alias SUGAR.App.view.layouts.DataPrivacySubpanelsLayout
 * @extends View.Layout.Base.SubpanelsLayout
 */
({
    /**
     * @inheritdoc
     * inject the Mark for Erase action link to all subpanels
     */
    initComponents: function(component, def) {
        this._super('initComponents', arguments);

        // Add the erase action to all subpanel rowactions
        _.each(this._components, function(comp) {
            var subView = comp.getComponent('subpanel-list');
            if (subView && subView.meta && subView.meta.rowactions && subView.meta.rowactions.actions) {
                subView.meta.rowactions.actions.push({
                    'type': 'dataprivacyerase',
                    'icon': 'fa-eye',
                    'name': 'dataprivacy-erase',
                    'label': 'LBL_DATAPRIVACY_MARKFORERASE'
                });
            }
        });
    }
})
