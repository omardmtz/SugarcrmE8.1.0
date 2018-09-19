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

// $Id: customMySugarCharts.js 2010-12-01 23:11:36Z lhuynh $

/**
 * This chart engine is now deprecated. Use the sucrose chart engine instead.
 * @deprecated This file will removed in a future release.
 */
initmySugarCharts = function(){

SUGAR.mySugar.sugarCharts = function() {
    (app || SUGAR.App).logger.warn('The Jit chart engine is deprecated.');

var activeTab = activePage,
    charts = new Object();

	return {
		loadSugarCharts: function(activeTab) {
			var chartFound = false;

			for (id in charts[activeTab]){
				if(id != 'undefined'){
					chartFound = true;
					loadSugarChart(
											 charts[activeTab][id]['chartId'],
											 charts[activeTab][id]['jsonFilename'],
											 charts[activeTab][id]['css'],
											 charts[activeTab][id]['chartConfig']
											 );
				}
			}
			//clear charts array
			charts = new Object();

		},

		addToChartsArrayJson: function(json,activeTab) {
			for (id in json) {
					if(json[id]['supported'] == "true") {
						SUGAR.mySugar.sugarCharts.addToChartsArray(
												 json[id]['chartId'],
 												 json[id]['filename'],
												 json[id]['css'],
												 json[id]['chartConfig'],
												 activeTab);
					}
				}
		},
		addToChartsArray: function(chartId,jsonFilename,css,chartConfig,activeTab) {

			if (charts[activeTab] == null){
				charts[activeTab] = new Object();
			}
			charts[activeTab][chartId] = new Object();
			charts[activeTab][chartId]['chartId'] = chartId;
			charts[activeTab][chartId]['jsonFilename'] = jsonFilename;
			charts[activeTab][chartId]['css'] = css;
			charts[activeTab][chartId]['chartConfig'] = chartConfig;

		}
	}
}();
};
