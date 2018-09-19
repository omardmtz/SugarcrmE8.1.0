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
 * @class View.Layouts.Base.Emails.ComposeEmailLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsComposeEmailLayout
 * @extends View.Layouts.Base.Emails.CreateLayout
 */
({
    extendsFrom: 'EmailsCreateLayout',

    /**
     * @inheritdoc
     *
     * Enables the Compose:Send shortcut for views that implement it.
     */
    initialize: function(options) {
        this.shortcuts = _.union(this.shortcuts || [], ['Compose:Send']);
        this._super('initialize', [options]);
    }
})
