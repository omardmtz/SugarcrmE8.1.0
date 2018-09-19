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

// $Id: CustomizeFields.php 42345 2008-12-04 15:18:11Z jmertic $

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $currentModule;
global $gridline;


echo getClassicModuleTitle('Customize Fields', array('Customize Fields'), false);

?>
<table cellspacing="<?php echo $gridline; ?>" class="other view">
<tr>
<td>
<form>
Module Name:
<select>
<?php
foreach($moduleList as $module)
{
   echo "<option>$module</option>";
}
?>
</select>
<input type="button" value="Edit" />
</form>
</td>
</tr>
</table>

