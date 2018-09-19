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

class ViewSystemQuicklist extends ViewQuickList{
	function display()
	{
		$GLOBALS['system_notification_buffer'] = array();
		$GLOBALS['buffer_system_notifications'] = true;
		$GLOBALS['system_notification_count'] = 0;
		$sv = new SugarView();
		$sv->includeClassicFile('modules/Administration/DisplayWarnings.php');
	    
		echo $this->_formatNotificationsForQuickDisplay($GLOBALS['system_notification_buffer'], "modules/Notifications/tpls/systemQuickView.tpl");

        $this->clearFTSFlags();
	}
    /**
     * After the notification is displayed, clear the fts flags
     * @return null
     */
    protected function clearFTSFlags() {
        if (is_admin($GLOBALS['current_user']))
        {
            $admin = Administration::getSettings();
            if (!empty($settings->settings['info_fts_index_done']))
            {
                $admin->saveSetting('info', 'fts_index_done', 0);
            }
            // remove notification disabled notification
            $cfg = new Configurator();
            $cfg->config['fts_disable_notification'] = false;
            $cfg->handleOverride();
        }        
    }
}

