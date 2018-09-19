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
    // forms range
    _renderHtml: function () {
        this._super('_renderHtml');

        var fieldSettings = {
            view: this,
            def: {
                name: 'include',
                type: 'range',
                view: 'edit',
                sliderType: 'connected',
                minRange: 0,
                maxRange: 100,
                'default': true,
                enabled: true
            },
            viewName: 'edit',
            context: this.context,
            module: this.module,
            model: this.model,
        },
        rangeField = app.view.createField(fieldSettings);

        this.$('#test_slider').append(rangeField.el);

        rangeField.render();

        rangeField.sliderDoneDelegate = function(minField, maxField) {
            return function(value) {
                minField.val(value.min);
                maxField.val(value.max);
            };
        }(this.$('#test_slider_min'), this.$('#test_slider_max'));
    }
})
