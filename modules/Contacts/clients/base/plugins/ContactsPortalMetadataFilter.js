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
(function(app) {
    // FIXME TY-106: we need to decide if we can deprecate this
    app.events.on('app:init', function() {

        /**
         * ListEditable plugin is for fields that use a list-edit template instead of the standard edit
         * during inline editing on list views.
         */
        app.plugins.register('ContactsPortalMetadataFilter', ['view'], {
            /**
             * Check if portal is active. If not, will remove the portal fields from the metadata
             * @param {Object} meta metadata to filter.
             */
            removePortalFieldsIfPortalNotActive: function(meta) {
                if (!_.isObject(meta)) {
                    return;
                }
                // Portal specific fields to hide if portal is disabled
                var portalFields = ['portal_name', 'portal_active', 'portal_password'];
                var serverInfo = app.metadata.getServerInfo();
                if (!serverInfo.portal_active) {
                    _.each(meta.panels, function(panel) {
                        panel.fields = _.reject(panel.fields, function(field) {
                            var name = _.isObject(field) ? field.name : field;
                            return _.contains(portalFields, name);
                        });
                    });
                }
            }
        });
    });
})(SUGAR.App);
