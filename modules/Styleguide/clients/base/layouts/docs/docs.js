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
    plugins: ['Prettify'],
    extendsFrom: 'StyleguideStyleguideLayout',

    /**
     * @inheritdoc
     */
    initComponents: function(components, context, module) {
        var def;
        var main;
        var content;
        var request = this.context.get('request');

        this._super('initComponents', [components, context, module]);

        def = {
            view: {
                type: request.keys[0] + '-' + request.view,
                name: request.keys[0] + '-' + request.view,
                meta: request.page_details
            }
        };

        main = this.getComponent('sidebar').getComponent('main-pane');
        content = this.createComponentFromDef(def, this.context, this.module);
        main.addComponent(content);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var defaultLayout = this.getComponent('sidebar');
        if (defaultLayout) {
            defaultLayout.trigger('sidebar:toggle', false);
        }

        this._super('_render');
    }
})
