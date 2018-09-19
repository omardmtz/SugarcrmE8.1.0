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
 * Formatter factory
 * @api
 */
class FormatterFactory {

	static $formatter_map = array();

	/**
	 * getInstance
	 * This method returns a formatter instance for the given source name and
	 * formatter name.  If no formatter name is specified, the default formatter
	 * for the source is used.
	 *
	 * @param $source_name The data source name to retreive formatter for
	 * @param $formatter_name Optional formatter name to use
	 * @param $wrapper_name Optional wrapper name to use
	 * @return $instance The formatter instance
	 */
	public static function getInstance($source_name, $formatter_name=''){
		$key = $source_name . $formatter_name;
		if(empty(self::$formatter_map[$key])) {

			if(empty($formatter_name)){
			   $formatter_name = $source_name;
			}

			$dir = str_replace('_','/',$formatter_name);
			$parts = explode("/", $dir);
			$file = array_pop($parts);

			if(ConnectorFactory::load($formatter_name, 'formatters')) {
				$formatter_name .= '_formatter';
			} else {
				//if there is no override wrapper, use the default.
				$formatter_name = 'default_formatter';
			}

			$component = ConnectorFactory::getInstance($source_name);
			$formatter = new $formatter_name();
			$formatter->setComponent($component);

			$tpl = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/formatters/{$dir}/tpls/{$file}.tpl");
			if(!empty($tpl)) {
			    $formatter->setTplFileName($tpl);
			}
			self::$formatter_map[$key] = $formatter;
		} //if
		return self::$formatter_map[$key];
	}

}
?>