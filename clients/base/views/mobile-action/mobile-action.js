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
 * @class View.Views.Base.MobileActionView
 * @alias SUGAR.App.view.views.BaseMobileActionView
 * @extends View.View
 */
({
    tagName: 'span',
    events: {
        'click [data-action=mobile]': 'navigateToMobile'
    },
    navigateToMobile: function () {
        if (document.cookie.indexOf('sugar_mobile=') !== -1) {
            // kill sugar_mobile=0 cookie
            document.cookie = 'sugar_mobile=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
        // navigate to the same route of mobile site
        window.location = app.utils.buildUrl('mobile/') + window.location.hash;
    }
})
