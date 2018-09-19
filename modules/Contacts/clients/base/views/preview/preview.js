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
({
    extendsFrom: 'PreviewView',


    initialize: function(options) {
        //Plugin is registered by the Contact record view
        this.plugins = _.union(this.plugins || [], ["ContactsPortalMetadataFilter"]);
        this._super("initialize", [options]);
    },

    /**
     * Gets the portal status from metadata to know if we render portal specific fields.
     * @override
     * @param options
     */
    _previewifyMetadata: function(meta) {
        meta = this._super("_previewifyMetadata", [meta]);
        this.removePortalFieldsIfPortalNotActive(meta);
        return meta;
    }
})
