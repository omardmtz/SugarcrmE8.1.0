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



class DashletsDialog {
	var $dashlets = array();

    function getDashlets($category='') {
        global $app_strings, $current_language, $mod_strings;

        require_once($GLOBALS['sugar_config']['cache_dir'].'dashlets/dashlets.php');

        $categories = array( 'module' 	=> 'Module Views',
        					 'portal' 	=> 'Portal',
        					 'charts'	=> 'Charts',
        					 'tools'	=> 'Tools',
        					 'misc'		=> 'Miscellaneous',
        					 'web'      => 'Web');

        $dashletStrings = array();
        $dashletsList = array();

        if (!empty($category)){
			$dashletsList[$categories[$category]] = array();
        }
        else{
	        $dashletsList['Module Views'] = array();
	        $dashletsList['Charts'] = array();
	        $dashletsList['Tools'] = array();
	        $dashletsList['Web'] = array();
        }

        asort($dashletsFiles);

        foreach($dashletsFiles as $className => $files) {
            if (!empty($files['meta']) && file_exists($files['meta'])) {
                require_once($files['meta']); // get meta file

                $directory = substr($files['meta'], 0, strrpos($files['meta'], '/') + 1);
                foreach(SugarAutoLoader::existing(
                    $directory . $files['class'] . '.' . $current_language . '.lang.php',
                    $directory . $files['class'] . '.en_us.lang.php'
                ) as $file) {
                    require $file;
                }

                // try to translate the string
                if(empty($dashletStrings[$files['class']][$dashletMeta[$files['class']]['title']]))
                    $title = $dashletMeta[$files['class']]['title'];
                else
                    $title = $dashletStrings[$files['class']][$dashletMeta[$files['class']]['title']];

                // try to translate the string
                if(empty($dashletStrings[$files['class']][$dashletMeta[$files['class']]['description']]))
                    $description = $dashletMeta[$files['class']]['description'];
                else
                    $description = $dashletStrings[$files['class']][$dashletMeta[$files['class']]['description']];

				// generate icon
                if (!empty($dashletMeta[$files['class']]['icon'])) {
                    // here we'll support image inheritance if the supplied image has a path in it
                    // i.e. $dashletMeta[$files['class']]['icon'] = 'themes/default/images/dog.gif'
                    // in this case, we'll strip off the path information to check for the image existing
                    // in the current theme.

                    $imageName = SugarThemeRegistry::current()->getImageURL(basename($dashletMeta[$files['class']]['icon']), false);
                    if ( !empty($imageName) ) {
                        if (sugar_is_file($imageName))
                            $icon = '<img src="' . $imageName .'" alt="" border="0" align="absmiddle" />';  //leaving alt tag blank on purpose for 508
                        else
                            $icon = '';
                    }
                }
                else{
	                if (empty($dashletMeta[$files['class']]['module'])){
                		$icon = get_dashlets_dialog_icon('default');
                	}
					else{
						if((!in_array($dashletMeta[$files['class']]['module'], $GLOBALS['moduleList']) && !in_array($dashletMeta[$files['class']]['module'], $GLOBALS['modInvisList'])) && (!in_array('Activities', $GLOBALS['moduleList']))){
							unset($dashletMeta[$files['class']]);
							continue;
						}else{
	                    	$icon = get_dashlets_dialog_icon($dashletMeta[$files['class']]['module']);
						}
                	}
                }

                // determine whether to display
                if (!empty($dashletMeta[$files['class']]['hidden']) && $dashletMeta[$files['class']]['hidden'] === true){
                	$displayDashlet = false;
                }
				//co: fixes 20398 to respect ACL permissions
				elseif(!empty($dashletMeta[$files['class']]['module']) && (!in_array($dashletMeta[$files['class']]['module'], $GLOBALS['moduleList']) && !in_array($dashletMeta[$files['class']]['module'], $GLOBALS['modInvisList'])) && (!in_array('Activities', $GLOBALS['moduleList']))){
                	$displayDashlet = false;
                }
				else{
                	$displayDashlet = true;
                	//check ACL ACCESS
                	if(!empty($dashletMeta[$files['class']]['module'])) {
                	    if(!SugarACL::checkAccess($dashletMeta[$files['class']]['module'], 'view', array('owner_override' => true))) {
                	        $displayDashlet = false;
                	    }
                	    if(!SugarACL::checkAccess($dashletMeta[$files['class']]['module'], 'list', array('owner_override' => true))) {
                	        $displayDashlet = false;
                	    }
                	}
                }

                if ($dashletMeta[$files['class']]['category'] == 'Charts'){
                	$type = 'predefined_chart';
                }
                else{
                	$type = 'module';
                }

                if ($displayDashlet && isset($dashletMeta[$files['class']]['dynamic_hide']) && $dashletMeta[$files['class']]['dynamic_hide']){
                    if (file_exists($files['file'])) {
                        require_once($files['file']);
                        if ( class_exists($files['class']) ) {
                            $dashletClassName = $files['class'];
                            $displayDashlet = call_user_func(array($files['class'],'shouldDisplay'));
                        }
                    }
                }

                if ($displayDashlet){
					$cell = array( 'title' => $title,
								   'description' => $description,
								   'onclick' => 'return SUGAR.mySugar.addDashlet(\'' . $className . '\', \'' . $type . '\', \''.(!empty($dashletMeta[$files['class']]['module']) ? $dashletMeta[$files['class']]['module'] : '' ) .'\');',
                                   'icon' => $icon,
                                   'id' => $files['class'] . '_select',
                               );

	                if (!empty($category) && $dashletMeta[$files['class']]['category'] == $categories[$category]){
	                	array_push($dashletsList[$categories[$category]], $cell);
	                }
	                else if (empty($category)){
						array_push($dashletsList[$dashletMeta[$files['class']]['category']], $cell);
	                }
                }
            }
        }
        if (!empty($category)){
        	asort($dashletsList[$categories[$category]]);
        }
        else{
        	foreach($dashletsList as $key=>$value){
        		asort($dashletsList[$key]);
        	}
        }
        $this->dashlets = $dashletsList;
    }


    function getReportCharts($category){
    	global $current_user;

    	$chartsList = array();
        require_once('modules/Reports/SavedReport.php');
        $sq = new SugarQuery();
        $savedReportBean = BeanFactory::newBean('Reports');
        $sq->from($savedReportBean);

        // Make sure the user isn't seeing reports they don't have access to
        $modules = array_keys(getACLDisAllowedModules());
        if(count($modules)) {
            $sq->where()->notIn('module', $modules);
        }

        //create the $where statement(s)
        $sq->where()->notEquals('chart_type', 'none');
    	switch($category){
    		case 'global':
		    	// build global where string
                $sq->where()->equals('saved_reports.team_set_id', '1');
    	    	break;

    		case 'myTeams':
		    	// build myTeams where string
		    	$myTeams = $current_user->get_my_teams();
		    	$teamWhere = '';
		    	foreach($myTeams as $team_id=>$team_name){
		    		if ($team_id != '1' && $team_id != $current_user->getPrivateTeamID()){
			    		if ($teamWhere == ''){
                            $teamWhere .= ' ';
			    		}
			    		else{
                            $teamWhere .= 'OR ';
			    		}
                        $teamWhere .= "saved_reports.team_set_id='".$team_id. "' ";
		    		}
		    	}
                $sq->whereRaw($teamWhere);
		    	break;

    		case 'mySaved':
		    	// build mySaved where string
                $sq->where()->equals('saved_reports.team_set_id',$current_user->getPrivateTeamID());
		    	break;

		    case 'myFavorites':
                global $current_user;
                $sugaFav = BeanFactory::newBean('SugarFavorites');
                $current_favorites_beans = $sugaFav->getUserFavoritesByModule('Reports', $current_user);
                $current_favorites = array();
                foreach ((array)$current_favorites_beans as $key=>$val) {
                    array_push($current_favorites,$val->record_id);
                }
                if(is_array($current_favorites) && !empty($current_favorites)) {
                    $sq->where()->in('saved_reports.id',  array_values($current_favorites));
                }else{
                    $sq->where()->in('saved_reports.id',  array('-1'));
                }
                break;


    		default:
    			break;
    	}

        //retrieve array of reports
        $savedReports = $savedReportBean->fetchFromQuery($sq);

		$chartsList = array();
		if (!empty($savedReports)){
			foreach($savedReports as $savedReport){
				// clint - fixes bug #20398
				// only display dashlets that are from visibile modules and that the user has permission to list
				$myDashlet = new MySugar($savedReport->module);
				$displayDashlet = $myDashlet->checkDashletDisplay();

				if ($displayDashlet) {
					$title = getReportNameTranslation($savedReport->name); 
					$report_def = array( 'title' => $title, 
										 'onclick' => 'return SUGAR.mySugar.addDashlet(\'' . $savedReport->id . '\', \'chart\', \''.$savedReport->module.'\');',
									);

				array_push($chartsList, $report_def);
			}
		}
		}
		asort($chartsList);
		$this->dashlets[$category] = $chartsList;
    }
}

?>
