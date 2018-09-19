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

class TemplateParentType extends TemplateText{
    var $max_size = 25;
    var $type='parent_type';
    
    function get_field_def(){
		$def = parent::get_field_def();
		$def['dbType'] = 'varchar';
		$def['studio'] = 'hidden';
        // FIXME this is to match default flex relates vardefs. We need to document the rules.
        $def['group'] = 'parent_name';
		return $def;	
	}

}


?>
