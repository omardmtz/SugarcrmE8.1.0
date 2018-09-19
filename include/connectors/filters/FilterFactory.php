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
 * Filter factory
 * @api
 */
class FilterFactory
{
	static $filter_map = array();

	public static function getInstance($source_name, $filter_name='')
	{
		$key = $source_name . $filter_name;
		if(empty(self::$filter_map[$key])) {

			if(empty($filter_name)){
			   $filter_name = $source_name;
			}

			if(ConnectorFactory::load($filter_name, 'filters')) {
		        $filter_name .= '_filter';
			} else {
				//if there is no override wrapper, use the default.
				$filter_name = 'default_filter';
			}

			$component = ConnectorFactory::getInstance($source_name);
			$filter = new $filter_name();
			$filter->setComponent($component);
			self::$filter_map[$key] = $filter;
		} //if
		return self::$filter_map[$key];
	}
}
