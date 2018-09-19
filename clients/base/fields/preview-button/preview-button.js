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
    extendsFrom: 'RowactionField',

    /**
     * True if the preview button is for a BWC module, false if not
     * @property {boolean}
     */
    isBwcEnabled: undefined,

    /**
     * Holds the proper tooltip label key
     */
    tooltip: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var fieldModule = options.model.get('_module');
        this.isBwcEnabled = app.metadata.getModule(fieldModule).isBwcEnabled;

        this._super('initialize', [options]);

        if(this.isBwcEnabled) {
            this.tooltip = 'LBL_PREVIEW_BWC_TOOLTIP';
        } else {
            this.tooltip = this.def.tooltip;
        }
    }
})
