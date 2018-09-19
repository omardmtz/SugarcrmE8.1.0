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
if (!is_admin($GLOBALS['current_user'])) {
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}

if (empty($_FILES)) {
	echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_DESC'];
	echo <<<EOQ
<br>
<br>
<form enctype="multipart/form-data" action="index.php" method="POST">
   	<input type='hidden' name='module' value='Administration'>
   	<input type='hidden' name='action' value='ImportCustomFieldStructure'>
   {$mod_strings['LBL_IMPORT_CUSTOM_FIELDS_STRUCT']}: <input name="sugfile" type="file" />
    <input type="submit" value="{$mod_strings['LBL_ICF_IMPORT_S']}" class='button'/>
</form>
EOQ;
} else {
    $fmd = BeanFactory::newBean('EditCustomFields');

    echo $mod_strings['LBL_ICF_DROPPING'] . '<br>';
    $lines = file($_FILES['sugfile']['tmp_name']);
    $cur = array();
    foreach ($lines as $line) {
        if (trim($line) == 'DONE') {
            $fmd->new_with_id = true;
            echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_ADDING'] . ':' . $fmd->custom_module . '-' .
                 $fmd->name . '<br>';
            $fmd->db->query("DELETE FROM $fmd->table_name WHERE id=".$fmd->db->quoted($fmd->id));
            $fmd->save(false);
            $fmd = BeanFactory::newBean('EditCustomFields');
        } else {

            $ln = explode(':::', $line, 2);
            if (sizeof($ln) == 2) {
                $KEY = trim($ln[0]);
                if ($KEY === 'table_name') {
                    continue;
                }
                $fmd->$KEY = trim($ln[1]);
            }
        }
    }
	$_REQUEST['run'] = true;
	$result = $fmd->db->query("SELECT count(*) field_count FROM $fmd->table_name");
	$row = $fmd->db->fetchByAssoc($result);
	echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_COUNT'].' :' . $row['field_count'] . '<br>';
	include('modules/Administration/UpgradeFields.php');
}
