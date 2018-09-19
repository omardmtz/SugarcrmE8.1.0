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

 // $Id: MyProjectTaskDashlet.php 45763 2009-04-01 19:16:18Z majed $




class MyProjectTaskDashlet extends DashletGeneric { 
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
		require('modules/ProjectTask/Dashlets/MyProjectTaskDashlet/MyProjectTaskDashlet.data.php');
		
        parent::__construct($id, $def);
        
        if(empty($def['title'])) $this->title = translate('LBL_LIST_MY_PROJECT_TASKS', 'ProjectTask');
        
        $this->searchFields = $dashletData['MyProjectTaskDashlet']['searchFields'];
        $this->columns = $dashletData['MyProjectTaskDashlet']['columns'];
        
        $this->seedBean = BeanFactory::newBean('ProjectTask');        
    }
    
    function buildWhere()
    {
        $resultArray = parent::buildWhere();
        
        $resultArray[] = $this->seedBean->table_name . '.' . "percent_complete != 100";

        return $resultArray;
    }
}
