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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

 /**
  * @api
  */
 class SugarVCR{

 	/**
 	 * records the query in the session for later retrieval
 	 */
    public static function store($module, $query)
    {
 		$_SESSION[$module .'2_QUERY'] = $query;
 	}

 	/**
 	 * This function retrieves a query from the session
 	 */
    public static function retrieve($module)
    {
 		return (!empty($_SESSION[$module .'2_QUERY']) ? $_SESSION[$module .'2_QUERY'] : '');
 	}

 	/**
 	 * return the start, prev, next, end
 	 */
    public static function play($module, $offset)
    {
 		//given some global offset try to determine if we have this
 		//in our array.
 		$ids = array();
 		if(!empty($_SESSION[$module.'QUERY_ARRAY']))
 			$ids = $_SESSION[$module.'QUERY_ARRAY'];
 		if(empty($ids[$offset]) || empty($ids[$offset+1]) || empty($ids[$offset-1]))
 			$ids = SugarVCR::record($module, $offset);
 		$menu = array();
 		if(!empty($ids[$offset])){
 			//return the control given this id
 			$menu['PREV'] = ($offset > 1) ? $ids[$offset-1] : '';
 			$menu['CURRENT'] = $ids[$offset];
 			$menu['NEXT'] = !empty($ids[$offset+1]) ? $ids[$offset+1] : '';
 		}
 		return $menu;
 	}

    public static function menu($module, $offset, $isAuditEnabled, $saveAndContinue = false)
    {
        $html_text = "";
        if ($offset < 0)
        {
            $offset = 0;
        }

        //this check if require in cases when you visit the edit view before visiting that modules list view.
        //you can do this easily either from home, activities or sitemap.
        $stored_vcr_query = SugarVCR::retrieve($module);

        // bug 15893 - only show VCR if called as an element in a set of records
        if (!empty($_REQUEST['record']) and !empty($stored_vcr_query) and isset($_REQUEST['offset']) and (empty($_REQUEST['isDuplicate']) or $_REQUEST['isDuplicate'] == 'false'))
        {
            //syncing with display offset;
            $offset ++;
            $action = InputValidation::getService()->getValidInputRequest('action', null, 'EditView');
            $action = htmlspecialchars($action, ENT_QUOTES, 'UTF-8');

            $menu = SugarVCR::play($module, $offset);

            $previous_link = "";
            if (!empty($menu['PREV']))
            {
                $previous_link = 'index.php?' . http_build_query(
                    array(
                        'action' => $action,
                        'module' => $module,
                        'offset' => ($offset - 1),
                        'record' => $menu['PREV'],
                    )
                );
            }

            $list_link = '';
            $next_link = '';
            if (!empty($menu['NEXT']))
            {
                $next_link = 'index.php?' . http_build_query(
                    array(
                        'action' => $action,
                        'module' => $module,
                        'offset' => ($offset + 1),
                        'record' => $menu['NEXT'],
                    )
                );

                if ($saveAndContinue) {
                    $list_link = $next_link;
                }
            }

            $ss = new Sugar_Smarty();
            $ss->assign('app_strings', $GLOBALS['app_strings']);
            $ss->assign('module', $module);
            $ss->assign('action', $action);
            $ss->assign('menu', $menu);
            $ss->assign('list_link', $list_link);
            $ss->assign('previous_link', $previous_link);
            $ss->assign('next_link', $next_link);

            $ss->assign('offset', $offset);
            $ss->assign('total', '');
            $ss->assign('plus', '');

            if (!empty($_SESSION[$module.'total']))
            {
                $ss->assign('total', $_SESSION[$module.'total']);
                if (
                    !empty($GLOBALS['sugar_config']['disable_count_query'])
                    && (($_SESSION[$module.'total']-1) % $GLOBALS['sugar_config']['list_max_entries_per_page'] == 0)
                )
                {
                    $ss->assign('plus', '+');
                }
            }

            $html_text .= $ss->fetchCustom('include/EditView/SugarVCR.tpl');
        }
        return $html_text;
    }

    public static function record($module, $offset)
    {
 		$GLOBALS['log']->debug('SUGARVCR is recording more records');
        $page_length = $GLOBALS['sugar_config']['list_max_entries_per_page'] + 1;
 		$start = max(0, $offset - $page_length);
 		$index = $start;
	    $db = DBManagerFactory::getInstance();

 		$result = $db->limitQuery(SugarVCR::retrieve($module), $start, ($offset + $page_length), false);
 		$index++;

 		$ids = array();
 		while(($row = $db->fetchByAssoc($result)) != null){
 			$ids[$index] = $row['id'];
 			$index++;
 		}
        //get last index of ids
        end($ids);
        $_SESSION[$module.'total'] = key($ids);
        reset($ids);
 		//now that we have the array of ids, store this in the session
 		$_SESSION[$module.'QUERY_ARRAY'] = $ids;
 		return $ids;
 	}

    public static function recordIDs($module, $rids, $offset, $totalCount)
    {
 		$index = $offset;
 		$index++;
 		$ids = array();
 		foreach($rids as $id){
 			$ids[$index] = $id;
 			$index++;
 		}
 		//now that we have the array of ids, store this in the session
 		$_SESSION[$module.'QUERY_ARRAY'] = $ids;
 		$_SESSION[$module.'total'] = $totalCount;
 	}

    public static function erase($module)
    {
 		unset($_SESSION[$module. 'QUERY_ARRAY']);
 	}
}
