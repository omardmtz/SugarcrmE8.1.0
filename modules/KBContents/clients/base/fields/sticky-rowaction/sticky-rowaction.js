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
 * @class View.Fields.Base.KBContents.StickyRowactionField
 * @alias SUGAR.App.view.fields.BaseKBContentsStickyRowactionField
 * @extends View.Fields.Base.StickyRowactionField
 */
({
    extendsFrom: 'StickyRowactionField',

    /**
     * Disable field if it has no access to edit.
     * @inheritdoc
     */
    isDisabled: function() {
        var parentLayout = this.context.parent.get('layout');
        var parentModel = this.context.parent.get('model');

        if (
            this.def.name === 'create_button' &&
            parentLayout === 'record' &&
            !app.acl.hasAccessToModel('edit', parentModel)
        ) {
            return true;
        }
        return this._super('isDisabled');
    }

})
