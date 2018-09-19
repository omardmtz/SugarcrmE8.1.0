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
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



class MyModulesUsedChartDashlet extends DashletGenericChart 
{
    /**
     * @see Dashlet::$isConfigurable
     */
    public $isConfigurable = true;
    
    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Trackers';
    
    /**
     * @see Dashlet::$isConfigPanelClearShown
     */
    public $isConfigPanelClearShown = false;
    
    /**
     * @param string $text Ignored
     * @see DashletGenericChart::display()
     */
    public function display($text = '')
    {
        global $db,$app_list_strings;
        
        require("modules/Charts/chartdefs.php");
        $chartDef = $chartDefs['my_modules_used_last_30_days'];
        
        $sugarChart = SugarChartFactory::getInstance();
        $sugarChart->setProperties('',  translate('LBL_MY_MODULES_USED_SIZE', 'Charts'), $chartDef['chartType']);
        $sugarChart->base_url = $chartDef['base_url'];
        $sugarChart->group_by = $chartDef['groupBy'];
        $sugarChart->url_params = array();		
        $result = $db->query($this->constructQuery());
        $dataset = array();
        while ($row = $db->fetchByAssoc($result))
        {
        	$dataset[translate($row['module_name'])] =  $row['count'];
        }
        $sugarChart->setData($dataset);
        $xmlFile = $sugarChart->getXMLFileName($this->id);
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
        
        return $this->getTitle('<div align="center"></div>') . '<div align="center">' . $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . '</div><br />'. $this->processAutoRefresh();
	}

    /**
     * @see Dashlet::hasAccess()
     */
    public function hasAccess()
    {
    	return ACLController::checkAccess('Trackers', 'view', false, 'Tracker');
    }	
	
    /**
     * @see DashletGenericChart::constructQuery()
     */
    protected function constructQuery() 
    {
        return "SELECT tracker.module_name as module_name ,COUNT(*) count " .
                    "FROM tracker " .
                    "WHERE tracker.user_id = '{$GLOBALS['current_user']->id}' " .
                        "AND tracker.module_name != 'UserPreferences' AND tracker.date_modified > ".db_convert("'".gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("- 30 days"))."'" ,"datetime")." " .
                        "GROUP BY tracker.module_name ORDER BY count DESC";
	}
}
