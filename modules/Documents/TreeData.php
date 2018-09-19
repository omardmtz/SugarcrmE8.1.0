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
    $ret=array();
    $click_level=$params['TREE']['depth'];
    $subcat_id=$params['NODES'][$click_level]['id'];
    $cat_id=$params['NODES'][$click_level-1]['id'];
    $href=true;
    if (isset($params['TREE']['caller']) and $params['TREE']['caller']=='Documents' ) {
        $href=false;
    }
	$nodes=get_documents($cat_id,$subcat_id,$href);
	foreach ($nodes as $node) {
		$ret['nodes'][]=$node->get_definition();
	}
	$json = new JSON();
	$str=$json->encode($ret);
	return $str;
}

/*
 *  
 *
 */
 function get_category_nodes($href_string){
    $nodes=array();
    global $mod_strings;
    global $app_list_strings;
    $query="select distinct category_id, subcategory_id from documents where deleted=0 order by category_id, subcategory_id";
    $result=$GLOBALS['db']->query($query);
    $current_cat_id=null;
    $cat_node=null;
    while (($row=$GLOBALS['db']->fetchByAssoc($result))!= null) {

        if (empty($row['category_id'])) {
            $cat_id='null';
            $cat_name=$mod_strings['LBL_CAT_OR_SUBCAT_UNSPEC'];
        } else {
            $cat_id=$row['category_id'];
            $cat_name=$app_list_strings['document_category_dom'][$row['category_id']];
        }            
        if (empty($current_cat_id) or $current_cat_id != $cat_id) {
            $current_cat_id = $cat_id;
            if (!empty($cat_node)) $nodes[]=$cat_node;
            
            $cat_node = new Node($cat_id, $cat_name);
            $cat_node->set_property("href", $href_string);
            $cat_node->expanded = true;
            $cat_node->dynamic_load = false;
        } 

        if (empty($row['subcategory_id'])) {
            $subcat_id='null';
            $subcat_name=$mod_strings['LBL_CAT_OR_SUBCAT_UNSPEC'];
        } else {
            $subcat_id=$row['subcategory_id'];
            $subcat_name=$app_list_strings['document_subcategory_dom'][$row['subcategory_id']];
        }            
        $subcat_node = new Node($subcat_id, $subcat_name);
        $subcat_node->set_property("href", $href_string);
        $subcat_node->expanded = false;
        $subcat_node->dynamic_load = true;
        
        $cat_node->add_node($subcat_node);
    }    
    if (!empty($cat_node)) $nodes[]=$cat_node;

    return $nodes;
 }
 
function get_documents($cat_id, $subcat_id,$href=true) {
    $db = DBManagerFactory::getInstance();
    $builder = $db->getConnection()->createQueryBuilder();
    $builder->select('*')->from('documents')->where('deleted = 0');

    if ($cat_id != 'null') {
        $builder->andWhere('category_id = ' . $builder->createPositionalParameter($cat_id));
    } else {
        $builder->andWhere('category_id IS NULL');
    }

    if ($subcat_id != 'null') {
        $builder->andWhere('subcategory_id = ' . $builder->createPositionalParameter($subcat_id));
    } else {
        $builder->andWhere('subcategory_id IS NULL');
    }

    $stmt = $builder->execute();

    $nodes=array();
    $href_string = "javascript:select_document('doctree')";
    while ($row = $stmt->fetch()) {
        $node = new Node($row['id'], $row['document_name']);
        if ($href) {
            $node->set_property("href", $href_string);
        }
        $node->expanded = true;
        $node->dynamic_load = false;
        
        $nodes[]=$node;
    }
    return $nodes;
}
