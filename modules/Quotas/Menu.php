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
/*********************************************************************************

 * Description:  
 ********************************************************************************/

global $mod_strings;

$module_menu = Array(
	Array("index.php?module=Forecasts&action=ListView", $mod_strings['LNK_FORECAST_HISTORY'],"Forecasts"),
	Array("index.php?module=Forecasts&action=index&submodule=Worksheet", $mod_strings['LNK_UPD_FORECAST'],"ForecastWorksheet"),
	Array("index.php?module=Quotas&action=index", $mod_strings['LNK_QUOTA'],"ForecastWorksheet")
);

?>
