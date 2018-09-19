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

 // $Id: MyPipelineBySalesStageDashlet.php 24275 2007-07-13 04:26:44Z awu $


class MyOpportunitiesGaugeDashlet extends DashletGenericChart 
{
    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Opportunities';
    
    /**
     * @see Dashlet::$isConfigPanelClearShown
     */
    public $isConfigPanelClearShown = false;
    
    /**
     * @see DashletGenericChart::__construct()
     */
    public function __construct(
        $id, 
        array $options = null
        ) 
    {
        if (empty($options['title'])) 
            $options['title'] = translate('LBL_MY_CLOSED_OPPORTUNITIES_GAUGE', 'Home');
        
        parent::__construct($id,$options);
    }
    
    /**
     * @see DashletGenericChart::display()
     */
    public function display() 
    {
        require('modules/Charts/chartdefs.php');
        $chartDef = $chartDefs['opportunities_this_quarter'];
		
        $sugarChart = SugarChartFactory::getInstance();
        $sugarChart->setProperties('', translate('LBL_NUMBER_OF_OPPS', 'Charts'), $chartDef['chartType']);
        $sugarChart->base_url = $chartDef['base_url'];
        $sugarChart->group_by = $chartDef['groupBy'];
        $sugarChart->url_params = array();
        
        // get gauge target
        $qry = "SELECT * from opportunities WHERE assigned_user_id = '" . $GLOBALS['current_user']->id . "' AND deleted=0";
        $result = $this->getSeedBean()->db->query($this->getSeedBean()->create_list_count_query($qry));	
        $row = $this->getSeedBean()->db->fetchByAssoc($result);
        $sugarChart->setDisplayProperty('gaugeTarget', $row['c']);
        
        $sugarChart->getData($this->constructQuery());
        
        $xmlFile = $sugarChart->getXMLFileName($this->id);
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
        
        return $this->getTitle('<div align="center"></div>') . '<div align="center">' .$sugarChart->display($this->id, $xmlFile, '100%', '480', false) . '</div><br />'. $this->processAutoRefresh();
    }  
    
    /**
     * @see DashletGenericChart::constructQuery()
     */
    protected function constructQuery() 
    {
		return "SELECT count(*) AS num " . 
				 "FROM opportunities " .
				 "WHERE assigned_user_id = '{$GLOBALS['current_user']->id}' " .
                    "AND sales_stage = '".Opportunity::STAGE_CLOSED_WON."' AND deleted=0" ;
    }
    
    /**
     * @see DashletGenericChart::constructGroupBy()
     */
    protected function constructGroupBy()
    {
    	return array( 'sales_stage', 'user_name', );
    }
}
