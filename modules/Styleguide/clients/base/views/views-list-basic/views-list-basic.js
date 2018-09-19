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

        this.layout.model.set({
            full_name: 'Cpt. James Kirk',
            title: 'SC937-0176 CEC',
            do_not_call: 1,
            email: 'kirkjt@starfleet.gov',
            assigned_user_name: 'Administrator',
            list_price: 123.45,
            birthdate: '03/22/2233',
            date_end: '06/15/2319 7:50:17PM'
        });
        this.example = app.view.createView({
            context: this.context,
            type: 'list',
            module: 'Styleguide',
            layout: this.layout,
            model: this.layout.model,
            readonly: true
        });

        this.example.collection.add(this.layout.model);

        this.example._render();

        this.$('#example_view').append(this.example.el);
    }
})
