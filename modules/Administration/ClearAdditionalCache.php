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

// $Id: clear_chart_cache.php 45763 2009-04-01 19:16:18Z majed $

global $sugar_config, $mod_strings;

print( $mod_strings['LBL_CLEAR_ADDITIONAL_CACHE_FINDING'] . "<br>" );


print( $mod_strings['LBL_CLEAR_ADDITIONAL_CACHE_DELETING'] . "<br>" );

$repair = new RepairAndClear();
$repair->show_output = false;
$repair->module_list = array();
$repair->clearAdditionalCaches();

echo "\n--- " . $mod_strings['LBL_DONE'] . "---<br />\n";
?>

