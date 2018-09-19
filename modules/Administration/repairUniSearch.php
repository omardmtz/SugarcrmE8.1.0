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

// $Id: clear_chart_cache.php 27404 2007-09-29 00:51:13Z Ben Soufflet $

global $sugar_config, $mod_strings;

$search_dir=sugar_cached('');

$src_file = $search_dir . 'modules/unified_search_modules.php';
if(file_exists($src_file)) {
    print( $mod_strings['LBL_CLEAR_UNIFIED_SEARCH_CACHE_DELETING1'] . "<br>" );
    print( $mod_strings['LBL_CLEAR_UNIFIED_SEARCH_CACHE_DELETING2'] . " $src_file<BR>" ) ;
    unlink( "$src_file" );
}

echo "\n--- " . $mod_strings['LBL_DONE'] . "---<br />\n";
?>
