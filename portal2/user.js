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
 * Portal specific user extensions.
 */
(function(app) {
    app.user = _.extend(app.user);

    /**
     * Helper to determine if current user is a support portal user (essentially a Contact with portal enabled);
     * For example, we only show the profile and profile/edit pages if so.
     *
     * @return {Boolean} true if user is of type: support_portal, otherwise false (and user is a "normal user").
     */
    app.user.isSupportPortalUser = function() {
        return this.get('type') === 'support_portal';
    };

})(SUGAR.App);
