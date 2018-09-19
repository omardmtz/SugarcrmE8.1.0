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

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



global $theme;
global $current_user;


// Create the head of the table.
?>
		<table cellpadding="2" cellspacing="0" border="0">
		
<?php
$current_row=1;
$tracker = BeanFactory::newBean('Trackers');
$history = $tracker->get_recently_viewed($current_user->id);

foreach($history as $row)
{
    $moduleImage = SugarThemeRegistry::current()->getImageURL("{$row['module_name']}.gif");
    echo <<<EOQ
        <tr>
          <td vAlign="top"><IMG width="20" alt="{$row['module_name']}" src="{$moduleImage}" border="0"></td>
          <td noWrap><A  href="index.php?module=$row[module_name]&action=DetailView&record=$row[item_id]">$row[item_summary]</A></td>
        </tr>
EOQ;
}
?>
</table>
