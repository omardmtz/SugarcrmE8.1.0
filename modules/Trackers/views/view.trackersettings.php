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

class TrackersViewTrackersettings extends SugarView 
{	
    /**
	 * @see SugarView::_getModuleTab()
	 */
	protected function _getModuleTab()
    {
        return 'Administration';
    }
 	
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	   translate('LBL_TRACKER_SETTINGS','Administration'),
    	   );
    }
    
 	/** 
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $app_strings;
        
        $admin = Administration::getSettings();
        
        require('modules/Trackers/config.php');
        
        ///////////////////////////////////////////////////////////////////////////////
        ////	HANDLE CHANGES
        if(isset($_POST['process'])) {
           if($_POST['process'] == 'true') {
               foreach($tracker_config as $entry) {
                  if(isset($entry['bean'])) {
                      //If checkbox is unchecked, we add the entry into the config table; otherwise delete it
                      if(empty($_POST[$entry['name']])) {
                        $admin->saveSetting('tracker', $entry['name'], 1);
                      }	else {
                        $db = DBManagerFactory::getInstance();
                        $db->query("DELETE FROM config WHERE category = 'tracker' and name = '" . $entry['name'] . "'");
                      }
                  }
               } //foreach
               
               //save the tracker prune interval
               if(!empty($_POST['tracker_prune_interval'])) {
                  $admin->saveSetting('tracker', 'prune_interval', $_POST['tracker_prune_interval']);
               }
               
               //save log slow queries and slow query interval
               $configurator = new Configurator();
               $configurator->saveConfig();
           } //if
           header('Location: index.php?module=Administration&action=index');
        }
        
        echo getClassicModuleTitle(
                "Administration", 
                array(
                    "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
                    translate('LBL_TRACKER_SETTINGS','Administration'),
                    ), 
                false
                );
        
        $trackerManager = TrackerManager::getInstance();
        $disabledMonitors = $trackerManager->getDisabledMonitors();
        $trackerEntries = array();
        foreach($tracker_config as $entry) {
           if(isset($entry['bean'])) {
              $disabled = !empty($disabledMonitors[$entry['name']]);
              $trackerEntries[$entry['name']] = array('label'=> $mod_strings['LBL_' . strtoupper($entry['name']) . '_DESC'], 'helpLabel'=> $mod_strings['LBL_' . strtoupper($entry['name']) . '_HELP'], 'disabled'=>$disabled);
           }
        }
        
        $configurator = new Configurator();
        $this->ss->assign('config', $configurator->config);
        
        $config_strings = return_module_language($GLOBALS['current_language'], 'Configurator');
        $mod_strings['LOG_SLOW_QUERIES'] = $config_strings['LOG_SLOW_QUERIES'];
        $mod_strings['SLOW_QUERY_TIME_MSEC'] = $config_strings['SLOW_QUERY_TIME_MSEC'];
        
        $this->ss->assign('mod', $mod_strings);
        $this->ss->assign('app', $app_strings);
        $this->ss->assign('trackerEntries', $trackerEntries);
        $this->ss->assign('tracker_prune_interval', !empty($admin->settings['tracker_prune_interval']) ? $admin->settings['tracker_prune_interval'] : 30);
        $this->ss->display('modules/Trackers/tpls/TrackerSettings.tpl');
    }
}
