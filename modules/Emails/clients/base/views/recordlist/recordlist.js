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
 * @class View.Views.Base.Emails.RecordlistView
 * @alias SUGAR.App.view.views.BaseEmailsRecordlistView
 * @extends View.Views.Base.RecordlistView
 */
({
    extendsFrom: 'RecordlistView',

    /**
     * @inheritdoc
     * When record name is empty, return (no subject)
     */
    _getNameForMessage: function(model) {
        var name = this._super('_getNameForMessage', [model]);

        if (_.isEmpty(name)) {
            return app.lang.get('LBL_NO_SUBJECT', this.module);
        }

        return name;
    }
})
