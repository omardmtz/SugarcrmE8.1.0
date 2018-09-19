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
 * Generic filter
 * @api
 */
class default_filter {

var $_component;

public function setComponent($component) {
   	$this->_component = $component;
}

public function getList($args, $module) {
	$args = $this->_component->mapInput($args, $module);
	return $this->_component->getSource()->getList($args, $module);
}

}
