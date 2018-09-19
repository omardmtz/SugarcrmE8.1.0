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

* Description:  Includes the functions for Customer module specific charts.
********************************************************************************/






class campaign_charts {
	/**
	* Creates opportunity pipeline image as a VERTICAL accumlated bar graph for multiple users.
	* param $datax- the month data to display in the x-axis
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/

	function campaign_response_by_activity_type($datay= array(),$targets=array(),$campaign_id, $cache_file_name='a_file', $refresh=false, $marketing_id='') {
		global $app_strings, $mod_strings, $charset, $lang, $barChartColors,$app_list_strings;
		$sugarChart = SugarChartFactory::getInstance('','Reports');
		$xmlFile = $sugarChart->getXMLFileName($campaign_id);

		if (!file_exists($xmlFile) || $refresh == true) {
			$GLOBALS['log']->debug("datay is:");
			$GLOBALS['log']->debug($datay);
			$GLOBALS['log']->debug("user_id is: ");
			$GLOBALS['log']->debug("cache_file_name is: $xmlFile");

			$focus = BeanFactory::newBean('Campaigns');

			$query = "SELECT activity_type,target_type, count(*) hits ";
			$query.= " FROM campaign_log ";
            $query.= " WHERE campaign_id = ";
            $query .= $focus->db->quoted($campaign_id);
            $query .= " AND archived=0 AND deleted=0 AND target_id IS NOT NULL";

            //if $marketing id is specified, then lets filter the chart by the value
            if (!empty($marketing_id)){
                $query.= " AND marketing_id = " . $focus->db->quoted($marketing_id);
            }

			$query.= " GROUP BY  activity_type, target_type";
			$query.= " ORDER BY  activity_type, target_type";
			$result = $focus->db->query($query);
			//$camp_data=$focus->db->fetchByAssoc($result);
			$camp_data = array();
			$leadSourceArr = array();
			$total=0;
			$total_targeted=0;
			$rowTotalArr = array();
			$rowTotalArr[] = 0;
			while($row = $focus->db->fetchByAssoc($result))
			{
				if(!isset($leadSourceArr[$row['activity_type']]['row_total'])) {
					$leadSourceArr[$row['activity_type']]['row_total']=0;
				}

				$leadSourceArr[$row['activity_type']][$row['target_type']]['hits'][] = $row['hits'];
				$leadSourceArr[$row['activity_type']][$row['target_type']]['total'][] = $row['hits'];
				$leadSourceArr[$row['activity_type']]['outcome'][$row['target_type']]=$row['target_type'];
				$leadSourceArr[$row['activity_type']]['row_total'] += $row['hits'];

				if (!isset($leadSourceArr['all_activities'][$row['target_type']])) {
					$leadSourceArr['all_activities'][$row['target_type']]=array('total'=>0);
				}

				$leadSourceArr['all_activities'][$row['target_type']]['total'] += $row['hits'];

				$total += $row['hits'];
				if ($row['activity_type'] =='targeted') {
					$targeted[$row['target_type']]=$row['hits'];
					$total_targeted+=$row['hits'];
				}
			}

			foreach ($datay as $key=>$translation) {
				if ($key == '') {
					//$key = $mod_strings['NTC_NO_LEGENDS'];
					$key = 'None';
					$translation = $mod_strings['NTC_NO_LEGENDS'];
				}
				if(!isset($leadSourceArr[$key])){
					$leadSourceArr[$key] = $key;
				}


				if(is_array($leadSourceArr[$key]) && isset($leadSourceArr[$key]['row_total'])){$rowTotalArr[]=$leadSourceArr[$key]['row_total'];}
				if(is_array($leadSourceArr[$key]) && isset($leadSourceArr[$key]['row_total']) && $leadSourceArr[$key]['row_total']>100){
					$leadSourceArr[$key]['row_total'] = round($leadSourceArr[$key]['row_total']);
				}
				$camp_data[$translation] = array();
					foreach ($targets as $outcome=>$outcome_translation){
						//create alternate text.
                        $alttext = ' ';
                        if(isset($targeted) && isset($targeted[$outcome])&& !empty($targeted[$outcome])){
						$alttext=$targets[$outcome].': '.$mod_strings['LBL_TARGETED'].' '.$targeted[$outcome]. ', '.$mod_strings['LBL_TOTAL_TARGETED'].' '. $total_targeted. ".";
                        }
						if ($key != 'targeted'){
							$hits =  (isset($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]['hits'])) ? array_sum($leadSourceArr[$key][$outcome]['hits']) : 0;
							$alttext.=" $translation ".$hits;
						}
						$count = (isset($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]['total'])) ? array_sum($leadSourceArr[$key][$outcome]['total']) : 0;
						$camp_data[$translation][$outcome] =
							array(
							"numerical_value" => $count,
							"group_text" => $translation,
							"group_key" => "",
							"count" => "{$count}",
							"group_label" => $alttext,
							"numerical_label" => "Hits",
							"numerical_key" => "hits",
							"module" => 'Campaigns',
     						"group_base_text" => $outcome,
     						"link" => $key
							);
					}

			}

            // Since this isn't a standard report chart (with report defs), set the group_by manually so the chart bars show properly
            $sugarChart->group_by = array('activity_type', 'target_type');

            if ($camp_data) {
                $sugarChart->setData($camp_data);
            } else {
                $sugarChart->setData(array());
            }

            $sugarChart->setProperties(
                $mod_strings['LBL_CAMPAIGN_RESPONSE_BY_RECIPIENT_ACTIVITY'],
                "",
                'horizontal group by chart',
                'on', // legend
                'value', // labels
                'off', // print
                false, // thousands
                'Campaigns',
                'Campaign Response Status'
            );

			$sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
		}

		$width = '100%';
		$return = '';
		$return .= $sugarChart->display($campaign_id, $xmlFile, $width, '480',"");

		return $return;
	}

	//campaign roi computations.
	function campaign_response_roi($datay= array(),$targets=array(),$campaign_id, $cache_file_name='a_file', $refresh=false,$marketing_id='',$is_dashlet=false,$dashlet_id='') {
		global $app_strings,$mod_strings, $current_module_strings, $charset, $lang, $app_list_strings, $current_language,$sugar_config;

		$not_empty = false;

		if ($is_dashlet){
			$mod_strings = return_module_language($current_language, 'Campaigns');
		}

		if (!file_exists($cache_file_name) || $refresh == true) {
			$GLOBALS['log']->debug("datay is:");
			$GLOBALS['log']->debug($datay);
			$GLOBALS['log']->debug("user_id is: ");
			$GLOBALS['log']->debug("cache_file_name is: $cache_file_name");

			$focus = BeanFactory::getBean('Campaigns', $campaign_id);
			$opp_count=0;
			$opp_query  = "select count(*) opp_count,sum(" . db_convert("amount_usdollar","IFNULL",array(0)).")  total_value";
            $opp_query .= " from opportunities";
            $opp_query .= " where campaign_id=";
            $opp_query .= $focus->db->quoted($campaign_id);
            $opp_query .= " and sales_stage='Prospecting'";
            $opp_query .= " and deleted=0";

            $opp_result=$focus->db->query($opp_query);
            $opp_data=$focus->db->fetchByAssoc($opp_result);
//            if (empty($opp_data['opp_count'])) $opp_data['opp_count']=0;
            if (empty($opp_data['total_value'])) $opp_data['total_value']=0;

            //report query
			$opp_query1  = "select SUM(opp.amount) as revenue";
            $opp_query1 .= " from opportunities opp";
            $opp_query1 .= " right join campaigns camp on camp.id = opp.campaign_id";
            $opp_query1 .= " where opp.sales_status = '".Opportunity::STAGE_CLOSED_WON;
            $opp_query1 .= "' and camp.id=";
            $opp_query1 .= $focus->db->quoted($campaign_id);
            $opp_query1 .= " and opp.deleted=0";
            $opp_query1 .= " group by camp.name";

            $opp_result1=$focus->db->query($opp_query1);
            $opp_data1=$focus->db->fetchByAssoc($opp_result1);

			//if (empty($opp_data1[]))
            if (empty($opp_data1['revenue'])){
				$opp_data1[$mod_strings['LBL_ROI_CHART_REVENUE']] = 0;
                unset($opp_data1['revenue']);
            }else{
                $opp_data1[$mod_strings['LBL_ROI_CHART_REVENUE']] = $opp_data1['revenue'];
                unset($opp_data1['revenue']);
				$not_empty = true;
            }

			$camp_query1  = "select camp.name, SUM(camp.actual_cost) as investment,SUM(camp.budget) as budget,SUM(camp.expected_revenue) as expected_revenue";
            $camp_query1 .= " from campaigns camp";
            $camp_query1 .= " where camp.id=";
            $camp_query1 .= $focus->db->quoted($campaign_id);
            $camp_query1 .= " group by camp.name";

            $camp_result1=$focus->db->query($camp_query1);
            $camp_data1=$focus->db->fetchByAssoc($camp_result1);
            //query needs to be lowercase, but array keys need to be propercased, as these are used in
            //chart to display legend

			if (empty($camp_data1['investment']))
				$camp_data1['investment'] = 0;
			else
				$not_empty = true;
			if (empty($camp_data1['budget']))
				$camp_data1['budget'] = 0;
			else
				$not_empty = true;
            if (empty($camp_data1['expected_revenue']))
            	$camp_data1['expected_revenue'] = 0;
			else
				$not_empty = true;

            $opp_data1[$mod_strings['LBL_ROI_CHART_INVESTMENT']]=$camp_data1['investment'];
	        $opp_data1[$mod_strings['LBL_ROI_CHART_BUDGET']]=$camp_data1['budget'];
	        $opp_data1[$mod_strings['LBL_ROI_CHART_EXPECTED_REVENUE']]=$camp_data1['expected_revenue'];


			$query = "SELECT activity_type,target_type, count(*) hits ";
			$query.= " FROM campaign_log ";
            $query.= " WHERE campaign_id = ";
            $query .= $focus->db->quoted($campaign_id);
            $query .= " AND archived=0 AND deleted=0";
            //if $marketing id is specified, then lets filter the chart by the value
            if (!empty($marketing_id)){
                $query.= " AND marketing_id = ";
                $query .= $focus->db->quoted($marketing_id);
            }
			$query.= " GROUP BY  activity_type, target_type";
			$query.= " ORDER BY  activity_type, target_type";
			$result = $focus->db->query($query);

			$leadSourceArr = array();
			$total=0;
			$total_targeted=0;

		}

		global $current_user;
		$user_id = $current_user->id;


		$width = '100%';

		$return = '';
		if (!$is_dashlet){
			$return .= '<br />';
		}


        $currency_id = $focus->currency_id;
        $currency_symbol = $sugar_config['default_currency_symbol'];
        if(!empty($currency_id)){

            $cur = BeanFactory::getBean('Currencies', $currency_id);
            $currency_symbol = $cur->symbol;
        }


		$sugarChart = SugarChartFactory::getInstance();
		$sugarChart->is_currency = true;
        $sugarChart->currency_symbol = $currency_symbol;

        // campaign charts should not convert currency values
        // see SugarChart::convertCurrency()
        $sugarChart->div = 1;

		if ($not_empty)
	 		$sugarChart->setData($opp_data1);
		else
			$sugarChart->setData(array());

        $sugarChart->setProperties(
            $mod_strings['LBL_CAMPAIGN_RETURN_ON_INVESTMENT'], // title
            $mod_strings['LBL_AMOUNT_IN'].$currency_symbol, // subtitle
            'bar chart',
            'on', // legend
            'value', // labels
            'off', // print
            false, // thousands
            'Campaigns',
            'Campaign ROI chart'
        );

        $sugarChart->setDisplayProperty('xDataType', 'ordinal');
        $sugarChart->setDisplayProperty('yDataType', 'currency');

    	if (!$is_dashlet){
			$xmlFile = $sugarChart->getXMLFileName('roi_details_chart');
			$sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
			$return .= $sugarChart->display('roi_details_chart', $xmlFile, $width, '480');
		}
		else{
			$xmlFile = $sugarChart->getXMLFileName($dashlet_id);
			$sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
			$return .= $sugarChart->display($dashlet_id, $xmlFile, $width, '480');
		}

		return $return;
	}
}// end charts class
?>
