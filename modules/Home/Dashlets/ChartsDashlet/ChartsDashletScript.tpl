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

<script>
    SUGAR.util.doWhen(
		"SUGAR && SUGAR.mySugar && SUGAR.mySugar.sugarCharts",
		function(){ldelim}
			SUGAR.mySugar.sugarCharts.addToChartsArray('{$chartName}', '{$chartXMLFile}', '100%', '480', '{$chartStyleCSS}', '{$chartColorsXML}', '{$chartLangFile}');
		{rdelim}
	);
</script>
