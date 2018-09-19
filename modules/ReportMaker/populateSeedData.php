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






//Create new Custom Queries
$query_object1 = BeanFactory::newBean('CustomQueries');
$query_object1->name = "Opportunity Query 1";
$query_object1->description = "Opportunities by Type";
$query_object1->query_locked = "off";
$query_object1->team_id = 1;

$m_closed = $query_object1->db->convert('opportunities.date_closed', 'month');
$today = $query_object1->db->convert('', 'today');
$m_date[0] = $query_object1->db->convert($today, 'month');
for($i=1;$i<6;$i++)
{
    $m_date[$i] = $query_object1->db->convert($query_object1->db->convert($today, 'add_date', array($i, "month")), 'month');
}
$m_date5 = $query_object1->db->convert($today, 'add_date', array(5, "month"));

	$query_object1->custom_query = "(
SELECT
 'New Business' \"Opportunity Type\",
sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
SUM(opportunities.amount_usdollar) AS 'Total Revenue'
FROM opportunities
 LEFT JOIN accounts_opportunities
ON opportunities.id=accounts_opportunities.opportunity_id
LEFT JOIN accounts
ON accounts_opportunities.account_id=accounts.id
WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today AND opportunities.opportunity_type = 'New Business'
) UNION (
SELECT
 'Existing Business' \"Opportunity Type\",
sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
SUM(opportunities.amount_usdollar) AS 'Total Revenue'
FROM opportunities
 LEFT JOIN accounts_opportunities
ON opportunities.id=accounts_opportunities.opportunity_id
LEFT JOIN accounts
ON accounts_opportunities.account_id=accounts.id
WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today AND opportunities.opportunity_type = 'Existing Business'
) UNION (
SELECT
 'Total Revenue' \"Opportunity Type\",
sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
SUM(opportunities.amount_usdollar) AS 'Total Revenue'
FROM opportunities
 LEFT JOIN accounts_opportunities
ON opportunities.id=accounts_opportunities.opportunity_id
LEFT JOIN accounts
ON accounts_opportunities.account_id=accounts.id
WHERE opportunities.date_closed <= $m_date5 AND  opportunities.date_closed >= $today
)";
$query_object1->save();


$query_object2 = BeanFactory::newBean('CustomQueries');
$query_object2->name = "Opportunity Query 2";
$query_object2->description = "Opportunities by Account";
$query_object2->query_locked = "off";
$query_object2->team_id = 1;

$query_object2->custom_query = "SELECT
	accounts.name \"Account Name\",
	sum( case when $m_closed = $m_date[0] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}0\",
	sum( case when $m_closed = $m_date[1] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}1\",
	sum( case when $m_closed = $m_date[2] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}2\",
	sum( case when $m_closed = $m_date[3] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}3\",
	sum( case when $m_closed = $m_date[4] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}4\",
	sum( case when $m_closed = $m_date[5] then opportunities.amount_usdollar else 0 end ) \"{sc}0{sc}5\",
	SUM(opportunities.amount_usdollar) AS \"Total Revenue\"
	FROM opportunities
	 LEFT JOIN accounts_opportunities
	ON opportunities.id=accounts_opportunities.opportunity_id
	LEFT JOIN accounts
	ON accounts_opportunities.account_id=accounts.id
	WHERE opportunities.date_closed <= $m_date5
	AND opportunities.date_closed >= $today
	GROUP BY accounts.id, accounts.name ORDER BY accounts.name
	";
$query_object2->save();

$query_id1 = $query_object1->id;
$query_id2 = $query_object2->id;

//Create new Report
$report_object = BeanFactory::newBean('ReportMaker');
$report_object->name ="6 month Sales Pipeline Report";
$report_object->title ="6 month Sales Pipeline Report";
$report_object->description ="Opportunities over the next 6 months broken down by month and type";
$report_object->report_align = "center";
$report_object->team_id = 1;
$report_object->save();

$report_id = $report_object->id;



//Create the data sets for the two custom queries

$format_object = BeanFactory::newBean('DataSets');

$format_object->name = "Opportunity Data Set 1";
$format_object->description = "This is where you can change the look and feel of the custom query";
$format_object->report_id = $report_id;
$format_object->query_id = $query_id1;
$format_object->list_order_y = 0;
$format_object->exportable = "on";
$format_object->header = "on";
$format_object->table_width = 100;
$format_object->font_size = "Default";
$format_object->output_default = "table";
$format_object->prespace_y = "off";
$format_object->use_prev_header = "off";
$format_object->table_width_type = "%";
$format_object->custom_layout = "Enabled";
$format_object->team_id = 1;

$format_object->header_back_color = "blue";
$format_object->body_back_color = "white";
$format_object->header_text_color = "white";
$format_object->body_text_color = "blue";




/////////////Second Data Set

$format_object2 = BeanFactory::newBean('DataSets');

$format_object2->name = "Opportunity Data Set 2";
$format_object2->description = "This query will be stacked below the first query in the report";
$format_object2->report_id = $report_id;
$format_object2->query_id = $query_id2;
$format_object2->list_order_y = 1;
$format_object2->exportable = "on";
$format_object2->header = "on";
$format_object2->table_width = 100;
$format_object2->font_size = "Default";
$format_object2->output_default = "table";
$format_object2->prespace_y = "on";
$format_object2->use_prev_header = "on";
$format_object2->table_width_type = "%";
$format_object2->custom_layout = "Enabled";
$format_object2->team_id = 1;


$format_object->save();
$format_object->enable_custom_layout();



$format_object2->save();
$format_object2->enable_custom_layout();



///////////////Get the attribute metadata ready///////
$start_body_array = array(
'display_type' =>'Normal',
'attribute_type' =>'Body',
'font_size' =>'Default',
'cell_size' =>'250',
'size_type' =>'px',
'wrap' =>'off',
'style' =>'normal',
'format_type' =>'Text',
);

$scalar_head_array = array(
'display_type' =>'Scalar',
'attribute_type' =>'Head',
'font_size' =>'Default',
'wrap' =>'off',
'style' =>'normal',
'format_type' =>'Month',
);


$scalar_body_array = array(
'display_type' =>'Normal',
'attribute_type' =>'Body',
'font_size' =>'Default',
'size_type' =>'px',
'wrap' =>'off',
'style' =>'normal',
'format_type' =>'Accounting',
);


//Populate thet dataset_attribute


	$layout_id = $format_object->get_layout_id_from_parent_value("Opportunity Type");
	$body_object = new DataSet_Attribute();
	$body_object->parent_id = $layout_id;
	foreach($start_body_array as $key => $value){
		$body_object->$key = $value;
	}
	$body_object->save();

////Fill in attributes for all the scalar columns
	for ($i = 0; $i <= 5; $i++) {

		$layout_id = $format_object->get_layout_id_from_parent_value("{sc}0{sc}".$i."");
		$body_object = new DataSet_Attribute();
		$body_object->parent_id = $layout_id;
		foreach($scalar_body_array as $key => $value){
			$body_object->$key = $value;
		}
		$body_object->save();
		$head_object = new DataSet_Attribute();
		$head_object->parent_id = $layout_id;
		foreach($scalar_head_array as $key => $value){
			$head_object->$key = $value;
		}
		$head_object->save();
	//end the for loop on scalar
	}

////Fill in attributes for all the scalar columns
	for ($i = 0; $i <= 5; $i++) {

		$layout_id = $format_object2->get_layout_id_from_parent_value("{sc}0{sc}".$i."");
		$body_object = new DataSet_Attribute();
		$body_object->parent_id = $layout_id;
		foreach($scalar_body_array as $key => $value){
			$body_object->$key = $value;
		}
		$body_object->save();
		$head_object = new DataSet_Attribute();
		$head_object->parent_id = $layout_id;
		foreach($scalar_head_array as $key => $value){
			$head_object->$key = $value;
		}
		$head_object->save();
	//end the for loop on scalar
	}


//////////////////Fill the Total Revenue Columns

		$layout_id = $format_object->get_layout_id_from_parent_value("Total Revenue");
		$body_object = new DataSet_Attribute();
		$body_object->parent_id = $layout_id;
		foreach($scalar_body_array as $key => $value){
			$body_object->$key = $value;
		}
		$body_object->save();

		$layout_id = $format_object2->get_layout_id_from_parent_value("Total Revenue");
		$body_object = new DataSet_Attribute();
		$body_object->parent_id = $layout_id;
		foreach($scalar_body_array as $key => $value){
			$body_object->$key = $value;
		}
		$body_object->save();


?>
