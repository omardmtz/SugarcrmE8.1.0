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
class AjaxCompose{
	var $sections = array();
	var $crumbs = array('Home'=>'ModuleBuilder.main("Home")',/* 'Assistant'=>'Assistant.mbAssistant.xy=Array("650, 40"); Assistant.mbAssistant.show();'*/);
	function addSection($name, $title, $content, $action='activate'){
		$crumb = '';
		if($name == 'center'){
			$crumb = $this->getBreadCrumb();
		}
        if (is_array($content)) {
            $this->sections[$name] = array('title'=>$title,'crumb'=>$crumb, 'content'=>$content, 'action'=>$action);
        } else {
            $this->sections[$name] = array('title'=>$title,'crumb'=>$crumb, 'content'=>mb_detect_encoding($content, mb_detect_order(), true) == "UTF-8" ? $content : utf8_encode($content), 'action'=>$action);
        }
	}
	
	function getJavascript(){
		if(!empty($this->sections['center'])){
			 if(empty($this->sections['east']))$this->addSection('east', '', '', 'deactivate');
			 if(empty($this->sections['east2']))$this->addSection('east2', '', '', 'deactivate');
		}
		
		$json = getJSONobj();
		return $json->encode($this->sections);
	}
	
	function addCrumb($name, $action){
		$this->crumbs[$name] = $action;
	}
	
	function getBreadCrumb(){
		$crumbs = '';
		$actions = array();
		$count = 0;
		foreach($this->crumbs as $name=>$action){
			if($name == 'Home'){
				$crumbs .= "<a onclick='$action' href='javascript:void(0)'>". getStudioIcon('home', 'home', 16, 16) . '</a>';
			}else if($name=='Assistant'){
				$crumbs .= "<a id='showassist' onclick='$action' href='javascript:void(0)'>". getStudioIcon('assistant', 'assistant', 16, 16) . '</a>';
			}else{
				if($count > 0){
					$crumbs .= '&nbsp;>&nbsp;';
				}else{
					$crumbs .= '&nbsp;|&nbsp;';
				}
				if(empty($action)){
					$crumbs .="<span class='crumbLink'>$name</span>";
					$actions[] = "";
				}else {
					$crumbs .="<a href='javascript:void(0);' onclick='$action' class='crumbLink'>$name</a>";
				    $actions[] = $action;
				}
				$count++;
			}
			
		}
		if($count > 1 && $actions[$count-2] != ""){
			$crumbs = "<a onclick='{$actions[$count-2]}' href='javascript:void(0)'>". getStudioIcon('back', 'back', 16, 16) . '</a>&nbsp;'. $crumbs;	
		}
		return $crumbs . '<br><br>';
		
		
	}
	
	function echoErrorStatus($labelName=''){
		$sections = array('failure'=>true,'failMsg'=>$labelName);
		$json = getJSONobj();
		echo $json->encode($sections);
	}
	
}
?>
