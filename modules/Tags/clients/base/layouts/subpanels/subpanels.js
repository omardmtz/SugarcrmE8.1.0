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
 * @class View.Layouts.Base.Tags.SubpanelsLayout
 * @alias SUGAR.App.view.layouts.TagsSubpanelsLayout
 * @extends View.Layout.Base.SubpanelsLayout
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // Create dynamic subpanel metadata
        var dSubpanels = app.utils.getDynamicSubpanelMetadata(options.module);

        if (dSubpanels.components) {
            _.each(dSubpanels.components, function(sub) {
                if (sub.layout) {
                    sub['override_paneltop_view'] = 'panel-top-readonly';
                }
            }, this);
        }

        // Merge dynamic subpanels with existing metadata
        options.meta = _.extend(
            options.meta || {},
            dSubpanels
        );

        // Call the parent
        this._super('initialize', [options]);
    }
})
