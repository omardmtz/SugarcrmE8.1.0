{*
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
*}

{if !$error}
<script type="text/javascript">
	{literal}
	SUGAR.util.doWhen(
		"((SUGAR && SUGAR.mySugar && SUGAR.mySugar.sugarCharts)   || (SUGAR.loadChart && typeof loadSugarChart == 'function')  || document.getElementById('showHideChartButton') != null) && typeof(loadSugarChart) != undefined",
		function(){
			{/literal}
			var css = [];
			var chartConfig = {ldelim}{rdelim};
			var chartParams = {ldelim}{rdelim};
			// chartParams["label"] = "{$label}";
			{foreach from=$config key=name item=value}
				chartConfig["{$name}"] = '{$value}';
			{/foreach}
			{foreach from=$params key=name item=value}
				chartParams["{$name}"] = '{$value}';
			{/foreach}
			{if $height > 480}
				chartConfig["scroll"] = true;
			{/if}
			chartConfig["reportView"] = true;
			chartConfig["direction"] = $('html', window.parent.document).hasClass('rtl') ? 'rtl' : 'ltr';
			loadCustomChartForReports = function(){ldelim}
				loadSugarChart('{$chartId}', '{$filename}', css, chartConfig, chartParams);
			{rdelim};
			// bug51857: fixed issue on report running in a loop when clicking on hide chart then run report in IE8 only
			// When hide chart button is clicked, the value of element showHideChartButton is set to $showchart.
			// Don't need to call the loadCustomChartForReports() function when hiding the chart.
			{if !isset($showchart)}
				loadCustomChartForReports();
			{else}
			     if ($('#showHideChartButton').attr('value') != '{$showchart}')
			        loadCustomChartForReports();
			{/if}
			{literal}
		}
	);
	{/literal}
</script>
<div class="chartContainer">
	<div id="sb{$chartId}" class="scrollBars">
    	<div id="d3_{$chartId}" class="sc-chart sc-{$config.chartType}" style="width: 100%; height: {$height}px;"></div>
    </div>
    <div id="d3_{$chartId}_print" class="sc-chart sc-print sc-{$config.chartType}" style="width: 720px; height: 480px;"></div>
    <canvas id="canvas_{$chartId}" width="1440" height="960" class="sc-image-canvas"></canvas>
</div>
<div class="clear"></div>
{else}

{$error}
{/if}
