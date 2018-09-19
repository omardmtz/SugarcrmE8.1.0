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

class TemplateHTML extends TemplateField{
    var $data_type = 'html';
    var $type = 'html';
    // Size and Len need to be empty to prevent validation errors on the client
    var $size = '';
    var $len = '';
    
    function save($df)
    {
        $this->ext3 = 'text';
        // clean the field of any dangerous html tags like the script tag, etc
        $this->ext4 = SugarCleaner::cleanHtml($this->ext4, true);
        parent::save($df);
    }
	
	function set($values){
       parent::set($values);
       if(!empty($this->ext4)){
           $this->default_value = $this->ext4;
           $this->default = $this->ext4;
       }
        
    }
    
    function get_html_detail(){
      
        return '<div title="' . strtoupper($this->name . '_HELP'). '" >{'.strtoupper($this->name) . '}</div>';
    }
    
    function get_html_edit(){
        return $this->get_html_detail();
    }
    
    function get_html_list(){
        return $this->get_html_detail();
    }
    
    function get_html_search(){
        return $this->get_html_detail();
    }
    
    function get_xtpl_detail(){
        
        return from_html(nl2br($this->ext4));   
    }
    
    function get_xtpl_edit(){
       return  $this->get_xtpl_detail();
    }
    
    function get_xtpl_list(){
        return  $this->get_xtpl_detail();
    }
    function get_xtpl_search(){
        return  $this->get_xtpl_detail();
    }
    
    function get_db_add_alter_table($table){
        return '';
    }

    function get_db_modify_alter_table($table){
        return '';
    }
    

    function get_db_delete_alter_table($table)
    {
        return '' ;
    }
    
    function get_field_def() {
        $def = parent::get_field_def();
        if(!empty($this->ext4)){
       		$def['default_value'] = $this->ext4;
        	$def['default'] = $this->ext4;
        }
        $def['studio'] = 'visible';
        $def['source'] = 'non-db';
		$def['dbType'] = isset($this->ext3) ? $this->ext3 : 'text' ;
        return array_merge($def, $this->get_additional_defs());
    }
    
    
}


?>
