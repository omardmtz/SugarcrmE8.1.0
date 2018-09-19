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

 // $Id: PipelineBySalesStageDashlet.php 28775 2007-10-24 00:26:22 +0000 (Wed, 24 Oct 2007) awu $



class PipelineBySalesStageDashlet extends DashletGenericChart
{
    public $pbss_date_start;
    public $pbss_date_end;
    public $pbss_sales_stages = array();
    public $pbss_chart_type = 'fun';

    /**
     * @see DashletGenericChart::$_seedName
     */
    protected $_seedName = 'Opportunities';

    /**
     * @see DashletGenericChart::__construct()
     */
    public function __construct(
        $id,
        array $options = null
        )
    {
        global $timedate;

        if(empty($options['pbss_date_start']))
            $options['pbss_date_start'] = $timedate->nowDbDate();

        if(empty($options['pbss_date_end']))
            $options['pbss_date_end'] = $timedate->asDbDate($timedate->getNow()->modify("+6 months"));

        if(empty($options['title']))
        	$options['title'] = translate('LBL_PIPELINE_FORM_TITLE', 'Home');

        parent::__construct($id,$options);
    }

    /**
     * @see DashletGenericChart::displayOptions()
     */
    public function displayOptions()
    {
        global $app_list_strings;

        if (!empty($this->pbss_sales_stages) && count($this->pbss_sales_stages) > 0)
            foreach ($this->pbss_sales_stages as $key)
                $selected_datax[] = $key;
        else
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);

        $this->_searchFields['pbss_sales_stages']['options'] = $app_list_strings['sales_stage_dom'];
        $this->_searchFields['pbss_sales_stages']['input_name0'] = $selected_datax;
        $this->_searchFields['pbss_chart_type']['options'] = $app_list_strings['pipeline_chart_dom'];

        return parent::displayOptions();
    }

    /**
     * @see DashletGenericChart::display()
     */
    public function display()
    {
        global $current_user, $sugar_config;

        $sugarChart = SugarChartFactory::getInstance();
        $sugarChart->base_url = array(
            'module' => 'Opportunities',
            'action' => 'index',
            'query' => 'true',
            'searchFormTab' => 'advanced_search',
            );
        //fixing bug #27097: The opportunity list is not correct after drill-down
        //should send to url additional params: start range value and end range value
        $sugarChart->url_params = array('start_range_date_closed' => $this->pbss_date_start,
                                        'end_range_date_closed' => $this->pbss_date_end);
        $sugarChart->group_by = $this->constructGroupBy();
        $sugarChart->setData($this->getChartData($this->constructQuery()));
        $sugarChart->is_currency = true;
        $sugarChart->thousands_symbol = translate('LBL_OPP_THOUSANDS', 'Charts');

        $currency_symbol = $sugar_config['default_currency_symbol'];
        if ($current_user->getPreference('currency')){

            $currency = BeanFactory::getBean('Currencies', $current_user->getPreference('currency'));
            $currency_symbol = $currency->symbol;
        }
        $subtitle = translate('LBL_OPP_SIZE', 'Charts') . " " . $currency_symbol . "1" . translate('LBL_OPP_THOUSANDS', 'Charts');

        $pipeline_total_string = translate('LBL_TOTAL_PIPELINE', 'Charts') . $sugarChart->currency_symbol . format_number($sugarChart->getTotal(), 0, 0, array('convert'=>true)) . $sugarChart->thousands_symbol;
		if ($this->pbss_chart_type == 'hbar')
            $sugarChart->setProperties($pipeline_total_string, $subtitle, 'horizontal group by chart');
		else
            $sugarChart->setProperties($pipeline_total_string, $subtitle, 'funnel chart 3D');

        $xmlFile = $sugarChart->getXMLFileName($this->id);
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());

        return $this->getTitle('') . '<div align="center">' . $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . '</div>'. $this->processAutoRefresh();
    }

	/**
     * awu: Bug 16794 - this function is a hack to get the correct sales stage order until
     * i can clean it up later
     *
     * @param  $query string
     * @return array
     */
    private function getChartData(
        $query
        )
    {
    	global $app_list_strings, $db;

    	$data = array();
    	$temp_data = array();
    	$selected_datax = array();

    	$user_sales_stage = $this->pbss_sales_stages;
        $tempx = $user_sales_stage;

        //set $datax using selected sales stage keys
        if (count($tempx) > 0) {
            foreach ($tempx as $key) {
                $datax[$key] = $app_list_strings['sales_stage_dom'][$key];
                $selected_datax[] = $key;
            }
        }
        else {
            $datax = $app_list_strings['sales_stage_dom'];
            $selected_datax = array_keys($app_list_strings['sales_stage_dom']);
        }

        $result = $db->query($query);
        while($row = $db->fetchByAssoc($result, false))
        	$temp_data[] = $row;

		// reorder and set the array based on the order of selected_datax
        foreach($selected_datax as $sales_stage){
        	foreach($temp_data as $key => $value){
        		if ($value['sales_stage'] == $sales_stage){
        			$value['sales_stage'] = $app_list_strings['sales_stage_dom'][$value['sales_stage']];
        			$value['key'] = $sales_stage;
        			$value['value'] = $value['sales_stage'];
        			$data[] = $value;
        			unset($temp_data[$key]);
        		}
        	}
        }
        return $data;
    }

    /**
     * @see DashletGenericChart::constructQuery()
     */
    protected function constructQuery()
    {
        $query = "  SELECT opportunities.sales_stage,
                        users.user_name,
                        opportunities.assigned_user_id,
                        count(*) AS opp_count,
                        sum(amount_usdollar/1000) AS total
                    FROM users,opportunities  ";
        $this->getSeedBean()->add_team_security_where_clause($query);
        $query .= " WHERE opportunities.date_closed >= ". db_convert("'".$this->pbss_date_start."'",'date').
                        " AND opportunities.date_closed <= ".db_convert("'".$this->pbss_date_end."'",'date') .
                        " AND opportunities.assigned_user_id = users.id  AND opportunities.deleted=0 ";
        if ( count($this->pbss_sales_stages) > 0 )
            $query .= " AND opportunities.sales_stage IN ('" . implode("','",$this->pbss_sales_stages) . "') ";
        $query .= " GROUP BY opportunities.sales_stage ,users.user_name,opportunities.assigned_user_id";

        return $query;
    }

    /**
     * @see DashletGenericChart::constructGroupBy()
     */
    protected function constructGroupBy()
    {
       return array(
           'sales_stage',
           'user_name',
           );
    }
}
