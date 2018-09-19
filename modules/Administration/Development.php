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

// $Id: Development.php 42345 2008-12-04 15:18:11Z jmertic $

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $currentModule;
global $gridline;

echo getClassicModuleTitle('MigrateFields', array($mod_strings['LBL_EXTERNAL_DEV_TITLE']), true);

?>
<p>
<table cellspacing="<?php echo $gridline;?>" class="other view">
<tr>
	<td scope="row"><?php echo SugarThemeRegistry::current()->getImage('ImportCustomFields','align="absmiddle" border="0"',null,null,'.gif',$mod_strings['LBL_IMPORT_CUSTOM_FIELDS_TITLE']); ?>&nbsp;<a href="./index.php?module=Administration&action=ImportCustomFieldStructure" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS_TITLE']; ?></a></td>
	<td> <?php echo $mod_strings['LBL_IMPORT_CUSTOM_FIELDS'] ; ?> </td>
</tr>
<tr>
	<td scope="row"><?php echo SugarThemeRegistry::current()->getImage('ExportCustomFields','align="absmiddle" border="0"',null,null,'.gif',$mod_strings['LBL_EXPORT_CUSTOM_FIELDS_TITLE']); ?>&nbsp;<a href="./index.php?module=Administration&action=ExportCustomFieldStructure" class="tabDetailViewDL2Link"><?php echo $mod_strings['LBL_EXPORT_CUSTOM_FIELDS_TITLE']; ?></a></td>
	<td> <?php echo $mod_strings['LBL_EXPORT_CUSTOM_FIELDS'] ; ?> </td>
</tr>

</table></p>


