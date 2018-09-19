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
 * @class View.Fields.Base.RowactionsField
 * @alias SUGAR.App.view.fields.BaseRowactionsField
 * @extends View.Fields.Base.ActiondropdownField
 */
({
    extendsFrom: 'ActiondropdownField',

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        //FIXME: SC-3372 Actions should not be based on `this.view.action`

        // check to see if this is a create subpanel
        var isCreate = this.context.get('isCreateSubpanel') || false,
            shouldHide = (this.view.action === 'list' && this.action === 'edit');
        // if this is a create subpanel, trump other logic as rowactions needs to be shown on edit
        if (isCreate || !shouldHide) {
            this.show();
        } else {
            this.hide();
        }
    }
})
