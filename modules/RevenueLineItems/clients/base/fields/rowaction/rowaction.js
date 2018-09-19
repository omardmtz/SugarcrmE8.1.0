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
    extendsFrom: "RowactionField",
    
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.clone(this.plugins) || [];

        if (!options.context.get('isCreateSubpanel')) {
            // if this is not a create subpanel, add the DisableDelete plugin
            // on a create subpanel, don't add the plugin so users can delete rows
            this.plugins.push('DisableDelete');
        }

        this._super("initialize", [options]);
    }
})
