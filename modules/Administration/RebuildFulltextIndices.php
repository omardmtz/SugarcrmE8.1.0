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
global $current_user, $mod_strings;

if(!is_admin($current_user)){
	die($mod_strings['LBL_REBUILD_FULL_TEXT_ABORT']);
}

//find  modules that have a full-text index and rebuild it.
global $beanFiles;
foreach ($beanFiles as $beanname=>$beanpath) {
	require_once($beanpath);
	$focus = BeanFactory::newBeanByName($beanname);

	//skips beans based on same tables. user, employee and group are an example.
	if(empty($focus->table_name) || isset($processed_tables[$focus->table_name])) {
		continue;
	} else {
		$processed_tables[$focus->table_name]=$focus->table_name;
	}

	if(!empty($dictionary[$focus->object_name]['indices'])) {
		$indices=$dictionary[$focus->object_name]['indices'];
	} else {
		$indices=array();
	}

	//clean vardef definitions.. removed indexes not value for this dbtype.
	//set index name as the key.
	$var_indices=array();
	foreach ($indices as $definition) {
		//database helpers do not know how to handle full text indices
		if ($definition['type']=='fulltext') {
			if (isset($definition['db']) and $definition['db'] != $GLOBALS['db']->dbType) {
				continue;
			}

			echo "Rebuilding Index {$definition['name']} <BR/>";
			$GLOBALS['db']->query('alter index ' .$definition['name'] . " REBUILD");
		}

	}
}
?>
