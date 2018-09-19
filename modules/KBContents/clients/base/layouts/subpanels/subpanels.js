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
    extendsFrom: 'SubpanelsLayout',

    /**
     * @inheritdoc
     */
    _pruneNoAccessComponents: function(components) {
        var prunedComponents = [];
        var layoutFromContext = this.context ? this.context.get('layout') || this.context.get('layoutName') : null;
        this.layoutType = layoutFromContext ? layoutFromContext : app.controller.context.get('layout');
        this.aclToCheck = this.aclToCheck || (this.layoutType === 'record') ? 'view' : 'list';
        _.each(components, function(component) {
            var relatedModule,
                link = component.context ? component.context.link : null;
            if (link) {
                relatedModule = app.data.getRelatedModule(this.module, link);
                var aclToCheck = component.acl_action || this.aclToCheck;
                if (!relatedModule || relatedModule && app.acl.hasAccess(aclToCheck, relatedModule)) {
                    prunedComponents.push(component);
                }
            }
        }, this);
        return prunedComponents;
    }
})
