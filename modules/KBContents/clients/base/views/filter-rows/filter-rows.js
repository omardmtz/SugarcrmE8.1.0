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
    extendsFrom: 'FilterRowsView',

    /**
     * @inheritdoc
     *
     * Add 'kbdocument_body' filter field only on KBContents listView. This field is not present in filter's
     * metadata to avoid its appearance on KBContents subpanels - this is done due to technical inability to make
     * REST calls to specific module's RelateApi and thus to perform KB specific filtering logic.
     */
    loadFilterFields: function(module) {
        this._super('loadFilterFields', [module]);
        if (this.context.get('layout') === 'records') {
            var bodyField = this.model.fields['kbdocument_body'];
            this.fieldList[bodyField.name] = bodyField;
            this.filterFields[bodyField.name] = app.lang.get(bodyField.vname, this.module);
        }
    }
})
