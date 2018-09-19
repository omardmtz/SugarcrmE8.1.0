<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
//node the tree view. no need to add a root node,a invisible root node will be added to the
//tree by default.
//predefined properties for a node are  id, label, target and href. label is required property.
//set the target and href property for cases where target is an iframe.
class ExtNode {
	//predefined node properties.
	var $_label;		//this is the only required property for a node.
	var $_href;
	var $id;
	
	//ad-hoc collection of node properties
	var $_properties=array();
	//collection of parmeter properties;
	var $_params=array();
	
	//sent to the javascript.
	var $uid; 		//unique id for the node.

	var $nodes=array();
	var $dynamic_load=false; //false means child records are pre-loaded.
	var $dynamicloadfunction='loadDataForNode'; //default script to load node data (children)
	var $expanded=false;  //show node expanded during initial load.
	 
    public function __construct($id, $label, $show_expanded = true)
    {
		$this->_label = $label;
		$this->id = $id;
		$this->_properties['text'] = htmlspecialchars_decode($label, ENT_QUOTES);
		$this->uid=microtime();
		$this->set_property('id',$id);
        $this->expanded = $show_expanded;
	}

	//properties set here will be accessible via
	//node.data object in javascript.
	//users can add a collection of paramaters that will
	//be passed to objects responding to tree events
 	function set_property($name, $value, $is_param=false) {
 		if(!empty($name) && ($value === 0 || !empty($value))) {
 			if ($is_param==false) {
 				$this->_properties[$name]=$value;
 			} else {
 				$this->_params[$name]=$value;
 			}	
 		}
 	}
 	
	//add a child node.
 	function add_node($node) {
  		$this->nodes[$node->uid]=$node;
  	}

	//return definition of the node. the definition is a multi-dimension array and has 3 parts.
	// data-> definition of the current node.
	// attributes=> collection of additional attributes such as style class etc..
	// nodes: definition of children nodes. 	
 	function get_definition() {
 		$ret=array();

 		$ret = $this->_properties; 
 		if (!empty($this->_params)) {
 			$ret[] = $this->_params;	
 		}		
 		
		$ret['dynamicload']=$this->dynamic_load;
		$ret['dynamicloadfunction']=$this->dynamicloadfunction;
		$ret['expanded']=$this->expanded;
		$ret['children'] = array();
		$ret['type'] = 1;
						 	
 		foreach ($this->nodes as $node) {
 			$ret['children'][]=$node->get_definition();
 		}
		//$ret['leaf'] = empty($ret['children']);
		return $ret;		
 	}
}
