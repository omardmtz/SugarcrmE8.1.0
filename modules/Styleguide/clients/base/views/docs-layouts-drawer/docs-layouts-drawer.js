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
    // layouts drawer
    _renderHtml: function () {
        this._super('_renderHtml');

        this.$('#sg_open_drawer').on('click.styleguide', function(){
            app.drawer.open({
                layout: 'create',
                context: {
                    create: true,
                    model: app.data.createBean('Styleguide')
                }
            });
        });
    },

    _dispose: function() {
        this.$('#sg_open_drawer').off('click.styleguide');

        this._super('_dispose');
    }
})
