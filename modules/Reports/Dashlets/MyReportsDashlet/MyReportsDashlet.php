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

 // $Id: MyReportsDashlet.php 16981 2006-10-10 22:51:30 +0000 (Tue, 10 Oct 2006) clee $




class MyReportsDashlet extends DashletGeneric { 
    
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings, $dashletData;
		require('modules/Reports/Dashlets/MyReportsDashlet/MyReportsDashlet.data.php');
        
        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_FAVORITE_REPORTS_TITLE', 'Reports');
        $this->isConfigurable = false;
        $this->searchFields = $dashletData['MyReportsDashlet']['searchFields'];
        $this->columns = $dashletData['MyReportsDashlet']['columns'];
        $this->seedBean = BeanFactory::newBean('Reports');        
    }

    public function process($lvsParams = array())
    {
        $this->lvs->quickViewLinks = false;
        parent::process($lvsParams);
    }
    
    function buildWhere() {
        global $current_user;
        $where_clauses = array();
        
        $sugaFav = BeanFactory::newBean('SugarFavorites');
        $current_favorites_beans = $sugaFav->getUserFavoritesByModule('Reports', $current_user);
        $current_favorites = array();
        foreach ($current_favorites_beans as $key=>$val) {
        	array_push($current_favorites,$val->record_id);
        }
        if(is_array($current_favorites) && !empty($current_favorites))
            array_push($where_clauses, 'saved_reports.id IN (\'' . implode("','", array_values($current_favorites)) . '\')');
        else {
            array_push($where_clauses, 'saved_reports.id IN (\'-1\')');
        }
        return $where_clauses; 
    }
    
}

