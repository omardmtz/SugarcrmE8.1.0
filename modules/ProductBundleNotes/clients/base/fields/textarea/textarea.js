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
 * @class View.Fields.Base.ProductBundleNotes.TextareaField
 * @alias SUGAR.App.view.fields.BaseProductBundleNotesTextareaField
 * @extends View.Fields.Base.BaseTextareaField
 */
({
    extendsFrom: 'BaseTextareaField',

    /**
     * Having to override because we do want it to go to edit in the list
     * contrary to everywhere else in the app
     *
     * @inheritdoc
     */
    setMode: function(name) {
        // skip textarea's setMode and call straight to Field.setMode
        app.view.Field.prototype.setMode.call(this, name);
    }
});
