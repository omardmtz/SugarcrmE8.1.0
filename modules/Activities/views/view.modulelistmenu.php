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


class ActivitiesViewModulelistmenu extends ViewModulelistmenu
{
 	public function display()
 	{
 	    $tracker = BeanFactory::newBean('Trackers');
        $history = $tracker->get_recently_viewed($GLOBALS['current_user']->id, array('Calls','Meetings','Tasks','Notes','Emails'));
        foreach ( $history as $key => $row ) {
            $history[$key]['item_summary_short'] = getTrackerSubstring($row['item_summary']);
            $history[$key]['image'] = SugarThemeRegistry::current()
                ->getImage($row['module_name'],'border="0" align="absmiddle"',null,null,'.gif',$row['item_summary']);

        }
        $this->ss->assign('LAST_VIEWED',$history);
 	    
 		$this->ss->display('include/MVC/View/tpls/modulelistmenu.tpl');
 	}
}
