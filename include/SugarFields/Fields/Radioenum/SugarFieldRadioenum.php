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
 * Created: Aug 22, 2011
 */
class SugarFieldRadioenum extends SugarFieldBase {
	/**
	 * Decrypt encrypt fields values before inserting them into the emails
	 * 
	 * @param string $inputField
	 * @param mixed $vardef
	 * @param mixed $displayParams
	 * @param int $tabindex
	 * @return string 
	*/
	public function getEmailTemplateValue($inputField, $vardef, $displayParams = array(), $tabindex = 0){
		global $app_list_strings;
		
		/**
		 * If array doesn't exist for some reason, return key.
		 */
		if (!empty($app_list_strings[$vardef['options']])) {
			if (isset($app_list_strings[$vardef['options']][$inputField])) {
				return $app_list_strings[$vardef['options']][$inputField];
			}
		} 
		return $inputField;
	}
}