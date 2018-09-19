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
require_once('vendor/ytree/Node.php');

//function returns an array of objects of Node type.
function get_node_data($params,$get_array=false) {
    $click_level=$params['TREE']['depth'];
    $parent_id=$params['NODES'][$click_level]['id'];
	$ret = array();
	
	$nodes=get_categories_and_products($parent_id);
	foreach ($nodes as $node) {
		$ret['nodes'][]=$node->get_definition();
	}
	$json = new JSON();
	$str=$json->encode($ret);
	return $str;
}

/*
 * Constructs the nodes give parent node id, if the parent node_id is null
 * top level nodes will be returned. function will return both product categories
 * and product.
 *
 * $open_nodes_ids is an hierachical list of nodes that should be open when the tree in rendered.
 * node at index 0 represents the topmost level node. This fuction calls itself recursively to build
 * open nodes.
 */
function get_categories_and_products($parent_id) {
    $href_string = "javascript:set_return('productcatalog')";
    $nodes=array();

    if ($parent_id=='' or empty($parent_id)) {
        $query="select id, name, 'category' type, list_order, 1 table_order from product_categories where (parent_id is null or parent_id='') and deleted=0";
        $query.=" union select id, name , 'product' type, NULL AS list_order, 2 table_order from product_templates where (category_id is null or category_id='') and deleted=0";
        
    } else {
        $query="select id, name, 'category' type, list_order, 1 table_order from product_categories where parent_id ='$parent_id' and deleted=0";
        $query.=" union select id, name , 'product' type, NULL AS list_order, 2 table_order from product_templates where category_id ='$parent_id' and deleted=0";
    }
    $query .= " order by table_order, list_order, name";

    $result=$GLOBALS['db']->query($query);

    // fetchByAssoc has been changed in version 7 and it does encoding of the string data.
    // for the treeview we do not encoding as it messes up the display of the folder labels,
    // hence we pass false as an additional parameter
    while (($row=$GLOBALS['db']->fetchByAssoc($result, false))!= null) {
        $node = new Node($row['id'], $row['name']); 
        $node->set_property("href", $href_string);
        $node->set_property("type", $row['type']);
        if ($row['type']=='product') {
            $node->expanded = false;
            $node->dynamic_load = false;
        } else {
            $node->expanded = false;
            $node->dynamic_load = true;        
        }
        $nodes[]=$node;
    }
    return $nodes;
}
