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
    initialize: function (options) {
        this._super("initialize", [options]);
        this.type = 'rowaction';
    },

    _render: function () {
        var value=this.model.get('prj_status');

        if (value === 'ACTIVE') {
            this.label = App.lang.get("LBL_PMSE_LABEL_DISABLE", "pmse_Project");
        } else {
            this.label = App.lang.get("LBL_PMSE_LABEL_ENABLE", "pmse_Project");
        }

        this._super("_render");
    },

    bindDataChange: function () {
        if (this.model) {
            this.model.on("change", this.render, this);
        }
    }
})
