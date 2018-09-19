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
 * @class View.Layouts.Base.Emails.ComposeDocumentsLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsComposeDocumentsLayout
 * @extends View.Layout
 * @deprecated Use {@link View.Layouts.Base.SelectionListLayout} instead.
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Layouts.Base.Emails.ComposeDocumentsLayout is deprecated.');

        this._super('initialize', [options]);
    }
})
