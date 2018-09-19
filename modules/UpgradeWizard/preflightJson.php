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

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

if(ob_get_level() < 1)
	ob_start();
ob_implicit_flush(1);

// load the generated persistence file if found
$persistence = array();
if(file_exists($persist = sugar_cached('/modules/UpgradeWizard/_persistence.php'))) {
	require_once $persist;
}
require_once('modules/UpgradeWizard/uw_utils.php');
require_once('include/utils/zip_utils.php');

switch($_REQUEST['preflightStep']) {
	case 'find_upgrade_files':
		logThis('preflightJson finding upgrade files');
		ob_end_flush();
		$persistence['upgrade_files'] = preflightCheckJsonFindUpgradeFiles();
	break;

	case 'diff_upgrade_files':
		logThis('preflightJson diffing upgrade files');
		ob_end_flush();
		$persistence = preflightCheckJsonDiffFiles();
	break;

	case 'get_diff_results':
		logThis('preflightJson getting diff results for display');
		ob_end_flush();
		$persistence = preflightCheckJsonGetDiff();
	break;

	case 'get_diff_errors':
		logThis('preflightJson getting diff errors (if any)');
		preflightCheckJsonGetDiffErrors();
	break;
}

write_array_to_file('persistence', $persistence, $persist);
