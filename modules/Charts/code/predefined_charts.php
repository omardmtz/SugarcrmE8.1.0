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

$chartsStrings = return_module_language($GLOBALS['current_language'], 'Charts');

$predefined_charts = array(
	'Chart_pipeline_by_sales_stage'=>
	array('type'=>'code','id'=>'Chart_pipeline_by_sales_stage','label'=>$chartsStrings['LBL_CHART_PIPELINE_BY_SALES_STAGE'],'chartType'=>'horizontal group by chart',),
	'Chart_lead_source_by_outcome'=>
	array('type'=>'code','id'=>'Chart_lead_source_by_outcome','label'=>$chartsStrings['LBL_CHART_LEAD_SOURCE_BY_OUTCOME'],'chartType'=>'horizontal group by chart',),
	'Chart_outcome_by_month'=>
	array('type'=>'code','id'=>'Chart_outcome_by_month','label'=>$chartsStrings['LBL_CHART_OUTCOME_BY_MONTH'],'chartType'=>'stacked group by chart',),
	'Chart_pipeline_by_lead_source'=>
	array('type'=>'code','id'=>'Chart_pipeline_by_lead_source','label'=>$chartsStrings['LBL_CHART_PIPELINE_BY_LEAD_SOURCE'],'chartType'=>'pie chart',),
	'Chart_my_pipeline_by_sales_stage'=>
	array('type'=>'code','id'=>'Chart_pipeline_by_sales_stage','label'=>$chartsStrings['LBL_CHART_MY_PIPELINE_BY_SALES_STAGE'],'chartType'=>'funnel chart',),
);