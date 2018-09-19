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

class ViewQuickview extends SugarView{
	function display()
	{
	    $focus = BeanFactory::getBean('Notifications', empty($_REQUEST['record']) ? "" : $_REQUEST['record']);

	    if(!empty($focus->id)) {
    	    //Mark as read.
    	    $focus->is_read = true;
    	    $focus->save(FALSE);
	    }

	    $results = array('contents' => $this->_formatNotificationForDisplay($focus) );

	    $json = getJSONobj();
		$out = $json->encode($results);
		ob_clean();
		print($out);
		sugar_cleanup(true);

	}

	function _formatNotificationForDisplay($notification)
    {
        global $app_strings;
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('focus', $notification);
        return $this->ss->fetch("modules/Notifications/tpls/detailView.tpl");
    }
}

