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
 * @class View.Layouts.Base.Emails.ArchiveEmailLayout
 * @alias SUGAR.App.view.layouts.BaseEmailsArchiveEmailLayout
 * @extends View.Layouts.Base.Emails.CreateLayout
 * @deprecated Use {@link View.Layouts.Base.Emails.CreateLayout} instead.
 */
({
    extendsFrom: 'EmailsCreateLayout',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.logger.warn('View.Layouts.Base.Emails.ArchiveEmailLayout is deprecated. ' +
            'Use View.Layouts.Base.Emails.CreateLayout instead.');

        this._super('initialize', [options]);
    }
})
