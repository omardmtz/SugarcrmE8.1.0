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
    extendsFrom: 'FilterpanelLayout',

    /**
     * @inheritdoc
     */
    initialize: function(options) {

        this._super('initialize', [options]);

        if (this.context.get('layout') === 'record') {
            var hasSubpanels = false,
                layouts = app.metadata.getModule(options.module, 'layouts');
            if (layouts && layouts.subpanels && layouts.subpanels.meta) {
                hasSubpanels = (layouts.subpanels.meta.components.length > 0);
            }

            if (!hasSubpanels) {
                this.before('render', function() {
                    return false;
                }, this);

                this.template = app.template.empty;
                this.$el.html(this.template());
            }
        }
    }
})
