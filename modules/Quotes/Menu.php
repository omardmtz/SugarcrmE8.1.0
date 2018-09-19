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

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $sugar_config, $app_strings;

if(ACLController::checkAccess('Quotes', 'edit', true))$module_menu[] =Array("index.php?module=Quotes&action=EditView&return_module=Quotes&return_action=DetailView", $mod_strings['LNK_NEW_QUOTE'],"CreateQuotes");
if(ACLController::checkAccess('Quotes', 'list', true))$module_menu[] =Array("index.php?module=Quotes&action=index&return_module=Quotes&return_action=index", $mod_strings['LNK_QUOTE_LIST'],"Quotes");
if (ACLController::checkAccess('Quotes', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=Reports&action=index&view=quotes',
        $mod_strings['LNK_QUOTE_REPORTS'],
        'QuoteReports',
        'Quotes',
    );
}
