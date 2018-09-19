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
 * @class View.Views.Base.Home.AboutSourceCodeView
 * @alias SUGAR.App.view.views.BaseHomeAboutSourceCodeView
 * @extends View.View
 */
({
    /**
     * The server info object. See {@link Core.MetadataManager#getServerInfo}.
     *
     * @property {String}
     */
    serverInfo: null,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.serverInfo = app.metadata.getServerInfo();
    }
})
