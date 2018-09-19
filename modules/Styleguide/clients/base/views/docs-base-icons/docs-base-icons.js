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
    // base icons
    _renderHtml: function () {
        this._super('_renderHtml');

        this.$('.chart-icon').each(function(){
            var svg = svgChartIcon($(this).data('chart-type'));
            $(this).html(svg);
        });

        this.$('.filetype-thumbnail').each(function(){
            $(this).html( '<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" width="28" height="33"><g><path class="ft-ribbon" d="M 0,15 0,29 3,29 3,13 z" /><path d="M 3,1 20.5,1 27,8 27,32 3,32 z" style="fill:#ececec;stroke:#b3b3b3;stroke-width:1;stroke-linecap:butt;" /><path d="m 20,1 0,7 7,0 z" style="fill:#b3b3b3;stroke-width:0" /></g></svg>' );
        });

        this.$('.sugar-cube').each(function(){
            var svg = svgChartIcon('sugar-cube');
            $(this).html(svg);
        });
    }
})
