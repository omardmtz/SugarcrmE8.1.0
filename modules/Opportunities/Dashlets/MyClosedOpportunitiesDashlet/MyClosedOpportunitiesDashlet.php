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

// $Id: MyOpportunitiesDashlet.php 24647 2007-07-26 00:21:25Z awu $


class MyClosedOpportunitiesDashlet extends Dashlet 
{ 
	protected $total_opportunities;
	protected $total_opportunities_won;
	
	/**
	 * @see Dashlet::Dashlet()
	 */
	public function __construct($id, $def = null) 
	{
        global $current_user, $app_strings;
        parent::__construct($id);
        $this->isConfigurable = true;
        $this->isRefreshable = true;        

        if(empty($def['title'])) { 
            $this->title = translate('LBL_MY_CLOSED_OPPORTUNITIES', 'Opportunities'); 
        } 
        else {
            $this->title = $def['title'];
        }
        
        if(isset($def['autoRefresh'])) $this->autoRefresh = $def['autoRefresh'];
        
        $this->seedBean = BeanFactory::newBean('Opportunities');      

        $qry = "SELECT * from opportunities WHERE assigned_user_id = '" . $current_user->id . "' AND deleted=0";
		$result = $this->seedBean->db->query($this->seedBean->create_list_count_query($qry));	
		$row = $this->seedBean->db->fetchByAssoc($result);

		$this->total_opportunities = $row['c'];
        $qry = "SELECT * from opportunities WHERE assigned_user_id = '" . $current_user->id . "' AND sales_stage = '".Opportunity::STAGE_CLOSED_WON."' AND deleted=0";
		$result = $this->seedBean->db->query($this->seedBean->create_list_count_query($qry));	
		$row = $this->seedBean->db->fetchByAssoc($result);

		$this->total_opportunities_won = $row['c'];
    }
    
    /**
     * @param string $text
	 * @see Dashlet::display()
	 */
    public function display($text = '')
    {	
    	$ss = new Sugar_Smarty();
    	$ss->assign('lblTotalOpportunities', translate('LBL_TOTAL_OPPORTUNITIES', 'Opportunities'));
    	$ss->assign('lblClosedWonOpportunities', translate('LBL_CLOSED_WON_OPPORTUNITIES', 'Opportunities'));    	
    	
    	$ss->assign('total_opportunities', $this->total_opportunities);
    	$ss->assign('total_opportunities_won', $this->total_opportunities_won);    	
    	
        return parent::display($text)
            . $ss->fetch(
                'modules/Opportunities/Dashlets/MyClosedOpportunitiesDashlet/MyClosedOpportunitiesDashlet.tpl'
            );
    }
    
    /**
	 * @see Dashlet::displayOptions()
	 */
	public function displayOptions() 
    {
        $ss = new Sugar_Smarty();
        $ss->assign('titleLBL', translate('LBL_DASHLET_OPT_TITLE', 'Home'));
        $ss->assign('title', $this->title);
        $ss->assign('id', $this->id);
        $ss->assign('saveLBL', $GLOBALS['app_strings']['LBL_SAVE_BUTTON_LABEL']);
        if($this->isAutoRefreshable()) {
       		$ss->assign('isRefreshable', true);
			$ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
			$ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
			$ss->assign('autoRefreshSelect', $this->autoRefresh);
		}
        
		return $ss->fetch('modules/Opportunities/Dashlets/MyClosedOpportunitiesDashlet/MyClosedOpportunitiesDashletConfigure.tpl');        
    }

    /**
	 * @see Dashlet::saveOptions()
	 */
	public function saveOptions($req) 
    {
        $options = array();
        
        if ( isset($req['title']) ) {
            $options['title'] = $req['title'];
        }
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];
        
        return $options;
    }   
}
