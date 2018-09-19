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
 * @class View.Fields.Portal.FileField
 * @alias SUGAR.App.view.fields.PortalFileField
 * @extends View.Fields.Base.FileField
 */
({
    extendsFrom:'FileField',
    /**
     * This is overriden by portal in order to prepend site url
     * @param {String} uri
     * @return {string} formatted uri
     */
    formatUri: function(uri) {
        return app.config.siteUrl + '/' + uri;
    }
})
