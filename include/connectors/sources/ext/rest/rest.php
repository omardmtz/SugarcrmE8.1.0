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
 * REST generic connector
 * @api
 */
abstract class ext_rest extends source{

	protected $_url;

 	protected function fetchUrl($url){
 		$data = '';
 		$data = @file_get_contents($url);
 		if(empty($data)) {
 		   $GLOBALS['log']->error("Unable to retrieve contents from url:[{$url}]");
 		}
 		return $data;
 	}

 	public function getUrl(){
 		return $this->_url;
 	}

 	public function setUrl($url){
 		$this->_url = $url;
 	}
}
?>