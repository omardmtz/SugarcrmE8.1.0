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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class ViewRepair extends SugarView
{
    /**
	 * @see SugarView::display()
	 */
	public function display()
	{
	    // To prevent lag in the rendering of the page after clicking the quick repair link...
        echo "<h2>{$GLOBALS['mod_strings']['LBL_BEGIN_QUICK_REPAIR_AND_REBUILD']}</h2>";
        ob_flush();
        $randc = new RepairAndClear();
        $actions = array();
        $actions[] = 'clearAll';
        $randc->repairAndClearAll($actions, array(translate('LBL_ALL_MODULES')), false,true,'');
        
        echo <<<EOHTML
<br /><br /><a href="index.php?module=Administration&action=index">{$GLOBALS['mod_strings']['LBL_DIAGNOSTIC_DELETE_RETURN']}</a>
EOHTML;
	}
}
