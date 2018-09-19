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


class SugarFieldPhone extends SugarFieldBase {
    
	/**
     * This should be called when the bean is saved. The bean itself will be passed by reference
     * 
     * @param SugarBean bean - the bean performing the save
     * @param array params - an array of paramester relevant to the save, most likely will be $_REQUEST
     */
	public function save($bean, $params, $field, $properties, $prefix = ''){
		 parent::save($bean, $params, $field, $properties, $prefix);
         	 	if (isset($params[$prefix.$field]))
		            $bean->$field = $params[$prefix.$field];
    }    
    
}
