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
 * @class View.Layouts.Base.Emails.RecordsLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsRecordsLayout
 * @extends View.Layouts.Base.RecordsLayout
 */
({
    extendsFrom: 'RecordsLayout',

    /**
     * @inheritdoc
     *
     * Remove shortcuts that do not apply to Emails module list view
     */
    initialize: function(options) {
        this.shortcuts = _.without(
            this.shortcuts,
            'List:Favorite',
            'List:Follow'
        );

        this._super('initialize', [options]);
    }
})
