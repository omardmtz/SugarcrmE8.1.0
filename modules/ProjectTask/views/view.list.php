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


class ProjectTaskViewList extends ViewList
{
 	function display()
 	{
 		if(!$this->bean->ACLAccess('list')){
 			ACLController::displayNoAccess();
 			return;
 		}
        $module = $GLOBALS['module'];
 	    $metadataFile = SugarAutoLoader::loadWithMetafiles($module, 'listviewdefs');
        require_once($metadataFile);


		$this->bean->ACLFilterFieldList($listViewDefs[$module], array("owner_override" => true));
		$seed = $this->bean;
        if(!empty($this->bean->object_name) && isset($_REQUEST[$module.'2_'.strtoupper($this->bean->object_name).'_offset'])) {//if you click the pagination button, it will populate the search criteria here
            if(!empty($_REQUEST['current_query_by_page'])) {//The code support multi browser tabs pagination
                $blockVariables = array('mass', 'uid', 'massupdate', 'delete', 'merge', 'selectCount', 'request_data', 'current_query_by_page', $module.'2_'.strtoupper($this->bean->object_name).'_ORDER_BY');
		        if(isset($_REQUEST['lvso'])) {
		        	$blockVariables[] = 'lvso';
		        }

                $current_query_by_page = $this->request->getValidInputRequest(
                    'current_query_by_page',
                    array('Assert\PhpSerialized' => array('base64Encoded' => true))
                );
                foreach($current_query_by_page as $search_key=>$search_value) {
                    if($search_key != $module.'2_'.strtoupper($this->bean->object_name).'_offset' && !in_array($search_key, $blockVariables)) {
                        if (!is_array($search_value)) {
                            $_REQUEST[$search_key] = $GLOBALS['db']->quote($search_value);
                        } else {
                            foreach ($search_value as $key=>&$val) {
                                $val = $GLOBALS['db']->quote($val);
                            }
                            $_REQUEST[$search_key] = $search_value;
                        }
                    }
                }
            }
        }

        if(!empty($_REQUEST['saved_search_select']) && $_REQUEST['saved_search_select']!='_none') {
            if(empty($_REQUEST['button']) && (empty($_REQUEST['clear_query']) || $_REQUEST['clear_query']!='true')) {
                $this->saved_search = BeanFactory::newBean('SavedSearch');
                $this->saved_search->retrieveSavedSearch($_REQUEST['saved_search_select']);
                $this->saved_search->populateRequest();
            } elseif(!empty($_REQUEST['button'])) { // click the search button, after retrieving from saved_search
                $_SESSION['LastSavedView'][$_REQUEST['module']] = '';
                unset($_REQUEST['saved_search_select']);
                unset($_REQUEST['saved_search_select_name']);
            }
        }

		$lv = new ListViewSmarty();
		$displayColumns = array();
		if(!empty($_REQUEST['displayColumns'])) {
		    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
		        if(!empty($listViewDefs[$module][$col]))
		            $displayColumns[$col] = $listViewDefs[$module][$col];
		    }
		} else {
		    foreach($listViewDefs[$module] as $col => $params) {
		        if(!empty($params['default']) && $params['default'])
		            $displayColumns[$col] = $params;
		    }
		}

        $params = array( 'massupdate' => true, 'export' => true);

		if(!empty($_REQUEST['orderBy'])) {
		    $params['orderBy'] = $_REQUEST['orderBy'];
		    $params['overrideOrder'] = true;
		    if(!empty($_REQUEST['sortOrder'])) $params['sortOrder'] = $_REQUEST['sortOrder'];
		}
		$lv->displayColumns = $displayColumns;

		$this->seed = $seed;
		$this->module = $module;

		$searchForm = null;
	 	$storeQuery = new StoreQuery();
		if(!isset($_REQUEST['query'])) {
			$storeQuery->loadQuery($this->module);
			$storeQuery->populateRequest();
		} else {
			$storeQuery->saveFromRequest($this->module);
		}

		//search
		$view = 'basic_search';
		if(!empty($_REQUEST['search_form_view'])) {
			$view = $_REQUEST['search_form_view'];
        }

		$headers = true;

        if(!empty($_REQUEST['search_form_only']) && $_REQUEST['search_form_only']) {
			$headers = false;
        } elseif(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        	if(isset($_REQUEST['searchFormTab']) && $_REQUEST['searchFormTab'] == 'advanced_search') {
				$view = 'advanced_search';
			}else {
				$view = 'basic_search';
			}
		}

		$use_old_search = true;
		if(SugarAutoLoader::existing('modules/'.$this->module.'/SearchForm.html')){
			require_once('include/SearchForm/SearchForm.php');
			$searchForm = new SearchForm($this->module, $this->seed);
		} else {
			$use_old_search = false;
			require_once('include/SearchForm/SearchForm2.php');
            $defs = SugarAutoLoader::loadWithMetafiles($this->module, 'searchdefs');
            if(!empty($defs)) {
                require $defs;
            }
            $searchFields = SugarAutoLoader::loadSearchFields($this->module);
			$searchForm = new SearchForm($this->seed, $this->module, $this->action);
			$searchForm->setup($searchdefs, $searchFields, 'SearchFormGeneric.tpl', $view, $listViewDefs);
			$searchForm->lv = $lv;
		}

		if(isset($this->options['show_title']) && $this->options['show_title']) {
			$moduleName = isset($this->seed->module_dir) ? $this->seed->module_dir : $GLOBALS['mod_strings']['LBL_MODULE_NAME'];
			echo getClassicModuleTitle($moduleName, array($GLOBALS['mod_strings']['LBL_MODULE_TITLE']), FALSE);
		}

		$where = '';

		if(isset($_REQUEST['query'])) {
			// we have a query
	    	if(!empty($_SERVER['HTTP_REFERER']) && preg_match('/action=EditView/', $_SERVER['HTTP_REFERER'])) { // from EditView cancel
	       		$searchForm->populateFromArray($storeQuery->query);
	    	}
	    	else {
                $searchForm->populateFromRequest();
	    	}
			$where_clauses = $searchForm->generateSearchWhere(true, $this->seed->module_dir);
			if (count($where_clauses) > 0 )$where = '('. implode(' ) AND ( ', $where_clauses) . ')';
			$GLOBALS['log']->info("List View Where Clause: $where");
		}

		if($use_old_search) {
			switch($view) {
				case 'basic_search':
			    	$searchForm->setup();
			        $searchForm->displayBasic($headers);
			        break;
			     case 'advanced_search':
			     	$searchForm->setup();
			        $searchForm->displayAdvanced($headers);
			        break;
			     case 'saved_views':
			     	echo $searchForm->displaySavedViews($listViewDefs, $lv, $headers);
			       break;
			}
		} else {
			echo $searchForm->display($headers);
		}

		if(!$headers) {
			return;
        }

        /*
        * Bug 50575 - related search columns not inluded in query in a proper way
        */
        $lv->searchColumns = $searchForm->searchColumns;

        if (isset($GLOBALS['mod_strings']['LBL_MODULE_NAME_SINGULAR'])) {
            $seed->module_title = $GLOBALS['mod_strings']['LBL_MODULE_NAME_SINGULAR'];
        }

        if (isset($GLOBALS['mod_strings']['LBL_LIST_PARENT_NAME'])) {
            $seed->parent_title = $GLOBALS['mod_strings']['LBL_LIST_PARENT_NAME'];
            $seed->parent_module_dir = 'Project';
        }

        $project = BeanFactory::newBean('Project');
        $project_query = new SugarQuery();
        $project_query->from($project);
        $project_list = $project->fetchFromQuery($project_query);

        if (count($project_list)) {
            $seed->show_link = true;
        }

        if(empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
            //Bug 58841 - mass update form was not displayed for non-admin users that should have access
            if(ACLController::checkAccess($module, 'massupdate') || ACLController::checkAccess($module, 'export')){
                $lv->setup($seed, 'include/ListView/ListViewGeneric.tpl', $where, $params);
            } else {
                $lv->setup($seed, 'include/ListView/ListViewNoMassUpdate.tpl', $where, $params);
            }

            echo $lv->display();
        }
    }
}
