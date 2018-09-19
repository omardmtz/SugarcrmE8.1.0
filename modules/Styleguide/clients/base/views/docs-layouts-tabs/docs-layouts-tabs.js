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
    // layouts tabs
    _renderHtml: function () {
        this._super('_renderHtml');

        this.$('#nav-tabs-pills')
            .find('ul.nav-tabs > li > a, ul.nav-list > li > a, ul.nav-pills > li > a')
            .on('click.styleguide', function(e){
                e.preventDefault();
                e.stopPropagation();
                $(this).tab('show');
            });
    },

    _dispose: function() {
        this.$('#nav-tabs-pills')
            .find('ul.nav-tabs > li > a, ul.nav-list > li > a, ul.nav-pills > li > a')
            .off('click.styleguide');

        this._super('_dispose');
    }
})
