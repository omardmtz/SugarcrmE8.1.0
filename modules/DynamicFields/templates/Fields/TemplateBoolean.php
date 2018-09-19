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

class TemplateBoolean extends TemplateField{
    var $default_value = '0';
    var $default = '0';
	var $type = 'bool';

	//BEGIN BACKWARDS COMPATIBILITY
function get_xtpl_edit(){
        $name = $this->name;
        $returnXTPL = array();
        if(!empty($this->help)){
            $returnXTPL[$this->name . '_help'] = translate($this->help, $this->bean->module_dir);
        }
        if(isset($this->bean->$name)){


            if(strcmp($this->bean->$name ,'1') ==0  || strcmp($this->bean->$name,'on')==0 || strcmp($this->bean->$name,'yes')==0 || strcmp($this->bean->$name, 'true')==0){
                $returnXTPL[$this->name . '_checked'] = 'checked';
                $returnXTPL[$this->name] = 'checked';
            }
        }else{

                if(empty($this->bean->id)){

                    if(!empty($this->default_value)){

                        if(!(strcmp($this->default_value,'false')==0 || strcmp($this->default_value,'no')==0 || strcmp($this->default_value,'off')==0 )){
                            $returnXTPL[$this->name . '_checked'] = 'checked';
                            $returnXTPL[$this->name] = 'checked';
                        }

                    }
                    $returnXTPL[strtoupper($this->name)] =  $this->default_value;
                }
        }



        return $returnXTPL;
    }




    function get_xtpl_search(){

        if(!empty($_REQUEST[$this->name])){
            $returnXTPL = array();

            if($_REQUEST[$this->name] == '1' || $_REQUEST[$this->name] == 'on' || $_REQUEST[$this->name] == 'yes'){
                $returnXTPL[$this->name . '_checked'] = 'checked';
                $returnXTPL[$this->name] = 'checked';
            }
            return $returnXTPL;

        }
        return '';
    }

   function get_xtpl_detail(){
        $name = $this->name;
        $returnXTPL = array();
        if(!empty($this->help)){
            $returnXTPL[$this->name . '_help'] = translate($this->help, $this->bean->module_dir);
        }
        $returnXTPL[$this->name . '_checked'] = '';
        $returnXTPL[$this->name] = '';

        if(isset($this->bean->$name)){
            if(strcmp($this->bean->$name ,'1') ==0  || strcmp($this->bean->$name,'on')==0 || strcmp($this->bean->$name,'yes')==0 || strcmp($this->bean->$name, 'true')==0){
                $returnXTPL[$this->name . '_checked'] = 'checked';
                $returnXTPL[$this->name] = 'checked';
            }
        }
        return $returnXTPL;
    }
    function get_xtpl_list(){
        return $this->get_xtpl_edit();
    }

    /**
     * {@inheritDoc}
     */
    public function get_field_def()
    {
        $def = parent::get_field_def();

        // The default value is stored in database as string,
        // however from domain standpoint it has to be boolean
        // @see Data.Validation#requiredValidator()
        $def['default'] = isTruthy($def['default']);

        return $def;
    }
}
