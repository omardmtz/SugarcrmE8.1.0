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
    // dashboard intel
    _renderHtml: function () {
        var self = this;
        this._super('_renderHtml');

        this.$('.dashlet-example').on('click.styleguide', function(){
            var dashlet = $(this).data('dashlet'),
                metadata = app.metadata.getView('Home', dashlet).dashlets[0];
            metadata.type = dashlet;
            metadata.component = dashlet;
            self.layout.previewDashlet(metadata);
        });
    },

    _dispose: function() {
        this.$('.dashlet-example').off('click.styleguide');
        this._super('_dispose');
    }
})
