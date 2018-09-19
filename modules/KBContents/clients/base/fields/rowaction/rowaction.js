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
 * Rowaction is a button that when selected will trigger a Backbone Event.
 *
 * @class View.Fields.KBContents.RowactionField
 * @alias SUGAR.App.view.fields.KBContentsRowactionField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        if ((this.options.def.name === 'create_localization_button' ||
            this.options.def.name === 'create_revision_button') && !app.acl.hasAccessToModel('view', this.model)) {
            this.hide();
        }
    }
})
