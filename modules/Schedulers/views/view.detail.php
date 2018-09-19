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


class SchedulersViewDetail extends ViewDetail {

    /**
     * {@inheritDoc}
     *
     * @param bool $browserTitle Ignored
	 */
    protected function _getModuleTitleListParam($browserTitle = false)
	{
	    global $mod_strings;

    	return "<a href='index.php?module=Schedulers&action=index'>".$mod_strings['LBL_MODULE_TITLE']."</a>";
    }

    /**
 	 * display
 	 */
 	function display()
 	{
		$this->bean->parseInterval();
		$this->bean->setIntervalHumanReadable();
		$this->ss->assign('JOB_INTERVAL', $this->bean->intervalHumanReadable);
 		parent::display();
 	}
}

