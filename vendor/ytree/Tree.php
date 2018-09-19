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
/*usage: initialize the tree, add nodes, generate header for required files inclusion.
 *  	  generate function that has tree data and script to convert data into tree init.
 *	      generate call to tree init function.
 *		  subsequent tree data calls will be served by the node class.  
 *		  tree view by default make ajax based calls for all requests.
 */
//require_once('include/entryPoint.php');

require_once ('vendor/ytree/Tree.php');
require_once ('include/JSON.php');

class Tree {
  var $tree_style='vendor/ytree/TreeView/css/folders/tree.css';	
  var $_header_files=array(	
		'include/javascript/yui/build/treeview/treeview.js',
        'vendor/ytree/treeutil.js',
      );
					 
  var $_debug_window=false;
  var $_debug_div_name='debug_tree';
  var $_name;
  var $_nodes=array();
  var $json;
  //collection of parmeter properties;
  var $_params=array();
  				   
    public function __construct($name)
    {
		$this->_name=$name;
		$this->json=new JSON();  
  }
  
  //optionally add json.js, required for making AJAX Calls. 
  function include_json_reference($reference=null) {
    // if (empty($reference)) {
    //  $this->_header_files[]='include/JSON.js';
    // } else {
    //  $this->_header_files[]=$reference;
    // }
  }
           
  function add_node($node) {
  		$this->_nodes[$node->uid]=$node;
  }
  
// returns html for including necessary javascript files.
  function generate_header() {
    $ret="<link rel='stylesheet' href='{$this->tree_style}'>\n";
   	foreach ($this->_header_files as $filename) {
   		$ret.="<script language='JavaScript' src='" . getJSPath($filename) . "'></script>\n";	
   	}
	return $ret;
  }
  
//properties set here will be accessible from
//the tree's name space..
  function set_param($name, $value) {
  	if (!empty($name) && !empty($value)) {
 		$this->_params[$name]=$value;
 	}
  }
 	  
  function generate_nodes_array($scriptTags = true) {
	global $sugar_config;
	$node=null;
	$ret=array();
	foreach ($this->_nodes as $node ) {
		$ret['nodes'][]=$node->get_definition();
	}  	
	
	//todo removed site_url setting from here.
	//todo make these variables unique.	
  	$tree_data="var TREE_DATA= " . $this->json->encode($ret) . ";\n";
  	$tree_data.="var param= " . $this->json->encode($this->_params) . ";\n";  	

  	$tree_data.="var mytree;\n";
  	$tree_data.="treeinit(mytree,TREE_DATA,'{$this->_name}',param);\n";
  	if($scriptTags) return '<script>'.$tree_data.'</script>';
    else return $tree_data;
  }
	
	
	/**
	 * Generates the javascript node arrays without calling treeinit().  Also generates a callback function that can be
	 * easily called to instatiate the treeview object onload().
	 * 
	 * IE6/7 will throw an "Operation Aborted" error when calling certain types of scripts before the page is fully
	 * loaded.  The workaround is to move the init() call to the onload handler.  See: http://www.viavirtualearth.
	 * com/wiki/DeferScript.ashx
	 * 
	 * @param bool insertScriptTags Flag to add <script> tags
	 * @param string customInitFunction Defaults to "onloadTreeInit"
	 * @return string
	 */
	function generateNodesNoInit($insertScriptTags=true, $customInitFunction="") {
		$node = null;
		$ret = array();
		
		$initFunction = (empty($customInitFunction)) ? 'treeinit' : $customInitFunction;
		
		foreach($this->_nodes as $node) {
			$ret['nodes'][] = $node->get_definition();
		}
		
		$treeData  = "var TREE_DATA = ".$this->json->encode($ret).";\n";
		$treeData .= "var param = ".$this->json->encode($this->_params).";\n";
		$treeData .= "var mytree;\n";
		$treeData .= "function onloadTreeinit() { {$initFunction}(mytree,TREE_DATA,'{$this->_name}',param); }\n";
		
		if($insertScriptTags) {
			$treeData = "<script type='text/javascript' language='javascript'>{$treeData}</script>";
		}
		
		return $treeData;
	}
	
	function generateNodesRaw() {
		$node = null;
		$ret = array();
		$return = array();
		
		foreach($this->_nodes as $node) {
			$ret['nodes'][] = $node->get_definition();
		}
		
		$return['tree_data'] = $ret;
		$return['param'] = $this->_params;
		
		return $return;
	}
}
