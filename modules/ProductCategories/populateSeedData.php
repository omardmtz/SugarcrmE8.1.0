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

///include some fake products and categories as arrays
///for now we will just use the account and contact name info


//set parameters

$tree_depth = "3";		//how many levels deep is the tree
$tree_branches = "3";	//how many categories per level

$branch_leafs = "3";	//Products per category

//3 x 3 x 3 will produce 39 categories and 117 products		total nodes:156			
//3 x 5 x 5 will produce 155 categories and 775 products	total nodes:930			
//8 x 2 x 3 will produce 510 categories	and 1538 products	total nodes:2048		
//3 x 5 x 10 will product 155 catgories and 1550 products	total nodes:1705
//4 x 5 x 5 will produce 780 categories and 3900 products	total nodes:4680
//5 x 5 x 5 will product 3905 categories and 19525 products	total nodes:23430

$depth_flag = "1";

traverse_tree("", $depth_flag, $tree_depth, $tree_branches, $branch_leafs);

function traverse_tree($parent_id, $depth_flag, &$tree_depth, &$tree_branches, &$branch_leafs){
	//control how deep the tree goes
	$depth_flag = $depth_flag + 1;


	//loop through categories at each level
	for($i = 0; $i < $tree_branches; $i++){
	//echo "looping through level $i categories PARENT ID: $parent_id<br>";
		$last_id = create_category($parent_id);
		
		//loop through how many products per category
		for($j = 0; $j < $branch_leafs; $j++){
		
		
			create_product($last_id);
			
		//echo "looping through level $j products LAST ID: $last_id<br>";
		//end foreach create product
		}
		
		

		//echo "<BR>1. DEPTH FLAG: $depth_flag PRIOR TO checking depth flag value<BR>";
		if($depth_flag<="$tree_depth"){
		
			//echo "<BR>2. DEPTH FLAG: $depth_flag TREE DEPTH: $tree_depth<BR>";
		
			traverse_tree($last_id, $depth_flag, $tree_depth, $tree_branches, $branch_leafs);
		
			//echo "<BR> 3. DEPTH FLAG: $depth_flag AFTER recursive traverse <BR>";
			
		//end if to traverse deeper
		}
		
		
	//end foreach categories at each level
	}


//end function traverse_tree
}



function create_category($parent_id){
	global $sugar_demodata;
	$last_name_array = $sugar_demodata['last_name_array'];
	$last_name_count = count($sugar_demodata['last_name_array']) - 1;
	$last_name_max = $last_name_count - 1;
	$category = BeanFactory::newBean('ProductCategories');
	$category->name = $last_name_array[mt_rand(0,$last_name_max)] ." Widgets";
	$category->parent_id = $parent_id;
    $key = array_rand($sugar_demodata['users']);
    $category->assigned_user_id = $sugar_demodata['users'][$key]['id'];
	$category->save();
	$cat_id = $category->id;
	unset($category);
	return $cat_id;
	

//end function create_category
}


function create_product($category_id){
global $first_name_array;
global $company_name_array;
global $first_name_count;
global $company_name_count;
global $dollar_id;
global $tekkyware_id;
$first_name_max = $first_name_count - 1;

	$template = BeanFactory::newBean('ProductTemplates');
	$template->name = $first_name_array[mt_rand(0,$first_name_max)] ." Gadget";
	$template->tax_class = "Taxable";
	$template->manufacturer_id = $tekkyware_id;
	$template->currency_id = $dollar_id;
	$template->cost_price = 1500.00;
	$template->cost_usdollar = 1500.00;
	$template->list_price = 1888.20;
	$template->list_usdollar = 1888.20;
	$template->discount_price = 1799.95;
	$template->discount_usdollar = 1799.95;
	$template->pricing_formula = "IsList";
	$template->mft_part_num = $company_name_array[mt_rand(0,$company_name_count-1)].' '.mt_rand(1,1000000) ."XYZ987";
	$template->pricing_factor = "1";
	$template->status = "Available";
	$template->weight = 20.0;
	$template->date_available = "2004-10-15";
	$template->qty_in_stock = "72";
	$template->category_id = $category_id;
	$template->save();

	unset($template);

//end function create_product
}


?>