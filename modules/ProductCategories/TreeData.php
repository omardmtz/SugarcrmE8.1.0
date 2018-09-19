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
	$nodes=get_product_categories($parent_id);
	foreach ($nodes as $node) {
		$ret['nodes'][]=$node->get_definition();
	}
	$json = new JSON();
	$str=$json->encode($ret);
	return $str;
}

/*
 * Constructs the nodes give parent node id, if the parent node_id is null
 * top level nodes will be returned.
 *
 * $open_nodes_ids is an hierachical list of nodes that should be open when the tree in rendered.
 * node at index 0 represents the topmost level node. This fuction calls itself recursively to build
 * open nodes.
 */
function get_product_categories($parent_id,$open_nodes_ids=array()) {
    $href_string = "javascript:set_return('productcategories')";
    reset($open_nodes_ids);
    $nodes=array();
    if ($parent_id=='') {
        $query="select * from product_categories where (parent_id is null or parent_id='') and deleted=0 order by list_order";
    } else {
        $query="select * from product_categories where parent_id ='$parent_id' and deleted=0 order by list_order";
    }
    $result=$GLOBALS['db']->query($query);
    while (($row=$GLOBALS['db']->fetchByAssoc($result))!= null) {
        $node = new Node($row['id'], $row['name']);
        $node->set_property("href", $href_string);

    	if (count($open_nodes_ids) > 0 and $row['id']==current($open_nodes_ids)) {
            $node->expanded = true;
            $node->dynamic_load = false;
            $current_id=current($open_nodes_ids);
            array_shift($open_nodes_ids);
            $child_nodes=get_product_categories($current_id,$open_nodes_ids);
            //add all returned node to current node.
            foreach ($child_nodes as $child_node) {
            	$node->add_node($child_node);
            }
    	} else {
            $node->expanded = false;
            $node->dynamic_load = true;
    	}
        $nodes[]=$node;
    }
    return $nodes;
}
