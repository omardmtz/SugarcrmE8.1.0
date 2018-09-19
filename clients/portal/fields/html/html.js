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
 * @class View.Fields.Portal.HtmlField
 * @alias SUGAR.App.view.fields.PortalHtmlField
 * @extends View.Fields.Base.HtmlField
 */
({
    extendsFrom:'HtmlField',

    /**
     * This is overridden by portal in order to prepend site url to src attributes of img tag
     * @param {String} value
     * @return {string} formatted value
     */
    format: function(value) {
        return value ?
            value.replace(/(src=")(?!http:\/\/|https:\/\/)(.*?)"/g, '$1' + app.config.siteUrl + '/$2"') :
            value;
    }
})
