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

class TemplatePhone extends TemplateText{
    var $max_size = 25;
    var $type='phone';
    var $supports_unified_search = true;
    
    /**
     * __construct
     * 
     * Constructor for TemplatePhone class. This constructor ensures that TemplatePhone instances have the
     * validate_usa_format vardef value.
     */
    public function __construct()
	{
	}	
	
	/**
	 * get_field_def
	 * 
	 * @see parent::get_field_def
	 * This method checks to see if the validate_usa_format key/value entry should be
	 * added to the vardef entry representing the module
	 */	
    function get_field_def(){
		$def = parent::get_field_def();
		$def['dbType'] = 'varchar';
		
		return $def;	
	}
}


?>
