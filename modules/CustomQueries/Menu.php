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
 ********************************************************************************/

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=Reports&action=index", $mod_strings['LBL_ALL_REPORTS'],"Reports", 'Reports'),
	Array("index.php?module=CustomQueries&action=EditView&return_module=CustomQueries&return_action=DetailView", $mod_strings['LNK_NEW_CUSTOMQUERY'],"CreateCustomQuery"),
	Array("index.php?module=CustomQueries&action=index&return_module=CustomQueries&return_action=DetailView", $mod_strings['LNK_CUSTOMQUERIES'],"CustomQueries"),
	Array("index.php?module=DataSets&action=EditView&return_module=DataSets&return_action=DetailView", $mod_strings['LNK_NEW_DATASET'],"CreateDataSet"),
	Array("index.php?module=DataSets&action=index&return_module=DataSets&return_action=index", $mod_strings['LNK_LIST_DATASET'],"DataSets"),
	Array("index.php?module=ReportMaker&action=EditView&return_module=ReportMaker&return_action=DetailView", $mod_strings['LNK_NEW_REPORTMAKER'],"CreateReport"),
	Array("index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index", $mod_strings['LNK_LIST_REPORTMAKER'],"ReportMaker"),
	);

?>
