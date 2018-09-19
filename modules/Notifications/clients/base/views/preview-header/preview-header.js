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
 * @class View.Views.Base.NotificationsPreviewHeaderView
 * @alias SUGAR.App.view.views.BaseNotificationsPreviewHeaderView
 * @extends View.Views.Base.PreviewHeaderView
 */
({
    extendsFrom: 'PreviewHeaderView',

    /**
     * @inheritdoc
     *
     * @override To make 'previewEdit' always false. Notifications do not allow any editing (but not via module ACL).
     */
    checkACL: function(model) {
        this._super('checkACL', [model]);
        this.layout.previewEdit = false;
    }
})
