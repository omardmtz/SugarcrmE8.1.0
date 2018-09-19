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

 ********************************************************************************/

global $mod_strings;

if ($current_user->is_admin)
{
    $lc = new ListCurrency();
    $lc->handleDelete();
    $lc->handleAdd();
    $lc->handleUpdate();
    echo $lc->getTable();
}
else
{
    echo $mod_strings['LBL_ADMIN_ONLY'];
}

?>
