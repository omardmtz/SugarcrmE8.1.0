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

function smarty_function_sugar_teamset_badges($params, &$smarty)
{
    if (!isset($params['items'])) {
        $smarty->trigger_error("sugar_teamset_badges: missing 'items' parameter");
        return;
    }

    $badges = array();
    if (!empty($params['items']['primary'])) {
        $badges[] = $GLOBALS['app_strings']['LBL_COLLECTION_PRIMARY'];
    }
    if (!empty($params['items']['selected'])) {
        $badges[] = $GLOBALS['app_strings']['LBL_TEAM_SET_SELECTED'];
    }

    return implode(', ', $badges);
}
