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


class ViewFavorites extends SugarView
{
 	public function __construct()
 	{
 		$this->options['show_title'] = false;
		$this->options['show_header'] = false;
		$this->options['show_footer'] = false;
		$this->options['show_javascript'] = false;
		$this->options['show_subpanels'] = false;
		$this->options['show_search'] = false;
        parent::__construct();
 	}

 	public function display()
 	{

        $favorites_max_viewed = (!empty($GLOBALS['sugar_config']['favorites_max_viewed']))? $GLOBALS['sugar_config']['favorites_max_viewed'] : 10;
 		$results = SugarFavorites::getUserFavoritesByModule($this->module,$GLOBALS['current_user'], "sugarfavorites.date_modified DESC ", $favorites_max_viewed);
 		$items = array();
 		foreach ( $results as $key => $row ) {
 				 $items[$key]['label'] = $row->record_name;
 				 $items[$key]['record_id'] = $row->record_id;
 				 $items[$key]['module'] = $row->module;
 		}
 		$this->ss->assign('FAVORITES',$items);
 		$this->ss->display('include/MVC/View/tpls/favorites.tpl');
 	}
}
