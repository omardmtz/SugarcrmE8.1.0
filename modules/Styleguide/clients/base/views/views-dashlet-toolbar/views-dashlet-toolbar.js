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

    initialize: function(options) {
        this._super('initialize', [options]);
        this.request = this.context.get('request');
    },

    _render: function() {
        this._super('_render');

        this.example = app.view.createView({
                context: this.context,
                type: 'dashlet-toolbar',
                module: 'Base',
                layout: this.layout,
                model: this.layout.model,
                readonly: true,
                meta: {
                    label: 'Example dashlet title'
                }
            });

        // override view function that relies on the dashlet layout
        this.example.toggleMinify = function(evt) {
            var $el = this.$('.dashlet-toggle > i'),
                collapsed = $el.is('.fa-chevron-up');
            this.$(".dashlet-toggle > i").toggleClass("fa-chevron-down", collapsed);
            this.$(".dashlet-toggle > i").toggleClass("fa-chevron-up", !collapsed);
        };

        this.$('#example_view').append(this.example.el);
        this.example.render();
    }
})
