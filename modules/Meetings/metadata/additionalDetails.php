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

function additionalDetailsMeeting($fields)
{
    static $mod_strings;
    if (empty($mod_strings)) {
        global $current_language;
        $mod_strings = return_module_language($current_language, 'Meetings');
    }

    $overlib_string = '';

    if (!empty($fields['NAME'])) {
        $overlib_string .= '<b>' . $mod_strings['LBL_SUBJECT'] . '</b> ' . $fields['NAME'];
        $overlib_string .= '<br>';
    }
    //Modify by jchi 6/27/2008 1515pm china time , bug 20626.
    if (!empty($fields['DATE_START'])) {
        $overlib_string .= '<b>' . $mod_strings['LBL_DATE_TIME'] . '</b> ' . $fields['DATE_START'] . ' <br>';
    }
    if (isset($fields['DURATION_HOURS']) || isset($fields['DURATION_MINUTES'])) {
        $overlib_string .= '<b>' . $mod_strings['LBL_DURATION'] . '</b> ';
        if (isset($fields['DURATION_HOURS'])) {
            $overlib_string .= $fields['DURATION_HOURS'] . $mod_strings['LBL_HOURS_ABBREV'] . ' ';
        }
        if (isset($fields['DURATION_MINUTES'])) {
            $overlib_string .= $fields['DURATION_MINUTES'] . $mod_strings['LBL_MINSS_ABBREV'];
        }
        $overlib_string .= '<br>';
    }

    if (!empty($fields['PARENT_ID'])) {
        $overlib_string .= "<b>" . $mod_strings['LBL_RELATED_TO'] . "</b> " .
            "<a href=\"javascript:parent.SUGAR.App.router.navigate(" .
            "parent.SUGAR.App.router.buildRoute('" . $fields['PARENT_TYPE'] . "', '" . $fields['PARENT_ID'] . "')" .
            ", {trigger: true});\">" .
            $fields['PARENT_NAME'] . "</a>";
        $overlib_string .= '<br>';
    }

    if (!empty($fields['STATUS'])) {
        $overlib_string .= '<b>' . $mod_strings['LBL_STATUS'] . '</b> ' . $fields['STATUS'];
        $overlib_string .= '<br>';
    }

    if (!empty($fields['DESCRIPTION'])) {
        $overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
        if (strlen($fields['DESCRIPTION']) > 300) {
            $overlib_string .= '...';
        }
        $overlib_string .= '<br>';
    }

    $editLink = "index.php?action=EditView&module=Meetings&record={$fields['ID']}";
    $viewLink = "index.php?action=DetailView&module=Meetings&record={$fields['ID']}";

    return array(
        'fieldToAddTo' => 'NAME',
        'string' => $overlib_string,
        'editLink' => $editLink,
        'viewLink' => $viewLink
    );

}
