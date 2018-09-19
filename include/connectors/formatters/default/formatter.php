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
/**
 * Generic formatter
 * @api
 */
class default_formatter {

   protected $_ss;
   protected $_component;
   protected $_tplFileName;
   protected $_module;
   protected $_hoverField;

   public function __construct() {}

   public function getDetailViewFormat()
   {
   	  $source = $this->_component->getSource();
   	  $class = get_class($source);
   	  $dir = str_replace('_', '/', $class);
   	  $config = $source->getConfig();
   	  $this->_ss->assign('config', $config);
   	  $this->_ss->assign('source', $class);
   	  $this->_ss->assign('module', $this->_module);
   	  $mapping = $source->getMapping();
   	  $mapping = !empty($mapping['beans'][$this->_module]) ? implode(',', array_values($mapping['beans'][$this->_module])) : '';
   	  $this->_ss->assign('mapping', $mapping);

   	  $tpl = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/formatters/{$dir}/tpls/default.tpl",
   	      "modules/Connectors/connectors/formatters/{$dir}/tpls/{$this->_module}.tpl");
   	  if(!empty($tpl)) {
   	  	return  $this->_ss->fetch($tpl);
   	  }

   	  if(strpos('_soap_', $class) !== false) {
      	 return $this->_ss->fetch("include/connectors/formatters/ext/soap/tpls/default.tpl");
      } else {
      	 return $this->_ss->fetch("include/connectors/formatters/ext/rest/tpls/default.tpl");
      }
   }

   public function getEditViewFormat() {
   	  return '';
   }

   public function getListViewFormat() {
   	  return '';
   }

   public function getSearchFormFormat() {
   	  return '';
   }

   protected function fetchSmarty()
   {
   	  $source = $this->_component->getSource();
   	  $class = get_class($source);
   	  $dir = str_replace('_', '/', $class);
   	  $config = $source->getConfig();
   	  $this->_ss->assign('config', $config);
	  $this->_ss->assign('source', $class);
	  $this->_ss->assign('module', $this->_module);

	  $tpl = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/formatters/{$dir}/tpls/default.tpl",
	      "modules/Connectors/connectors/formatters/{$dir}/tpls/{$this->_module}.tpl");
	  if(!empty($tpl)) {
	  	return $this->_ss->fetch($tpl);
	  }

	  return $this->_ss->fetch("modules/Connectors/connectors/formatters/{$dir}/tpls/default.tpl");
   }

   public function getSourceMapping(){
   	  $source = $this->_component->getSource();
      $mapping = $source->getMapping();
      return $mapping;
   }

   public function setSmarty($smarty) {
   	   $this->_ss = $smarty;
   }

   public function getSmarty() {
   	   return $this->_ss;
   }

   public function setComponent($component) {
   	   $this->_component = $component;
   }

   public function getComponent() {
   	   return $this->_component;
   }

   public function getTplFileName(){
   		return $this->tplFileName;
   }

   public function setTplFileName($tplFileName){
   		$this->tplFileName = $tplFileName;
   }

   public function setModule($module) {
   	    $this->_module = $module;
   }

   public function getModule() {
   	    return $this->_module;
   }

   public function getIconFilePath() {
   	    return '';
   }
}
?>