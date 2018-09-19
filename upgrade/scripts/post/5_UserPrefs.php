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
/**
 * Update user preferences
 */
class SugarUpgradeUserPrefs extends UpgradeScript
{
    public $order = 5000;

    public function __construct($upgrader)
    {
        $this->type = self::UPGRADE_CUSTOM | self::UPGRADE_DB;
        parent::__construct($upgrader);
    }

    protected function upgradeLocaleNameFormat($name_format)
    {
        if (!in_array($name_format, $this->config['name_formats'])) {
            $new_config = sugarArrayMerge($this->config['name_formats'], array($name_format=>$name_format));
            $this->upgrader->config['name_formats'] = $new_config;
        }
    }


    public function run()
    {
        $this->localization = $localization = Localization::getObject();
        $localeCoreDefaults = $this->localeCoreDefaults = $localization->getLocaleConfigDefaults();

        // check the current system wide default_locale_name_format and add it to the list if it's not there
        if(empty($this->config['name_formats'])) {
            $this->upgrader->config['name_formats'] = $localeCoreDefaults['name_formats'];
        }

        $currentDefaultLocaleNameFormat = $this->config['default_locale_name_format'];

        if ($localization->isAllowedNameFormat($currentDefaultLocaleNameFormat)) {
            $this->upgradeLocaleNameFormat($currentDefaultLocaleNameFormat);
        } else {
            $this->upgrader->config['default_locale_name_format'] = $localeCoreDefaults['default_locale_name_format'];
            //TODO: This doesn't seem to do anything useful?? $localization->createInvalidLocaleNameFormatUpgradeNotice();
        }

        if($this->toFlavor('pro')) {
        	if(file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
   	           require($cachedfile);
   	        } else if(file_exists('modules/Dashboard/dashlets.php')) {
           	   require('modules/Dashboard/dashlets.php');
   	        }

        	$upgradeTrackingDashlets = array('TrackerDashlet'=>array(
									    'file' => 'modules/Trackers/Dashlets/TrackerDashlet/TrackerDashlet.php',
									    'class' => 'TrackerDashlet',
									    'meta' => 'modules/Trackers/Dashlets/TrackerDashlet/TrackerDashlet.meta.php',
									    'module' => 'Trackers',
									 ),
									 'MyModulesUsedChartDashlet'=>array(
									    'file' => 'modules/Charts/Dashlets/MyModulesUsedChartDashlet/MyModulesUsedChartDashlet.php',
									    'class' => 'MyModulesUsedChartDashlet',
									    'meta' => 'modules/Charts/Dashlets/MyModulesUsedChartDashlet/MyModulesUsedChartDashlet.meta.php',
									    'module' => 'Trackers',
									 ),
									 'MyTeamModulesUsedChartDashlet'=>array(
									    'file' => 'modules/Charts/Dashlets/MyTeamModulesUsedChartDashlet/MyTeamModulesUsedChartDashlet.php',
									    'class' => 'MyTeamModulesUsedChartDashlet',
									    'meta' => 'modules/Charts/Dashlets/MyTeamModulesUsedChartDashlet/MyTeamModulesUsedChartDashlet.meta.php',
									    'module' => 'Trackers',
									 )
							   );
        }

        $result = $this->db->query("SELECT id FROM users WHERE " . User::getLicensedUsersWhere());
   	    while($row = $this->db->fetchByAssoc($result))
        {
            $current_user = BeanFactory::getBean('Users', $row['id']);

            // get the user's name locale format, check if it's in our list, add it if it's not, keep it as user's default
            $changed = false;
            $currentUserNameFormat = $current_user->getPreference('default_locale_name_format');
            if ($localization->isAllowedNameFormat($currentUserNameFormat)) {
                $this->upgradeLocaleNameFormat($currentUserNameFormat);
            } else {
                $current_user->setPreference('default_locale_name_format', 's f l', 0, 'global');
                $changed = true;
            }

            if(!$current_user->getPreference('calendar_publish_key')) {
            	// set publish key if not set already
            	$current_user->setPreference('calendar_publish_key', create_guid());
            	$changed = true;
            }

            // versions of 6.7.4 and earlier show user preferred by default
            if (version_compare($this->from_version, '6.7.5', '<')) {
                $current_user->setPreference('currency_show_preferred', true, 0, 'global');
            }

            if($this->toFlavor('pro')) {
	          //Set the user theme to be 'Sugar' theme since this is run for CE flavor conversions
	          $userTheme = $current_user->getPreference('user_theme', 'global');

              //If theme is empty or if theme was set to Classic (Sugar5) or if this is a ce to pro/ent flavor upgrade change to RacerX theme
	          if(empty($userTheme) || $userTheme == 'Sugar5' || $this->from_flavor == 'ce')
	          {
                  $changed = true;
	      	      $current_user->setPreference('user_theme', 'RacerX', 0, 'global');
	          }

    	      //Set the number of tabs by default to 7
	          $maxTabs = $current_user->getPreference('max_tabs', 'global');
	          if(empty($maxTabs))
	          {
                $changed = true;
	      	    $current_user->setPreference('max_tabs', '7', 0, 'global');
	          }

              //If preferences have changed, save before proceeding
              if($changed)
              {
                 $current_user->savePreferencesToDB();
              }

    		  $pages = $current_user->getPreference('pages', 'Home');

	    	  if(empty($pages))
              {
                 continue;
		      }

              $changed = false;
    		  $empty_dashlets = array();
    		  $dashlets = $current_user->getPreference('dashlets', 'Home');
    		  $dashlets = !empty($dashlets) ? $dashlets : $empty_dashlets;
       		  $existingDashlets = array();
       		  foreach($dashlets as $id=>$dashlet) {
       		  	      if(!empty($dashlet['className']) && !is_array($dashlet['className'])) {
    		  	         $existingDashlets[$dashlet['className']] = $dashlet['className'];
       		  	      }
    		  }

    		  if(ACLController::checkAccess('Trackers', 'view', false, 'Tracker')) {
    				$trackingDashlets = array();
    			    foreach($upgradeTrackingDashlets as $trackingDashletName=>$entry){
    			    	if (empty($existingDashlets[$trackingDashletName])) {
    			            $trackingDashlets[create_guid()] = array('className' => $trackingDashletName,
    				                                                 'fileLocation' => $entry['file'],
    			                                                     'options' => array());
    			    	}
    			    }

    			    if(empty($trackingDashlets)) {
    			       continue;
    			    }

    			    $trackingColumns = array();
    			    $trackingColumns[0] = array();
    			    $trackingColumns[0]['width'] = '50%';
    			    $trackingColumns[0]['dashlets'] = array();

    			    foreach($trackingDashlets as $guid=>$dashlet){
    			            array_push($trackingColumns[0]['dashlets'], $guid);
    			    }

    			    //Set the tracker dashlets to user preferences table
    		 		$dashlets = array_merge($dashlets, $trackingDashlets);
    		 		$current_user->setPreference('dashlets', $dashlets, 0, 'Home');

    		    	//Set the dashlets pages to user preferences table
    		    	$pageIndex = count($pages);
    				$pages[$pageIndex]['columns'] = $trackingColumns;
    				$pages[$pageIndex]['numColumns'] = '1';
    				$pages[$pageIndex]['pageTitle'] = $this->mod_strings['LBL_HOME_PAGE_4_NAME'];
    				$current_user->setPreference('pages', $pages, 0, 'Home');
                    $changed = true;
    		  } //if
            }

            // we need to force save the changes to disk, otherwise we lose them.
            if($changed)
            {
                $current_user->savePreferencesToDB();
            }

    	} //while

        if($this->toFlavor('pro')) {
    /*
	 * This section checks to see if the Tracker settings for the corresponding versions have been
	 * disabled and the regular tracker (for breadcrumbs) enabled.  If so, then it will also disable
	 * the tracking for the regular tracker.  Disabling the tracker (for breadcrumbs) will no longer prevent
	 * breadcrumb tracking.  It will instead only track visible entries (see trackView() method in SugarView.php).
	 * This has the benefit of reducing the tracking overhead and limiting it to only visible items.
	 * For the CE version, we are checking to see that there are no entries enabled for PRO/ENT versions
	 * we are checking for Tracker sessions, performance and queries.
	 */
        	if($this->from_flavor == 'ce') {
        		//Set tracker settings. Disable tracker session, performance and queries
        		$category = 'tracker';
        		$value = 1;
        		$key = array('Tracker', 'tracker_sessions','tracker_perf','tracker_queries');
        		$admin = new Administration();
        		foreach($key as $k){
        			$admin->saveSetting($category, $k, $value);
        		}
        	} else {
        	   $query = "select count(name) as total from config where category = 'tracker' and name = 'Tracker'";
        	   $results = $this->db->query($query);
        	   if(!empty($results)) {
        	       $row = $this->db->fetchByAssoc($results);
        	       $total = $row['total'];
        	       if($total > 1) {
                        $this->db->query("DELETE FROM config where category = 'tracker' and name = 'Tracker'");
        	       	   	$this->db->query("INSERT INTO config (category, name, value) VALUES ('tracker', 'Tracker', '1')");
        	       }
        	   }
        	}

        	//Write the entries to cache/dashlets/dashlets.php
        	if(file_exists($cachedfile = sugar_cached('dashlets/dashlets.php'))) {
        	   require($cachedfile);
        	   foreach($upgradeTrackingDashlets as $id=>$entry) {
        	   	   if(!isset($dashletsFiles[$id])) {
        	   	   	  $dashletsFiles[$id] = $entry;
        	   	   }
        	   }
        	   write_array_to_file("dashletsFiles", $dashletsFiles, $cachedfile);
            } //if
        }
    }

}
