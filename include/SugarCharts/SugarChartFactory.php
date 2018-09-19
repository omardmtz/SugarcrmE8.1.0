<?php
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

/**
 * Chart factory
 * @api
 */
class SugarChartFactory
{
    /**
	 * Returns a reference to the ChartEngine object for instance $chartEngine, or the default
     * instance if one is not specified
     *
     * @param string $chartEngine optional, name of the chart engine from $sugar_config['chartEngine']
     * @param string $module optional, name of module extension for chart engine (see JitReports or SugarFlashReports)
     * @return object ChartEngine instance
     */
    public static function getInstance($chartEngine = '', $module = '')
    {
        global $sugar_config;
        $defaultEngine = "sucrose";
        //fall back to the default Js Engine if config is not defined
        if (empty($sugar_config['chartEngine'])) {
            $sugar_config['chartEngine'] = $defaultEngine;
        }

        if (empty($chartEngine)) {
            $chartEngine = $sugar_config['chartEngine'];
        }

        if (!SugarAutoLoader::requireWithCustom("include/SugarCharts/{$chartEngine}/{$chartEngine}{$module}.php")) {
            $GLOBALS['log']->debug("using default engine include/SugarCharts/{$defaultEngine}/{$defaultEngine}{$module}.php");
            require_once("include/SugarCharts/{$defaultEngine}/{$defaultEngine}{$module}.php");
            $chartEngine = $defaultEngine;
        }

        $className = $chartEngine.$module;
        return new $className();

    }
}
