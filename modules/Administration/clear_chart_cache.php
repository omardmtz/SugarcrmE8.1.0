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

print( $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_FINDING'] . "<br>" );

$search_dir=sugar_cached("");
$all_src_files  = findAllFiles($search_dir.'/xml', array() );

print( $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_DELETING1'] . "<br>" );
foreach( $all_src_files as $src_file ){
	if (preg_match('/\.xml$/',$src_file))
	{
   		print( $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_DELETING2'] . " $src_file<BR>" ) ;
		unlink( "$src_file" );
	}
}

echo "\n--- " . $mod_strings['LBL_DONE'] . "---<br />\n";
