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
 * @class View.Layouts.Base.Emails.CreateLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsCreateLayout
 * @extends View.Layouts.Base.CreateLayout
 */
({
    extendsFrom: 'CreateLayout',

    /**
     * @inheritdoc
     *
     * Enables the DragdropSelect2:SelectAll shortcut for views that implement
     * it.
     */
    initialize: function(options) {
        this.shortcuts = _.union(this.shortcuts || [], ['DragdropSelect2:SelectAll']);
        this._super('initialize', [options]);
    }
})
