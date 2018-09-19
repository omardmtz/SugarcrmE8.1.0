(function($){
    /**
     * Custom sort method for numbers.  Set sSortDataType:"dom-number" to use
     *
     * @param oSettings table settings
     * @param iColumn the column index for all columns, hidden or not
     * @param jColumn the column index for only visible columns
     * @return {Array}
     */
    $.fn.dataTableExt.afnSortData['dom-number'] = function  ( oSettings, iColumn, jColumn )
    {
        var aData = [];
        var sortString;
        //Use JQuery select on the table cell which has a span with an sfuuid attribute
        $('td:eq('+jColumn+') span[sfuuid]', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
            // look for text inside a span of class numberValue,
            // otherwise fall back to full text content
            sortString = $(this).find('.click.format').text();
            if(_.isEmpty(sortString)) {
                sortString = this.textContent;
            }
            aData.push(SUGAR.App.currency.unformatAmount(sortString, SUGAR.App.user.getPreference('number_grouping_separator'), SUGAR.App.user.getPreference('decimal_separator'), false));
        });
        return aData;
    }
})(jQuery);
