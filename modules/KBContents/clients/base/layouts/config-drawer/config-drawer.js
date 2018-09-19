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
 * @class View.Layouts.Base.KBContentsConfigDrawerLayout
 * @alias SUGAR.App.view.layouts.BaseKBContentsConfigDrawerLayout
 * @extends View.Layouts.Base.ConfigDrawerLayout
 */
({

    extendsFrom: 'ConfigDrawerLayout',

    /**
     * @inheritdoc
     */
    _checkModuleAccess: function() {
        var acls = app.user.getAcls().KBContents,
            isSysAdmin = (app.user.get('type') == 'admin'),
            isAdmin = (!_.has(acls, 'admin'));
        isDev = (!_.has(acls, 'developer'));
        return (isSysAdmin || isAdmin || isDev);
    }
})
