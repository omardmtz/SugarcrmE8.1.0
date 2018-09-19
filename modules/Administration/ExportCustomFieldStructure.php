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

$db = DBManagerFactory::getInstance();
$result = $db->query('SELECT * FROM fields_meta_data WHERE deleted = 0');
$fields = array();
$str = '';
while($row = $db->fetchByAssoc($result)){
	foreach($row as $name=>$value){
		$str.= "$name:::$value\n";
	}
	$str .= "DONE\n";
}
ob_get_clean();

header("Content-Disposition: attachment; filename=CustomFieldStruct.sugar");
header("Content-Type: text/txt; charset={$app_strings['LBL_CHARSET']}");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . TimeDate::httpTime() );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($str));
echo $str;
?>