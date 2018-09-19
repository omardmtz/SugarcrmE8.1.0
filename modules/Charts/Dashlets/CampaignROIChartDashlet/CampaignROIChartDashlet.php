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



class CampaignROIChartDashlet extends DashletGenericChart 
{
    public $campaign_id;
    
    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Campaigns';
    
    /**
     * @see DashletGenericChart::displayOptions()
     */
    public function displayOptions() 
    {
        $this->getSeedBean()->disable_row_level_security = false;

        $campaigns = $this->getSeedBean()->get_full_list("","");
    	if ( $campaigns != null )
            foreach ($campaigns as $c)
                $this->_searchFields['campaign_id']['options'][$c->id] = $c->name;
    	else 
            $this->_searchFields['campaign_id']['options'] = array();
            
        return parent::displayOptions();
    }   
    
    /**
     * @see DashletGenericChart::display()
     */
    public function display()
    {

        $roi_chart = new campaign_charts();
        $chartStr = $roi_chart->campaign_response_roi(
            $GLOBALS['app_list_strings']['roi_type_dom'],
            $GLOBALS['app_list_strings']['roi_type_dom'],
            $this->campaign_id[0],null,true,true,true,$this->id);
        
		$returnStr = $chartStr;
		
        return $this->getTitle('<div align="center"></div>') . '<div align="center">' . $returnStr . '</div>'. $this->processAutoRefresh();
    }
}
